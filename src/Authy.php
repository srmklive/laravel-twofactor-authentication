<?php

namespace Srmklive\Authy;

use Exception;
use GuzzleHttp\Client as HttpClient;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Provider;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

class Authy implements Provider
{
    private $config;

    /**
     * Authy constructor.
     */
    public function __construct()
    {
        if (!empty(config('authy.mode')) && (config('authy.mode') == 'sandbox')) {
            $this->config['api_key'] = config('authy.sandbox.key');
            $this->config['api_url'] = 'http://sandbox-api.authy.com';
        } else {
            $this->config['api_key'] = config('authy.live.key');
            $this->config['api_url'] = 'https://api.authy.com';
        }
    }

    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatable $user)
    {
        return isset($user->getTwoFactorAuthProviderOptions()['id']);
    }

    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param  \Laravel\Spark\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function canSendSms(TwoFactorAuthenticatable $user)
    {
        if ($this->isEnabled($user)) {
            if ($user->getTwoFactorAuthProviderOptions()['sms'])
                return true;
        }

        return false;
    }
    
    /**
     * Register the given user with the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param boolean $sms
     * @return void
     */
    public function register(TwoFactorAuthenticatable $user, $sms)
    {
        $response = json_decode((new HttpClient)->post($this->config['api_url'] . '/protected/json/users/new?api_key=' . $this->config['api_key'], [
            'form_params' => [
                'user' => [
                    'email' => $user->getEmailForTwoFactorAuth(),
                    'cellphone' => preg_replace('/[^0-9]/', '', $user->getAuthPhoneNumber()),
                    'country_code' => $user->getAuthCountryCode(),
                ],
            ],
        ])->getBody(), true);

        $user->setTwoFactorAuthProviderOptions([
            'id' => $response['user']['id'],
            'sms' => $sms,
        ]);
    }

    /**
     * Send the user 2-factor authentication token via SMS
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return void
     */
    public function sendSmsToken(TwoFactorAuthenticatable $user)
    {
        try {
            $options = $user->getTwoFactorAuthProviderOptions();

            $response = json_decode((new HttpClient)->get(
                $this->config['api_url'] . '/protected/json/sms/' . $options['id'] .
                '?force=true&api_key=' . $this->config['api_key']
            )->getBody(), true);

            return $response['success'] === true;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param  string  $token
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token)
    {
        try {
            $options = $user->getTwoFactorAuthProviderOptions();

            $response = json_decode((new HttpClient)->get(
                $this->config['api_url'] . '/protected/json/verify/' .
                $token. '/' . $options['id'] . '?force=true&api_key='.
                $this->config['api_key']
            )->getBody(), true);

            return $response['token'] === 'is valid';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete the given user from the provider.
     *
     * @param  \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @return bool
     */
    public function delete(TwoFactorAuthenticatable $user)
    {
        $options = $user->getTwoFactorAuthProviderOptions();

        (new HttpClient)->post(
            $this->config['api_url'] . '/protected/json/users/delete/' .
            $options['id'] . '?api_key=' . $this->config['api_key']
        );

        $user->setTwoFactorAuthProviderOptions([]);
    }
}
