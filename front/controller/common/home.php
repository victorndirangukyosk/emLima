<?php

class ControllerCommonHome extends Controller {

	public function show_home() {
		unset($this->session->data['config_store_id']);
		
		if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->response->redirect($server);
	}
	
	public function saveVisitorId() {
		$this->session->data['visitor_id'] = $this->request->get['visitor_id'];
		return $this->session->data['visitor_id'];
	}

	public function start(){
		
		$this->session->data['config_store_id'] = $this->request->post['store_id'];
		
		$json['status'] = 1;

		$json['location'] =  $this->url->link('product/store','store_id='.$this->session->data['config_store_id'].'');
		
		echo json_encode($json);
	}
	
	public function getFacebookRedirectUrl(){
		
		$url = null;
		if(isset($this->request->get['category'])){
            $url = $this->request->get['category'];
        }

        if(isset($this->request->get['redirect_url'])) {

            if (($pos = strpos($this->request->get['redirect_url'], "path=")) !== FALSE) { 
                $redirectPath = substr($this->request->get['redirect_url'], $pos+5); 
                if($url) {
                    $redirectPath .='&category='.$url;
                }
                $this->session->data['redirect'] = $this->url->link($redirectPath);
            } else {
                $this->session->data['redirect'] = $this->request->get['redirect_url'];
            }
        }

       require DIR_SYSTEM . 'vendor/Facebook/autoload.php';

		$fb = new Facebook\Facebook( [
			'app_id' => !empty($this->config->get('config_fb_app_id')) ? $this->config->get('config_fb_app_id') : 'randomstringforappid',
			'app_secret' => !empty($this->config->get('config_fb_secret'))? $this->config->get('config_fb_secret') : 'randomstringforappsecret',
			'default_graph_version' => 'v2.5',
			//'default_access_token' => $this->request->get['code']//'5ce6c3df96acc19c6215f2ac62d3480e', // optional
			] );

		$helper = $fb->getRedirectLoginHelper();

		if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

		$json['facebook'] = $helper->getLoginUrl( $server . 'index.php?path=account/facebook', array( 'email' ) );

		echo json_encode($json);
	}

	public function saveLocation() {

		if(isset($this->request->post['name']) && isset($this->request->post['lat']) && isset($this->request->post['lng'])) {

			$_COOKIE['location'] = $this->request->post['lat'].','.$this->request->post['lng'];
			/* Commented For Meta Organic */
            //setcookie('location', $this->request->post['lat'].','.$this->request->post['lng'], time() + (86400 * 30 * 30 * 30 * 3), "/");// 3 month expiry
            /* Commented For Meta Organic */
			$_COOKIE['location_name'] = $this->request->post['name'];
        	//setcookie('location_name', $this->request->post['name'], time() + (86400 * 30 * 30 * 30 * 3), "/");
		}

		$html['status'] = true;
		
		echo json_encode($html);
	}
	

	public function find_store() {

		$html = '';
		$this->load->language('information/locations');
		if(isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		}else{
			$filter_name = '';
		}
		
		$html = array();
		$html['text_error'] = $this->language->get('text_error');
		$html['text_not_found'] = $this->language->get('text_not_found');
		$this->load->model('setting/store');

		if($this->config->get('config_store_location') == 'autosuggestion') { 
			$stores = $this->model_setting_store->getStoreByLatLang($this->request->get['zipcode'], $filter_name);            
        } else {
            $stores = $this->model_setting_store->getStoreByZip($this->request->get['zipcode'], $filter_name);
        }
		
		if ($stores) {  
			$html['store'] = true;
		} else {    
			$html['store'] = false;    
		}   
		echo json_encode($html);
	}

