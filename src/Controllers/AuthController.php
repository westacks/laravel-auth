<?php

namespace WeStacks\Laravel\Auth\Controllers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use WeStacks\Laravel\Auth\Traits\ConfirmsPasswords;
use WeStacks\Laravel\Auth\Traits\LoginUsers;
use WeStacks\Laravel\Auth\Traits\LogoutUsers;
use WeStacks\Laravel\Auth\Traits\RegisterUsers;
use WeStacks\Laravel\Auth\Traits\ResetsPasswords;
use WeStacks\Laravel\Auth\Traits\VerifiesEmail;

abstract class AuthController extends Controller
{
    use LoginUsers,
        LogoutUsers,
        RegisterUsers,
        ResetsPasswords,
        ConfirmsPasswords,
        VerifiesEmail;

    protected static $model                 = 'App\Models\User';
    protected static $auth_middleware       = 'auth';
    protected static $route_prefix          = null;

    protected static $login                 = false;
    protected static $logout                = false;
    protected static $register              = false;
    protected static $reset                 = false;
    protected static $confirm               = false;
    protected static $verify                = false;

    protected static $login_view            = 'auth::login';
    protected static $register_view         = 'auth::register';
    protected static $reset_password_view   = 'auth::password.reset';
    protected static $forgot_password_view  = 'auth::password.forgot';
    protected static $confirm_password_view = 'auth::password.confirm';
    protected static $verify_view           = 'auth::verify';
    
    protected static $logout_redirect       = '/';
    protected static $register_redirect     = '/';
    protected static $verify_redirect       = '/';

    /**
     * Auth guard used for controller routes.
     * @return SessionGuard 
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * Register controller routes
     */
    public static function routes() {
        Route::prefix(static::$route_prefix ?: '')
            ->name(static::$route_prefix ? static::$route_prefix.'.' : '')
            ->group(function(Router $router) {
                static::loginRoutes($router);
                static::logoutRoutes($router);
                static::registerRoutes($router);
                static::passwordResetRoutes($router);
                static::passwordConfirmRoutes($router);
                static::emailVerificationRoutes($router);
            });
    }
}
