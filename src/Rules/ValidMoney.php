<?php

declare(strict_types=1);

namespace Elegantly\Forex\Rules;

use Brick\Money\Currency;
use Brick\Money\Money;
use Closure;
use Elegantly\Forex\Facades\Forex;
use Elegantly\Money\MoneyParser;
use Elegantly\Money\MoneyServiceProvider;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\App;
use Illuminate\Translation\PotentiallyTranslatedString;
use InvalidArgumentException;

class ValidMoney implements DataAwareRule, ValidationRule
{
    public function __construct(
        public readonly Currency|string|null $currency = null,
        public readonly ?Money $min = null,
        public readonly ?Money $max = null
    ) {
        //
    }

    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getCurrency(): Currency
    {
        if ($this->currency instanceof Currency) {
            return $this->currency;
        }

        if (is_string($this->currency)) {

            if ($value = data_get($this->data, $this->currency)) {
                /** @var string $value */
                return Currency::of($value);
            }

            throw new InvalidArgumentException(
                "Currency attribute \"{$this->currency}\" does not exist or is null in the validation data."
            );

        }

        return Currency::of(MoneyServiceProvider::getDefaultCurrency());
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $locale = App::getLocale();

        $money = MoneyParser::parse($value, $this->getCurrency());

        if ($money === null) {
            $fail('money::validation.money')->translate();

            return;
        }

        if (
            $this->min && Forex::convert($money, $this->min->getCurrency())->isLessThan($this->min)
        ) {
            $fail('money::validation.money_min')->translate([
                'value' => $this->min->formatToLocale($locale),
            ]);

            return;
        }

        if (
            $this->max && Forex::convert($money, $this->max->getCurrency())->isGreaterThan($this->max)
        ) {
            $fail('money::validation.money_max')->translate([
                'value' => $this->max->formatToLocale($locale),
            ]);

            return;
        }

    }
}
