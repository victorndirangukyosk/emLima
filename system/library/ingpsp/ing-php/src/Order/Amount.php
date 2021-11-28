<?php

namespace GingerPayments\Payment\Order;

use Assert\Assertion as Guard;

final class Amount
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @param int $amount
     *
     * @return Amount
     */
    public static function fromInteger($amount)
    {
        return new static((int) $amount);
    }

    /**
     * @return int
     */
    public function toInteger()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    private function __construct($amount)
    {
        Guard::min($amount, 1, 'Amount must be at least one');

        $this->amount = $amount;
    }
}
