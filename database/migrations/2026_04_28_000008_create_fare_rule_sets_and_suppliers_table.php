<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('dynamic_fare_rules');

        Schema::create('fare_rule_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->timestamps();
        });

        Schema::create('fare_rule_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fare_rule_set_id')->constrained('fare_rule_sets')->onDelete('cascade');
            $table->string('api_type', 20)->default('all');
            $table->decimal('pax_markup_value', 10, 2)->default(0);
            $table->decimal('commission_value', 10, 2)->default(0);
            $table->enum('commission_type', ['flat', 'percentage'])->default('flat');
            $table->decimal('markup_value', 10, 2)->default(0);
            $table->enum('markup_type', ['flat', 'percentage'])->default('flat');
            $table->decimal('segment_commission_value', 10, 2)->default(0);
            $table->enum('segment_commission_type', ['flat', 'percentage'])->default('flat');
            $table->decimal('segment_markup_value', 10, 2)->default(0);
            $table->enum('segment_markup_type', ['flat', 'percentage'])->default('flat');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['fare_rule_set_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fare_rule_suppliers');
        Schema::dropIfExists('fare_rule_sets');
        Schema::dropIfExists('dynamic_fare_rules');
    }
};
