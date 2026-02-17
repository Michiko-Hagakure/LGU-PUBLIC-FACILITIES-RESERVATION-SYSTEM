<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('facilities_db')->create('water_connection_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('external_id')->nullable();
            $table->string('external_application_number', 50)->nullable();
            $table->string('consumer_name', 255);
            $table->enum('service_type', [
                'water_connection',
                'electricity_connection',
                'water_reconnection',
                'meter_replacement',
                'transfer_service',
                'disconnection',
            ]);
            $table->text('installation_address');
            $table->enum('property_type', ['residential', 'commercial', 'industrial', 'government']);
            $table->string('contact_person', 255);
            $table->string('contact_phone', 50);
            $table->string('contact_email', 255);
            $table->string('partner_reference', 100)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 50)->default('submitted');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('external_application_number');
            $table->index('status');
            $table->index('service_type');
        });
    }

    public function down(): void
    {
        Schema::connection('facilities_db')->dropIfExists('water_connection_requests');
    }
};
