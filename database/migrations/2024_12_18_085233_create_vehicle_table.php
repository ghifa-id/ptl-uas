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
            $table->uuid('uuid');
            $table->uuid('type_id')->references('uuid')->on('type_vehicle')->onDelete(null);
            $table->string('plat_number')->unique();
            $table->string('merk');
            $table->boolean('status');
            $table->uuid('created_by')->references('uuid')->on('users')->onDelete(null);
            $table->uuid('updated_by')->references('uuid')->on('users')->onDelete(null);
            $table->timestamps('deleted_at');
            $table->timestamps();
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
