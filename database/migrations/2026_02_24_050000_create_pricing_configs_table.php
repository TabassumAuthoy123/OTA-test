<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pricing_configs', function (Blueprint $table) {
            $table->id();
            $table->string('channel', 10);                // 'b2c' or 'b2b'
            $table->string('markup_type', 20);            // 'percentage' or 'fixed'
            $table->decimal('markup_value', 10, 2);       // e.g. 5.00 (%) or 500.00 (BDT)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('channel'); // one config per channel
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_configs');
    }
};
