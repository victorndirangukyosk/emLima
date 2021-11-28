<?php

/**
 * Class ControllerPaymentIngpspCc.
 */
class ControllerPaymentIngpspCc extends Controller
{
    /**
     * Default currency for Order.
     */
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * Payments module name.
     */
    const MODULE_NAME = 'ingpsp_cc';

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

        $log = new Log('error.log');

        $log->write('constructor ingpsp');

        //$log->write($this->config);

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

        $data['continue'] = $this->url->link('checkout/success');
        $data['action'] = $this->url->link('payment/'.static::MODULE_NAME.'/confirm');

        //return $this->load->view('extension/payment/'.static::MODULE_NAME, $data);
        //return $this->load->view('default/template/payment/iugu_credit_card.tpl', $data);
        return $this->load->view('mvgv2/template/extension/payment/ingpsp_cc.tpl', $data);
    }

    /**
     * Order Confirm Action.
     */
    // public function confirm()
    // {
    //     try {
    //         $this->load->model('checkout/order');
    //         $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    //         if ($orderInfo) {
    //             $ingOrderData = $this->ingHelper->getOrderData($orderInfo, $this);
    //             $ingOrder = $this->createOrder($ingOrderData);
    //             $checkoutUrl = $ingOrder->firstTransactionPaymentUrl();
    //             $this->response->redirect($checkoutUrl);
    //         }
    //     } catch (\Exception $e) {
    //         $this->session->data['error'] = $e->getMessage();
    //         $this->response->redirect($this->url->link('checkout/checkout'));
    //     }
    // }

    public function confirm()
    {
        $log = new Log('error.log');

        $log->write('confirm ingpsp');

        try {
            $this->load->model('checkout/order');

            //$orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);

            foreach ($this->session->data['order_id'] as $order_id) {
                $orderInfo = $this->model_checkout_order->getOrder($order_id);

                $log->write('orderid' + $order_id);
                //$log->write($orderInfo);

                //$log->write($this);
                if ($orderInfo) {
                    $ingOrderData = $this->ingHelper->getOrderData($orderInfo, $this);

                    $log->write('ingOrderData');
                    //$log->write($ingOrderData);

                    /*$orderData['amount'],            // Amount in cents
                    $orderData['currency'],          // Currency
                    $orderData['description'],       // Description
                    $orderData['merchant_order_id'], // Merchant Order Id
                    $orderData['return_url'],        // Return URL
                    null,                            // Expiration Period
                    $orderData['customer'],          // Customer information
                    null,                            // Extra information
                    $orderData['webhook_url']        // Webhook URL
                    */

                    $ingOrder = $this->createOrder($ingOrderData);

                    $log->write($ingOrder);
                    $checkoutUrl = $ingOrder->firstTransactionPaymentUrl();

                    $log->write($checkoutUrl);
                    $log->write('checkoutUrl');

                    $this->response->redirect($checkoutUrl);
                }
            }
        } catch (\Exception $e) {
            $this->session->data['error'] = $e->getMessage();

            $log->write('Exception');
            $log->write($e->getMessage());

            //$this->response->redirect($this->url->link('checkout/checkout'));
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
     * Generate ING PSP order.
     *
     * @param array
     *
     * @return \GingerPayments\Payment\Order
     */
    protected function createOrder(array $orderData)
    {
        return $this->ing->createCreditCardOrder(
            $orderData['amount'],            // Amount in cents
            $orderData['currency'],          // Currency
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
