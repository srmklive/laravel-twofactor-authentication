# Laravel Two-Factor Authentication
- [Introduction](#introduction)
- [Installation](#installation)
- [Usage](#usage)
- [Demo Application](#demo-application)

<a name="introduction"></a>
## Introduction

This plugins allows you to enable two-factor authentication in your Laravel applications. 

**Only Laravel 5 or greater supported**


<a name="installation"></a>
## Installation

* Use following command to install:

```
composer require srmklive/authy
```

* Add the service provider to your $providers array in config/app.php file like: 

```
'Srmklive\Authy\AuthyServiceProvider' // Laravel 5
```
```
Srmklive\Authy\AuthyServiceProvider::class // Laravel 5.1 or greater
```

* Add the alias to your $aliases array in config/app.php file like: 

```
'Authy' => 'Srmklive\Authy\Facades\Authy' // Laravel 5
```
```
'Authy' => Srmklive\Authy\Facades\Authy::class // Laravel 5.1 or greater
```

* Run the following command to publish configuration:

```
php artisan vendor:publish
```

* Run the following command to migrate user table changes to database:

```
php artisan migrate
```

* Add the following lines in your User model (e.g App\User.php)

  * Before the class declaration, add these lines:

```
use Srmklive\Authy\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatableContract;
```

  * Now the change the class declaration. For example, if your class declaration is 

```
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
```

then change it to this:

```
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract,
                                    TwoFactorAuthenticatableContract
```

  * Now change the import traits line accordingly in user model file. For example if the line is:

```
use Authenticatable, Authorizable, CanResetPassword;
```

to

```
use Authorizable, CanResetPassword, TwoFactorAuthenticatable;
```

  * Lastly, add/update $hidden variable to hide 'two_factor_options' field from any DB call for user detail:

```
protected $hidden = [
	'two_factor_options'
];
```

<a name="usage"></a>
## Usage

* Registering User

```
$phone = '405-342-5699';
$code = 1;

$user = User::find(1);

$user->setAuthPhoneInformation(
    $code, $phone
);

try {
   Authy::twoFactorProvider()->register($user);

   $user->save();
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Unable To Register User']], 422);
}
```

* Send token via SMS

```
$user = User::find(1);

try {
   Authy::twoFactorProvider()->sendSmsToken($user);
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Unable To Send 2FA Login Token']], 422);
}
```

* Send token via phone call

```
$user = User::find(1);

try {
   Authy::twoFactorProvider()->sendPhoneCallToken($user);
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Unable To Send 2FA Login Token']], 422);
}
```

* Validating two-factor token

```
$user = User::find(1);

try {
   Authy::twoFactorProvider()->tokenIsValid($user, $token);
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Invalid 2FA Login Token Provided']], 422);
}
```

* Deleting User

```
$user = User::find(1);

try {
   Authy::twoFactorProvider()->delete($user);

   $user->save();
} catch (Exception $e) {
   app(ExceptionHandler::class)->report($e);

   return response()->json(['error' => ['Unable to Delete User']], 422);
}
```

<a name="demo-application"></a>
## Demo Application

I have also implemented this package in a simple laravel application. You can view installation instructions [here](https://github.com/srmklive/laravel-2fa-app). Through this application, you can do:

* User login & registration.
* Enable/Disable two-factor authentication for a user.
 
Following are the download links for the laravel demo application with two-factor authentication:

* [Laravel 5.1](https://github.com/srmklive/laravel-2fa-app/archive/v1.0.zip) 
* [Laravel 5.2](https://github.com/srmklive/laravel-2fa-app/archive/v2.0.zip)
