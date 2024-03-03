<?php

namespace Finller\Forex\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, int|float> get(string $currency)
 *
 * @see \Finller\Forex\Forex
 */
class Forex extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Finller\Forex\Forex::class;
    }
}
