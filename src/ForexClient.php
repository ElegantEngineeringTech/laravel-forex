<?php

declare(strict_types=1);

namespace Elegantly\Forex;

interface ForexClient
{
    /**
     * @return array<string, int|float>
     */
    public function rates(string $currency): array;
}
