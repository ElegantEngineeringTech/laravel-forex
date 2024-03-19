<?php

use Finller\Forex\Integrations\ExchangeRateApi\Requests\LatestRequest;
use Finller\Forex\Tests\TestCase;
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
            LatestRequest::class => MockResponse::fixture('exchange-rates-api/latest'),
        ]);
    })
    ->in(__DIR__);
