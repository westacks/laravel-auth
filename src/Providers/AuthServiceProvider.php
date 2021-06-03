<?php

namespace WeStacks\Laravel\Auth\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use WeStacks\Laravel\Auth\AuthRoutesMethods;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'auth');
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/auth'),
        ], 'views');

        Route::mixin(new AuthRoutesMethods);
    }
}