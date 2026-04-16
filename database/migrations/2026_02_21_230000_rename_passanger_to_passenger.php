<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Fix "passanger" typo → "passenger" in table names and columns.
     */
    public function up(): void
    {
        // Rename tables (only if old name still exists)
        if (Schema::hasTable('flight_passangers')) {
            Schema::rename('flight_passangers', 'flight_passengers');
        }
        if (Schema::hasTable('saved_passangers')) {
            Schema::rename('saved_passangers', 'saved_passengers');
        }

        // Rename columns
        if (Schema::hasColumn('flight_passengers', 'passanger_type')) {
            Schema::table('flight_passengers', function (Blueprint $table) {
                $table->renameColumn('passanger_type', 'passenger_type');
            });
        }

        if (Schema::hasColumn('flight_bookings', 'passanger_id')) {
            Schema::table('flight_bookings', function (Blueprint $table) {
                $table->renameColumn('passanger_id', 'passenger_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->renameColumn('passenger_id', 'passanger_id');
        });

        Schema::table('flight_passengers', function (Blueprint $table) {
            $table->renameColumn('passenger_type', 'passanger_type');
        });

        Schema::rename('flight_passengers', 'flight_passangers');
        Schema::rename('saved_passengers', 'saved_passangers');
    }
};
