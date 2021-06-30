<?php

class ControllerAmitruckAmitruck extends Controller {

    private $error = [];

    public function createDelivery() {
        $this->load->model('amitruck/amitruck');
        $log = new Log('error.log');

        $response['status'] = false;

        $pickup_time = date("d/m/Y H:i", strtotime('+3 hours'));
        $payment_date = date("d/m/Y");

        $start_point = array('latitude' => '-1.305183', 'longitude' => '36.737732');
        $end_point = array('latitude' => '-1.297139', 'longitude' => '36.80455');
        $stops = array($start_point, $end_point);
        $vehicleCategories = array("OPEN");

        $body = array('orderId' => $this->request->post['order_id'], 'stops' => $stops, 'vehicleType' => 1, 'vehicleCategories' => $vehicleCategories, 'paymentTerm' => 'UPFRONT', 'declaredValueOfGoods' => $this->request->post['order_total'], 'pickUpDateAndTime' => $pickup_time, 'paymentDueDate' => $payment_date, "description" => "Household goods");
        $log->write($body);
        $body = json_encode($body);
        $log->write($body);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://customer.amitruck.com/rest-api-v1.0.0-test/delivery/request');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['clientId:fbc86ee31d7ee4a998822d234363efd51416c4bb', 'clientSecret:wNSABgWArMR9qNYBghuD4w', 'Content-Type:application/json']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);

        $log->write('createDelivery log');
        $log->write($result);
        curl_close($curl);
        $result = json_decode($result);
        $json = $result;
        if (isset($result->error)) {
            $response['status'] = false;
            $response['error'] = $result->error;
        } else {
            $response['status'] = true;
            $response['data'] = $result;
        }

        $this->model_amitruck_amitruck->addDelivery($this->request->post['order_id'], json_encode($json));

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
