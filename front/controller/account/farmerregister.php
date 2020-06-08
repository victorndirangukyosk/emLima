<?php

class ControllerAccountFarmerRegister extends Controller {
	private $error = array();


	public function validate() {

		if ( ( utf8_strlen( trim( $this->request->post['name'] ) ) < 1 ) || ( utf8_strlen( trim( $this->request->post['name'] ) ) > 32 ) ) {
			$this->error['name'] = $this->language->get( 'error_name' );
		}


		if ( ( utf8_strlen( $this->request->post['email'] ) > 96 ) || !filter_var( $this->request->post['email'], FILTER_VALIDATE_EMAIL ) ) {
			$this->error['email'] = $this->language->get( 'error_email' );
		}



		if ( $this->model_account_farmer->getTotalfarmersByEmail( $this->request->post['email'] ) ) {

			$numb = $this->model_account_farmer->getfarmerByEmail( $this->request->post['email'] );

			if(isset($numb['telephone'])) {
				$this->error['warning'] = sprintf( $this->language->get( 'error_exists_email' ), $numb['telephone'] );
			} else {
				$this->error['warning'] = $this->language->get( 'error_exists' );
			}


		}

		if (strpos($this->request->post['telephone'], '#') !== false || empty($this->request->post['telephone']) ) {
		    $this->error['telephone'] = $this->language->get( 'error_telephone' );
		}

		$this->request->post['telephone'] = preg_replace("/[^0-9]/", "", $this->request->post['telephone']);

		//echo "<pre>";print_r($this->request->post);die;

		if ( $this->model_account_farmer->getTotalfarmersByPhone( $this->request->post['telephone'] ) ) {
            $this->error['telephone_exists'] = $this->language->get( 'error_telephone_exists' );
        }





		// if (( utf8_strlen( trim( $this->request->post['address'] ) ) < 1 )) {
		// 	$this->error['address'] = $this->language->get( 'error_address' );
		// }

		return !$this->error;
	}



	public function register() {




    	$data['status'] = false;

    	// $this->load->language( 'account/login' );

    	$this->load->language( 'account/farmerregister' );

		//$this->document->setTitle( $this->language->get( 'heading_title' ) );

		// $data['referral_description'] = 'Referral';//$this->language->get( 'referral_description' );


		// $this->document->addScript( 'front/ui/javascript/jquery/datetimepicker/moment.js' );
		// $this->document->addScript( 'front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js' );
		// $this->document->addStyle( 'front/ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css' );
		// $this->document->addStyle( 'front/ui/theme/'.$this->config->get( 'config_template' ).'/stylesheet/layout_login.css' );


		$this->load->model( 'account/farmer' );

		$log = new Log('error.log');

		$this->request->post['telephone'] = preg_replace("/[^0-9]/", "", $this->request->post['telephone']);

		// $this->request->post['phone'] = $this->request->post['telephone'];
		if ( ( $this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validate() ) {



	        $this->load->model('account/farmer');

					$farmer_id = $this->model_account_farmer->addfarmer( $this->request->post );
					
					  $data['status'] = true;

					$data['message'] = $this->language->get('register_mail_sent');
					 

	                $data['success_message'] = $this->language->get('text_success');
	            } else {
			$log->write("outside form 3nr dime");
			$data['entry_submit'] = $this->language->get( 'entry_submit' );
			$data['entry_email_address'] = $this->language->get( 'entry_email_address' );
			 $data['entry_phone'] = $this->language->get( 'entry_phone' );
			$data['heading_text']  = $this->language->get('heading_text');
			// $data['text_account_already'] = sprintf( $this->language->get( 'text_account_already' ), $this->url->link( 'account/login', '', 'SSL' ) );

			if ( isset( $this->error['warning'] ) ) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			if ( isset( $this->error['name'] ) ) {
				$data['error_name'] = $this->error['name'];
			} else {
				$data['error_firstname'] = false;
			}



			if ( isset( $this->error['email'] ) ) {
				$data['error_email'] = $this->error['email'];
			} else {
				$data['error_email'] = false;
			}



			if ( isset( $this->error['telephone'] ) ) {
				$data['error_telephone'] = $this->error['telephone'];
			} else {
				$data['error_telephone'] = false;
			}

			if ( isset( $this->error['telephone_exists'] ) ) {
				$data['error_telephone_exists'] = $this->error['telephone_exists'];
			} else {
				$data['error_telephone_exists'] = false;
			}
		}

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


}
