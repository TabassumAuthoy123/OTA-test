<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('b2c_coin_configs', function (Blueprint $table) {
            $table->id();
            $table->decimal('taka_per_coin', 10, 2)->default(500);
            $table->decimal('coin_value', 10, 2)->default(1);
            $table->decimal('min_redeem_coins', 10, 2)->default(50);
            $table->decimal('max_redeem_percent', 5, 2)->default(50);
            $table->boolean('is_active')->default(true);
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('b2c_coin_configs'); }
};
