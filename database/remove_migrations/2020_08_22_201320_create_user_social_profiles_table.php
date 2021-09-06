<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSocialProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social_profiles', function (Blueprint $table) {
            $table->foreignUuid('user_id')->primary()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('instagram_id', 32)->nullable();
            $table->string('instagram_username', 64)->nullable();
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
        Schema::dropIfExists('user_social_profiles');
    }
}
