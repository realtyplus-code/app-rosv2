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
            $table->string('position');
            $table->string('phone');
            $table->string('code_number')->nullable();
            $table->string('code_country')->nullable();
            $table->string('country');
            $table->unsignedBigInteger('property_id');
            $table->unsignedInteger('insurance_type_id');
            $table->boolean('renewal_indicator');
            $table->integer('renewal_months');
            $table->string('policy_number');
            $table->decimal('policy_amount', 10, 2);
            $table->string('document')->nullable();
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties');
            $table->foreign('insurance_type_id')->references('id')->on('enum_options');
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