<?php

namespace Srmklive\Authy\Auth\TwoFactor;

trait CanSendToken
{

    /**
     * Determine if the given user should be sent two-factor authentication token via SMS/phone call.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function canSendToken(TwoFactorAuthenticatable $user)
    {
        if ($this->isEnabled($user)) {
            if ($user->getTwoFactorAuthProviderOptions()['sms'] ||
                $user->getTwoFactorAuthProviderOptions()['phone'] ||
                $user->getTwoFactorAuthProviderOptions()['email'])
                return true;
        }

        return false;
    }
}
