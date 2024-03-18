<?php

namespace Finller\Forex;

interface ForexClient
{
    /**
     * @return array<string, int|float>
     */
    public function rates(string $currency): array;
}
