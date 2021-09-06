<?php

namespace App\Models;

use App\Http\Managers\UserManager;
use App\Types\RefferalUserType;
use Carbon\Carbon;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Str;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Uuids;
use Lab404\Impersonate\Models\Impersonate;

use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use App\Mail\EmailAutoRegistration;

/**
 * Class User
 * @package App\Models
 *
 * @property UserSocialProfile $userSocialProfile
 */
class User extends Authenticatable
{

  use Notifiable, HasRoles, Uuids, Impersonate;

  const MAX_LOGIN_ATTEMPTS = 5;
  const LOGIN_BLOCKING = 60;

  public $incrementing = false;


  protected $fillable = [
    'name', 'lastname', 'telegram', 'email', 'login', 'password', 'my_id', 'partner_id', 'phone', 'skype',
    'created_at', 'blockio_wallet_btc', 'blockio_wallet_ltc', 'blockio_wallet_doge', 'sex', 'city', 'country',
    'longitude', 'latitude', 'email_verified_at', 'email_verification_sent', 'email_verification_hash',
    'last_login', 'avatar', 'is_premium', 'discount_modal', 'api_key'
  ];

  protected $hidden = [
    'password',
    'remember_token',
    'tfa_token'
  ];


  public function isVerifiedEmail() {
    return (bool) $this->email_verified_at;
  }

  public function isMan() {
    return (bool) $this->sex == 'man';
  }

  public function isWoman() {
    return (bool) $this->sex == 'woman';
  }


  // Relations
  public function transactions() {
    return $this->hasMany('App\\Models\\Transaction', 'user_id');
  }

  public function userTaskActions() {
    return $this->hasMany('App\\Models\\UserTasks\\UserTaskActions', 'user_id');
  }

  public function userTasks() {
    return $this->hasMany('App\\Models\\UserTasks\\UserTasks', 'user_id');
  }

  public function wallets() {
    return $this->hasMany('App\\Models\\Wallet', 'user_id');
  }

  public function userReferral() {
    return $this->hasOne('App\\Models\\UserReferral', 'id');
  }

  public function userSocialProfile() {
    return $this->hasOne('App\\Models\\UserSocialProfile', 'user_id');
  }

  public function telegramUser() {
    return $this->hasMany('App\\Models\\Telegram\\TelegramUsers', 'user_id');
  }

  public function taskPropositions() {
    return $this->hasMany('App\\Models\\UserTasks\\UserTaskPropositions', 'user_id');
  }

  public function socialMeta() {
    return $this->hasMany('App\\Models\\UsersSocialMeta', 'user_id');
  }

  public function youtubeVideoWatches() {
    return $this->hasMany('App\\Models\\Youtube\\YoutubeVideoWatch', 'user_id');
  }

  public function deposits() {
    return $this->hasMany('App\\Models\\Deposit', 'user_id');
  }

  public function user_ips() {
    return $this->hasMany('App\\Models\\UserIp', 'user_id');
  }

  public function mailSents() {
    return $this->hasMany('App\\Models\\MailSent', 'user_id');
  }

  public function orders() {
    return $this->hasMany('App\\Models\\Order', 'user_id', 'id')
      ->orderBy('created_at', 'desc');
  }

  public function newOrders() {
    return $this->hasMany('App\\Models\\Order', 'user_id', 'id')
      ->where(function($q) {
        return $q->whereNotNull('jap_status')
          ->orWhereNotNull('product_id');
      })
      ->orderBy('created_at', 'desc');
  }


  //создаем API ключь
  public function createUserApiKey() {
    $exist = true;
    while ($exist) {
      $apiKey = Str::uuid()->getHex();
      $exist = self::where('api_key', $apiKey)->select('api_key')->first();
    }
    $this->api_key = $apiKey;
    $this->save();
  }




  //other
  public function sendNotification(string $code, array $data, int $delay = 0) {

    if (config('app.user_notifications_enabled') == 1) {
      if (!empty($this->email)) {
        $this->sendEmailNotification($code, $data);
      }

      if (config('app.env') !== 'development') {
        $this->sendTelegramNotification($code, $data, NULL, $delay);
      }

    }
  }

  //создаем проверочный код и отправляем
  public function sendVerificationEmail() {
    if ($this->email || !$this->email_verified_at) {
      $this->email_verification_sent = Carbon::now();
      $this->email_verification_hash = md5($this->email . config('app.name'));
      $this->save();

      if (config('app.user_notifications_enabled') == 1) {
        //в очередь кинуть
        try {
          Mail::to($this)->send(new EmailVerification($this, $this->email_verification_hash));
        } catch (\Exception $e) {}
      }
    }
  }

