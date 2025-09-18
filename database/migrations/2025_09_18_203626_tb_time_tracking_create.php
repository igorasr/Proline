<?php

use App\Enums\UploadStatus;
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
        Schema::create('tb_time_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('batteryLevel')->default(0);
            $table->decimal('latitude', 10, 7);              // graus com 7 casas
            $table->decimal('longitude', 10, 7);
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_time_tracking_events');
    }
};
