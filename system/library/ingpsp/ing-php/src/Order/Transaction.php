<?php

namespace GingerPayments\Payment\Order;

use Assert\Assertion as Guard;
use Carbon\Carbon;
use GingerPayments\Payment\Currency;
use GingerPayments\Payment\Order\Transaction\Amount as TransactionAmount;
use GingerPayments\Payment\Order\Transaction\Balance;
use GingerPayments\Payment\Order\Transaction\Description as TransactionDescription;
use GingerPayments\Payment\Order\Transaction\PaymentMethod;
use GingerPayments\Payment\Order\Transaction\PaymentMethodDetails;
use GingerPayments\Payment\Order\Transaction\Reason;
use GingerPayments\Payment\Order\Transaction\Status as TransactionStatus;
use GingerPayments\Payment\Url;
use Rhumsaa\Uuid\Uuid;

final class Transaction
{
    /**
     * @var Uuid|null
     */
    private $id;

    /**
     * @var Carbon|null
     */
    private $created;

    /**
     * @var Carbon|null
     */
    private $modified;

    /**
     * @var Carbon|null
     */
    private $completed;

    /**
     * @var TransactionStatus|null
     */
    private $status;

    /**
     * @var Reason|null
     */
    private $reason;

    /**
     * @var Currency|null
     */
    private $currency;

    /**
     * @var TransactionAmount|null
     */
    private $amount;

    /**
     * @var \DateInterval|null
     */
    private $expirationPeriod;

    /**
     * @var TransactionDescription|null
     */
    private $description;

    /**
     * @var Balance|null
     */
    private $balance;

    /**
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * @var PaymentMethodDetails
     */
    private $paymentMethodDetails;

    /**
     * @var Url|null
     */
    private $paymentUrl;

    /**
     * @return Transaction
     */
    public static function fromArray(array $transaction)
    {
        Guard::keyExists($transaction, 'payment_method');

        $paymentMethod = PaymentMethod::fromString($transaction['payment_method']);

        return new static(
            $paymentMethod,
            PaymentMethodDetails\PaymentMethodDetailsFactory::createFromArray(
                $paymentMethod,
                array_key_exists(
                    'payment_method_details',
                    $transaction
                ) ? $transaction['payment_method_details'] : []
            ),
            array_key_exists('id', $transaction) ? Uuid::fromString($transaction['id']) : null,
            array_key_exists('created', $transaction) ? new Carbon($transaction['created']) : null,
            array_key_exists('modified', $transaction) ? new Carbon($transaction['modified']) : null,
            array_key_exists('completed', $transaction) ? new Carbon($transaction['completed']) : null,
            array_key_exists('status', $transaction) ? TransactionStatus::fromString($transaction['status']) : null,
            array_key_exists('reason', $transaction) ? Reason::fromString($transaction['reason']) : null,
            array_key_exists('currency', $transaction) ? Currency::fromString($transaction['currency']) : null,
            array_key_exists('amount', $transaction)
                ? TransactionAmount::fromInteger($transaction['amount'])
                : null,
            array_key_exists('expiration_period', $transaction)
                ? new \DateInterval($transaction['expiration_period'])
                : null,
            array_key_exists('description', $transaction)
                ? TransactionDescription::fromString($transaction['description'])
                : null,
            array_key_exists('balance', $transaction) ? Balance::fromString($transaction['balance']) : null,
            array_key_exists('payment_url', $transaction) ? Url::fromString($transaction['payment_url']) : null
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'payment_method' => $this->getPaymentMethod(),
            'payment_method_details' => $this->getPaymentMethodDetails(),
            'id' => $this->getId(),
            'created' => $this->getCreated(),
            'modified' => $this->getModified(),
            'completed' => $this->getCompleted(),
            'status' => $this->getStatus(),
            'reason' => $this->getReason(),
            'currency' => $this->getCurrency(),
            'amount' => $this->getAmount(),
            'expiration_period' => $this->getExpirationPeriod(),
            'description' => $this->getDescription(),
            'balance' => $this->getBalance(),
            'payment_url' => $this->getPaymentUrl(),
        ];
    }

    /**
     * @return TransactionStatus|null
     */
    public function getStatus()
    {
        return (null !== $this->status()) ? $this->status()->toString() : null;
    }

    /**
     * @return Reason|null
     */
    public function getReason()
    {
        return (null !== $this->reason()) ? $this->reason()->toString() : null;
    }

