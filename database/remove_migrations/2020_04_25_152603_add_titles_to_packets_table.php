<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitlesToPacketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packets', function (Blueprint $table) {
            $table->string('icon_title1', 64)->default('Title 1')->nullable()->after('link');
            $table->string('icon_title2', 64)->default('Title 2')->nullable()->after('icon_title1');
            $table->string('icon_title3', 64)->default('Title 3')->nullable()->after('icon_title2');
            $table->string('icon_title4', 64)->default('Title 4')->nullable()->after('icon_title3');
            $table->string('icon_subtitle1', 64)->default('Sub title 1')->nullable()->after('icon_title4');
            $table->string('icon_subtitle2', 64)->default('Sub title 2')->nullable()->after('icon_subtitle1');
            $table->string('icon_subtitle3', 64)->default('Sub title 3')->nullable()->after('icon_subtitle2');
            $table->string('icon_subtitle4', 64)->default('Sub title 4')->nullable()->after('icon_subtitle3');
            $table->string('icon_title1_ru', 64)->default('Заголовок 1')->nullable()->after('icon_subtitle4');
            $table->string('icon_title2_ru', 64)->default('Заголовок 2')->nullable()->after('icon_title1_ru');
            $table->string('icon_title3_ru', 64)->default('Заголовок 3')->nullable()->after('icon_title2_ru');
            $table->string('icon_title4_ru', 64)->default('Заголовок 4')->nullable()->after('icon_title3_ru');
            $table->string('icon_subtitle1_ru', 64)->default('Подзаголовок 1')->nullable()->after('icon_title4_ru');
            $table->string('icon_subtitle2_ru', 64)->default('Подзаголовок 2')->nullable()->after('icon_subtitle1_ru');
            $table->string('icon_subtitle3_ru', 64)->default('Подзаголовок 3')->nullable()->after('icon_subtitle2_ru');
            $table->string('icon_subtitle4_ru', 64)->default('Подзаголовок 4')->nullable()->after('icon_subtitle3_ru');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packets', function (Blueprint $table) {
            $table->dropColumn('icon_title1');
            $table->dropColumn('icon_title2');
            $table->dropColumn('icon_title3');
            $table->dropColumn('icon_title4');
            $table->dropColumn('icon_subtitle1');
            $table->dropColumn('icon_subtitle2');
            $table->dropColumn('icon_subtitle3');
            $table->dropColumn('icon_subtitle4');
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
