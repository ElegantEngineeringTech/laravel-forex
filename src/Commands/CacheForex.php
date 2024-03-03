<?php

namespace Finller\Forex\Commands;

use Illuminate\Console\Command;

class CacheForex extends Command
{
    public $signature = 'forex:cache';

    public $description = 'Cache up to date forex values';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
