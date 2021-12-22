<?php

class ControllerVehiclesDispatchPlanning extends Controller {

    private $error = [];

    public function index() {
        $data = NULL;
        $log = new Log('error.log');
        $this->load->model('dispatchplanning/dispatchplanning');
        $delivery_executive = $this->request->get['delivery_executive'];
        $driver = $this->request->get['driver'];
        $delivery_timeslot = $this->request->get['delivery_timeslot'];
        $delivery_date = $this->request->get['delivery_date'];
        $vehicle_id = $this->request->get['vehicle'];
        $dispatche_id = $this->request->get['clicked_vehicle_id'];
        $data['delivery_executive'] = $delivery_executive;
        $data['driver'] = $driver;
        $data['delivery_timeslot'] = $delivery_timeslot;
        $data['delivery_date'] = $delivery_date;
        $data['vehicle_id'] = $vehicle_id;
        $data['dispatche_id'] = $dispatche_id;
        $log->write($data);
        if ($dispatche_id == NULL) {
            $res = $this->model_dispatchplanning_dispatchplanning->addVehicleToDispatchPlanning($data);
        }
        if ($dispatche_id != NULL && $dispatche_id > 0) {
            $log->write($data);
            $res = $this->model_dispatchplanning_dispatchplanning->updateVehicleToDispatchPlanning($data);
            if ($res == NULL) {
                $res = $dispatche_id;
            }
        }
        $json = $res;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getUnAssignedVehicles() {
        $json = [];
        $delivery_date = $this->request->get['delivery_date'];
        $delivery_timeslot = $this->request->get['delivery_timeslot'];
        $data['delivery_date'] = $delivery_date;
        $data['delivery_timeslot'] = $delivery_timeslot;
        $this->load->model('dispatchplanning/dispatchplanning');
        $res = $this->model_dispatchplanning_dispatchplanning->getUnAssignedVehicles($data['delivery_date'], $data['delivery_timeslot']);
        $json = $res;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getUnAssignedDeliveryExecutives() {
        $json = [];
        $delivery_date = $this->request->get['delivery_date'];
        $delivery_timeslot = $this->request->get['delivery_timeslot'];
        $data['delivery_date'] = $delivery_date;
        $data['delivery_timeslot'] = $delivery_timeslot;
        $this->load->model('dispatchplanning/dispatchplanning');
        $res = $this->model_dispatchplanning_dispatchplanning->getUnAssignedDeliveryExecutives($data['delivery_date'], $data['delivery_timeslot']);
        $json = $res;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getUnAssignedDrivers() {
        $json = [];
        $delivery_date = $this->request->get['delivery_date'];
        $delivery_timeslot = $this->request->get['delivery_timeslot'];
        $data['delivery_date'] = $delivery_date;
        $data['delivery_timeslot'] = $delivery_timeslot;
        $this->load->model('dispatchplanning/dispatchplanning');
        $res = $this->model_dispatchplanning_dispatchplanning->getUnAssignedDriver($data['delivery_date'], $data['delivery_timeslot']);
        $json = $res;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAssignedVehicles() {
        $json = [];
        $this->load->model('sale/order');
        $updateDeliveryDate = $this->request->get['updateDeliveryDate'];
        $order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
        $delivery_date = isset($updateDeliveryDate) && $updateDeliveryDate == 1 ? date('y-m-d') : $order_info['delivery_date'];
        $log = new Log('error.log');
        $log->write($delivery_date);
        $delivery_timeslot = $order_info['delivery_timeslot'];
        $data['delivery_date'] = $delivery_date;
        $data['delivery_timeslot'] = $delivery_timeslot;
        $this->load->model('dispatchplanning/dispatchplanning');
        $res = $this->model_dispatchplanning_dispatchplanning->getAssignedVehicles($data['delivery_date'], $data['delivery_timeslot']);
        $log = new Log('error.log');
        $log->write($res);
        $json = $res;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getAssignedVehiclesNew() {
        $json = [];
        $this->load->model('sale/order');
        $updateDeliveryDate = $this->request->get['updateDeliveryDate'];
        $orders = explode(',', $this->request->get['order_id']);
        $orders_new = NULL;
        foreach ($orders as $order) {
            if ($order > 0) {
                $orders_new[] = $order;
            }
        }
        $log = new Log('error.log');
        $log->write($orders_new);
        //$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
        $order_info = $this->model_sale_order->getOrder($orders_new[0]);
        $delivery_date = isset($updateDeliveryDate) && $updateDeliveryDate == 1 ? date('y-m-d') : $order_info['delivery_date'];
        $log->write($delivery_date);
        $delivery_timeslot = $order_info['delivery_timeslot'];
        $data['delivery_date'] = $delivery_date;
        $data['delivery_timeslot'] = $delivery_timeslot;
        $this->load->model('dispatchplanning/dispatchplanning');
        $res = $this->model_dispatchplanning_dispatchplanning->getAssignedVehicles($data['delivery_date'], $data['delivery_timeslot']);
        $log->write($res);
        $json = $res;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getDispatcheById() {
        $json = [];
        $this->load->model('dispatchplanning/dispatchplanning');
        $res = $this->model_dispatchplanning_dispatchplanning->getDispatcheById($this->request->get['dispache_id']);
        $log = new Log('error.log');
        $log->write($res);
        $json = $res;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
