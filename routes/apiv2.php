<?php

Route::group(['middleware' => ['auth.api2']], function () {
    Route::prefix('/api/v2')->group(function () {

        //tiktok
        Route::post('/socials/add', 'API\SocialProfileController@add');
        Route::post('/socials/update', 'API\SocialProfileController@update');
        Route::post('/socials/delete', 'API\SocialProfileController@delete');
        Route::post('/socials/get', 'API\SocialProfileController@get');
        Route::post('/socials/check', 'API\SocialProfileController@check');



        Route::get('/tiktok/check-user', 'API\TikTokController@userCheck');



    });
});
