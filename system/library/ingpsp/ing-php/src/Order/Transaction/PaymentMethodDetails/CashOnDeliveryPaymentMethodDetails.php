<?php

namespace GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;

use GingerPayments\Payment\Iban;
use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;
use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails\SofortPaymentMethodDetails\TransactionId;
use GingerPayments\Payment\SwiftBic;

final class CashOnDeliveryPaymentMethodDetails implements PaymentMethodDetails
{
    /**
     * @var TransactionId|null
     */
    private $transactionId;

    /**
     * @var Iban|null
     */
    private $consumerIban;

    /**
     * @var SwiftBic|null
     */
    private $consumerBic;

    /**
     * @var ConsumerName|null
     */
    private $consumerName;

    /**
     * @return CashOnDeliveryPaymentMethodDetails
     */
    public static function fromArray(array $details)
    {
        return new static(
            array_key_exists('transaction_id', $details) ? TransactionId::fromString($details['transaction_id']) : null,
            array_key_exists('consumer_name', $details) ? ConsumerName::fromString($details['consumer_name']) : null,
            array_key_exists('consumer_iban', $details) ? Iban::fromString($details['consumer_iban']) : null,
            array_key_exists('consumer_bic', $details) ? SwiftBic::fromString($details['consumer_bic']) : null
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'transaction_id' => (null !== $this->transactionId()) ? $this->transactionId()->toString() : null,
            'consumer_name' => (null !== $this->consumerName()) ? $this->consumerName()->toString() : null,
            'consumer_iban' => (null !== $this->consumerIban()) ? $this->consumerIban()->toString() : null,
            'consumer_bic' => (null !== $this->consumerBic()) ? $this->consumerBic()->toString() : null,
        ];
    }

    /**
     * @return SwiftBic|null
     */
    public function consumerBic()
    {
        return $this->consumerBic;
    }

    /**
     * @return Iban|null
     */
    public function consumerIban()
    {
        return $this->consumerIban;
    }

    /**
     * @return consumerName|null
     */
    public function consumerName()
    {
        return $this->consumerName;
    }

    /**
     * @return TransactionId|null
     */
    public function transactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param TransactionId $transactionId
     * @param ConsumerName  $consumerName
     * @param Iban          $consumerIban
     * @param SwiftBic      $consumerBic
     */
    private function __construct(
        TransactionId $transactionId = null,
        ConsumerName $consumerName = null,
        Iban $consumerIban = null,
        SwiftBic $consumerBic = null
    ) {
        $this->transactionId = $transactionId;
        $this->consumerName = $consumerName;
        $this->consumerIban = $consumerIban;
        $this->consumerBic = $consumerBic;
    }
}
