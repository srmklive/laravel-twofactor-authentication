<?php

namespace Srmklive\Authy;

use Srmklive\Authy\Services\Authy;

class AuthyFacadeAccessor
{
    /**
     * TwoFactor auth provider object.
     *
     * @var
     */
    public static $provider;

    /**
     * Get specific TwoFactor auth provider object to use.
     *
     * @return Authy
     */
    public static function getProvider()
    {
        if (empty(self::$provider)) {
            return new Authy();
        } else {
            return self::$provider;
        }
    }

    /**
     * Set specific TwoFactor auth provider to use.
     *
     * @param string $option
     *
     * @return Authy
     */
    public static function setProvider($option = '')
    {
        if (!in_array($option, ['authy'])) {
            $option = 'authy';
        }

        if ($option == 'authy') {
            self::$provider = new Authy();
        }

        return self::getProvider();
    }
}
