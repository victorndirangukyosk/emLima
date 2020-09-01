<?php

/**
 * Class IngHelper.
 */
class IngHelper
{
    /**
     * Default currency for Order.
     */
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * ING PSP Order statuses.
     */
    const ING_STATUS_EXPIRED = 'expired';
    const ING_STATUS_NEW = 'new';
    const ING_STATUS_PROCESSING = 'processing';
    const ING_STATUS_COMPLETED = 'completed';
    const ING_STATUS_CANCELLED = 'cancelled';
    const ING_STATUS_ERROR = 'error';

    /**
     * @param string $paymentMethod
     */
    public function __construct($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @param object $config
     *
     * @return \GingerPayments\Payment\Client
     */
    public function getClient($config)
    {
        require_once DIR_SYSTEM.'library/ingpsp/ing-php/vendor/autoload.php';

        $ing = \GingerPayments\Payment\Ginger::createClient(
            $config->get($this->getPaymentSettingsFieldName('api_key')),
            $config->get($this->getPaymentSettingsFieldName('psp_product'))
        );

        if ($config->get($this->getPaymentSettingsFieldName('bundle_cacert'))) {
            $ing->useBundledCA();
        }

        return $ing;
    }

    /**
     * Method maps ING PSP order status to OpenCart specific.
     *
     * @param string $ingOrderStatus
     *
     * @return string
     */
    public function getOrderStatus($ingOrderStatus, $config)
    {
        switch ($ingOrderStatus) {
            case IngHelper::ING_STATUS_NEW:
                $orderStatus = $config->get($this->getPaymentSettingsFieldName('order_status_id_new'));
                break;
            case IngHelper::ING_STATUS_EXPIRED:
                $orderStatus = $config->get($this->getPaymentSettingsFieldName('order_status_id_expired'));
                break;
            case IngHelper::ING_STATUS_PROCESSING:
                $orderStatus = $config->get($this->getPaymentSettingsFieldName('order_status_id_processing'));
                break;
            case IngHelper::ING_STATUS_COMPLETED:
                $orderStatus = $config->get($this->getPaymentSettingsFieldName('order_status_id_completed'));
                break;
            case IngHelper::ING_STATUS_CANCELLED:
                $orderStatus = $config->get($this->getPaymentSettingsFieldName('order_status_id_cancelled'));
                break;
            case IngHelper::ING_STATUS_ERROR:
                $orderStatus = $config->get($this->getPaymentSettingsFieldName('order_status_id_error'));
                break;
            default:
                $orderStatus = $config->get($this->getPaymentSettingsFieldName('order_status_id_new'));
                break;
        }

        return $orderStatus;
    }

    /**
     * @return array
     */
    public function getCustomerInformation(array $orderInfo)
    {
        // $data['payer']['address']['street'] = $order_info['shipping_address'];
        // $data['payer']['address']['number'] = isset($order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')]) ? $order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')] : 0;
        // $data['payer']['address']['city'] = $order_info['shipping_city'];
        // /*$data['payer']['address']['state'] = $order_info['payment_zone_code'];
        // $data['payer']['address']['country'] = $order_info['payment_country'];*/
        // $data['payer']['address']['zip_code'] = $order_info['shipping_zipcode'];

        $customer = [
            'address_type' => 'customer',
            //'country' => $orderInfo['payment_iso_code_2'],
            'email_address' => $orderInfo['email'],
            'first_name' => $orderInfo['firstname'],
            'last_name' => $orderInfo['lastname'],
            'merchant_customer_id' => $orderInfo['customer_id'],
            'phone_numbers' => [$orderInfo['telephone']],
            'address' => implode("\n", array_filter([
                /*$orderInfo['payment_company'],
                $orderInfo['payment_address_1'],
                $orderInfo['payment_address_2'],
                $orderInfo['payment_firstname']." ".$orderInfo['payment_lastname'],
                $orderInfo['payment_postcode']." ".$orderInfo['payment_city']*/
                $orderInfo['shipping_address'],
                /*$orderInfo['shipping_address'],
                $orderInfo['shipping_address'],*/
                $orderInfo['firstname'].' '.$orderInfo['lastname'],
                $orderInfo['shipping_city'],
            ])),

            'locale' => self::formatLocale($orderInfo['language_code']),
        ];

        return $customer;
    }

    /**
     * @param object $language
     *
     * @return string
     */
    public function getOrderDescription(array $orderInfo, $language)
    {
        $language->load('payment/'.$this->paymentMethod);

        return $language->get('text_transaction').$orderInfo['order_id'];
    }

    /**
     * @param array  $orderInfo
     * @param object $currency
     *
     * @return int
     */
    public function getAmountInCents($orderInfo, $currency)
    {
        $amount = $currency->format(
            $orderInfo['total'],
            $orderInfo['currency_code'],
            $orderInfo['currency_value'],
            false
        );

        return (int) (100 * round($amount, 2, PHP_ROUND_HALF_UP));
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    public function getPaymentSettingsFieldName($fieldName)
    {
        return $this->paymentMethod.'_'.$fieldName;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return static::DEFAULT_CURRENCY;
    }

    /**
     * @param string $locale
     *
     * @return mixed
     */
    public function formatLocale($locale)
    {
        return strstr($locale, '-', true);
    }

    /**
     * @param object $paymentMethod
     *
     * @return array
     */
    public function getOrderData(array $orderInfo, $paymentMethod)
    {
        $webhookUrl = $paymentMethod->config->get($this->getPaymentSettingsFieldName('send_webhook'))
            ? $paymentMethod->url->link('payment/'.$this->paymentMethod.'/webhook') : null;

        $issuerId = array_key_exists('issuer_id', $paymentMethod->request->post)
            ? $paymentMethod->request->post['issuer_id'] : null;

        return [
            'amount' => $this->getAmountInCents($orderInfo, $paymentMethod->currency),
            'currency' => $this->getCurrency(),
            'merchant_order_id' => $orderInfo['order_id'],
            'return_url' => $paymentMethod->url->link('payment/'.$this->paymentMethod.'/callback'),
            'description' => $this->getOrderDescription($orderInfo, $paymentMethod->language),
            'customer' => $this->getCustomerInformation($orderInfo),
            'issuer_id' => $issuerId,
            'webhook_url' => $webhookUrl,
            'payment_info' => [],
        ];
    }

    /**
     * Method processes calls to webhook url.
     *
     * @param object $paymentMethod
     *
     * @return void
     */
    public function processWebhook($paymentMethod, array $webhookData)
    {
        if ('status_changed' == $webhookData['event']) {
            $ingOrder = $paymentMethod->ing->getOrder($webhookData['order_id']);
            $orderInfo = $paymentMethod->model_checkout_order->getOrder($ingOrder->getMerchantOrderId());
            if ($orderInfo) {
                $paymentMethod->model_checkout_order->addOrderHistory(
                    $ingOrder->getMerchantOrderId(),
                    $paymentMethod->ingHelper->getOrderStatus($ingOrder->getStatus(), $paymentMethod->config),
                    'Status changed for order: '.$ingOrder->id()->toString(),
                    true
                );
            }
        }
    }

    /**
     * Method prepares Ajax response for processing page.
     *
     * @param object $paymentMethod
     */
    public function checkStatusAjax($paymentMethod)
    {
        $orderId = $paymentMethod->request->get['order_id'];
        $ingOrder = $paymentMethod->ing->getOrder($orderId);

        if ($ingOrder->status()->isProcessing()
            || $ingOrder->status()->isNew()
        ) {
            $response = [
                'redirect' => false,
            ];
        } else {
            $response = [
                'redirect' => true,
            ];
        }

        die(json_encode($response));
    }

    /**
     * @param object $paymentMethod
     *
     * @return mixed
     */
    public function loadProcessingPage($paymentMethod)
    {
        if (isset($paymentMethod->request->post['processing'])) {
            $this->checkStatusAjax($paymentMethod);
        }

        return $paymentMethod->response->setOutput(
            $paymentMethod->load->view(
                'payment/ingpsp_processing',
                $this->getPageData($paymentMethod)
            )
        );
    }

    /**
     * @param object $paymentMethod
     *
     * @return mixed
     */
    public function loadPendingPage($paymentMethod)
    {
        return $paymentMethod->response->setOutput(
            $paymentMethod->load->view(
                'payment/ingpsp_pending',
                $this->getPageData($paymentMethod)
            )
        );
    }

    /**
     * @param $paymentMethod
     *
     * @return array
     */
    public function getPageData($paymentMethod)
    {
        $paymentMethod->load->language('payment/'.$this->paymentMethod);
        $paymentMethod->load->language('checkout/success');

        return [
            'breadcrumbs' => $this->getBreadcrumbs($paymentMethod),
            'fallback_url' => $this->getPendingUrl($paymentMethod),
            'callback_url' => $this->getCallbackUrl($paymentMethod),

            'header' => $paymentMethod->load->controller('common/header'),
            'footer' => $paymentMethod->load->controller('common/footer'),
            'column_left' => $paymentMethod->load->controller('common/column_left'),
            'column_right' => $paymentMethod->load->controller('common/column_right'),
            'content_top' => $paymentMethod->load->controller('common/content_top'),
            'content_bottom' => $paymentMethod->load->controller('common/content_bottom'),

            'text_processing' => $paymentMethod->language->get('text_processing'),
            'processing_message' => $paymentMethod->language->get('processing_message'),
            'pending_text' => $paymentMethod->language->get('pending_text'),
            'pending_message' => $paymentMethod->language->get('pending_message'),
            'pending_message_sub' => $paymentMethod->language->get('pending_message_sub'),
            'button_continue' => $paymentMethod->language->get('button_continue'),

            'continue' => $paymentMethod->url->link('common/home'),
        ];
    }

    /**
     * @param $paymentMethod
     */
    public function loadCallbackFunction($paymentMethod)
    {
        $paymentMethod->load->model('checkout/order');
        $ingOrder = $paymentMethod->ing->getOrder($paymentMethod->request->get['order_id']);
        $orderInfo = $paymentMethod->model_checkout_order->getOrder($ingOrder->getMerchantOrderId());
        if ($orderInfo) {
            $paymentMethod->model_checkout_order->addOrderHistory(
                $ingOrder->getMerchantOrderId(),
                $paymentMethod->ingHelper->getOrderStatus($ingOrder->getStatus(), $paymentMethod->config),
                'ING PSP order: '.$ingOrder->id()->toString(),
                true
            );
            if ($ingOrder->status()->isCompleted()) {
                $paymentMethod->response->redirect($paymentMethod->url->link('checkout/success'));
            } elseif ($ingOrder->status()->isProcessing() || $ingOrder->status()->isNew()) {
                $paymentMethod->response->redirect($paymentMethod->ingHelper->getProcessingUrl($paymentMethod));
            } else {
                $paymentMethod->response->redirect($paymentMethod->url->link('checkout/failure'));
            }
        }
    }

    /**
     * @param $paymentMethod
     *
     * @return array
     */
    public function getBreadcrumbs($paymentMethod)
    {
        return [
            [
                'text' => $paymentMethod->language->get('text_home'),
                'href' => $paymentMethod->url->link('common/home'),
            ],
            [
                'text' => $paymentMethod->language->get('text_basket'),
                'href' => $paymentMethod->url->link('checkout/cart'),
            ],
            [
                'text' => $paymentMethod->language->get('text_checkout'),
                'href' => $paymentMethod->url->link('checkout/checkout', '', true),
            ],
        ];
    }

    /**
     * @param $paymentMethod
     *
     * @return string
     */
    public function getCallbackUrl($paymentMethod)
    {
        return htmlspecialchars_decode(
            $paymentMethod->url->link(
                'payment/'.$this->paymentMethod.'/callback',
                ['order_id' => $paymentMethod->request->get['order_id']]
            )
        );
    }

    /**
     * @param $paymentMethod
     *
     * @return string
     */
    public function getProcessingUrl($paymentMethod)
    {
        return htmlspecialchars_decode(
            $paymentMethod->url->link(
                'payment/'.$this->paymentMethod.'/processing',
                ['order_id' => $paymentMethod->request->get['order_id']]
            )
        );
    }

    /**
     * @param $paymentMethod
     *
     * @return string
     */
    public function getPendingUrl($paymentMethod)
    {
        return htmlspecialchars_decode(
            $paymentMethod->url->link(
                'payment/'.$this->paymentMethod.'/pending',
                ['order_id' => $paymentMethod->request->get['order_id']]
            )
        );
    }
}
