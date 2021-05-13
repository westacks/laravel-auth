<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

trait ResetsPasswords
{
    protected static $reset                 = false;
    protected static $reset_password_view   = 'auth::password.reset';
    protected static $forgot_password_view  = 'auth::password.forgot';

    protected static function passwordResetRoutes(Router $router)
    {
        if (!static::$reset) return;

        $router->post('/forgot-password', [static::class, 'forgotPassword'])->name('password.email')
            ->middleware('guest');

        $router->post('/reset-password', [static::class, 'resetPassword'])->name('password.update')
            ->middleware(['guest']);

        if (static::$forgot_password_view)
            $router->view('/forgot-password', static::$forgot_password_view)->name('password.request')
                ->middleware('guest');

        if (static::$reset_password_view)
            $router->get('/reset-password/{token}', [static::class, 'resetPasswordView'])->name('password.reset')
                ->middleware('guest');
    }

    /**
     * Send email to reset user's password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        $code = $status == Password::RESET_LINK_SENT ? 200 : 422;
        return $this->reportPasswordResetStatus($request, $code, $status);
    }

    /**
     * Show form to reset user's password
     */
    public function resetPasswordView(string $token)
    {
        return view(static::$reset_password_view, compact('token'));
    }

    /**
     * Set new user's password
     */
    public function resetPassword(Request $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        $code = $status == Password::PASSWORD_RESET ? 200 : 422;
        return $this->reportPasswordResetStatus($request, $code, $status);
    }

    /**
     * Return password reset status response.
     */
    protected function reportPasswordResetStatus(Request $request, int $code, string $status)
    {
        if ($code == 200) {
            return $request->expectsJson() ?
                response()->json([
                    'message' => trans($status)
                ]) :
                back()->with([
                    'message' => trans($status)
                ]);
        }

        return $request->expectsJson() ?
            response()->json([
                'message' => trans($status),
                'errors' => [
                    'email' => [trans($status)]
                ]
            ], $code) :
            back()->withErrors([
                'email' => [trans($status)]
            ])->withInput($request->all());
    }
}
