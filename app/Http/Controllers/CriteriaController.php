<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CriteriaController extends Controller
{
    public function index()
    {
        $criteria    = Criteria::orderBy('code')->orderBy('id')->get();
        $totalWeight = (float) $criteria->sum('weight');

        return view('criteria.index', compact('criteria', 'totalWeight'));
    }

    public function create()
    {
        $totalWeight = (float) Criteria::sum('weight');
        $remaining   = round(max(0, 1 - $totalWeight), 2);

        return view('criteria.create', compact('totalWeight', 'remaining'));
    }

    public function store(Request $request)
    {
        $totalWeight = (float) Criteria::sum('weight');
        $remaining   = round(max(0, 1 - $totalWeight), 2);

        $validated = $request->validate([
            'name'   => 'required|string|max:100',
            'unit'   => 'nullable|string|max:20',
            'type'   => ['required', Rule::in(['benefit', 'cost'])],
            'weight' => "required|numeric|min:0.01|max:{$remaining}",
        ], [
            'weight.max' => "Bobot melebihi sisa yang tersedia ({$remaining}). Kurangi bobot kriteria lain terlebih dahulu.",
        ]);

        $validated['code'] = $this->nextCode();
        Criteria::create($validated);

        return redirect()->route('criteria.index')
            ->with('success', 'Kriteria baru berhasil ditambahkan.');
    }

    public function edit(Criteria $criterium)
    {
        $totalWeight   = (float) Criteria::sum('weight');
        // sisa yang boleh dipakai = 1 - total + bobot kriteria ini sendiri
        $remaining     = round(max(0, 1 - $totalWeight + $criterium->weight), 2);

        return view('criteria.edit', compact('criterium', 'totalWeight', 'remaining'));
    }

    public function update(Request $request, Criteria $criterium)
    {
        $totalWeight = (float) Criteria::where('id', '!=', $criterium->id)->sum('weight');
        $remaining   = round(max(0, 1 - $totalWeight), 2);

        $validated = $request->validate([
            'name'   => 'required|string|max:100',
            'unit'   => 'nullable|string|max:20',
            'type'   => ['required', Rule::in(['benefit', 'cost'])],
            'weight' => "required|numeric|min:0.01|max:{$remaining}",
        ], [
            'weight.max' => "Bobot melebihi sisa yang tersedia ({$remaining}). Kurangi bobot kriteria lain terlebih dahulu.",
        ]);

        $criterium->update($validated);

        $newTotal = (float) Criteria::sum('weight');
        if (abs($newTotal - 1.0) > 0.001) {
            return redirect()->route('criteria.index')->with('warning', sprintf(
                'Kriteria diperbarui. Total bobot saat ini = %.2f (seharusnya = 1.00).',
                $newTotal
            ));
        }

        return redirect()->route('criteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Criteria $criterium)
    {
        $criterium->delete(); // soft delete (arsip tetap konsisten)

        return redirect()->route('criteria.index')
            ->with('success', "Kriteria \"{$criterium->name}\" berhasil dihapus. Jangan lupa menyesuaikan bobot agar total = 1.00.");
    }

    /**
     * Hasilkan kode kriteria berikutnya (C1, C2, ...).
     */
    private function nextCode(): string
    {
        $max = 0;
        foreach (Criteria::withTrashed()->pluck('code') as $code) {
            if (preg_match('/^C(\d+)$/', (string) $code, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }
        return 'C' . ($max + 1);
    }
}
