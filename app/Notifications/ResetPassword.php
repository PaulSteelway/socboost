<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPassword extends ResetPasswordNotification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $count = config('auth.passwords.users.expire');
        if (!empty($notifiable->country) && !in_array($notifiable->country, config('enumerations.language_codes_ru'))) {
            $lang = 'en';
            return (new MailMessage)
                ->subject('Reset Password Notification')
                ->view('vendor.notifications.email', [
                    'lang' => $lang,
                    'introLines' => [
                        'You are receiving this email because we received a password reset request for your account.'
                    ],
                    'actionUrl' => url(config('app.url') . route('password.reset', [$this->token, 'email' => $notifiable->email], false)),
                    'actionText' => 'Reset Password',
                    'outroLines' => [
                        "This password reset link will expire in {$count} minutes.",
                        'If you did not request a password reset, no further action is required.'
                    ]
                ]);
        } else {
            $lang = 'ru';
            return (new MailMessage)
                ->subject('Уведомление о сбросе пароля')
                ->view('vendor.notifications.email', [
                    'lang' => $lang,
                    'introLines' => [
                        'Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.'
                    ],
                    'actionUrl' => url(config('app.url') . route('password.reset', [$this->token, 'email' => $notifiable->email], false)),
                    'actionText' => 'Сброс пароля',
                    'outroLines' => [
                        "Срок действия ссылки для сброса пароля истекает через {$count} минут.",
                        'Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.'
                    ]
                ]);
        }
    }
}
