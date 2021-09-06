<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderToPaymentSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_systems', function (Blueprint $table) {
            $table->smallInteger('order')->nullable()->after('minimum_withdraw');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_systems', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
