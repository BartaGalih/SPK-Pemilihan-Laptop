<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index()
    {
        $criteria = Criteria::orderBy('code')->get();
        return view('criteria.index', compact('criteria'));
    }

    public function edit(Criteria $criterium)
    {
        return view('criteria.edit', compact('criterium'));
    }

    public function update(Request $request, Criteria $criterium)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'unit'   => 'nullable|string|max:20',
            'type'   => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0.01|max:1',
        ]);

        $criterium->update($request->only('name', 'unit', 'type', 'weight'));

        // Validasi total bobot
        $totalWeight = Criteria::sum('weight');
        if (abs($totalWeight - 1.0) > 0.001) {
            return back()->with('warning', sprintf(
                'Data tersimpan, namun total bobot saat ini = %.2f (seharusnya = 1.00). Harap sesuaikan bobot lainnya.',
                $totalWeight
            ));
        }

        return redirect()->route('criteria.index')
                         ->with('success', 'Bobot kriteria berhasil diperbarui.');
    }
}
