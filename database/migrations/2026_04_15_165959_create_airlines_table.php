<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->nullable();
            $table->string('iata', 10)->nullable();
            $table->string('icao', 10)->nullable();
            $table->string('country', 100)->nullable();
            $table->char('active', 1)->default('Y');
            $table->decimal('comission', 8, 2)->default(0);
            $table->timestamps();

            $table->index('iata');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airlines');
    }
};
