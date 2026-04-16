<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('flight_bookings', 'flight_type')) {
                $table->tinyInteger('flight_type')->nullable()->after('id');
            }
            if (!Schema::hasColumn('flight_bookings', 'source')) {
                $table->tinyInteger('source')->default(1)->after('flight_type');
            }
            if (!Schema::hasColumn('flight_bookings', 'get_booking_response')) {
                $table->longText('get_booking_response')->nullable()->after('booking_response');
            }
            if (!Schema::hasColumn('flight_bookings', 'payment_status')) {
                $table->tinyInteger('payment_status')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropColumn(['flight_type', 'source', 'get_booking_response', 'payment_status']);
        });
    }
};
