<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('country_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('related_id');
            $table->string('type');
            $table->timestamps();
            // Llaves foráneas
            $table->foreign('country_id')->references('id')->on('enum_options')->onDelete('cascade');
            $table->foreign('related_id')->references('id')->on('enum_options')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_relations');
    }
};