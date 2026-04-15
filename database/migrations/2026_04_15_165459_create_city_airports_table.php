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
        Schema::create('city_airports', function (Blueprint $table) {
            $table->id();
            $table->string('city_name', 100);
            $table->string('city_code', 10)->nullable();
            $table->string('airport_name', 150);
            $table->string('airport_code', 10);
            $table->string('country_name', 100)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->timestamps();

            $table->index('airport_code');
            $table->index('city_code');
            $table->index('city_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_airports');
    }
};
