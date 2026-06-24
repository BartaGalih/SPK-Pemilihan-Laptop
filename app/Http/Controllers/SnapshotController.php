<?php

namespace App\Http\Controllers;

use App\Models\CalculationSnapshot;

class SnapshotController extends Controller
{
    public function index()
    {
        $snapshots = CalculationSnapshot::latest()->paginate(10);
        return view('snapshots.index', compact('snapshots'));
    }

    public function show(CalculationSnapshot $snapshot)
    {
        $snapshot->load([
            'criteria',
            'laptops' => fn ($q) => $q->orderBy('rank'),
            'laptops.values',
        ]);

        return view('snapshots.show', compact('snapshot'));
    }

    public function destroy(CalculationSnapshot $snapshot)
    {
        $snapshot->delete(); // cascade ke turunan
        return redirect()->route('snapshots.index')
            ->with('success', 'Arsip hasil berhasil dihapus.');
    }
}
