<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Flight Bookings — queried by booked_by, status, pnr_id, gds
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->index('booked_by');
            $table->index('status');
            $table->index('pnr_id');
            $table->index('gds');
            $table->index('created_at');
        });

        // Flight Passengers — queried by flight_booking_id
        Schema::table('flight_passangers', function (Blueprint $table) {
            $table->index('flight_booking_id');
        });

        // Flight Segments — queried by flight_booking_id
        Schema::table('flight_segments', function (Blueprint $table) {
            $table->index('flight_booking_id');
        });

        // Recharge Requests — queried by user_id, status, transaction_id
        Schema::table('recharge_requests', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
            $table->index('transaction_id');
        });

        // Activity Logs — queried by user_id, created_at
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
        });

        // B2B Account Deductions — queried by b2b_user_id
        Schema::table('b2b_account_deductions', function (Blueprint $table) {
            $table->index('b2b_user_id');
        });

        // Users — queried by user_type, status
        Schema::table('users', function (Blueprint $table) {
            $table->index('user_type');
            $table->index('status');
        });

        // SSL Commerz Payment Histories — queried by tran_id
        Schema::table('ssl_commerz_payment_histories', function (Blueprint $table) {
            $table->index('tran_id');
            $table->index('recharge_history_id');
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropIndex(['booked_by']);
            $table->dropIndex(['status']);
            $table->dropIndex(['pnr_id']);
            $table->dropIndex(['gds']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('flight_passangers', function (Blueprint $table) {
            $table->dropIndex(['flight_booking_id']);
        });

        Schema::table('flight_segments', function (Blueprint $table) {
            $table->dropIndex(['flight_booking_id']);
        });

        Schema::table('recharge_requests', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['transaction_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('b2b_account_deductions', function (Blueprint $table) {
            $table->dropIndex(['b2b_user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['user_type']);
            $table->dropIndex(['status']);
        });

        Schema::table('ssl_commerz_payment_histories', function (Blueprint $table) {
            $table->dropIndex(['tran_id']);
            $table->dropIndex(['recharge_history_id']);
        });
    }
};
