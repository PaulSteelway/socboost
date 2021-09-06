<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyUsd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $currency = new \App\Models\Currency(
            array(
                'name' => 'U.S. dollar',
                'code' => 'USD',
                'precision' => 2,
                'symbol' => '$'
            )
        );
        $currency->save();
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
