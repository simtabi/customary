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
        $setting = app()->make(CustomaryInterface::class);

        if (is_null($key)) {
            return $setting;
        }

        if (is_array($key)) {
            return $setting->set($key);
        }

        return $setting->get($key, value($default));
    }
}
