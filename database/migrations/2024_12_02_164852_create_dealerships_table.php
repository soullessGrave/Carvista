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
        Schema::create('dealerships', function (Blueprint $table) {
            $table->id();
            $table->string('dealershipName');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('phoneNumber')->unique();
            $table->string('password');
            $table->string('city');
            $table->boolean('accountType')->default(1);
            $table->string('locationUrl')->nullable();
            $table->string('otp')->nullable();
            $table->string('imgUrl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealerships');
    }
};