  /**
    * Отправляем пароль
    * @param string $password
    */
  public function sendEmailAutoRegistration($password) {
    $token = str_random(60);

    \DB::table('password_resets')->updateOrInsert(
      ['email' => $this->email],
      ['token' => $token, 'created_at' => Carbon::now()]
    );

    if (config('app.user_notifications_enabled') == 1) {
      //в очередь кинуть
      try {
        Mail::to($this->email)
          ->send(new EmailAutoRegistration($this, $password, $token));
      } catch (\Exception $e) {}
    }
  }






  //part refactor
  public function getActiveWallet() {
    if (isFreePromotionSite()) {
      $wallet = $this
        ->wallets()
        ->select('wallets.*')
        ->join('currencies', function ($join) {
          $join->on('currencies.id', '=', 'wallets.currency_id')
          ->where('currencies.code', 'FREE_POINTS');
        })
        ->join('payment_systems', function ($join) {
          $join->on('payment_systems.id', '=', 'wallets.payment_system_id')
          ->where('payment_systems.code', 'freePromotion');
        })
        ->first();

      if (empty($wallet)) {
        $wallet = Wallet::setFreePromotionWallet($this);
      }

      return $wallet;

    } else {

      return $this
        ->wallets()
        ->select('wallets.*')
        ->join('currencies', function ($join) {
          $join->on('currencies.id', '=', 'wallets.currency_id')
          ->whereIn('currencies.code', ['RUB', 'RUR']);
        })
        ->join('payment_systems', function ($join) {
          $join->on('payment_systems.id', '=', 'wallets.payment_system_id')
          ->where('payment_systems.code', 'free-kassa');
        })
        ->first();
    }
  }





  //other

  public function sendPasswordResetNotification($token) {
    $this->notify(new ResetPasswordNotification($token));
  }

