<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaptopsTable extends Migration
{
    public function up()
    {
        // Spesifikasi laptop tidak lagi disimpan sebagai kolom tetap,
        // melainkan dinamis pada tabel laptop_values (EAV) terhadap setiap kriteria.
        Schema::create('laptops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laptops');
    }
}
