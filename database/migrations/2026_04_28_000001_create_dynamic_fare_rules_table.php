<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dynamic_fare_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('origin', 10)->nullable();
            $table->string('destination', 10)->nullable();
            $table->string('airline_code', 10)->nullable();
            $table->enum('trip_type', ['one_way', 'round_trip', 'multi_city', 'all'])->default('all');
            $table->string('cabin_class', 10)->nullable();
            $table->enum('markup_type', ['fixed', 'percentage'])->default('percentage');
            $table->decimal('markup_value', 10, 2)->default(0);
            $table->decimal('min_fare', 10, 2)->nullable();
            $table->decimal('max_fare', 10, 2)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['origin', 'destination', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_fare_rules');
    }
};
