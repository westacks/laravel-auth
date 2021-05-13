# Laravel Auth

<p align="left">
<a href="https://packagist.org/packages/westacks/laravel-auth"><img src="https://poser.pugx.org/westacks/laravel-auth/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/westacks/laravel-auth"><img src="https://poser.pugx.org/westacks/laravel-auth/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/westacks/laravel-auth"><img src="https://poser.pugx.org/westacks/laravel-auth/license.svg" alt="License"></a>
</p>

Laravel Auth is a collection of reusable modules to build your own laravel authentication comfortably. This package is what `laravel/ui` should have been and `laravel/fortify` have not became.

### Another Laravel authentication package?

Yes. The goal of this package is to have a built in modular Laravel authentication in one line of code and keep flexibility without configuring an additional packages.


## Installation

Only Laravel `+5.5` supported by the library. You can istall package using composer:

```bash
composer require westacks/laravel-auth
```

The package will self-register it's ServiceProvider using Laravel's auto-discovery. If you turned off auto-discovery for some reason, you need to register service provider manually in `config/app.php`:

```php
'providers' => [
    /*
     * Package Service Providers...
     */
    WeStacks\Laravel\Auth\Providers\AuthServiceProvider::class,
],
```

## Usage

If you need just basic auth, you only need to define controller routes in your `routes/web.php` file:

```php
# routes/web.php

<?php

WeStacks\Laravel\Auth\Controllers\DefaultAuthController::routes();

// Or if you building SPA

WeStacks\Laravel\Auth\Controllers\SanctumSpaAuthController::routes();
```

## Customizing

### Views

Publish all views to your `resource\views` directory using command:

```bash
php artisan vendor:publish --provider="WeStacks\Laravel\Auth\Providers\AuthServiceProvider"
```

### Back-end

If you want to customize auth controller methods, you need to extend your own controller from library's `AuthController`:

```bash
php artisan make:controller AuthController
```

```php
# app/Http/Controllers/AuthController.php

<?php

namespace App\Http\Controllers;

use WeStacks\Laravel\Auth\Controllers\AuthController as Controller;

class AuthController extends Controller
{
    // Your customs here
}

```
```php
# routes/web.php

<?php

App\Http\Controllers\AuthController::routes();

```

#### Global controller behavior

```php
# app/Http/Controllers/AuthController.php

<?php

namespace App\Http\Controllers;

use WeStacks\Laravel\Auth\Controllers\AuthController as Controller;

class AuthController extends Controller
{
    /**
     * Authenticatable model. Mainly used for registration.
     */
    protected static $model           = App\Models\User::class;

    /**
     * Middleware you are using to protect your auth routes.
     */
    protected static $auth_middleware = 'auth';

    /**
     * Prefix for all controller routes
     */
    protected static $route_prefix    = null;

    /**
     * Auth guard used for controller routes.
     */
    protected function guard()
    {
        return Auth::guard('web');
    }
}

```

#### Enabling/disabling features

```php
# app/Http/Controllers/AuthController.php

<?php

namespace App\Http\Controllers;

use WeStacks\Laravel\Auth\Controllers\AuthController as Controller;

class AuthController extends Controller
{
    protected static $login    = true;   # Login user
    protected static $logout   = true;   # Logout user
    protected static $register = true;   # Register user
    protected static $reset    = true;   # Reset user's passw 
    protected static $confirm  = false;  # Confirm password
    protected static $verify   = false;  # Verify email
}

```

#### Other options

```php
# app/Http/Controllers/AuthController.php

<?php

namespace App\Http\Controllers;

use WeStacks\Laravel\Auth\Controllers\AuthController as Controller;

class AuthController extends Controller
{
    // Views
    protected static $login_view            = 'auth::login';
    protected static $register_view         = 'auth::register';
    protected static $reset_password_view   = 'auth::password.reset';
    protected static $forgot_password_view  = 'auth::password.forgot';
    protected static $confirm_password_view = 'auth::password.confirm';
    protected static $verify_view           = 'auth::verify';
    
    // Redirect after succesfull action
    protected static $logout_redirect       = '/'; # Loged out
    protected static $register_redirect     = '/'; # Registered
    protected static $verify_redirect       = '/'; # Email verified
}

```

#### Logic

All controller logic is stored inside traits:

```php
<?php

use WeStacks\Laravel\Auth\Traits\LoginUsers;
use WeStacks\Laravel\Auth\Traits\LogoutUsers;
use WeStacks\Laravel\Auth\Traits\RegisterUsers;
use WeStacks\Laravel\Auth\Traits\ResetsPasswords;
use WeStacks\Laravel\Auth\Traits\ConfirmsPasswords;
use WeStacks\Laravel\Auth\Traits\VerifiesEmail;
```

You can easily override any logic if you building custom auth by yourself:

```php
# app/Http/Controllers/AuthController.php

<?php

namespace App\Http\Controllers;

use WeStacks\Laravel\Auth\Controllers\AuthController as Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Your custom login
    }

    public function register(Request $request)
    {
        // Your custom register
    }
}
```

## Changelog

### 1.0.0 - 2021.05.13
* Initial release