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
        Schema::create('vehicle_return', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('application_id')->nullable();
            $table->foreign('application_id')->references('uuid')->on('vehicle_application')->onDelete('set null');
            $table->dateTime('return_at');
            $table->integer('fuel_used');
            $table->string('photo_receipt');
            $table->integer('receipt_amount');
            $table->enum('status', ['req_claim', 'claimed', 'refused'])->default('req_claim');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_return');
    }
};
