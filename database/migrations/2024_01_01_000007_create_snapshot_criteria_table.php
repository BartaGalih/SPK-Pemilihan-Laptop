<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSnapshotCriteriaTable extends Migration
{
    public function up()
    {
        // Salinan kriteria + bobot pada saat snapshot dibuat.
        Schema::create('snapshot_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snapshot_id')->constrained('calculation_snapshots')->onDelete('cascade');
            $table->string('code', 10)->nullable();
            $table->string('name');
            $table->string('unit')->nullable();
            $table->enum('type', ['benefit', 'cost']);
            $table->decimal('weight', 4, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('snapshot_criteria');
    }
}
