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
            $table->string('action_description', 1000)->nullable();
            $table->decimal('action_cost', 10, 2);
            $table->string('photo')->nullable();
            $table->string('photo1')->nullable();
            $table->string('photo2')->nullable();
            $table->string('photo3')->nullable();
            $table->string('document')->nullable();
            $table->string('document1')->nullable();
            $table->timestamps();

            $table->foreign('incident_id')->references('id')->on('incidents');
            $table->foreign('responsible_user_id')->references('id')->on('users');
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
