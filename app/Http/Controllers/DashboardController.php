<?php

namespace App\Http\Controllers;

use App\Models\CalculationSnapshot;
use App\Models\Criteria;
use App\Models\Laptop;
use App\Models\MfepResult;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLaptops   = Laptop::all()->count();
        $totalCriteria  = Criteria::all()->count();
        $totalResults   = MfepResult::all()->count();
        $totalSnapshots = CalculationSnapshot::all()->count();

        $criteria    = Criteria::orderBy('code')->orderBy('id')->get();
        $totalWeight = (float) $criteria->sum('weight');
        $weightValid = abs($totalWeight - 1.0) < 0.001;

        $topResults = MfepResult::with('laptop')
            ->orderBy('rank')
            ->take(3)
            ->get();

        $lastResult       = MfepResult::latest('calculated_at')->first();
        $lastCalculatedAt = $lastResult ? $lastResult->calculated_at : null;
        $latestSnapshot   = CalculationSnapshot::latest('created_at')->first();

        return view('dashboard.index', compact(
            'totalLaptops',
            'totalCriteria',
            'totalResults',
            'totalSnapshots',
            'criteria',
            'totalWeight',
            'weightValid',
            'topResults',
            'lastCalculatedAt',
            'latestSnapshot'
        ));
    }
}