	public function index() {
		
		$log = new Log('error.log');

		$data['kondutoStatus'] = $this->config->get('config_konduto_status');

		$data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

		//echo $this->config->get('config_store_location');die;

		if(isset($this->session->data['language'])) {
			$data['config_language'] = $this->session->data['language'];
		} else {
			$data['config_language'] = 'pt-BR';
		}

		if(isset($this->request->get['refer'])) {


			$x = strpos($this->request->get['refer'],"%21%40");

	        $p = substr($this->request->get['refer'], $x+6);

			$cookie_name = "referral";
			$cookie_value = $p;

			//echo "<pre>";print_r($p);die;
			setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");// 1 day expiry
		}
		
		$data['seo_url_test'] = $this->url->link('store/collection', 'collection_id=3');
		
		$log->write($data['konduto_public_key']);
		unset($this->session->data['visitor_id']);

		//$log->write($this->session->data['language']);

		$data['justlogged_in'] = false;

		if(isset($this->session->data['just_loggedin']) && $this->session->data['just_loggedin']) {
			$data['justlogged_in'] = true;
			$this->session->data['just_loggedin'] = false;
		}
		
		if(defined('const_latitude') && defined('const_longitude') && !empty(const_latitude) && !empty(const_longitude) ) {

			$_COOKIE['location'] = const_latitude.','.const_longitude;
			
            setcookie('location', const_latitude.','.const_longitude, time() + (86400 * 30 * 30 * 30 * 3), "/");// 3 month expiry

			$_COOKIE['location_name'] = const_location_name;
        	setcookie('location_name', const_location_name, time() + (86400 * 30 * 30 * 30 * 3), "/");
        	
			$this->response->redirect($this->url->link('information/locations/stores', 'location=' . const_latitude.",".const_longitude));	
		}
		
		
		if(count($_COOKIE) > 0 && isset($_COOKIE['zipcode'])) {

			$this->response->redirect($this->url->link('information/locations/stores', 'zipcode=' . $_COOKIE['zipcode']));	
			
		}
		if(count($_COOKIE) > 0 && isset($_COOKIE['location'])) {
			$this->response->redirect($this->url->link('information/locations/stores', 'location=' . $_COOKIE['location']));	
		}

		
		if(isset($this->session->data['config_store_id'])){
			//$this->response->redirect($this->url->link('product/store'));

			$this->response->redirect($this->url->link('product/store','store_id='.$this->session->data['config_store_id'].''));
		}

		

		$this->load->model('tool/image');
		$this->load->language('common/home');
		
		$data['blocks'] = [];

		$blocks = $this->model_tool_image->getBlocks();

		//echo "<pre>";print_r($blocks);die;
		foreach ($blocks as $block) {
			
        	if (is_file(DIR_IMAGE . $block['image'])) {
	            $image = $this->model_tool_image->resize($block['image'], 290, 163);
	        } else {
	            $image = $this->model_tool_image->resize('no_image.png', 290, 163);
	   		}

	        $temp['image'] = $image;
	        $temp['description'] = trim($block['description']);
	        $temp['title'] = $block['title'];
	        $temp['sort_order'] = $block['sort_order'];
	       
	        array_push($data['blocks'], $temp);
		}

		
		//echo "<pre>";print_r($data['blocks']);die;
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		$data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['metas'] = $this->document->getMetas();

        $data['zipcode_mask'] = $this->config->get('config_zipcode_mask');

        $data['zipcode_mask_number'] = '';
        
        if(isset($data['zipcode_mask'])) {
        	$data['zipcode_mask_number'] = str_replace('#', '9', $this->config->get('config_zipcode_mask'));	
        }


        $data['telephone_mask'] = $this->config->get('config_telephone_mask');

        if(isset($data['telephone_mask'])) {
        	$data['telephone_mask_number'] = str_replace('#', '9', $this->config->get('config_telephone_mask'));	
        }

        $data['taxnumber_mask'] = $this->config->get('config_taxnumber_mask');

        if(isset($data['taxnumber_mask'])) {
        	$data['taxnumber_mask_number'] = str_replace('#', '*', $this->config->get('config_taxnumber_mask'));	
        }

        //echo "<pre>";print_r($data);die;
        
        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

		if (!$this->config->get('config_seo_url') and isset($this->request->get['path'])) {
			$this->document->addLink($server, 'canonical');
		}

		if ($this->config->get('config_google_analytics_status')) {
            $data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
        } else {
            $data['google_analytics'] = '';
        }
        

		$data['text_get_groceries'] = $this->language->get('text_get_groceries');
		$data['base'] = $server;
		$data['text_deliver_in'] = $this->language->get('text_deliver_in');
		$data['text_order_fresh'] = $this->language->get('text_order_fresh');
		$data['text_shop_at'] = $this->language->get('text_shop_at');

		$data['text_from_device'] = $this->language->get('text_from_device');
		$data['text_schedule_delivery'] = $this->language->get('text_schedule_delivery');
		$data['text_get_grocery_at'] = $this->language->get('text_get_grocery_at');
		$data['text_an_hour'] = $this->language->get('text_an_hour');

		$data['text_or_want_them'] = $this->language->get('text_or_want_them');
		$data['text_get_delivered'] = $this->language->get('text_get_delivered');
		$data['text_fresh_handpicked'] = $this->language->get('text_fresh_handpicked');
		$data['text_local_stores'] = $this->language->get('text_local_stores');

		$data['text_my_wishlist'] = $this->language->get('text_my_wishlist');
		
		$data['text_welcome_user'] = $this->language->get('text_welcome_user');
		$data['text_open_store'] = $this->language->get('text_open_store');
		$data['text_store_working'] = $this->language->get('text_store_working');
		$data['text_enter_zipcode_title'] = $this->language->get('text_enter_zipcode_title');
		$data['text_delivery_detail'] = $this->language->get('text_delivery_detail');
		$data['text_enter_zipcode'] = $this->language->get('text_enter_zipcode');
		$data['text_find_store'] = $this->language->get('text_find_store');
		$data['text_have_account'] = $this->language->get('text_have_account');
		$data['text_log_in'] = $this->language->get('text_log_in');
		$data['text_get_delivered'] = $this->language->get('text_get_delivered');
		$data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }


