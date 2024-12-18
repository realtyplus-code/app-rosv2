<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnumOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('enum_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->string('code')->index()->unique()->nullable();
            $table->string('name')->index();
            $table->string('description', 255)->nullable();
            $table->string('valor1', 512)->nullable();
            $table->string('valor2', 512)->nullable();
            $table->string('valor3', 512)->nullable();
            $table->boolean('is_father')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('orden')->default(-1);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('enum_options')->onUpdate('cascade')->onDelete('cascade');
        });


    }

    public function down()
    {
        Schema::dropIfExists('enum_options');
    }
}
