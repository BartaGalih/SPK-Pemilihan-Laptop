<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaptopSeeder extends Seeder
{
    public function run()
    {
        DB::table('laptops')->insert([
            ['name' => 'HP OmniBook 7 Aero 13-BG1667AU',   'price' => 15289000, 'ram' => 16, 'cpu_score' => 19643, 'weight_kg' => 0.90, 'storage' => 512,  'battery' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ASUS Vivobook S14 S3407CA',         'price' => 15499000, 'ram' => 16, 'cpu_score' => 28514, 'weight_kg' => 1.40, 'storage' => 1024, 'battery' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ASUS Vivobook S14 M3407HA',         'price' => 16299000, 'ram' => 16, 'cpu_score' => 28221, 'weight_kg' => 1.40, 'storage' => 1024, 'battery' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ASUS TUF Gaming A16 FA607NUG',      'price' => 16499000, 'ram' => 16, 'cpu_score' => 18764, 'weight_kg' => 2.20, 'storage' => 1024, 'battery' => 7,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HP OmniBook 7 Aero 13-BG1778AU',   'price' => 16789000, 'ram' => 16, 'cpu_score' => 24971, 'weight_kg' => 0.90, 'storage' => 512,  'battery' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lenovo Yoga Slim 7-39ID',           'price' => 17099000, 'ram' => 16, 'cpu_score' => 19643, 'weight_kg' => 1.19, 'storage' => 512,  'battery' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lenovo Yoga 7 Flip-3DID',           'price' => 17499000, 'ram' => 16, 'cpu_score' => 19643, 'weight_kg' => 1.40, 'storage' => 512,  'battery' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ASUS TUF Gaming A15 FA506NCG (8GB)','price' => 17999000, 'ram' => 8,  'cpu_score' => 18764, 'weight_kg' => 2.30, 'storage' => 512,  'battery' => 6,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lenovo IdeaPad Slim 5-1QID',        'price' => 18599000, 'ram' => 16, 'cpu_score' => 28514, 'weight_kg' => 1.39, 'storage' => 512,  'battery' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ASUS TUF Gaming A15 FA506NCG (16GB)','price'=> 18999000, 'ram' => 16, 'cpu_score' => 18764, 'weight_kg' => 2.30, 'storage' => 512,  'battery' => 5,  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
