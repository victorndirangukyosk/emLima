<?php

class ControllerDispatchPlanning extends Controller {

    private $error = [];

    public function index() {
        $data = NULL;
        $log = new Log('error.log');
        $this->load->model('dispatchplanning/dispatchplanning');
        $delivery_executive = $this->request->get['delivery_executive'];
        $driver = $this->request->get['driver'];
        $delivery_timeslot = $this->request->get['delivery_timeslot'];
        $delivery_date = $this->request->get['delivery_date'];
        $vehicle_id = $this->request->get['vehicle_id'];
        $data['delivery_executive'] = $delivery_executive;
        $data['driver'] = $driver;
        $data['delivery_timeslot'] = $delivery_timeslot;
        $data['delivery_date'] = $delivery_date;
        $data['vehicle_id'] = $vehicle_id;
        exit;
        $res = $this->model_dispatchplanning_dispatchplanning->addVehicleToDispatchPlanning($data);
        $json = $res;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
