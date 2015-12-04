<?php

/**
 * Authy configuration & API credentials
 * @author Raza Mehdi<srmk@outlook.com>
 */

return [
    'mode' => 'live', // Can be either 'live' or 'sandbox'. If empty or invalid 'live' will be used
    'sandbox' => [
        'key' => ''
    ],
    'live' => [
        'key' => ''
    ],
    'sms' => false,
];
