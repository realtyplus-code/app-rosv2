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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('insurance_company');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('contact_person');
            $table->string('contact_email');
            $table->unsignedBigInteger('property_id');
            $table->unsignedInteger('coverage_type_id');
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties');
            $table->foreign('coverage_type_id')->references('id')->on('enum_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