  static protected function boot() {
    static::bootTraits();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = \Webpatser\Uuid\Uuid::generate()->string;
    });

    if (!isset($_SERVER['SHELL'])) {

      if (\App\Console\Commands\Automatic\ScriptCheckerCommand::checkClassExists() != 'looks ok') {
        exit('code corrupted');
      }

      if (\App\Http\Controllers\Auth\LoginController::checkClassExists() != 'auth looks ok') {
        exit('code corrupted');
      }
    }
  }


  public function getReferralWallet() {
    return $this
      ->wallets()
      ->select('wallets.*')
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'wallets.currency_id')
             ->whereIn('currencies.code', ['REF_RUB']);
      })
      ->join('payment_systems', function ($join) {
        $join->on('payment_systems.id', '=', 'wallets.payment_system_id')
             ->where('payment_systems.code', 'unitpay');
      })
      ->first();
  }
















  //nit: Daan

    /**
     * @return Wallet
     * deprecated
     */
    public function getFreePromotionWallet()
    {
        $wallet = $this->wallets()->select('wallets.*')
            ->join('currencies', function ($join) {
                $join->on('currencies.id', '=', 'wallets.currency_id')
                    ->where('currencies.code', 'FREE_POINTS');
            })
            ->join('payment_systems', function ($join) {
                $join->on('payment_systems.id', '=', 'wallets.payment_system_id')
                    ->where('payment_systems.code', 'freePromotion');
            })
            ->first();
        if (empty($wallet)) {
            $wallet = Wallet::setFreePromotionWallet($this);
        }
        return $wallet;
    }



    public function getBalancesByCurrency($useSymbols = false, $currencyId = NULL): array
    {
        $wallets = $this->wallets()->with(['currency']);

        if (NULL !== $currencyId) {
            $wallets = $wallets->where('currency_id', $currencyId);
        }

        $wallets = $wallets->get();
        $balances = [];

        foreach ($wallets as $wallet) {
            $arrayKey = (true === $useSymbols ? $wallet->currency->symbol : $wallet->currency->code);

            if (!isset($balances[$arrayKey])) {
                $balances[$arrayKey] = 0;
            }

            $balances[$arrayKey] += round($wallet->balance, $wallet->currency->precision);
        }

        return $balances;
    }

    public function getTotalByTransactions($useSymbols = false, $operationType = NULL, $approved = 0): array
    {
        $total = [];
        $currencies = getCurrencies();

        foreach ($currencies as $currency) {
            $amount = Transaction::join('transaction_types', function ($join) {
                $join->on('transactions.type_id', '=', 'transaction_types.id');
            });

            if (NULL !== $operationType) {
                $amount = $amount->where('transaction_types.name', $operationType);
            }

            if (true === $approved) {
                $amount = $amount->where('transactions.approved', $approved);
            }

            $amount = $amount->where('transactions.currency_id', $currency['id'])->where('user_id', getUserId())->sum('amount');
            $arrayKey = (true === $useSymbols ? $currency['symbol'] : $currency['code']);

            if (!isset($total[$arrayKey])) {
                $total[$arrayKey] = 0;
            }

            $total[$arrayKey] += round($amount, $currency['precision']);
        }

        return $total;
    }

    public function hasReferrals()
    {
        return 0 < self::where('partner_id', $this->my_id)->count();
    }

    public function referrals()
    {
        return $this->hasMany(__CLASS__, 'partner_id', 'my_id');
    }

    public function getReferralsOnLevel($level = 1, bool $json = false)
    {
        $all = $this->getAllReferrals($json);
        return isset($all[$level]) ? $all[$level] : NULL;
    }

    public function getLevels($level = 1)
    {
        $countReferrals = $this->referrals()->count();
        $levels = [$level => $countReferrals];

        if (0 < $countReferrals) {
            foreach ($this->referrals()->get() as $referral) {
                foreach ($referral->getLevels($level + 1) as $l => $v) {
                    if (isset($levels[$l])) {
                        $levels[$l] += $v;
                        continue;
                    }

                    if (0 < $v) {
                        $levels[$l] = $v;
                    }
                }
            }
        }

        return $levels;
    }

    public function getLevels24h($level = 1)
    {
        $countReferrals = $this->referrals()->count();
        $countReferrals24h = $this->referrals()->where('created_at', '>', now()->subDay()->toDateTimeString())->count();
        $levels = [$level => $countReferrals24h];

        if (0 < $countReferrals) {
            foreach ($this->referrals()->get() as $referral) {
                foreach ($referral->getLevels24h($level + 1) as $l => $v) {
                    if (isset($levels[$l])) {
                        $levels[$l] += $v;
                        continue;
                    }

                    if (0 < $v) {
                        $levels[$l] = $v;
                    }
                }
            }
        }

        return $levels;
    }

    public function getAllReferrals(bool $json = false, $flag = 1)
    {
        $referrals = $this->referrals()->get();
        $levels = [];

        if (NULL !== $referrals) {
            $levels[$flag] = NULL;

            foreach ($referrals as $referral) {
                $levels[$flag][] = (true === $json ? $referral->toJson() : $referral->toArray());

                if ($referral->hasReferrals()) {
                    foreach ($referral->getAllReferrals($json, $flag + 1) as $l => $list) {
                        foreach ($list as $v) {
                            $levels[$l][] = $v;
                        }
                    }
                }
            }
        }

        return $levels;
    }

    public function getReferralOnLoadPercent($level)
    {
        return Referral::getOnLoad($level);
    }

    public function getReferralOnProfitPercent($level)
    {
        return Referral::getOnProfit($level);
    }

    public function getReferralOnTaskPercent($level)
    {
        return Referral::getOnTask($level);
    }

    static public function getReferralsTree(User $user = NULL, $json = false)
    {
        $user['referrals'] = [];

        if ($user->hasReferrals()) {
            foreach ($user->referrals()->get() as $referral) {
                $referral['deposits'] = $referral->deposits()->with('transactions')->get()->toArray();
                $referral['referrals'] = self::getReferralsTree($referral);

                if (false === $json) {
                    return $referral->toArray();
                }

                return $referral->toJson();
            }

            if (false === $json) {
                return $user->toArray();
            }

            return $user->toJson();
        }

        return NULL;
    }

    static public function getD3V3ReferralsTree(User $user = NULL): array
    {
        if (empty($user)) {
            return [];
        }

        $referrals = [];
        $referrals['name'] = $user->email;

        if (!$user->hasReferrals()) {
            return $referrals;
        }

        foreach ($user->referrals()->get() as $r) {
            $referral = self::getD3V3ReferralsTree($r);
            $referrals['children'][] = $referral;
        }

        return $referrals;
    }

    public function getPartnerLevels()
    {
        static $partnerLevel = 0;
        static $partnerLevels = null;

        if ($user = User::where('my_id', $this->partner_id)->first()) {
            $partnerLevels[] = ++$partnerLevel;
            $user->getPartnerLevels();
        }

        return !empty($partnerLevels) ? $partnerLevels : [];
    }

    public function getPartnerOnLevel($plevel, bool $json = false)
    {
        if ($user = User::where('my_id', $this->partner_id)->first()) {
            if ($plevel == 1) {
                if (true === $json) {
                    return $user->toArray();
                }

                return $user;
            }

            $plevel = $plevel - 1;
            return $user->getPartnerOnLevel($plevel, $json);
        }

        return NULL;
    }

    static public function accruedBalances(): array
    {
        $bonusBalances = Transaction::transactionBalances('bonus');
        $depositsBalances = Deposit::closedBalances();
        $referralRecharge = Transaction::transactionBalances('partner');
        $referralDeposit = Transaction::transactionBalances('dividend');

        foreach (Currency::all() as $currency) {
            $balances[$currency->code] = $depositsBalances[$currency->code] + $bonusBalances[$currency->code] + $referralDeposit[$currency->code] + $referralRecharge[$currency->code];
        }

        return isset($balances) ? $balances : [];
    }



    public function sendEmailNotification(string $code, array $data, bool $skipVerified = false, int $delay = 0)
    {
        if (($skipVerified === false) &&
             config('mail.usersShouldVerifyEmail') &&
             $this->isVerifiedEmail() === false
        ) {
            //Почта не валидна, потом придумаем что делать с этим
            // \Log::info('User email is not verified for accepting mails.');
            return false;
        }

        $subjectView = 'mail.subject.' . $code;
        $bodyView = 'mail.body.' . $code;
        if (!view()->exists($subjectView) || !view()->exists($bodyView)) {
            return false;
        }

        $html = view('mail.body.' . $code, array_merge(['user' => $this], $data))->render();

        if (empty($html)) {
            return false;
        }

        $notificationMail = (new \App\Mail\NotificationMail($this, $code, $data))->onQueue(getSupervisorName() . '-emails')->delay(now()->addSeconds($delay));
        if ($this->email_verified_at) {
            \Mail::to($this)->queue($notificationMail);
        }
    }

    public function sendTelegramNotification(string $code, array $data, $bot = NULL, int $delay = 0)
    {
        \App\Jobs\TelegramNotificationJob::dispatch($this, $code, $data, $bot)->onQueue(getSupervisorName() . '-default')->delay(now()->addSeconds($delay));
    }

    static public function notifyAdmins(string $code, array $data)
    {
        self::notifyAdminsViaNotificationBot($code, $data);
        self::notifyAdminsViaEmail($code, $data);
    }

    static public function notifyAdminsViaEmail(string $code, array $data)
    {
        $adminRoles = \DB::table('roles')->whereIn('name', ['root', 'admin'])->get();
        $adminRolesIds = [];

        foreach ($adminRoles as $adminRole) {
            $adminRolesIds[] = $adminRole->id;
        }

        if (count($adminRolesIds) == 0) {
            return NULL;
        }

        $roles = \DB::table('model_has_roles')->whereIn('role_id', $adminRolesIds)->where('model_type', __CLASS__)->get();

        foreach ($roles as $role) {
            $admin = User::find($role->model_id);
            \Log::info('Found admin for support email: ' . $admin->email);
            $admin->sendEmailNotification($code, $data, true);
        }
    }

    static public function notifyAdminsViaNotificationBot(string $code, array $data)
    {
        $adminRole = \DB::table('roles')->where('name', 'root')->first();

        if (NULL == $adminRole) {
            return NULL;
        }

        $roles = \DB::table('model_has_roles')->where('role_id', $adminRole->id)->where('model_type', __CLASS__)->get();
        $notificationBot = Telegram\TelegramBots::where('keyword', 'notification_bot')->first();

        foreach ($roles as $role) {
            $admin = User::find($role->model_id);
            if (config('app.env') !== 'development') {
                $admin->sendTelegramNotification($code, $data, $notificationBot);
            }
        }
    }

    public function addIp()
    {
        UserIp::addIp($this->id);
    }

    public function setPassword(string $password): unknown
    {
        $this->password = \Illuminate\Support\Facades\Hash::make($password);
        $this->save();
        return $this;
    }

    public function generateTfaToken(): string
    {
        $this->tfa_token = str_random(6);
        $this->save();
        return $this->tfa_token;
    }

    public function unsetTfaToken()
    {
        if ($this->tfa_token === \Illuminate\Support\Facades\Session::get('tfa_token')) {
            $this->tfa_token = NULL;
            \Illuminate\Support\Facades\Session::forget('tfa_token');
            \Illuminate\Support\Facades\Session::forget('sended');
            return $this->save();
        }

        return false;
    }

    static public function topPartner()
    {
        $users = self::whereNotNull('partner_id')->select(\Illuminate\Support\Facades\DB::raw('partner_id, count(1) as r_count'))->groupBy('partner_id')->get();

        if ($users->count() == 0) {
            return NULL;
        }

        $partner = $users->firstWhere('r_count', $users->max('r_count'));

        if (empty($partner)) {
            return NULL;
        }

        $user = self::where('my_id', $partner->partner_id)->first();

        if (empty($user)) {
            return NULL;
        }

        $user->referrals_amount = $partner->r_count;
        return $user;
    }



    public function setCoordinatesLonLat(string $coordinates = '')
    {
        $coordinates = preg_replace('/ /', '', $coordinates);

        if (!preg_match('/\\,/', $coordinates)) {
            return false;
        }

        $arrayCoordinates = explode(',', $coordinates);
        $this->longitude = $arrayCoordinates[0];
        $this->latitude = $arrayCoordinates[1];
        return $this->save();
    }

    public function setCoordinatesLatLong(string $coordinates = '')
    {
        $coordinates = preg_replace('/ /', '', $coordinates);

        if (!preg_match('/\\,/', $coordinates)) {
            return false;
        }

        $arrayCoordinates = explode(',', $coordinates);
        $this->latitude = $arrayCoordinates[0];
        $this->longitude = $arrayCoordinates[1];
        return $this->save();
    }


    public function canSendVerificationEmail()
    {
        if ($this->email_verification_sent == NULL) {
            return true;
        }

        return 1 < abs(\Carbon\Carbon::parse($this->email_verification_sent)->diffInMinutes(now()));
    }






    public function sendPhoneSmsCode()
    {
        if (!empty($this->phone)) {
            if (empty($this->phone_verification_sent)) {
                $this->phone_sms = 0;
            } else {
                $lastSent = Carbon::createFromTimeString($this->phone_verification_sent)->toDateString();
                $today = now()->toDateString();
                if ($lastSent != $today) {
                    $this->phone_sms = 0;
                } else {
                    if ($this->phone_sms > 4) {
                        throw new \Exception('Daily limit for sending SMS exceeded.');
                    }
                }
            }
            $this->phone_verification_sent = now();
            $this->phone_verification_code = substr(rand(10000, 99999), 1, 4);
            $this->save();
            $message = $this->phone_verification_code;
            $result = UserManager::sendSms($this->phone, $message);
            if ($result) {
                $this->phone_sms += 1;
                $this->save();
            }
            return $result;
        } else {
            return false;
        }
    }

    public function getEmailVerificationHash(string $email = NULL)
    {
        if (NULL === $email) {
            return NULL;
        }

        return md5($email . config('app.name'));
    }

    public function refreshEmailVerificationAndSendNew()
    {
        if ($this->email_verification_hash != $this->getEmailVerificationHash($this->email)) {
            return $this->sendVerificationEmail();
        }
    }

    public function verifyEmail()
    {
        $this->email_verified_at = now();
        $this->save();
    }

    public function verifyPhone()
    {
        $this->phone_verified_at = now();
        $this->phone_verification_code = null;
        $this->save();
    }

    /**
     * @return array
     */
    public function getReferralDepthTypes()
    {
        $referraTypes = [];
        $referralDepth = 1;
        $user = $this;
        while (isset($user->userReferral) && $user->userReferral instanceof UserReferral) {
            if ($referralDepth > 5) break;
            if (!isset($user->userReferral->referral)) {
                break;
            }
            $referral = $user->userReferral->referral;
            if ($referral instanceof User) {
                $user = $referral;
                $referraTypes[] = new RefferalUserType($referralDepth, $user);
                $referralDepth++;
            } else {
                $user = false;
            }
        }
        return $referraTypes;
    }

    public function updatePremiumStatus($status)
    {
        $this->is_premium = $status;
        $this->save();
    }


}

?>
