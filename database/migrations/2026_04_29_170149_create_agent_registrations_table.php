<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agent_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('password');
            $table->string('user_photo')->nullable();
            $table->string('agency_logo')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('nid_document')->nullable();
            $table->string('civil_aviation')->nullable();
            $table->tinyInteger('status')->default(0); // 0=pending, 1=approved, 2=rejected
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_registrations');
    }
};
