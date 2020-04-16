<?php

class ModelCatalogVendorProduct extends Model {

	public function addProduct( $data ) {
           

		$this->trigger->fire( 'pre.admin.product.add', $data );
		
		if ($this->user->isVendor()) {
        	$data['status'] = $this->config->get('config_auto_approval_product');
        }



		$this->db->query( "INSERT INTO " . DB_PREFIX . "product_to_store SET  product_id = '".$data['product_id']."', store_id = '" . $this->db->escape( $data['product_store'] ) . "', price = '" . $data['price'] . "',special_price = '" . $data['special_price'] . "',tax_percentage = '" . $data['tax_percentage'] . "',quantity = '" . $data['quantity'] . "',min_quantity = '" . $data['min_quantity'] . "',subtract_quantity = '" . $data['subtract_quantity'] . "',status = '" . $data['status'] . "',tax_class_id = '" . $data['tax_class_id'] . "'" );
		$product_store_id = $this->db->getLastId();

			foreach ( $data['product_variation']['variation'] as $prv => $value ) {
				$this->db->query( "INSERT INTO " . DB_PREFIX . "variation_to_product_store SET  variation_id = '".$value."', product_store_id = '" .$product_store_id . "', price = '" .$data['product_variation']['price'][$prv] . "',special_price = '" . $data['product_variation']['special_price'][$prv] . "'" );
			}

		$this->cache->delete( 'product' );

		$this->trigger->fire( 'post.admin.product.add', $product_id );

		return $product_id;
	}


	public function editProduct($store_product_id, $data) {
		$this->trigger->fire('pre.admin.product.edit', $data);

		if ($this->user->isVendor() && !$this->config->get('config_auto_approval_product') ) {
        	$data['status'] = $this->config->get('config_auto_approval_product');
        }

		$query =  "UPDATE " . DB_PREFIX . "product_to_store SET product_id = '".$data['product_id']."', store_id = '" . $this->db->escape( $data['product_store'] ) . "', price = '" . $data['price'] . "',special_price = '" . $data['special_price'] . "',tax_percentage = '" . $data['tax_percentage'] . "',quantity = '" . $data['quantity'] . "',min_quantity = '" . $data['min_quantity'] . "',subtract_quantity = '" . $data['subtract_quantity'] . "',status = '" . $data['status'] . "',tax_class_id = '" . $data['tax_class_id'] . "' WHERE product_store_id = '" . (int) $store_product_id . "'";

		
		$this->db->query($query);
		
		
		//  delete variation here
		$this->db->query("DELETE FROM " . DB_PREFIX . "variation_to_product_store WHERE product_store_id = '" . (int) $store_product_id . "'");
		// insert variation
		if (isset( $this->request->post['product_variation']['variation'])) {
		
			foreach ( $this->request->post['product_variation']['variation'] as $prv => $value ) {
				$this->db->query( "INSERT INTO " . DB_PREFIX . "variation_to_product_store SET  variation_id = '".$value."', product_store_id = '" .$store_product_id . "', price = '" .$this->request->post['product_variation']['price'][$prv] . "',special_price = '" . $this->request->post['product_variation']['special_price'][$prv] . "'" );

			}
		}
		$this->trigger->fire( 'post.admin.product.edit', $store_product_id );

		return $product_id;
	} 