		$data['text_move_next'] = $this->language->get('text_move_next');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_back'] = $this->language->get('text_back');
		$data['text_enter_code_in_area'] = $this->language->get('text_enter_code_in_area');
		$data['text_move_Next'] = $this->language->get('text_move_Next');
		$data['text_enter_you_agree'] = $this->language->get('text_enter_you_agree');
		$data['text_terms_of_service'] = $this->language->get('text_terms_of_service');
		$data['text_privacy_policy'] = $this->language->get('text_privacy_policy');
		$data['text_get_delivered_download_apps'] = $this->language->get('text_get_delivered_download_apps');
		
		
		$data['support'] = $this->language->get('support');
		$data['faq'] = $this->language->get('faq');
		$data['call'] = $this->language->get('call');
		$data['text'] = $this->language->get('text');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_rewards'] = $this->language->get('text_rewards');
		$data['text_orders'] = $this->language->get('text_orders');
		$data['text_refer'] = $this->language->get('text_refer');
		$data['text_sign_out'] = $this->language->get('text_sign_out');
		$data['text_sign_in'] = $this->language->get('text_sign_in');
		$data['text_heading'] = $this->language->get('text_heading');
		$data['text_heading2'] = $this->language->get('text_heading2');
		$data['text_heading3'] = $this->language->get('text_heading3');
		$data['text_heading4'] = $this->language->get('text_heading4');
		$data['text_heading5'] = $this->language->get('text_heading5');
		$data['text_heading6'] = $this->language->get('text_heading6');

		$data['text_my_cash'] = $this->language->get('text_my_cash');
        $data['label_my_address'] = $this->language->get('label_my_address');
        $data['text_my_profile'] = $this->language->get('text_my_profile');
        $data['contactus'] = $this->language->get('contactus');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_register'] = $this->language->get('text_register');
        
		$data['step1'] = $this->language->get('step1');
		$data['step2'] = $this->language->get('step2');
		$data['step3'] = $this->language->get('step3');
		$data['step4'] = $this->language->get('step4');

		$data['label_start'] = $this->language->get('label_start');
		$data['label_name'] = $this->language->get('label_name');
		$data['label_email/phone'] = $this->language->get('label_email/phone');
		$data['label_msg'] = $this->language->get('label_msg');

		$data['button_send'] = $this->language->get('button_send');
	
		$data['is_login'] = $this->customer->isLogged();
		$data['f_name'] =  $this->customer->getFirstName();
		$data['name'] =  $this->customer->getFirstName();
		$data['l_name'] =  $this->customer->getLastName();
		$data['full_name'] = $data['f_name'];//.' '.$data['l_name'];
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['credit'] = $this->url->link('account/credit', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['refer'] = $this->url->link('account/refer','','SSL');
		$data['reward'] = $this->url->link('account/reward','','SSL');
		$data['footer'] = $this->load->controller('common/footer');
		$data['action'] = $this->url->link('common/home/find_store');
		$data['address'] = $this->url->link('account/address', '', 'SSL');
		$data['help'] = $this->url->link('information/help');
		
		$data['language'] = $this->load->controller('common/language/dropDown');
		
		$log->write("home tpl 3");
		$data['login_modal'] = $this->load->controller('common/login_modal');

		$log->write("home tpl 3.1");
		$data['signup_modal'] = $this->load->controller('common/signup_modal');	

		$log->write("home tpl 3.2");
		$data['forget_modal'] = $this->load->controller('common/forget_modal');		

		$log->write("home tpl 4");

		$data['heading_title'] = $this->config->get('config_meta_title', '');

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'),30,30);
        } else {
            $data['icon'] = '';
        }


