<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocking_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gds', ['sabre', 'flyhub', 'all'])->default('all');
            $table->string('airline_code', 10)->nullable();
            $table->string('route_from', 5)->nullable();
            $table->string('route_to', 5)->nullable();
            $table->string('cabin_class', 5)->nullable();
            $table->enum('block_type', ['airline', 'route', 'class', 'combo'])->default('airline');
            $table->text('reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['gds', 'airline_code', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocking_rules');
    }
};
