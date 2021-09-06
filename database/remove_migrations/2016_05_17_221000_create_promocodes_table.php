<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('promocodes.table', 'promocodes'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 32)->unique();
            $table->double('reward', 10, 2)->nullable();
            $table->double('from_amount', 10, 2)->nullable();

            $table->text('data')->nullable();

            $table->boolean('is_disposable')->default(false);
            $table->timestamp('expires_at')->nullable();
        });

        Schema::create(config('promocodes.relation_table', 'promocode_user'), function (Blueprint $table) {
            $table->uuid('user_id');
            $table->unsignedBigInteger('promocode_id');
            $table->timestamp('used_at');

            $table->primary(['user_id', 'promocode_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('promocode_id')->references('id')->on(config('promocodes.table', 'promocodes'));
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('promocode_id')->nullable();
            $table->foreign('promocode_id')->references('id')->on('promocodes');

        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('spend_amount', 12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('promocodes.relation_table', 'promocode_user'));
        Schema::drop(config('promocodes.table', 'promocodes'));
    }
}
