<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralPayoutRequest;
use App\Http\Resources\ReferralPayoutResource;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Modules\PaymentSystems\UnitpayModule;
use Illuminate\Support\Facades\Auth;
use Response;

class ReferralController extends Controller
{
    /**
     * @param ReferralPayoutRequest $request
     * @return ReferralPayoutResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function payout(ReferralPayoutRequest $request)
    {
        $wallet = Auth::user()->getReferralWallet();
        $transactionType = TransactionType::getByName('payout');
        if(!$wallet){
            throw new \Exception(__('Insufficient funds'));
        }

        $transactionData    = [
            'amount'            => $request->amount,
            'type_id'           => $transactionType->id,
            'user_id'           => Auth::id(),
            'wallet_id'         => $wallet->id,
            'currency_id'       => $wallet->currency_id,
            'payment_system_id' => $wallet->payment_system_id,
        ];
        $transaction = Transaction::create($transactionData);

        try{
            $response = UnitpayModule::referralPayout($request, $transaction);
        }catch (\Exception $e){
            return  Response::json(['errors' => [$e->getMessage()]], 500);
        }

        if((isset($response->error))){
            return (new ReferralPayoutResource($response))->response()->setStatusCode(500);

        }
        $transaction->response = json_encode($response);
        $transaction->save();
        if(isset($response->result) && $response->result->status === 'success'){
                $wallet->balance  -= $request->amount;
                $wallet->save();
            $result = $response->result;
            return new ReferralPayoutResource($result);
        }else{
            return  Response::json(['errors' => ['Выплата не произведена']], 500);
        }
    }
}