	public function getProduct( $product_store_id ) {

		$query = $this->db->query( "SELECT DISTINCT p.*,pd.name,v.user_id as vendor_id FROM " . DB_PREFIX . "product_to_store p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "store st ON (st.store_id = p.store_id) LEFT JOIN " . DB_PREFIX . "user v ON (v.user_id = st.vendor_id) WHERE p.product_store_id = '" . (int) $product_store_id . "' AND pd.language_id = '" . (int) $this->config->get( 'config_language_id' ) . "'" );

		$product = $query->row;


		return $product;
	}

	public function getProductDetail( $p_id ) {

		$query = $this->db->query( "SELECT * from ". DB_PREFIX . "product WHERE product_id = '" . $p_id . "'" );

		$product = $query->row;


		return $product;
	}

	public function getProducts( $data = array() ) {
		

		$sql = "SELECT ps.*,p2c.product_id,pd.name as product_name ,p.*,st.name as store_name,v.firstname as fs,v.lastname as ls,ps.status as sts,v.user_id as vendor_id from ".DB_PREFIX ."product_to_store ps LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = ps.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "store st ON (st.store_id = ps.store_id) LEFT JOIN " . DB_PREFIX . "user v ON (v.user_id = st.vendor_id)";

		$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_store_id'])) {
			$sql .=  " AND st.name LIKE '" . $this->db->escape($data['filter_store_id']) . "%'";
		}

		if ($this->user->isVendor()) {
            $sql .= ' AND v.user_id="'.$this->user->getId().'"';
        }


		if (!empty($data['filter_vendor_name'])) {
			$sql .= " AND v.firstname LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'";
			$sql .= " OR v.lastname LIKE '" . $this->db->escape($data['filter_vendor_name']) . "%'";
		}

		if ( !empty( $data['filter_model'] ) ) {
            $sql .= " AND p.model LIKE '" . $this->db->escape( $data['filter_model'] ) . "%'";
        }

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {

			if(!$data['filter_price']) {

				$sql .= " AND ps.price = '" . $this->db->escape($data['filter_price']) . "'";

			} else {
				$sql .= " AND (ps.price = '" . $this->db->escape($data['filter_price']) . "' or ps.special_price = '" . $this->db->escape($data['filter_price']) . "' )";
			}			
		}

		if ( !empty( $data['filter_product_id_from'] ) ) {
            $sql .= " AND ps.product_store_id >= '" . (int)$data['filter_product_id_from'] . "'";
        }

        if ( !empty( $data['filter_product_id_to'] ) ) {
            $sql .= " AND ps.product_store_id <= '" . (int)$data['filter_product_id_to'] . "'";
        }


		if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
			$sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
		}

