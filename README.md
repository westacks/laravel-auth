# Laravel Auth

<p align="left">
<a href="https://packagist.org/packages/westacks/laravel-auth"><img src="https://poser.pugx.org/westacks/laravel-auth/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/westacks/laravel-auth"><img src="https://poser.pugx.org/westacks/laravel-auth/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/westacks/laravel-auth"><img src="https://poser.pugx.org/westacks/laravel-auth/license.svg" alt="License"></a>
</p>

Laravel Auth is a collection of reusable modules to build your own laravel authentication comfortably. It comfortably integrates into any Laravel application with 1 line of code. This package is what `laravel/ui` should have been and `laravel/fortify` have not became.

### Another Laravel authentication package?

Yes. The goal of this package is to have a built in modular Laravel authentication and keep flexibility without configuring an additional packages.


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

use Illuminate\Support\Facades\Route;

Route::auth();
```

## Customizing

### Views

Publish all views to your `resource\views` directory using command:

```bash
php artisan vendor:publish --provider="WeStacks\Laravel\Auth\Providers\AuthServiceProvider"
```

If you want completely remove auth views from application, just initialize routes without them:
```php
# routes/web.php

<?php

use Illuminate\Support\Facades\Route;

Route::auth([
    'views' => false
]);
```
You may define custom pathes to your views using this config to:
```php
# routes/web.php

<?php

use Illuminate\Support\Facades\Route;

Route::auth([
    'login_view'            => 'auth::login',
    'register_view'         => 'auth::register',
    'reset_password_view'   => 'auth::password.reset',
    'forgot_password_view'  => 'auth::password.forgot',
    'confirm_password_view' => 'auth::password.confirm',
    'verify_view'           => 'auth::verify',
]);
```
### Enabling/disabling features

Just define only features you want to use and you are ready to go:

```php
# routes/web.php

<?php

use Illuminate\Support\Facades\Route;

Route::auth([
    'login'    => true,
    'logout'   => true,
    'register' => true,
    'reset'    => true,
    'confirm'  => false,
    'verify'   => false,
]);
```
### Back-end

If you want to customize auth controller methods, you need to extend your own controller from library's `AuthController`:

```bash
php artisan make:controller AuthController
```
```php
# routes/web.php

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::auth([
    'controller' => AuthController::class,
    'middleware' => 'auth' // if you using custom guards for authenticated routes, put them here
]);
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

Check `WeStacks\Laravel\Auth\Traits` namespace for customizing logic of your auth backend.

### 1.0.0 - 2021.05.13
* Initial release

### 2.0.0 - 2021.06.03
* Make library work `larave/ui`-like.
* Make controller syntax cleater.