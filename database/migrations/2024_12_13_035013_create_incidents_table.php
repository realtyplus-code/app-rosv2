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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->string('description', 1000)->nullable();
            $table->date('report_date');
            $table->unsignedInteger('status_id');
            $table->unsignedBigInteger('reported_by');
            $table->unsignedInteger('incident_type_id');
            $table->unsignedInteger('priority_id');
            $table->unsignedInteger('currency_id');
            $table->decimal('cost', 10, 2)->nullable();
            $table->unsignedInteger('payer_id');
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('enum_options');
            $table->foreign('incident_type_id')->references('id')->on('enum_options');
            $table->foreign('priority_id')->references('id')->on('enum_options');
            $table->foreign('payer_id')->references('id')->on('enum_options');
            $table->foreign('currency_id')->references('id')->on('enum_options');

            $table->foreign('property_id')->references('id')->on('properties');
            $table->foreign('reported_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
