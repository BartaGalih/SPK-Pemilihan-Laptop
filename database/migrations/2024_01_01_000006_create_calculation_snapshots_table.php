<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculationSnapshotsTable extends Migration
{
    public function up()
    {
        // Arsip/snapshot hasil perhitungan. Seluruh data (kriteria, laptop,
        // nilai, hasil) disalin ke tabel turunan agar independen dari master.
        Schema::create('calculation_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('note')->nullable();
            $table->unsignedInteger('total_laptops')->default(0);
            $table->unsignedInteger('total_criteria')->default(0);
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculation_snapshots');
    }
}
