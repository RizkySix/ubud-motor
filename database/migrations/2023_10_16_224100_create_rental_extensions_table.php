<?php

use App\Models\BookingDetail;
use App\Models\Customer;
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
        Schema::create('rental_extensions', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignIdFor(BookingDetail::class);
            $table->foreignIdFor(Customer::class);
            $table->tinyInteger('total_unit' , false ,true);
            $table->string('full_name' , 50);
            $table->string('email');
            $table->string('whatsapp_number' , 50);
            $table->string('package' , 50);
            $table->decimal('amount' , 10 , 2 , true);
            $table->dateTime('expired_payment');
            $table->dateTime('extension_from');
            $table->dateTime('extension_to');
            $table->boolean('is_confirmed')->default(false);
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_extensions');
    }
};
