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
        Schema::table('vehicle_application', function (Blueprint $table) {
            $table->index('start_booking');
            $table->index('end_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_application', function (Blueprint $table) {
            $table->dropIndex(['start_booking']);
            $table->dropIndex(['end_booking']); 
        });
    }
};
