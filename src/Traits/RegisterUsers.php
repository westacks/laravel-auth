<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

trait RegisterUsers
{
    protected static $register          = false;
    protected static $model             = 'App\Models\User';
    protected static $register_view     = 'auth::register';
    protected static $register_redirect = '/';

    protected static function registerRoutes(Router $router)
    {
        if (!static::$register) return;

        $router->post('/register', [static::class, 'register'])->name('register')
            ->middleware(['guest', 'throttle:6,1']);

        if (!static::$register_view) return;

        $router->view('/register', static::$register_view)
            ->middleware('guest');
    }

    /**
     * Registration request handler
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(static::$model)],
            'password' => ['required', 'string', 'max:255', 'confirmed']
        ]);
        
        $user = static::$model::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $this->guard()->login($user);

        return $this->registered($request);
    }

    /**
     * Return response about successfull registration
     */
    protected function registered(Request $request)
    {
        return $request->expectsJson() ?
            response()->json(['message' => trans('You are registered successfully!')]) :
            redirect(static::$register_redirect)->with(['message' => trans('You are registered successfully!')]);
    }
}
