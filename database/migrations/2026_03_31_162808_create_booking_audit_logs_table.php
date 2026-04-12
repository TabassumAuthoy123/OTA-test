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
        Schema::create('booking_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_booking_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action')->comment('e.g., ISSUE, VOID, REFUND, REISSUE');
            $table->string('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            // Assuming relationships but relying on indexing for fast lookups
            // $table->foreign('flight_booking_id')->references('id')->on('flight_bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_audit_logs');
    }
};
