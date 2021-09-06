<?php

Route::namespace('App\Http\Controllers')->group(function () {
	Route::get('', 'Admin2Controller@index')->name('admin.dashboard');
});
