<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Managers\FreePromotion\InstagramManager;
use App\Http\Managers\UserManager;
use App\Models\User;
use App\Models\UserSocialProfile;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class SocialiteController
 * @package App\Http\Controllers\Auth
 */
class SocialiteController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
        $this->middleware('guest')->except('instagramAuthViaLike');
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param string $provider
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user's data from the Provider.
     *
     * @param string $provider
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws ValidationException|\Exception
     */
    public function handleProviderCallback($provider)
    {
        try {
            $providedUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            if ($provider === 'facebook' && strpos($e->getMessage(), '"Missing authorization code"') !== false) {
                return redirect('/');
            } else {
                throw $e;
            }
        }

        if (empty($providedUser->getEmail()) && !empty($providedUser->accessTokenResponseBody['email'])) {
            $email = $providedUser->accessTokenResponseBody['email'];
        } else {
            $email = $providedUser->getEmail();
        }

        if (empty($email)) {
            return redirect(route('login'))->with('error', __('The account does not have an e-mail - required field for login/registration'));
        } else {
            $user = User::where('email', $email)->first();
            if (!($user instanceof User)) {
                $user = User::create([
                    'name' => empty($providedUser->getName()) ? $email : $providedUser->getName(),
                    'email' => $email,
                    'login' => $email,
                    'password' => bcrypt(Str::random()),
                    'country' => UserManager::determineUserCountry(),
//                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user);
            if (stripos(url()->previous(), config('app.free_url')) !== false) {
                return redirect(route('freePromotion.task.tasklist'));
            } else {
                return redirect($this->redirectTo);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function instagramAuthCallback(Request $request)
    {
        try {
            $instagram = new InstagramBasicDisplay([
                'appId' => config('services.instagram.client_id'),
                'appSecret' => config('services.instagram.client_secret'),
                'redirectUri' => route('instagram.auth.callback')
            ]);

            // Get the OAuth callback code
            $code = $_GET['code'];

            // Get the short lived access token (valid for 1 hour)
            $token = $instagram->getOAuthToken($code, true);

            // Exchange this token for a long lived token (valid for 60 days)
            // $token = $instagram->getLongLivedToken($token, true);

            // Set user access token
            $instagram->setAccessToken($token);

            // Get the users profile
            $profile = $instagram->getUserProfile();

            /** @var User $user */
            $user = User::findOrFail($_GET['state']);
            $socialProfile = $user->userSocialProfile ?: new UserSocialProfile();
            $socialProfile->instagram_id = $profile->id;
            $socialProfile->instagram_username = $profile->username;
            $user->userSocialProfile()->save($socialProfile);
            return redirect(route('freePromotion.task.tasklist'));
        } catch (\Exception $e) {
            \Log::error('SocialiteController.instagramAuthCallback: ' . $e->getMessage());
            return redirect(route('freePromotion.task.tasklist'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function instagramAuthViaLike(Request $request)
    {
        try {
            $link = $request->get('username');
            if (strpos($link, 'instagram.com/') !== false) {
                $username = trim(explode('instagram.com/', $link)[1], '/');
            } elseif (strpos($link, '@') !== false) {
                $username = trim(explode('@', $link)[1], '/');
            }
            if (!empty($username)) {
                $result = InstagramManager::checkInstagramLikeAction($username, config('services.instagram.auth_like_url'));
                if ($result) {
                    /** @var User $user */
                    $user = Auth::user();
                    $socialProfile = $user->userSocialProfile ?: new UserSocialProfile();
                    $socialProfile->instagram_username = $username;
                    $user->userSocialProfile()->save($socialProfile);
                    return response()->json(['success' => true], 200);
                }
            }
            throw new \Exception('Аккаунт не подтвержден.');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
