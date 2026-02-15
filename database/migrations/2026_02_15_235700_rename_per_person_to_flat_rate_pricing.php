<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Changes pricing model from per-person to flat rate:
     * - per_person_rate → base_rate_3hrs (flat rate for first 3 hours)
     * - per_person_extension_rate → extension_rate_2hrs (flat rate for each +2 hour extension)
     */
    public function up(): void
    {
        // Update default database facilities table
        if (Schema::hasTable('facilities')) {
            if (Schema::hasColumn('facilities', 'per_person_rate')) {
                Schema::table('facilities', function (Blueprint $table) {
                    $table->renameColumn('per_person_rate', 'base_rate_3hrs');
                });
            }
            if (Schema::hasColumn('facilities', 'per_person_extension_rate')) {
                Schema::table('facilities', function (Blueprint $table) {
                    $table->renameColumn('per_person_extension_rate', 'extension_rate_2hrs');
                });
            }
        }

        // Update facilities_db facilities table
        if (Schema::connection('facilities_db')->hasTable('facilities')) {
            if (Schema::connection('facilities_db')->hasColumn('facilities', 'per_person_rate')) {
                Schema::connection('facilities_db')->table('facilities', function (Blueprint $table) {
                    $table->renameColumn('per_person_rate', 'base_rate_3hrs');
                });
            }
            if (Schema::connection('facilities_db')->hasColumn('facilities', 'per_person_extension_rate')) {
                Schema::connection('facilities_db')->table('facilities', function (Blueprint $table) {
                    $table->renameColumn('per_person_extension_rate', 'extension_rate_2hrs');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert default database
        if (Schema::hasTable('facilities')) {
            if (Schema::hasColumn('facilities', 'base_rate_3hrs')) {
                Schema::table('facilities', function (Blueprint $table) {
                    $table->renameColumn('base_rate_3hrs', 'per_person_rate');
                });
            }
            if (Schema::hasColumn('facilities', 'extension_rate_2hrs')) {
                Schema::table('facilities', function (Blueprint $table) {
                    $table->renameColumn('extension_rate_2hrs', 'per_person_extension_rate');
                });
            }
        }

        // Revert facilities_db
        if (Schema::connection('facilities_db')->hasTable('facilities')) {
            if (Schema::connection('facilities_db')->hasColumn('facilities', 'base_rate_3hrs')) {
                Schema::connection('facilities_db')->table('facilities', function (Blueprint $table) {
                    $table->renameColumn('base_rate_3hrs', 'per_person_rate');
                });
            }
            if (Schema::connection('facilities_db')->hasColumn('facilities', 'extension_rate_2hrs')) {
                Schema::connection('facilities_db')->table('facilities', function (Blueprint $table) {
                    $table->renameColumn('extension_rate_2hrs', 'per_person_extension_rate');
                });
            }
        }
    }
};