    /**
     * @return Url|null
     */
    public function getPaymentUrl()
    {
        return (null !== $this->paymentUrl()) ? $this->paymentUrl()->toString() : null;
    }

    /**
     * @return PaymentMethodDetails
     */
    public function getPaymentMethodDetails()
    {
        return $this->paymentMethodDetails()->toArray();
    }

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod()->toString();
    }

    /**
     * @return Carbon|null
     */
    public function getModified()
    {
        return (null !== $this->modified()) ? $this->modified()->toIso8601String() : null;
    }

    /**
     * @return Uuid|null
     */
    public function getId()
    {
        return (null !== $this->id()) ? $this->id()->toString() : null;
    }

    /**
     * @return \DateInterval|null
     */
    public function getExpirationPeriod()
    {
        return (null !== $this->expirationPeriod())
            ? $this->expirationPeriod()->format('P%yY%mM%dDT%hH%iM%sS')
            : null;
    }

    /**
     * @return TransactionDescription|null
     */
    public function getDescription()
    {
        return (null !== $this->description()) ? $this->description()->toString() : null;
    }

    /**
     * @return TransactionAmount|null
     */
    public function getAmount()
    {
        return (null !== $this->amount()) ? $this->amount()->toInteger() : null;
    }

    /**
     * @return Balance|null
     */
    public function getBalance()
    {
        return (null !== $this->balance()) ? $this->balance()->toString() : null;
    }

    /**
     * @return Carbon|null
     */
    public function getCompleted()
    {
        return  (null !== $this->completed()) ? $this->completed()->toIso8601String() : null;
    }

    /**
     * @return Carbon|null
     */
    public function getCreated()
    {
        return (null !== $this->created()) ? $this->created()->toIso8601String() : null;
    }

    /**
     * @return Currency|null
     */
    public function getCurrency()
    {
        return (null !== $this->currency()) ? $this->currency()->toString() : null;
    }

    /**
     * @return Uuid|null
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Carbon|null
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * @return Carbon|null
     */
    public function modified()
    {
        return $this->modified;
    }

    /**
     * @return Carbon|null
     */
    public function completed()
    {
        return $this->completed;
    }

    /**
     * @return TransactionStatus|null
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @return Reason|null
     */
    public function reason()
    {
        return $this->reason;
    }

    /**
     * @return Currency|null
     */
    public function currency()
    {
        return $this->currency;
    }

    /**
     * @return Amount|null
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return \DateInterval|null
     */
    public function expirationPeriod()
    {
        return $this->expirationPeriod;
    }

    /**
     * @return Description|null
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @return Balance|null
     */
    public function balance()
    {
        return $this->balance;
    }

    /**
     * @return PaymentMethod
     */
    public function paymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return PaymentMethodDetails
     */
    public function paymentMethodDetails()
    {
        return $this->paymentMethodDetails;
    }

    /**
     * @return Url|null
     */
    public function paymentUrl()
    {
        return $this->paymentUrl;
    }

    /**
     * @param Uuid                   $id
     * @param Carbon                 $created
     * @param Carbon                 $modified
     * @param Carbon                 $completed
     * @param TransactionStatus      $status
     * @param Reason                 $reason
     * @param Currency               $currency
     * @param TransactionAmount      $amount
     * @param \DateInterval          $expirationPeriod
     * @param TransactionDescription $description
     * @param Balance                $balance
     * @param Url                    $paymentUrl
     */
    private function __construct(
        PaymentMethod $paymentMethod,
        PaymentMethodDetails $paymentMethodDetails,
        Uuid $id = null,
        Carbon $created = null,
        Carbon $modified = null,
        Carbon $completed = null,
        TransactionStatus $status = null,
        Reason $reason = null,
        Currency $currency = null,
        TransactionAmount $amount = null,
        \DateInterval $expirationPeriod = null,
        TransactionDescription $description = null,
        Balance $balance = null,
        Url $paymentUrl = null
    ) {
        $this->paymentMethod = $paymentMethod;
        $this->paymentMethodDetails = $paymentMethodDetails;
        $this->id = $id;
        $this->created = $created;
        $this->modified = $modified;
        $this->completed = $completed;
        $this->status = $status;
        $this->reason = $reason;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->expirationPeriod = $expirationPeriod;
        $this->description = $description;
        $this->balance = $balance;
        $this->paymentUrl = $paymentUrl;
    }
}
