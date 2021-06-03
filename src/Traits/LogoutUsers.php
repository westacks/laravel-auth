<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

trait LogoutUsers
{
    protected static $logout_redirect   = '/';

    /**
     * Logout request handler
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->unauthenticated($request);
    }

    /**
     * Return response about successfull logout
     */
    protected function unauthenticated(Request $request)
    {
        return $request->expectsJson() ?
            response()->json(['message' => trans('You are logged out!')]) :
            redirect(static::$logout_redirect)->with(['message' => trans('You are logged out!')]);
    }
}
