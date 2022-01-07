<?php

namespace App\Providers;

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
        // 根据 env 定义的 APP_URL 来生成链接
        if (app('url')->isValidUrl(config('app.url'))) {
            app('url')->forceRootUrl(config('app.url'));
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
