<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name_ru');
            $table->string('name_en');
            $table->boolean('status')->default(1);
            $table->unsignedTinyInteger('order')->default(1);
            $table->string('url')->unique()->nullable();
            $table->text('details_ru')->nullable();
            $table->text('details_en')->nullable();
            $table->timestamps();
            $table->unique(['name_ru', 'parent_id']);
            $table->unique(['name_en', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
