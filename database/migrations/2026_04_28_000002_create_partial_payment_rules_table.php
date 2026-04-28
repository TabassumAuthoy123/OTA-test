<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partial_payment_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->decimal('min_payment_percent', 5, 2)->default(20);
            $table->decimal('max_defer_percent', 5, 2)->default(80);
            $table->integer('payment_due_days')->default(7);
            $table->enum('applicable_for', ['flight', 'tour', 'all'])->default('all');
            $table->string('airline_code', 10)->nullable();
            $table->string('route_from', 10)->nullable();
            $table->string('route_to', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['applicable_for', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partial_payment_rules');
    }
};
