<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $areas = \Illuminate\Support\Facades\Cache::remember('master_areas', 3600, function() {
            return \App\Models\ZiArea::withCount('components')->get();
        });
        return view('master-data.index', compact('areas'));
    }

    public function storeArea(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'code' => 'required',
            'name' => 'required',
        ]);

        \App\Models\ZiArea::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        \Illuminate\Support\Facades\Cache::forget('master_areas');
        \Illuminate\Support\Facades\Cache::forget('all_areas');

        return back()->with('success', 'Komponen berhasil ditambahkan.');
    }

    public function editArea($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $area = \App\Models\ZiArea::findOrFail($id);
        return view('master-data.edit-area', compact('area'));
    }

    public function updateArea(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $area = \App\Models\ZiArea::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:zi_areas,code,' . $area->id,
            'name' => 'required',
        ]);

        $area->update($request->only(['code', 'name']));
        
        \Illuminate\Support\Facades\Cache::forget('master_areas');
        \Illuminate\Support\Facades\Cache::forget('all_areas');

        return redirect()->route('master-data.index')->with('success', 'Komponen berhasil diperbarui.');
    }

    public function destroyArea($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $area = \App\Models\ZiArea::findOrFail($id);
        $area->delete();
        
        \Illuminate\Support\Facades\Cache::forget('master_areas');
        \Illuminate\Support\Facades\Cache::forget('all_areas');

        return back()->with('success', 'Komponen berhasil dihapus.');
    }

    public function components($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $area = \App\Models\ZiArea::findOrFail($id);
        $components = \Illuminate\Support\Facades\Cache::remember("area_components_{$id}", 3600, function() use ($area) {
            return $area->components()->get();
        });
        return view('master-data.components', compact('area', 'components'));
    }

    public function storeComponent(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'zi_area_id' => 'required|exists:zi_areas,id',
            'code' => 'required',
            'name' => 'required',
        ]);

        \App\Models\ZiComponent::create($request->only(['zi_area_id', 'code', 'name']));

        \Illuminate\Support\Facades\Cache::forget("area_components_{$request->zi_area_id}");
        \Illuminate\Support\Facades\Cache::forget('master_areas'); // Count changes

        return back()->with('success', 'Sub Komponen berhasil ditambahkan.');
    }

    public function editComponent($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $ziComponent = \App\Models\ZiComponent::with('area')->findOrFail($id);
        return view('master-data.edit-component', compact('ziComponent'));
    }

    public function updateComponent(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ziComponent = \App\Models\ZiComponent::findOrFail($id);

        $request->validate([
            'code' => 'required',
            'name' => 'required',
        ]);

        $ziComponent->update($request->only(['code', 'name']));

        \Illuminate\Support\Facades\Cache::forget("area_components_{$ziComponent->zi_area_id}");

        return redirect()->route('master-data.components', ['area' => $ziComponent->zi_area_id])->with('success', 'Sub Komponen berhasil diperbarui.');
    }

    public function destroyComponent($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $ziComponent = \App\Models\ZiComponent::findOrFail($id);
        $areaId = $ziComponent->zi_area_id;
        $ziComponent->delete();

        \Illuminate\Support\Facades\Cache::forget("area_components_{$areaId}");
        \Illuminate\Support\Facades\Cache::forget('master_areas');

        return back()->with('success', 'Sub Komponen berhasil dihapus.');
    }


}

