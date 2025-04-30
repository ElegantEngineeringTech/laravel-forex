<?php

declare(strict_types=1);

namespace Elegantly\Forex\Facades;

use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, int|float> latest(string $currency)
 * @method static array<string, int|float> rates(Carbon $date, string $currency)
 * @method static array<string, int|float> refreshLatest()
 * @method static array<string, int|float> queryLatest()
 * @method static array<string, int|float> refreshRates()
 * @method static array<string, int|float> queryRates()
 * @method static array<string, array<string, int|float>> getLatest()
 * @method static array<string, array<string, array<string, int|float>>> getRates()
 * @method static Money convert(Money $money, string|Currency $currency, RoundingMode $roundingMode = RoundingMode::HALF_UP, ?Carbon $date = null)
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
