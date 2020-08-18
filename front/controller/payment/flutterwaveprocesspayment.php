<?php

require_once DIR_SYSTEM . 'vendor/flutterwave/vendor/autoload.php';
include(DIR_SYSTEM . 'vendor/flutterwave/library/rave.php');
include(DIR_SYSTEM . 'vendor/flutterwave/library/raveEventHandlerInterface.php');

use Flutterwave\Rave;
use Flutterwave\EventHandlerInterface;
use Flutterwave\myEventHandler;

class ControllerPaymentFlutterwaveprocesspayment extends Controller {

    public function index() {

        $this->load->model('setting/setting');
        $this->load->language('payment/flutterwave');
        $this->load->model('payment/flutterwave');
        $this->load->model('checkout/order');

        $flutter_creds = $this->model_setting_setting->getSetting('flutterwave', 0);
        $log = new Log('error.log');
        $log->write('Flutterwave Creds');
        $log->write($flutter_creds);
        $log->write('Flutterwave Creds');

        foreach ($this->session->data['order_id'] as $key => $value) {
            $order_id = $value;
        }

        $log->write('Flutterwave Order ID');
        $log->write($this->session->data['order_id']);
        $log->write('Flutterwave Order ID');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        $log->write('Flutterwave Order Info');
        $log->write($order_info);
        $log->write('Flutterwave Order Info');

        if (count($order_info) > 0) {
            $amount = (int) ($order_info['total']);
        }

        $URL = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $getData = $_GET;
        $postData = $_POST;
        $publicKey = $flutter_creds['flutterwave_public_key'];
        $secretKey = $flutter_creds['flutterwave_secret_key'];
        $success_url = 'http://kwikbasket.test';
        $failure_url = 'http://kwikbasket.test';
        $env = $_SERVER['ENV'];

        $_SESSION['publicKey'] = $publicKey;
        $_SESSION['secretKey'] = $secretKey;
        $_SESSION['env'] = $env;
        $_SESSION['successurl'] = $success_url;
        $_SESSION['failureurl'] = $failure_url;
        $_SESSION['currency'] = 'KES';
        $_SESSION['order_id'] = $order_id;
        $_SESSION['amount'] = $amount;

        $prefix = 'KWIKBASKET'; // Change this to the name of your business or app
        $overrideRef = false;

// Uncomment here to enforce the useage of your own ref else a ref will be generated for you automatically
        if ($order_id) {
            $prefix = $order_id;
            $overrideRef = true;
        }

        $payment = new Rave($_SESSION['secretKey'], $prefix, $overrideRef);

        if ($amount) {
            // Make payment
            $payment
                    ->eventHandler(new myEventHandler)
                    ->setAmount($amount)
                    ->setPaymentOptions('card') // value can be card, account or both
                    ->setDescription('KwikBasket')
                    ->setLogo('https://www.kwikbasket.com/front/ui/theme/metaorganic/assets_landing_page/img/logo.svg')
                    ->setTitle('KwikBasket')
                    ->setCountry('NG')
                    ->setCurrency('NGN')
                    ->setEmail('ramakanth.rapaka@yopmail.com')
                    ->setFirstname('Ramakanth')
                    ->setLastname('Rapaka')
                    ->setPhoneNumber('08098787676')
                    ->setPayButtonText('Complete Payment')
                    ->setRedirectUrl($URL)
                    // ->setMetaData(array('metaname' => 'SomeDataName', 'metavalue' => 'SomeValue')) // can be called multiple times. Uncomment this to add meta datas
                    // ->setMetaData(array('metaname' => 'SomeOtherDataName', 'metavalue' => 'SomeOtherValue')) // can be called multiple times. Uncomment this to add meta datas
                    ->initialize();
        } else {
            if ($getData['cancelled'] && $getData['tx_ref']) {
                // Handle canceled payments
                $payment
                        ->eventHandler(new myEventHandler)
                        ->requeryTransaction($getData['tx_ref'])
                        ->paymentCanceled($getData['tx_ref']);
            } elseif ($getData['tx_ref']) {
                // Handle completed payments
                $payment->logger->notice('Payment completed. Now requerying payment.');

                $payment
                        ->eventHandler(new myEventHandler)
                        ->requeryTransaction($getData['tx_ref']);
            } else {
                $payment->logger->warn('Stop!!! Please pass the txref parameter!');
                echo 'Stop!!! Please pass the txref parameter!';
            }
        }
    }

}
