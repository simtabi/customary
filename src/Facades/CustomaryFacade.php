<?php

namespace Simtabi\Customary\Facades;

use Simtabi\Customary\Contracts\CustomaryInterface;
use Illuminate\Support\Facades\Facade;

class CustomaryFacade extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return CustomaryInterface::class;
    }
}
