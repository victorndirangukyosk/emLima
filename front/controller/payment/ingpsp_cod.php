<?php

/**
 * Class ControllerExtensionPaymentIngpspCod.
 */
class ControllerPaymentIngpspCod extends Controller
{
    /**
     * Default currency for Order.
     */
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * Payments module name.
     */
    const MODULE_NAME = 'ingpsp_cod';

    /**
     * @var \GingerPayments\Payment\Client
     */
    public $ing;

    /**
     * @var IngHelper
     */
    public $ingHelper;

    /**
     * @param $registry
     */
    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->ingHelper = new IngHelper(static::MODULE_NAME);
        $this->ing = $this->ingHelper->getClient($this->config);
    }

    /**
     * Index Action.
     *
     * @return mixed
     */
    public function index()
    {
        $this->language->load('payment/'.static::MODULE_NAME);
        $this->load->model('checkout/order');
        $data = [];

        try {
            $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);

            if ($orderInfo) {
                $ingOrderData = $this->ingHelper->getOrderData($orderInfo, $this);
                $ingOrder = $this->createOrder($ingOrderData);

                $this->model_checkout_order->addOrderHistory(
                    $ingOrder->getMerchantOrderId(),
                    $this->ingHelper->getOrderStatus($ingOrder->getStatus(), $this->config),
                    'ING PSP Cash On Delivery order: '.$ingOrder->id()->toString(),
                    true
                );

                $data['button_confirm'] = $this->language->get('button_confirm');
                $data['text_description'] = $this->language->get('text_description');
                $data['action'] = $this->url->link('checkout/success');
            }
        } catch (\Exception $e) {
            $this->session->data['error'] = $e->getMessage();
        }

        return $this->load->view('extension/payment/'.static::MODULE_NAME, $data);
    }

    /**
     * Generate ING PSP Payments order.
     *
     * @param array
     *
     * @return \GingerPayments\Payment\Order
     */
    protected function createOrder(array $orderData)
    {
        return $this->ing->createCashOnDeliveryOrder(
            $orderData['amount'],            // Amount in cents
            $orderData['currency'],          // Currency
            $orderData['payment_info'],      // Payment information
            $orderData['description'],       // Description
            $orderData['merchant_order_id'], // Merchant Order Id
            $orderData['return_url'],        // Return URL
            null,                            // Expiration Period
            $orderData['customer'],          // Customer information
            null,                            // Extra information
            $orderData['webhook_url']        // Webhook URL
        );
    }

    /**
     * Webhook action is called by API when transaction status is updated.
     *
     * @return void
     */
    public function webhook()
    {
        $this->load->model('checkout/order');
        $webhookData = json_decode(file_get_contents('php://input'), true);
        $this->ingHelper->processWebhook($this, $webhookData);
    }
}
