<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaptopValuesTable extends Migration
{
    public function up()
    {
        // Nilai spesifikasi laptop per kriteria (EAV).
        Schema::create('laptop_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained('laptops')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('value', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['laptop_id', 'criteria_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('laptop_values');
    }
}
