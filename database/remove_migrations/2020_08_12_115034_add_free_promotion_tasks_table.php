<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreePromotionTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('free_promotion')->default(0)->nullable();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('social_category');
            $table->integer('category_id')->unsigned();
            $table->text('comments');
            $table->integer('execution_qty');
            $table->integer('status_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
//        Schema::create('free_promotion_tasks', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->string('user_id', 36);
//            $table->integer('category_id')->unsigned();
//            $table->integer('pay_amount')->unsigned();
//            $table->integer('execution_qty');
//            $table->string('link', 2056);
//            $table->string('status', 64);
//            $table->text('comments');
//
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
//
//            $table->timestamps();
//        });
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
