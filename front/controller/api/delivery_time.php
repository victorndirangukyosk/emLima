<?php

class ControllerApiDeliverytime Extends Controller {

    public function index() {

        $data = array();

        $store_id = $this->request->post['store_id'];
        $shipping_method =  $this->request->post['shipping_method'];
      
        $getActiveDays = $this->getActiveDays($store_id,$shipping_method);

        $data['dates'] = $this->getDates($getActiveDays,$store_id,$shipping_method);
        $data['store'] = $this->getStoreDetail($store_id);    
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/api/delivery_time.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/api/delivery_time.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/api/delivery_time.tpl', $data));
        }
    }

    public function getStoreDetail($store_id) {

        $this->load->model( 'account/activity' );
        return $this->model_account_activity->getStoreData( $store_id);
        
    }

    protected function getDates($getActiveDays,$store_id,$shipping_method){
        $avalday = array();
        foreach ($getActiveDays as $ad) {
              $avalday[] = $ad['day'];     
        }

        $date = $this->checkCurrentDateTs($store_id,$shipping_method);
        if ($date) {
          $tmpDate = date('Y-m-d');
        }else{
          $tmpDate = date('Y-m-d', strtotime('+1 Days'));     
        }
       
        $i = 0;
        $nextBusinessDay = array();
        for ($i=0; $i <=6; $i++) { 
            if (in_array($i, $avalday)) {
                $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate . ' +' . $i . ' Days'));
            }

        }
        $total = count($nextBusinessDay);
        if ($total <= 7) {
            $length = 7 -  $total;
            for ($i=7; $i <= 6 +$length; $i++) { 
                if (in_array($i, $avalday)) {
                    $nextBusinessDay[] = date('d-m-Y', strtotime($tmpDate . ' +' . $i . ' Days'));
                }

            }    
           
        }
        return $nextBusinessDay;
    }

    public function getActiveDays($store_id,$method){
        $shipping_method = explode('.',$method);

        if ($shipping_method[0] == 'pickup') {
            $this->db->group_by('day');    
            $this->db->select('day', FALSE);
            $this->db->where('status', '1');     
            $this->db->where('store_id', $store_id);
            $rows  = $this->db->get('store_pickup_timeslot')->rows;   
            return $rows;
        }else{
            $this->db->group_by('day');
            $this->db->select('day', FALSE);
            $this->db->where('status', '1');     
            $this->db->where('store_id', $store_id);
            $rows  = $this->db->get('store_delivery_timeslot')->rows;               
            return $rows;
        }
    }

    public function getStoreTimeSlot($store_id,$method,$day){

        $shipping_method = explode('.',$method);

        if ($shipping_method[0] == 'pickup') {

            $this->db->where('day', $day);
            $this->db->select('timeslot', FALSE);
            $this->db->where('status', '1');     
            $this->db->where('store_id', $store_id);
            $rows  = $this->db->get('store_pickup_timeslot')->rows;   
            return $rows;
        }else{
            $this->db->where('day', $day);
            $this->db->select('timeslot', FALSE);
            $this->db->where('status', '1');     
            $this->db->where('store_id', $store_id);
            $rows  = $this->db->get('store_delivery_timeslot')->rows;               
            return $rows;
        }
    } 
    public function get_time_slot(){
        $store_id = $this->request->post['store_id'];
        $shipping_method = $this->session->data['shipping_method'][$store_id]['shipping_method']['code'];// $this->request->post['shipping_method'];


        //echo $shipping_method;die;

        $date =  $this->request->post['date'];
        $day = date('w',strtotime($date));


        $data['timeslot'] = $this->getStoreTimeSlot($store_id, $shipping_method,$day);


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/api/delivery_slot.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/api/delivery_slot.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/api/delivery_slot.tpl', $data));
        }

    }

    public function checkCurrentDateTs($store_id,$method){
        $storeDetail = $this->getStoreDetail($store_id);
        $timeDiff = $storeDetail['delivery_time_diff'];
        $day = date('w');
        $shipping_method = explode('.',$method);
        if ($shipping_method[0] == 'pickup') {
            $this->db->where('day', $day);
            $this->db->select('timeslot', FALSE);
            $this->db->where('status', '1');     
            $this->db->where('store_id', $store_id);
            $timeslots  = $this->db->get('store_pickup_timeslot')->rows;   
        }else{
            $this->db->where('day', $day);
            $this->db->select('timeslot', FALSE);
            $this->db->where('status', '1');     
            $this->db->where('store_id', $store_id);
            $timeslots  = $this->db->get('store_delivery_timeslot')->rows;               
        }
        $is_enabled = false;
        foreach ($timeslots as $timeslot) {
            $temp = explode('-', $timeslot['timeslot']);
            $is_enabled = $this->timeIsBetween($temp[0], $temp[1], date('h:ma'),$timeDiff);
        }
        return $is_enabled;


    } 
    private function timeIsBetween($from, $to, $time, $time_diff = false) {

        //calculate from_time in minuts 
        $i = explode(':', $to);
        if ($i[0] == 12) {
            $to_min = substr($i[1], 0, 2);
        } else {
            $to_min = ($i[0] * 60) + substr($i[1], 0, 2);
        }
        //if pm add 12 hours 
        $am_pm = substr($to, -2);
        if ($am_pm == 'pm')
            $to_min += 12 * 60;

        //calculate time in minuts             
        $i = explode(':', $time);
        if ($i[0] == 12) {
            $min = substr($i[1], 0, 2);
        } else {
            $min = $i[0] * 60 + substr($i[1], 0, 2);
        }

        //if pm add 12 hours 
        $am_pm = substr($time, -2);
        if ($am_pm == 'pm')
            $min += 12 * 60;

        //if time difference 
        if ($time_diff) {
            $i = explode(':', $time_diff);
            $min = $min + $i[0] * 60 + $i[1]; //add difference minut to current time
        }

        if ($min < $to_min) {
            return true;
        } else {
            return false;
        }
    }



    
    //save deliery date / timeslot 
    public function save(){
        
        if(isset($this->request->post['store_id'])){
            $store_id = $this->request->post['store_id'];
        }else{
            $store_id;
        }
        if(isset($this->request->post['date'])){
            $delivery_date = $this->request->post['date'];

            $this->session->data['dates'][$store_id] = $delivery_date;
        }else{
            $delivery_date = '';
        }
        
        if(isset($this->request->post['timeslot'])){
            $delivery_timeslot = $this->request->post['timeslot'];
            $this->session->data['timeslot'][$store_id] = $delivery_timeslot;
        }else{
            $delivery_timeslot = '';
        }
        exit;        
    }
}
