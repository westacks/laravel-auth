<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Hash;

trait ConfirmsPasswords
{
    /**
     * Password confirmation request handler
     */
    public function confirmPassword(Request $request)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            return $this->confirmError($request);
        }

        $request->session()->passwordConfirmed();

        return $this->confirmed($request);
    }

    /**
     * Return response about successfull password confirmation
     */
    protected function confirmed(Request $request)
    {
        return $request->expectsJson() ?
            response()->json(['message' => trans('Your password confirmed successfully!')]) :
            redirect()->intended();
    }

    /**
     * Return response about error during password confirmation
     */
    protected function confirmError(Request $request)
    {
        return $request->expectsJson() ?
            response()->json([
                'message' => trans('The provided password does not match our records.'),
                'errors' => [ 'password' => [trans('The provided password does not match our records.')] ]
            ]) :
            back()->withErrors([
                'password' => ['The provided password does not match our records.']
            ])->withInput($request->all());
    }
}
