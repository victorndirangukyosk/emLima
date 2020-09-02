<?php

namespace GingerPayments\Payment\Order\Customer;

use Assert\Assertion as Guard;
use GingerPayments\Payment\Common\StringBasedValueObject;

final class Locale
{
    use StringBasedValueObject;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        if (!empty($value)) {
            Guard::regex($value, '/^[a-z]{2}(_[A-Z]{2})?$/', 'Locale is invalid: '.$value);
        }

        $this->value = $value;
    }
}
