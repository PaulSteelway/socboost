<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_id')->nullable()->after('jap_status');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedInteger('min')->nullable()->after('username');
            $table->unsignedInteger('max')->nullable()->after('min');
            $table->string('expiry', 16)->nullable()->after('max');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subscription_id', 'min', 'max', 'expiry']);
        });
    }
}
