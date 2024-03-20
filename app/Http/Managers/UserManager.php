<?php

namespace App\Http\Managers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Types\RefferalUserType;
use Nexmo\Client;
use Nexmo\Client\Credentials\Basic;

class UserManager
{
    /**
     * @param Transaction $transaction
     * @param $amount
     */
    public static function accrueReferralBonusForEnter(Transaction $transaction, $amount)
    {
        try {
            if (!isset($transaction->user)) {
                return;
            }
            $referalUserTypes = $transaction->user->getReferralDepthTypes();
            foreach ($referalUserTypes as $referalUserType) {
                /** @var RefferalUserType $referalUserType */
                $referral = $referalUserType->getUser();
                $referralAmount = bcmul(bcdiv($amount, 100, 4), $referalUserType->getReferalBonus(), 2);

                $wallet = $referral->getReferralWallet();
                if (empty($wallet)) {
                    $wallet = Wallet::setReferralWallet($referral);
                }

                Transaction::referral($wallet, $referralAmount, $transaction->id);
                $wallet->refill($referralAmount, null);
            }
        } catch (\Exception $e) {
            var_dump($e);
        }

    }

    /**
     * @param Transaction $transaction
     * @throws \Throwable
     */
    public static function accrueBonusWithTopup(Transaction $transaction)
    {
        $bonusAmount = null;
        if ($transaction->currency->code == 'USD') {
            if ($transaction->amount >= 10 && $transaction->amount < 20) {
                $bonusAmount = 1;
            } elseif ($transaction->amount >= 20 && $transaction->amount < 50) {
                $bonusAmount = 2;
            } elseif ($transaction->amount >= 50 && $transaction->amount < 100) {
                $bonusAmount = 7;
            } elseif ($transaction->amount >= 100) {
                $bonusAmount = 15;
            }
        } else {
            if ($transaction->amount >= 650 && $transaction->amount < 1300) {
                $bonusAmount = 65;
            } elseif ($transaction->amount >= 1300 && $transaction->amount < 3000) {
                $bonusAmount = 130;
            } elseif ($transaction->amount >= 3000 && $transaction->amount < 7000) {
                $bonusAmount = 470;
            } elseif ($transaction->amount >= 7000) {
                $bonusAmount = 900;
            }
        }
        if (!empty($bonusAmount)) {
            /** @var Wallet $wallet */
            $wallet = $transaction->user->getActiveWallet();
            Transaction::bonus($wallet, $bonusAmount, $transaction->currency, $transaction->id);
            $transactionAmount = $bonusAmount;
            $wallet->refill($transactionAmount, null);
        }
    }

    /**
     * @return string
     */
    public static function determineUserCountry()
    {
      //nit: Daan disabled geoip
      // похоже эта штука не работает, а только кучу логов делает

        // try {
        //     if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        //     } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        //         $ip = $_SERVER['REMOTE_ADDR'];
        //     } else {
        //         $ip = null;
        //     }
        //     $location = geoip($ip);
        //     return (empty($location->iso_code) || $location->country == 'not available') ? 'RU' : $location->iso_code;
        // } catch (\Exception $e) {
        // }
        return 'RU';
    }

    /**
     * @param $locale
     * @return string
     */
    public static function getLocaleRoute($locale)
    {
        $rootHost = parse_url(config('app.url'), PHP_URL_HOST);
        $newLocale = $locale == 'en' ? $locale . '.' . $rootHost : $rootHost;

        $previous = url()->previous();
        $currentLocale = parse_url($previous, PHP_URL_HOST);
        if (strpos($currentLocale, $rootHost) === false) {
            return $newLocale;
        } else {
            return str_replace($currentLocale, $newLocale, $previous);
        }
    }

    /**
     * @param string $phone
     * @return string|null
     */
    public static function getFormattedPhone($phone)
    {
        if (empty($phone)) {
            return null;
        } else {
            return '+' . preg_replace('/\D/', '', $phone);
        }
    }

    /**
     * @param $phone
     * @param $message
     * @return bool
     */
    public static function sendSms($phone, $message)
    {
        try {
            if (config('services.nexmo.key') && config('services.nexmo.secret')) {
                $phone = self::getFormattedPhone($phone);
                if (!empty($phone) && strlen($phone) > 10) {
                    $basic = new Basic(config('services.nexmo.key'), config('services.nexmo.secret'));
                    $client = new Client($basic);
                    $message = $client->message()->send([
                        'to' => $phone,
                        'from' => 'SocBooster',
                        'text' => $message
                    ]);
                    $response = $message->getResponseData();
                    if ($response['messages'][0]['status'] == 0) {
                        return True;
                    } else {
                        throw new \Exception(json_encode($response));
                    }
                }
            }
            throw new \Exception('Nexmo credentials is empty.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return False;
        }
    }

}
