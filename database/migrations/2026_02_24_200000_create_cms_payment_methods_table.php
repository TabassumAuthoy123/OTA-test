<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // e.g. Visa, bKash, Nagad
            $table->string('image')->nullable();  // uploaded image path
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_payment_methods');
    }
};
