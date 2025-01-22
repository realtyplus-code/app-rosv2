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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('email');
            $table->string('password');
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('code_number')->nullable();
            $table->string('code_country')->nullable();
            $table->unsignedInteger('language_id');
            $table->string('coverage_area');
            $table->string('website')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('language_id')->references('id')->on('enum_options');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->decimal('service_cost', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
