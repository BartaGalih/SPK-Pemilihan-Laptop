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
     * Tampilkan halaman hasil rekomendasi terakhir (atau kosong jika belum dihitung).
     */
    public function index()
    {
        $results  = MfepResult::with('laptop')->orderBy('rank')->get();
        $criteria = Criteria::orderBy('code')->get();

        return view('results.index', compact('results', 'criteria'));
    }

    /**
     * Jalankan kalkulasi MFEP dan redirect kembali ke hasil.
     */
    public function calculate()
    {
        $this->engine->calculate();

        return redirect()->route('results.index')
                         ->with('success', 'Perhitungan MFEP berhasil dijalankan!');
    }
}
