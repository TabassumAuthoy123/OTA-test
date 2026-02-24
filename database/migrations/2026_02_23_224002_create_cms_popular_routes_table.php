<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_popular_routes', function (Blueprint $table) {
            $table->id();
            $table->string('origin_city', 100);
            $table->string('origin_code', 10);
            $table->string('destination_city', 100);
            $table->string('destination_code', 10);
            $table->decimal('starting_price', 10, 2);
            $table->string('image', 500)->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_popular_routes');
    }
};
