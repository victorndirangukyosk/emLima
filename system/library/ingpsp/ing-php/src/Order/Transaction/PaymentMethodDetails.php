<?php

namespace GingerPayments\Payment\Order\Transaction;

interface PaymentMethodDetails
{
    /**
     * @return PaymentMethodDetails
     */
    public static function fromArray(array $details);

    /**
     * @return array
     */
    public function toArray();
}
