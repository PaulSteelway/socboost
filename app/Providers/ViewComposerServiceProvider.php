<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\Factory;
use View;

use App\Http\ViewComposers\SettingComposer;
use App\Http\ViewComposers\UserComposer;
use App\Http\ViewComposers\WalletComposer;
use App\Http\ViewComposers\CategoryComposer;
use App\Http\ViewComposers\FreeSocialProfileComposer;


class ViewComposerServiceProvider extends ServiceProvider
{

  private $views;

  public function boot(Factory $viewFactory) {

    $this->views = $viewFactory;

    //пока не используется
    $this->compose('*', SettingComposer::class);
    // $this->compose('*', UserComposer::class);

    //freeSocialProfiles
    $this->compose('layouts.free_promotion.header', FreeSocialProfileComposer::class);

    //already refactor
    $this->compose([
      'layouts.free_promotion.header',
      'layouts.header.usermenu',
      'main_pages.my_profile_internal',
    ], WalletComposer::class);

    $this->compose([
      'layouts.header',
      'customer.main.our-service',

      //deprecated
      //test view
      'partials.menu'
    ], CategoryComposer::class);




  }


  private function compose($views, string $viewComposer){
    $this->app->singleton($viewComposer);
    $this->views->composer($views, $viewComposer);
  }

}
