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
        Schema::create('incident_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id');
            $table->dateTime('action_date');
            $table->unsignedBigInteger('responsible_user_id');
            $table->string('responsible_user_type');
            $table->string('action_description', 1000)->nullable();
            $table->decimal('action_cost', 10, 2);
            $table->unsignedInteger('currency_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('action_type_id');
            $table->unsignedInteger('status_id');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('enum_options');
            $table->foreign('incident_id')->references('id')->on('incidents');
            $table->foreign('action_type_id')->references('id')->on('enum_options');
            $table->foreign('status_id')->references('id')->on('enum_options');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_actions');
    }
};
