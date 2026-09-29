<?php

declare(strict_types=1);

namespace Elegantly\Forex\Facades;

use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, int|float> latest(string|Currency $currency)
 * @method static array<string, int|float> rates(CarbonInterface $date, string|Currency $currency)
 * @method static array<string, int|float> rate(CarbonInterface $date, string|Currency $currency, string|Currency $target)
 * @method static array<string, int|float> refreshLatest(string|Currency $currency)
 * @method static array<string, int|float> queryLatest(string|Currency $currency)
 * @method static array<string, int|float> refreshRates(CarbonInterface $date, string|Currency $currency)
 * @method static array<string, int|float> queryRates(CarbonInterface $date, string|Currency $currency)
 * @method static array<string, array<string, int|float>> getLatest()
 * @method static array<string, array<string, array<string, int|float>>> getRates()
 * @method static Money convert(Money $money, string|Currency $currency, ?RoundingMode $roundingMode = null, ?CarbonInterface $date = null)
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
