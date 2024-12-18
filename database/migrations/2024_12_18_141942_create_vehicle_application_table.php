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
        Schema::create('vehicle_application', function (Blueprint $table) {
            $table->uuid('uuid')->primary;
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('uuid')->on('users')->onDelete('set null');
            $table->uuid('vehicle_id')->nullable();
            $table->foreign('vehicle_id')->references('uuid')->on('vehicle')->onDelete('set null');
            $table->longText('application_detail');
            $table->datetime('start_booking');
            $table->status('status_application');
            $table->uuid('decided_by')->nullable();
            $table->foreign('decided_by')->references('uuid')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_application');
    }
};
