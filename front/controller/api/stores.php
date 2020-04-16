<?php


class ControllerApiStores extends Controller
{
     public function addNewstore($args = array()){
           $this->load->model('api/stores');

     
            $store_id = $this->model_api_stores->addStore($args);
            if($store_id){
            $json['success'] = 'Store created successfully';
			$json['status'] = 200;
			$json['data'] = $args;
       
			
			}else{
				$json['status'] = 400;
			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));

		
	}
    public function getStore($args = array())
    {
        $this->load->language('api/products');

        $json = array();

        //echo "api/product";

        //echo $args['id'];
        if (!isset($this->session->data['api_id']) || !isset($args['id']) ) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/stores');
            $this->load->model('setting/store');

            $json = $this->model_tool_image->getStore($args['id']);
            $tempstoreCategories =  $this->model_setting_store->getStoreCategories($args['id']);
            if(count($tempstoreCategories) > 0) {
                $temp_cat_stories = [];
                foreach ($tempstoreCategories as $temp) {
                    $temp['thumb'] = $this->model_tool_image->resize($temp['image'], 300, 300);
                    if($temp['parent_id'] == 0){
                    array_push($temp_cat_stories, $temp);
                    }
                }
            }
            
            $json['categories'] = $temp_cat_stories;

        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function getStores($args = array())
    {
        $this->load->language('api/products');

        $json = array();

        //echo "getStores";
        $this->load->model('api/stores');

        //echo $args['id'];
        if (!isset($this->session->data['api_id']) ) {
            $json['error'] = $this->language->get('error_permission');
        } else {

            $this->load->model('setting/setting');

            if(isset($this->request->get['filter_city'])) {
                $filter_city = $this->request->get['filter_city'];
            }else{
                $filter_city = '';
            }
        
            if(isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            }else{
                $filter_name = '';
            }
                
            if (isset($args['limit'])) {
                $limit = $args['limit'];
            } else {
                $limit = 10;
            }

            if (isset($args['start'])) {
                $start = $args['start'];
            } else {
                $start = 0;
            }

            if(isset($this->request->get['filter_status'])) {
                $filter_status = $this->request->get['filter_status'];
            }else{
                $filter_status = null;
            }
            
            if(isset($this->request->get['filter_date_added'])) {
                $filter_date_added = $this->request->get['filter_date_added'];
            }else{
                $filter_date_added = '';
            }
            
            if(isset($this->request->get['filter_vendor'])) {
                $filter_vendor = $this->request->get['filter_vendor'];
            }else{
                $filter_vendor = '';
            }
        
            if(isset($this->request->get['filter_vendor_id'])) {
                $filter_vendor_id = $this->request->get['filter_vendor_id'];
            }else{
                $filter_vendor_id = '';
            }

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'pd.name';
            }

            if (isset($this->request->get['order'])) {
                $order = $this->request->get['order'];
            } else {
                $order = 'ASC';
            }

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

           
            $data['stores'] = array();

            $filter_data = array(
                'filter_name' => $filter_name,
                'filter_vendor_id' => $filter_vendor_id,
                'filter_city' => $filter_city,
                'filter_date_added' => $filter_date_added,
                'filter_vendor' => $filter_vendor,
                'filter_status' => $filter_status,
                'sort' => $sort,
                'order' => $order,
                'start' => $start,
                'limit' => $limit
            );
            
            $results = $this->model_api_stores->getStores($filter_data);
            $total = $this->model_api_stores->getTotalStores($filter_data);
            
            $json['stores_count'] = $total;
            foreach ($results as $result) {
                $json['stores'][] = array(
                    'store_id' => $result['store_id'],
                    'name' => $result['name'],
                    'city' => $result['city'],
                    'address'     => $result['address'],  
                    'zipcode'     => $result['zipcode'],      
                    'status'     => $result['status']
                );
            }

        }

        //echo "<pre>";print_r($json);die;
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editStore($args = array())
    {
        $this->load->language('api/products');

        /*echo "editStore";

        echo "<pre>";print_r($args);die;*/
        $json = array();

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/stores');
            // $args['id'] this should be store product id

            //echo "<pre>";print_r($args);die;
            

            $this->model_api_stores->editStore($args['id'], $args);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteStores($args = []) {

        echo "delete";
        $this->load->model('api/stores');

        if (!isset($this->session->data['api_id']) || !isset($args['id']) || !$this->validateDelete($args['id']) ) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->model_api_stores->deleteStore($args['id']);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    protected function validateDelete($store_id) {

        $this->load->model('api/stores');

        $store_total = $this->model_api_stores->getTotalOrdersByStoreId($store_id);

        //echo "<pre>";print_r($store_total);die;
        if ($store_total) {
            return false;
        }

        return true;
        
    }
}
