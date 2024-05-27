<?php

namespace easyCRM\Providers;

use Carbon\Carbon;
use easyCRM\Menu;
use easyCRM\ProfileMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        Schema::defaultStringLength(250);
        Carbon::setLocale(config('app.locale'));
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS','on');
        }
    }
}
