<?php

use Finller\Forex\Facades\Forex;

it('can query forex', function () {
    $rates = Forex::get('USD');

    expect($rates)
        ->toBeArray()
        ->toHaveKey('EUR');

    expect((float) $rates['USD'])->toBe(1.0);

    expect(Forex::getRates())
        ->toHaveKey('USD');
});
