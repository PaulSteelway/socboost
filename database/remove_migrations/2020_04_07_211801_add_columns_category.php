<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon_title1',64)->default('Title 1')->nullable();
            $table->string('icon_title2',64)->default('Title 2')->nullable();
            $table->string('icon_title3',64)->default('Title 3')->nullable();
            $table->string('icon_title4',64)->default('Title 4')->nullable();
            $table->string('icon_subtitle1',64)->default('Sub title 1')->nullable();
            $table->string('icon_subtitle2',64)->default('Sub title 2')->nullable();
            $table->string('icon_subtitle3',64)->default('Sub title 3')->nullable();
            $table->string('icon_subtitle4',64)->default('Sub title 4')->nullable();
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
            $table->dropColumn('icon_title1');
            $table->dropColumn('icon_title2');
            $table->dropColumn('icon_title3');
            $table->dropColumn('icon_title4');
            $table->dropColumn('icon_subtitle1');
            $table->dropColumn('icon_subtitle2');
            $table->dropColumn('icon_subtitle3');
            $table->dropColumn('icon_subtitle4');
        });
    }
}
