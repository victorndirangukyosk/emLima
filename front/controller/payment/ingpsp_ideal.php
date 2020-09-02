<?php

/**
 * Class ControllerExtensionPaymentIngpspIdeal.
 */
class ControllerPaymentIngpspIdeal extends Controller
{
    /**
     * Default currency for Order.
     */
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * Payments module name.
     */
    const MODULE_NAME = 'ingpsp_ideal';

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

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['text_select_bank'] = $this->language->get('text_select_bank');
        $data['issuers'] = $this->ing->getIdealIssuers();
        $data['action'] = $this->url->link('payment/'.static::MODULE_NAME.'/confirm');

        return $this->load->view('extension/payment/'.static::MODULE_NAME, $data);
    }

    /**
     * Order Confirm Action.
     */
    public function confirm()
    {
        try {
            $this->load->model('checkout/order');
            $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);

            if ($orderInfo) {
                $ingOrderData = $this->ingHelper->getOrderData($orderInfo, $this);
                $ingOrder = $this->createOrder($ingOrderData);
                $checkoutUrl = $ingOrder->firstTransactionPaymentUrl();
                $this->response->redirect($checkoutUrl);
            }
        } catch (\Exception $e) {
            $this->session->data['error'] = $e->getMessage();
            $this->response->redirect($this->url->link('checkout/checkout'));
        }
    }

    /**
     * Callback Action.
     */
    public function callback()
    {
        $this->ingHelper->loadCallbackFunction($this);
    }

    /**
     * Pending order processing page.
     *
     * @return mixed
     */
    public function processing()
    {
        return $this->ingHelper->loadProcessingPage($this);
    }

    /**
     * Pending order processing page.
     *
     * @return mixed
     */
    public function pending()
    {
        $this->cart->clear();

        return $this->ingHelper->loadPendingPage($this);
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

    /**
     * Generate ING PSP iDEAL order.
     *
     * @param array
     *
     * @return \GingerPayments\Payment\Order
     */
    protected function createOrder(array $orderData)
    {
        return $this->ing->createIdealOrder(
            $orderData['amount'],            // Amount in cents
            $orderData['currency'],          // Currency
            $orderData['issuer_id'],         // Issuer ID (BIC/SWIFT)
            $orderData['description'],       // Description
            $orderData['merchant_order_id'], // Merchant Order Id
            $orderData['return_url'],        // Return URL
            null,                            // Expiration Period
            $orderData['customer'],          // Customer information
            null,                            // Extra information
            $orderData['webhook_url']        // Webhook URL
        );
    }
}
