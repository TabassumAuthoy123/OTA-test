<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('film_watch_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('film_watch_links'); }
};
