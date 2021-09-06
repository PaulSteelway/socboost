<?php

namespace App\Providers;

use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;
use SleepingOwl\Admin\Contracts\Widgets\WidgetsRegistryInterface;

use AdminSection;


class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        \App\Models\User::class => 'App\Admin\Sections\Users',
        \App\Models\Transaction::class => 'App\Admin\Sections\Transactions',
    ];

    protected $widgets = [
      \App\Admin\Widgets\NavigationUserBlock::class,
    ];

    /**
     * Register sections.
     *
     * @param \SleepingOwl\Admin\Admin $admin
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
        $this->loadViewsFrom(base_path("resources/views/admin-so"), 'admin');
        $this->registerPolicies('App\\Admin\\Policies\\');
    	//

        parent::boot($admin);

        $this->registerRoutes();
        $this->registerNavigation();

        $this->app->call([$this, 'registerViews']);


    }



    public function registerViews(WidgetsRegistryInterface $widgetsRegistry) {
        foreach ($this->widgets as $widget) {
            $widgetsRegistry->registerWidget($widget);
        }
    }


    private function registerRoutes() {
        $this->app['router']->group([
            'prefix' => config('sleeping_owl.url_prefix'),
            'namespace' => 'App\Http\Controllers\Admin2',
            'middleware' => config('sleeping_owl.middleware')],

            function ($router) {

                $router->get('/recalculate', [
                    'as' => 'admin2.recalculate.index',
                    'uses' => 'RecalculateTransactonController@index'
                ]);

                $router->post('/recalculate', 'RecalculateTransactonController@count');
                $router->post('/recalculate/cleared', 'RecalculateTransactonController@cleared');

            });
    }

    private function registerNavigation() {
    \AdminNavigation::setFromArray([
      [
        'title' => 'Пересчет',
        'icon'  => 'fas fa-calculator',
        'priority' => 1000,

        'pages' => [
          [
            'title' => 'Транзакции',
            'icon'  => 'fas fa-cubes',
            'url'   => route('admin2.recalculate.index'),
            'priority' => 100,
          ],

        ],

      ],
    ]);

  }
}
