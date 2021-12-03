<?php

class ControllerApiDeliverySystem extends Controller
{
    private $error = [];
      //AMItruck will send driver id , lat and lng to this api
      public function VehicleLatLng() {


        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);
        // echo "<pre>";print_r($data);die;


        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $log = new Log('error.log');
        $log->write('Amitruck _ Vehicle Lat lng');
        $log->write(date('d-m-y h:i:sa'));
        $log->write('Amitruck _ Vehicle lat lng');

        if (empty($data->vehicle_number)) {
        // echo "<pre>";print_r(isset($data->vehicle_number));die;

             $this->error['vehicle_number'] = "Vehicle number is required";
        } 
        // else if ((utf8_strlen(trim($data->vehicle_number)) < 1) || (utf8_strlen(trim($data->vehicle_number)) > 10)) {
        //     $this->error['vehicle_number'] = "Vehicle number is not valid";
        // }

        if (empty($data->latitude)) {
            $this->error['latitude'] = "Latitude is required";
        }  
        if (empty($data->longitude)) {
            $this->error['longitude'] = "Longitude is required";
        } 
        if (empty($data->speed) || !isset($data->speed)) {
            $this->error['speed'] = "Speed is required";
        }  

        // echo "<pre>";print_r($this->error);die;

        if(!$this->error)
        {
            $this->load->model('sale/order');
            $id = $this->model_sale_order->addVehicleLatLng($data->vehicle_number,$data->latitude,$data->longitude,$data->speed);
            // $json['data']= $id;
            $json['message']="Vehicle data received";
        }
        else {
            $json['status'] = 500;
            $json['message']="One or few of the parameters are missing";

            foreach ($this->error as $key => $value) {
                $json['error'][] = ['type' => $key, 'body' => $value];
            }


        }
       
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        return $json;
    }
}
