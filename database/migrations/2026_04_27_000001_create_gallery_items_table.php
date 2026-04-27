<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('section_type', ['short', 'certificates'])->default('short');
            $table->enum('media_type', ['video', 'image', 'other'])->default('other');
            $table->string('file_path')->nullable();
            $table->decimal('file_size_mb', 10, 2)->default(0);
            $table->string('duration')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('gallery_items'); }
};
