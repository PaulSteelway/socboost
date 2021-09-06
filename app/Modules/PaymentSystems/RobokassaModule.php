<?php

namespace App\Modules\PaymentSystems;

use App\Models\Transaction;

/**
 * Class UnitpayModule
 * @package App\Modules\PaymentSystems
 */
class RobokassaModule
{


    public static function getRobokassaWidgetData(Transaction $transaction, array $additional_fields=[])
    {
        $path = self::gerRedirectPathForPay($transaction);
        return $path;
    }

    /**
     * @param Transaction $transaction
     * @return string
     */
    public static function gerRedirectPathForPay(Transaction $transaction)
    {
        $login = config('money')['robokassa_login'];
        $invId = $transaction->id;
        $outSum = $transaction->amount;
        $outSumCurrency = $transaction->currency->code == 'USD' ? 'USD' : 'RUB';
        $desc = __('Add funds') . ' (' . $outSum . ' ' . __($outSumCurrency) . ')';
        if ($outSumCurrency == 'USD') {
            $signature = self::getFormSignature($login, $outSum, $invId, $outSumCurrency);
            if (config('app')['debug'] == false) {
                return "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin={$login}&OutSum={$outSum}&InvId=0&OutSumCurrency={$outSumCurrency}&Description={$desc}&Shp_id={$invId}&SignatureValue={$signature}&IsTest=1";
            } else {
                return "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin={$login}&OutSum={$outSum}&InvId=0&OutSumCurrency={$outSumCurrency}&Description={$desc}&Shp_id={$invId}&SignatureValue={$signature}";
            }
        } else {
            $signature = self::getFormSignature($login, $outSum, $invId);
            if (config('app')['debug'] == false) {
                return "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin={$login}&OutSum={$outSum}&InvId=0&Description={$desc}&Shp_id={$invId}&SignatureValue={$signature}&IsTest=1";
            } else {
                return "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin={$login}&OutSum={$outSum}&InvId=0&Description={$desc}&Shp_id={$invId}&SignatureValue={$signature}";
            }
        }
    }

    /**
     * @param $login
     * @param $outSum
     * @param $invId
     * @param null|string $outSumCurrency
     * @return string
     */
    public static function getFormSignature($login, $outSum, $invId, $outSumCurrency = null)
    {
        $password = config('money')['robokassa_password_1'];
        if (empty($outSumCurrency)) {
            return md5("$login:$outSum:0:$password:Shp_id=$invId");
        } else {
            return md5("$login:$outSum:0:$outSumCurrency:$password:Shp_id=$invId");
        }
    }

    /**
     * @param $outSum
     * @param $invId
     * @param $signature
     * @param $shpId
     * @return bool
     */
    public static function validateResponse($outSum, $invId, $signature, $shpId)
    {
        $password2 = config('money')['robokassa_password_2'];

        $mySignature = strtoupper(md5("$outSum:$invId:$password2:Shp_id=$shpId"));

        $signature = strtoupper($signature);

        return $mySignature == $signature;
    }
}
