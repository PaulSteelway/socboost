<?php

namespace App\Providers;

use App\Models\Reviews;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

// use Jenssegers\Agent\Agent;
// use App\Repositories\SettingRepository;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {

        View::composer([
            'main_pages.lendings.instagram',
            'main_pages.lendings.telegram',
            'main_pages.lendings.youtube',
            'main_pages.lendings.tiktok',
            'main_pages.courses.tiktok',
            'main_pages.courses.instagram',
            'main_pages.courses.telegram',
            'main_pages.courses.youtube',
        ], function ($view) {
            $reviews = Reviews::where('status', 1)
                ->where('type_id', 2)
                ->orderBy('created_at', 'desc')
                ->paginate(9);
            $view->with('reviews', $reviews);
        });

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
