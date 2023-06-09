<?php

class ControllerApiVendorProduct extends Controller {

    public function getProductAutocomplete($data=array()) {
      
        $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        
        $sql = "SELECT p.*,pd.*,p2c.product_id product_id2 FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)" ;

        if (!empty($data['filter_store'])) {
            $sql .= " LEFT JOIN `".DB_PREFIX."product_to_store` ps on ps.product_id = p.product_id";
        }
        
        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        
        if (!empty($data['filter_store'])) {
            $sql .= ' AND ps.store_id="'.$data['filter_store'].'"';    
        }
        
        /*if ($this->user->isVendor()) {
            // $sql .= ' AND p.vendor_id="'.$this->user->getId().'"';
        }else{
            // $sql .= ' AND p.vendor_id!="0"';
        }
        */
      
        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '%" . $data['filter_name'] . "%'";
            //$this->db->like('product_description.name', $this->db->escape( $filter_name ) , 'both');
        }
      
        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $data['filter_model'] . "%'";
        }

       
        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $sql .= " AND p2c.category_id = '" . $data['filter_category'] . "'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            $lGroup = False;
            $sql .= " AND p2c.category_id = '" . $data['filter_category'] . "'";
        } else {
            $lGroup = True;
        }

        

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
        }

        //$sql .= " GROUP BY p.product_id";
        //$sql .= " LIMIT 10";
        //$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        //echo $sql;exit;
        $sort_data = array(
            'pd.name',
            'p.model',
            'p.price',
            'p2c.category_id',
            'p.quantity',
            'p.status',
            'p.sort_order'
        );
       
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
	
	}else{
            $sql .= " LIMIT 10";
        }

        $results = $query = $conn->query($sql);
        foreach ($results as $result) {

            $result['index'] = $result['name'];
            if (strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                $result['name'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
                $result['name'] = end($result['name']);
            }

            $json[] = array(
                'product_id' => $result['product_id'],
                'index' => $result['index'],
                'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))." - ".$result['unit'],
                'unit' => $result['unit'],
            );
	}
	 $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
	array_multisort($sort_order, SORT_ASC, $json);
	$resjson['status']=200;
	$resjson['data']=$json;
	$resjson['msg']='Product list fetched succesfully';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($resjson));
        //echo '<pre>';print_r($json);exit;
        //echo $sql;ext;
       
       // $query = $this->db->query($sql);
    }
}
