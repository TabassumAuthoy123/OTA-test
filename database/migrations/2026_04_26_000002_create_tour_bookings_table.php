<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->unique();
            $table->unsignedBigInteger('b2b_user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('tour_type')->nullable();
            $table->date('travel_date')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->tinyInteger('status')->default(0)->comment('0=Pending,1=Confirmed,2=Cancelled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
};
