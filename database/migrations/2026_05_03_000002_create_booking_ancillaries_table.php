<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_ancillaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_booking_id');
            $table->unsignedBigInteger('ancillary_option_id')->nullable();
            $table->enum('type', ['baggage', 'meal', 'seat', 'other'])->default('baggage');
            $table->string('name');
            $table->integer('pax_index')->default(0);
            $table->integer('qty')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('currency', 5)->default('BDT');
            $table->string('status', 20)->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('flight_booking_id')->references('id')->on('flight_bookings')->onDelete('cascade');
            $table->index('flight_booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_ancillaries');
    }
};
