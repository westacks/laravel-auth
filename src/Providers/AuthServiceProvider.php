<?php

namespace WeStacks\Laravel\Auth\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../views', 'auth');
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/auth'),
        ], 'views');
    }
}