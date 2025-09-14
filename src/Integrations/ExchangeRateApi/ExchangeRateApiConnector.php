<?php

declare(strict_types=1);

namespace Elegantly\Forex\Integrations\ExchangeRateApi;

use Carbon\CarbonInterface;
use Elegantly\Forex\ForexClient;
use Elegantly\Forex\Integrations\ExchangeRateApi\Requests\HistoryRequest;
use Elegantly\Forex\Integrations\ExchangeRateApiFree\Requests\LatestRequest;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

/**
 * Free exchange rate values updated once a day
 *
 * @see https://www.exchangerate-api.com/docs/overview
 */
class ExchangeRateApiConnector extends Connector implements Cacheable, ForexClient
{
    use AlwaysThrowOnErrors;
    use HasCaching;
    use HasRateLimits;

    public readonly string $token;

    public function __construct(
        ?string $token = null,
        ?bool $cachingEnabled = null,
        ?bool $rateLimitingEnabled = null,
    ) {
        // @phpstan-ignore-next-line
        $this->token = $token ?? config('forex.clients.exchange-rate-api.token');
        $this->cachingEnabled = $cachingEnabled ?? (bool) config('forex.cache.enabled', true);
        $this->rateLimitingEnabled = $rateLimitingEnabled ?? (bool) config('forex.rate_limit.enabled', false);
    }

    public function resolveBaseUrl(): string
    {
        return 'https://v6.exchangerate-api.com/v6/';
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token);
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
        return $this->send(new LatestRequest($currency))->json('conversion_rates', []);
    }

    public function rates(CarbonInterface $date, string $currency): array
    {
        // @phpstan-ignore-next-line
        return $this->send(new HistoryRequest($date, $currency))->json('conversion_rates', []);
    }
}
