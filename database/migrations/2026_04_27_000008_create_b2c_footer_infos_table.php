<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('b2c_footer_infos', function (Blueprint $table) {
            $table->id();
            $table->text('company_info')->nullable();
            $table->json('social_links')->nullable();
            $table->json('footer_sections')->nullable();
            $table->json('company_links')->nullable();
            $table->json('support_links')->nullable();
            $table->json('certifications')->nullable();
            $table->json('payment_methods')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('b2c_footer_infos'); }
};
