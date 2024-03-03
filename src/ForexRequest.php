<?php

namespace Finller\Forex;

interface ForexRequest
{
    /**
     * @return array<string, int|float>
     */
    public static function get(string $currency): array;
}
