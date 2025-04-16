<?php

declare(strict_types=1);

namespace Elegantly\Forex\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, array<string, int|float>> getRates()
 * @method static array<string, int|float> get(string $currency)
 * @method static array<string, int|float> query(string $currency)
 * @method static array<string, int|float> refresh(string $currency)
 *
 * @see \Elegantly\Forex\Forex
 */
class Forex extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Elegantly\Forex\Forex::class;
    }
}
