<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Laptop;
use Illuminate\Database\Seeder;

class LaptopSeeder extends Seeder
{
    public function run()
    {
        // Kriteria dipetakan berdasarkan kode → spesifikasi (EAV).
        $criteria = Criteria::pluck('id', 'code'); // ['C1' => id, ...]

        // [nama, C1 harga, C2 ram, C3 cpu, C4 bobot, C5 storage, C6 baterai]
        $laptops = [
            ['HP OmniBook 7 Aero 13-BG1667AU',    15289000, 16, 19643, 0.90, 512,  15],
            ['ASUS Vivobook S14 S3407CA',         15499000, 16, 28514, 1.40, 1024, 11],
            ['ASUS Vivobook S14 M3407HA',         16299000, 16, 28221, 1.40, 1024, 11],
            ['ASUS TUF Gaming A16 FA607NUG',      16499000, 16, 18764, 2.20, 1024, 7],
            ['HP OmniBook 7 Aero 13-BG1778AU',    16789000, 16, 24971, 0.90, 512,  15],
            ['Lenovo Yoga Slim 7-39ID',           17099000, 16, 19643, 1.19, 512,  15],
            ['Lenovo Yoga 7 Flip-3DID',           17499000, 16, 19643, 1.40, 512,  11],
            ['ASUS TUF Gaming A15 FA506NCG (8GB)', 17999000, 8,  18764, 2.30, 512,  6],
            ['Lenovo IdeaPad Slim 5-1QID',        18599000, 16, 28514, 1.39, 512,  11],
            ['ASUS TUF Gaming A15 FA506NCG (16GB)',18999000, 16, 18764, 2.30, 512,  5],
        ];

        $codes = ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'];

        foreach ($laptops as $row) {
            $name = array_shift($row);
            $laptop = Laptop::create(['name' => $name]);

            foreach ($codes as $i => $code) {
                if (!isset($criteria[$code])) {
                    continue;
                }
                $laptop->values()->create([
                    'criteria_id' => $criteria[$code],
                    'value'       => $row[$i],
                ]);
            }
        }
    }
}
