<?php

class ControllerAmitruckAmitruck extends Controller {

    private $error = [];

    public function createDelivery() {
        $this->load->model('amitruck/amitruck');
        $this->load->model('sale/order');
        $order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);
        if (is_array($order_info) && count($order_info) > 0 && $order_info['delivery_id'] == NULL) {
            $log = new Log('error.log');
            //$log->write($order_info);

            $response['status'] = false;

            $pickup_time = date("d/m/Y H:i", strtotime('+3 hours'));
            $payment_date = date("d/m/Y", strtotime('+5 days'));

            $start_point = array('latitude' => '-1.2911133', 'longitude' => '36.7943673');
            $end_point = array('latitude' => $order_info['latitude'], 'longitude' => $order_info['longitude']);
            $stops = array($start_point, $end_point);
            $vehicleCategories = array("OPEN");

            $body = array('orderId' => $this->request->post['order_id'], 'stops' => $stops, 'vehicleType' => 1, 'vehicleCategories' => $vehicleCategories, 'paymentTerm' => 'PAY_LATER', 'declaredValueOfGoods' => $this->request->post['order_total'], 'pickUpDateAndTime' => $pickup_time, 'paymentDueDate' => $payment_date, "description" => "Household goods");
            //$log->write($body);
            $body = json_encode($body);
            //$log->write($body);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery/request');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            //$log->write('createDelivery log');
            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $json = $result;

            if ($result['status'] == 200) {
                $this->model_amitruck_amitruck->addDelivery($this->request->post['order_id'], json_encode($json), 'CREATE_DELIVERY');
                $this->model_amitruck_amitruck->addDeliveryStatus($this->request->post['order_id'], json_encode($json));
                $this->model_amitruck_amitruck->updateOrderDelivery($this->request->post['order_id'], json_encode($json));
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function getDriverLocation() {
        $this->load->model('amitruck/amitruck');
        $this->load->model('sale/order');
        $order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);
        if (is_array($order_info) && count($order_info) > 0 && $order_info['delivery_id'] != NULL) {
            $log = new Log('error.log');
            //$log->write($order_info);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery/driver_location?id=' . $order_info['delivery_id']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $json = $result;

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function getCurrentDeliveryStatus() {
        $this->load->model('amitruck/amitruck');
        $this->load->model('sale/order');
        $order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);
        if (is_array($order_info) && count($order_info) > 0 && $order_info['delivery_id'] != NULL) {
            $log = new Log('error.log');
            //$log->write($order_info);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery?id=' . $order_info['delivery_id']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $json = $result;
            if ($result['status'] == 200) {
                $this->model_amitruck_amitruck->updateDeliveryStatus($this->request->post['order_id'], json_encode($json));
                $this->model_amitruck_amitruck->addDelivery($this->request->post['order_id'], json_encode($json), 'FETCH_DELIVERY');
                $log->write($result);
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

}
