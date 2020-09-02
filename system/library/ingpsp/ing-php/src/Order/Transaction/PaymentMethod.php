<?php

namespace GingerPayments\Payment\Order\Transaction;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class PaymentMethod
{
    use ChoiceBasedValueObject;

    /**
     * Possible payment methods.
     */
    const IDEAL = 'ideal';
    const CREDIT_CARD = 'credit-card';
    const BANK_TRANSFER = 'bank-transfer';
    const SOFORT = 'sofort';
    const BANCONTACT = 'bancontact';
    const COD = 'cash-on-delivery';

    /**
     * @return array
     */
    public function possibleValues()
    {
        return [
            self::IDEAL,
            self::CREDIT_CARD,
            self::BANK_TRANSFER,
            self::SOFORT,
            self::BANCONTACT,
            self::COD,
        ];
    }

    /**
     * @return bool
     */
    public function isIdeal()
    {
        return self::IDEAL === $this->value;
    }

    /**
     * @return bool
     */
    public function isCreditCard()
    {
        return self::CREDIT_CARD === $this->value;
    }

    /**
     * @return bool
     */
    public function isBankTransfer()
    {
        return self::BANK_TRANSFER === $this->value;
    }

    /**
     * @return bool
     */
    public function isSofort()
    {
        return self::SOFORT === $this->value;
    }

    /**
     * @return bool
     */
    public function isBancontact()
    {
        return self::BANCONTACT === $this->value;
    }

    /**
     * @return bool
     */
    public function isCashOnDelivery()
    {
        return self::COD === $this->value;
    }
}
