<?php

namespace Finller\Forex;

class Forex
{
    /**
     * @return array<string, int|float>
     */
    public function get(string $currency): array
    {
        /** @var string $request */
        $request = config('forex.request');

        return $request::get($currency);
    }
}
