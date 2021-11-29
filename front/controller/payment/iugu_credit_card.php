<?php
/*
    Author: Valdeir Santana
    Site: http://www.valdeirsantana.com.br
    License: http://www.gnu.org/licenses/gpl-3.0.en.html
*/

require_once DIR_SYSTEM.'library/Iugu.php';

class ControllerPaymentIuguCreditCard extends Controller
{
    public function index()
    {
        /* Carrega o idioma */
        $data = $this->language->load('payment/iugu');

        /* Carrega model */
        $this->load->model('payment/iugu');

        /* Captura o ID da Conta */
        $data['iugu_account_id'] = $this->config->get('iugu_account_id');

        /* Verifica se é modo de teste */
        $data['test_mode'] = (bool) $this->config->get('iugu_test_mode');

        /* Calcula o valor das parcelas */
        $data['installments'] = $this->model_payment_iugu->getInstallments();

        /* Link de pagamento */
        $data['link_pay'] = $this->url->link('payment/iugu_credit_card/pay', '', 'SSL');

        $data['link_payment_method_id'] = $this->url->link('payment/iugu_credit_card/paymentMethodId', '', 'SSL');

        /* Link Download da Fatura */
        $data['link_download_invoice'] = $this->url->link('payment/iugu/download', '', 'SSL');

        /* Link Envia Fatura por E-mail */
        $data['link_send_mail_invoice'] = $this->url->link('payment/iugu/sendMail', '', 'SSL');

        $this->document->addScript('https://js.iugu.com/v2');

        /* Link Confirmação do Pedido */
        $data['continue'] = $this->url->link('payment/iugu_credit_card/confirm', '', 'SSL');

        //543CBB7A71AF45ADAA43B98045DB1CF8

        $data['payment_methods'] = [];

        if ($this->customer->isLogged()) {
            $this->load->model('account/customer');

            $iugu_customer_id = $this->model_account_customer->getIuguCustomerPaymentIds($this->customer->getId());

            if (count($iugu_customer_id) > 0) {
                $data['payment_methods'] = $iugu_customer_id;
            }
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        //	print_r($data['payment_methods']);die;
        return $this->load->view('default/template/payment/iugu_credit_card.tpl', $data);
    }

    public function pay()
    {
        /* Carrega Model */
        $this->load->model('payment/iugu');

        /* Carrega library */
        require_once DIR_SYSTEM.'library/Iugu.php';

        /* Define a API */
        Iugu::setApiKey($this->config->get('iugu_token'));

        $data = [];

        /* Recebe o token gerado */
        //$data['token'] = isset($this->request->post['token']) ? $this->request->post['token'] : '';

        /* Recebe a quantidade de parcelas */
        $data['months'] = 1; //($this->config->get('iugu_credit_card_installments_status') || !isset($this->request->post['installment'])) ? $this->request->post['installment'] : 1;

        /* Forma de Pagamento */
        $data['payable_with'] = 'credit_card';

        /* Url de Notificações */
        $data['notification_url'] = $this->url->link('payment/iugu/notification', '', 'SSL');

        /* Url de Expiração */
        $data['expired_url'] = $this->url->link('payment/iugu/expired', '', 'SSL');

        /* Validade */
        $data['due_date'] = date('d/m/Y', strtotime('+7 days'));

        /* Carrega model de pedido */
        $this->load->model('account/order');
        $this->load->model('checkout/order');

        /* Captura informações do pedido */

        foreach ($this->session->data['order_id'] as $order_ids) {
            //$this->model_checkout_order->addOrderHistory( $order_ids, $this->config->get( 'payu_order_status_id' ) );
            $fetched_order_id = $order_ids;
        }
        //$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $order_info = $this->model_checkout_order->getOrder($fetched_order_id);

        /* Captura o E-mail do Cliente */
        $data['email'] = $order_info['email'];

        /* Captura os produtos comprados */
        //$products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);
        $products = $this->model_account_order->getOrderProducts($fetched_order_id);

        /* Formata as informações do produto (Nome, Quantidade e Preço unitário) */
        $data['items'] = [];

        $count = 0;

        foreach ($products as $product) {
            $data['items'][$count] = [
                'description' => $product['name'],
                'quantity' => $product['quantity'],
                'price_cents' => $this->currency->format($product['price'], 'BRL', null, false) * 100,
            ];
            ++$count;
        }

        unset($count);

        /* Captura os Descontos, Acréscimo, Vale-Presente, Crédito do Cliente, etc. */
        $data['items'] = array_merge($data['items'], $this->model_payment_iugu->getTotals());

        /* Captura valor do desconto */
        $data['discount_cents'] = $this->model_payment_iugu->getDiscount();

        /* Informações do Cliente */
        $data['payer'] = [];
        $data['payer']['cpf_cnpj'] = isset($order_info['custom_field'][$this->config->get('iugu_custom_field_cpf')]) ? $order_info['custom_field'][$this->config->get('iugu_custom_field_cpf')] : '';
        $data['payer']['name'] = $order_info['firstname'].' '.$order_info['lastname'];
        $data['payer']['phone_prefix'] = substr($order_info['telephone'], 0, 2);
        $data['payer']['phone'] = substr($order_info['telephone'], 2);
        $data['payer']['email'] = $order_info['email'];

        /* Informações de Endereço */
        $data['payer']['address'] = [];
        /*$data['payer']['address']['street'] = $order_info['payment_address_1'];
        $data['payer']['address']['number'] = isset($order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')]) ? $order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')] : 0;
        $data['payer']['address']['city'] = $order_info['payment_city'];
        $data['payer']['address']['state'] = $order_info['payment_zone_code'];
        $data['payer']['address']['country'] = $order_info['payment_country'];
        $data['payer']['address']['zip_code'] = $order_info['payment_postcode'];*/

        $data['payer']['address']['street'] = $order_info['shipping_address'];
        $data['payer']['address']['number'] = isset($order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')]) ? $order_info['payment_custom_field'][$this->config->get('iugu_custom_field_number')] : 0;
        $data['payer']['address']['city'] = $order_info['shipping_city'];
        /*$data['payer']['address']['state'] = $order_info['payment_zone_code'];
        $data['payer']['address']['country'] = $order_info['payment_country'];*/
        $data['payer']['address']['zip_code'] = $order_info['shipping_zipcode'];

        /* Informações adicionais */
        $data['custom_variables'] = [
            'order_id' => $fetched_order_id, //$this->session->data['order_id']
        ];

        $payment_method_id = isset($this->request->post['payment_method_id']) ? $this->request->post['payment_method_id'] : '';

        $log = new Log('error.log');

        $log->write('payment_method id '.$payment_method_id);

        $data['customer_payment_method_id'] = $payment_method_id;

        $result = Iugu_Charge::create($data);

        $response = [];

        foreach (reset($result) as $key => $value) {
            $response[$key] = $value;
        }

        $response['customer_payment_method_id'] = $payment_method_id;

        $response['identification'] = $payment_method_id;

        $this->session->data['result_iugu'] = $response;

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /*
        Method: confirm
        Function: Armazena os dados do pedido na loja
    */
    public function confirm()
    {
        $log = new Log('error.log');

        $log->write('confirm 1');

        if ('iugu_credit_card' == $this->session->data['payment_method']['code']) {
            $log->write('confirm if');
            $this->load->model('checkout/order');
            $this->load->model('payment/iugu');

            foreach ($this->session->data['order_id'] as $order_ids) {
                //$this->model_checkout_order->addOrderHistory( $order_ids, $this->config->get( 'payu_order_status_id' ) );
                $fetched_order_id = $order_ids;
            }

            $log->write('confirm iugu ');
            $log->write($this->session->data['result_iugu']);

            /*
                2017-10-13 18:16:28 - Array
(
    [message] => Autorizado
    [success] => 1
    [url] => https://faturas.iugu.com/f2fbc5e6-fb24-42e8-82fc-b7f38e8228e2-b2ab
    [pdf] => https://faturas.iugu.com/f2fbc5e6-fb24-42e8-82fc-b7f38e8228e2-b2ab.pdf
    [identification] =>
    [invoice_id] => F2FBC5E6FB2442E882FCB7F38E8228E2
    [LR] => 00
    [months] => 1
    [payable_with] => credit_card
    [notification_url] => http://localhost/gatoo/index.php?path=payment/iugu/notification
    [expired_url] => http://localhost/gatoo/index.php?path=payment/iugu/expired
    [due_date] => 20/10/2017
    [email] => chaurasiaabhi09@gmail.com
    [items] => Array
        (
            [0] => Array
                (
                    [description] => Leite Fermentado Actimel Danone de Morango 6un
                    [quantity] => 4
                    [price_cents] => 1175
                )

            [1] => Array
                (
                    [description] => Buscar na loja-Rainbow Grocery
                    [quantity] => 1
                    [price_cents] => 0
                )

        )

    [discount_cents] => 0
    [payer] => Array
        (
            [cpf_cnpj] =>
            [name] => Abhishek Chaurasia
            [phone_prefix] => 35
            [phone] => 34645657
            [email] => chaurasiaabhi09@gmail.com
            [address] => Array
                (
                    [street] => 23, btm layout, 6th Cross Rd, Srinivasnagar, Banashankari, Bengaluru, Karnataka 560050, India
                    [number] => 0
                    [city] => Curitiba
                    [zip_code] => 80420-170
                )

        )

    [custom_variables] => Array
        (
            [order_id] => 586
        )

    [customer_payment_method_id] => F2A16A7BFF6B44ABA49B8D9254857790
)

            */
            $this->model_payment_iugu->addOrder($fetched_order_id, $this->session->data['result_iugu'], true);

            $log->write('confirm 122');
            //$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('iugu_order_status_pending'));
            $this->model_checkout_order->addOrderHistory($fetched_order_id, $this->config->get('iugu_order_status_pending'));

            $log->write('confirm 1222');
            //$this->model_payment_iugu->addOrder($this->session->data['order_id'], $this->session->data['result_iugu'], true);

            $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));

            unset($this->session->data['result_iugu']);
        }

        $log->write('confirm end');
    }

    public function createIuguCustomer($data)
    {
        require_once DIR_SYSTEM.'library/Iugu.php';

        $email = $data['email'];
        $name = $data['name'];

        Iugu::setApiKey($this->config->get('iugu_token'));

        $result = Iugu_Customer::create([
            'email' => $email,
            'name' => $name,
        ]);

        $resp['status'] = false;

        $this->load->model('checkout/order');

        if (!$result->errors) {
            //success
            //save Iugu customer id in customer_to_customer_iugu table
            $resp['status'] = true;
            $resp['id'] = $result->id;
            $resp['customer_id'] = $this->customer->getId();

            //$this->model_checkout_order->saveIuguCustomer($resp);
        }

        return $resp;
    }

    public function createPaymentMethodCustomer($data)
    {
        $log = new Log('error.log');

        require_once DIR_SYSTEM.'library/Iugu.php';

        $log->write('create pay meto in');

        $description = $data['description'];

        $customer_id = $data['customer_id'];

        $token = $data['token'];

        Iugu::setApiKey($this->config->get('iugu_token'));

        $customer = Iugu_Customer::fetch($customer_id);

        $log->write($customer);
        //echo "rf";print_r($customer);die;
        $payment_method = $customer->payment_methods()->create([
            'description' => $description,
            'item_type' => 'token',
            'token' => $token,
        ]);

        $this->load->model('account/customer');

        $resp['id'] = $payment_method['id'];
        $resp['brand'] = $payment_method['data']->brand;
        $resp['holder_name'] = $payment_method['data']->holder_name;
        $resp['display_number'] = $payment_method['data']->display_number;
        $resp['description'] = $payment_method['description'];

        $resp['customer_id'] = $this->customer->getId();
        $resp['iugu_customer_id'] = $data['customer_id'];

        $this->model_account_customer->saveIuguCustomerId($resp);

        $log->write($payment_method);
        $log->write($payment_method['id']);
        //return $payment_method['id'];

        return $resp;
    }

    public function paymentMethodId()
    {
        $log = new Log('error.log');
        $log->write('paymentMethodId');

        $data = $this->language->load('payment/iugu');

        /* Carrega Model */
        $this->load->model('payment/iugu');

        /* Carrega library */
        require_once DIR_SYSTEM.'library/Iugu.php';

        $log->write('paymentMethodId 1');
        /* Define a API */
        Iugu::setApiKey($this->config->get('iugu_token'));

        $log->write('paymentMethodId 12');
        $data = [];

        /* Recebe o token gerado */
        $data['token'] = isset($this->request->post['token']) ? $this->request->post['token'] : '';

        $data['label'] = isset($this->request->post['label']) ? $this->request->post['label'] : '';

        /* Carrega model de pedido */
        $this->load->model('account/order');
        $this->load->model('checkout/order');
        // create iugu customer if not present

        $iuguData['email'] = $this->customer->getEmail();
        $iuguData['name'] = $this->customer->getFirstName().' '.$this->customer->getLastName();
        //$iuguData['description'] = "Credit Card";
        $iuguData['description'] = $data['label'];
        $iuguData['token'] = $data['token'];

        $iuguData['customer_id'] = false;

        $this->load->model('account/customer');

        $iugu_customer_id = $this->model_account_customer->getIuguCustomerId($this->customer->getId());

        $log->write('paymentMethodId 121');
        if (count($iugu_customer_id) > 0) {
            $iuguData['customer_id'] = $iugu_customer_id['iugu_customer_id'];
        } else {
            $createCustomer = $this->createIuguCustomer($iuguData);
            //save this customer id
            if ($createCustomer['status']) {
                $iuguData['customer_id'] = $createCustomer['id'];
            }
        }

        $log->write('paymentMethodId 1211');

        $log->write($iuguData);

        $resp['status'] = false;

        $log->write('create pay meto 1');
        if (isset($iuguData['customer_id'])) {
            $log->write('create pay meto 2');
            $payment_method_data = $this->createPaymentMethodCustomer($iuguData);
            unset($data['token']);

            $resp['status'] = true;
            $resp['customer_payment_method_id'] = $payment_method_data['id'];
        }

        $resp['message'] = 'Something went wrong please try ';

        $data['button_pay'] = $this->language->get('button_pay');

        $resp['html'] = '<div class="col-md-6"> <div class="address-block" >
                <h3 class="address-locations">'.
                  $payment_method_data['brand'].'
                </h3>
                <h4 class="address-name">'.$payment_method_data['display_number'].'</h4>
                <p>'.$payment_method_data['holder_name']
                    .'<br>'.$payment_method_data['description'].'
                </p>
                <button type="button" data-payment-method-id="'.$payment_method_data['id'].'" class="btn btn-primary btn-block iugu-pay">

                	<span class="button-pay-text">'.$data['button_pay'].'
                	</span>
                  <div class="loader" style="display: none;"></div>
                </button>
            </div></div>';

        header('Content-Type: application/json');
        echo json_encode($resp);
    }
}
