<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'archives_v2_' . md5(json_encode($request->all()));
        
        $archives = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function() use ($request) {
            $query = \App\Models\Archive::with(['user', 'component.area']);
            
            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('area_id')) {
                $query->whereHas('component', function($q) use ($request) {
                    $q->where('zi_area_id', $request->area_id);
                });
            }
            
            return $query->latest()->paginate(10);
        });

        $areas = \Illuminate\Support\Facades\Cache::remember('all_areas', 3600, function() {
            return \App\Models\ZiArea::all();
        });
        
        $users = \Illuminate\Support\Facades\Cache::remember('all_users', 3600, function() {
            return \App\Models\User::all();
        });

        return view('archives.index', compact('archives', 'areas', 'users'));
    }


    public function show($id)
    {
        $archive = \App\Models\Archive::with(['component.area', 'user', 'files'])->findOrFail($id);
        return view('archives.show', compact('archive'));
    }

    public function myArchives()
    {
        $archives = \App\Models\Archive::where('user_id', auth()->id())
            ->with(['component.area', 'files'])
            ->latest()
            ->get();
        return view('archives.my', compact('archives'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'User cannot add archives.');
        }
        $areas = \App\Models\ZiArea::with('components')->get();
        return view('archives.create', compact('areas'));
    }

    public function store(Request $request, \App\Services\GoogleDriveService $driveService)
    {
        if (!$driveService->isReady()) {
            return back()->with('error', 'Layanan Google Drive belum siap. Silakan hubungi admin untuk konfigurasi.');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat membuat wadah arsip.');
        }

        $request->validate([
            'zi_component_id' => 'required|exists:zi_components,id',
            'year' => 'required|integer',
            'description' => 'nullable|string',
            'file' => 'nullable|file',
        ]);

        $component = \App\Models\ZiComponent::with('area')->findOrFail($request->zi_component_id);
        $folderName = sprintf("[%s] %s - %s (%s)", 
            $request->year, 
            $component->area->code, 
            $component->name, 
            now()->format('His')
        );

        try {
            // 1. Create Folder as "Wadah"
            $googleFolder = $driveService->createFolder($folderName);
            
            // Set Folder to Public (Inherited by all files inside)
            $driveService->setPublic($googleFolder->id);
            
            // 2. Create Archive Entry
            $archive = \App\Models\Archive::create([
                'user_id' => auth()->id(),
                'zi_component_id' => $request->zi_component_id,
                'year' => $request->year,
                'google_drive_folder_id' => $googleFolder->id,
                'description' => $request->description,
            ]);

            // 3. Upload File if exists
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                if (!\Illuminate\Support\Facades\Storage::exists('temp')) {
                    \Illuminate\Support\Facades\Storage::makeDirectory('temp');
                }
                $tempPath = $file->storeAs('temp', $fileName);
                $fullPath = \Illuminate\Support\Facades\Storage::path($tempPath);

                $googleFile = $driveService->upload($fullPath, $file->getClientOriginalName(), $googleFolder->id);
                
                \App\Models\ArchiveFile::create([
                    'archive_id' => $archive->id,
                    'user_id' => auth()->id(),
                    'file_name' => $file->getClientOriginalName(),
                    'google_drive_file_id' => $googleFile->id,
                    'google_drive_link' => $googleFile->webViewLink,
                ]);

                unlink($fullPath);
            }

            \Illuminate\Support\Facades\Cache::flush();
            return redirect()->route('archives.index')->with('success', 'Wadah arsip berhasil dibuat.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membuat wadah di Google Drive: ' . $e->getMessage()]);
        }
    }

    public function updateFile(Request $request, $id, \App\Services\GoogleDriveService $driveService)
    {
        if (!$driveService->isReady()) {
            return response()->json(['error' => 'Layanan Google Drive belum siap.'], 400);
        }

        $request->validate([
            'file' => 'required|file',
        ]);

        $archive = \App\Models\Archive::findOrFail($id);
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        if (!\Illuminate\Support\Facades\Storage::exists('temp')) {
            \Illuminate\Support\Facades\Storage::makeDirectory('temp');
        }
        $tempPath = $file->storeAs('temp', $fileName);
        $fullPath = \Illuminate\Support\Facades\Storage::path($tempPath);

        try {
            // Upload to the archive's specific folder
            $uploadedFile = $driveService->upload($fullPath, $file->getClientOriginalName(), $archive->google_drive_folder_id);
            
            $archive->files()->create([
                'user_id' => auth()->id(),
                'file_name' => $file->getClientOriginalName(),
                'google_drive_file_id' => $uploadedFile->id,
                'google_drive_link' => $uploadedFile->webViewLink,
            ]);

            unlink($fullPath);
            \Illuminate\Support\Facades\Cache::flush();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            if (file_exists($fullPath)) unlink($fullPath);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroyFile($id, \App\Services\GoogleDriveService $driveService)
    {
        if (!$driveService->isReady()) {
            return back()->with('error', 'Layanan Google Drive belum siap.');
        }

        $file = \App\Models\ArchiveFile::findOrFail($id);
        $archiveId = $file->archive_id;

        // Cek permission (Admin atau Pemilik wadah)
        if (auth()->user()->role !== 'admin' && $file->archive->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus berkas ini.');
        }

        // Hapus di Drive
        $driveService->deleteFile($file->google_drive_file_id);

        // Hapus di DB
        $file->delete();

        \Illuminate\Support\Facades\Cache::flush();
        return back()->with('success', 'Berkas berhasil dihapus dari sistem dan Google Drive.');
    }

    public function destroy($id, \App\Services\GoogleDriveService $driveService)
    {
        if (!$driveService->isReady()) {
            return back()->with('error', 'Layanan Google Drive belum siap.');
        }

        $archive = \App\Models\Archive::findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus wadah arsip.');
        }

        // 1. Hapus Folder di Google Drive (Otomatis menghapus semua file di dalamnya)
        if ($archive->google_drive_folder_id) {
            $driveService->deleteFile($archive->google_drive_folder_id);
        }

        // 2. Hapus data di DB (Cascade akan menghapus data di archive_files)
        $archive->delete();

        \Illuminate\Support\Facades\Cache::flush();
        return redirect()->route('archives.index')->with('success', 'Wadah arsip dan seluruh isinya di Google Drive berhasil dihapus.');
    }
}
