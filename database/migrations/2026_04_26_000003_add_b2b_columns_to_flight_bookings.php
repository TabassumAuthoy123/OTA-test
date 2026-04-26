<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->boolean('partial_payment')->default(false)->after('payment_status');
            $table->decimal('paid_amount', 12, 2)->nullable()->after('partial_payment');
            $table->date('partial_payment_last_date')->nullable()->after('paid_amount');
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropColumn(['partial_payment', 'paid_amount', 'partial_payment_last_date']);
        });
    }
};
