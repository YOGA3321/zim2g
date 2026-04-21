<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'archives_' . md5(json_encode($request->all()));
        
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


    public function myArchives()
    {
        $archives = \App\Models\Archive::where('user_id', auth()->id())
            ->with('component.area')
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat membuat wadah arsip.');
        }

        $request->validate([
            'zi_component_id' => 'required|exists:zi_components,id',
            'year' => 'required|integer',
            'file' => 'nullable|file',
        ]);

        $archiveData = [
            'user_id' => auth()->id(),
            'zi_component_id' => $request->zi_component_id,
            'year' => $request->year,
            'description' => $request->description,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            if (!\Illuminate\Support\Facades\Storage::exists('temp')) {
                \Illuminate\Support\Facades\Storage::makeDirectory('temp');
            }
            $tempPath = $file->storeAs('temp', $fileName);
            $fullPath = \Illuminate\Support\Facades\Storage::path($tempPath);

            try {
                $googleFile = $driveService->upload($fullPath, $file->getClientOriginalName());
                $archiveData['file_name'] = $file->getClientOriginalName();
                $archiveData['google_drive_file_id'] = $googleFile->id;
                $archiveData['google_drive_link'] = $googleFile->webViewLink;
                unlink($fullPath);
            } catch (\Exception $e) {
                if (file_exists($fullPath)) unlink($fullPath);
                return back()->withErrors(['file' => 'Gagal mengunggah ke Google Drive: ' . $e->getMessage()]);
            }
        }

        \App\Models\Archive::create($archiveData);
        \Illuminate\Support\Facades\Cache::flush();

        return redirect()->route('dashboard')->with('success', 'Arsip berhasil dibuat.');
    }

    public function updateFile(Request $request, $id, \App\Services\GoogleDriveService $driveService)
    {
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
            $googleFile = $driveService->upload($fullPath, $file->getClientOriginalName());
            
            $archive->update([
                'file_name' => $file->getClientOriginalName(),
                'google_drive_file_id' => $googleFile->id,
                'google_drive_link' => $googleFile->webViewLink,
            ]);

            unlink($fullPath);
            \Illuminate\Support\Facades\Cache::flush();

            return back()->with('success', 'File berhasil diunggah ke arsip.');
        } catch (\Exception $e) {
            if (file_exists($fullPath)) unlink($fullPath);
            return back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $archive = \App\Models\Archive::findOrFail($id);
        
        if (auth()->user()->role !== 'admin' && $archive->user_id !== auth()->id()) {
            abort(403);
        }

        $archive->delete();
        \Illuminate\Support\Facades\Cache::flush();
        
        return back()->with('success', 'Arsip berhasil dihapus.');
    }
}
