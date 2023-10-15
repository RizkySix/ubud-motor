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
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->tinyInteger('total_unit' ,false , true);
            $table->string('full_name' , 50);
            $table->string('email');
            $table->string('whatsapp_number' , 50);
            $table->string('motor_name' , 30);
            $table->string('package' , 50);
            $table->decimal('amount' , 10 , 2 , true);
            $table->string('delivery_address');
            $table->string('pickup_address');
            $table->string('additional_message')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};