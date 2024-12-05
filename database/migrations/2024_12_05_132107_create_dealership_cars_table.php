<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dealership_cars', function (Blueprint $table) {
            $table->id();
            $table->string('brandName');
            $table->string('modelName');
            $table->year('manufactureYear');
            $table->integer('distance');
            $table->string('condition');
            $table->string('price');
            $table->string('ownerId');
            $table->string('description')->nullable();
            $table->string('imgUrl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealership_cars');
    }
};
