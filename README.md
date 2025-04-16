# Laravel Forex

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/laravel-forex.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-forex)  
[![Tests](https://img.shields.io/github/actions/workflow/status/elegantly/laravel-forex/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ElegantEngineeringTech/laravel-forex/actions?query=workflow%3Arun-tests+branch%3Amain)  
[![Code Style](https://img.shields.io/github/actions/workflow/status/elegantly/laravel-forex/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ElegantEngineeringTech/laravel-forex/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)  
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/laravel-forex.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-forex)

**Laravel Forex** is a simple and flexible package for retrieving the latest and historical foreign exchange rates in your Laravel application.

By default, it uses the free tier from [exchangerate-api.com](https://www.exchangerate-api.com/), but you can easily configure it to use any other Forex provider.

---

## 🚀 Installation

Install via Composer:

```bash
composer require elegantly/laravel-forex
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag="forex-config"
```

---

## ⚙️ Configuration

Here’s the default configuration that will be published to `config/forex.php`:

```php
use Elegantly\Forex\Integrations\ExchangeRateApiFree\ExchangeRateApiFreeConnector;

return [

    'cache' => [
        'enabled' => true,
        'driver' => env('FOREX_CACHE_DRIVER', env('CACHE_DRIVER', 'file')),
        'expiry_seconds' => 86_400, // 1 day
    ],

    'rate_limit' => [
        'enabled' => false,
        'driver' => env('FOREX_RATE_LIMIT_DRIVER', env('CACHE_DRIVER', 'file')),
        'every_seconds' => 3_600, // 1 hour
    ],

    'client' => ExchangeRateApiFreeConnector::class,

    'clients' => [
        'exchange-rate-api' => [
            'token' => env('EXCHANGE_RATE_API_TOKEN'),
        ],
    ],

];
```

---

## 📦 Usage

### Get Latest Rates

```php
use Elegantly\Forex\Facades\Forex;

$rates = Forex::latest('USD');

$usdToEur = $rates['EUR'];
```

### Get Historical Rates

```php
use Carbon\Carbon;
use Elegantly\Forex\Facades\Forex;

$rates = Forex::rates(Carbon::create(2022, 4, 25), 'USD');

$usdToEur = $rates['EUR'];
```

---

## ✅ Testing

Run the test suite with:

```bash
composer test
```

---

## 📄 Changelog

See the [CHANGELOG](CHANGELOG.md) for details on recent updates.

---

## 🤝 Contributing

Contributions are welcome! Please read the [CONTRIBUTING](CONTRIBUTING.md) guide for details.

---

## 🔐 Security

If you discover any security-related issues, please refer to our [security policy](../../security/policy).

---

## 🙏 Credits

-   [Quentin Gabriele](https://github.com/QuentinGab)
-   [All Contributors](../../contributors)

---

## 📃 License

This package is open-source software licensed under the [MIT license](LICENSE.md).

---

Let me know if you'd like this version saved in a `README.md` file or if you want badges for other integrations!
