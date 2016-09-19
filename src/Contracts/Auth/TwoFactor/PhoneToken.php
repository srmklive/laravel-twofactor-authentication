<?php

namespace Srmklive\Authy\Contracts\Auth\TwoFactor;

use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

interface PhoneToken
{
    /**
     * Start the user two-factor authentication via phone call.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     *
     * @return void
     */
    public function sendPhoneCallToken(TwoFactorAuthenticatable $user);
}
