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
 * Class BlockioModule
 * @package App\Modules\PaymentSystems
 */
class BlockioModule
{
    /**
     * @return array
     * @throws \Exception
     */
    public static function getBalances(): array
    {
        $ps       = PaymentSystem::getByCode('blockio');
        $balances = [];

        foreach ($ps->currencies as $currency) {
            try {
                $balances[$currency->code] = self::getBalance($currency->code);
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage());
            }
        }

        if (count($balances) > 0 && !empty($ps)) {
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

        return $balances;
    }

    /**
     * @param string $currency
     * @return float
     * @throws \Exception
     */
    public static function getBalance(string $currency): float
    {
        $ps       = PaymentSystem::getByCode('blockio');
        $currency = Currency::where('code', $currency)->first();

        if (null == $ps || null == $currency) {
            return 0;
        }

        switch ($currency->code) {
            case 'BTC':
                $apiKey = config('money.blockio_api_key_btc');
                break;

            case 'LTC':
                $apiKey = config('money.blockio_api_key_ltc');
                break;

            case 'DOGE':
                $apiKey = config('money.blockio_api_key_doge');
                break;

            default:
                throw new \Exception('Currency not supportable.');
        }

        $version  = 2; // API version
        $pin      = config('money.blockio_pin');
        $block_io = new \BlockIo($apiKey, $pin, $version);
        $balance  = $block_io->get_balance();

        if (!isset($balance->status) || $balance->status != 'success') {
            throw new \Exception('Can not reach balance for '.$currency->code);
        }

        $data = $balance->data;

        return $data->available_balance;
    }

    /**
     * @param Transaction $transaction
     * @return int
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

        switch ($currency->code) {
            case 'BTC':
                $apiKey = config('money.blockio_api_key_btc');
                break;

            case 'LTC':
                $apiKey = config('money.blockio_api_key_ltc');
                break;

            case 'DOGE':
                $apiKey = config('money.blockio_api_key_doge');
                break;

            default:
                throw new \Exception('Currency not supportable.');
        }

        $version  = 2; // API version
        $pin      = config('money.blockio_pin');
        $block_io = new \BlockIo($apiKey, $pin, $version);

        $result = $block_io->withdraw([
            'amounts'      => $transaction->amount,
            'to_addresses' => $wallet->external,
            'pin'          => $pin,
        ]);

        if (!isset($result->status) || $result->status != 'success' || !isset($result->data->txid)) {
            $msg = isset($result->data->error_message)
                ? $result->data->error_message
                : 'Message undefined';
            throw new \Exception('Operation failed. '.$msg);
        }

        return $result->data->txid;
    }

    /**
     * @param string $currency
     * @param string $address
     * @return \stdClass
     * @throws \Exception
     */
    public static function archiveAddress(string $currency, string $address)
    {
        $ps       = PaymentSystem::getByCode('blockio');
        $currency = Currency::where('code', $currency)->first();

        if (null == $ps || null == $currency) {
            throw new \Exception('Currency or payment system not found.');
        }

        switch ($currency->code) {
            case 'BTC':
                $apiKey = config('money.blockio_api_key_btc');
                break;

            case 'LTC':
                $apiKey = config('money.blockio_api_key_ltc');
                break;

            case 'DOGE':
                $apiKey = config('money.blockio_api_key_doge');
                break;

            default:
                throw new \Exception('Currency not supportable.');
        }

        $version  = 2; // API version
        $pin      = config('money.blockio_pin');
        $block_io = new \BlockIo($apiKey, $pin, $version);

        $result = $block_io->archive_address([
            'addresses' => $address,
        ]);

        if (!isset($result->status) || $result->status != 'success') {
            $msg = isset($result->data->error_message)
                ? $result->data->error_message
                : 'Message undefined';
            throw new \Exception('Archiving address failed. '.$msg);
        }

        return $result;
    }

