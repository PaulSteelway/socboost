<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCatFieldsToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            DB::statement('ALTER TABLE `categories` 
                CHANGE COLUMN `icon_class` `icon_class` VARCHAR(64) COLLATE \'utf8mb4_unicode_ci\' NULL ,
                CHANGE COLUMN `icon_img` `icon_img` VARCHAR(1024) COLLATE \'utf8mb4_unicode_ci\' NULL ;
            ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
