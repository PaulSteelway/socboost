<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReviewFields extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'user_ip')) {
                $table->string('user_ip')->nullable();
                $table->integer('time');
            }
            // Добавьте другие изменения для таблицы reviews
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('user_ip');
            $table->integer('time');

            // Удалите другие добавленные столбцы или изменения
        });
    }
}

// class ReviewFields extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::table('reviews', function (Blueprint $table) {
//             $table->string('user_ip');
//         });
//     }

//     /**
//      * Reverse the migrations.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         //
//     }
// }
