<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules\Messangers;

use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBotMessages;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramBotScopes;
use App\Models\Telegram\TelegramUsers;
use App\Models\Telegram\TelegramWebhooks;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;

/**
 * Class TelegramModule
 * @package App\Modules\Messangers
 */
class TelegramModule
{
    /** @var TelegramBots|null $bot */
    private $bot = null;

    /** @var string $api */
    private $api = 'https://api.telegram.org/bot';

    /**
     * TelegramModule constructor.
     * @param null $keyword
     * @throws \Exception
     */
    public function __construct($keyword=null)
    {
        if (null !== $keyword && null === $this->bot) {
            $this->setBot($keyword);
        }
    }

    /**
     * @param string $keyword
     * @return TelegramBots
     * @throws \Exception
     */
    public function setBot(string $keyword) : TelegramBots
    {
        if (null !== $this->bot) {
            throw new \Exception('BOT already connected to current module.');
        }

        $searchingBot = TelegramBots::where('keyword', $keyword)->first();

        if (null == $searchingBot) {
            throw new \Exception('Telegram BOT with keyword '.$keyword.' not found.');
        }

        return $this->bot = $searchingBot;
    }

    /**
     * @param string $method
     * @param array|null $data
     * @return \stdClass
     * @throws \Exception
     */
    private function sendRequest(string $method, array $data=null)
    {
        if (null === $this->bot) {
            throw new \Exception('Set BOT token firstly.');
        }

        if (null === $data) {
            $data = [];
        }

        if (isset($data['chat_id']) && $data['chat_id'] > 0) {
            /** @var TelegramUsers $telegramUser */
            $telegramUser = TelegramUsers::where('chat_id', $data['chat_id'])
                ->first();

            if (null === $telegramUser || $telegramUser->isBlocked()) {
                throw new \Exception('Telegram user is blocked or just not found');
            }
        }

        $client   = new Client();
        $baseUrl  = $this->api.$this->bot->token.'/';
        $headers  = [
            'Content-Type' => 'application/json'
        ];
        $verify   = config('app.env') == 'production' ? true : false;
        $params   = [
            'headers' => $headers,
            'verify'  => $verify,
            'json'    => $data,
        ];

        try {
            $response = $client->post($baseUrl . $method, $params);
        } catch (\Exception $e) {
            if (preg_match('/blocked/', $e->getMessage())) {
                if (!isset($telegramUser) || null === $telegramUser) {
                    throw new \Exception('Can not block user, telegram user not found. '.print_r($data,true));
                }

                $telegramUser->blocked_user = 1;
                $telegramUser->save();

                throw new \Exception('telegram user blocked '.$telegramUser->username);
            }
            throw new \Exception('Telegram API request is failed. '.$e->getMessage());
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Telegram API response status is '.$response->getStatusCode().' for method '.$method);
        }

        $body = json_decode($response->getBody()->getContents());

        if (!isset($body->ok) || $body->ok != true || !isset($body->result)) {
            throw new \Exception('Telegram API response is not ok. '.print_r($body,true));
        }

        return $body;
    }

