<?php

namespace GingerPayments\Payment\Order\Transaction;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class PaymentMethod
{
    use ChoiceBasedValueObject;

    /**
     * Possible payment methods
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
            self::COD
        ];
    }

    /**
     * @return bool
     */
    public function isIdeal()
    {
        return $this->value === self::IDEAL;
    }

    /**
     * @return bool
     */
    public function isCreditCard()
    {
        return $this->value === self::CREDIT_CARD;
    }

    /**
     * @return bool
     */
    public function isBankTransfer()
    {
        return $this->value === self::BANK_TRANSFER;
    }

    /**
     * @return bool
     */
    public function isSofort()
    {
        return $this->value === self::SOFORT;
    }

    /**
     * @return bool
     */
    public function isBancontact()
    {
        return $this->value === self::BANCONTACT;
    }

    /**
     * @return bool
     */
    public function isCashOnDelivery()
    {
        return $this->value === self::COD;
    }
}
