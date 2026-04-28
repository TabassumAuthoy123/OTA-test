<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('partial_payment_rules');
        Schema::create('partial_payment_rules', function (Blueprint $table) {
            $table->id();
            $table->string('flight_api', 20)->default('all');
            $table->string('airline_code', 20)->nullable();
            $table->boolean('from_dac')->default(false);
            $table->boolean('to_dac')->default(false);
            $table->boolean('domestic')->default(false);
            $table->boolean('soto')->default(false);
            $table->boolean('one_way')->default(false);
            $table->boolean('round_trip')->default(false);
            $table->unsignedSmallInteger('travel_date_from_now')->default(0);
            $table->unsignedSmallInteger('payment_before_days')->default(0);
            $table->decimal('payment_percent', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['flight_api', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partial_payment_rules');
    }
};
