<?php

declare(strict_types=1);

namespace Elegantly\Forex;

use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Carbon\CarbonInterface;
use Closure;
use Elegantly\Forex\Facades\Forex;

/**
 * Sum the Money values at the given key.
 *
 * @template TValue
 *
 * @param  iterable<array-key, TValue>  $items
 * @param  string|(Closure(TValue $item):?Money)  $key
 * @param  null|string|CarbonInterface|(Closure(TValue $item):?CarbonInterface)  $date
 */
function sumMoney(
    iterable $items,
    string|Closure $key,
    null|string|Currency $currency,
    null|string|CarbonInterface|Closure $date,
    ?RoundingMode $roundingMode = null,
): ?Money {
    $roundingMode ??= ForexServiceProvider::getRoundingMode();

    $key = match (true) {
        // @phpstan-ignore-next-line
        is_string($key) => fn ($item): ?Money => data_get($item, $key),
        default => $key,
    };

    $date = match (true) {
        $date instanceof CarbonInterface => fn ($item) => $date,
        // @phpstan-ignore-next-line
        is_string($date) => fn ($item): ?CarbonInterface => data_get($item, $date),
        default => $date,
    };

    $total = null;

    foreach ($items as $item) {
        $money = $key($item);

        if ($money === null) {
            continue;
        }

        if ($currency) {
            $money = Forex::convert(
                money: $money,
                currency: $currency,
                roundingMode: $roundingMode,
                date: $date ? $date($item) : null
            );
        }

        $total = $total === null ? $money : $total->plus($money, $roundingMode);
    }

    return $total;
}