    /**
     * @param string $currency
     * @param bool $archived
     * @return mixed
     * @throws \Exception
     */
    public static function getAddresses(string $currency, $archived=false)
    {
        $ps       = PaymentSystem::getByCode('blockio');
        $currency = Currency::where('code', $currency)->first();

        if (null == $ps || null == $currency) {
            throw new \Exception('Currency or payment system not found.');
        }

        switch ($currency->code) {
            case 'BTC':
                $apiKey = config('money.blockio_api_key_btc');
                break;

            case 'LTC':
                $apiKey = config('money.blockio_api_key_ltc');
                break;

            case 'DOGE':
                $apiKey = config('money.blockio_api_key_doge');
                break;

            default:
                throw new \Exception('Currency not supportable.');
        }

        $version  = 2; // API version
        $pin      = config('money.blockio_pin');
        $block_io = new \BlockIo($apiKey, $pin, $version);

        if (false === $archived) {
            $result = $block_io->get_my_addresses([]);
        } elseif(true === $archived) {
            $result = $block_io->get_my_archived_addresses([]);
        } else {
            throw new \Exception('Undefined waiting addresses type');
        }

        if (!isset($result->status) || $result->status != 'success') {
            $msg = isset($result->data->error_message)
                ? $result->data->error_message
                : 'Message undefined';
            throw new \Exception('Getting unarchived addresses failed. '.$msg);
        }

        return $result;
    }

    /**
     * @param string $currency
     * @param string $address
     * @return mixed
     * @throws \Exception
     */
    public static function unarchiveAddress(string $currency, string $address)
    {
        $ps       = PaymentSystem::getByCode('blockio');
        $currency = Currency::where('code', $currency)->first();

        if (null == $ps || null == $currency) {
            throw new \Exception('Currency or payment system not found.');
        }

        switch ($currency->code) {
            case 'BTC':
                $apiKey = config('money.blockio_api_key_btc');
                break;

            case 'LTC':
                $apiKey = config('money.blockio_api_key_ltc');
                break;

            case 'DOGE':
                $apiKey = config('money.blockio_api_key_doge');
                break;

            default:
                throw new \Exception('Currency not supportable.');
        }

        $version  = 2; // API version
        $pin      = config('money.blockio_pin');
        $block_io = new \BlockIo($apiKey, $pin, $version);

        $result = $block_io->unarchive_addresses([
            'addresses' => $address
        ]);

        if (!isset($result->status) || $result->status != 'success') {
            $msg = isset($result->data->error_message)
                ? $result->data->error_message
                : 'Message undefined';
            throw new \Exception('Getting unarchived addresses failed. '.$msg);
        }

        return $result;
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     * @throws \Exception
     */
    public static function createWalletAndNotification(Transaction $transaction)
    {
        $ps       = PaymentSystem::getByCode('blockio');
        $currency = $transaction->currency;

        if (null == $ps || null == $currency) {
            throw new \Exception('Currency or payment system not found.');
        }

        switch ($currency->code) {
            case 'BTC':
                $apiKey = config('money.blockio_api_key_btc');
                break;

            case 'LTC':
                $apiKey = config('money.blockio_api_key_ltc');
                break;

            case 'DOGE':
                $apiKey = config('money.blockio_api_key_doge');
                break;

            default:
                throw new \Exception('Currency not supportable.');
        }

        $version  = 2; // API version
        $pin      = config('money.blockio_pin');
        $block_io = new \BlockIo($apiKey, $pin, $version);

        $newWallet = $block_io->get_new_address([
            'label' => $transaction->user->id,
        ]);

        if (!isset($newWallet->status) || $newWallet->status != 'success') {
            $msg = isset($newWallet->data->error_message)
                ? $newWallet->data->error_message
                : 'Message undefined';
            throw new \Exception('Creating new wallet failed. '.$msg);
        }

        $newWalletAddress = $newWallet->data->address;

        /** @var User $user */
        $user = $transaction->user;
        $user->setAttribute('blockio_wallet_'.strtolower($currency->code), $newWalletAddress);
        $user->save();

        try {
            $createNotification = $block_io->create_notification([
                'url'     => route('blockio.status'),
                'type'    => 'address',
                'address' => $newWalletAddress,
            ]);

            \DB::table('blockio_notifications')->insert([
                'user_id'         => $user->id,
                'notification_id' => $createNotification->data->notification_id,
                'network'         => $currency->code,
                'created_at'      => now()
            ]);
        } catch (\Exception $e) {
            $msg = isset($newWallet->data->error_message)
                ? $newWallet->data->error_message
                : 'Message undefined';
            throw new \Exception('Creating notification for wallet failed. '.$msg);
        }
    }
}