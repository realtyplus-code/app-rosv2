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
            $table->date('action_date');
            $table->unsignedBigInteger('responsible_user_id');
            $table->string('responsible_user_type');
            $table->string('action_description', 1000)->nullable();
            $table->decimal('action_cost', 10, 2);
            $table->unsignedInteger('currency_id');
            $table->decimal('cost', 10, 2);
            $table->string('photo')->nullable();
            $table->string('photo1')->nullable();
            $table->string('photo2')->nullable();
            $table->string('photo3')->nullable();
            $table->string('document')->nullable();
            $table->string('document1')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('action_type_id');
            $table->unsignedBigInteger('status_id');
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
