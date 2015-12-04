<?php

namespace Srmklive\Authy;

use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

class AuthyFacadeAccessor
{
    protected $authy;

    public function __construct()
    {
        $this->authy = new Authy();
    }

    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return bool
     */
    public static function isEnabled(TwoFactorAuthenticatable $user)
    {
        return isset(self::$authy->getTwoFactorAuthProviderOptions()['id']);
    }

    /**
     * Register the given user with the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return void
     */
    public static function register(TwoFactorAuthenticatable $user)
    {
        return self::$authy->register($user);
    }

    /**
     * Send the given user authentication token
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return bool
     */
    public function sendSms(TwoFactorAuthenticatable $user)
    {
        return self::$authy->sendSms($user);
    }

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @param  string  $token
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token)
    {
        return self::$authy->tokenIsValid($user, $token);
    }

    /**
     * Delete the given user from the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable  $user
     * @return bool
     */
    public function delete(TwoFactorAuthenticatable $user)
    {
        return self::$authy->delete($user);
    }
}
