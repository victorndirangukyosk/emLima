<?php

class ControllerAmitruckAmitruck extends Controller {

    private $error = [];

    public function createDelivery() {
        $log = new Log('error.log');

        $response['status'] = false;

        $start_point = array('latitude' => '-1.305183', 'longitude' => '36.737732');
        $end_point = array('latitude' => '-1.297139', 'longitude' => '36.80455');
        $stops = array($start_point, $end_point);
        $vehicleCategories = array("OPEN");

        $body = array('orderId' => '1234', 'stops' => $stops, 'vehicleType' => 1, 'vehicleCategories' => $vehicleCategories, 'paymentTerm' => 'UPFRONT', 'customerRequestedPrice' => 200, 'wantInsurance' => true, 'declaredValueOfGoods' => 1500000, 'pickUpDateAndTime' => "28/06/2021 14:00", 'paymentDueDate' => "30/06/2021", "description" => "Household goods");
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
        if (isset($result->error)) {
            $response['status'] = false;
            $response['error'] = $result->error;
        } else {
            $response['status'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

}
