<?php

use App\Models\Booking;
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
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Booking::class);
            $table->string('motor_name' , 30);
            $table->dateTime('rental_date')->default(now());
            $table->dateTime('return_date');
            $table->json('renewal_history')->nullable();
            $table->decimal('charge' , 10 , 2 ,true);
            $table->decimal('total_charge' , 10 , 2 ,true)->default(0.00);
            $table->tinyInteger('passed_days' , false, true)->default(0);
            $table->dateTime('today_charge')->nullable();
            $table->boolean('is_done')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details');
    }
};
