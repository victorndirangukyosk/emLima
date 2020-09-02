<?php

namespace GingerPayments\Payment\Order;

use GingerPayments\Payment\Common\ChoiceBasedValueObject;

final class Status
{
    use ChoiceBasedValueObject;

    /**
     * Possible order statuses.
     */
    const BRAND_NEW = 'new';
    const PROCESSING = 'processing';
    const ERROR = 'error';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    const EXPIRED = 'expired';
    const SEE_TRANSACTIONS = 'see-transactions';

    /**
     * @return array
     */
    public function possibleValues()
    {
        return [
            self::BRAND_NEW,
            self::PROCESSING,
            self::ERROR,
            self::COMPLETED,
            self::CANCELLED,
            self::EXPIRED,
            self::SEE_TRANSACTIONS,
        ];
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return self::BRAND_NEW === $this->value;
    }

    /**
     * @return bool
     */
    public function isProcessing()
    {
        return self::PROCESSING === $this->value;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return self::ERROR === $this->value;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return self::COMPLETED === $this->value;
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return self::CANCELLED === $this->value;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return self::EXPIRED === $this->value;
    }

    /**
     * @return bool
     */
    public function isSeeTransactions()
    {
        return self::SEE_TRANSACTIONS === $this->value;
    }
}