		if (is_file(DIR_IMAGE . $this->config->get('config_fav_icon'))) {
			$data['fav_icon'] = $server . 'image/' . $this->config->get('config_fav_icon');
		} else {
			$data['fav_icon'] = '';
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'),130,72);
			//$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}
		

        $data['playStorelogo'] = $this->model_tool_image->resize('play-store-logo.png',200,60);
        
        $data['appStorelogo'] = $this->model_tool_image->resize('app-store-logo.png',200,60);

		if(isset($this->session->data['warning'])){
			$data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		}else{
		   $data['warning'] = ''; 
		}
		
		$data['banners'] = $data['testimonials'] = array();
		
		$rows = $this->model_tool_image->getTestimonial();
		
		foreach($rows as $row){
			$row['thumb'] = $this->model_tool_image->resize($row['image'],80,80);
			$data['testimonials'][] = $row;
		}
		
		//banners 
		$rows = $this->model_tool_image->getAllOffers();
		
		foreach($rows as $row){
			if (false === strpos($row['link'], '://')) {
				$row['link'] = 'http://' . $row['link'];
			}
			$row['image'] = $this->model_tool_image->resize($row['image'],300,300);            
			$data['banners'][] = $row;    
		}
		
		$data['play_store'] = $this->config->get('config_android_app_link');
        $data['app_store'] = $this->config->get('config_apple_app_link');

        //echo "<pre>";print_r($this->config->get('config_android_app_link'));die;
		$this->load->model('setting/setting');
        $te = $this->model_setting_setting->getSetting('config');

        if($te) {
            $data['play_store'] = $te['config_android_app_link'];
            $data['app_store'] = $te['config_apple_app_link'];
        }

			
		$data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$this->load->model('assets/category');
		$data['categories'] = array();
        $this->load->controller('product/store' );
		//$categories = $this->model_assets_category->getCategoriesNoRelationStore();
		$categories = $this->model_assets_category->getCategoryByStoreId(ACTIVE_STORE_ID,0);
		
	    foreach ($categories as $category) {
			   // Level 2
			   $children_data = array();

			   $children = $this->model_assets_category->getCategories($category['category_id']);

			   //echo "<pre>";print_r($children);die;
			   foreach ($children as $child) {
					   $children_data[] = array(
							   'name'  => $child['name'],
							   'id' => $child['category_id'],
							   'href'  => $this->url->link('product/category', 'category=' . $category['category_id'] . '_' . $child['category_id'])
					   );                      
			   }

			   $filter_data_product = array(
                'filter_category_id' => $category['category_id'],
                'filter_sub_category' => true,
                'start' => 0,
                'limit' => 4,
                'store_id'=>ACTIVE_STORE_ID
              );

			   // Level 1
			   $data['categories'][] = array(
					   'name'     => $category['name'],
					   'id' => $category['category_id'],
					   'thumb'     => $this->model_tool_image->resize($category['image'], 300, 300),
					   'children' => $children_data,
					   'column'   => $category['column'] ? $category['column'] : 1,
					   'href'     => $this->url->link('product/category', 'category=' . $category['category_id']),
					   'products' => $this->getProducts($filter_data_product)
				);

			   
	   }
	   //echo "<pre>";print_r($data['categories']);die;
	   $data['page'] = $_REQUEST['page'];
	   //$this->load->language('module/store');
	   $this->load->model('setting/store');
	   $filter = array();
	   $filter['filter_status'] = 1;
	   if($_REQUEST['location']){
		   $userSearch = explode(",", $_REQUEST['location']);
		   $filter['filter_location'] = $_REQUEST['location'];
	   }
	   if($_REQUEST['category']){
		$filter['filter_category'] = $_REQUEST['category'];
	   }
	   //echo'<pre>';print_r($filter);exit;
	   //$categoriesIds = $this->model_setting_store->getStoreCategoriesbyStoreId(2,$_REQUEST['category']);
	   //echo'<pre>';print_r($categoriesIds);exit;
	   $stores = $this->model_setting_store->getStoresAll($filter);

	    /* Code for storeType Dynamic */
	    $this->load->model('assets/category');
		$store_types = $this->model_assets_category->getStoreTypes();
		$tempStoreTypeArray=array();
		foreach($store_types as $value){
			$tempStoreTypeArray[$value['store_type_id']] = $value['name'];
		}
	    //echo'<pre>';print_r($store_types);exit;
	    foreach($stores as $store){
		   $tempStore = $store;
		   $tempStore['href'] = $this->model_setting_store->getSeoUrl('store_id=' . $store['store_id']);
		   $tempStore['thumb'] = $this->model_tool_image->resize($store['logo'],300, 300);
		   $tempStore['categorycount'] = $this->model_setting_store->getStoreCategoriesbyStoreId($store['store_id'],$_REQUEST['category']);
		   if(!empty($store['store_type_ids'])){
			  $arrayStoretypes = explode(',',$store['store_type_ids']);
			  $tempStoretypename ='';
			  foreach($arrayStoretypes as $key=>$types){
				$tempStoretypename .= ($key ==0) ? $tempStoreTypeArray[$types] : ','.$tempStoreTypeArray[$types];
			  }
		    $tempStore['storeTypes'] = $tempStoretypename;
		   }
		 
		   if($_REQUEST['location'] && $_REQUEST['category']){	
				//echo 'locat';exit;
			    $res = $this->model_setting_store->getDistance( $userSearch[0], $userSearch[1], $store['latitude'], $store['longitude'],$store['serviceable_radius']);
				if($res && ($tempStore['categorycount'] > 0)) {
					$data['stores'][] = $tempStore;  
				}
			}else if($_REQUEST['location']){
				//echo 'loc';exit;
				$res = $this->model_setting_store->getDistance( $userSearch[0], $userSearch[1], $store['latitude'], $store['longitude'],$store['serviceable_radius']);
				if($res) {
					$data['stores'][] = $tempStore;  
				}
			}else if($_REQUEST['category']){
				  //echo 'cat';exit;
				 // $categorycount = $this->model_setting_store->getStoreCategoriesbyStoreId($store['store_id'],$_REQUEST['category']);
				  if($tempStore['categorycount'] > 0){
				   $data['stores'][] = $tempStore;
				  }
			}else{
				//echo 'no';exit;
				$data['stores'][] = $tempStore;
			}
		   /*if($_REQUEST['category']){
		     $categorycount = $this->model_setting_store->getStoreCategoriesbyStoreId($store['store_id'],$_REQUEST['category']);
		       if($categorycount > 0){
				$data['stores'][] = $tempStore;
			   }
		    }else{
		       $data['stores'][] = $tempStore;
			}*/
	    }
	    //echo'<pre>';print_r($data['stores']);exit;
	    /* add Contact modal */
		$data['contactus_modal'] = $this->load->controller('information/contact');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
	}

