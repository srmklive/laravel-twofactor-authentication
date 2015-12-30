<?php

namespace Srmklive\Authy\Contracts\Auth\TwoFactor;

use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

interface Provider
{
    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatable $user);

    /**
     * Determine if the given user should be sent two-factor authentication token via SMS.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function canSendSms(TwoFactorAuthenticatable $user);

    /**
     * Register the given user with the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param boolean $sms
     * @return void
     */
    public function register(TwoFactorAuthenticatable $user, $sms);

    /**
     * Send the user 2-factor authentication token via SMS
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return void
     */
    public function sendTokenSms(TwoFactorAuthenticatable $user);

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param  string  $token
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token);

    /**
     * Delete the given user from the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function delete(TwoFactorAuthenticatable $user);
}
