<?php

use App\Models\CatalogMotor;
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
        Schema::create('catalog_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CatalogMotor::class);
            $table->string('package' , 30);
            $table->string('duration' , 30);
            $table->decimal('price' , 10 , 2 , true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_prices');
    }
};
