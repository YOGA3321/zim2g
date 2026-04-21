<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $cacheKey = 'archives_' . md5(json_encode($request->all()));
        
        $archives = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function() use ($request) {
            $query = \App\Models\Archive::with(['user', 'subComponent.component.area']);
            
            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('area_id')) {
                $query->whereHas('subComponent.component', function($q) use ($request) {
                    $q->where('zi_area_id', $request->area_id);
                });
            }
            
            return $query->latest()->get();
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
            ->with('subComponent.component.area')
            ->latest()
            ->get();
        return view('archives.my', compact('archives'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'User cannot add archives.');
        }
        $areas = \App\Models\ZiArea::with('components.subComponents')->get();
        return view('archives.create', compact('areas'));
    }

    public function store(\Illuminate\Http\Request $request, \App\Services\GoogleDriveService $driveService)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'User cannot add archives.');
        }

        $request->validate([
            'zi_sub_component_id' => 'required|exists:zi_sub_components,id',
            'year' => 'required|integer',
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        // Save to temp
        $tempPath = $file->storeAs('temp', $fileName);
        $fullPath = storage_path('app/' . $tempPath);

        try {
            // Upload to Google Drive
            $googleFile = $driveService->upload($fullPath, $file->getClientOriginalName());
            
            \App\Models\Archive::create([
                'user_id' => auth()->id(),
                'zi_sub_component_id' => $request->zi_sub_component_id,
                'year' => $request->year,
                'file_name' => $file->getClientOriginalName(),
                'google_drive_file_id' => $googleFile->id,
                'google_drive_link' => $googleFile->webViewLink,
                'description' => $request->description,
            ]);

            // Delete from temp
            unlink($fullPath);

            // Clear Cache
            \Illuminate\Support\Facades\Cache::flush(); // Flush is safer for dynamic filter keys

            return redirect()->route('dashboard')->with('success', 'Arsip berhasil diunggah.');
        } catch (\Exception $e) {
            // Cleanup temp if failed
            if (file_exists($fullPath)) unlink($fullPath);
            return back()->withErrors(['file' => 'Gagal mengunggah ke Google Drive: ' . $e->getMessage()]);
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