		if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
			$lGroup = False;
			$sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
		} else {
			$lGroup = True;
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			
			if ($data['filter_quantity'] == 0) {
				$sql .= " AND ps.quantity = '" . (int) $data['filter_quantity'] . "'";
			}else{
				$sql .= " AND ps.quantity <= '" . (int) $data['filter_quantity'] . "' AND ps.quantity > '0'";
			}

		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND ps.status = '" . (int) $data['filter_status'] . "'";
		}

		$sort_data = array(
			'pd.name',
			'p.price',
			'p.product_id',
			'ps.product_store_id',
			'p2c.category_id',
			'ps.quantity',
			'p.model',
			'ps.status',
			'st.name',
		);


		$sql .= ' GROUP BY ps.product_store_id';
		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if ( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) ) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if ( isset( $data['start'] ) || isset( $data['limit'] ) ) {
			if ( $data['start'] < 0 ) {
				$data['start'] = 0;
			}

			if ( $data['limit'] < 1 ) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}

		// echo $sql;die;
		$query = $this->db->query( $sql );

		return $query->rows;

	}


	public function getTotalProducts( $data = array() ) {

		$sql = "SELECT Distinct product_store_id from ".DB_PREFIX ."product_to_store ps LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = ps.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "store st ON (st.store_id = ps.store_id) LEFT JOIN " . DB_PREFIX . "user v ON (v.user_id = st.vendor_id)";
		$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
		if (!empty($data['filter_store_id'])) {
			$sql .=  " AND st.name LIKE '" . $this->db->escape($data['filter_store_id']) . "%'";
		}
		if ($this->user->isVendor()) {
            $sql .= ' AND v.user_id="'.$this->user->getId().'"';
        }

        if ( !empty( $data['filter_model'] ) ) {
            $sql .= " AND p.model LIKE '" . $this->db->escape( $data['filter_model'] ) . "%'";
        }
        
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			//$sql .= " AND ps.price = '" . $this->db->escape($data['filter_price']) . "'";
			$sql .= " AND (ps.price = '" . $this->db->escape($data['filter_price']) . "' or ps.special_price = '" . $this->db->escape($data['filter_price']) . "' )";
		}

		if ( !empty( $data['filter_product_id_from'] ) ) {
            $sql .= " AND ps.product_store_id >= '" . (int)$data['filter_product_id_from'] . "'";
        }

        if ( !empty( $data['filter_product_id_to'] ) ) {
            $sql .= " AND ps.product_store_id <= '" . (int)$data['filter_product_id_to'] . "'";
        }
        

		 if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
			$sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
		}

		if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
			$lGroup = False;
			$sql .= " AND p2c.category_id = '" . $this->db->escape($data['filter_category']) . "'";
		} else {
			$lGroup = True;
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {

			if ($data['filter_quantity'] == 0 ) {
				$sql .= " AND ps.quantity = '" . (int) $data['filter_quantity'] . "'";
			}else{
				$sql .= " AND ps.quantity <= '" . (int) $data['filter_quantity'] . "' AND ps.quantity > '0'";
			}

		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND ps.status = '" . (int) $data['filter_status'] . "'";
		}

		$sql .= ' GROUP BY ps.product_store_id';
		
		$query = $this->db->query( $sql );
		return count( $query->rows );
	}


	public function copyGeneralProduct($product_id) {

		$someExist = false;

		$status = 0;

		if ($this->user->isVendor()) {
        	$status = $this->config->get('config_auto_approval_product');
        }
        $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int) $product_id. "'" );

        $product_info = $query->row;

        if($product_info) {
        	$variations_id = explode(",",$product_info['variations_id']);
        	
			foreach ($this->request->post['product_store'] as $store_id) {

				$product_to_store_ids = [];

				foreach ($variations_id as $variation) {
					$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation. "' AND p.store_id = '" . (int) $store_id. "'");

			        $exists = $query->row;

			        if($exists) {
			        	array_push($product_to_store_ids, $exists['product_store_id']);
			        }
				}
				
				$product_to_store_ids = implode(",",$product_to_store_ids);

				$Countquery = $this->db->query( "SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id. "' AND  store_id = '" . $store_id."'" );

		        $countExists = $Countquery->row;
		        
		        if($countExists['count'] >= 1) {
		        	$someExist = true;
		        } else {
		        	$this->db->query( "INSERT INTO " . DB_PREFIX . "product_to_store SET  product_id = '".$product_id."', quantity = 0, price = '" . $product_info['default_price']."', store_id = '" . $store_id."',status = '" . $status. "'" );
		        }
			}
			return $someExist;
        }
	}

	public function copyAllGeneralProduct() {

		$someExist = false;

		$status = 0;
		
		if ($this->user->isVendor()) {
        	$status = $this->config->get('config_auto_approval_product');
        }
        $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product" );

        //echo "<pre>";print_r($query->rows);
        foreach ($query->rows as $product_info) {

        	//echo "<pre>";print_r($product_info);
	        if($product_info) {

	        	$product_id = $product_info['product_id'];

	        	$variations_id = explode(",",$product_info['variations_id']);
	        	
				foreach ($this->request->post['product_store'] as $store_id) {

					$product_to_store_ids = [];

					foreach ($variations_id as $variation) {
						$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation. "' AND p.store_id = '" . (int) $store_id. "'");

				        $exists = $query->row;

				        if($exists) {
				        	array_push($product_to_store_ids, $exists['product_store_id']);
				        }
					}
					
					$product_to_store_ids = implode(",",$product_to_store_ids);

					$Countquery = $this->db->query( "SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id. "' AND  store_id = '" . $store_id."'" );

			        $countExists = $Countquery->row;
			        
			        if($countExists['count'] >= 1) {
			        	$someExist = true;
			        } else {
			        	$this->db->query( "INSERT INTO " . DB_PREFIX . "product_to_store SET  product_id = '".$product_id."', quantity = 0, price = '" . $product_info['default_price']."', store_id = '" . $store_id."',status = '" . $status. "'" );
			        }
				}
				
	        }
	    }

	    return $someExist;

	    
	}


	public function copyGeneralProductVariations($product_id) {

		
		if ($this->user->isVendor()) {
        	$status = $this->config->get('config_auto_approval_product');
        }
        $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int) $product_id. "'" );

        $product_info = $query->row;

        



        if($product_info) {
        	$variations_id = explode(",",$product_info['variations_id']);
        	
			foreach ($this->request->post['product_store'] as $store_id) {


				$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id. "' AND p.store_id = '" . (int) $store_id. "'");

		        $exists = $query->row;

		        if($exists) {
		        	$product_to_store_ids = [];
		        	$product_store_id = $exists['product_store_id'];

					foreach ($variations_id as $variation) {
						$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation. "' AND p.store_id = '" . (int) $store_id. "'");

				        $exists = $query->row;

				        if($exists) {
				        	array_push($product_to_store_ids, $exists['product_store_id']);

				        	$this->updateVendorProductVariations($variation);
				        }
					}
					
					$product_to_store_ids = implode(",",$product_to_store_ids);

			        $this->db->query( "UPDATE " . DB_PREFIX . "product_to_store SET product_to_store_ids = '" . $product_to_store_ids. "' WHERE product_store_id='".$product_store_id."'" );
			        
		        }
			}
        }
	}

	public function copyAllGeneralProductVariations() {

		
		if ($this->user->isVendor()) {
        	$status = $this->config->get('config_auto_approval_product');
        }

        $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product" );

        foreach ($query->rows as $product_info) {



	        if($product_info) {

	        	$product_id = $product_info['product_id'];

	        	$variations_id = explode(",",$product_info['variations_id']);
	        	
				foreach ($this->request->post['product_store'] as $store_id) {


					$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id. "' AND p.store_id = '" . (int) $store_id. "'");

			        $exists = $query->row;

			        if($exists) {
			        	$product_to_store_ids = [];
			        	$product_store_id = $exists['product_store_id'];

						foreach ($variations_id as $variation) {
							$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation. "' AND p.store_id = '" . (int) $store_id. "'");

					        $exists = $query->row;

					        if($exists) {
					        	array_push($product_to_store_ids, $exists['product_store_id']);

					        	$this->updateVendorProductVariations($variation);
					        }
						}
						
						$product_to_store_ids = implode(",",$product_to_store_ids);

				        $this->db->query( "UPDATE " . DB_PREFIX . "product_to_store SET product_to_store_ids = '" . $product_to_store_ids. "' WHERE product_store_id='".$product_store_id."'" );
				        
			        }
				}
	        }
	    }
	}


	public function updateVendorProductVariations($product_id) {

		
		if ($this->user->isVendor()) {
        	$status = $this->config->get('config_auto_approval_product');
        }
        $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int) $product_id. "'" );

        $product_info = $query->row;

        



        if($product_info) {
        	$variations_id = explode(",",$product_info['variations_id']);
        	
			foreach ($this->request->post['product_store'] as $store_id) {


				$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $product_id. "' AND p.store_id = '" . (int) $store_id. "'");

		        $exists = $query->row;

		        if($exists) {
		        	$product_to_store_ids = [];
		        	$product_store_id = $exists['product_store_id'];

					foreach ($variations_id as $variation) {
						$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store p WHERE p.product_id = '" . (int) $variation. "' AND p.store_id = '" . (int) $store_id. "'");

				        $exists = $query->row;

				        if($exists) {
				        	array_push($product_to_store_ids, $exists['product_store_id']);
				        }
					}
					
					$product_to_store_ids = implode(",",$product_to_store_ids);

			        $this->db->query( "UPDATE " . DB_PREFIX . "product_to_store SET product_to_store_ids = '" . $product_to_store_ids. "' WHERE product_store_id='".$product_store_id."'" );
			        
		        }
			}
        }
	}


	public function getProductCategories( $product_id ) {
		$product_category_data = array();

		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'" );

		foreach ( $query->rows as $result ) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}


	public function getProductStores( $product_store_id ) {
		$product_store_data = array();

		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_store_id = '" . (int) $product_store_id . "'" );

		foreach ( $query->rows as $result ) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}


	public function getProductVariations( $product_store_id ) {
		
		$query = "SELECT * FROM " . DB_PREFIX . "product_to_store pv WHERE product_store_id ='" . (int) $product_store_id . "'";

		$query = $this->db->query( $query );

		if(!empty($query->row['product_to_store_ids']) ) {
			$all_variations = "SELECT * FROM " . DB_PREFIX . "product_to_store ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE product_store_id IN (" .$query->row['product_to_store_ids'] . ")";

			$result = $this->db->query( $all_variations );
			return $result->rows;
		}
	}

	public function getProductStoreVariations( $product_store_id ) {

		$query = "SELECT * FROM " . DB_PREFIX . "variation_to_product_store vps  LEFT JOIN " . DB_PREFIX . "product_variation pv ON (vps.variation_id = pv.id)  WHERE product_store_id = '" . (int) $product_store_id . "'  ORDER BY sort_order ASC ";
		$query = $this->db->query( $query );
		//$this->db->last_query();die;
		return $query->rows;
	}

	public function getStoreProducts( $data = array() ) {
        

        $sql = "SELECT ps.*,p2c.product_id,pd.name,p.*,st.name as store_name,v.firstname as fs,v.lastname as ls,ps.status as sts,v.user_id as vendor_id from ".DB_PREFIX ."product_to_store ps LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = ps.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "store st ON (st.store_id = ps.store_id) LEFT JOIN " . DB_PREFIX . "user v ON (v.user_id = st.vendor_id)";

        $sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";
        if (!empty($data['filter_store'])) {
            $sql .=  " AND st.store_id  = '" . $this->db->escape($data['filter_store']) . "'";
        }


        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND ps.status = '" . (int) $data['filter_status'] . "'";
        }

        $sql .= ' GROUP BY ps.product_store_id';
        $query = $this->db->query( $sql );

        return $query->rows;

    }		

	public function copyProduct($store_product_id) {
		$query = $this->db->query( "SELECT DISTINCT p.* FROM " . DB_PREFIX . "product_to_store p  WHERE p.product_store_id = '" . (int) $store_product_id . "'" );


		if ($query->num_rows) {
			$data = $query->row;

			//$data['product_variation'] = $this->getProductVariations($store_product_id);
			if ($this->user->isVendor()) {
	        	$status = $this->config->get('config_auto_approval_product');
	        }else{
	        	$status = $data['status'];
	        }
			$this->db->query( "INSERT INTO " . DB_PREFIX . "product_to_store SET  product_id = '".$data['product_id']."', store_id = '" . $this->db->escape( $data['store_id'] ) . "', price = '" . $data['price'] . "',special_price = '" . $data['special_price'] . "',tax_percentage = '" . $data['tax_percentage'] . "',quantity = '" . $data['quantity'] . "',min_quantity = '" . $data['min_quantity'] . "',subtract_quantity = '" . $data['subtract_quantity'] . "',status = '" . $status . "'" );
			$product_store_id = $this->db->getLastId();
		}
	}

	 public function deleteProduct($store_product_id) {
		
		$this->trigger->fire('pre.admin.product.delete', $store_product_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_store_id = '" . (int) $store_product_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "variation_to_product_store WHERE product_store_id = '" . (int) $store_product_id . "'");
		
		$this->cache->delete('product');

		$this->trigger->fire('post.admin.product.delete', $product_id);
	}
}
