<?php

namespace App\Services;

use App\Models\CalculationSnapshot;
use App\Models\Criteria;
use App\Models\Laptop;
use App\Models\LaptopValue;
use App\Models\MfepResult;
use App\Models\MfepResultDetail;
use App\Models\SnapshotCriterion;
use App\Models\SnapshotLaptop;
use App\Models\SnapshotLaptopValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * MfepEngine
 *
 * Multi Factor Evaluation Process (MFEP) — versi DINAMIS.
 * Kriteria tidak lagi hardcode (C1–C6); engine membaca seluruh kriteria
 * aktif beserta nilai laptop dari tabel laptop_values (EAV).
 *
 *   NEF = normalisasi nilai atribut ke skala 1–5 berdasarkan ranking
 *   NBE = NBF × NEF
 *   TBE = Σ NBE
 */
class MfepEngine
{
    /**
     * Apakah total bobot kriteria aktif = 1.00 (toleransi kecil)?
     */
    public function isWeightValid(): bool
    {
        return abs($this->totalWeight() - 1.0) < 0.001;
    }

    public function totalWeight(): float
    {
        return (float) Criteria::sum('weight');
    }

    /**
     * Jalankan kalkulasi MFEP untuk semua laptop & kriteria aktif.
     * Hasil disimpan ke mfep_results + mfep_result_details.
     */
    public function calculate(): Collection
    {
        $laptops  = Laptop::with('values')->get();
        $criteria = Criteria::orderBy('code')->orderBy('id')->get();

        if ($laptops->isEmpty() || $criteria->isEmpty()) {
            return collect();
        }

        // 1. NEF per kriteria (skala 1–5 berdasarkan ranking)
        $nefMatrix = $this->buildNefMatrix($laptops, $criteria);

        // 2. Hitung NBE & TBE
        $computed = [];
        foreach ($laptops as $laptop) {
            $tbe     = 0;
            $details = [];
            foreach ($criteria as $criterion) {
                $nef = $nefMatrix[$laptop->id][$criterion->id] ?? 0;
                $nbe = round($criterion->weight * $nef, 4);
                $details[$criterion->id] = ['nef' => $nef, 'nbe' => $nbe];
                $tbe += $nbe;
            }
            $computed[$laptop->id] = ['tbe' => round($tbe, 4), 'details' => $details];
        }

        // 3. Urutkan berdasarkan TBE tertinggi → tetapkan rank
        uasort($computed, fn ($a, $b) => $b['tbe'] <=> $a['tbe']);

        // 4. Simpan (replace hasil lama)
        DB::transaction(function () use ($computed) {
            MfepResult::query()->delete(); // cascade ke details

            $rank = 1;
            foreach ($computed as $laptopId => $data) {
                $result = MfepResult::create([
                    'laptop_id'     => $laptopId,
                    'rank'          => $rank++,
                    'tbe'           => $data['tbe'],
                    'calculated_at' => now(),
                ]);

                foreach ($data['details'] as $criteriaId => $d) {
                    MfepResultDetail::create([
                        'mfep_result_id' => $result->id,
                        'criteria_id'    => $criteriaId,
                        'nef'            => $d['nef'],
                        'nbe'            => $d['nbe'],
                    ]);
                }
            }
        });

        return MfepResult::with(['laptop', 'details'])->orderBy('rank')->get();
    }

