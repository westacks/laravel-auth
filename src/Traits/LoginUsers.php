<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Http\Request;

trait LoginUsers
{
    /**
     * Login request handler
     */
    public function login(Request $request)
    {
        $this->validateLoginCredentials($request);

        if ($this->guard()->attempt(
                $request->validated(),
                $request->input('remember', false)
            )) {
            $request->session()->regenerate();
            return $this->authenticated($request);
        }

        return $this->authenticationFailed($request);
    }

    /**
     * Validate login credentials
     * 
     * @param Request $request 
     * @return array 
     */
    protected function validateLoginCredentials(Request $request)
    {
        return $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string']
        ]);
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
