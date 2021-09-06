<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaBlogFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_entries', function (Blueprint $table) {
            $table->string('meta_title', 1024)->nullable();
            $table->string('meta_title_ru', 1024)->nullable();
            $table->string('meta_description', 1024)->nullable();
            $table->string('meta_description_ru', 1024)->nullable();
            $table->string('meta_keywords', 512)->nullable();
            $table->string('meta_keywords_ru', 512)->nullable();
            $table->text('description')->change();
            $table->renameColumn('title', 'title_ru')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
