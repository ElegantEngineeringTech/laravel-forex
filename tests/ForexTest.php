<?php

declare(strict_types=1);

use Brick\Money\Money;
use Elegantly\Forex\Facades\Forex;

it('can query forex latest rates', function () {

    $rates = Forex::latest('USD');

    expect($rates)
        ->toBeArray()
        ->toHaveKey('EUR');

    expect((float) $rates['USD'])->toBe(1.0);

});

it('can convert money to another currency', function ($money, $expected) {

    $converted = Forex::convert(
        $money,
        $expected->getCurrency(),
    );

    expect((string) $converted)->toBe((string) $expected);

})->with([
    [Money::of(100, 'USD'), Money::of(100, 'USD')],
    [Money::of(100, 'USD'), Money::of(87.81, 'EUR')],
    [Money::of(100, 'USD'), Money::of(74.61, 'GBP')],
]);
