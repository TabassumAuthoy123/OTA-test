<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('type', ['google_analytics', 'facebook_pixel', 'google_tag_manager', 'custom'])->default('custom');
            $table->text('tracking_code');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_configs');
    }
};
