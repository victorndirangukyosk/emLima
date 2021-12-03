<?php

class ControllerApiDeliverySystem extends Controller
{
     
      //AMItruck will send driver id , lat and lng to this api
      public function VehicleLatLng() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $log = new Log('error.log');
        $log->write('Amitruck _ Vehicle Lat lng');
        $log->write(date('d-m-y h:i:sa'));
        $log->write('Amitruck _ Vehicle lat lng');
        if(isset($this->request->post['vehicle_number']) && isset($this->request->post['latitude']) && isset($this->request->post['longitude']))
        {
            $this->load->model('sale/order');
            $id = $this->model_sale_order->addVehicleLatLng($this->request->post['vehicle_number'],$this->request->post['latitude'],$this->request->post['longitude']);
            // $json['data']= $id;
            $json['message']="Vehicle latitude longitude received";
        }
        else {
            $json['status'] = 500;
            $json['message']="One or few of the parameters are missing";
        }
       
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        return $json;
    }
}
