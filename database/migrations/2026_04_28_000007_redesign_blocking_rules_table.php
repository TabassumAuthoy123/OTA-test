<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('blocking_rules');
        Schema::create('blocking_rules', function (Blueprint $table) {
            $table->id();
            $table->string('departure', 10)->default('');
            $table->string('arrival', 10)->default('');
            $table->string('airline_code', 20)->nullable();
            $table->boolean('one_way')->default(false);
            $table->boolean('round_trip')->default(false);
            $table->boolean('booking_block')->default(false);
            $table->boolean('full_block')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['departure', 'arrival', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocking_rules');
    }
};
