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
        Schema::create('tb_price_survey_registers', function (Blueprint $table) {
            $table->id();
            $table->string('operationName');
            $table->string('skuName');
            $table->string('category')->nullable();
            $table->boolean('competing');
            $table->unsignedTinyInteger('batteryLevel');
            $table->decimal('price', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_price_survey_registers');
    }
};
