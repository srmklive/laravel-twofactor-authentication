# Laravel Two-Factor Authentication

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/srmklive/authy.svg?style=flat-square)](https://packagist.org/packages/srmklive/authy)
[![Total Downloads](https://img.shields.io/packagist/dt/srmklive/authy.svg?style=flat-square)](https://packagist.org/packages/srmklive/authy)
[![StyleCI](https://styleci.io/repos/47398032/shield?style=flat)](https://styleci.io/repos/47398032)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/srmklive/laravel-twofactor-authentication/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/srmklive/laravel-twofactor-authentication/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1f1e2abe-aefe-4490-a011-0ec8fac6860f/small.png)](https://insight.sensiolabs.com/projects/1f1e2abe-aefe-4490-a011-0ec8fac6860f)

- [Introduction](#introduction)
- [Installation](#installation)
- [Modify Login Workflow](#modify-login-workflow)
- [Usage](#usage)
- [Add a new TwoFactor Authentication Provider](#implement-new-provider)
- [Demo Application](#demo-application)

<a name="introduction"></a>
## Introduction

This plugins allows you to enable two-factor authentication in your Laravel applications. 

**Only Laravel 5.1 or greater supported**


<a name="installation"></a>
## Installation

* Use following command to install:

```bash
composer require srmklive/authy
```

* Add the service provider to your $providers array in config/app.php file like: 

```php
Srmklive\Authy\Providers\AuthyServiceProvider::class
```

* Add the alias to your $aliases array in config/app.php file like: 

```php
'Authy' => Srmklive\Authy\Facades\Authy::class
```

* Run the following command to publish configuration:

```bash
php artisan vendor:publish --provider "Srmklive\Authy\Providers\AuthyServiceProvider"
```

* Run the following command to migrate user table changes to database:

```bash
php artisan migrate
```

* Add the following lines in your User model (e.g App\User.php)

  * Before the class declaration, add these lines:

```php
use Srmklive\Authy\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatableContract;
```

  * Now the change the class declaration. For example, if your class declaration is 

```php
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
```

then change it to this:

```php
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract,
                                    TwoFactorAuthenticatableContract
```

  * Now change the import traits line accordingly in user model file. For example if the line is:

```php
use Authenticatable, Authorizable, CanResetPassword;
```

to

```php
use Authenticatable, Authorizable, CanResetPassword, TwoFactorAuthenticatable;
```

  * Lastly, add/update $hidden variable to hide 'two_factor_options' field from any DB call for user detail:

```php
protected $hidden = [
	'two_factor_options'
];
```


<a name="modify-login-workflow"></a>
## Modifying Login Workflow

* You need to add the following code to your `app\Http\Controllers\Auth\AuthController.php`.

```php
    /**
     * Send the post-authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, Authenticatable $user)
    {
        if (authy()->isEnabled($user)) {
            return $this->logoutAndRedirectToTokenScreen($request, $user);
        }

        return redirect()->intended($this->redirectPath());
    }
    
    /**
     * Generate a redirect response to the two-factor token screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Http\Response
     */
    protected function logoutAndRedirectToTokenScreen(Request $request, Authenticatable $user)
    {
        // Uncomment this line for Laravel 5.2+
        //auth($this->getGuard())->logout();

        // Uncomment this line for Laravel 5.1
        // auth()->logout();

        $request->session()->put('authy:auth:id', $user->id);

        return redirect(url('auth/token'));
    }

    /**
     * Show two-factor authentication page
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getToken()
    {
        return session('authy:auth:id') ? view('auth.token') : redirect(url('login'));
    }

    /**
     * Verify the two-factor authentication token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postToken(Request $request)
    {
        $this->validate($request, ['token' => 'required']);
        if (! session('authy:auth:id')) {
            return redirect(url('login'));
        }

        // Uncomment these lines for use in Laravel 5.2+ 
        //$guard = config('auth.defaults.guard');
        //$provider = config('auth.guards.' . $guard . '.provider');
        //$model = config('auth.providers.' . $provider . '.model');

        // Uncomment the line below for use in Laravel 5.1
        // $model = config('auth.model');

        $user = (new $model)->findOrFail(
            $request->session()->pull('authy:auth:id')
        );

        if (authy()->tokenIsValid($user, $request->token)) {
            // Uncomment this line for Laravel 5.2+
            //auth($this->getGuard())->login($user);

            // Uncomment this line for Laravel 5.1
	        //auth()->login($user);

            return redirect()->intended($this->redirectPath());
        } else {
            return redirect(url('login'))->withErrors('Invalid two-factor authentication token provided!');
        }
    }        
```

* Add route to verify two-factor authentication token

```php
Route::get('auth/token','Auth\AuthController@getToken');
Route::post('auth/token','Auth\AuthController@postToken');
```

* Create view file in `resources/views/auth/token.blade.php`. Change this accordingly for your application. I have used code from [AdminLTE](https://github.com/almasaeed2010/AdminLTE) theme here.

```blade
@extends('layouts.app')

@section('content')
    <div class="register-logo">
        Two-factor Authentication
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">Validate your two-factor authentication token</p>
        <form method="POST" action="{{url('auth/token')}}">
            {!! csrf_field() !!}

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group has-feedback">
                <input type="type" name="token" class="form-control" placeholder="Token">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-7"></div><!-- /.col -->
                <div class="col-xs-5">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Verify Token</button>
                </div><!-- /.col -->
            </div>
        </form>
    </div><!-- /.form-box -->
@endsection

```


<a name="usage"></a>
## Usage

* Registering User

```php
$phone = '405-342-5699';
$code = 1;

$user = User::find(1);

$user->setAuthPhoneInformation(
    $code, $phone
);

try {
   Authy::getProvider()->register($user);

   $user->save();
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Unable To Register User']], 422);
}
```

* Send token via SMS

```php
$user = User::find(1);

try {
   Authy::getProvider()->sendSmsToken($user);
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Unable To Send 2FA Login Token']], 422);
}
```

* Send token via phone call

```php
$user = User::find(1);

try {
   Authy::getProvider()->sendPhoneCallToken($user);
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Unable To Send 2FA Login Token']], 422);
}
```

* Validating two-factor token

```php
$user = User::find(1);

try {
   Authy::getProvider()->tokenIsValid($user, $token);
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Invalid 2FA Login Token Provided']], 422);
}
```

* Deleting User

```php
$user = User::find(1);

try {
   Authy::getProvider()->delete($user);

   $user->save();
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Unable to Delete User']], 422);
}
```

<a name="implement-new-provider"></a>
## Add a new TwoFactor Authentication Provider

Currently this package uses two-factor authentication services from [**Authy**](https://www.authy.com). You can also implement another two-factor authentication provider by doing the following:

```php
<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client as HttpClient;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Provider as BaseProvider;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

class MyAuthProvider implements BaseProvider
{
    /**
     * Array containing configuration data.
     *
     * @var array $config
     */
    private $config;

    /**
     * Authy constructor.
     */
    public function __construct()
    {
    	// Add your configuration code here
    }

    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatable $user)
    {
    	// Add your code here
    }

    /**
     * Register the given user with the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param boolean $sms
     * @return void
     */
    public function register(TwoFactorAuthenticatable $user, $sms = false)
    {
    	// Add your code here
    }

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param  string  $token
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token)
    {
    	// Add your code here
    }

    /**
     * Delete the given user from the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function delete(TwoFactorAuthenticatable $user)
    {
    	// Add your code here
    }
}
```

<a name="demo-application"></a>
## Demo Application

I have also implemented this package in a simple laravel application. You can view installation instructions [here](https://github.com/srmklive/laravel-2fa-demo). Through this application, you can do:

* User login & registration.
* Enable/Disable two-factor authentication for a user.
