<?php

namespace GingerPayments\Payment;

use Assert\Assertion as Guard;
use Carbon\Carbon;
use GingerPayments\Payment\Order\Amount;
use GingerPayments\Payment\Order\Customer;
use GingerPayments\Payment\Order\Description;
use GingerPayments\Payment\Order\Extra;
use GingerPayments\Payment\Order\MerchantOrderId;
use GingerPayments\Payment\Order\Status;
use GingerPayments\Payment\Order\Transaction\PaymentMethod;
use GingerPayments\Payment\Order\Transactions;
use Rhumsaa\Uuid\Uuid;

final class Order
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
     * @var MerchantOrderId|null
     */
    private $merchantOrderId;

    /**
     * @var Uuid|null
     */
    private $projectId;

    /**
     * @var Status|null
     */
    private $status;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var Amount
     */
    private $amount;

    /**
     * @var \DateInterval|null
     */
    private $expirationPeriod;

    /**
     * @var Description|null
     */
    private $description;

    /**
     * @var Url|null
     */
    private $returnUrl;

    /**
     * @var Transactions
     */
    private $transactions;

    /**
     * @var Customer|null
     */
    private $customer;

    /**
     * Used for adding extra information to the order.
     *
     * @var Extra|null
     */
    private $extra;

    /**
     * Webhook URL is used for transaction information updates.
     *
     * @var Url|null
     */
    private $webhookUrl;

    /**
     * Create a new Order with the iDEAL payment method.
     *
     * @param int    $amount           amount in cents
     * @param string $currency         a valid currency code
     * @param string $issuerId         the SWIFT/BIC code of the iDEAL issuer
     * @param string $description      a description of the order
     * @param string $merchantOrderId  a merchant-defined order identifier
     * @param string $returnUrl        the return URL
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array  $customer         customer information
     * @param array  $extra            extra information
     * @param string $webhookUrl       the webhook URL
     *
     * @return Order
     */
    public static function createWithIdeal(
        $amount,
        $currency,
        $issuerId,
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return static::create(
            $amount,
            $currency,
            PaymentMethod::IDEAL,
            ['issuer_id' => $issuerId],
            $description,
            $merchantOrderId,
            $returnUrl,
            $expirationPeriod,
            $customer,
            $extra,
            $webhookUrl
        );
    }

    /**
     * Create a new Order with the credit card payment method.
     *
     * @param int    $amount           amount in cents
     * @param string $currency         a valid currency code
     * @param string $description      a description of the order
     * @param string $merchantOrderId  a merchant-defined order identifier
     * @param string $returnUrl        the return URL
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array  $customer         customer information
     * @param array  $extra            extra information
     * @param string $webhookUrl       the webhook URL
     *
     * @return Order
     */
    public static function createWithCreditCard(
        $amount,
        $currency,
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return static::create(
            $amount,
            $currency,
            PaymentMethod::CREDIT_CARD,
            [],
            $description,
            $merchantOrderId,
            $returnUrl,
            $expirationPeriod,
            $customer,
            $extra,
            $webhookUrl
        );
    }

    /**
     * Create a new Order with the SEPA payment method.
     *
     * @param int    $amount               amount in cents
     * @param string $currency             a valid currency code
     * @param array  $paymentMethodDetails an array of extra payment method details
     * @param string $description          a description of the order
     * @param string $merchantOrderId      a merchant-defined order identifier
     * @param string $returnUrl            the return URL
     * @param string $expirationPeriod     the expiration period as an ISO 8601 duration
     * @param array  $customer             Customer information
     * @param array  $extra                extra information
     * @param string $webhookUrl           the webhook URL
     *
     * @return Order
     */
    public static function createWithSepa(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return static::create(
            $amount,
            $currency,
            PaymentMethod::BANK_TRANSFER,
            $paymentMethodDetails,
            $description,
            $merchantOrderId,
            $returnUrl,
            $expirationPeriod,
            $customer,
            $extra,
            $webhookUrl
        );
    }

    /**
     * Create a new Order with the SOFORT payment method.
     *
     * @param int    $amount               amount in cents
     * @param string $currency             a valid currency code
     * @param array  $paymentMethodDetails an array of extra payment method details
     * @param string $description          a description of the order
     * @param string $merchantOrderId      a merchant-defined order identifier
     * @param string $returnUrl            the return URL
     * @param string $expirationPeriod     the expiration period as an ISO 8601 duration
     * @param array  $customer             customer information
     * @param array  $extra                extra information
     * @param string $webhookUrl           the webhook URL
     *
     * @return Order
     */
    public static function createWithSofort(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return static::create(
            $amount,
            $currency,
            PaymentMethod::SOFORT,
            $paymentMethodDetails,
            $description,
            $merchantOrderId,
            $returnUrl,
            $expirationPeriod,
            $customer,
            $extra,
            $webhookUrl
        );
    }

    /**
     * Create a new Order with the Bancontact payment method.
     *
     * @param int    $amount           amount in cents
     * @param string $currency         a valid currency code
     * @param string $description      a description of the order
     * @param string $merchantOrderId  a merchant-defined order identifier
     * @param string $returnUrl        the return URL
     * @param string $expirationPeriod the expiration period as an ISO 8601 duration
     * @param array  $customer         customer information
     * @param array  $extra            extra information
     * @param string $webhookUrl       the webhook URL
     *
     * @return Order
     */
    public static function createWithBancontact(
        $amount,
        $currency,
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return static::create(
            $amount,
            $currency,
            PaymentMethod::BANCONTACT,
            [],
            $description,
            $merchantOrderId,
            $returnUrl,
            $expirationPeriod,
            $customer,
            $extra,
            $webhookUrl
        );
    }

    /**
     * @param int    $amount               amount in cents
     * @param string $currency             a valid currency code
     * @param array  $paymentMethodDetails an array of extra payment method details
     * @param string $description          a description of the order
     * @param string $merchantOrderId      a merchant-defined order identifier
     * @param string $returnUrl            the return URL
     * @param string $expirationPeriod     the expiration period as an ISO 8601 duration
     * @param array  $customer             Customer information
     * @param array  $extra                extra information
     * @param string $webhookUrl           the webhook URL
     *
     * @return Order
     */
    public static function createWithCod(
        $amount,
        $currency,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return static::create(
            $amount,
            $currency,
            PaymentMethod::COD,
            $paymentMethodDetails,
            $description,
            $merchantOrderId,
            $returnUrl,
            $expirationPeriod,
            $customer,
            $extra,
            $webhookUrl
        );
    }

    /**
     * Create a new Order.
     *
     * @param int    $amount               amount in cents
     * @param string $currency             a valid currency code
     * @param string $paymentMethod        the payment method to use
     * @param array  $paymentMethodDetails an array of extra payment method details
     * @param string $description          a description of the order
     * @param string $merchantOrderId      a merchant-defined order identifier
     * @param string $returnUrl            the return URL
     * @param string $expirationPeriod     The expiration period as an ISO 8601 duration
     * @param array  $customer             customer information
     * @param array  $extra                extra information
     * @param string $webhookUrl           the webhook URL
     *
     * @return Order
     */
    public static function create(
        $amount,
        $currency,
        $paymentMethod,
        array $paymentMethodDetails = [],
        $description = null,
        $merchantOrderId = null,
        $returnUrl = null,
        $expirationPeriod = null,
        $customer = null,
        $extra = null,
        $webhookUrl = null
    ) {
        return new static(
            Transactions::fromArray(
                [
                    [
                        'payment_method' => $paymentMethod,
                        'payment_method_details' => $paymentMethodDetails,
                    ],
                ]
            ),
            Amount::fromInteger($amount),
            Currency::fromString($currency),
            (null !== $description) ? Description::fromString($description) : null,
            (null !== $merchantOrderId) ? MerchantOrderId::fromString($merchantOrderId) : null,
            (null !== $returnUrl) ? Url::fromString($returnUrl) : null,
            (null !== $expirationPeriod) ? new \DateInterval($expirationPeriod) : null,
            null,
            null,
            null,
            null,
            null,
            null,
            (null !== $customer) ? Customer::fromArray($customer) : null,
            (null !== $extra) ? Extra::fromArray($extra) : null,
            (null !== $webhookUrl) ? Url::fromString($webhookUrl) : null
        );
    }

    /**
     * @return Order
     */
    public static function fromArray(array $order)
    {
        Guard::keyExists($order, 'transactions');
        Guard::keyExists($order, 'amount');
        Guard::keyExists($order, 'currency');

        return new static(
            Transactions::fromArray($order['transactions']),
            Amount::fromInteger($order['amount']),
            Currency::fromString($order['currency']),
            array_key_exists('description', $order) ? Description::fromString($order['description']) : null,
            array_key_exists('merchant_order_id', $order)
                ? MerchantOrderId::fromString($order['merchant_order_id'])
                : null,
            array_key_exists('return_url', $order) ? Url::fromString($order['return_url']) : null,
            array_key_exists('expiration_period', $order) ? new \DateInterval($order['expiration_period']) : null,
            array_key_exists('id', $order) ? Uuid::fromString($order['id']) : null,
            array_key_exists('project_id', $order) ? Uuid::fromString($order['project_id']) : null,
            array_key_exists('created', $order) ? new Carbon($order['created']) : null,
            array_key_exists('modified', $order) ? new Carbon($order['modified']) : null,
            array_key_exists('completed', $order) ? new Carbon($order['completed']) : null,
            array_key_exists('status', $order) ? Status::fromString($order['status']) : null,
            array_key_exists('customer', $order) && null !== $order['customer']
                ? Customer::fromArray($order['customer']) : null,
            array_key_exists('extra', $order) && null !== $order['extra']
                ? Extra::fromArray($order['extra']) : null,
            array_key_exists('webhook_url', $order) ? Url::fromString($order['webhook_url']) : null
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'currency' => $this->getCurrency(),
            'amount' => $this->getAmount(),
            'transactions' => $this->getTransactions(),
            'id' => $this->getId(),
            'project_id' => $this->getProjectId(),
            'created' => $this->getCreated(),
            'modified' => $this->getModified(),
            'completed' => $this->getCompleted(),
            'expiration_period' => $this->getExpirationPeriod(),
            'merchant_order_id' => $this->getMerchantOrderId(),
            'status' => $this->getStatus(),
            'description' => $this->getDescription(),
            'return_url' => $this->getReturnUrl(),
            'customer' => $this->getCustomer(),
            'extra' => $this->getExtra(),
            'webhook_url' => $this->getWebhookUrl(),
        ];
    }

    /**
     * @return string|null
     */
    public function getWebhookUrl()
    {
        return (null !== $this->webhookUrl()) ? $this->webhookUrl()->toString() : null;
    }

    /**
     * @return array
     */
    public function getTransactions()
    {
        return $this->transactions()->toArray();
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency()->toString();
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount()->toInteger();
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return (null !== $this->id()) ? $this->id()->toString() : null;
    }

    /**
     * @return string|null
     */
    public function getProjectId()
    {
        return (null !== $this->projectId()) ? $this->projectId()->toString() : null;
    }

    /**
     * @return string|null
     */
    public function getCreated()
    {
        return (null !== $this->created()) ? $this->created()->toIso8601String() : null;
    }

    /**
     * @return string|null
     */
    public function getModified()
    {
        return (null !== $this->modified()) ? $this->modified()->toIso8601String() : null;
    }

    /**
     * @return string|null
     */
    public function getCompleted()
    {
        return (null !== $this->completed()) ? $this->completed()->toIso8601String() : null;
    }

    /**
     * @return string|null
     */
    public function getExpirationPeriod()
    {
        return (null !== $this->expirationPeriod())
            ? $this->expirationPeriod()->format('P%yY%mM%dDT%hH%iM%sS')
            : null;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return (null !== $this->description()) ? $this->description()->toString() : null;
    }

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return (null !== $this->status()) ? $this->status()->toString() : null;
    }

    /**
     * @return string|null
     */
    public function getMerchantOrderId()
    {
        return (null !== $this->merchantOrderId())
            ? $this->merchantOrderId()->toString()
            : null;
    }

    /**
     * @return string|null
     */
    public function getReturnUrl()
    {
        return (null !== $this->returnUrl()) ? $this->returnUrl()->toString() : null;
    }

    /**
     * @return array|null
     */
    public function getCustomer()
    {
        return (null !== $this->customer()) ? $this->customer()->toArray() : null;
    }

    /**
     * @return array|null
     */
    public function getExtra()
    {
        return (null !== $this->extra()) ? $this->extra()->toArray() : null;
    }

    /**
     * @return Extra|null
     */
    public function extra()
    {
        return $this->extra;
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
     * @return Uuid|null
     */
    public function projectId()
    {
        return $this->projectId;
    }

    /**
     * @param string $merchantOrderId
     *
     * @return MerchantOrderId|null
     */
    public function merchantOrderId($merchantOrderId = null)
    {
        if (null !== $merchantOrderId) {
            $this->merchantOrderId = MerchantOrderId::fromString($merchantOrderId);
        }

        return $this->merchantOrderId;
    }

    /**
     * @return Status|null
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @param string $currency
     *
     * @return Currency
     */
    public function currency($currency = null)
    {
        if (null !== $currency) {
            $this->currency = Currency::fromString($currency);
        }

        return $this->currency;
    }

    /**
     * @param int $amount
     *
     * @return Amount
     */
    public function amount($amount = null)
    {
        if (null !== $amount) {
            $this->amount = Amount::fromInteger($amount);
        }

        return $this->amount;
    }

    /**
     * Time interval (ISO 8601 / RFC 3339).
     *
     * @param string $expirationPeriod
     *
     * @return \DateInterval|null
     */
    public function expirationPeriod($expirationPeriod = null)
    {
        if (null !== $expirationPeriod) {
            $this->expirationPeriod = new \DateInterval($expirationPeriod);
        }

        return $this->expirationPeriod;
    }

    /**
     * @param string $description
     *
     * @return Description|null
     */
    public function description($description = null)
    {
        if (null !== $description) {
            $this->description = Description::fromString($description);
        }

        return $this->description;
    }

    /**
     * @param string $returnUrl
     *
     * @return Url|null
     */
    public function returnUrl($returnUrl = null)
    {
        if (null !== $returnUrl) {
            $this->returnUrl = Url::fromString($returnUrl);
        }

        return $this->returnUrl;
    }

    /**
     * @param string $webhookUrl
     *
     * @return Url|null
     */
    public function webhookUrl($webhookUrl = null)
    {
        if (null !== $webhookUrl) {
            $this->webhookUrl = Url::fromString($webhookUrl);
        }

        return $this->webhookUrl;
    }

    /**
     * @return Transactions
     */
    public function transactions()
    {
        return $this->transactions;
    }

    /**
     * @return Url|null
     */
    public function firstTransactionPaymentUrl()
    {
        return $this->transactions()->firstPaymentUrl();
    }

    /**
     * @return Customer|null
     */
    public function customer()
    {
        return $this->customer;
    }

    /**
     * @param Description     $description
     * @param MerchantOrderId $merchantOrderId
     * @param Url             $returnUrl
     * @param \DateInterval   $expirationPeriod
     * @param Uuid            $id
     * @param Uuid            $projectId
     * @param Carbon          $created
     * @param Carbon          $modified
     * @param Carbon          $completed
     * @param Status          $status
     * @param Customer        $customer
     * @param Extra           $extra
     * @param Url             $webhookUrl
     */
    private function __construct(
        Transactions $transactions,
        Amount $amount,
        Currency $currency,
        Description $description = null,
        MerchantOrderId $merchantOrderId = null,
        Url $returnUrl = null,
        \DateInterval $expirationPeriod = null,
        Uuid $id = null,
        Uuid $projectId = null,
        Carbon $created = null,
        Carbon $modified = null,
        Carbon $completed = null,
        Status $status = null,
        Customer $customer = null,
        Extra $extra = null,
        Url $webhookUrl = null
    ) {
        $this->transactions = $transactions;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->merchantOrderId = $merchantOrderId;
        $this->returnUrl = $returnUrl;
        $this->expirationPeriod = $expirationPeriod;
        $this->id = $id;
        $this->projectId = $projectId;
        $this->created = $created;
        $this->modified = $modified;
        $this->completed = $completed;
        $this->status = $status;
        $this->customer = $customer;
        $this->extra = $extra;
        $this->webhookUrl = $webhookUrl;
    }
}
