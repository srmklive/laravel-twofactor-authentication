<?php

namespace Srmklive\Authy\Contracts\Auth\TwoFactor;

use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

interface Provider
{
    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     *
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatable $user);

    /**
     * Register the given user with the provider.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param bool                                                     $sms
     *
     * @return void
     */
    public function register(TwoFactorAuthenticatable $user, $sms = false);

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param string                                                   $token
     *
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token);

    /**
     * Delete the given user from the provider.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     *
     * @return bool
     */
    public function delete(TwoFactorAuthenticatable $user);
}
