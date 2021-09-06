<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryAddPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_add_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title', 1024)->nullable();
            $table->string('title_ru', 1024)->nullable();
            $table->string('title_es', 1024)->nullable();
            $table->string('meta_title', 1024)->nullable();
            $table->string('meta_title_ru', 1024)->nullable();
            $table->string('meta_title_es', 1024)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_keywords_ru')->nullable();
            $table->text('meta_keywords_es')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_description_ru')->nullable();
            $table->text('meta_description_es')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_add_pages');
    }
}
