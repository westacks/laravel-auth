<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

trait VerifiesEmail
{
    protected static $verify                = false;
    protected static $verify_view           = 'auth::verify';
    protected static $verify_redirect       = '/';
    protected static $auth_middleware       = 'auth';

    protected static function emailVerificationRoutes(Router $router)
    {
        if (!static::$verify) return;

        $router->get('/email/verify/{id}/{hash}', [static::class, 'verifyEmail'])->name('verification.verify')
            ->middleware(['signed', static::$auth_middleware]);

        $router->post('/email/verification-notification', [static::class, 'sendEmailVerification'])->name('verification.send')
            ->middleware([static::$auth_middleware, 'throttle:1,1']);

        if (!static::$verify_view) return;

        $router->view('/email/verify', static::$verify_view)->name('verification.notice')
            ->middleware([static::$auth_middleware]);
    }

    /**
     * Handle email verification request
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return $this->verified($request);
    }

    /**
     * Send new email verification notification
     */
    public function sendEmailVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return $this->verificationSent($request);
    }

    /**
     * Return response about successfull email verification
     */
    protected function verified(Request $request)
    {
        return $request->expectsJson() ?
            response()->json(['message' => trans('Your Email address successfully verified!')]) :
            redirect(static::$verify_redirect)->with(['message' => trans('Your Email address successfully verified!')]);
    }

    /**
     * Return response about successfull email verification norification
     */
    protected function verificationSent(Request $request)
    {
        return $request->expectsJson() ?
            response()->json(['message' => trans('A fresh verification link has been sent to your email address.')]) :
            back()->with(['message' => trans('A fresh verification link has been sent to your email address.')]);
    }
}
