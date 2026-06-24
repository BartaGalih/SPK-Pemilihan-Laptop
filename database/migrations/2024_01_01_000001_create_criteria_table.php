<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCriteriaTable extends Migration
{
    public function up()
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->nullable();  // C1, C2, ... (label, auto-generate)
            $table->string('name');
            $table->string('unit')->nullable();
            $table->enum('type', ['benefit', 'cost'])->default('benefit');
            $table->decimal('weight', 4, 2)->default(0); // 0.00 – 1.00
            $table->softDeletes();                    // arsip tetap konsisten meski kriteria dihapus
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('criteria');
    }
}
