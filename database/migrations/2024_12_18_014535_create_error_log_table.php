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
        Schema::create('error_log', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->longText('message');
            $table->longText('trace')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_log');
    }
};
