<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\MfepResult;
use App\Services\MfepEngine;
use Illuminate\Http\Request;

class MfepController extends Controller
{
    protected MfepEngine $engine;

    public function __construct(MfepEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Tampilkan halaman hasil rekomendasi terakhir.
     */
    public function index()
    {
        $results     = MfepResult::with(['laptop', 'details'])->orderBy('rank')->get();
        $criteria    = Criteria::orderBy('code')->orderBy('id')->get();
        $totalWeight = (float) $criteria->sum('weight');
        $weightValid = abs($totalWeight - 1.0) < 0.001;

        return view('results.index', compact('results', 'criteria', 'totalWeight', 'weightValid'));
    }

    /**
     * Jalankan kalkulasi MFEP. Diblokir bila total bobot != 1.00.
     */
    public function calculate()
    {
        if (!$this->engine->isWeightValid()) {
            return redirect()->route('results.index')->with('danger', sprintf(
                'Perhitungan dibatalkan: total bobot kriteria = %.2f, harus = 1.00 terlebih dahulu.',
                $this->engine->totalWeight()
            ));
        }

        $results = $this->engine->calculate();

        if ($results->isEmpty()) {
            return redirect()->route('results.index')
                ->with('warning', 'Tidak ada data untuk dihitung. Pastikan data laptop dan kriteria sudah terisi.');
        }

        return redirect()->route('results.index')
            ->with('success', 'Perhitungan MFEP berhasil dijalankan!');
    }

    /**
     * Simpan hasil perhitungan terakhir sebagai arsip/snapshot.
     */
    public function saveSnapshot(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'note'  => 'nullable|string|max:500',
        ]);

        if (MfepResult::count() === 0) {
            return redirect()->route('results.index')
                ->with('danger', 'Belum ada hasil yang bisa disimpan. Jalankan perhitungan terlebih dahulu.');
        }

        $snapshot = $this->engine->snapshot($validated['title'], $validated['note'] ?? null);

        return redirect()->route('snapshots.show', $snapshot)
            ->with('success', 'Hasil perhitungan berhasil disimpan ke arsip.');
    }
}
