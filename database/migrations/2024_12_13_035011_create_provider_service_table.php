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
        Schema::create('provider_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('service_id');
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('enum_options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_service');
    }
};
