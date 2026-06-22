<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaptopsTable extends Migration
{
    public function up()
    {
        Schema::create('laptops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('price');          // C1 – Harga (Rp)
            $table->integer('ram');               // C2 – RAM (GB)
            $table->integer('cpu_score');         // C3 – CPU Score PassMark
            $table->decimal('weight_kg', 4, 2);  // C4 – Bobot (kg)
            $table->integer('storage');           // C5 – Storage (GB)
            $table->decimal('battery', 4, 1);    // C6 – Baterai (jam)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laptops');
    }
}
