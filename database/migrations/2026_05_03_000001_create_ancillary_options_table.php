<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ancillary_options', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['baggage', 'meal', 'seat', 'other'])->default('baggage');
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('weight_kg', 6, 1)->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 5)->default('BDT');
            $table->string('airline_code', 10)->nullable();
            $table->string('route_from', 5)->nullable();
            $table->string('route_to', 5)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ancillary_options');
    }
};
