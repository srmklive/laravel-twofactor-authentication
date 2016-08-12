<?php

if (! function_exists('authy')) {

    /**
     * Global helper function for Authy class.
     *
     * @return mixed
     */
    function express_checkout()
    {
        return app('authy');
    }

}