	public function toHome() {

		//echo "cer";die;
        unset($this->session->data['config_store_id']);
        unset($this->session->data['zipcode']);

        unset($_COOKIE['zipcode']);
        unset($_COOKIE['location']);

        setcookie('zipcode', null, time() - 3600, "/");
        setcookie('location', null, time() - 3600, "/");
        
        setcookie('location_name', null, time() - 3600, "/");


        unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['guest']);
		unset($this->session->data['comment']);
		unset($this->session->data['order_id']);
		unset($this->session->data['coupon']);
		unset($this->session->data['reward']);
		unset($this->session->data['voucher']);
		unset($this->session->data['vouchers']);
		unset($this->session->data['totals']);
				
        $this->cart->clear();

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->response->redirect($server);
    }

    public function toStore() {
        
        if(!$this->config->get('config_multi_store')) {
        	unset($this->session->data['config_store_id']);
        	$this->cart->clear();	
        }

        $this->response->redirect($this->url->link('common/home/index'));
    }

    

    public function cartDetails() {
    	
    	$this->load->language('common/home');

        //check login
        $this->load->model( 'account/address' );
        
        $json['status'] = true;
        $json['amount'] = 0;
        $currentprice = 'initial';
        $log = new Log('error.log');
   		$log->write("1");
        $this->document->addStyle( 'front/ui/theme/'.$this->config->get( 'config_template' ).'/stylesheet/layout_checkout.css' );

        $json['href'] = $this->url->link('checkout/checkout', '', 'SSL');

        
        // Validate cart has products and has stock.
        if ( ( !$this->cart->hasProducts() && empty( $this->session->data['vouchers'] ) ) /*|| ( !$this->cart->hasStock() && !$this->config->get( 'config_stock_checkout' ) )*/ ) {
        	$log->write("2");
        	$json['status'] = false;
        	$currentprice = 0;
            $this->response->addHeader('Content-Type: application/json');
        	$this->response->setOutput(json_encode($json));
        }

        $data['total_quantity'] = 0;
        $data['product_total_amount'] = 0;

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();
        $product_total_count = 0;
        $product_total_amount = 0;
        
        $data['products_details'] = [];

        $this->load->model('tool/image');
        foreach ( $products as $product ) {
            $product_total = 0;

            foreach ( $products as $product_2 ) {
                if ( $product_2['product_store_id'] == $product['product_store_id'] ) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = '';
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }
            $log->write("2.x");
            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }
            
            $product_total_count += $product['quantity'];
            $product_total_amount += $product['total'];

            $data['products_details'][] = array(
                'key' => $product['key'],
                'product_store_id' => $product['product_store_id'],
                'thumb' => $image,
                'name' => $product['name'],
                'unit' => $product['unit'],                    
                'model' => $product['model'],
                'quantity' => $product['quantity'],
                'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price' => $price,
                'total' => $total,
                'href' => $this->url->link('product/product', 'product_store_id=' . $product['product_store_id'])
            );

            /*if ( $product['minimum'] > $product_total ) {
                $json['status'] = false;
                $message = 'product has minimum requirement';

	            $this->response->addHeader('Content-Type: application/json');
	        	$this->response->setOutput(json_encode($json));
            }*/
        }
        $log->write("2.3");
        // echo "<pre>";print_r($data['products_details']);die;
        $data['total_quantity'] = $product_total_count;

        $data['product_total_amount'] = $this->currency->format($product_total_amount);

        $order_stores = $this->cart->getStores();
        $min_order_or_not = array();
        $store_data= array();
        
        /*
        	sort array to make this current store as lst element
        */

        foreach ($order_stores as $key => $value) {
        	if($value == $this->session->data['config_store_id'])
        		unset($order_stores[$key]);
        }

        if(isset($this->session->data['config_store_id'])) {
        	array_push($order_stores, $this->session->data['config_store_id']);	
        }
        
        /*$json['store_note'] = "<center style='background-color:#ee4054'> $2.3 away from minimum order value</center>'";
        $json['store_note'] = "<center style='background-color:#ff811e'> $2.3 away from minimum order value</center>'";*/


        

        foreach ($order_stores as $os) {

        	$json['store_note'][$os] = "<center style='background-color:#43b02a;color:#fff'> Yay! Free Delivery </center>";

            $store_info = $this->model_account_address->getStoreData($os);


           // echo "<pre>";print_r($store_info);die;
            $store_total  = $this->cart->getSubTotal($os);
            $store_data[] = $store_info;
            
            if((0  <= $store_info['min_order_cod']) && ($store_info['min_order_cod'] <= 10000) ) {
            	if($store_info['min_order_cod'] > $store_total) {

	            	$freedeliveryprice = $store_info['min_order_cod'] - $store_total;

	            	$json['store_note'][$os] = "<center style='background-color:#ff811e;color:#fff'> You are only ".$this->currency->format($freedeliveryprice)." away for FREE DELIVERY! </center>";
	            }
	        } else {
	            $json['store_note'][$os] = "";
	        }

            

            if ($this->cart->getTotalProductsByStore($os) && $store_info['min_order_amount'] > $store_total ) {
            	$log->write("3");
            	$currentprice = $store_info['min_order_amount'] - $store_total;
            	$store_name = $store_info['name'];
                $json['status'] = false;
	            $this->response->addHeader('Content-Type: application/json');
	        	$this->response->setOutput(json_encode($json));

	        	$json['store_note'][$os] = "<center style='background-color:#ee4054;color:#fff'>".$this->currency->format($currentprice)." away from minimum order value </center>";

            }

            
        }
        $log->write("4");
        
        if($json['status'])
        	$json['status'] = true;

        if($currentprice == 0) {
        	$json['amount'] = $this->language->get('text_no_product');
        } else {
        	$json['amount'] = $this->currency->format($currentprice).' '. $this->language->get('text_away_from') .' ( '. $store_name .' )';//. $this->language->get('text_store') .' )';
        }

        $json['text_proceed_to_checkout'] = $this->language->get('text_proceed_to_checkout');

        
        $this->response->addHeader('Content-Type: application/json');
    	$this->response->setOutput(json_encode($json));
	}
	
	public function getProducts( $filter_data ) {
        
        $this->load->model( 'assets/product' );
        $this->load->model( 'tool/image' );


        $results = $this->model_assets_product->getProducts( $filter_data );

        $data['products'] = array();

        //echo "<pre>";print_r($results);die;
        foreach ( $results as $result ) {

            // if qty less then 1 dont show product 
            if($result['quantity'] <= 0)
                continue;

            if ( file_exists( DIR_IMAGE .$result['image'] ) ) {
                $image = $this->model_tool_image->resize( $result['image'], $this->config->get( 'config_image_product_width' ), $this->config->get( 'config_image_product_height' ) );
            } else {
                $image = $this->model_tool_image->resize( 'placeholder.png', $this->config->get( 'config_image_product_width' ), $this->config->get( 'config_image_product_height' ) );
            }

            //if category discount define override special price

            

            $discount = '';

            $s_price = 0;
            $o_price = 0;

            if ( !$this->config->get( 'config_inclusiv_tax' ) ) {
                //get price html
                if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
                    $price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );

                    $o_price = $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );
                } else {
                    $price = false;
                }
                if ( (float) $result['special_price'] ) {
                    $special_price = $this->currency->format( $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ) );

                    $s_price = $this->tax->calculate( $result['special_price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) );

                } else {
                    $special_price = false;
                }
            }else {
                if ( ( $this->config->get( 'config_customer_price' ) && $this->customer->isLogged() ) || !$this->config->get( 'config_customer_price' ) ) {
                    $price = $this->currency->format( $result['price'] );
                } else {
                    $price = $result['price'];
                }

                if ( (float) $result['special_price'] ) {
                    $special_price = $this->currency->format( $result['special_price'] );
                } else {
                    $special_price = $result['special_price'];
                }


                $s_price = $result['special_price'];
                $o_price = $result['price'];
            }


            //get qty in cart
            if(!empty($this->session->data['config_store_id'])){
              $key = base64_encode( serialize( array( 'product_store_id' => (int) $result['product_store_id'], 'store_id'=>$this->session->data['config_store_id'] ) ) );
            }else{
            $key = base64_encode( serialize( array( 'product_store_id' => (int) $result['product_store_id'], 'store_id'=>$filter_data['store_id'] ) ) ); 
            }
            if ( isset( $this->session->data['cart'][$key] ) ) {
                $qty_in_cart = $this->session->data['cart'][$key]['quantity'];
            } else {
                $qty_in_cart = 0;
            }

            
            //$result['name'] = strlen($result['name']) > 27 ? substr($result['name'],0,27)."..." : $result['name'];
            $name = $result['name'];
            if (isset($result['pd_name'] ) ) {
                $name = $result['pd_name'];
            }

            //$name .= str_repeat('&nbsp;',30 - strlen($result['name']));
            

            
            $percent_off = null;
            if(isset($s_price)  && isset($o_price) && $o_price !=0 && $s_price !=0 ) {
                $percent_off = (($o_price - $s_price) / $o_price) * 100;
            }

            $data['products'][] = array(
                'key' => $key,
                'qty_in_cart' => $qty_in_cart,
                'variations' => $this->model_assets_product->getVariations( $result['product_store_id'] ),
                'store_product_variation_id' => 0,
                'product_id' => $result['product_id'],
                'product_store_id'=> $result['product_store_id'],
                'default_variation_name' => $result['default_variation_name'],
                'thumb' => $image,
                'name' => $name,
                'unit' => $result['unit'],
                'description' => utf8_substr( strip_tags( html_entity_decode( $result['description'], ENT_QUOTES, 'UTF-8' ) ), 0, $this->config->get( 'config_product_description_length' ) ) . '..',
                'price' => $price,
                'special' => $special_price,
                'percent_off' => number_format($percent_off,0),
                'tax' => $result['tax_percentage'],
                'minimum' => $result['min_quantity'] > 0 ? $result['min_quantity'] : $result['quantity'],
                'rating' => 0,
                'href' => $this->url->link( 'product/product',  '&product_store_id=' . $result['product_store_id'] )
            );
        }

        return $data['products'];
    }
}
