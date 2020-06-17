<?php


class ModelAssetsProduct extends Model {

	public function getCategoryDiscount( $product_id, $price ) {
		$sql  = 'select c.discount from `'.DB_PREFIX.'product_to_category` pc';
		$sql .= ' INNER JOIN `'.DB_PREFIX.'category` c on c.category_id = pc.category_id';
		$sql .= ' WHERE pc.product_id = "'.$product_id.'"';
		$sql .= ' GROUP BY pc.category_id';

		$rows = $this->db->query( $sql )->rows;

		$max_discount = 0;

		foreach ( $rows as $row ) {
			if ( $row['discount'] > $max_discount ) {
				$max_discount = $row['discount'];
			}
		}

		if ( $max_discount > 0 ) {
			$dicount =  $price * $max_discount / 100;
			$final_price = round( $price - $dicount, 2 );
			return $final_price;
		}else {
			return false;
		}
	}

	public function getProductByProductStoreId( $product_store_id, $is_admin = false ) {
		//$store_id = $this->session->data['config_store_id'];
		
		$this->db->select('product_to_store.*,product_description.*,product.unit,product.model,product.image', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');
		$this->db->group_by('product_to_store.product_store_id');
	    //$this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    //$this->db->where('product.status',1);
	    $this->db->where('product_to_store.product_store_id',$product_store_id);
		$ret = $this->db->get('product_to_store')->row;
		return $ret;

	}
	
	public function getProductsForCron( $data = array() ) {
		$store_id = $data['store_id'];


		$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->join('category_path', 'category_path.category_id = product_to_category.category_id', 'left');
	        }
		}	
		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->where('category_path.path_id', (int)$data['filter_category_id']);
	        } else {
	        	  $this->db->where('product_to_category.category_id', (int)$data['filter_category_id']);

	        }
	    }

		if ( !empty( $data['filter_name'] )) {
        
	        if ( !empty( $data['filter_name'] ) ) {

                //$this->db->like('product_description.name', $this->db->escape_str( $data['filter_name'] ), 'both');
                $this->db->like('product_description.name', $this->db->escape_str( $data['filter_name'] ));

                /*$this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ), 'none');*/
	        }
		}	

	    $sort_data = array(
	        'product_description.name',
	        'product.model',
	        'product_to_store.quantity',
	        'product_to_store.price',
	        'product.sort_order',
	        'product.date_added'
		);

		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
		       	$this->db->order_by($data['sort'], 'asc');
	        }else {
				$this->db->order_by($data['sort'], 'asc');
	        }
		} else {
		    $this->db->order_by('product.sort_order', 'asc');      
		}	

		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
	    $this->db->where('product.status',1);
		$ret = $this->db->get('product_to_store')->rows;
		//die;
		// echo $this->db->last_query();die;
		return $ret;
	}
	
	public function updateViewed( $product_id ) {
		$this->db->query( "UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'" );
	}

	//old Variations

	/*public function getVariations( $product_store_id ) {
		$this->db->join('product_variation', 'product_variation.id = variation_to_product_store.variation_id', 'left');
		$this->db->where('product_store_id', $product_store_id);
		$return = $this->db->get('variation_to_product_store')->rows;
		return $return;
	}*/

	//new Variations

	public function getVariations( $product_store_id ) {
		
		$returnData = [];

		$query = "SELECT * FROM " . DB_PREFIX . "product_to_store pv WHERE product_store_id ='" . (int) $product_store_id . "'";

		$query = $this->db->query( $query );

		if(!empty($query->row['product_to_store_ids']) ) {
			$all_variations = "SELECT * FROM " . DB_PREFIX . "product_to_store ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE product_store_id IN (" .$query->row['product_to_store_ids'] . ")";

			$result = $this->db->query( $all_variations );

			foreach ($result->rows as $r) {
				if($r['quantity'] > 0 && $r['status']) {
					array_push($returnData, $r);	
				}
			}

			//return $result->rows;
			return $returnData;
		}
	}

	public function getApiVariations( $product_store_id ) {
			
		$this->load->model('tool/image');

		$query = "SELECT * FROM " . DB_PREFIX . "product_to_store pv WHERE product_store_id ='" . (int) $product_store_id . "'";

		$query = $this->db->query( $query );

		if(!empty($query->row['product_to_store_ids']) ) {
			$all_variations = "SELECT *,ps.status as vendor_status FROM " . DB_PREFIX . "product_to_store ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE product_store_id IN (" .$query->row['product_to_store_ids'] . ")";

			

			$new_result = [];

			$result = $this->db->query( $all_variations );

			foreach ($result->rows as $res) {

				if($res['quantity'] <= 0 || !$res['status'] || !$res['vendor_status']) {
					continue;
				}

				if ( file_exists( DIR_IMAGE .$res['image'] ) ) {
					$res['image'] = $this->model_tool_image->resize( $res['image'], 362, 317 );
				} else {
					$res['image'] = $this->model_tool_image->resize( 'placeholder.png', 362, 317 );
				}

				$s_price = 0;
            	$o_price = 0;

				if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
					//get price html
					if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
						$res['price'] = $this->tax->calculate( $res['price'], $res['tax_class_id'], $this->config->get( 'config_tax' ));

						$o_price = $this->tax->calculate( $res['price'], $res['tax_class_id'], $this->config->get( 'config_tax' ) );

					} else {
						$res['price'] = false;
					}
					if ( (float) $res['special_price'] ) {
						$res['special_price'] = $this->tax->calculate( $res['special_price'], $res['tax_class_id'], $this->config->get( 'config_tax' ));

						$s_price = $this->tax->calculate( $res['special_price'], $res['tax_class_id'], $this->config->get( 'config_tax' ) );

					} else {
						$res['special_price'] = false;
					}
				}else {

					$s_price = $res['special_price'];
		            $o_price = $res['price'];

					if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
						//$res['price'] = $res['price'];
						$res['price'] = $this->currency->formatWithoutCurrency($res['price']);
					} else {
						$res['price'] = $res['price'];
					}

					if ( (float) $res['special_price'] ) {
						//$res['special_price'] = $res['special_price'];
						$res['special_price'] = $this->currency->formatWithoutCurrency($res['special_price']);
					} else {
						$res['special_price'] = $res['special_price'];
					}
				}
	            
	            if ($res['name'] && isset($res['pd_name'] ) ) {
					$res['name'] = $res['pd_name'];
				}

				$percent_off = null;
	            if(isset($s_price)  && isset($o_price) && $o_price !=0 && $s_price !=0) {
	                $percent_off = (($o_price - $s_price) / $o_price) * 100;
	            }

	           
	            /*if(is_null($res['special_price'])) {
	            	$res['special_price'] = $res['price'];
	            }*/
	            if(is_null($res['special_price']) || !($res['special_price'] + 0) )  {
	            	$res['special_price'] = $res['price'];
	            }


	            $res['max_qty'] = $res['min_quantity'] > 0 ? $res['min_quantity'] : $res['quantity'];
	            
	            $res['percent_off'] = number_format($percent_off,0);

				$new_result[] = $res;
			}
			
			return $new_result;
		}
	}


	public function getVariation( $store_product_variation_id ) {

		$this->db->select('variation_to_product_store.*,product.*', FALSE);
		$this->db->join('product', 'product.product_id = variation_to_product_store.variation_id', 'left');
		$this->db->group_by('variation_to_product_store.product_store_id');
	    $this->db->where('variation_to_product_store.product_variation_store_id',$store_product_variation_id);
		$return = $this->db->get('variation_to_product_store')->row;

		return $return;

		/*$this->db->join('product_variation', 'product_variation.id = variation_to_product_store.variation_id', 'left');
		$this->db->where('product_variation_store_id', $store_product_variation_id);
		$return = $this->db->get('variation_to_product_store')->row;
		return $return;*/
	}

	public function getProduct( $product_store_id, $is_admin = false,$store_id=null) {
        if(isset($this->session->data['config_store_id'])){
		   $store_id = $this->session->data['config_store_id'];
		}else{
			$store_id = ACTIVE_STORE_ID;
		}
		$this->db->select('product_to_store.*,product_description.*,product.unit,product.image', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    //$this->db->where('product.status',1);
	    $this->db->where('product_to_store.product_store_id',$product_store_id);
		$ret = $this->db->get('product_to_store')->row;
		return $ret;

	}

	public function getProductStoreId( $product_id,$store_id) {
		
		$query = $this->db->query("SELECT * from  " . DB_PREFIX . "product_to_store where store_id = " . (int) $store_id." and product_id = " .$product_id);
        
        return $query->row;
	}

	public function getProductStoreIdAvailable( $product_id,$store_id) {
		
		$query = $this->db->query("SELECT * from  " . DB_PREFIX . "product_to_store where store_id = " . (int) $store_id." and product_id = " .$product_id." and status = 1 and quantity > 0");
        
        return $query->row;
	}


	

	public function getProductForPopup( $product_store_id, $is_admin = false, $store_id ) {
		if(!isset($store_id)){
			$store_id = $this->session->data['config_store_id'];
		}
		$this->db->select('product_to_store.*,product_description.*,product.*,product_description.name as pd_name', FALSE);
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
	    $this->db->where('product_to_store.product_store_id',$product_store_id);
		$ret = $this->db->get('product_to_store')->row;
		return $ret;

	}

	public function getProductVariations( $product_name ) {
		
		$returnData = [];
		
			$all_variations = "SELECT * ,product_store_id as variation_id FROM " . DB_PREFIX . "product_to_store ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE name = '$product_name'";

			//echo $all_variations;die;
			$result = $this->db->query( $all_variations );

			foreach ($result->rows as $r) {
				if($r['quantity'] > 0 && $r['status']) {



					if ((float)$r['special_price']) {
						$r['special_price'] = $this->currency->format($this->tax->calculate($r['special_price'], $r['tax_class_id'], $this->config->get('config_tax')));
	 
	
					} else {
						$r['special_price'] = false;
					}
					
					
					// $r['variation_id'] => $result['product_store_id'],
                    //         'unit' => $result['unit'],
                    //         'weight' => floatval($result['weight']),
                    //         'price' => $price,
                    //         'special' => $special_price
					array_push($returnData, $r);	
				}
				
			}

			 
			return $returnData;
		
	}


	public function getProductForPopupByApi( $store_id,$product_store_id, $is_admin = false ) {
		
		$this->db->select('product_to_store.*,product_description.*,product.*,product_description.name as pd_name', FALSE);

		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    //$this->db->where('product_to_store.status', 1);
	    $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
	    $this->db->where('product_to_store.product_store_id',$product_store_id);
		$ret = $this->db->get('product_to_store')->row;
		return $ret;

	}

	

	public function getDetailproduct($product_store_id){

		
		$this->db->select('product_to_store.*,product.*,product_description.*,product_description.name as pd_name', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product.status',1);
	    $this->db->where('product_to_store.product_store_id',$product_store_id);
		$ret = $this->db->get('product_to_store')->row;
		
		return $ret;
	}



	public function getProducts( $data = array() ) {
		if($this->session->data['config_store_id']){
		$store_id = $this->session->data['config_store_id'];
		}else{
		$store_id = $data['store_id'];
		}


		$this->db->select('product_to_store.*,product_to_category.category_id,product.*,product_description.*,product_description.name as pd_name', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->join('category_path', 'category_path.category_id = product_to_category.category_id', 'left');
	        }
		}	
		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->where('category_path.path_id', (int)$data['filter_category_id']);
	        } else {
	        	  $this->db->where('product_to_category.category_id', (int)$data['filter_category_id']);

	        }
	    }

		if ( !empty( $data['filter_name'] )) {
        
	        if ( !empty( $data['filter_name'] ) ) {
                
                // original 
                
                $this->db->like('product_description.name', $this->db->escape_str( $data['filter_name'] ) , 'both');

	        	//working try 0

	        	/*$searchCSV = implode(",",explode(" ",$data['filter_name']));
                $this->db->where('(MATCH('. DB_PREFIX .'product_description.name) AGAINST("'.$searchCSV.'"))');*/

                //try 1
                
                

                /*$search_text = $this->db->escape( $data['filter_name'] );
                $search_text1 = $this->db->escape( $data['filter_name'] ) .' ';
                $search_text2 = ' '.$this->db->escape( $data['filter_name'] );
                $search_text3 = ' '.$this->db->escape( $data['filter_name'] ) .' ';


                $this->db->where('(hf7_product_description.name ="'.$search_text.'" OR hf7_product_description.name ="'.$search_text1 .'" OR hf7_product_description.name ="'.$search_text2 .'" OR hf7_product_description.name ="'.$search_text3 .'")', NULL, FALSE);*/

                //$this->db->where("product_description.name REGEXP '[[:<:]]pencil[[:>:]]'");

                
                //try 2

                /*
				$this->db->group_start();

                $this->db->or_like('product_description.name', ' '.$this->db->escape( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.nasme', $this->db->escape( $data['filter_name'] ), 'none');

                $this->db->group_end();*/

	        }
		}	


        if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
        	$offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
        	$limit = $data['limit'];
        }

	    $sort_data = array(
	        'product_description.name',
	        'product.model',
	        'product_to_store.quantity',
	        'product_to_store.price',
	        'product.sort_order',
	        'product.date_added'
		);

		/*if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
		       	$this->db->order_by($data['sort'], 'asc');
	        }else {
				$this->db->order_by($data['sort'], 'asc');
	        }
		} else {
		    $this->db->order_by('product.sort_order', 'asc');      
		}*/	

		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_to_store.quantity >=', 1);
	    $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
	    $this->db->where('product.status',1);
		$ret = $this->db->get('product_to_store', $limit, $offset)->rows;
		//die;
