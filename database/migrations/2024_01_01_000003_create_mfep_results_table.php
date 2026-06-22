<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMfepResultsTable extends Migration
{
    public function up()
    {
        Schema::create('mfep_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained('laptops')->onDelete('cascade');
            $table->integer('rank');
            // NEF (Nilai Evaluasi Faktor) per kriteria
            $table->decimal('nef_c1', 3, 2)->nullable(); // Harga
            $table->decimal('nef_c2', 3, 2)->nullable(); // RAM
            $table->decimal('nef_c3', 3, 2)->nullable(); // CPU Score
            $table->decimal('nef_c4', 3, 2)->nullable(); // Bobot
            $table->decimal('nef_c5', 3, 2)->nullable(); // Storage
            $table->decimal('nef_c6', 3, 2)->nullable(); // Baterai
            // NBE (Nilai Bobot Evaluasi) = NBF × NEF
            $table->decimal('nbe_c1', 5, 4)->nullable();
            $table->decimal('nbe_c2', 5, 4)->nullable();
            $table->decimal('nbe_c3', 5, 4)->nullable();
            $table->decimal('nbe_c4', 5, 4)->nullable();
            $table->decimal('nbe_c5', 5, 4)->nullable();
            $table->decimal('nbe_c6', 5, 4)->nullable();
            // TBE (Total Bobot Evaluasi)
            $table->decimal('tbe', 6, 4);
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mfep_results');
    }
}