    /**
     * Simpan snapshot/arsip dari hasil perhitungan terakhir.
     * Menyalin kriteria, laptop, nilai, dan hasil ke tabel snapshot
     * sehingga independen terhadap perubahan data master.
     */
    public function snapshot(string $title, ?string $note = null): CalculationSnapshot
    {
        $criteria = Criteria::orderBy('code')->orderBy('id')->get();
        $results  = MfepResult::with(['laptop.values', 'details'])->orderBy('rank')->get();

        return DB::transaction(function () use ($title, $note, $criteria, $results) {
            $snapshot = CalculationSnapshot::create([
                'title'          => $title,
                'note'           => $note,
                'total_laptops'  => $results->count(),
                'total_criteria' => $criteria->count(),
                'calculated_at'  => optional($results->first())->calculated_at ?? now(),
            ]);

            // Salin kriteria → map criteria_id asli ke snapshot_criteria_id
            $criteriaMap = [];
            foreach ($criteria as $c) {
                $sc = SnapshotCriterion::create([
                    'snapshot_id' => $snapshot->id,
                    'code'        => $c->code,
                    'name'        => $c->name,
                    'unit'        => $c->unit,
                    'type'        => $c->type,
                    'weight'      => $c->weight,
                ]);
                $criteriaMap[$c->id] = $sc->id;
            }

            // Salin laptop + hasil + nilai/nef/nbe
            foreach ($results as $result) {
                $laptop = $result->laptop;
                $sl = SnapshotLaptop::create([
                    'snapshot_id' => $snapshot->id,
                    'name'        => $laptop->name,
                    'rank'        => $result->rank,
                    'tbe'         => $result->tbe,
                ]);

                foreach ($criteria as $c) {
                    if (!isset($criteriaMap[$c->id])) {
                        continue;
                    }
                    $detail = $result->detailFor($c->id);
                    SnapshotLaptopValue::create([
                        'snapshot_laptop_id'   => $sl->id,
                        'snapshot_criteria_id' => $criteriaMap[$c->id],
                        'value'                => $laptop->valueFor($c->id) ?? 0,
                        'nef'                  => $detail->nef ?? 0,
                        'nbe'                  => $detail->nbe ?? 0,
                    ]);
                }
            }

            return $snapshot;
        });
    }

    // ---------------------------------------------------------------
    // PRIVATE HELPERS
    // ---------------------------------------------------------------

    /**
     * Bangun matriks NEF[laptop_id][criteria_id] — meniru persis rumus Excel:
     *
     *   NEF = ROUND( 1 + 4 * (RANK.EQ - 1) / (N - 1) , 0 )   → bilangan bulat 1–5
     *
     * RANK.EQ memakai peringkat kompetisi (nilai seri mendapat peringkat sama):
     *   BENEFIT (order ascending) : rank = 1 + jumlah nilai yang LEBIH KECIL
     *   COST    (order descending): rank = 1 + jumlah nilai yang LEBIH BESAR
     *
     * Sehingga nilai terbaik mendapat peringkat tertinggi → NEF mendekati 5,
     * dan penyebut tetap (N - 1) — bukan peringkat maksimum — agar konsisten
     * dengan spreadsheet meskipun terdapat banyak nilai seri.
     */
    private function buildNefMatrix(Collection $laptops, Collection $criteria): array
    {
        $n      = $laptops->count();
        $matrix = [];

        foreach ($criteria as $criterion) {
            // [laptop_id => value] (default 0 bila belum diisi)
            $values = [];
            foreach ($laptops as $laptop) {
                $values[$laptop->id] = (float) ($laptop->valueFor($criterion->id) ?? 0);
            }

            foreach ($laptops as $laptop) {
                $x = $values[$laptop->id];

                // RANK.EQ — nilai seri otomatis berbagi peringkat yang sama.
                if ($criterion->type === 'benefit') {
                    $rank = 1 + count(array_filter($values, fn ($v) => $v < $x));
                } else {
                    $rank = 1 + count(array_filter($values, fn ($v) => $v > $x));
                }

                // ROUND ke bilangan bulat (sesuai ROUND(...,0) di Excel).
                // N = 1 dijaga agar tidak terjadi pembagian nol.
                $nef = ($n <= 1) ? count($laptops) : round(1 + (count($laptops) - 1) * ($rank - 1) / ($n - 1));

                $matrix[$laptop->id][$criterion->id] = $nef;
            }
        }

        return $matrix;
    }
}
