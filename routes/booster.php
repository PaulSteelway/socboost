<?php
            

/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */


Route::post('/telegram_webhook/{token}', 'Telegram\TelegramWebhookController@index')->name('telegram.webhook');

$boosterRoutes = function() {
    Route::group(['middleware' => ['web', 'email.verification']], function () {


        Route::group(['middleware' => ['site.status']], function () {
            Route::get('/quickorder', 'QuickOrderController@index')->name('quickOrder.main');
            Route::get('/quickorder/{id}', 'QuickOrderController@show')->name('quickOrder.show');
            // Not authorized
            Route::get('/', 'Customer\MainController@index')->name('customer.main');
            Route::get('/about', 'Customer\MainController@about')->name('customer.about');
            Route::get('/voucher', 'Customer\MainController@voucher')->name('customer.voucher');
            Route::post('/voucher', 'Customer\MainController@voucherPurchase')->name('customer.voucher.purchase');
            Route::get('/video-review', 'Customer\ReviewsController@videoReview')->name('customer.videoReview');
            Route::get('/affilate-program', 'Customer\MainController@affilateProgram')->name('customer.affilate');
            Route::get('/app', 'Customer\MainController@app')->name('customer.app');
            Route::get('/subscription-system', 'Customer\MainController@subscriptionSystem')->name('customer.subscription');

            Route::get('/readyaccount', 'Customer\MainController@readyAccount')->name('customer.readyAccount');
            Route::get('/api', 'API\V1APIController@index')->name('api.index');

            Route::get('/readyaccount/{category}', 'Customer\MainController@readyAccount2')->name('customer.readyAccount2');
            Route::get('/premium_account', 'Customer\MainController@premiumAccount')->name('customer.premiumAccount');
            Route::get('/prices', 'Customer\MainController@prices')->name('customer.prices');
            Route::post('/prices', 'Customer\MainController@pricesSearch')->name('customer.prices-search');
            Route::get('/public_offer', 'Customer\MainController@publicOffer')->name('customer.public_offer');
            Route::get('/user_referal_page', 'Customer\MainController@user_referal_page');
            Route::get('/privacy_policy', 'Customer\MainController@privacy_policy')->name('customer.public_policy');
            Route::get('/refund_policy', 'Customer\MainController@refund_policy')->name('customer.refund_policy');

            Route::get('/blog', 'Customer\MainController@blog')->name('customer.blog');

            Route::get('/disableModal', 'Customer\MainController@disableModal');
            /*Course block*/
            Route::post('/process_course_payment', 'Customer\CoursesController@payment');
            Route::get('/courses', 'Customer\CoursesController@index')->name('customer.courses');
            Route::get('/courses/tiktok', 'Customer\CoursesController@tiktok')->name('customer.courses.tiktok');
            Route::get('/courses/instagram', 'Customer\CoursesController@instagram')->name('customer.courses.instagram');
            Route::get('/courses/telegram', 'Customer\CoursesController@telegram')->name('customer.courses.telegram');
            Route::get('/courses/youtube', 'Customer\CoursesController@youtube')->name('customer.courses.youtube');
            Route::get('/lending/tiktok', 'Customer\MainController@tiktok');
            Route::get('/lending/instagram', 'Customer\MainController@instagram');
            Route::get('/lending/telegram', 'Customer\MainController@telegram');
            Route::get('/lending/youtube', 'Customer\MainController@youtube');
            /*End Course block*/

            Route::get('/blog_entry/{blog_entry}', 'Customer\MainController@blog_internal')->name('customer.blog_internal');

            Route::get('/c/{category}', 'Customer\Order\CategoryController@index')->name('order.category');
            Route::get('/price_convert', 'Customer\Order\CategoryController@convert');
            Route::get('/user_agreement', 'Customer\UserAgreemenControllert@index')->name('user_agreement');
            Route::get('/guarantees', 'Customer\GuaranteesController@index')->name('guarantees');
            Route::get('/discount', 'Profile\DiscountController@index')->name('discount');
            #Route::get('/discounts', 'Profile\DiscountController@index')->name('discounts');

            Route::get('/faq', 'Customer\FaqController@index')->name('faq');
            Route::get('/knowledges', 'Customer\KnowledgeController@index')->name('knowledges');
            Route::get('/contacts', 'Customer\ContactsController@index')->name('contacts');
            Route::get('/reviews', 'Customer\ReviewsController@index')->name('reviews');

            Route::get('/support', 'Customer\SupportController@index')->name('customer.support');
            Route::post('/support', 'Customer\SupportController@send');




        });

        Route::group(['middleware' => ['auth']], function () {
            Route::group(['middleware' => ['site.status']], function () {
                Route::post('/referral_payout', 'Customer\ReferralController@payout')->name('customer.referral_payout');
                Route::get('/referral_page', 'Customer\MainController@referal_page');
                Route::get('/my_profile_internal', 'Customer\MainController@my_profile_internal')->name('customer.my_profile_internal');
                Route::any('/my_profile_internal/process_avatar', 'Profile\ProfileController@save_avatar');
                Route::get('/impersonate/leave', 'Admin\ImpersonateController@leave')->name('admin.impersonate.leave');

                Route::any('/add_order/{service}', 'Profile\AddOrderController@index')->name('add_order');
                Route::any('/order_added', 'Profile\OrderAddedController@index')->name('profile.order_added');

                Route::get('/check_balance', 'Profile\AddOrderController@check_balance')->name('check_balance');

                Route::get('/reftree', 'Technical\ReftreeController@show')->name('users.reftree');

                Route::get('/profile', 'Profile\SettingsController@index')->name('profile.profile');

                Route::get('/operations', 'Profile\OperationsController@index')->name('profile.operations.index');
                Route::get('/operations_dataTable/{type?}', 'Profile\OperationsController@datatable')->name('profile.operations.dataTable');

                Route::get('/subscriptions', 'Profile\SubscriptionsController@index')->name('profile.subscriptions.index');
                Route::get('/subscriptions/{id}', 'Profile\SubscriptionsController@show')->name('profile.subscriptions.show');
                Route::post('/subscriptions/{id}', 'Profile\SubscriptionsController@update')->name('profile.subscriptions.update');
                Route::delete('/subscriptions/{id?}', 'Profile\SubscriptionsController@close')->name('profile.subscriptions.close');

                Route::get('/affiliate', 'Profile\AffiliateController@index')->name('profile.affiliate');
                Route::get('/promo', 'Profile\PromoController@index')->name('profile.promo');


                Route::get('/settings', 'Customer\MainController@my_profile_internal')->name('profile.settings');
                Route::post('/settings', 'Profile\SettingsController@handle');
                Route::post('/profilePremiumPurchase', 'Profile\AddOrderController@premiumPurchase')->name('profile.premium.purchase');
                Route::get('/generate_referral_link', 'Profile\SettingsController@generateReferralLink')->name('profile.generate');
                Route::get('phone/verification', 'Auth\VerificationController@sendPhoneVerificationCode')->name('verification.phone.send');
                Route::post('phone/verification', 'Auth\VerificationController@verifyPhone')->name('verification.phone');
                Route::post('phone/change', 'Auth\VerificationController@changePhone')->name('verification.phone.change');

                Route::get('/withdraw', 'Profile\WithdrawController@index')->name('profile.withdraw');
                Route::post('/withdraw', 'Profile\WithdrawController@handle');


                Route::resource('/deposits', 'Profile\DepositsController', ['names' => [
                    'index' => 'profile.deposits',
                    'create' => 'profile.deposits.create',
                    'edit' => 'profile.deposits.edit',
                    'store' => 'profile.deposits.store',
                ]]);
                Route::get('/deposits_datatable/{active?}', 'Profile\DepositsController@dataTable')->name('profile.deposits.dataTable');

                Route::get('/reviews/form', 'Customer\ReviewsController@form')->name('reviews.form');
                Route::post('/reviews', 'Customer\ReviewsController@add')->name('reviews.add');

                Route::get('tickets', 'Profile\TicketsController@index')->name('profile.tickets.index');
                Route::get('tickets/create', 'Profile\TicketsController@create')->name('profile.tickets.create');
                Route::post('tickets', 'Profile\TicketsController@store')->name('profile.tickets.store');
                Route::get('tickets/{id}', 'Profile\TicketsController@show')->name('profile.tickets.show');

                Route::post('tickets/{id}', 'Profile\TicketsController@update')->name('profile.tickets.update');
            });

        });
    });
};

// Route::group(array('domain' => 'bot.socialbooster.me'), $boosterRoutes);
// Route::group(array('domain' => 'en.socialbooster.me'), $boosterRoutes);
// Route::group(array('domain' => 'en.stage.socialbooster.me'), $boosterRoutes);
// Route::group(array('domain' => 'en.lvh.me'), $boosterRoutes);
Route::group(array('domain' => env('MAIN_DOMAIN')), $boosterRoutes);
