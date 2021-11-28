<?php

namespace GingerPayments\Payment\Order\Customer;

use Assert\Assertion as Guard;
use GingerPayments\Payment\Common\ISO3166;
use GingerPayments\Payment\Common\StringBasedValueObject;

final class Country
{
    use StringBasedValueObject;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        Guard::true(
            empty($value) || ISO3166::isValid($value),
            'Customer country must have ISO 3166-1 alpha-2 standard.'
        );

        $this->value = $value;
    }
}
