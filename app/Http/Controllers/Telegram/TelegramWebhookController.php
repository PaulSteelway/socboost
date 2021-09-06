<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBotMessages;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramBotScopes;
use App\Models\Telegram\TelegramUsers;
use App\Models\Telegram\TelegramWebhooks;
use App\Models\User;
use App\Models\UsersSocialMeta;
use App\Modules\Messangers\TelegramModule;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class TelegramWebhookController
 * @package App\Http\Controllers
 */
class TelegramWebhookController extends Controller
{
    /** @var int $spamLimit */
    private $spamLimit = 500;

    /**
     * TelegramWebhookController constructor.
     */
    public function __construct()
    {
        $this->spamLimit = env('TELEGRAM_SPAM_LIMIT_PER_HOUR', 500);
    }

    /**
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function index(Request $request, string $token) {
        $registerRequest = $this->registerRequest($request, $token);

        if (!isset($registerRequest['eventData'])) {
            return response('ok');
        }

        /*
         * Searching commands for reaction
         */

        preg_match('/(\/)?[A-Za-z0-9-_]+/', $registerRequest['eventData']['text'], $commandRegV);
        $commandRegV = $commandRegV[0] ?? '';

        $searchingCommand = !empty($registerRequest['eventData']['text'])
            ? TelegramBotScopes::where(function($query) use($commandRegV, $registerRequest) {
                $query->where('command', 'like', $commandRegV)
                    ->orWhere('command', 'like', $registerRequest['eventData']['text']);
            })
                ->where('bot_keyword', $registerRequest['bot']->keyword)
                ->first()
            : null;

        if (null == $searchingCommand) {
            return $this->commandNotFound($registerRequest['searchingWebhook'], $registerRequest['bot'], $registerRequest['searchingTelegramUser'], $registerRequest['event'], $registerRequest['userMessage']);
        }

