# Laravel Authy
- [Credits](#credits)
- [Introduction](#introduction)
- [Installation](#installation)

<a name="credits"></a>
## Credits

This plugins code is a direct port of the two-factor authentication functionality implemented in [Laravel Spark](https://github.com/laravel/spark) by @taylorotwell. However some modifications are done to the original code in this plugin.  

<a name="introduction"></a>
## Introduction

This plugins allows you to enable two-factor authentication in your Laravel applications. 

** Only Laravel 5 or greater supported **


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
Srmklive\Authy\AuthyServiceProvider::class // Laravel 5.1
```

* Add the alias to your $aliases array in config/app.php file like: 

```
'Authy' => 'Srmklive\Authy\Facades\Authy' // Laravel 5
```
```
'Authy' => Srmklive\Authy\Facades\Authy::class // Laravel 5.1
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

** Before the class declaration, add these lines:

```
use Srmklive\Authy\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatableContract;
```

** Now the change the class declaration. For example, if your class declaration is 

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

** Now change the import traits line accordingly in user model file. For example if the line is:

```
use Authenticatable, Authorizable, CanResetPassword;
```

to

```
use Authorizable, CanResetPassword, TwoFactorAuthenticatable;
```

** Lastly, add/update $hidden variable to hide 'two_factor_options' field from any DB call for user detail:

```
protected $hidden = [
	'two_factor_options'
];
```
