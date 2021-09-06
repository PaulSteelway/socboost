<?php

Route::post('/telegram_webhook/{token}', 'Telegram\TelegramWebhookController@index')->name('telegram.webhook');

$boosterRoutes = function () {
    Route::get('/', 'FreePromotion\MainController@index');

    Route::group(['middleware' => ['auth']], function () {
        Route::namespace('FreePromotion')->group(function () {
            Route::get('tasks-list/', 'TaskController@tasklist')->name('freePromotion.task.tasklist');
            Route::post('hide_task/', 'TaskController@hideTask')->name('freePromotion.hide_task');
            Route::get('tasks/get_by_service', 'TaskController@get_by_service')->name('freePromotion.get_by_service');
            Route::get('tasks/get_total_rewards', 'TaskController@get_total_rewards')->name('freePromotion.get_total_rewards');
            Route::get('tasks/check_task_validity', 'TaskController@check_task_validity')->name('freePromotion.check_task_validity');
            Route::get('tasks/create', 'TaskController@create')->name('freePromotion.task.create');
            Route::post('tasks/create', 'TaskController@store')->name('freePromotion.task.store');
            Route::post('tasks/verificationImplementation', 'TaskController@verificationImplementation')->name('freePromotion.verification_implementation');
            Route::get('blacklist', 'BlacklistController@index')->name('freePromotion.black_list');
            Route::post('blacklist', 'BlacklistController@addToBlacklist')->name('freePromotion.add_to_blacklist');
        });
        Route::post('authInstagramViaLike', 'Auth\SocialiteController@instagramAuthViaLike')->name('freePromotion.auth.instagramViaLike');
    });
};

Route::group(array('domain' => config('app.free_url')), $boosterRoutes);
//Route::group(array('domain' => 'free.socialbooster.local'), $boosterRoutes);
//Route::group(array('domain' => 'free.lvh.me'), $boosterRoutes);
