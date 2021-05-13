<?php

namespace WeStacks\Laravel\Auth\Controllers;

class DefaultAuthController extends AuthController
{
    protected static $model                 = 'App\Models\User';
    protected static $auth_middleware       = 'auth';
    protected static $route_prefix          = null;

    protected static $login                 = true;
    protected static $logout                = true;
    protected static $register              = true;
    protected static $reset                 = true;
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
}
