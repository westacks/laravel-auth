<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

trait RegisterUsers
{
    protected static $register_redirect = '/';

    /**
     * Validate register credentials
     * 
     * @param Request $request 
     * @return array 
     */
    protected function validateRegisterCredentials(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', 'max:255', 'confirmed']
        ]);
    }

    /**
     * Register new user
     * 
     * @param Request $request 
     * @return User 
     */
    protected function registerNewUser(Request $request)
    {
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
    }

    /**
     * Registration request handler
     */
    public function register(Request $request)
    {
        $this->validateRegisterCredentials($request);
        
        $user = $this->registerNewUser($request);
        
        event(new Registered($user));

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
