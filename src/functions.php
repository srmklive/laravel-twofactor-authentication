<?php

if (!function_exists('authy')) {

    /**
     * Global helper function for Authy class.
     *
     * @return mixed
     */
    function authy()
    {
        return app('authy');
    }
}
