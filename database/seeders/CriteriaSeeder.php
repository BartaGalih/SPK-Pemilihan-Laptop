<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('criteria')->insert([
            ['code' => 'C1', 'name' => 'Harga',     'unit' => 'Rp',  'type' => 'cost',    'weight' => 0.30, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'C2', 'name' => 'RAM',        'unit' => 'GB',  'type' => 'benefit', 'weight' => 0.20, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'C3', 'name' => 'CPU Score',  'unit' => 'pts', 'type' => 'benefit', 'weight' => 0.25, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'C4', 'name' => 'Bobot',      'unit' => 'kg',  'type' => 'cost',    'weight' => 0.10, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'C5', 'name' => 'Storage',    'unit' => 'GB',  'type' => 'benefit', 'weight' => 0.05, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'C6', 'name' => 'Baterai',    'unit' => 'jam', 'type' => 'benefit', 'weight' => 0.10, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
