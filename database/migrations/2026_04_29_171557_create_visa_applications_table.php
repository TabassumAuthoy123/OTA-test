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
        Schema::create('visa_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('applicant_name');
            $table->string('passport_no')->nullable();
            $table->string('nationality')->nullable();
            $table->string('destination_country');
            $table->enum('visa_type', ['tourist','business','student','work','medical','other'])->default('tourist');
            $table->date('travel_date')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending','processing','approved','rejected','cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_applications');
    }
};
