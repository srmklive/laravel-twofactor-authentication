<?php

namespace Srmklive\Authy\Contracts\Auth\TwoFactor;

use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

interface SMSToken
{
    /**
     * Send the user two-factor authentication token via SMS.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     *
     * @return void
     */
    public function sendSmsToken(TwoFactorAuthenticatable $user);
}
