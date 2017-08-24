<?php

namespace App\Providers;

use App\Services\DateService;
use App\Services\StatsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('stats', function ($app) {
            return new StatsService(
                resolve('App\Repositories\Users'),
                resolve('App\Services\DateService')
            );
        });

        $this->app->singleton('date', function ($app) {
            return new DateService();
        });
    }
}
