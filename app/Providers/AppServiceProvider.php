<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
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
        Schema::defaultStringLength(191);

        // Railway terminates TLS at the edge, so the container only ever sees
        // plain HTTP; force https on generated URLs in production to avoid
        // http:// form actions (which the edge 301s, dropping POST bodies).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
