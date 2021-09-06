<?php

Auth::routes();

Route::group(['middleware' => ['tfa', 'auth', 'site.status']], function () {

    Route::get('/topup', 'Profile\TopupController@index')->name('profile.topup');

    Route::get('/topup/advcash', 'Payment\AdvcashController@topUp')->name('profile.topup.advcash');
    Route::get('/topup/perfectmoney', 'Payment\PerfectMoneyController@topUp')->name('profile.topup.perfectmoney');
    Route::get('/topup/payeer', 'Payment\PayeerController@topUp')->name('profile.topup.payeer');
    Route::get('/topup/blockio', 'Payment\BlockioController@topUp')->name('profile.topup.blockio');
    Route::get('/topup/coinpayments', 'Payment\CoinpaymentsController@topUp')->name('profile.topup.coinpayments');
    Route::get('/topup/enpay', 'Payment\EnpayController@topUp')->name('profile.topup.enpay');
    Route::get('/topup/nixmoney', 'Payment\NixmoneyController@topUp')->name('profile.topup.nixmoney');
    Route::get('/topup/free-kassa', 'Payment\FreeKassaController@topUp')->name('profile.topup.free-kassa');
    Route::get('/topup/coinbase', 'Payment\CoinbaseController@topUp')->name('profile.topup.coinbase');
    Route::get('/topup/paypal', 'Payment\PayPalController@topUp')->name('profile.topup.paypal');
    Route::get('/topup/robokassa', 'Payment\RobokassaController@topUp')->name('profile.topup.robokassa');
    Route::get('/topup/voucher', 'Payment\VoucherController@index')->name('profile.topup.voucher');
    Route::post('/topup/voucher', 'Payment\VoucherController@apply');
    Route::get('/topup/payop', 'Payment\PayOpController@topUp')->name('profile.topup.payop');
    Route::get('/topup/cloudpayments', 'Payment\CloudPaymentsController@topUp')->name('profile.topup.cloudpayments');
    Route::get('/topup/qiwi', 'Payment\QIWIController@topUp')->name('profile.topup.qiwi');
    Route::get('/topup/stripe', 'Payment\StripeController@topUp')->name('profile.topup.stripe');


    Route::any('/topup/payment_message', 'Profile\TopupController@paymentMessage')->name('profile.topup.payment_message');

    Route::group(['middleware' => ['role:root|admin|moderator']], function () {
        Route::get('/t1', 'TestController@t1');
    });

    Route::prefix('admin')->namespace('Admin')->group(function () {

        // Controllers Within The "App\Http\Controllers\Admin" Namespace
        Route::group(['middleware' => ['role:root|admin|moderator']], function () {

            // Users
            Route::get('/users/reftree/{id}', 'Technical\ReftreeController@show')->name('admin.users.reftree');
            Route::get('/users/dtdata', 'UsersController@dataTable')->name('admin.users.dtdata');
            Route::get('/users/dt-transactions/{user_id}', 'UsersController@dataTableTransactions')->name('admin.users.dt-transactions');
            Route::get('/users/dt-deposits/{user_id}', 'UsersController@dataTableDeposits')->name('admin.users.dt-deposits');
            Route::get('/users/dt-wrs/{user_id}', 'UsersController@dataTableWrs')->name('admin.users.dt-wrs');
            Route::get('/users/dt-pvs/{user_id}', 'UsersController@dataTablePageViews')->name('admin.users.dt-pvs');
            Route::get('/users/dt-orders/{user_id}', 'UsersController@dataTableOrders')->name('admin.users.dt-orders');
            Route::resource('/users', 'UsersController', ['names' => [
                'index' => 'admin.users.index',
                'show' => 'admin.users.show',
                'edit' => 'admin.users.edit',
                'update' => 'admin.users.update',
                'destroy' => 'admin.users.destroy',
            ]]);
            Route::post('/users/bonus', 'UsersController@bonus')->name('admin.users.bonus');
            Route::post('/users/penalty', 'UsersController@penalty')->name('admin.users.penalty');
            Route::post('/users', 'UsersController@filter')->name('admin.users.filter');

            // Transactions
            Route::get('/transactions/dtdata', 'TransactionsController@dataTable')->name('admin.transactions.dtdata');
            Route::resource('/transactions', 'TransactionsController', ['names' => [
                'index' => 'admin.transactions.index',
                'show' => 'admin.transactions.show',
            ]]);

            // Content
            Route::resource('/news', 'NewsController', ['names' => [
                'index' => 'admin.news.index',
                'create' => 'admin.news.create',
                'store' => 'admin.news.store',
                'edit' => 'admin.news.edit',
                'update' => 'admin.news.update',
                'destroy' => 'admin.news.destroy',
            ]]);

            Route::resource('/reviews', 'ReviewsController', ['names' => [
                'index' => 'admin.reviews.index',
                'create' => 'admin.reviews.create',
                'store' => 'admin.reviews.store',
                'edit' => 'admin.reviews.edit',
                'update' => 'admin.reviews.update',
                'destroy' => 'admin.reviews.destroy',
            ]]);

            Route::resource('/faqs', 'FaqController', ['names' => [
                'index' => 'admin.faqs.index',
                'create' => 'admin.faqs.create',
                'store' => 'admin.faqs.store',
                'edit' => 'admin.faqs.edit',
                'update' => 'admin.faqs.update',
                'destroy' => 'admin.faqs.destroy',
            ]]);

            Route::resource('faqCategories', 'FaqCategoryController', ['names' => [
                'index' => 'admin.faqCategories.index',
                'create' => 'admin.faqCategories.create',
                'store' => 'admin.faqCategories.store',
                'edit' => 'admin.faqCategories.edit',
                'update' => 'admin.faqCategories.update',
                'destroy' => 'admin.faqCategories.destroy',
            ]]);

            // Email
            Route::get('/email', 'EmailController@index')->name('admin.email.index');
            Route::post('/email', 'EmailController@send_emails')->name('admin.email.send-emails');

            // Promocodes
            Route::resource('/promocodes', 'PromocodeController')->names([
                'index' => 'admin.promocodes.index',
                'show' => 'admin.promocodes.show',
                'edit' => 'admin.promocodes.edit',
                'update' => 'admin.promocodes.update',
                'destroy' => 'admin.promocodes.destroy',
                'store' => 'admin.promocodes.store',
            ]);

            // Categories
            Route::resource('categories', 'CategoryController')->names([
                'index' => 'admin.categories.index',
                'create' => 'admin.categories.create',
                'store' => 'admin.categories.store',
                'show' => 'admin.categories.show',
                'edit' => 'admin.categories.edit',
                'update' => 'admin.categories.update',
                'destroy' => 'admin.categories.destroy',
            ]);

            Route::resource('categoryAddPages', 'CategoryAddPageController')->names([
                'index' => 'admin.categoryAddPages.index',
                'create' => 'admin.categoryAddPages.create',
                'store' => 'admin.categoryAddPages.store',
                'show' => 'admin.categoryAddPages.show',
                'edit' => 'admin.categoryAddPages.edit',
                'update' => 'admin.categoryAddPages.update',
                'destroy' => 'admin.categoryAddPages.destroy',
            ]);

            // Ready accounts
            Route::resource('accountCategories', 'AccountCategoryController')->names([
                'index' => 'admin.accountCategories.index',
                'create' => 'admin.accountCategories.create',
                'store' => 'admin.accountCategories.store',
                'show' => 'admin.accountCategories.show',
                'edit' => 'admin.accountCategories.edit',
                'update' => 'admin.accountCategories.update',
                'destroy' => 'admin.accountCategories.destroy',
            ]);

            // Subscriptions
            Route::resource('subscriptions', 'SubscriptionController')->names([
                'index' => 'admin.subscriptions.index',
                'create' => 'admin.subscriptions.create',
                'store' => 'admin.subscriptions.store',
                'show' => 'admin.subscriptions.show',
                'edit' => 'admin.subscriptions.edit',
                'update' => 'admin.subscriptions.update',
                'destroy' => 'admin.subscriptions.destroy',
            ]);

            // Packets
            Route::resource('/packets', 'PacketController')->names([
                'index' => 'admin.packets.index',
                'create' => 'admin.packets.create',
                'store' => 'admin.packets.store',
                'show' => 'admin.packets.show',
                'edit' => 'admin.packets.edit',
                'update' => 'admin.packets.update',
                'destroy' => 'admin.packets.destroy',
            ]);
            Route::post('/packetsReprice', 'PacketController@reprice')->name('admin.packets.reprice');
            Route::get('/packetsReprice', 'PacketController@repricePackets')->name('admin.packets.repricePackets');

            // Vouchers
            Route::resource('/vouchers', 'VoucherController')->names([
                'index' => 'admin.vouchers.index',
                'create' => 'admin.vouchers.create',
                'store' => 'admin.vouchers.store',
                'show' => 'admin.vouchers.show',
                'edit' => 'admin.vouchers.edit',
                'update' => 'admin.vouchers.update',
                'destroy' => 'admin.vouchers.destroy',
            ]);

            // Tickets
            Route::resource('tickets', 'TicketController')->names([
                'index' => 'admin.tickets.index',
                'create' => 'admin.tickets.create',
                'store' => 'admin.tickets.store',
                'show' => 'admin.tickets.show',
                'edit' => 'admin.tickets.edit',
                'update' => 'admin.tickets.update',
                'destroy' => 'admin.tickets.destroy',
            ]);

            Route::resource('ticketMessages', 'TicketMessageController')->names([
                'index' => 'admin.ticketMessages.index',
                'create' => 'admin.ticketMessages.create',
                'store' => 'admin.ticketMessages.store',
                'show' => 'admin.ticketMessages.show',
                'edit' => 'admin.ticketMessages.edit',
                'update' => 'admin.ticketMessages.update',
                'destroy' => 'admin.ticketMessages.destroy',
            ]);

            // Packages
            Route::resource('packages', 'PackageController')->names([
                'index' => 'admin.packages.index',
                'create' => 'admin.packages.create',
                'store' => 'admin.packages.store',
                'show' => 'admin.packages.show',
                'edit' => 'admin.packages.edit',
                'update' => 'admin.packages.update',
                'destroy' => 'admin.packages.destroy',
            ]);

            Route::group(['middleware' => ['role:root|admin']], function () {
                Route::get('/', 'DashboardController@index')->name('admin');
                Route::get('/dashboard1', 'DashboardController@index1')->name('admin1');
                Route::get('/dashboard2', 'DashboardController@index2')->name('admin2');

                Route::get('/impersonate/{id}', 'ImpersonateController@impersonate')->name('admin.impersonate');

                Route::get('auth/2fa', 'TwoFactAuthController@authForm')->name('auth.form.token');
                Route::post('auth/2fa', 'TwoFactAuthController@enterToken')->name('auth.enter.token');
                Route::post('auth/2fa/send', 'TwoFactAuthController@sendToken')->name('auth.send.token');
                Route::get('auth/2fa/status', 'TwoFactAuthController@statusForm')->name('auth.tfa.form');
                Route::post('auth/2fa/status', 'TwoFactAuthController@statusUpdate')->name('auth.tfa.update');

                Route::get('/statistic', 'StatisticController@index')->name('admin.statistic');

                Route::get('/settings', 'SettingsController@index')->name('admin.settings.index');
                Route::get('/settings/switch_site_status', 'SettingsController@switchSiteStatus')->name('admin.settings.switchSiteStatus');
                Route::post('/settings/change-many', 'SettingsController@changeMany')->name('admin.settings.change-many');

                Route::get('/deposits/block/{deposit}', 'DepositController@block')->name('admin.deposits.block');
                Route::get('/deposits/unblock/{deposit}', 'DepositController@unblock')->name('admin.deposits.unblock');
                Route::get('/deposits/dtdata', 'DepositController@dataTable')->name('admin.deposits.dtdata');
                Route::resource('/deposits', 'DepositController', ['names' => [
                    'index' => 'admin.deposits.index',
                    'update' => 'admin.deposits.update',
                    'destroy' => 'admin.deposits.destroy',
                    'show' => 'admin.deposits.show',
                ]]);
                Route::post('/deposits', 'DepositController@filter')->name('admin.deposits.filter');

                Route::get('/requests/approve/{id}', 'WithdrawalRequestsController@approve')->name('admin.requests.approve');
                Route::post('/requests/approve-many', 'WithdrawalRequestsController@approveMany')->name('admin.requests.approve-many');
                Route::get('/requests/reject/{id}', 'WithdrawalRequestsController@reject')->name('admin.requests.reject');
                Route::get('/requests/approveManually/{id}', 'WithdrawalRequestsController@approveManually')->name('admin.requests.approveManually');
                Route::get('/requests/dtdata', 'WithdrawalRequestsController@dataTable')->name('admin.requests.dtdata');
                Route::resource('/requests', 'WithdrawalRequestsController', ['names' => [
                    'index' => 'admin.requests.index',
                    'show' => 'admin.requests.show',
                    'edit' => 'admin.requests.edit',
                    'update' => 'admin.requests.update',
                ]]);
                Route::resource('/blogEntries', 'BlogEntryController', ['names' => [
                    'index' => 'admin.blogEntries.index',
                    'create' => 'admin.blogEntries.create',
                    'store' => 'admin.blogEntries.store',
                    'edit' => 'admin.blogEntries.edit',
                    'update' => 'admin.blogEntries.update',
                    'destroy' => 'admin.blogEntries.destroy',
                ]]);
                Route::resource('/blog_categories', 'BlogCategoryController', ['names' => [
                    'index' => 'admin.blog_categories.index',
                    'create' => 'admin.blog_categories.create',
                    'store' => 'admin.blog_categories.store',
                    'edit' => 'admin.blog_categories.edit',
                    'update' => 'admin.blog_categories.update',
                    'destroy' => 'admin.blog_categories.destroy',
                ]]);

                Route::get('/orders/{id}', 'OrdersController@edit')->name('admin.orders.edit');
                Route::put('/orders/{id}', 'OrdersController@update')->name('admin.orders.update');

                Route::resource('/langs', 'LanguagesController', ['names' => [
                    'index' => 'admin.langs.index',
                    'create' => 'admin.langs.create',
                    'store' => 'admin.langs.store',
                    'edit' => 'admin.langs.edit',
                    'update' => 'admin.langs.update',
                ]]);
                Route::get('/langs/destroy/{id}', 'LanguagesController@destroy')->name('admin.langs.destroy');

                Route::resource('/translations', 'TplTranslationsController', ['names' => [
                    'index' => 'admin.tpl_texts.index',
                    'index/{category?}' => 'admin.tpl_texts.index',
                    'create' => 'admin.tpl_texts.create',
                    'store' => 'admin.tpl_texts.store',
                    'edit' => 'admin.tpl_texts.edit',
                    'update' => 'admin.tpl_texts.update',
                    'destroy' => 'admin.tpl_texts.destroy',
                ]]);

                Route::resource('/currencies', 'CurrenciesController', ['names' => [
                    'index' => 'admin.currencies.index',
                    'edit' => 'admin.currencies.edit',
                    'update' => 'admin.currencies.update',
                ]]);
                Route::resource('/payment-systems', 'PaymentSystemsController', ['names' => [
                    'index' => 'admin.payment-systems.index',
                    'edit' => 'admin.payment-systems.edit',
                    'update' => 'admin.payment-systems.update',
                ]]);

                Route::resource('/referral', 'ReferralController', ['names' => [
                    'index' => 'admin.referral.index',
                    'create' => 'admin.referral.create',
                    'store' => 'admin.referral.store',
                    'edit' => 'admin.referral.edit',
                    'update' => 'admin.referral.update',
                ]]);
                Route::get('/referral/destroy/{id}', 'ReferralController@destroy')->name('admin.referral.destroy');

                Route::resource('/rates', 'RateController', ['names' => [
                    'index' => 'admin.rates.index',
                    'show' => 'admin.rates.show',
                    'create' => 'admin.rates.create',
                    'store' => 'admin.rates.store',
                    'edit' => 'admin.rates.edit',
                    'update' => 'admin.rates.update',
                ]]);
                Route::get('/rates/destroy/{id}', 'RateController@destroy')->name('admin.rates.destroy');

                Route::get('/social_meta', 'SocialMetaController@index')->name('admin.social_meta.index');
                Route::get('/social_meta/dtdata', 'SocialMetaController@dataTable')->name('admin.social_meta.dtdata');

                /*
                 * Telegram
                 */
                Route::resource('/telegram/bots', 'Telegram\BotsController', ['names' => [
                    'index' => 'admin.telegram.bots.index',
                    'create' => 'admin.telegram.bots.create',
                    'store' => 'admin.telegram.bots.store',
                    'edit' => 'admin.telegram.bots.edit',
                    'update' => 'admin.telegram.bots.update',
                ]]);
                Route::get('/telegram/datatable/bots/{id}/destroy', 'Telegram\BotsController@destroy')->name('admin.telegram.bots.destroy');
                Route::get('/telegram/datatable/bots', 'Telegram\BotsController@datatable')->name('admin.telegram.bots.datatable');

                Route::get('/telegram/events', 'Telegram\EventsController@index')->name('admin.telegram.events.list');
                Route::get('/telegram/datatable/events', 'Telegram\EventsController@datatable')->name('admin.telegram.events.datatable');

                Route::get('/telegram/messages', 'Telegram\MessagesController@index')->name('admin.telegram.messages.list');
                Route::get('/telegram/datatable/messages', 'Telegram\MessagesController@datatable')->name('admin.telegram.messages.datatable');

                Route::get('/telegram/users', 'Telegram\UsersController@index')->name('admin.telegram.users.list');
                Route::get('/telegram/datatable/users', 'Telegram\UsersController@datatable')->name('admin.telegram.users.datatable');

                Route::get('/telegram/webhooks', 'Telegram\WebhooksController@index')->name('admin.telegram.webhooks.list');
                Route::get('/telegram/datatable/webhooks', 'Telegram\WebhooksController@datatable')->name('admin.telegram.webhooks.datatable');

                Route::get('/telegram/webhooks_info', 'Telegram\WebhooksInfoController@index')->name('admin.telegram.webhooks_info.list');
                Route::get('/telegram/datatable/webhooks_info', 'Telegram\WebhooksInfoController@datatable')->name('admin.telegram.webhooks_info.datatable');

                /*
                 * User tasks
                 */
                Route::resource('/user-tasks/tasks', 'UserTasks\TasksController', ['names' => [
                    'index' => 'admin.user-tasks.tasks.index',
                    'create' => 'admin.user-tasks.tasks.create',
                    'store' => 'admin.user-tasks.tasks.store',
                    'edit' => 'admin.user-tasks.tasks.edit',
                    'update' => 'admin.user-tasks.tasks.update',
                ]]);
                Route::get('/user-tasks/datatable/tasks/{id}/destroy', 'UserTasks\TasksController@destroy')->name('admin.user-tasks.tasks.destroy');
                Route::get('/user-tasks/datatable/tasks', 'UserTasks\TasksController@datatable')->name('admin.user-tasks.tasks.datatable');

                Route::get('/user-tasks/accepted_tasks', 'UserTasks\AcceptedTasksController@index')->name('admin.user-tasks.accepted_tasks.list');
                Route::get('/user-tasks/datatable/accepted_tasks', 'UserTasks\AcceptedTasksController@datatable')->name('admin.user-tasks.accepted_tasks.datatable');

                Route::get('/user-tasks/available_elements', 'UserTasks\AvailableElementsController@index')->name('admin.user-tasks.available_elements.list');
                Route::get('/user-tasks/datatable/available_elements', 'UserTasks\AvailableElementsController@datatable')->name('admin.user-tasks.available_elements.datatable');

                Route::get('/user-tasks/tasks_elements', 'UserTasks\TasksElementsController@index')->name('admin.user-tasks.tasks_elements.list');
                Route::get('/user-tasks/datatable/tasks_elements', 'UserTasks\TasksElementsController@datatable')->name('admin.user-tasks.tasks_elements.datatable');

                Route::get('/user-tasks/user_task_elements', 'UserTasks\UserTaskElementsController@index')->name('admin.user-tasks.user_task_elements.list');
                Route::get('/user-tasks/datatable/user_task_elements', 'UserTasks\UserTaskElementsController@datatable')->name('admin.user-tasks.user_task_elements.datatable');
            });
        });

        Route::group(['middleware' => ['role:root']], function () {
            Route::resource('userReferrals', 'UserReferralController')->names([
                'index' => 'admin.userReferrals.index',
                'store' => 'admin.userReferrals.store',
                'show' => 'admin.userReferrals.show',
                'edit' => 'admin.userReferrals.edit',
                'update' => 'admin.userReferrals.update',
                'destroy' => 'admin.userReferrals.destroy',
            ]);
            Route::get('/userReferrals/create/{user}', 'UserReferralController@create')->name('admin.userReferrals.create');
            Route::get('/userReferrals/generate/{user}', 'UserReferralController@generate')->name('admin.userReferrals.generate');

            Route::resource('products', 'ProductsController')->names([
                'index' => 'admin.products.index',
                'create' => 'admin.products.create',
                'store' => 'admin.products.store',
                'show' => 'admin.products.show',
                'edit' => 'admin.products.edit',
                'update' => 'admin.products.update',
                'destroy' => 'admin.products.destroy',
            ]);

            Route::resource('productItems', 'ProductItemsController')->names([
                'index' => 'admin.productItems.index',
                'create' => 'admin.productItems.create',
                'store' => 'admin.productItems.store',
                'show' => 'admin.productItems.show',
                'edit' => 'admin.productItems.edit',
                'update' => 'admin.productItems.update',
                'destroy' => 'admin.productItems.destroy',
            ]);

            Route::get('/backup', 'BackupController@index')->name('admin.backup.index');
            Route::get('/backup/backupDB', 'BackupController@backupDB')->name('admin.backup.backupDB');
            Route::get('/backup/backupFiles', 'BackupController@backupFiles')->name('admin.backup.backupFiles');
            Route::get('/backup/backupAll', 'BackupController@backupAll')->name('admin.backup.backupAll');
            Route::get('/backup/destroy/{file}', 'BackupController@destroy')->where('file', '(.*(?:%2F:)?.*)')->name('admin.backup.destroy');
            Route::post('/backup/download', 'BackupController@download')->name('admin.backup.download');

            Route::get('/failedjobs', 'FailedJobsController@index')->name('admin.failedjobs.index');
            Route::get('/failedjobs/datatable', 'FailedJobsController@dataTable')->name('admin.failedjobs.datatable');

            Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
            Route::get('see_integration_example/{functionName}', 'SeeIntegrationExample@index')->name('integration-docs');

            Route::get('/sys_load', 'SysLoadController@index')->name('admin.sys_load');
        });
    });
});
Route::group(['middleware' => ['web', 'email.verification']], function () {
    Route::post('/auth/registerOrder', 'Auth\RegisterController@registerViaOrder')->name('register.order');
    Route::post('/auth/sendSms', 'Auth\RegisterController@send_sms')->name('register.sendSms');
    Route::post('/auth/checkSmsCode', 'Auth\RegisterController@checkSmsCode')->name('register.checkSmsCode');
    Route::post('auth/location', 'Auth\RegisterController@determineLocation');
    Route::get('r/{refferal_link}', 'Auth\RegisterController@registerByRefferalLink')->name('referral.route');

    Route::get('auth/{provider}', 'Auth\SocialiteController@redirectToProvider')->name('auth.socialite');
    Route::get('auth/{provider}/callback', 'Auth\SocialiteController@handleProviderCallback');
    Route::get('/instagram/auth/callback/', 'Auth\SocialiteController@instagramAuthCallback')->name('instagram.auth.callback');

    // social event catcher
    Route::get('youtube/watch/{taskAction}/{userId}', 'Technical\Youtube\YoutubeWatchCatchController@index')->name('youtube.watch');
    Route::any('youtube/watch/save/{taskAction}/{userId}', 'Technical\Youtube\YoutubeWatchCatchController@catchEvent')->name('youtube.watch.save');

    Route::group(['middleware' => ['site.status']], function () {
        Route::get('/get_promocode_details/{id}', 'Admin\PromocodeController@get_promo_details')->name('profile.promo_details');

        // Confirm email
        Route::get('/confirm_email/{hash}', 'Auth\VerificationController@verify')->name('email.confirm');
        Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
        Route::get('/get_promocode_details/{id}', 'Admin\PromocodeController@get_promo_details')->name('profile.promo_details');

        // Confirm email
        Route::get('/confirm_email/{hash}', 'Auth\VerificationController@verify')->name('email.confirm');
        Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

        // Technical
        Route::get('/partner/{partner_id}', 'SetPartnerController@index')->name('partner');
        Route::get('/lang/{locale}', 'LanguageController@index')->name('set.lang');


        // IPN
        Route::post('/advcash/status', 'Payment\AdvcashController@status')->name('advcash.status');
        Route::post('/perfectmoney/status', 'Payment\PerfectMoneyController@status')->name('perfectmoney.status');
        Route::post('/payeer/status', 'Payment\PayeerController@status')->name('payeer.status');
        Route::post('/blockio/status', 'Payment\BlockioController@status')->name('blockio.status');
        Route::post('/coinpayments/status', 'Payment\CoinpaymentsController@status')->name('coinpayments.status');
        Route::post('/enpay/status', 'Payment\EnpayController@status')->name('enpay.status');
        Route::post('/nixmoney/status', 'Payment\NixmoneyController@status')->name('nixmoney.status');
        Route::post('/free-kassa/status', 'Payment\FreeKassaController@status')->name('free-kassa.status');
        Route::post('/coinbase/status', 'Payment\CoinbaseController@status')->name('coinbase.status');
        Route::get('/coinbase/cancel', 'Payment\CoinbaseController@cancelPayment')->name('coinbase.cancel');
        Route::get('/coinbase/success', 'Payment\CoinbaseController@successPayment')->name('coinbase.success');
        Route::get('/paypal/success', 'Payment\PayPalController@successPayment')->name('paypal.success');
        Route::get('/paypal/cancel', 'Payment\PayPalController@cancelPayment')->name('paypal.cancel');
        Route::get('/unitpay/process', 'Payment\UnitpayController@processPayment')->name('unitpay.process');
        Route::get('/unitpay/success', 'Payment\UnitpayController@successPayment')->name('unitpay.success');
        Route::get('/unitpay/fail', 'Payment\UnitpayController@failPayment')->name('unitpay.fail');
        Route::get('/robokassa/process', 'Payment\RobokassaController@processPayment')->name('robokassa.process');
        Route::post('/payop/process', 'Payment\PayOpController@processPayment')->name('payop.process');
        Route::post('/cloudpayments/pay', 'Payment\CloudPaymentsController@pay')->name('cloudpayments.pay');
        Route::post('/cloudpayments/fail', 'Payment\CloudPaymentsController@fail')->name('cloudpayments.fail');
        Route::post('/cloudpayments/recurrent', 'Payment\CloudPaymentsController@recurrent')->name('cloudpayments.recurrent');
        Route::post('/qiwi/process', 'Payment\QIWIController@processPayment')->name('qiwi.process');
        Route::get('/qiwi/succeess', 'Payment\QIWIController@successPayment')->name('qiwi.success');

        Route::get('/payment/success', 'Profile\TopupController@successPayment')->name('payment.success');
        Route::get('/payment/fail', 'Profile\TopupController@failPayment')->name('payment.fail');

        Route::post('/checkout', 'Profile\AddOrderController@checkout')->name('checkout');
        Route::post('/checkoutAccountPurchase', 'Profile\AddOrderController@checkoutAccountPurchase')->name('checkoutAccountPurchase');
        Route::post('/checkoutSubscription', 'Profile\AddOrderController@checkoutSubscription')->name('checkoutSubscription');
        Route::post('/topup', 'Profile\TopupController@sendPost')->name('profile.topup.post');


        Route::get('/topup/unitpay', 'Payment\UnitpayController@topUp')->name('profile.topup.unitpay');
        Route::get('/topup/stripe', 'Payment\StripeController@topUp')->name('profile.topup.stripe');
        Route::post('/stripe/process', 'Payment\StripeController@processPayment')->name('stripe.process');
    });
});
