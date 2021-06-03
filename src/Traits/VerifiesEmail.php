<?php

namespace WeStacks\Laravel\Auth\Traits;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

trait VerifiesEmail
{
    protected static $verify_redirect = '/';

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
