<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCloudpaymentsToPaymentSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $paymentSystem = new \App\Models\PaymentSystem(
            array(
                'name' => 'CloudPayments',
                'code' => 'cloudpayments',
                'connected' => 0,
            )
        );
        $paymentSystem->save();

        $currency = \App\Models\Currency::where('code', 'RUR')->first();

        \DB::table('currency_payment_system')->insert([
            'currency_id' => $currency->id,
            'payment_system_id' => $paymentSystem->id,
        ]);
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
