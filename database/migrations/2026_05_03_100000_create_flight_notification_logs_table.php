<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_booking_id');
            $table->enum('type', ['passenger_email', 'agent_email', 'sms'])->default('passenger_email');
            $table->string('recipient')->nullable();
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['flight_booking_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_notification_logs');
    }
};
