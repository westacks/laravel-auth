<?php

namespace WeStacks\Laravel\Auth\Controllers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use WeStacks\Laravel\Auth\Traits\ConfirmsPasswords;
use WeStacks\Laravel\Auth\Traits\LoginUsers;
use WeStacks\Laravel\Auth\Traits\LogoutUsers;
use WeStacks\Laravel\Auth\Traits\RegisterUsers;
use WeStacks\Laravel\Auth\Traits\ResetsPasswords;
use WeStacks\Laravel\Auth\Traits\VerifiesEmail;

class AuthController extends Controller
{
    use LoginUsers,
        LogoutUsers,
        RegisterUsers,
        ResetsPasswords,
        ConfirmsPasswords,
        VerifiesEmail;
    
    protected static $logout_redirect       = '/';
    protected static $register_redirect     = '/';
    protected static $verify_redirect       = '/';

    /**
     * Auth guard used for controller routes.
     * @return SessionGuard 
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
