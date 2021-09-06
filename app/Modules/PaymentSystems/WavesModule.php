<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\PaymentSystems;

use App\Models\Currency;
use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Modules\PythonInterpreterModule;
use App\Models\User;

/**
 * Class WavesModule
 * @package App\Modules\PaymentSystems
 */
class WavesModule
{
    /**
     * @return array
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getBalances(): array
    {
        $ps       = PaymentSystem::getByCode('waves');
        $balances = [];

        /** @var Currency $currency */
        foreach ($ps->currencies()->get() as $currency) {
            $balances[$currency->code] = self::getBalance($currency->currency_id);
        }

        if (count($balances) > 0 && !empty($ps)) {
            $ps->update([
                'external_balances' => json_encode($balances),
                'connected'         => true,
            ]);
        } else {
            $ps->update([
                'external_balances' => json_encode([]),
                'connected'         => false,
            ]);
            throw new \Exception('Balance is not reachable.');
        }
        return $balances;
    }

    /**
     * @param string $currencyId
     * @return float
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getBalance(string $currencyId): float
    {
        $method  = 'GET';
        $address = 'assets/balance/'.config('money.waves_account_address').'/'.$currencyId;

        $request = self::request($method, $address);

        if (!isset($request->address) || !isset($request->assetId) || !isset($request->balance)) {
            return 0.00000000;
        }

        return number_format($request->balance * 0.00000001, 8, '.', '');
    }

    /**
     * @param string $currencyCode
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function getAddressByCurrencyCode(string $currencyCode)
    {
        switch($currencyCode) {
            case 'hm1_testnet';
                return config('money.waves_hm1_testnet_wallet');
        }
    }

    /**
     * @param string $method
     * @param string $address
     * @param array $data
     * @param array|null $additionHeaders
     * @return string
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function request($method, $address, $data=[], $additionHeaders=null)
    {
        $client   = new \GuzzleHttp\Client();
        $headers  = [
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];
        $params   = [
            'headers' => array_merge($headers, (is_array($additionHeaders) ? $additionHeaders : [])),
            'verify'  => false,
            'json'    => $data,
        ];

        try {
            $response = $client->request($method, 'https://nodes.wavesplatform.com/'.$address, $params);
        } catch (\Exception $e) {
            throw new \Exception('Request to '.$address.' is failed. '.$e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Request to '.$address.' was with response status '.$response->getStatusCode());
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param Transaction $transaction
     * @return string
     * @throws \Exception
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

        $request        = PythonInterpreterModule::command('Waves/SendTransfer.py', [
            config('money.waves_private_key'),
            $wallet->external,
            $currency->currency_id,
            number_format($transaction->amount * 100000000, $currency->precision, '.', ''),
        ]);

        if (!preg_match("/id\'\: u\'([A-Za-z0-9]+)\'/", $request, $id)) {
            throw new \Exception($request);
        }

        if (!isset($id[1])) {
            throw new \Exception($request);
        }

        return $id[1];
    }
}