<?php

namespace GingerPayments\Payment;

use GingerPayments\Payment\Client\ClientException;
use GingerPayments\Payment\Client\OrderNotFoundException;
use GingerPayments\Payment\Common\ArrayFunctions;
use GingerPayments\Payment\Ideal\Issuers;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

final class Client
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Set httpClient default SSL validation using cURL CA bundle.
     * http://curl.haxx.se/docs/caextract.html.
     *
     * @return void
     */
    public function useBundledCA()
    {
        $this->httpClient->setDefaultOption(
            'verify',
            realpath(dirname(__FILE__).'/../assets/cacert.pem')
        );
    }

    /**
     * Get possible iDEAL issuers.
     *
     * @return Issuers
     */
    public function getIdealIssuers()
    {
        try {
            return Issuers::fromArray(
                $this->httpClient->get('ideal/issuers/')->json()
            );
        } catch (RequestException $exception) {
            throw new ClientException('An error occurred while processing the request: '.$exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Get allowed payment methods.
     *
     * @return array
     */
    public function getAllowedProducts()
    {
        try {
            return $this->processProducts(
                $this->httpClient->get('merchants/self/projects/self/')->json()
            );
        } catch (RequestException $exception) {
            return [];
        }
    }

    /**
     * Process the API response with allowed payment methods.
     *
     * @param array $details
     *
     * @return array
     */
    private function processProducts($details)
    {
        $result = [];

        if (!array_key_exists('permissions', $details)) {
            return $result;
        }

        if (array_key_exists('status', $details)
            && 'active-testing' == $details['status']) {
            return ['ideal'];
        }

        $products_to_check = [
            'ideal' => 'ideal',
            'bank-transfer' => 'banktransfer',
            'bancontact' => 'bancontact',
            'cash-on-delivery' => 'cashondelivery',
            'credit-card' => 'creditcard',
        ];

        foreach ($products_to_check as $permission_id => $id) {
            if (array_key_exists('/payment-methods/'.$permission_id.'/', $details['permissions']) &&
                array_key_exists('POST', $details['permissions']['/payment-methods/'.$permission_id.'/']) &&
                $details['permissions']['/payment-methods/'.$permission_id.'/']['POST']
            ) {
                $result[] = $id;
            }
        }

        return $result;
    }

    /**
     * Check if account is in test mode.
     *
     * @return bool
     */
    public function isInTestMode()
    {
        try {
            return $this->isTestMode(
                $this->httpClient->get('merchants/self/projects/self/')->json()
            );
        } catch (RequestException $exception) {
            throw new ClientException('An error occurred while processing the request: '.$exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Process test-mode API response.
     *
     * @param array $projectDetails
     *
     * @return bool
     */
    private function isTestMode($projectDetails)
    {
        if (!array_key_exists('status', $projectDetails)) {
            return false;
        }

        return 'active-testing' == $projectDetails['status'];
    }

    /**
     * Create a new iDEAL order.
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
     *
     * @return Order the newly created order
     */
    public function createIdealOrder(
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
        return $this->postOrder(
            Order::createWithIdeal(
                $amount,
                $currency,
                $issuerId,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new SEPA order.
     *
     * @param int    $amount               amount in cents
     * @param string $currency             a valid currency code
     * @param array  $paymentMethodDetails an array of extra payment method details
     * @param string $description          a description of the order
     * @param string $merchantOrderId      a merchant-defined order identifier
     * @param string $returnUrl            the return URL
     * @param string $expirationPeriod     The expiration period as an ISO 8601 duration
     * @param array  $customer             customer information
     * @param array  $extra                extra information
     *
     * @return Order the newly created order
     */
    public function createSepaOrder(
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
        return $this->postOrder(
            Order::createWithSepa(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new SOFORT order.
     *
     * @param int    $amount               amount in cents
     * @param string $currency             a valid currency code
     * @param array  $paymentMethodDetails an array of extra payment method details
     * @param string $description          a description of the order
     * @param string $merchantOrderId      a merchant-defined order identifier
     * @param string $returnUrl            the return URL
     * @param string $expirationPeriod     The expiration period as an ISO 8601 duration
     * @param array  $customer             customer information
     * @param array  $extra                extra information
     *
     * @return Order the newly created order
     */
    public function createSofortOrder(
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
        return $this->postOrder(
            Order::createWithSofort(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new credit card order.
     *
     * @param int    $amount           amount in cents
     * @param string $currency         a valid currency code
     * @param string $description      a description of the order
     * @param string $merchantOrderId  a merchant-defined order identifier
     * @param string $returnUrl        the return URL
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array  $customer         customer information
     * @param array  $extra            extra information
     *
     * @return Order the newly created order
     */
    public function createCreditCardOrder(
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
        return $this->postOrder(
            Order::createWithCreditCard(
                $amount,
                $currency,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new Bancontact order.
     *
     * @param int    $amount           amount in cents
     * @param string $currency         a valid currency code
     * @param string $description      a description of the order
     * @param string $merchantOrderId  a merchant-defined order identifier
     * @param string $returnUrl        the return URL
     * @param string $expirationPeriod The expiration period as an ISO 8601 duration
     * @param array  $customer         customer information
     * @param array  $extra            extra information
     *
     * @return Order the newly created order
     */
    public function createBancontactOrder(
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
        return $this->postOrder(
            Order::createWithBancontact(
                $amount,
                $currency,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new Cash On Delivery/Corporate Account/ Cheque Payment order.
     *
     * @param int    $amount               amount in cents
     * @param string $currency             a valid currency code
     * @param array  $paymentMethodDetails an array of extra payment method details
     * @param string $description          a description of the order
     * @param string $merchantOrderId      a merchant-defined order identifier
     * @param string $returnUrl            the return URL
     * @param string $expirationPeriod     The expiration period as an ISO 8601 duration
     * @param array  $customer             customer information
     * @param array  $extra                extra information
     * @param string $webhookUrl           the webhook URL
     *
     * @return Order the newly created order
     */
    public function createCashOnDeliveryOrder(
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
        return $this->postOrder(
            Order::createWithCod(
                $amount,
                $currency,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Create a new order.
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
     * @return Order the newly created order
     */
    public function createOrder(
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
        return $this->postOrder(
            Order::create(
                $amount,
                $currency,
                $paymentMethod,
                $paymentMethodDetails,
                $description,
                $merchantOrderId,
                $returnUrl,
                $expirationPeriod,
                $customer,
                $extra,
                $webhookUrl
            )
        );
    }

    /**
     * Get a single order.
     *
     * @param string $id the order ID
     *
     * @return Order
     */
    public function getOrder($id)
    {
        try {
            return Order::fromArray(
                $this->httpClient->get("orders/$id")->json()
            );
        } catch (RequestException $exception) {
            if (404 == $exception->getCode()) {
                throw new OrderNotFoundException('No order with that ID was found.', 404, $exception);
            }
            throw new ClientException('An error occurred while getting the order: '.$exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Update an existing order.
     *
     * @return Order
     */
    public function updateOrder(Order $order)
    {
        return $this->putOrder($order);
    }

    /**
     * Post a new order.
     *
     * @return Order
     */
    private function postOrder(Order $order)
    {
        try {
            $response = $this->httpClient->post(
                'orders/',
                [
                    'timeout' => 30,
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => json_encode(
                        ArrayFunctions::withoutNullValues($order->toArray())
                    ),
                ]
            );
        } catch (RequestException $exception) {
            throw new ClientException('An error occurred while posting the order: '.$exception->getMessage(), $exception->getCode(), $exception);
        }

        return Order::fromArray($response->json());
    }

    /**
     * PUT order data to Ginger API.
     *
     * @return Order
     */
    private function putOrder(Order $order)
    {
        try {
            return Order::fromArray(
                $this->httpClient->put(
                    'orders/'.$order->id().'/',
                    [
                        'timeout' => 3,
                        'json' => ArrayFunctions::withoutNullValues($order->toArray()),
                    ]
                )->json()
            );
        } catch (RequestException $exception) {
            if (404 == $exception->getCode()) {
                throw new OrderNotFoundException('No order with that ID was found.', 404, $exception);
            }
            throw new ClientException('An error occurred while updating the order: '.$exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
