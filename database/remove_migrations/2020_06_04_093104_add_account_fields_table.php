<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_categories', function (Blueprint $table) {
            $table->string('meta_title', 1024)->nullable();
            $table->string('meta_title_ru', 1024)->nullable();
            $table->string('meta_description', 1024)->nullable();
            $table->string('meta_description_ru', 1024)->nullable();
            $table->string('meta_keywords', 1024)->nullable();
            $table->string('meta_keywords_ru', 1024)->nullable();
            $table->string('title', 1024)->nullable();
            $table->string('title_ru', 1024)->nullable();
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
