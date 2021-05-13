<?php

namespace WeStacks\Laravel\Auth\Controllers;

class SanctumSpaAuthController extends AuthController
{
    protected static $model                 = 'App\Models\User';
    protected static $auth_middleware       = 'auth:sanctum';
    protected static $route_prefix          = null;

    protected static $login                 = true;
    protected static $logout                = true;
    protected static $register              = true;
    protected static $reset                 = true;
    protected static $confirm               = false;
    protected static $verify                = false;

    // We don't need any views, as all forms should be handled on frontend by SPA
    protected static $login_view            = null;
    protected static $register_view         = null;
    protected static $reset_password_view   = null;
    protected static $forgot_password_view  = null;
    protected static $confirm_password_view = null;
    protected static $verify_view           = null;
}
