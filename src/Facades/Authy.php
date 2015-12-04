<?php

namespace Srmklive\Authy\Facades;

use Illuminate\Support\Facades\Facade;

class Authy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Srmklive\Authy\AuthyFacadeAccessor';
    }
}
