<?php

/**
 * Class ControllerPaymentIngpspSepa.
 */
class ControllerPaymentIngpspSepa extends Controller
{
    /**
     * Default currency for Order.
     */
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * Payments module name.
     */
    const MODULE_NAME = 'ingpsp_sepa';

    /**
     *  ING PSP bank transfer details.
     */
    const ING_BIC = 'INGBNL2A';
    const ING_IBAN = 'NL13INGB0005300060';
    const ING_HOLDER = 'ING Bank N.V. PSP';
    const ING_RESIDENCE = 'Amsterdam';

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
    // public function index()
    // {
    //     $this->language->load('payment/'.static::MODULE_NAME);
    //     $this->load->model('checkout/order');
    //     $data = [];

    //     try {
    //         $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    //         if ($orderInfo) {
    //             $ingOrderData = $this->ingHelper->getOrderData($orderInfo, $this);
    //             $ingOrder = $this->createOrder($ingOrderData);
    //             $paymentReference = $this->getBankPaymentReference($ingOrder);

    //             $this->model_checkout_order->addOrderHistory(
    //                 $ingOrder->getMerchantOrderId(),
    //                 $this->ingHelper->getOrderStatus($ingOrder->getStatus(), $this->config),
    //                 'ING PSP Bank Transfer order: '.$ingOrder->id()->toString(),
    //                 true
    //             );

    //             $this->model_checkout_order->addOrderHistory(
    //                 $ingOrder->getMerchantOrderId(),
    //                 $this->ingHelper->getOrderStatus($ingOrder->getStatus(), $this->config),
    //                 'ING PSP Bank Transfer Reference ID: '.$paymentReference,
    //                 true
    //             );

    //             $data['button_confirm'] = $this->language->get('button_confirm');
    //             $data['ing_bank_details'] = $this->language->get('ing_bank_details');
    //             $data['ing_payment_reference'] = $this->language->get('ing_payment_reference').$paymentReference;
    //             $data['ing_iban'] = $this->language->get('ing_iban').static::ING_IBAN;
    //             $data['ing_bic'] = $this->language->get('ing_bic').static::ING_BIC;
    //             $data['ing_account_holder'] = $this->language->get('ing_account_holder').static::ING_HOLDER;
    //             $data['ing_residence'] = $this->language->get('ing_residence').static::ING_RESIDENCE;
    //             $data['text_description'] = $this->language->get('text_description');
    //             $data['action'] = $this->url->link('checkout/success');
    //         }
    //     } catch (\Exception $e) {
    //         $this->session->data['error'] = $e->getMessage();
    //     }

    //     return $this->load->view('mvgv2/template/extension/payment/ingpsp_sepa.tpl', $data);
    // }

    public function index()
    {
        $this->language->load('payment/'.static::MODULE_NAME);
        $this->load->model('checkout/order');
        $data = [];

        $log = new Log('error.log');
        $log->write('ingpsp_sepa');

        foreach ($this->session->data['order_id'] as $order_id) {
            //$ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));

            $log->write('order_id'.$order_id);
            try {
                $orderInfo = $this->model_checkout_order->getOrder($order_id);

                if ($orderInfo) {
                    $ingOrderData = $this->ingHelper->getOrderData($orderInfo, $this);
                    $ingOrder = $this->createOrder($ingOrderData);
                    $paymentReference = $this->getBankPaymentReference($ingOrder);

                    $log->write($ingOrder->getMerchantOrderId());
                    $log->write($this->ingHelper->getOrderStatus($ingOrder->getStatus(), $this->config));

                    $this->model_checkout_order->addOrderHistory(
                        $ingOrder->getMerchantOrderId(),
                        $this->ingHelper->getOrderStatus($ingOrder->getStatus(), $this->config),
                        'ING PSP Bank Transfer order: '.$ingOrder->id()->toString(),
                        true
                    );

                    $this->model_checkout_order->addOrderHistory(
                        $ingOrder->getMerchantOrderId(),
                        $this->ingHelper->getOrderStatus($ingOrder->getStatus(), $this->config),
                        'ING PSP Bank Transfer Reference ID: '.$paymentReference,
                        true
                    );

                    $log->write('ingpsp_sepa end loop');
                    $data['button_confirm'] = $this->language->get('button_confirm');
                    $data['ing_bank_details'] = $this->language->get('ing_bank_details');
                    $data['ing_payment_reference'] = $this->language->get('ing_payment_reference').$paymentReference;
                    $data['ing_iban'] = $this->language->get('ing_iban').static::ING_IBAN;
                    $data['ing_bic'] = $this->language->get('ing_bic').static::ING_BIC;
                    $data['ing_account_holder'] = $this->language->get('ing_account_holder').static::ING_HOLDER;
                    $data['ing_residence'] = $this->language->get('ing_residence').static::ING_RESIDENCE;
                    $data['text_description'] = $this->language->get('text_description');
                    $data['action'] = $this->url->link('checkout/success');
                }
            } catch (\Exception $e) {
                $this->session->data['error'] = $e->getMessage();
            }
        }

        return $this->load->view('mvgv2/template/extension/payment/ingpsp_sepa.tpl', $data);
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
        return $this->ing->createSepaOrder(
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
     * Method gets payment reference from order.
     *
     * @return mixed
     */
    protected function getBankPaymentReference(\GingerPayments\Payment\Order $ingOrder)
    {
        $ingOrder = $ingOrder->toArray();

        return $ingOrder['transactions'][0]['payment_method_details']['reference'];
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
