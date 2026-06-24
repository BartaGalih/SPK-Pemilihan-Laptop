<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run()
    {
        $criteria = [
            ['code' => 'C1', 'name' => 'Harga',     'unit' => 'Rp',  'type' => 'cost',    'weight' => 0.30],
            ['code' => 'C2', 'name' => 'RAM',       'unit' => 'GB',  'type' => 'benefit', 'weight' => 0.20],
            ['code' => 'C3', 'name' => 'CPU Score', 'unit' => 'pts', 'type' => 'benefit', 'weight' => 0.25],
            ['code' => 'C4', 'name' => 'Bobot',     'unit' => 'kg',  'type' => 'cost',    'weight' => 0.10],
            ['code' => 'C5', 'name' => 'Storage',   'unit' => 'GB',  'type' => 'benefit', 'weight' => 0.05],
            ['code' => 'C6', 'name' => 'Baterai',   'unit' => 'jam', 'type' => 'benefit', 'weight' => 0.10],
        ];

        foreach ($criteria as $c) {
            Criteria::create($c);
        }
    }
}