//		echo $this->db->last_query();die;

//		echo "<pre>";print_r($ret);die;
		return $ret;
	}

	
	public function getCollectionProducts( $data = array() ) {
		$store_id = $this->session->data['config_store_id'];

		$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		$product_collection_product_ids = [];

		if ( !empty( $data['filter_product_collection_id'] ) ) {
	        
	        $product_collection_product_ids = $this->getProductCollectionProducts($data['filter_product_collection_id']);
	        
		}	
		

		if ( !empty( $data['filter_name'] ) || !empty( $data['filter_tag'] ) ) {

	        if ( !empty( $data['filter_name'] ) ) {

                $this->db->like('product_description.name', $this->db->escape_str( $data['filter_name'] ) , 'both');

                /*$this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ), 'none');*/
	        }

        	
		}	


        if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
        	$offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
        	$limit = $data['limit'];
        }
	    $sort_data = array(
	        'product_description.name',
	        'product.model',
	        'product_to_store.quantity',
	        'product_to_store.price',
	        'product.sort_order',
	        'product.date_added'
		);
		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
		       	$this->db->order_by($data['sort'], 'asc');
	        }else {
				$this->db->order_by($data['sort'], 'asc');
	        }
		} else {
		    $this->db->order_by('product.sort_order', 'asc');      
		}	
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product.status',1);
	    $this->db->where_in('product.product_id',implode(",",$product_collection_product_ids));
		$ret = $this->db->get('product_to_store', $limit, $offset)->rows;
		// echo $this->db->last_query();die;
		return $ret;
	}

	public function getCollectionProductsApi( $data = array() ) {
		$store_id = $data['store_id'];

		$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		$product_collection_product_ids = [];

		if ( !empty( $data['filter_product_collection_id'] ) ) {
	        
	        $product_collection_product_ids = $this->getProductCollectionProducts($data['filter_product_collection_id']);
	        
		}	
		

		if ( !empty( $data['filter_name'] ) || !empty( $data['filter_tag'] ) ) {
        
	        if ( !empty( $data['filter_name'] ) ) {

                $this->db->like('product_description.name', $this->db->escape( $data['filter_name'] ) , 'both');

                /*$this->db->or_like('product_description.name', ' '.$this->db->escape( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.name', $this->db->escape( $data['filter_name'] ), 'none');*/
	        }
        	
		}	


        if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
        	$offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
        	$limit = $data['limit'];
        }
	    $sort_data = array(
	        'product_description.name',
	        'product.model',
	        'product_to_store.quantity',
	        'product_to_store.price',
	        'product.sort_order',
	        'product.date_added'
		);
		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
		       	$this->db->order_by($data['sort'], 'asc');
	        }else {
				$this->db->order_by($data['sort'], 'asc');
	        }
		} else {
		    $this->db->order_by('product.sort_order', 'asc');      
		}	
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product.status',1);
	    $this->db->where_in('product.product_id',implode(",",$product_collection_product_ids));
		$ret = $this->db->get('product_to_store', $limit, $offset)->rows;
		// echo $this->db->last_query();die;
		return $ret;
	}

	public function getOfferProductsBySpecialPrice( $data = array() ) {
		$store_id = $this->session->data['config_store_id'];
		$language_id = (int)$this->config->get('config_language_id');
		
        if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
        	$offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
        	$limit = $data['limit'];
        }
	    

	    $query = $this->db->query("SELECT distinct *,p.name as name,pd.name as pd_name, p.image as image,pd.description as description
		FROM " . DB_PREFIX . "product p," . DB_PREFIX . "product_description pd," . DB_PREFIX . "product_to_store pts 
		WHERE p.product_id=pd.product_id
		AND pts.product_id=p.product_id
		AND pts.store_id=".$store_id."
		AND pts.status= 1
		AND p.status= 1
		AND pts.special_price > 0
		AND language_id = ".$language_id. " LIMIT ".$limit." OFFSET ".$offset);

		return $query->rows;
		
	}

	public function getTotalOfferProductsBySpecialPrice( $data = array() ) {
		

		$store_id = $this->session->data['config_store_id'];
		$language_id = (int)$this->config->get('config_language_id');
		
	    

	    $query = $this->db->query("SELECT distinct *,p.name as name,pd.name as pd_name, p.image as image,pd.description as description
		FROM " . DB_PREFIX . "product p," . DB_PREFIX . "product_description pd," . DB_PREFIX . "product_to_store pts 
		WHERE p.product_id=pd.product_id
		AND pts.product_id=p.product_id
		AND pts.store_id=".$store_id."
		AND pts.status= 1
		AND p.status= 1
		AND pts.special_price > 0
		AND language_id = ".$language_id);

		return count($query->rows);
	}

	public function getOfferProducts( $data = array() ) {
		$store_id = $this->session->data['config_store_id'];
		$language_id = (int)$this->config->get('config_language_id');
		/*$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');

		if ( !empty( $data['filter_store_id'] ) ) {
	        $this->db->join('offer_products', 'offer_products.product_id = product_to_store.product_id', 'left');
	        $this->db->join('offers', 'offers.offer_id = offer_products.offer_id', 'left');
		}*/

		

		//
	    /*$offer_product_data = array();

		$query = $this->db->query("SELECT of.name as offer_name,of.store_id as store_ids,p.* FROM " . DB_PREFIX . "offer_products op inner join " . DB_PREFIX . "product p inner join " . DB_PREFIX . "offers of on  p.product_id = op.product_id and of.offer_id = op.offer_id WHERE store_id = '" . (int)$store_id . "'");

		//echo "<pre>";print_r($query);die;
		return $query->rows;*/
        if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
        	$offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
        	$limit = $data['limit'];
        }
	    

	    $query = $this->db->query("SELECT distinct *,p.name as name,pd.name as pd_name, p.image as image,pd.description as description
		FROM " . DB_PREFIX . "product p," . DB_PREFIX . "product_description pd," . DB_PREFIX . "product_to_store pts," . DB_PREFIX . "offers of," . DB_PREFIX . "offer_products op
		WHERE p.product_id=pd.product_id
		AND pts.product_id=p.product_id
		AND op.product_id=p.product_id
		AND of.offer_id=op.offer_id
		AND of.store_id=".$store_id."
		AND pts.store_id=".$store_id."
		AND of.status= 1
		AND pts.status= 1
		AND p.status= 1
		AND of.date_start <= '". date('Y-m-d')."' 
		AND of.date_end >= '". date('Y-m-d')."'
		AND language_id = ".$language_id. " LIMIT ".$limit." OFFSET ".$offset);

		return $query->rows;
		/*$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('offers.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product.statuss',1);
		$ret = $this->db->get('product_to_store', $limit, $offset)->rows;*/
		// echo $this->db->last_query();die;
		//return $ret;
	}

	public function getTotalOfferProducts( $data = array() ) {
		

		$store_id = $this->session->data['config_store_id'];
		$language_id = (int)$this->config->get('config_language_id');
		
        
	    $query = $this->db->query("SELECT distinct *,p.name as name,p.image as image,pd.description as description
		FROM " . DB_PREFIX . "product p," . DB_PREFIX . "product_description pd," . DB_PREFIX . "product_to_store pts," . DB_PREFIX . "offers of," . DB_PREFIX . "offer_products op
		WHERE p.product_id=pd.product_id
		AND pts.product_id=p.product_id
		AND op.product_id=p.product_id
		AND of.offer_id=op.offer_id
		AND of.store_id=".$store_id."
		AND pts.store_id=".$store_id."
		AND of.status= 1
		AND pts.status= 1
		AND p.status= 1
		AND of.date_start <= '". date('Y-m-d')."' 
		AND of.date_end >= '". date('Y-m-d')."'
		AND pts.store_id=".$store_id."
		AND language_id = ".$language_id);

		return count($query->rows);
	}

	public function getTotalProducts( $data = array() ) {
		$store_id = $this->session->data['config_store_id'];
		$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->join('category_path', 'category_path.category_id = product_to_category.category_id', 'left');
	        }
		}	
		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->where('category_path.path_id', (int)$data['filter_category_id']);
	        } else {
	        	  $this->db->where('product_to_category.category_id', (int)$data['filter_category_id']);

	        }
	    }

		if ( !empty( $data['filter_name'] ) || !empty( $data['filter_tag'] ) ) {
        
	        if ( !empty( $data['filter_name'] ) ) {
                /*$this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ), 'none');

                $this->db->where('product_description.name', $this->db->escape( $data['filter_name'] ) , 'both');*/
                $this->db->like('product_description.name', $this->db->escape_str( $data['filter_name'] ) , 'both');
                //$this->db->where('(MATCH('. DB_PREFIX .'product_description.name) AGAINST("'.$data['filter_name'].'"))', NULL, FALSE);

	        }
        	
		}	
		
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_to_store.quantity >=', 1);
	    //$this->db->where('product_description.language_id', 1);
	    $this->db->where('product.status',1);
	    
		$ret = $this->db->get('product_to_store')->rows;
		return count($ret);
		
	}

	public function getTotalCollectionProducts( $data = array() ) {
		$store_id = $this->session->data['config_store_id'];
		$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		$product_collection_product_ids = [];

		if ( !empty( $data['filter_product_collection_id'] ) ) {
	        
	        $product_collection_product_ids = $this->getProductCollectionProducts($data['filter_product_collection_id']);
	        
		}	
		
		//echo "<pre>";print_r(implode(",",$product_collection_product_ids));die;//762,2128
		if ( !empty( $data['filter_name'] ) || !empty( $data['filter_tag'] ) ) {
        

	        if ( !empty( $data['filter_name'] ) ) {
                /*$this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ), 'none');*/

                $this->db->like('product_description.name', $this->db->escape_str( $data['filter_name'] ) , 'both');
	        }
	      
		}	
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_to_store.quantity >=', 1);
	    
	    $this->db->where('product.status',1);
	    $this->db->where_in('product.product_id',implode(",",$product_collection_product_ids));
		$ret = $this->db->get('product_to_store')->rows;
		return count($ret);
		
	}

	public function getTotalCollectionProductsApi( $data = array() ) {
		$store_id = $data['store_id'];
		$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		$product_collection_product_ids = [];

		if ( !empty( $data['filter_product_collection_id'] ) ) {
	        
	        $product_collection_product_ids = $this->getProductCollectionProducts($data['filter_product_collection_id']);
	        
		}	
		
		//echo "<pre>";print_r(implode(",",$product_collection_product_ids));die;//762,2128
		if ( !empty( $data['filter_name'] ) || !empty( $data['filter_tag'] ) ) {

	        if ( !empty( $data['filter_name'] ) ) {
                $this->db->or_like('product_description.name', ' '.$this->db->escape( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.name', $this->db->escape( $data['filter_name'] ), 'none');
	        }
	      
		}	
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product.status',1);
	    $this->db->where_in('product.product_id',implode(",",$product_collection_product_ids));
		$ret = $this->db->get('product_to_store')->rows;
		return count($ret);
		
	}


	public function getTotalProductsByApi( $data = array() ) {
		
		$store_id = $data['store_id'];
		$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->join('category_path', 'category_path.category_id = product_to_category.category_id', 'left');
	        }
		}	
		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->where('category_path.path_id', (int)$data['filter_category_id']);
	        } else {
	        	  $this->db->where('product_to_category.category_id', (int)$data['filter_category_id']);

	        }
	    }
		if ( !empty( $data['filter_name'] ) || !empty( $data['filter_tag'] ) ) {
	        if ( !empty( $data['filter_name'] ) ) {
                /*$this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ), 'none');*/

                $this->db->like('product_description.name', $this->db->escape_str( $data['filter_name'] ) , 'both');
	        }
		}	
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_to_store.quantity >=', 1);
	    $this->db->where('product.status',1);
		$ret = $this->db->get('product_to_store')->rows;
		return count($ret);
		
	}





	public function getProductsByModel( $modal ) {
		
		
	}

	public function getProductSpecials( $data = array() ) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->session->data['config_store_id'] . "' AND ps.customer_group_id = '" . (int)$this->config->get( 'config_customer_group_id' ) . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			if ( $data['sort'] == 'pd.name' || $data['sort'] == 'p.model' ) {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if ( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) ) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if ( isset( $data['start'] ) || isset( $data['limit'] ) ) {
			if ( $data['start'] < 0 ) {
				$data['start'] = 0;
			}

			if ( $data['limit'] < 1 ) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query( $sql );

		foreach ( $query->rows as $result ) {
			$product_data[$result['product_id']] = $this->getProduct( $result['product_id'] );
		}

		return $product_data;
	}

	public function getLatestProducts( $limit ) {
		$product_data = $this->cache->get( 'product.latest.' . (int)$this->config->get( 'config_language_id' ) . '.' . (int)$this->session->data['config_store_id'] . '.' . $this->config->get( 'config_customer_group_id' ) . '.' . (int)$limit );

		if ( !$product_data ) {
			$query = $this->db->query( "SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->session->data['config_store_id'] . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit );

			foreach ( $query->rows as $result ) {
				$product_data[$result['product_id']] = $this->getProduct( $result['product_id'] );
			}

			$this->cache->set( 'product.latest.' . (int)$this->config->get( 'config_language_id' ) . '.' . (int)$this->session->data['config_store_id'] . '.' . $this->config->get( 'config_customer_group_id' ) . '.' . (int)$limit, $product_data );
		}

		return $product_data;
	}

	public function getPopularProducts( $limit ) {
		$product_data = array();

		$query = $this->db->query( "SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->session->data['config_store_id'] . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit );

		foreach ( $query->rows as $result ) {
			$product_data[$result['product_id']] = $this->getProduct( $result['product_id'] );
		}

		return $product_data;
	}

	public function getBestSellerProducts( $limit ) {
		$product_data = $this->cache->get( 'product.bestseller.' . (int)$this->config->get( 'config_language_id' ) . '.' . (int)$this->session->data['config_store_id'] . '.' . $this->config->get( 'config_customer_group_id' ) . '.' . (int)$limit );

		if ( !$product_data ) {
			$product_data = array();

			$query = $this->db->query( "SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->session->data['config_store_id'] . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit );

			foreach ( $query->rows as $result ) {
				$product_data[$result['product_id']] = $this->getProduct( $result['product_id'] );
			}

			$this->cache->set( 'product.bestseller.' . (int)$this->config->get( 'config_language_id' ) . '.' . (int)$this->session->data['config_store_id'] . '.' . $this->config->get( 'config_customer_group_id' ) . '.' . (int)$limit, $product_data );
		}

		return $product_data;
	}

	public function getProductAttributes( $product_id ) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query( "SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get( 'config_language_id' ) . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name" );

		foreach ( $product_attribute_group_query->rows as $product_attribute_group ) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query( "SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get( 'config_language_id' ) . "' AND pa.language_id = '" . (int)$this->config->get( 'config_language_id' ) . "' ORDER BY a.sort_order, ad.name" );

			foreach ( $product_attribute_query->rows as $product_attribute ) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions( $product_id ) {
		$product_option_data = array();

		$product_option_query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get( 'config_language_id' ) . "' ORDER BY o.sort_order" );

		foreach ( $product_option_query->rows as $product_option ) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get( 'config_language_id' ) . "' ORDER BY ov.sort_order" );

			foreach ( $product_option_value_query->rows as $product_option_value ) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductDiscounts( $product_id ) {
		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get( 'config_customer_group_id' ) . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC" );

		return $query->rows;
	}

	public function getProductImages( $product_id ) {
		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC" );

		return $query->rows;
	}

	public function getProductRelated( $product_id ) {
		$product_data = array();

		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->session->data['config_store_id'] . "'" );

		foreach ( $query->rows as $result ) {
			$product_data[$result['related_id']] = $this->getProduct( $result['related_id'] );
		}

		return $product_data;
	}

	public function getProductLayoutId( $product_id ) {
		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->session->data['config_store_id'] . "'" );

		if ( $query->num_rows ) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}


	public function getCategories( $product_id ) {
		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'" );

		return $query->rows;
	}

	

	public function getProfiles( $product_id ) {
		return $this->db->query( "SELECT `pd`.* FROM `" . DB_PREFIX . "product_recurring` `pp` JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get( 'config_language_id' ) . " AND `pd`.`recurring_id` = `pp`.`recurring_id` JOIN `" . DB_PREFIX . "recurring` `p` ON `p`.`recurring_id` = `pd`.`recurring_id` WHERE `product_id` = " . (int)$product_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$this->config->get( 'config_customer_group_id' ) . " ORDER BY `sort_order` ASC" )->rows;
	}

	public function getProfile( $product_id, $recurring_id ) {
		return $this->db->query( "SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int)$product_id . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get( 'config_customer_group_id' ) )->row;
	}

	public function getTotalProductSpecials() {
		$query = $this->db->query( "SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->session->data['config_store_id'] . "' AND ps.customer_group_id = '" . (int)$this->config->get( 'config_customer_group_id' ) . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))" );

		if ( isset( $query->row['total'] ) ) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function getStoreByZip($zipcode, $store_id) {

		$this->db->where('zipcode', $zipcode);
		$this->db->where('store_id', $store_id);
		return $this->db->get('store_zipcodes')->row;
        
    }

    public function getOffersByStore($store_id) {

		$offer_product_data = array();

		$query = $this->db->query("SELECT of.name as offer_name,of.store_id as store_ids,p.* FROM " . DB_PREFIX . "offer_products op inner join " . DB_PREFIX . "product p inner join " . DB_PREFIX . "offers of on  p.product_id = op.product_id and of.offer_id = op.offer_id WHERE store_id = '" . (int)$store_id . "'");

		//echo "<pre>";print_r($query);die;
		return $query->rows;
    }

    public function getProductCollectionDescriptions( $product_collection_id ) {
        $product_description_data = array();

        $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_collection_description WHERE product_collection_id = '" . (int) $product_collection_id . "' and language_id= '".(int)$this->config->get('config_language_id')."'");
/*
        foreach ( $query->rows as $result ) {
            $product_description_data[$result['language_id']] = array(
                'name' => $result['name'],
                
                'meta_title' => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword' => $result['meta_keyword'],
                
            );
        }*/

        return $query->row;
    }

    public function getProductCollectionApi( $product_collection_id ) {
        $product_description_data = array();

        $query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_collection WHERE product_collection_id = '" . (int) $product_collection_id . "' and status= 1");

        return $query->row;
    }
    
    public function getProductCollection($data = array()) {
        //$sql = "SELECT * FROM " . DB_PREFIX . "product_collection pc" ;

        $sql  =  'SELECT * FROM   `'.DB_PREFIX.'product_collection` pc  JOIN `'.DB_PREFIX.'product_collection_description`  pcd ON pcd.product_collection_id = pc.product_collection_id';


        $isWhere = 0;
        $_sql = array();        
        
        if (true) {
            $isWhere = 1;
            $_sql[]= "pcd.language_id= '".(int)$this->config->get('config_language_id')."'";
            
        }

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            
            $_sql[] = "pcd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        /*if (isset($data['meta_description']) && !is_null($data['meta_description'])) {
            $isWhere = 1;

            $_sql[] = "pc.meta_description = '" . $this->db->escape($data['meta_description']) . "'" ;
        }*/

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "pc.status = '" . $this->db->escape($data['filter_status']) . "'" ;
        }
        
        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }
        
        $sort_data = array(
            'name',
            'meta_description',
            'meta_keywords',
            'content',
            'status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
      
        $query = $this->db->query($sql);
          
        return $query->rows;
    }

    public function getProductCollectionProducts($product_collection_id) {
        $product_collection_product_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_collection_products WHERE product_collection_id = '" . (int)$product_collection_id . "'");

        foreach ($query->rows as $result) {
            $product_collection_product_data[] = $result['product_id'];
        }

        return $product_collection_product_data;
    }

    public function getProductData($filter_name) {

    	$language_id = (int)$this->config->get('config_language_id');
    	
        $sql  = 'select pd.product_id, pd.name  from `'.DB_PREFIX.'product_description` as pd inner join ' . DB_PREFIX . 'product p on pd.product_id = p.product_id';
        $sql .= ' WHERE pd.name LIKE "'.$filter_name.'%" AND language_id = '.$language_id.' and p.status = 1 LIMIT 5';
        
        return $this->db->query($sql)->rows;
    }

    public function getProductsByApi( $data = array() ) {

		$store_id = $data['store_id'];

		$this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->join('category_path', 'category_path.category_id = product_to_category.category_id', 'left');
	        }
		}	
		if ( !empty( $data['filter_category_id'] ) ) {
	        if ( !empty( $data['filter_sub_category'] ) ) {
	                $this->db->where('category_path.path_id', (int)$data['filter_category_id']);
	        } else {
	        	  $this->db->where('product_to_category.category_id', (int)$data['filter_category_id']);

	        }
	    }

		if ( !empty( $data['filter_name'] ) || !empty( $data['filter_tag'] ) ) {
	        if ( !empty( $data['filter_name'] ) ) {
                /*$this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ) .' ', 'both');
                $this->db->or_like('product_description.name', ' '.$this->db->escape_str( $data['filter_name'] ), 'before');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ) .' ', 'after');
                $this->db->or_like('product_description.name', $this->db->escape_str( $data['filter_name'] ), 'none');*/


                $this->db->like('product_description.name', $this->db->escape_str( $data['filter_name'] ) , 'both');

	        }
		}

        if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
        	$offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
        	$limit = $data['limit'];
        }
	    $sort_data = array(
	        'product_description.name',
	        'product.model',
	        'product_to_store.quantity',
	        'product_to_store.price',
	        'product.sort_order',
	        'product.date_added'
		);
		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
		       	$this->db->order_by($data['sort'], 'asc');
	        }else {
				$this->db->order_by($data['sort'], 'asc');
	        }
		} else {
		    $this->db->order_by('product.sort_order', 'asc');      
		}	
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_to_store.quantity >=', 1);
	    $this->db->where('product.status',1);
		$ret = $this->db->get('product_to_store', $limit, $offset)->rows;
		// echo $this->db->last_query();die;
		return $ret;
	}

	public function getProductDataByStore( $filter_name ) {

		$store_id = (int)$this->session->data['config_store_id'];
		$language_id = (int)$this->config->get('config_language_id');

		$limit = 5;
		$offset = 0;

		$this->db->select('product_description.*,product.unit', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		
		if ( !empty( $filter_name) ) {
        
	        $this->db->like('product_description.name', $this->db->escape( $filter_name ) , 'both');
	        
		}


        /*if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
        	$offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
        	$limit = $data['limit'];
        }
	    $sort_data = array(
	        'product_description.name',
	        'product.model',
	        'product_to_store.quantity',
	        'product_to_store.price',
	        'product.sort_order',
	        'product.date_added'
		);
		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
		       	$this->db->order_by($data['sort'], 'asc');
	        }else {
				$this->db->order_by($data['sort'], 'asc');
	        }
		} else {
		    $this->db->order_by('product.sort_order', 'asc');      
		}	*/
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_to_store.quantity >=', 1);
	    $this->db->where('product_description.language_id', $language_id);
	    $this->db->where('product.status',1);
		$ret = $this->db->get('product_to_store', $limit)->rows;
		//$ret = $this->db->get('product_to_store')->rows;
		// echo $this->db->last_query();die;
		return $ret;
	}

    public function getProductDataByStoreId( $store_id ) {

		$store_id = $store_id;
		$language_id = (int)$this->config->get('config_language_id');

		$limit = 10;
		$offset = 0;

		$this->db->select('product_description.*,product.unit', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

		
		if ( !empty( $filter_name) ) {
        
	        $this->db->like('product_description.name', $this->db->escape( $filter_name ) , 'both');
	        
		}


        /*if ( $data['start'] < 0 ) {
            $data['start'] = 0;
            $offset = $data['start'];
        }else{
        	$offset = $data['start'];
        }
        if ( $data['limit'] < 1 ) {
            $data['limit'] = 20;
            $limit = $data['limit'];

        }else{
        	$limit = $data['limit'];
        }
	    $sort_data = array(
	        'product_description.name',
	        'product.model',
	        'product_to_store.quantity',
	        'product_to_store.price',
	        'product.sort_order',
	        'product.date_added'
		);
		if ( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) ) {
			if ( $data['sort'] == 'product_description.name' || $data['sort'] == 'product.model' ) {
		       	$this->db->order_by($data['sort'], 'asc');
	        }else {
				$this->db->order_by($data['sort'], 'asc');
	        }
		} else {
		    $this->db->order_by('product.sort_order', 'asc');      
		}	*/
		$this->db->group_by('product_to_store.product_store_id');
	    $this->db->where('product_to_store.store_id', $store_id);
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_to_store.quantity >=', 1);
	    $this->db->where('product_description.language_id', $language_id);
	    $this->db->where('product.status',1);
		$ret = $this->db->get('product_to_store', $limit)->rows;
		//$ret = $this->db->get('product_to_store')->rows;
		// echo $this->db->last_query();die;
		return $ret;
	}

	/** Get Latest products by StoreId **/
	public function getLatestProductsByStoreId($store_id,$limit) {
	    $categories = $this->model_assets_category->getCategoriesByStoreId($store_id);
	    $this->db->select('product_to_store.*,product.*,product_description.*', FALSE);
		$this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
		$this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');
		$this->db->group_by('product_to_store.product_store_id');
		$this->db->where('product_to_store.store_id', $store_id);
		if(count($categories)>0){
			$cat_array = array_column($categories,'category_id');
			$this->db->where_in('product_to_category.category_id',$cat_array);
		}
	    $this->db->where('product_to_store.status', 1);
	    $this->db->where('product_to_store.quantity >=', 1);
	    $this->db->where('product.status',1);
		$ret = $this->db->get('product_to_store',$limit)->rows;
		return $ret;
	} 
}
