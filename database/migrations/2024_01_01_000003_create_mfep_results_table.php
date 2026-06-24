<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMfepResultsTable extends Migration
{
    public function up()
    {
        // Hasil utama per laptop (ringkasan). Rincian NEF/NBE per kriteria
        // disimpan dinamis pada tabel mfep_result_details.
        Schema::create('mfep_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained('laptops')->onDelete('cascade');
            $table->integer('rank');
            $table->decimal('tbe', 8, 4);                 // Total Bobot Evaluasi
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mfep_results');
    }
}
