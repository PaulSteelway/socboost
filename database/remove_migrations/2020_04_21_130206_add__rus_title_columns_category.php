<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRusTitleColumnsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon_title1_ru',64)->default('Title 1 RUS')->nullable();
            $table->string('icon_title2_ru',64)->default('Title 2 RUS')->nullable();
            $table->string('icon_title3_ru',64)->default('Title 3 RUS')->nullable();
            $table->string('icon_title4_ru',64)->default('Title 4 RUS')->nullable();
            $table->string('icon_subtitle1_ru',64)->default('Sub title 1 RUS')->nullable();
            $table->string('icon_subtitle2_ru',64)->default('Sub title 2 RUS')->nullable();
            $table->string('icon_subtitle3_ru',64)->default('Sub title 3 RUS')->nullable();
            $table->string('icon_subtitle4_ru',64)->default('Sub title 4 RUS')->nullable();
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
            $table->dropColumn('icon_title1_ru');
            $table->dropColumn('icon_title2_ru');
            $table->dropColumn('icon_title3_ru');
            $table->dropColumn('icon_title4_ru');
            $table->dropColumn('icon_subtitle1_ru');
            $table->dropColumn('icon_subtitle2_ru');
            $table->dropColumn('icon_subtitle3_ru');
            $table->dropColumn('icon_subtitle4_ru');
        });
    }
}
