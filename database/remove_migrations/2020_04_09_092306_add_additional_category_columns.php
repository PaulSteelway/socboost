<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalCategoryColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('details_title_ru',512)->nullable();
            $table->string('details_title_en',512)->nullable();
            $table->dropColumn('important_en');
            $table->dropColumn('important_ru');
            $table->dropColumn('description_en');
            $table->dropColumn('description_ru');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('important_en');
            $table->dropColumn('important_ru');
        });
    }
}
