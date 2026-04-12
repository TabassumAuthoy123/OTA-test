<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('markup_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('channel', ['b2c', 'b2b', 'all'])->default('all');
            $table->enum('gds', ['sabre', 'flyhub', 'all'])->default('all');
            $table->string('airline_code', 10)->nullable();
            $table->string('route_from', 5)->nullable();
            $table->string('route_to', 5)->nullable();
            $table->string('cabin_class', 5)->nullable();
            $table->string('pax_type', 5)->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();  // NULL = global, set = agent-specific
            $table->enum('markup_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('markup_value', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(100);
            $table->timestamps();

            $table->index(['channel', 'gds', 'airline_code', 'is_active']);
            $table->index(['agent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('markup_rules');
    }
};
