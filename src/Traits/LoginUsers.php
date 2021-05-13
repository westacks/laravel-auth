<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

trait LoginUsers
{
    protected static $login      = false;
    protected static $login_view = 'auth::login';

    protected static function loginRoutes(Router $router)
    {
        if (!static::$login) return;

        $router->post('/login', [static::class, 'login'])->name('login')
            ->middleware(['guest', 'throttle:6,1']);
        
        if (!static::$login_view) return;
            
        $router->view('/login', static::$login_view)
            ->middleware('guest');
    }

    /**
     * Login request handler
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string']
        ]);

        if ($this->guard()->attempt(
                $request->only(['email', 'password']),
                $request->input('remember', false)
            )) {
            $request->session()->regenerate();
            return $this->authenticated($request);
        }

        return $this->authenticationFailed($request);
    }

    /**
     * Return response about successfull login
     */
    protected function authenticated(Request $request)
    {
        return $request->wantsJson() ?
            response()->json(['message' => trans('You are successfully logged in!')]) :
            redirect()->intended();
    }

    /**
     * Return response about error during login
     */
    protected function authenticationFailed(Request $request)
    {
        return $request->wantsJson() ?
            response()->json(['message' => trans('auth.failed')], 401) :
            back()->withErrors(['email' => trans('auth.failed')])->withInput($request->all());
    }
}
