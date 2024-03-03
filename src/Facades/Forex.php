<?php

namespace Finller\Forex\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Finller\Forex\Forex
 */
class Forex extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Finller\Forex\Forex::class;
    }
}
