<?php

namespace App\Providers;

use App\Admin\Widgets\DashboardMap;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use SleepingOwl\Admin\Contracts\Widgets\WidgetsRegistryInterface;

class AppServiceProvider extends ServiceProvider
{
    protected $widgets = [
        DashboardMap::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Для работы с MariaDB
        Schema::defaultStringLength(191);

        // Регистрация виджетов в реестре
        $widgetsRegistry = $this->app[WidgetsRegistryInterface::class];

        foreach ($this->widgets as $widget) {
            $widgetsRegistry->registerWidget($widget);
        }
    }
}
