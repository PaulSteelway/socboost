<?php

use App\Models\Currency;
use App\Models\User;
use App\Models\PaymentSystem;
use App\Models\Wallet;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferralWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $currency = Currency::where('code', 'REF_RUB')->first();
        $paymentSystem = PaymentSystem::where('code', 'unitpay')->first();
        foreach(User::all() as $user){
            Wallet::newWallet($user, $currency, $paymentSystem);
        }
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
