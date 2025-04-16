<?php

declare(strict_types=1);

namespace Elegantly\Forex\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, int|float> latest()
 * @method static array<string, int|float> rates()
 * @method static array<string, int|float> refreshLatest()
 * @method static array<string, int|float> queryLatest()
 * @method static array<string, int|float> refreshRates()
 * @method static array<string, int|float> queryRates()
 * @method static array<string, array<string, int|float>> getLatest()
 * @method static array<string, array<string, array<string, int|float>>> getRates()
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
