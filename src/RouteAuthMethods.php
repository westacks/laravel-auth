<?php

namespace WeStacks\Laravel\Auth;

use RuntimeException;
use WeStacks\Laravel\Auth\Controllers\AuthController;

class RouteAuthMethods
{
    public function auth()
    {
        return function (array $options = []) 
        {
            $options = [
                'controller'    => $options['controller']       ?? AuthController::class,
                'middleware'    => $options['middleware']       ?? 'auth',
                'views'         => $options['views']            ?? true,

                // Enable features
                'login'         => $options['login']            ?? true,
                'logout'        => $options['logout']           ?? true,
                'register'      => $options['register']         ?? true,
                'reset'         => $options['reset']            ?? true,
                'confirm'       => $options['confirm']          ?? false,
                'verify'        => $options['verify']           ?? false,

                // Views
                'login_view'            => $options['login_view']               ?? 'auth::login',
                'register_view'         => $options['logout_view']              ?? 'auth::register',
                'reset_password_view'   => $options['reset_password_view']      ?? 'auth::password.reset',
                'forgot_password_view'  => $options['forgot_password_view']     ?? 'auth::password.forgot',
                'confirm_password_view' => $options['confirm_password_view']    ?? 'auth::password.confirm',
                'verify_view'           => $options['verify_view']              ?? 'auth::verify',
            ];

            if (!class_exists($options['controller']) || !is_subclass_of($options['controller'], AuthController::class)) {
                throw new RuntimeException("The given 'controller' is not instance of WeStacks\Laravel\Auth\Controllers\AuthController");
            }

            // Login routes
            $this->group([], function() use ($options)
            {
                if (!$options['login']) return;

                $this->post('/login', [$options['controller'], 'login'])->name('login')
                    ->middleware(['guest', 'throttle:6,1']);

                if (!$options['login_view'] || !$options['views']) return;

                $this->view('/login', $options['login_view'])
                    ->middleware('guest');
            });

            // Logout routes
            $this->group([], function() use ($options)
            {
                if (!$options['logout']) return;

                $this->post('/logout', [$options['controller'], 'logout'])->name('logout')
                    ->middleware($options['middleware']);
            });
                
            // Register routes
            $this->group([], function () use ($options)
            {
                if (!$options['register']) return;

                $this->post('/register', [$options['controller'], 'register'])->name('register')
                    ->middleware(['guest', 'throttle:6,1']);

                if (!$options['register_view'] || !$options['views']) return;

                $this->view('/register', $options['register_view'])
                    ->middleware('guest');
            });

            // Reset password routes
            $this->group([], function () use ($options)
            {
                if (!$options['reset']) return;

                $this->post('/forgot-password', [$options['controller'], 'forgotPassword'])->name('password.email')
                    ->middleware('guest');

                $this->post('/reset-password', [$options['controller'], 'resetPassword'])->name('password.reset')
                    ->middleware(['guest']);

                if ($options['forgot_password_view'] && $options['views'])
                    $this->view('/forgot-password', $options['forgot_password_view'])->name('password.request')
                        ->middleware('guest');

                if ($options['reset_password_view'] && $options['views'])
                    $this->view('/reset-password/{token}', [$options['controller'], 'resetPasswordView'])->name('password.reset')
                        ->middleware('guest');
            });

            // Password confirmation routes
            $this->group([], function () use ($options)
            {
                if (!$options['confirm']) return;
        
                $this->post('/confirm-password', [$options['controller'], 'confirmPassword'])->name('password.confirm')
                    ->middleware([$options['middleware'], 'throttle:6,1']);
        
                if (!$options['confirm_password_view'] || !$options['views']) return;
            
                $this->view('/confirm-password', $options['confirm_password_view'])
                    ->middleware($options['middleware']);
            });

            // Verify email routes
            $this->group([], function () use ($options)
            {
                if (!$options['verify']) return;
        
                $this->get('/email/verify/{id}/{hash}', [$options['controller'], 'verifyEmail'])->name('verification.verify')
                    ->middleware(['signed', $options['middleware']]);
        
                $this->post('/email/verification-notification', [$options['controller'], 'sendEmailVerification'])->name('verification.send')
                    ->middleware([$options['middleware'], 'throttle:1,1']);
        
                if (!$options['verify_view'] || !$options['views']) return;
        
                $this->view('/email/verify', $options['verify_view'])->name('verification.notice')
                    ->middleware([$options['middleware']]);
            });
        };
    }
}
