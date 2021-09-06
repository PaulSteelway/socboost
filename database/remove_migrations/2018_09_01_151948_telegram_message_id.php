<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TelegramMessageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telegram_bot_messages', function(Blueprint $table) {
            $table->integer('message_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telegram_bot_messages', function(Blueprint $table) {
            $table->dropColumn('message_id');
        });
    }
}
