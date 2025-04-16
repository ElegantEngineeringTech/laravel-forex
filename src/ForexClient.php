<?php

declare(strict_types=1);

namespace Elegantly\Forex;

use Carbon\Carbon;

interface ForexClient
{
    /**
     * @return array<string, int|float>
     */
    public function latest(string $currency): array;

    /**
     * @return array<string, int|float>
     */
    public function rates(Carbon $carbon, string $currency): array;
}
