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
        Schema::create('catalog_motors', function (Blueprint $table) {
            $table->id();
            $table->string('motor_name')->unique();
            $table->string('first_catalog')->nullable();
            $table->string('second_catalog')->nullable();
            $table->string('third_catalog')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_motors');
    }
};
