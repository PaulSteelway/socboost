<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('user_socials', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('user_id');
          $table->string('social_type', 20)->nullable();
          $table->string('social_username', 100);
          $table->string('social_id', 100)->nullable();
          $table->integer('follower')->unsigned()->default(0);
          $table->integer('following')->unsigned()->default(0);
          $table->integer('liking')->default(0);
          $table->integer('liker')->default(0);
          $table->string('social_avatar')->nullable();
          $table->boolean('private')->default(false);
          $table->boolean('open_data')->default(false);
          $table->boolean('can_check')->default(false);
          $table->text('checked')->nullable();
          $table->boolean('active')->default(false);
          $table->softDeletes();
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_socials');
    }
}
