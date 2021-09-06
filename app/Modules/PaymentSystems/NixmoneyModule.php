<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\PaymentSystems;

use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

/**
 * Class NixmoneyModule
 * @package App\Modules\PaymentSystems
 */
class NixmoneyModule
{
    /**
     * @return array
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getBalances(): array
    {
        $ps       = PaymentSystem::getByCode('nixmoney');
        $balances = [];
        $client   = new \GuzzleHttp\Client();

        try {
            $res = $client->request('POST', 'https://www.nixmoney.com/balance', [
                'form_params' => [
                    'ACCOUNTID' => config('money.nixmoney_account_id'),
                    'PASSPHRASE' => config('money.nixmoney_account_password'),
                ]
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if ($res->getStatusCode() != 200) {
            throw new \Exception('Response status is '.$res->getStatusCode());
        }

        $body = $res->getBody()->getContents();

        // searching for hidden fields
        if (!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $body, $result, PREG_SET_ORDER)) {
            $ps->update([
                'external_balances' => json_encode([]),
                'connected' => false,
            ]);
            throw new \Exception('Error reading Nixmoney response.');
        }

        // forming currency=>amount array
        foreach ($result as $item) {
            if (preg_match('/^U[0-9]{14,}$/', $item[1])) {
                $balances['USD'] = $item[2];
            } elseif (preg_match('/^E[0-9]{14,}$/', $item[1])) {
                $balances['EUR'] = $item[2];
            } elseif (preg_match('/^B[0-9]{14,}$/', $item[1])) {
                $balances['BTC'] = $item[2];
            } elseif ($item[1] == 'ERROR') {
                throw new \Exception($item[2]);
            }
        }

        if(isset($balances) && count($balances) > 0 && !empty($ps)){
            $ps->update([
                'external_balances' => json_encode($balances),
                'connected' => true,
            ]);
        } else {
            $ps->update([
                'external_balances' => json_encode([]),
                'connected' => false,
            ]);
            throw new \Exception('Balance is not reachable.');
        }
        return isset($balances) ? $balances : [];
    }

    /**
     * @param string $currency
     * @return float
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getBalance(string $currency): float
    {
        $balances = self::getBalances();
        return key_exists($currency, $balances) ? $balances[$currency] : 0;
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function transfer(Transaction $transaction
    ) {
        /** @var Wallet $wallet */
        $wallet         = $transaction->wallet()->first();
        /** @var User $user */
        $user           = $wallet->user()->first();
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem  = $wallet->paymentSystem()->first();
        /** @var Currency $currency */
        $currency       = $wallet->currency()->first();

        if (null === $wallet || null === $user || null === $paymentSystem || null === $currency) {
            throw new \Exception('Wallet, user, payment system or currency is not found for withdrawal approve.');
        }

        if ($currency->code == 'USD') {
            $account = config('money.nixmoney_wallet_usd');
        } elseif ($currency->code == 'EUR') {
            $account = config('money.nixmoney_wallet_eur');
        } elseif ($currency->code == 'BTC') {
            $account = config('money.nixmoney_wallet_btc');
        } else {
            throw new \Exception('Nixmoney currency error');
        }

        $comment = config('money.nixmoney_withdraw_memo');
        $comment = preg_replace('/\{login\}/', $user->login, $comment);
        $comment = preg_replace('/\{amount\}/', $transaction->amount, $comment);
        $comment = preg_replace('/\{project\}/', config('app.name'), $comment);

        $client  = new \GuzzleHttp\Client();

        try {
            $res = $client->request('POST', 'https://www.nixmoney.com/send', [
                'form_params' => [
                    'PASSPHRASE'    => config('money.nixmoney_account_password'),
                    'PAYER_ACCOUNT' => $account,
                    'PAYEE_ACCOUNT' => $wallet->external,
                    'AMOUNT'        => $transaction->amount,
                    'MEMO'          => $comment,
                ],
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if ($res->getStatusCode() != 200) {
            throw new \Exception('Response status is '.$res->getStatusCode());
        }

        $body = $res->getBody()->getContents();

        // searching for hidden fields
        if (!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $body, $result, PREG_SET_ORDER)) {
            $paymentSystem->update([
                'external_balances' => json_encode([]),
                'connected' => false,
            ]);
            throw new \Exception('Error reading Nixmoney response.');
        }

        $resultRows = [];

        // forming currency=>amount array
        foreach ($result as $item) {
            if ('PAYER_ACCOUNT' == $item[1]) {
                $resultRows[$item[1]] = $item[2];
            } elseif ('PAYEE_ACCOUNT' == $item[1]) {
                $resultRows[$item[1]] = $item[2];
            } elseif ('PAYMENT_AMOUNT' == $item[1]) {
                $resultRows[$item[1]] = $item[2];
            } elseif ('PAYMENT_BATCH_NUM' == $item[1]) {
                $resultRows[$item[1]] = $item[2];
            } elseif ($item[1] == 'ERROR') {
                throw new \Exception($item[2]);
            }
        }

        if (!isset($resultRows['PAYMENT_BATCH_NUM'])) {
            throw new \Exception('Batch number not found.');
        }

        return $resultRows['PAYMENT_BATCH_NUM'];
    }
}