    /**
     * @param TelegramBots $bot
     * @param string|null $certificate
     * @param int $max_connections
     * @return TelegramWebhooks
     * @throws \Exception
     */
    public function setWebhook(TelegramBots $bot, string $certificate=null, int $max_connections=40)
    {
        $url = route('telegram.webhook', [
            'hash' => md5(str_random(50))
        ]);

        $requestData = [
            'url'             => $url,
            'max_connections' => $max_connections,
        ];

        if (null !== $certificate) {
            $requestData['certificate'] = $certificate;
        }

        try {
            $requestedData = $this->sendRequest('setWebhook', $requestData);

            if ($requestedData->result != true && $requestedData->description != "Webhook was set") {
                throw new \Exception('Webhook can not be installed. Response: '.print_r($requestedData,true));
            }

            try {
                foreach ($bot->webhooks()->get() as $webhook) {
                    $webhook->delete();
                }
                $newWebhookInfo = TelegramWebhooks::create([
                    'telegram_bot_id' => $bot->id,
                    'url'             => $url,
                    'certificate'     => $certificate,
                    'allowed_updates' => null,
                    'max_connections' => $max_connections,
                ]);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $newWebhookInfo;
    }

    /**
     * @return \stdClass
     * @throws \Exception
     */
    public function getWebhookInfo()
    {
        try {
            $response = $this->sendRequest('getWebhookInfo');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @return \stdClass
     * @throws \Exception
     */
    public function deleteWebhook()
    {
        try {
            $response = $this->sendRequest('deleteWebhook');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        foreach ($this->bot->webhooks()->get() as $webhook) {
            $webhook->delete();
        }

        return $response;
    }

    /**
     * @return \stdClass
     * @throws \Exception
     */
    public function getMe()
    {
        try {
            $response = $this->sendRequest('getMe');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $chat_id
     * @param string $text
     * @param string|null $parse_mode
     * @param bool $disable_web_page_preview
     * @param bool $disable_notification
     * @param int|null $reply_to_message_id
     * @param array|null $reply_markup
     * @param TelegramBotScopes|null $scope
     * @param string|null $extra_data
     * @return bool|\stdClass
     * @throws \Exception
     */
    public function sendMessage($chat_id,
                                string $text,
                                string $parse_mode=null,
                                bool $disable_web_page_preview=false,
                                bool $disable_notification=false,
                                int $reply_to_message_id=null,
                                array $reply_markup=null,
                                TelegramBotScopes $scope=null,
                                string $extra_data=null)
    {
        if (empty($text)) {
            return false;
        }

        $data = [
            'chat_id'                  => $chat_id,
            'text'                     => (string) $text,
            'disable_web_page_preview' => (bool) $disable_web_page_preview,
            'disable_notification'     => (bool) $disable_notification,
        ];

        if (null !== $parse_mode) {
            $data['parse_mode'] = $parse_mode;
        }

        if (null !== $reply_to_message_id) {
            $data['reply_to_message_id'] = $reply_to_message_id;
        }

        if (null !== $reply_markup) {
            $data['reply_markup'] = $reply_markup;
        }

        if (config('app.env') == 'develop') {
            \Log::info('Sending message, to '.$chat_id.', with data: '.print_r($data,true).' || JSON '.json_encode($data));
        }

        try {
            $response = $this->sendRequest('sendMessage', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (config('app.env') == 'develop') {
            \Log::info('Message was sent. Response is: '.print_r($response,true));
        }

        $botMessageData = [
            'sender'     => 'bot',
            'receive'    => $chat_id,
            'bot_id'     => $this->bot->id,
            'message'    => $text,
            'extra_data' => $extra_data,
            'message_id' => $response->result->message_id ?? null
        ];

        if (config('app.env') == 'develop') {
            \Log::info('BOT message id: '.$response->result->message_id);
        }

        if (null !== $scope) {
            $botMessageData['scope_id'] = $scope->id;
        }

        TelegramBotMessages::create($botMessageData);

        return $response;
    }

    /**
     * @param string $languageCode
     * @throws \Exception
     * @return bool
     */
    public static function setLanguageLocale(string $languageCode=null)
    {
        if (null === $languageCode) {
            return false;
        }

        $languages = getLanguagesArray();

        foreach ($languages as $language) {
            if (!preg_match('/'.$language['code'].'/', $languageCode)) {
                continue;
            }

            try {
                \App::setLocale($language['code']);
            } catch (\Exception $e) {
                throw new \Exception('Can not set locale for Telegram: '. $e->getMessage());
            }

            return true;
        }
    }

    /**
     * @param $chat_id
     * @param int $user_id
     * @param string|null $until_date
     * @return \stdClass
     * @throws \Exception
     */
    public function kickChatMember($chat_id,
                                   int $user_id,
                                   string $until_date=null)
    {
        $data = [
            'chat_id' => $chat_id,
            'user_id' => (int) $user_id,
        ];

        if (null !== $until_date) {
            $data['until_date'] = $until_date;
        }

        try {
            $response = $this->sendRequest('kickChatMember', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $chat_id
     * @param int $user_id
     * @return \stdClass
     * @throws \Exception
     */
    public function unbanChatMember($chat_id,
                                    int $user_id)
    {
        $data = [
            'chat_id' => $chat_id,
            'user_id' => (int) $user_id,
        ];

        try {
            $response = $this->sendRequest('unbanChatMember', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $chat_id
     * @param int $user_id
     * @param string|null $until_date
     * @param bool $can_send_messages
     * @param bool $can_send_media_messages
     * @param bool $can_send_other_messages
     * @param bool $can_add_web_page_previews
     * @return \stdClass
     * @throws \Exception
     */
    public function restrictChatMember($chat_id,
                                       int $user_id,
                                       string $until_date=null,
                                       bool $can_send_messages = true,
                                       bool $can_send_media_messages = true,
                                       bool $can_send_other_messages = true,
                                       bool $can_add_web_page_previews = true)
    {
        $data = [
            'chat_id'                   => $chat_id,
            'user_id'                   => (int) $user_id,
            'can_send_messages'         => $can_send_messages,
            'can_send_media_messages'   => $can_send_media_messages,
            'can_send_other_messages'   => $can_send_other_messages,
            'can_add_web_page_previews' => $can_add_web_page_previews,
        ];

        if (null !== $until_date) {
            $data['until_date'] = Carbon::parse($until_date)->toDateTimeString();
        }

        try {
            $response = $this->sendRequest('restrictChatMember', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $chat_id
     * @param string $title
     * @return \stdClass
     * @throws \Exception
     */
    public function setChatTitle($chat_id,
                                 string $title)
    {
        $data = [
            'chat_id' => $chat_id,
            'title'   => $title,
        ];

        try {
            $response = $this->sendRequest('setChatTitle', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $chat_id
     * @param string $description
     * @return \stdClass
     * @throws \Exception
     */
    public function setChatDescription($chat_id,
                                       string $description)
    {
        $data = [
            'chat_id'     => $chat_id,
            'description' => $description,
        ];

        try {
            $response = $this->sendRequest('setChatDescription', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $chat_id
     * @return \stdClass
     * @throws \Exception
     */
    public function leaveChat($chat_id)
    {
        $data = [
            'chat_id'     => $chat_id,
        ];

        try {
            $response = $this->sendRequest('leaveChat', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param integer $chat_id
     * @param integer $message_id
     * @return \stdClass
     * @throws \Exception
     */
    public function deleteMessage($chat_id, $message_id)
    {
        $data = [
            'chat_id'       => $chat_id,
            'message_id'    => $message_id,
        ];

        try {
            $response = $this->sendRequest('deleteMessage', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param string|integer $chat_id
     * @param integer $message_id
     * @param string $text
     * @param array|null $reply_markup
     * @param string|null $parse_mode
     * @param string|null $inline_message_id
     * @param boolean|null $disable_web_page_preview
     * @return \stdClass
     * @throws \Exception
     */
    public function editMessageText($chat_id,
                                    $message_id,
                                    $text,
                                    $reply_markup=null,
                                    $parse_mode=null,
                                    $inline_message_id=null,
                                    $disable_web_page_preview=null)
    {
        $data = [
            'chat_id'       => $chat_id,
            'message_id'    => $message_id,
            'text'          => $text,
        ];

        if (null !== $reply_markup) {
            $data['reply_markup'] = $reply_markup;
        }

        if (null !== $parse_mode) {
            $data['parse_mode'] = $parse_mode;
        }

        if (null !== $inline_message_id) {
            $data['inline_message_id'] = $inline_message_id;
        }

        if (null !== $disable_web_page_preview) {
            $data['disable_web_page_preview'] = $disable_web_page_preview;
        }

        try {
            $response = $this->sendRequest('editMessageText', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $chat_id
     * @param $user_id
     * @return \stdClass
     * @throws \Exception
     */
    public function getChatMember($chat_id,
                                  $user_id)
    {
        $data = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ];

        try {
            $response = $this->sendRequest('getChatMember', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $chat_id
     * @param $reply_markup
     * @param null $inline_message_id
     * @param null $message_id
     * @return \stdClass
     * @throws \Exception
     */
    public function editMessageReplyMarkup($chat_id,
                                            $reply_markup,
                                            $message_id=null,
                                            $inline_message_id=null)
    {
        $data = [
            'chat_id'       => $chat_id,
            'reply_markup'  => $reply_markup,
        ];

        if (null !== $inline_message_id) {
            $data['inline_message_id'] = $inline_message_id;
        }

        if (null !== $message_id) {
            $data['message_id'] = $message_id;
        }

        if (null !== $inline_message_id) {
            $data['inline_message_id'] = $inline_message_id;
        }

        try {
            $response = $this->sendRequest('editMessageReplyMarkup', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    /*
     * Checking task results
     */

    /**
     * @param User $user
     * @param string $objectUrl
     * @return bool
     * @throws \Exception
     */
    public static function channelSubscription(User $user, string $objectUrl)
    {
        /** @var TelegramUsers $telegramUser */
        $telegramUser = $user->telegramUser()->first();

        if (null === $telegramUser) {
            \Log::info('channelSubscription - Telegram user not found');
            return false;
        }

        if (!preg_match('/\@/', $objectUrl)) {
            \Log::info('channelSubscription - @ not found in resource');
            return false;
        }

        /** @var TelegramBots $bot */
        $bot = $telegramUser->bot()->first();

        if (null === $bot) {
            \Log::info('channelSubscription - Bot not found');
            return false;
        }

        try {
            $telegramInstance = new TelegramModule($bot->keyword);
            $chatMember = $telegramInstance->getChatMember($objectUrl, $telegramUser->telegram_user_id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        \Log::info('channelSubscription - Got response '.print_r($chatMember,true));

        if (isset($chatMember->result->user) && isset($chatMember->result->status) &&
            ($chatMember->result->status != 'restricted' && $chatMember->result->status != 'left' && $chatMember->result->status != 'kicked')) {
            return true;
        }

        return false;
    }
}