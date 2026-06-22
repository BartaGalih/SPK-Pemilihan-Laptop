<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\Laptop;
use App\Models\MfepResult;
use Illuminate\Support\Collection;

/**
 * MfepEngine
 *
 * Mengimplementasikan Multi Factor Evaluation Process (MFEP):
 *   NEF  = normalisasi nilai atribut ke skala 1–5 berdasarkan ranking
 *   NBE  = NBF × NEF
 *   TBE  = Σ NBE
 */
class MfepEngine
{
    /** Kode kolom model Laptop yang dipetakan ke kode kriteria */
    private const COLUMN_MAP = [
        'C1' => 'price',
        'C2' => 'ram',
        'C3' => 'cpu_score',
        'C4' => 'weight_kg',
        'C5' => 'storage',
        'C6' => 'battery',
    ];

    /**
     * Jalankan kalkulasi MFEP untuk semua laptop,
     * simpan hasilnya ke tabel mfep_results, kembalikan koleksi hasil.
     */
    public function calculate(): Collection
    {
        $laptops  = Laptop::all();
        $criteria = Criteria::orderBy('code')->get()->keyBy('code');

        if ($laptops->isEmpty() || $criteria->isEmpty()) {
            return collect();
        }

        // 1. Hitung NEF (skala 1–5) berdasarkan ranking per kriteria
        $nefMatrix = $this->buildNefMatrix($laptops, $criteria);

        // 2. Hitung NBE & TBE, simpan ke DB
        MfepResult::query()->delete(); // hapus hasil lama

        $results = [];

        foreach ($laptops as $laptop) {
            $nbePerCriteria = [];
            $tbe = 0;

            foreach ($criteria as $code => $criterion) {
                $nef = $nefMatrix[$laptop->id][$code] ?? 0;
                $nbe = round($criterion->weight * $nef, 4);
                $nbePerCriteria[$code] = ['nef' => $nef, 'nbe' => $nbe];
                $tbe += $nbe;
            }

            $results[$laptop->id] = [
                'laptop'          => $laptop,
                'nbePerCriteria'  => $nbePerCriteria,
                'tbe'             => round($tbe, 4),
            ];
        }

        // 3. Urutkan berdasarkan TBE tertinggi → tetapkan rank
        uasort($results, fn($a, $b) => $b['tbe'] <=> $a['tbe']);

        $rank = 1;
        foreach ($results as $laptopId => $data) {
            $row = [
                'laptop_id'    => $laptopId,
                'rank'         => $rank++,
                'tbe'          => $data['tbe'],
                'calculated_at'=> now(),
            ];

            foreach ($criteria as $code => $criterion) {
                $cNum = strtolower($code);
                $row["nef_{$cNum}"] = $data['nbePerCriteria'][$code]['nef'];
                $row["nbe_{$cNum}"] = $data['nbePerCriteria'][$code]['nbe'];
            }

            MfepResult::create($row);
        }

        // 4. Kembalikan hasil yang sudah diurutkan, dengan relasi
        return MfepResult::with('laptop')->orderBy('rank')->get();
    }

    // ---------------------------------------------------------------
    // PRIVATE HELPERS
    // ---------------------------------------------------------------

    /**
     * Bangun matriks NEF[laptop_id][code] menggunakan ranking rank-proportional.
     *
     * Untuk tipe BENEFIT: nilai lebih besar → rank lebih tinggi → NEF lebih tinggi
     * Untuk tipe COST   : nilai lebih kecil → rank lebih tinggi → NEF lebih tinggi
     *
     * Konversi rank ke skala 1–5:
     *   NEF = 1 + (rank - 1) / (n - 1) * 4   (bila n > 1)
     *   NEF = 5                               (bila n = 1)
     */
    private function buildNefMatrix(Collection $laptops, Collection $criteria): array
    {
        $n      = $laptops->count();
        $matrix = [];

        foreach ($criteria as $code => $criterion) {
            $column = self::COLUMN_MAP[$code] ?? null;
            if (!$column) continue;

            // Kumpulkan nilai unik & urutkan
            $values = $laptops->pluck($column, 'id')->toArray(); // [id => value]

            // Buat salinan untuk diurutkan
            $sorted = $values;
            if ($criterion->type === 'benefit') {
                arsort($sorted); // DESC: nilai besar di depan → rank 1 (NEF tertinggi)
            } else {
                asort($sorted);  // ASC : nilai kecil di depan → rank 1 (NEF tertinggi)
            }

            // Assign rank (1 = terbaik) dengan tie-handling (rank sama jika nilai sama)
            $rankMap   = [];
            $prevValue = null;
            $prevRank  = 0;
            $position  = 1;

            foreach ($sorted as $id => $value) {
                if ($value === $prevValue) {
                    $rankMap[$id] = $prevRank; // tie: rank sama
                } else {
                    $rankMap[$id] = $position;
                    $prevRank     = $position;
                    $prevValue    = $value;
                }
                $position++;
            }

            // Tentukan rank terbesar (worst) untuk normalisasi
            $maxRank = max($rankMap);

            foreach ($laptops as $laptop) {
                $rank = $rankMap[$laptop->id] ?? $maxRank;

                // rank=1 (terbaik) → NEF=5, rank=maxRank (terburuk) → NEF=1
                if ($maxRank === 1) {
                    $nef = 5.0;
                } else {
                    $nef = 5 - (($rank - 1) / ($maxRank - 1)) * 4;
                }

                $matrix[$laptop->id][$code] = round($nef, 2);
            }
        }

        return $matrix;
    }
}
