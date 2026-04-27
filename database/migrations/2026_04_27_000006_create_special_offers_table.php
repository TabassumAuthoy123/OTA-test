<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('special_offers', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['hot_deal', 'ad', 'banner'])->default('hot_deal');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('special_offers'); }
};
