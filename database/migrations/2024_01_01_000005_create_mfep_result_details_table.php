<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMfepResultDetailsTable extends Migration
{
    public function up()
    {
        // Rincian perhitungan per (hasil x kriteria): NEF & NBE.
        Schema::create('mfep_result_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mfep_result_id')->constrained('mfep_results')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('nef', 6, 2)->default(0);   // Nilai Evaluasi Faktor (skala 1–5)
            $table->decimal('nbe', 8, 4)->default(0);   // Nilai Bobot Evaluasi = NBF × NEF
            $table->timestamps();

            $table->unique(['mfep_result_id', 'criteria_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mfep_result_details');
    }
}
