<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVoucherAsPaymentSystem extends Migration
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
                'name' => 'Voucher',
                'code' => 'voucher',
                'connected' => 0,
            )
        );
        $paymentSystem->save();
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
