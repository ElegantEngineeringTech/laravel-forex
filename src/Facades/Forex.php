<?php

namespace Finller\Forex\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, array<string, int|float>> getRates()
 * @method static array<string, int|float> get(string $currency)
 * @method static array<string, int|float> query(string $currency)
 * @method static array<string, int|float> refresh(string $currency)
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
