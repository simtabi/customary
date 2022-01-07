<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Simtabi\Customary\Contracts\CustomaryInterface;

if (! function_exists('customary')) {

    /**
     * Get app setting stored in db.
     *
     * @param $key
     * @param null $default
     * @return mixed
     * @throws BindingResolutionException
     */
    function customary($key = null, $default = null): mixed
    {
        $customary = app()->make(CustomaryInterface::class);

        if (is_null($key)) {
            return $customary;
        }

        if (is_array($key)) {
            return $customary->set($key);
        }

        return $customary->get($key, value($default));
    }
}
