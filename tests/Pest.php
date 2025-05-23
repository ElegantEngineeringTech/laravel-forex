<?php

declare(strict_types=1);

use Elegantly\Forex\Integrations\ExchangeRateApiFree\Requests\LatestRequest;
use Elegantly\Forex\Tests\TestCase;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\MockConfig;

uses(TestCase::class)->in(__DIR__);

Config::preventStrayRequests();

MockConfig::throwOnMissingFixtures();

uses()
    ->beforeEach(function () {
        MockClient::destroyGlobal();
        MockClient::global([
            LatestRequest::class => MockResponse::fixture('exchangerate-api-free/latest'),
        ]);
    })
    ->in(__DIR__);
