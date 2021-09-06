<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id', 36);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->char('currency_id', 36);
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('type', config('enumerations.subscription_types'));
            $table->unsignedInteger('period')->default(30);
            $table->date('date_at')->nullable();
            $table->unsignedInteger('packet_id')->nullable();
            $table->foreign('packet_id')->references('id')->on('packets')->onDelete('set null')->onUpdate('cascade');
            $table->string('subscription_id', 64)->nullable()->unique();
            $table->enum('status', config('enumerations.subscription_statuses'))->default('new');
            $table->string('payment_method', 16)->nullable();
            $table->string('ip', 16)->nullable();
            $table->string('username', 64)->nullable();
            $table->unsignedInteger('posts')->nullable();
            $table->unsignedInteger('qty_min')->nullable();
            $table->unsignedInteger('qty_max')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
