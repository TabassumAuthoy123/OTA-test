<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->decimal('gds_base_fare', 12, 2)->nullable()->after('total_fare');
            $table->decimal('markup_amount', 10, 2)->default(0)->after('gds_base_fare');
            $table->decimal('selling_fare', 12, 2)->nullable()->after('markup_amount');
            $table->string('pricing_channel', 10)->nullable()->after('selling_fare');
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropColumn(['gds_base_fare', 'markup_amount', 'selling_fare', 'pricing_channel']);
        });
    }
};
