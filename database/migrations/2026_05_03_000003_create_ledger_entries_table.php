<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id');
            $table->enum('party_type', ['b2b', 'b2c'])->default('b2b');
            $table->unsignedBigInteger('flight_booking_id')->nullable();
            $table->unsignedBigInteger('recharge_request_id')->nullable();
            $table->enum('entry_type', ['debit', 'credit']);
            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->default(0);
            $table->string('description');
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['party_id', 'party_type', 'created_at']);
            $table->index('flight_booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
