<?php

namespace Srmklive\Authy;

use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

class AuthyFacadeAccessor
{
    public static function twoFactorProvider()
    {
        return new Authy;
    }
}
