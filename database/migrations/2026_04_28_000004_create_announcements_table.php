<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'success', 'danger'])->default('info');
            $table->enum('target', ['all', 'b2c', 'b2b'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->dateTime('show_from')->nullable();
            $table->dateTime('show_until')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'show_from', 'show_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
