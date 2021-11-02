<?php

class ControllerAmitruckAmitruck extends Controller {

    private $error = [];

    public function createDelivery() {
        $log = new Log('error.log');       
        $this->load->model('amitruck/amitruck');
        $this->load->model('sale/order');        

        $order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);
        // $log->write($order_info);
       
        if (is_array($order_info) && count($order_info) > 0 && $order_info['delivery_id'] == NULL) {
            //$log->write($order_info);

            $response['status'] = false;

            $pickup_time = date("d/m/Y H:i", strtotime('+3 hours'));
            $payment_date = date("d/m/Y", strtotime('+5 days'));

            $start_point = array('latitude' => '-1.2911133', 'longitude' => '36.7943673');
            $end_point = array('latitude' => $order_info['latitude'], 'longitude' => $order_info['longitude']);
            $stops = array($start_point, $end_point);
            // $vehicleCategories = array("OPEN");
            $vehicleCategories = array("CLOSED");

            // $body = array('orderId' => $this->request->post['order_id'], 'stops' => $stops, 'vehicleType' => 1, 'vehicleCategories' => $vehicleCategories, 'paymentTerm' => 'PAY_LATER', 'declaredValueOfGoods' => $this->request->post['order_total'], 'pickUpDateAndTime' => $pickup_time, 'paymentDueDate' => $payment_date, "description" => "Household goods");
            $body = array('orderId' => $this->request->post['order_id'], 'stops' => $stops, 'vehicleType' => 1, 'vehicleCategories' => $vehicleCategories, 'paymentTerm' => 'UPFRONT', 'declaredValueOfGoods' => $this->request->post['order_total'], 'pickUpDateAndTime' => $pickup_time, 'customerRequestedPrice' => 200,'wantInsurance'=>true, "description" => "Household goods");
            //$log->write($body);
            $body = json_encode($body);
            //$log->write($body);
            $curl = curl_init();
            if(ENV=='production')
            {
                curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0/delivery/request');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:a1476380a93c2ffffa00b058cd9833ae489ef3d0', 'clientSecret:wjeACEB9BVk/vzmufg3MEg', 'Content-Type:application/json']);
               
            }
            else {
                curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery/request');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
                  
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
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
        $json = array();
        $this->load->model('amitruck/amitruck');
        $this->load->model('sale/order');
        $order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);
        if (is_array($order_info) && count($order_info) > 0 && $order_info['delivery_id'] != NULL) {
            $log = new Log('error.log');
            //$log->write($order_info);

            $curl = curl_init();
            
            if(ENV=='production')
            {
                curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0/delivery/driver_location?id=' . $order_info['delivery_id']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:a1476380a93c2ffffa00b058cd9833ae489ef3d0', 'clientSecret:wjeACEB9BVk/vzmufg3MEg', 'Content-Type:application/json']);
               
            }
            else {
                curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery/driver_location?id=' . $order_info['delivery_id']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
                  
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 0);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $json = $result;

            if ($result['status'] == 200) {
                $driver_details = $this->model_amitruck_amitruck->fetchOrderDeliveryInfo($this->request->post['order_id']);
                $log->write($driver_details['driver_phone']);
                $log->write($driver_details['vehicle_number']);
                $log->write($driver_details['driver_name']);
                $json['driver_details'] = array('driver_phone' => $driver_details['driver_phone'], 'vehicle_number' => $driver_details['vehicle_number'], 'driver_name' => $driver_details['driver_name']);

                $this->model_amitruck_amitruck->addDelivery($this->request->post['order_id'], json_encode($json), 'FETCH_DRIVER_LOCATION');
                $log->write($result);
            }

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
            if(ENV=='production')
            {
                curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0/delivery?id=' . $order_info['delivery_id']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:a1476380a93c2ffffa00b058cd9833ae489ef3d0', 'clientSecret:wjeACEB9BVk/vzmufg3MEg', 'Content-Type:application/json']);
               
            }
            else {
                curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery?id=' . $order_info['delivery_id']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
                  
            }
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 0);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $json = $result;
            //In Response "completedByDriver": true

            if ($result['status'] == 200) {
                $this->model_amitruck_amitruck->updateDeliveryStatus($this->request->post['order_id'], json_encode($json));
                $this->model_amitruck_amitruck->addDelivery($this->request->post['order_id'], json_encode($json), 'FETCH_DELIVERY');
                $log->write($result);
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function MakeDeliveryPayment() {
        $this->load->model('amitruck/amitruck');
        $this->load->model('sale/order');
        $order_info = $this->model_sale_order->getOrder($this->request->post['order_id']);
        if (is_array($order_info) && count($order_info) > 0 && $order_info['delivery_id'] != NULL) {
            $log = new Log('error.log');
            //$log->write($order_info);
            $body = array('id' => $order_info['delivery_id']);
            //$log->write($body);
            $body = json_encode($body);
            //$log->write($body);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery/make_payment');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $json = $result;
            if ($result['status'] == 200) {
                $this->model_amitruck_amitruck->addDelivery($this->request->post['order_id'], json_encode($json), 'MAKE_PAYMENT');
                $this->model_amitruck_amitruck->updateDeliveryPayment($this->request->post['order_id'], json_encode($json));
                $log->write($result);
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function getWalletBalance() {
        $this->load->model('amitruck/amitruck');

        $log = new Log('error.log');
        //$log->write($order_info);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/wallet');
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

    public function LoadWallet() {
        $this->load->model('amitruck/amitruck');
        $log = new Log('error.log');
        //$log->write($order_info);
        $body = array('amount' => 10000);
        //$log->write($body);
        $body = json_encode($body);
        //$log->write($body);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/wallet/load');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
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


    //For Fetching Trips/Delivaries List
    public function getDeliveries() {
        $this->load->model('amitruck/amitruck');
        // $this->load->model('sale/order');
        $log = new Log('error.log');
            //$log->write($order_info);

            $curl = curl_init();
            if(ENV=='production')
            {
                curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0/delivery');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:a1476380a93c2ffffa00b058cd9833ae489ef3d0', 'clientSecret:wjeACEB9BVk/vzmufg3MEg', 'Content-Type:application/json']);
               
            }
            else {
                curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery');
                curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
                  
            }
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 0);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);

            $log->write($result);
            curl_close($curl);
            $result = json_decode($result, true);
            $json = $result;
            //In Response "completedByDriver": true

            if ($result['status'] == 200) {
               
                $log->write($result);
                
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        
    }
}
