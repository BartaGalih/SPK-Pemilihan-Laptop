<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSnapshotLaptopsTable extends Migration
{
    public function up()
    {
        // Salinan laptop + ringkasan hasil (rank, tbe) pada saat snapshot dibuat.
        Schema::create('snapshot_laptops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snapshot_id')->constrained('calculation_snapshots')->onDelete('cascade');
            $table->string('name');
            $table->integer('rank')->nullable();
            $table->decimal('tbe', 8, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('snapshot_laptops');
    }
}
