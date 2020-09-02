<?php

namespace GingerPayments\Payment\Order\Customer;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class AddressType
{
    use ChoiceBasedValueObject;

    /**
     * Possible Address Types.
     */
    const CUSTOMER = 'customer';
    const DELIVERY = 'delivery';
    const BILLING = 'billing';

    /**
     * @return array
     */
    public function possibleValues()
    {
        return [
            self::CUSTOMER,
            self::DELIVERY,
            self::BILLING,
        ];
    }

    /**
     * @return bool
     */
    public function isCustomer()
    {
        return self::CUSTOMER === $this->value;
    }

    /**
     * @return bool
     */
    public function isDelivery()
    {
        return self::DELIVERY === $this->value;
    }

    /**
     * @return bool
     */
    public function isBilling()
    {
        return self::BILLING === $this->value;
    }
}