        return $this->commandFound($registerRequest['searchingWebhook'], $registerRequest['bot'], $searchingCommand, $registerRequest['searchingTelegramUser'], $registerRequest['event']);
    }

    /**
     * @param Request $request
     * @param string $token
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    private function registerRequest(Request $request, string $token)
    {
        /*
         * Preparing and registering data
         */

        $searchingWebhook = TelegramWebhooks::where('url', 'like', route('telegram.webhook', [
            'token' => $token
        ]))->first();

        if (null === $searchingWebhook) {
            $msg = 'webhook is not found. '.print_r($request->all(),true);
            \Log::error($msg);
            return [];
        }

        /** @var TelegramBots $bot */
        $bot = $searchingWebhook->bot()->first();

        if (null == $bot) {
            $msg = 'bot is not registered yet '.print_r($request->all(),true);
            \Log::error($msg);
            return [];
        }

        if ($request->has([
            'update_id',
            'message',
        ])) {
            $messageId  = $request->message['message_id'];
            $from       = $request->message['from'];
            $message    = $request->message;
        } elseif($request->has([
            'update_id',
            'callback_query',
        ])) {
            $messageId          = $request->callback_query['message']['message_id'];
            $from               = $request->callback_query['from'];
            $message            = $request->callback_query['message'];
            $message['text']    = $request->callback_query['data'];
        } else {
            $msg = 'required inputs not found. '.print_r($request->all(),true);
            \Log::error($msg);
            return [];
        }

        if (empty($message['text'])
            && isset($message['location']['latitude'])
            && isset($message['location']['longitude'])) {
            $message['text'] = '/set_location '.$message['location']['latitude'].','.$message['location']['longitude'];
        }

        $eventData = [
            'update_id'          => $request->update_id,
            'message_id'         => $messageId,

            'from_id'            => $from['id'],
            'from_is_bot'        => $from['is_bot'],
            'from_first_name'    => $from['first_name'] ?? null,
            'from_last_name'     => $from['last_name'] ?? null,
            'from_username'      => $from['username'] ?? null,
            'from_language_code' => $from['language_code'] ?? null,

            'chat_id'            => $message['chat']['id'],
            'chat_first_name'    => $message['chat']['first_name'] ?? null,
            'chat_last_name'     => $message['chat']['last_name'] ?? null,
            'chat_username'      => $message['chat']['username'] ?? null,
            'chat_type'          => $message['chat']['type'],

            'date'               => $message['date'],
            'text'               => $message['text'] ?? null,

            'bot_keyword'        => $bot->keyword,
            'bot_id'             => $bot->id,
            'webhook_id'         => $searchingWebhook->id,
        ];

        if ($eventData['chat_type'] != 'private') {
            exit('ok');
        }

        if ($bot->isDisabled()) {
            \Log::info('Bot is disabled, can not answer to user.');

            TelegramModule::setLanguageLocale($eventData['from_language_code']);

            $message = view('telegram.bot_is_disabled', [
                'webhook' => $searchingWebhook,
                'bot'     => $bot,
            ])->render();

            try {
                $telegramInstance = new TelegramModule($bot->keyword);
                $telegramInstance->sendMessage($eventData['chat_id'], $message, 'HTML');
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
            return [];
        }

        if (config('app.env') == 'develop') {
            \Log::info(print_r($eventData, true) . '<hr>' . print_r($request->all(), true));
        }

        if (empty($eventData)) {
            $msg = 'text is empty. '.print_r($request->all(),true);
            \Log::info($msg);
            return [];
        }

        /** @var TelegramUsers $searchingTelegramUser */
        $searchingTelegramUser = TelegramUsers::where('bot_id', $bot->id)
            ->where('telegram_user_id', $eventData['from_id'])
            ->first();

        if (null === $searchingTelegramUser) {
            $user = User::where('email', $eventData['from_id'])
                ->orWhere('login', $eventData['from_id'])
                ->first();

            if (null === $user) {
                $refId                          = null;
                $registrationWithoutRefDisabled = config('telegram.registration_without_ref_disabled');

                switch ($bot->keyword) {
                    case "admin_bot":
                        $uniqueUserKey = str_random(12);
                        $registrationWithoutRefDisabled = false;
                        break;

                    case "account_bot":
                        $uniqueUserKey = str_random(12);
                        break;

                    case "notification_bot":
                        $uniqueUserKey = str_random(12);
                        $registrationWithoutRefDisabled = false;
                        break;

                    default:
                        $uniqueUserKey = $eventData['from_id'];
                        break;
                }

                // https://t.me/HyipiumDevelopBot?start=MY_ID
                if (preg_match('/\/start ([A-Za-z0-9]+)/', $eventData['text'], $ref)) {
                    if (config('app.env') == 'develop') {
                        \Log::info('Found referral '.print_r($ref,true));
                    }

                    $refTelegramId = TelegramUsers::where('telegram_user_id', $ref[1])
                        ->first();

                    $ref = null !== $refTelegramId
                        ? $refTelegramId->user
                        : User::where('my_id', $ref[1])->first();

                    if (null !== $ref) {
                        $refId = $ref->my_id;
                    } elseif($registrationWithoutRefDisabled) {
                        if (config('app.env') == 'develop') {
                            \Log::info('Ref user not found');
                        }

                        $this->noRefRegistrationResponse($searchingWebhook, $bot, $eventData);
                        return [];
                    }
                } elseif($registrationWithoutRefDisabled) {
                    if (config('app.env') == 'develop') {
                        \Log::info('Ref link not found');
                    }

                    $this->noRefRegistrationResponse($searchingWebhook, $bot, $eventData);
                    return [];
                }

                if (config('app.env') == 'develop') {
                    \Log::info('Registering user for telegram');
                }

                $checkUserExists = User::where('email', $uniqueUserKey)
                    ->orWhere('login', $uniqueUserKey)
                    ->orWhere('my_id', $uniqueUserKey)
                    ->count();

                if ($checkUserExists > 0) {
                    \Log::info('User '.$checkUserExists.' blocked and can not be registered.');
                    return [];
                }

                $user = User::create([
                    'name'       => $uniqueUserKey,
                    'email'      => $uniqueUserKey,
                    'login'      => $uniqueUserKey,
                    'password'   => bcrypt('password'),
                    'partner_id' => $refId,
                    'my_id'      => $uniqueUserKey,
                ]);

                $socialMetas = [
                    'set_settings_notifications_main_statistics set',
                    'settings_notifications_quests animation',
                    'settings_notifications_quests business',
                    'settings_notifications_quests gadgets',
                    'settings_notifications_quests 18plus',
                    'settings_notifications_quests games',
                    'settings_notifications_quests beauty',
                    'settings_notifications_quests lifehack',
                    'settings_notifications_quests cartoons',
                    'settings_notifications_quests news',
                    'settings_notifications_quests education',
                    'settings_notifications_quests entertainment',
                    'settings_notifications_quests sport',
                    'settings_notifications_quests quotes',
                    'settings_notifications_quests art',
                    'settings_notifications_quests fashion',
                    'settings_notifications_new_referral 1_level',
                    'settings_notifications_new_referral 2_level',
                    'settings_notifications_new_referral 3_level',
                    'settings_notifications_new_referral 4_level',
                    'settings_notifications_new_referral 5_level',
                    'settings_notifications_new_referral 6_level',
                    'settings_notifications_new_referral 7_level',
                    'settings_notifications_new_referral 8_level',
                    'settings_notifications_new_referral 9_level',
                    'settings_notifications_new_referral 10_level',
                    'set_settings_notifications_personal_statistics set',
                    'settings_notifications_referral 1_level',
                    'settings_notifications_referral 2_level',
                    'settings_notifications_referral 3_level',
                    'settings_notifications_referral 4_level',
                    'settings_notifications_referral 5_level',
                    'settings_notifications_referral 6_level',
                    'settings_notifications_referral 7_level',
                    'settings_notifications_referral 8_level',
                    'settings_notifications_referral 9_level',
                    'settings_notifications_referral 10_level',
                    'set_settings_notifications_rewards set',
                ];

                foreach ($socialMetas as $meta) {
                    UsersSocialMeta::create([
                        'user_id'   => $user->id,
                        's_key'     => $meta,
                        's_value'   => 1,
                    ]);
                }
            }

            $searchingTelegramUser = TelegramUsers::create([
                'user_id'          => $user->id,
                'telegram_user_id' => $eventData['from_id'],
                'bot_id'           => $bot->id,
                'username'         => $eventData['from_username'],
                'chat_id'          => $eventData['chat_id'],
                'language'         => $eventData['from_language_code'],
            ]);
        }

        if ($searchingTelegramUser->username != $eventData['from_username']) {
            $searchingTelegramUser->username = $eventData['from_username'];
            $searchingTelegramUser->save();
        }

        if ($searchingTelegramUser->chat_id != $eventData['chat_id']) {
            $searchingTelegramUser->chat_id = $eventData['chat_id'];
            $searchingTelegramUser->save();
        }

        if ($searchingTelegramUser->language != $eventData['from_language_code']) {
            $searchingTelegramUser->language = $eventData['from_language_code'];
            $searchingTelegramUser->save();
        }

        $checkSpam = TelegramBotEvents::where('from_id', $eventData['from_id'])
            ->where('created_at', 'like', Carbon::now()->format('Y-m-d H').'%')
            ->count();

        if ($checkSpam > $this->spamLimit) {
            \Log::info('Spam limit for chat ID '.$eventData['chat_id'].'. This user sent '.$checkSpam.' messages. Limit '.$this->spamLimit);

            TelegramModule::setLanguageLocale($eventData['from_language_code']);

            $message = view('telegram.spam_limit', [
                'webhook'      => $searchingWebhook,
                'bot'          => $bot,
                'telegramUser' => $searchingTelegramUser,
            ])->render();

            try {
                $telegramInstance = new TelegramModule($bot->keyword);
                $telegramInstance->sendMessage($searchingTelegramUser->chat_id, $message, 'HTML');
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
            return [];
        }

        $event       = TelegramBotEvents::create($eventData);
        $userMessage = TelegramBotMessages::create([
            'receive' => 'bot',
            'sender'  => $event->chat_id,
            'bot_id'  => $bot->id,
            'message' => $event->text,
        ]);

        if (!empty($eventData['from_language_code'])) {
            TelegramModule::setLanguageLocale($eventData['from_language_code']);
        }

        if ($searchingTelegramUser->isBlocked()) {
            $searchingTelegramUser->blocked_user = 0;
            $searchingTelegramUser->save();
        }

        return [
            'eventData'             => $eventData,
            'searchingWebhook'      => $searchingWebhook,
            'bot'                   => $bot,
            'searchingTelegramUser' => $searchingTelegramUser,
            'event'                 => $event,
            'userMessage'           => $userMessage,
        ];
    }

    /**
     * @param TelegramWebhooks $webhook
     * @param TelegramBots $bot
     * @param array $event
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function noRefRegistrationResponse(TelegramWebhooks $webhook,
                                              TelegramBots $bot,
                                              array $event)
    {
        if (config('app.env') == 'develop') {
            \Log::info('Calling method '.TelegramNoRefRegistrationResponseController::class.'@index');
        }

        app()->call(TelegramNoRefRegistrationResponseController::class.'@index', [
            'webhook'      => $webhook,
            'bot'          => $bot,
            'event'        => $event,
        ]);

        return response('ok');
    }

    /**
     * @param TelegramWebhooks $webhook
     * @param TelegramBots $bot
     * @param TelegramBotScopes $scope
     * @param TelegramUsers $telegramUser
     * @param TelegramBotEvents $event
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    private function commandFound(TelegramWebhooks $webhook,
                                  TelegramBots $bot,
                                  TelegramBotScopes $scope,
                                  TelegramUsers $telegramUser,
                                  TelegramBotEvents $event)
    {
        $method = '\App\Http\Controllers\Telegram\\'.$scope->method_address.'@index';

        if (config('app.env') == 'develop') {
            \Log::info('Calling method '.$method);
        }

        if ($telegramUser->username != $event['from_username']) {
            $telegramUser->username = $event['from_username'];
            $telegramUser->save();
        }

        app()->call($method, [
            'webhook'      => $webhook,
            'bot'          => $bot,
            'scope'        => $scope,
            'telegramUser' => $telegramUser,
            'event'        => $event,
        ]);
        return response('ok');
    }

    /**
     * @param TelegramWebhooks $webhook
     * @param TelegramBots $bot
     * @param TelegramUsers $telegramUser
     * @param TelegramBotEvents $event
     * @param TelegramBotMessages $message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    private function commandNotFound(TelegramWebhooks $webhook,
                                     TelegramBots $bot,
                                     TelegramUsers $telegramUser,
                                     TelegramBotEvents $event,
                                     TelegramBotMessages $message)
    {
        /*
         * Looking for last request from the bot.
         */
        $lastRequestFromBot = TelegramBotMessages::with('scope')
            ->where('receive', $event->chat_id)
            ->where('sender', 'bot')
            ->where('bot_id', $bot->id)
            ->whereNotNull('scope_id')
            ->where('scope_is_closed', 0)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();
        /** @var TelegramBotScopes $lastRequestScope */
        $lastRequestScope = $lastRequestFromBot->scope ?? null;

        if (null === $lastRequestFromBot || null === $lastRequestScope) {
            if (config('app.env') == 'develop') {
                \Log::info('Calling method '.TelegramCommandNotFoundController::class.'@index');
            }

            app()->call(TelegramCommandNotFoundController::class.'@index', [
                'webhook'      => $webhook,
                'bot'          => $bot,
                'telegramUser' => $telegramUser,
                'event'        => $event,
            ]);

            return response('ok');
        }

        /*
         * Checking how long time ago request was sent
         */
        $lastRequestDatetime      = Carbon::parse($lastRequestFromBot->created_at);
        $currentDatetime          = Carbon::now();
        $maximumWaitingAnswerTime = 10; // minutes

        if ($lastRequestDatetime->diffInMinutes($currentDatetime) > $maximumWaitingAnswerTime) {
            if (config('app.env') == 'develop') {
                \Log::info('Calling method '.TelegramUserTimeAnswerWastedController::class.'@index. Max wait time: '.$maximumWaitingAnswerTime.', diff '.$lastRequestDatetime->diffInMinutes($currentDatetime));
            }

            app()->call(TelegramUserTimeAnswerWastedController::class.'@index', [
                'webhook'      => $webhook,
                'bot'          => $bot,
                'telegramUser' => $telegramUser,
                'event'        => $event,
                'lastRequest'  => $lastRequestFromBot,
            ]);

            TelegramBotMessages::closeUserScopes($event, $bot);

            return response('ok');
        }

        /*
         * Searching and running checking SCOPE method.
         */
        $checkAndProcessClass = '\App\Http\Controllers\Telegram\\'.$lastRequestScope->method_address;
        $checkMethodName      = 'checkAndProcessAnswer';

        if (config('app.env') == 'develop') {
            \Log::info('Checking if method is exists: '.$checkAndProcessClass.'@'.$checkMethodName);
        }

        if (false === method_exists($checkAndProcessClass, $checkMethodName)) {
            app()->call(TelegramAnswerMethodNotExistsController::class.'@index', [
                'webhook'      => $webhook,
                'bot'          => $bot,
                'telegramUser' => $telegramUser,
                'event'        => $event,
                'lastRequest'  => $lastRequestFromBot,
            ]);
            return response('ok');
        }

        if (config('app.env') == 'develop') {
            \Log::info('Calling method '.$checkAndProcessClass.'@'.$checkMethodName);
        }

        app()->call($checkAndProcessClass.'@'.$checkMethodName, [
            'webhook'           => $webhook,
            'bot'               => $bot,
            'telegramUser'      => $telegramUser,
            'event'             => $event,
            'userMessage'       => $message,
            'botRequestMessage' => $lastRequestFromBot,
        ]);
        return response('ok');
    }
}
