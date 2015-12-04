# Laravel Authy
- [Introduction](#introduction)
- [Installation](#installation)

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
