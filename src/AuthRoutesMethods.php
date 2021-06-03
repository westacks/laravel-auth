<?php

namespace WeStacks\Laravel\Auth;

class AuthRoutesMethods
{
    public function auth()
    {
        return function (array $options = []) 
        {
            static::$app->make('router')->auth($options);
        };
    }
}