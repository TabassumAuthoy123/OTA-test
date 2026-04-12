<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');                              // Rule name e.g. "BG Domestic 7%"
            $table->enum('gds', ['sabre', 'flyhub', 'all'])->default('all');
            $table->string('airline_code', 10)->nullable();      // BG, BS, EK etc. NULL = all
            $table->string('route_from', 5)->nullable();         // DAC, NULL = any
            $table->string('route_to', 5)->nullable();           // DXB, NULL = any
            $table->string('cabin_class', 5)->nullable();        // Y, C, F, NULL = all
            $table->string('pax_type', 5)->nullable();           // ADT, CHD, INF, NULL = all
            $table->unsignedBigInteger('agent_id')->nullable();  // NULL = global, set = agent-specific
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('commission_value', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(100);           // Lower = higher priority
            $table->timestamps();

            $table->index(['gds', 'airline_code', 'is_active']);
            $table->index(['agent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_rules');
    }
};
