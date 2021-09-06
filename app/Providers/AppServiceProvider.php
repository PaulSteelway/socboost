<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Providers;

use App\Listeners\LoggingListener;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Faq;
use App\Models\Language;
use App\Models\MailSent;
use App\Models\News;
use App\Models\NewsLang;
use App\Models\Order;
use App\Models\PageViews;
use App\Models\PaymentSystem;
use App\Models\Rate;
use App\Models\Referral;
use App\Models\Reviews;
use App\Models\Setting;
use App\Models\Telegram\TelegramBotEvents;
use App\Models\Telegram\TelegramBotMessages;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramBotScopes;
use App\Models\Telegram\TelegramUsers;
use App\Models\Telegram\TelegramWebhooks;
use App\Models\Telegram\TelegramWebhooksInfo;
use App\Models\TplDefaultLang;
use App\Models\TplTranslation;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\UserIp;
use App\Models\UserTasks\TaskActions;
use App\Models\UserTasks\Tasks;
use App\Models\UserTasks\TaskScopes;
use App\Models\UserTasks\UserTaskActions;
use App\Models\UserTasks\UserTaskPropositions;
use App\Models\UserTasks\UserTasks;
use App\Models\Wallet;
use App\Observers\CurrencyObserver;
use App\Observers\DepositObserver;
use App\Observers\FaqObserver;
use App\Observers\LanguageObserver;
use App\Observers\MailSentObserver;
use App\Observers\NewsLangObserver;
use App\Observers\NewsObserver;
use App\Observers\OrdersObserver;
use App\Observers\PageViewsObserver;
use App\Observers\PaymentSystemObserver;
use App\Observers\RateObserver;
use App\Observers\ReferralObserver;
use App\Observers\ReviewsObserver;
use App\Observers\SettingObserver;
use App\Observers\UserTasks\TaskActionsObserver;
use App\Observers\UserTasks\TaskScopesObserver;
use App\Observers\UserTasks\TasksObserver;
use App\Observers\Telegram\TelegramBotEventsObserver;
use App\Observers\Telegram\TelegramBotMessagesObserver;
use App\Observers\Telegram\TelegramBotScopesObserver;
use App\Observers\Telegram\TelegramBotsObserver;
use App\Observers\Telegram\TelegramUsersObserver;
use App\Observers\Telegram\TelegramWebhooksInfoObserver;
use App\Observers\Telegram\TelegramWebhooksObserver;
use App\Observers\TplDefaultLangObserver;
use App\Observers\TplTranslationObserver;
use App\Observers\TransactionObserver;
use App\Observers\TransactionTypeObserver;
use App\Observers\UserIpObserver;
use App\Observers\UserObserver;
use App\Observers\UserTasks\UserTaskActionsObserver;
use App\Observers\UserTasks\UserTaskPropositionsObserver;
use App\Observers\UserTasks\UserTasksObserver;
use App\Observers\WalletObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;


use App\Models\UserSocial;
use App\Observers\UserSocialObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $unitPaySecondAcc = \App\Models\Setting::getValue('unitPaySecondAcc');
        config()->set('money.unitpay_project_id', $unitPaySecondAcc ? env('UNITPAY_PROJECT_ID2') : env('UNITPAY_PROJECT_ID'));
        config()->set('money.unitpay_public_key', $unitPaySecondAcc ? env('UNITPAY_PUBLIC_KEY2') : env('UNITPAY_PUBLIC_KEY'));
        config()->set('money.unitpay_secret_key', $unitPaySecondAcc ? env('UNITPAY_SECRET_KEY2') : env('UNITPAY_SECRET_KEY'));
        config()->set('money.unitpay_secret_key_personal', env('UNITPAY_SECRET_KEY_PERSONAL'));

        Schema::defaultStringLength(191);

        Horizon::auth(function ($request) {
            $user = \Auth::user();

            if (null === $user) {
                return false;
            }

            return $user->hasRole([
                'root',
            ]);
        });

        /*
         * Base observers
         */
        Currency::observe(CurrencyObserver::class);
        Deposit::observe(DepositObserver::class);
        Faq::observe(FaqObserver::class);
        Language::observe(LanguageObserver::class);
        MailSent::observe(MailSentObserver::class);
        NewsLang::observe(NewsLangObserver::class);
        News::observe(NewsObserver::class);
        PageViews::observe(PageViewsObserver::class);
        PaymentSystem::observe(PaymentSystemObserver::class);
        Rate::observe(RateObserver::class);
        Referral::observe(ReferralObserver::class);
        Reviews::observe(ReviewsObserver::class);
        Setting::observe(SettingObserver::class);
        TplDefaultLang::observe(TplDefaultLangObserver::class);
        TplTranslation::observe(TplTranslationObserver::class);
        Transaction::observe(TransactionObserver::class);
        TransactionType::observe(TransactionTypeObserver::class);
        UserIp::observe(UserIpObserver::class);
        User::observe(UserObserver::class);
        Wallet::observe(WalletObserver::class);

        //nit: daan add
        UserSocial::observe(UserSocialObserver::class);

        /*
         * Telegram observers
         */
        TelegramBots::observe(TelegramBotsObserver::class);
        TelegramUsers::observe(TelegramUsersObserver::class);
        TelegramWebhooks::observe(TelegramWebhooksObserver::class);
        TelegramWebhooksInfo::observe(TelegramWebhooksInfoObserver::class);
        TelegramBotScopes::observe(TelegramBotScopesObserver::class);
        TelegramBotMessages::observe(TelegramBotMessagesObserver::class);
        TelegramBotEvents::observe(TelegramBotEventsObserver::class);

        /*
         * Tasks observers
         */
        TaskActions::observe(TaskActionsObserver::class);
        TaskScopes::observe(TaskScopesObserver::class);
        Tasks::observe(TasksObserver::class);
        UserTasks::observe(UserTasksObserver::class);
        UserTaskActions::observe(UserTaskActionsObserver::class);
        UserTaskPropositions::observe(UserTaskPropositionsObserver::class);

        /*
         * Orders
         */
        Order::observe(OrdersObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
