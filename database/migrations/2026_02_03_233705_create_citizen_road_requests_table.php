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
        Schema::connection('facilities_db')->create('citizen_road_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('external_request_id')->nullable();
            $table->string('event_type', 100);
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->string('location', 500);
            $table->string('landmark', 255)->nullable();
            $table->text('description');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('status', 50)->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->dropIfExists('citizen_road_requests');
    }
};
