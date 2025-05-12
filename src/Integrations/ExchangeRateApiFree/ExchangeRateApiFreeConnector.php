<?php

declare(strict_types=1);

namespace Elegantly\Forex\Integrations\ExchangeRateApiFree;

use Carbon\Carbon;
use Elegantly\Forex\ForexClient;
use Elegantly\Forex\Integrations\ExchangeRateApiFree\Requests\LatestRequest;
use Exception;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Http\Connector;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

/**
 * Free exchange rate values updated once a day
 *
 * @see https://www.exchangerate-api.com/docs/free
 */
class ExchangeRateApiFreeConnector extends Connector implements Cacheable, ForexClient
{
    use AlwaysThrowOnErrors;
    use HasCaching;
    use HasRateLimits;

    public function __construct(
        ?bool $cachingEnabled = null,
        ?bool $rateLimitingEnabled = null,
    ) {
        $this->cachingEnabled = $cachingEnabled ?? (bool) config('forex.cache.enabled', true);
        $this->rateLimitingEnabled = $rateLimitingEnabled ?? (bool) config('forex.rate_limit.enabled', false);
    }

    public function resolveBaseUrl(): string
    {
        return 'https://open.er-api.com/v6/';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function resolveCacheDriver(): Driver
    {
        /** @var string $name */
        $name = config('forex.cache.driver', 'file');

        return new LaravelCacheDriver(Cache::store($name));
    }

    public function cacheExpiryInSeconds(): int
    {
        /** @var int $seconds */
        $seconds = config('forex.cache.expiry_seconds', 86_400);

        return $seconds;
    }

    protected function resolveRateLimitStore(): RateLimitStore
    {
        /** @var string $name */
        $name = config('forex.rate_limit.driver', 'array');

        return new LaravelCacheStore(Cache::store($name));
    }

    protected function resolveLimits(): array
    {
        /** @var int $seconds */
        $seconds = config('forex.rate_limit.every_seconds');

        return [
            Limit::allow(1)->everySeconds($seconds),
        ];
    }

    public function latest(string $currency): array
    {
        // @phpstan-ignore-next-line
        return $this->send(new LatestRequest($currency))->json('rates', []);
    }

    public function rates(Carbon $date, string $currency): array
    {
        throw new Exception(static::class.' does not support historical data.');
    }
}
