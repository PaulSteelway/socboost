<?php

namespace App\Modules\PaymentSystems;

use App\Models\Transaction;
use Qiwi\Api\BillPayments;
/**
 * Class QIWIModule
 * @package App\Modules\PaymentSystems
 */
class QIWIModule
{
    private $billPayments;
    private $secret_key;
    public function __construct()
    {
        $private_key = config('money.qiwi_secret_key');
        $this->secret_key = $private_key;
        $this->billPayments = new BillPayments($private_key);
    }

    /**
     * @param Transaction $transaction
     * @return string
     */
    public function getRedirectPathForPay(Transaction $transaction)
    {
        $public_key = config('money.qiwi_public_key');
        $transaction_id = $transaction->id;
        $amount = $transaction->amount;
        $params = [
            'publicKey' => $public_key,
            'amount' => $amount,
            'billId' => $transaction_id,
            'successUrl' => route('qiwi.success')
        ];
        $link = $this->billPayments->createPaymentForm($params);

        return $link;
    }
//{amount.currency}|{amount.value}|{billId}|{siteId}|{status.value}
    /**
     * @param $header_hash
     * @param $bill_data
     * @return boolean
     */
    public function checkSignature($header_hash, $bill_data)
    {
        return $this->billPayments->checkNotificationSignature($header_hash, $bill_data, $this->secret_key);
    }
}
