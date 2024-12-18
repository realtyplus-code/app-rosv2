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
            $table->unsignedBigInteger('property_id');
            $table->text('description');
            $table->date('report_date');
            $table->unsignedInteger('status_id');
            $table->unsignedBigInteger('reported_by');
            $table->unsignedInteger('incident_type_id');
            $table->unsignedInteger('priority_id');
            $table->unsignedBigInteger('assigned_responsible');
            $table->decimal('cost', 10, 2);
            $table->unsignedInteger('payer_id');
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('enum_options');
            $table->foreign('incident_type_id')->references('id')->on('enum_options');
            $table->foreign('priority_id')->references('id')->on('enum_options');
            $table->foreign('payer_id')->references('id')->on('enum_options');

            $table->foreign('property_id')->references('id')->on('properties');
            $table->foreign('reported_by')->references('id')->on('users');
            $table->foreign('assigned_responsible')->references('id')->on('providers');
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
