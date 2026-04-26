<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('b2b_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('agency_name');
            $table->string('contact_person');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('civil_aviation_doc')->nullable();
            $table->string('trade_license_doc')->nullable();
            $table->string('nid_doc')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Pending,1=Approved,2=Rejected');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('b2b_registration_requests');
    }
};
