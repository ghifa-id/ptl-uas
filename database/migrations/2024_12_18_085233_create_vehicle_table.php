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
        Schema::create('vehicle', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('type_id')->nullable();
            $table->foreign('type_id')->references('uuid')->on('type_vehicle')->onDelete('set null');
            $table->string('plat_number')->unique();
            $table->string('merk');
            $table->boolean('status')->default(true);
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('uuid')->on('users')->onDelete('set null');
            $table->uuid('updated_by')->nullable();
            $table->foreign('updated_by')->references('uuid')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle');
    }
};
