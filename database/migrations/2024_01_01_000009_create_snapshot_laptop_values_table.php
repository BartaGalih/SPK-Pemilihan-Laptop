<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSnapshotLaptopValuesTable extends Migration
{
    public function up()
    {
        // Salinan nilai spesifikasi + hasil hitung (nef, nbe) per
        // (laptop snapshot x kriteria snapshot).
        Schema::create('snapshot_laptop_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snapshot_laptop_id')->constrained('snapshot_laptops')->onDelete('cascade');
            $table->foreignId('snapshot_criteria_id')->constrained('snapshot_criteria')->onDelete('cascade');
            $table->decimal('value', 15, 2)->default(0);
            $table->decimal('nef', 6, 2)->default(0);
            $table->decimal('nbe', 8, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('snapshot_laptop_values');
    }
}
