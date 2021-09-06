<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Http\Managers\UserManager;
use App\Services\UserCreateService;


//nit: Daan
use App\Http\Managers\PaymentManager;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Response;

class RegisterController extends Controller
{

  use RegistersUsers;

  protected $redirectTo = '/';

  public function __construct() {
    $this->middleware('guest');
  }




  public function registerViaOrder(Request $request) {

    //реквестов и так много, упрощаем
    $this->validate($request, [
      'email' => 'required|email|max:191|unique:users'
    ], [
      'unique' => trans('auth.email_unique')
    ]);

    $password = str_random(8);
    $email = $request->email;

    $user_data = [
      'email' => $email,
      'login' => $email,
      'password' => $password,
      'country' => UserManager::determineUserCountry()
    ];

    $serv = new UserCreateService;
    $user = $serv->createUser($user_data);

    if (!$user['success']) {
      return [
        'success' => false,
        'errors' => $user['info']
      ];
    }

    $user = $user['user'];
    $this->guard()->login($user);
    $user->sendEmailAutoRegistration($password);

    $widgetData = PaymentManager::setOrderDataByUnregisteredUser($user, json_decode($request->orderData, true));

    return [
      'success' => true,
      'locale' => app()->getLocale(),
      'data' => $widgetData,
      'errors' => $user['info']
    ];


    //deprecated
    // try {
    //   // $data = $request->all();
    //   if(isset($data["just_register"]) && !empty($data["just_register"])) {
    //     $user->getFreePromotionWallet();
    //     return Response::json(['success' => true, 'locale' => app()->getLocale()], 200);
    //   } else {
    //     return Response::json(['success' => true, 'data' => $widgetData, 'locale' => app()->getLocale()], 200);
    //   }
    //
    // } catch (ValidationException $e) {
    //   return Response::json(['success' => false, 'errors' => $e->validator->errors()->getMessages()], 406);
    // } catch (\Exception $e) {
    //   return Response::json(['success' => false, 'errors' => [$e->getMessage()]], 500);
    // }
  }








  // nit: Daan




    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
//            'name' => 'required|string|max:255',
            'email' => 'required|nullable|string|email|max:191|unique:users',
//            'login' => 'string|max:30|unique:users',
            'password' => 'required|string|min:6',
            'partner_id' => 'nullable|digits:6|exists:users,my_id',
        ], [
            'email.unique' => trans('auth.email_unique'),
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ValidationException|\Exception
     */
    protected function create(array $data)
    {
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            session()->put('blocked_time', now());
            throw ValidationException::withMessages([
                $data['email'] => [trans('auth.email_invalid')],
            ]);
        }

        if (isset($_COOKIE['partner_id'])) {
            $partner_id = $_COOKIE['partner_id'];
        } elseif (isset($data['partner_id'])) {
            $partner_id = $data['partner_id'];
        } else {
            $partner_id = null;
        }

        try {
            /** @var User $user */
            $user = User::create([
//                'name' => empty($data['name']) ? (empty($data['email']) ? UserManager::getFormattedPhone($data['phone']) : $data['email']) : $data['name'],
//                'lastname' => $data['lastname'] ?? null,
                'phone' => null,
//                'telegram' => $data['telegram'] ?? null,
                'email' => $data['email'] ?? null,
                'login' => empty($data['login']) ? (empty($data['email']) ? UserManager::getFormattedPhone($data['phone']) : $data['email']) : $data['login'],
                'password' => bcrypt($data['password']),
                'country' => empty($data['country']) ? UserManager::determineUserCountry() : $data['country'],
                'partner_id' => $partner_id ?? NULL,
                'discount_modal' => false
            ]);

            $data = [
                'user' => [
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'referral_code' => $user->my_id
                ]
            ];
            $user->sendNotification('registered',$data);
        } catch (\Exception $e) {
            if (method_exists($e, 'getResponse')) {
                $response = json_decode($e->getResponse()->getBody()->getContents());
                if (strpos($response->message, "'to' parameter is not a valid address. please check documentation") !== false) {
                    $user = User::where('email', $data['email'])->first();
                    if ($user instanceof User) {
                        $user->delete();
                    }
                    throw ValidationException::withMessages([
                        $data['email'] => [trans('auth.email_invalid')],
                    ]);
                }
            }
            throw $e;
        }

        UserReferral::createUserReferralLink($user, session()->pull('register.referral.link'));

        if (!isset(request()->phone_verified) || request()->phone_verified == 0) {
            $user->sendPhoneSmsCode();
            if (!empty($user->phone)) {
                session(['phoneCodeModal' => 1]);
            }
        }

        return $user;
    }

    /**
     * @param $refferalLink
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function registerByRefferalLink($refferalLink)
    {
        $userReferral = UserReferral::where('link', $refferalLink)->first();
        if ($userReferral instanceof UserReferral) {
            $user = $userReferral->user;
            session(['register.referral.link' => $user->id]);
        }
        if(\Auth::guest()){
            return view('main_pages.user_referal_page')->with('referral_user', $userReferral->user);
        }else{
            return redirect(route('customer.affilate'));
        }
    }

    /**
     * @return mixed
     */
    public function determineLocation()
    {
        return Response::json(['country' => UserManager::determineUserCountry()], 200);
    }






    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function send_sms(Request $request)
    {
        try {
            $this->validate($request, ['phone' => 'required']);
            $code = substr(rand(10000, 99999), 1, 4);

            // disabled dev send SMS
            if (config('app.env') == 'development') {
                $result = true;
                \Log::notice('sms - ' . $code);
            } else {
                $result = UserManager::sendSms($request->phone, $code);
            }

            if ($result) {
                session()->put('sms_code', $code);
            } else {
                throw new \Exception(__('Code not sent'));
            }

            return Response::json(['success' => true, 'data' => [], 'locale' => app()->getLocale()], 200);
        } catch (ValidationException $e) {
            return Response::json(['success' => false, 'errors' => $e->validator->errors()->getMessages()], 406);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'errors' => [$e->getMessage()]], 500);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSmsCode(Request $request)
    {
        try {
            $this->validate($request, ['sms_code' => 'required']);
            if(session()->get('sms_code') == $request->sms_code){
                return Response::json(['success' => true, 'data' => [], 'locale' => app()->getLocale()], 200);
            }else{
                throw new \Exception(__('Sms code not valid'));
            }
        } catch (ValidationException $e) {
            return Response::json(['success' => false, 'errors' => $e->validator->errors()->getMessages()], 406);
        } catch (\Exception $e) {
            return Response::json(['success' => false, 'errors' => [$e->getMessage()]], 500);
        }
    }
}
