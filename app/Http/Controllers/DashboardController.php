<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalArchives = \App\Models\Archive::count();
        $years = [2025, 2026, 2027, 2028];
        $yearStats = [];
        foreach ($years as $year) {
            $yearStats[$year] = \App\Models\Archive::where('year', $year)->count();
        }

        return view('dashboard', [
            'totalArchives' => $totalArchives,
            'years' => $years,
            'yearStats' => $yearStats,
        ]);
    }
}
