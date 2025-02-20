<?php

namespace Finller\Forex;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ForexServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-forex')
            ->hasConfigFile();
    }

    public function registeringPackage()
    {
        $this->app->scoped(Forex::class, function () {
            $client = config('forex.client');

            return new Forex(new $client);
        });
    }
}
