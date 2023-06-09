<?php  

class ControllerDeliversystemDeliversystem extends Controller {
    private $error = array();

    public function updateDeliveryAddress($data) {

        $log = new Log('timeslot.log');

        $log->write('updateDelivery DS');

        $log->write($data);

        $response['status'] = false;
        
        if(isset($data['tokens']) && isset($data['body'])) {

            $log->write('updateDelivery DS if');
            
            $token = $data['tokens'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link')."/api/updateDeliveryAddress");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);//Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));
            $result = curl_exec($curl);



            $log->write('updateDelivery log');
            $log->write($result);
            curl_close($curl);
            $result = json_decode($result);
            if(isset($result->error))
            {
                $response['status'] = false;
                $response['error'] = $result->error;
            } else {
                $response['status'] = true;
                $response['data'] = $result;
            }
        }
        
        return $response;
        
    }
    
    public function updateDeliveryDateTime($data) {

        $log = new Log('timeslot.log');

        $log->write('updateDelivery DS');

        $log->write($data);

        $response['status'] = false;
        
        if(isset($data['tokens']) && isset($data['body'])) {

            $log->write('updateDelivery DS if');
            
            $token = $data['tokens'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link')."/api/updateDeliveryDateTime");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);//Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));
            $result = curl_exec($curl);



            $log->write('updateDelivery log');
            $log->write($result);
            curl_close($curl);
            $result = json_decode($result);
            if(isset($result->error))
            {
                $response['status'] = false;
                $response['error'] = $result->error;
            } else {
                $response['status'] = true;
                $response['data'] = $result;
            }
        }
        
        return $response;
        
    }

    public function updateDelivery($data) {

        $log = new Log('error.log');

        $log->write('updateDelivery DS');

        $log->write($data);

        $response['status'] = false;
        
        if(isset($data['tokens']) && isset($data['body'])) {

            $log->write('updateDelivery DS if');
            
            $token = $data['tokens'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link')."/api/update_delivery");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);//Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));
            $result = curl_exec($curl);



            $log->write('updateDelivery log');
            $log->write($result);
            curl_close($curl);
            $result = json_decode($result);
            if(isset($result->error))
            {
                $response['status'] = false;
                $response['error'] = $result->error;
            } else {
                $response['status'] = true;
                $response['data'] = $result;
            }
        }
        
        return $response;
        
    }

    public function getToken($data) {
        
        $log = new Log('error.log');
        $log->write('getToken');

        $response['status'] = false;
        //http://shopper.suacompraonline.com.br
        //echo "<pre>";print_r($this->config->get('config_shopper_link'));die;
        if(isset($data['email'])  && isset($data['password'])) {

            $email = $data['email'];

            $password = $data['password']; 

            $log->write($email);
            $log->write($password);
            $log->write($password);
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link')."/api/authenticate");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('email'=>$email,'password'=>$password));
            $result = curl_exec($curl);

            $log->write('getToken res');

            $log->write($result);


            curl_close($curl);
            $result = json_decode($result);
            
            if(!isset($result->error))
            {
                $response['status'] = true;
                $response['token'] = $result->token;
            }
        }

        return $response;
    }

    public function updateEcomProductStatus() {

        /*echo "Not now man. I am still updating.. :)";
        die;*/

        /*$st = '[1490,2553]';

        $st = json_decode($st);
        echo "<pre>";print_r($st);die;
        
        foreach ($st as $p_id) {
            echo $p_id;
        }
        echo "<pre>";print_r($st);die;*/
        $response['status'] = false;


        $json = array();

        $this->load->model('account/order');
        $this->load->model('account/return');
        $this->load->model('assets/product');

        $log = new Log('error.log');
        $log->write('updateEcomProductStatus');

        $log->write($this->request->post);
        
        /*$entityBody = file_get_contents('php://input');
        $body = json_decode($entityBody,true);*/

        /*$manifest_id = 712;

        $p_name =  'Cerveja Pilsen Skol Lata';
        $p_unit =  '350ml';*/

        $manifest_id = $this->request->post['delivery_id'];

        $p_name =  $this->request->post['p_name'];
        $p_unit =  $this->request->post['p_unit'];

        
        if (isset($manifest_id) && isset($p_name) ) {
            // Store
           
            $order_info = $this->model_account_order->getAdminOrder($manifest_id);

            $orderProducts = $this->model_account_order->getOrderProducts($manifest_id);

            /*$realproducts = $this->model_account_order->hasRealOrderProducts($order_id);

            if($realproducts) {
                $products = $this->model_account_order->getRealOrderProducts($order_id);  
            } else {
                $products = $this->model_account_order->getOrderProducts($order_id);
            }*/
                

            //echo "<pre>";print_r($orderProducts);die;

            if ($order_info) {

                $success = false;
                foreach ($orderProducts as $tmp_product) {

                    $product = $this->model_assets_product->getProductByProductStoreId($tmp_product['product_id']);

                    //echo "<pre>";print_r($product);die;
                    if(trim($product['name']) == trim($p_name) && $product['unit'] == $p_unit ) {

                        $send_data = $order_info;
                        $send_data =  array_merge($send_data,$product);
                        $send_data['opened'] = 0;
                        $send_data['return_reason_id'] = 1;
                        $send_data['comment'] = 'returned from driver';
                        $send_data['product_id'] = $product['product_store_id'];
                        $send_data['product'] = $product['name'];
                        $send_data['date_ordered'] = $order_info['date_added'];
                        
                        
                        

                        //echo "<pre>";print_r($send_data);die;

                        $log->write($send_data);

                        $return_id = $this->model_account_return->addReturn($send_data);

                        $success = true;
                    }
                }

                if(!$success) {
                    $response['message'][] = ['type' =>  'product not found' , 'body' => 'product not found'];
                }  else {
                    
                    $response['message'][] = ['type' =>  'success' , 'body' => 'return initiated' ];

                    $response['status'] = true;
                }

            } else {
                $log->write('order info not got');
            }

            /* end */
            
        } else {
            $response['error'] = 'Missing data';
        }

        $log->write('deliversystem_distributed responing');
        $log->write($response);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
        
    }
    
    public function createDelivery($data) {
        
        $response['status'] = false;
        
        $log = new Log('dserror.log');

        $log->write('createDelivery log' );
        //$log->write($data);

        if(isset($data['token']) && isset($data['body'])) {

            $token = $data['token'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link')."/api/create_delivery");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);//Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));
            $result = curl_exec($curl);

            
            $log->write($result);

            curl_close($curl);
            $result = json_decode($result);
            if(isset($result->error))
            {
                $response['status'] = false;
                $response['error'] = $result->error;
            } else {
                $response['status'] = true;
                $response['data'] = $result;
            }
        }
        
        return $response;
        
    }

    public function getDeliveryStatus($data) {

        $response['status'] = false;
        
        if(isset($data['token']) &&  isset($data['delivery_id'])) {

            $cSession = curl_init();
            $token = $data['token'];
            $delivery_id = $data['delivery_id'];
            
            curl_setopt($cSession,CURLOPT_URL,$this->config->get('config_shopper_link')."/api/deliveries/".$delivery_id);
            curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));
            //step3
            $result=curl_exec($cSession);
            
            curl_close($cSession);
            $result = json_decode($result);

            //echo "<pre>";print_r($result->message);die;
            if(isset($result->message))
            {   

                $response['status'] = false;
                $response['error'] = $result->message;
            } else {
                $response['status'] = true;
                $response['data'] = $result;
            }
        }

        return $response;
    }

    public function getDeliveries($data) {
        $cSession = curl_init();

        $token = '';
      
        curl_setopt($cSession,CURLOPT_URL,$this->config->get('config_shopper_link')."/api/deliveries");
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);
        //step3
        $result=curl_exec($cSession);
        //step4
        curl_close($cSession);
        $result = json_decode($result);

        return $result;
    }

    public function getProductStatus($data) {
        $response['status'] = false;
        //print_r("getProductStatuss");

        if(isset($data['token']) &&  isset($data['delivery_id'])) {


            $cSession = curl_init();
            $token = $data['token'];
            $delivery_id = $data['delivery_id'];

            curl_setopt($cSession,CURLOPT_URL,$this->config->get('config_shopper_link')."/api/products_status/".$delivery_id);
            curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));
            //step3
            $result=curl_exec($cSession);
            //step4
            //print_r($result);
            curl_close($cSession);
            $result = json_decode($result);


            if(isset($result->message))
            {   

                $response['status'] = false;
                $response['error'] = $result->message;
            } else {
                $response['status'] = true;
                $response['data'] = $result;
            }
        }

        return $response;
    }

    public function postRating($data) {
        $response['status'] = false;
        //print_r("getDeliveryStatus");
        /*
            delivery_id
            ratings 1
        */
        if(isset($data['token']) &&  isset($data['delivery_id'])  &&  isset($data['rating']) ) {

            $cSession = curl_init();
            $token = $data['token'];
            $delivery_id = $data['delivery_id'];
            $ratings = $data['rating'];
            $review = isset($data['review'])?$data['review']:"";

            curl_setopt($cSession,CURLOPT_URL,$this->config->get('config_shopper_link')."/api/ratings");
            curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($cSession, CURLOPT_POSTFIELDS, array('delivery_id'=>$delivery_id,'ratings'=>$ratings,'review'=>$review));

            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));
            //step3
            $result=curl_exec($cSession);
            //step4
            //print_r($result);

            return $result;
            /*curl_close($cSession);
            $result = json_decode($result);

            if(isset($result->error))
            {
                $response['status'] = false;
                $response['error'] = $result->error;
            } else {
                $response['status'] = true;
                $response['data'] = $result;
            }*/
        }

        return $response;
    }
    
    public function updateCancelledOrder($data) {
        $response['status'] = false;

        $log = new Log('error.log');

        $log->write('updateCancelledOrder' );
        $log->write($data);

        $this->load->model('account/order');
        $this->load->model('checkout/order');


        // refund stripe captured

        $stripe_info_order = $this->model_account_order->getStripeOrderPaymentId($data['delivery_id']);

        

        $log->write('refund stripe_info_order');
        $log->write($stripe_info_order);

        if($stripe_info_order) {

            $order_info = $this->model_checkout_order->getOrder($data['delivery_id']); 
            
            

            if($this->initStripe() && count($order_info) > 0) {
                $param['charge'] = $stripe_info_order['stripe_order_id'];
                /*$re = \Stripe\Refund::create(array(
                  "charge" => $param['charge']
                ));*/
                try {

                    $re = \Stripe\Refund::create(array(
                      "charge" => $param['charge'],
                      'amount' => round($order_info['total'] * 100,2)
                    ));

                    $log->write($re); 

                } catch (\Stripe\Error\Card $e) {
                //return redirect::refresh->withFlashMessage($e->getMessage());
                } catch (\Stripe\Error\InvalidRequest $e) {
                    //return redirect::refresh->withFlashMessage($e->getMessage());
                    $log->write($e->getMessage());
                } catch (\Stripe\Error\Authentication $e) {
                    //return redirect::refresh->withFlashMessage($e->getMessage());
                } catch (Exception $e) {
                    //return redirect::refresh->withFlashMessage($e->getMessage());
                }
                 
            }

            $log->write('refund stripe_info_order capture end');
        }

        if(isset($data['token']) &&  isset($data['delivery_id']))  {

            $log->write('updateCancelledOrder if' );

            $cSession = curl_init();
            $token = $data['token'];

            $log->write($data);

            curl_setopt($cSession,CURLOPT_URL,$this->config->get('config_shopper_link')."/api/cancelOrder");
            curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($cSession, CURLOPT_POSTFIELDS, $data);

            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));
            //step3
            $result=curl_exec($cSession);
            
            $log->write($result);
            return $result;
        }

        return $response;
    }
    
    // ok so i will make a url which will be called by delivery system (mu url) whenever status is changed from delviery system adn that my url will make an entry in history
    //http://dev.suacompraonline.com.br/index.php?path=deliversystem/deliversystem/updateOrderHistory
    public function updateOrderHistory() {

        /*echo "Not now man. I am still updating.. :)";
        die;*/
        $response['status'] = true;
        $response['deliversystem_distributed'] = false;
        $response['ds_transfer'] = false;
        $json = array();

        $log = new Log('error.log');
        $log->write('api/order');

        $log->write($this->request->post);
        
        /*$entityBody = file_get_contents('php://input');
        $body = json_decode($entityBody,true);*/

        
        $manifest_id = $this->request->post['manifest_id'];

        $status =  $this->request->post['status'];

        $log->write($status ." " . $manifest_id);
        
        if (isset($manifest_id) && isset($status)) {
            // Store
            $log->write('api/order validate');
            
            $this->load->model('localisation/order_status');
            $this->load->model('account/order');

            $order_status = $this->model_localisation_order_status->getOrderStatuses();

            $order_status_id = "no";

            $delvieryStatus = $this->model_localisation_order_status->getDeliveryOrderStatusByCode($status);


            $log->write($delvieryStatus);
            
            if(count($delvieryStatus) > 0 ) {
                $order_status_id = $delvieryStatus['order_status_id'];
            }

            /*foreach ($order_status as $order_state) {
                # code...
                if(strtolower($order_state['name']) == strtolower($status) ) {
                    $order_status_id = $order_state['order_status_id'];
                    break;
                }
            }*/

            /* start */

            // If order is not completed already

            $order_info = $this->model_account_order->getAdminOrder($manifest_id);

            if ($order_info  && $order_info['order_status_id'] != $order_status_id &&  !in_array( $order_info['order_status_id'], $this->config->get( 'config_complete_status') ) ) {

                $log->write('not completed take action');

                $log->write($order_status_id);
                $dataAddHisory['order_id'] = $manifest_id;
                $dataAddHisory['order_status_id'] = $order_status_id;
                $dataAddHisory['notify'] = 1;
                $dataAddHisory['append'] = 0;
                $dataAddHisory['comment'] = '';

                $url = HTTPS_SERVER;
                $api = 'api/order/addHistory';

                if (isset($api) && $order_status_id != 'no') {
                    
                    $url_data = array();
                    $log->write("if");
                    foreach ($dataAddHisory as $key => $value) {
                        if ($key != 'path' && $key != 'token' && $key != 'store_id') {
                            $url_data[$key] = $value;
                        }
                    }

                    $curl = curl_init();

                    // Set SSL if required
                    if (substr($url, 0, 5) == 'https') {
                        curl_setopt($curl, CURLOPT_PORT, 443);
                    }

                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                    /*curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);*/
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_URL, $url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                    $json = curl_exec($curl);
                    $log->write("json");
                    $log->write($url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                    
                    $log->write($json);
                    curl_close($curl);

                    // order payment distribution

                    $this->load->model('account/order');
                    if ( in_array( $order_status_id, $this->config->get( 'config_complete_status' ) ) ) {

                        //completed order

                        $log->write('order payment distribution');
                
                        $this->load->model('account/order');

                        $log->write($manifest_id);

                        $response['already_deliversystem_distributed'] = false;
                        if ($order_info && !$order_info['commsion_received']) {
                        //get order detail store id

                            $order_id = $manifest_id;
                            $status = 1;
                            //status:1
                            $store_id = $order_info['store_id'];

                            $distribution_resp = $this->model_account_order->payment_status($order_id, $status, $store_id);

                            $response['deliversystem_distributed'] = $distribution_resp['ds_payment_distributed'];
                            $response['ds_transfer'] = $distribution_resp['ds_transfer'];

                            $log->write('deliversystem_distributed');
                            $log->write($response);

                            $response['status'] = true;
                        } else {

                            $response['already_deliversystem_distributed'] = true;
                            $log->write('order commsion_received already');
                        }
                    }
                    // fininsh
                }

            } else {
                $log->write('already completed no action');
            }

            /* end */
            
        } else {
            $response['status'] = false;
            $response['error'] = 'Missing data';
        }

        $log->write('deliversystem_distributed responing');
        $log->write($response);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
        
        //return $response;
    }

    public function getAllDeliveryStatus($data) {

        $response['status'] = false;
        //print_r("getDeliveryStatus");

        if(isset($data['token'])) {

            $cSession = curl_init();
            $token = $data['token'];
            
            $url = $this->config->get('config_shopper_link')."/api/status_codes";

            curl_setopt($cSession,CURLOPT_URL,$url);

            curl_setopt($cSession, CURLOPT_HTTPHEADER, array('AUTHORIZATION: Bearer '.$token,'Accept: application/json'));

            //step3
            $result=curl_exec($cSession);
            

            curl_close($cSession);
            $result = json_decode($result,true);

            $response['status'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

    public function getShippingPrice($data) {
        
        $response['status'] = false;
        //http://shopper.suacompraonline.com.br
        //echo "<pre>";print_r($this->config->get('config_shopper_link'));die;
        if(isset($data['latitude'])  && isset($data['longitude']) && isset($data['dropoff_lat']) && isset($data['city']) && isset($data['delivery_priority']) && isset($data['dropoff_lng'])  ) {

            //http://localhost/deliverysystem.gatoo.eu/public/api/calculate_price
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link')."/api/calculate_price");
            //curl_setopt($curl, CURLOPT_URL, "http://localhost/deliverysystem.gatoo.eu/public/api/calculate_price");
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($curl);
            curl_close($curl);

            
            $result = json_decode($result);
            
            //echo "<pre>";print_r($result);die;

            if(isset($result->status) && $result->status)
            {
                $response['status'] = true;
                $response['data'] = $result->data;
            }
        }

        return $response;
    }

    public function distributeOrderPayment() {

        /*echo "Not now man. I am still updating.. :)";
        die;*/
        $response['status'] = false;
        $json = array();

        $log = new Log('error.log');
        $log->write('api/order');

        $entityBody = file_get_contents('php://input');

        $log->write(json_decode($entityBody));
        
        $body = json_decode($entityBody,true);

        $manifest_id = $body['manifest_id'];

        $status =  $body['status'];

        $log->write($status ." " . $manifest_id);
        

        if (isset($manifest_id) && isset($status)) {
            // Store
            $log->write('api/order validate');
            
            $this->load->model('sale/order');
            $this->load->model('account/order');

            $order_info = $this->model_account_order->getOrder($order_id);

            if ($order_info) {
            //get order detail store id

                $order_id = $this->request->post['order_id'];
                $status = $this->request->post['status'];
                //status:1
                $store_id = $order_info['store_id'];

                $this->model_sale_order->payment_status($order_id, $status, $store_id);

                $response['status'] = true;            
            }
        } else {
            $response['status'] = false;
            $response['error'] = 'Missing data';
        }

        return $response;
    }

    public function mpesaOrderStatus() {

        $response['status'] = false;

        $this->load->model('payment/mpesa');
        $this->load->model('account/order');

        $postData = file_get_contents('php://input');
        
        $log = new Log('error.log');
        $log->write('updateMpesaOrderStatus');
        $log->write($postData);

        $file = fopen("system/log/mpesa_log.txt", "w"); //url fopen should be allowed for this to occur
        if(fwrite($file, $postData) === FALSE)
        {
            fwrite("Error: no data written");
        }
        fclose($file);

        $postData = json_decode($postData);
        
        $stkCallback = $postData->Body;
        
        $log->write($stkCallback);

        $log->write($stkCallback->stkCallback->MerchantRequestID);

        $manifest_id = $this->model_payment_mpesa->getMpesaOrder($stkCallback->stkCallback->MerchantRequestID);

        $log->write("order_id".$manifest_id);
       
        if (isset($manifest_id)) {
            // Store

            //save CallbackMetadata MpesaReceiptNumber

            if(isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {

                    $log->write($value);

                    if($value->Name == 'MpesaReceiptNumber') {
                        $this->model_payment_mpesa->insertOrderTransactionId($manifest_id,$value->Value);        
                    }
                }
            }

            $order_info = $this->model_account_order->getAdminOrder($manifest_id);


            if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && $stkCallback->stkCallback->ResultCode == 0 && $order_info &&  !$order_info['order_status_id'] ) {
                //success pending to processing
                $order_status_id = $this->config->get('config_order_status_id');

                $log->write('updateMpesaOrderStatus validate');
            
                $this->load->model('localisation/order_status');

                $order_status = $this->model_localisation_order_status->getOrderStatuses();

                $dataAddHisory['order_id'] = $manifest_id;
                $dataAddHisory['order_status_id'] = $order_status_id;
                $dataAddHisory['notify'] = 0;
                $dataAddHisory['append'] = 0;
                $dataAddHisory['comment'] = '';

                $url = HTTPS_SERVER;
                $api = 'api/order/addHistory';

                if (isset($api)) {
                    
                    $url_data = array();
                    $log->write("if");
                    foreach ($dataAddHisory as $key => $value) {
                        if ($key != 'path' && $key != 'token' && $key != 'store_id') {
                            $url_data[$key] = $value;
                        }
                    }

                    $curl = curl_init();

                    // Set SSL if required
                    if (substr($url, 0, 5) == 'https') {
                        curl_setopt($curl, CURLOPT_PORT, 443);
                    }

                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                    curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_URL, $url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                    $json = curl_exec($curl);
                    $log->write("json");
                    $log->write($url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                    
                    $log->write($json);
                    curl_close($curl);

                    $response['status'] = true;
                }


            }


            

        } else {
            $response['status'] = false;
            $response['error'] = 'Missing data';
        }

        return $response;
    }

    public function updateMpesaOrderStatusWorking() {

        $response['status'] = false;

        $this->load->model('payment/mpesa');

        $postData = file_get_contents('php://input');
        
        $log = new Log('error.log');
        $log->write('updateMpesaOrderStatus');
        $log->write($postData);

        $file = fopen("system/log/mpesa_log.txt", "w"); //url fopen should be allowed for this to occur
        if(fwrite($file, $postData) === FALSE)
        {
            fwrite("Error: no data written");
        }
        fclose($file);

        $postData = json_decode($postData);
        
        $stkCallback = $postData->Body;
        
        $log->write($stkCallback);

        $log->write($stkCallback->stkCallback->MerchantRequestID);

        $manifest_id = $this->model_payment_mpesa->getMpesaOrder($stkCallback->stkCallback->MerchantRequestID);

        $log->write("order_id".$manifest_id);
        
        //echo '{"ResultCode": 0, "ResultDesc": "The service was accepted successfully", "ThirdPartyTransID": "1234567890"}';
        
        if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && $stkCallback->stkCallback->ResultCode == 0) {
            //success pending to processing
            $order_status_id = $this->config->get('config_order_status_id');
        } else {
            //failed
            //failed:  pending to failed status
            $order_status_id = $this->config->get('mpesa_failed_order_status_id');
        }

        if (isset($manifest_id)) {
            // Store

            //save CallbackMetadata MpesaReceiptNumber

            if(isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {

                    $log->write($value);

                    if($value->Name == 'MpesaReceiptNumber') {
                        $this->model_payment_mpesa->updateMpesaOrder($manifest_id,$value->Value);        
                    }
                }
            }
            
            

            $log->write('updateMpesaOrderStatus validate');
            
            $this->load->model('localisation/order_status');

            $order_status = $this->model_localisation_order_status->getOrderStatuses();

            $dataAddHisory['order_id'] = $manifest_id;
            $dataAddHisory['order_status_id'] = $order_status_id;
            $dataAddHisory['notify'] = 0;
            $dataAddHisory['append'] = 0;
            $dataAddHisory['comment'] = '';

            $url = HTTPS_SERVER;
            $api = 'api/order/addHistory';

            if (isset($api)) {
                
                $url_data = array();
                $log->write("if");
                foreach ($dataAddHisory as $key => $value) {
                    if ($key != 'path' && $key != 'token' && $key != 'store_id') {
                        $url_data[$key] = $value;
                    }
                }

                $curl = curl_init();

                // Set SSL if required
                if (substr($url, 0, 5) == 'https') {
                    curl_setopt($curl, CURLOPT_PORT, 443);
                }

                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_URL, $url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                $json = curl_exec($curl);
                $log->write("json");
                $log->write($url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                
                $log->write($json);
                curl_close($curl);

                $response['status'] = true;
            }
        } else {
            $response['status'] = false;
            $response['error'] = 'Missing data';
        }

        return $response;
    }

    public function iPayCallbackUrl() {

        $response['status'] = false;

        $data = [];

        $data['success'] = 'failed';
        
        //$postData = file_get_contents('php://input');
        
        $log = new Log('ipay.log');
        $log->write('iPayCallbackUrl');

        //$log->write($this->request->post);
        $log->write($this->request->get);

        $this->load->model('payment/mpesa');
        $this->load->model('checkout/order');

        $orders = []; 

        if (isset($this->request->get['p1']) && isset($this->request->get['txncd'])) {

            $order_reference_number = $this->request->get['p1'];

            $txncd = $this->request->get['txncd'];

            $this->load->model('account/order');

            $orders = $this->model_account_order->getOrderByReferenceIdIPay($order_reference_number);

            //print_r($orders);


            foreach ($orders as $order) {
                //$ret = $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cod_order_status_id'));

               // echo 'Below order id sud come<br>';

                //echo 'Parameters are Order: '. $order['order_id'] . 'to be made ' . $this->config->get('iPay_payment_software_order_status_id');

                //die();

                $ret = $this->model_checkout_order->addOrderHistory($order['order_id'], $this->config->get('iPay_payment_software_order_status_id'));

                //save order transaction id
                if(isset($txncd)) {
                    $this->model_payment_mpesa->insertOrderTransactionId($order['order_id'],$txncd); 
                      $data['success'] = 'success';      
                }
            }

        }


        //$data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header/onlyHeader');

        

        // $this->document->addStyle('/front/ui/theme/'.$this->config->get('config_template').'/css/style.css');
        // $this->document->addStyle('/front/ui/theme/'.$this->config->get('config_template').'/css/bootstrap.min.css');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/deliversystem/ipay-success.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/deliversystem/ipay-success.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/deliverysystem/ipay-success.tpl', $data));
        }

    }


    public function mPesaTakePayment() {

        /*echo "Not now man. I am still updating.. :)";
        die;*/
        $response['status'] = false;


        $json = array();

        $this->load->model('account/order');

        $log = new Log('error.log');
        $log->write('api/order');

        $log->write($this->request->post);
        
        /*$entityBody = file_get_contents('php://input');
        $body = json_decode($entityBody,true);*/

        
        $manifest_id = $this->request->post['delivery_id'];

        $phone_number =  $this->request->post['phone_number'];

        $log->write($phone_number ." " . $manifest_id);
        
        if (isset($manifest_id) && isset($phone_number)) {
            // Store
           
            $order_info = $this->model_account_order->getAdminOrder($manifest_id);

            if ($order_info &&  !in_array( $order_info['order_status_id'], $this->config->get( 'config_complete_status') ) ) {

                $order_ids[] = $manifest_id;

                $send['orders'] = $order_ids;
                $send['mpesa_phonenumber'] = $phone_number;

                $log->write($send);
                $log->write("send");
                $pay_res = $this->load->controller( 'payment/mpesa/apiDSConfirm',$send);

                $log->write($pay_res);
                $log->write("pay_res");

                if(!$pay_res['status']) {

                    if(isset($pay_res['response']->errorMessage)) {

                        $response['message'][] = ['type' =>  'payment error' , 'body' => $pay_res['response']->errorMessage ];
                    }
                }  else {
                    //success wait for listner
                    $response['message'][] = ['type' =>  'success' , 'body' => $pay_res['message'] ];

                    $response['status'] = true;
                }

            } else {
                $log->write('already completed no action');
            }

            /* end */
            
        } else {
            $response['error'] = 'Missing data';
        }

        $log->write('deliversystem_distributed responing');
        $log->write($response);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
        
        //return $response;
    }

    public function checkmPesaPaymentStatus() {

        /*echo "Not now man. I am still updating.. :)";
        die;*/
        $response['status'] = false;
        $response['message'] = '';
        
        $this->load->model('account/order');
        $this->load->model('payment/mpesa');

        $log = new Log('error.log');
        $log->write('api/order');

        $log->write($this->request->post);
        
        /*$entityBody = file_get_contents('php://input');
        $body = json_decode($entityBody,true);*/

        
        $manifest_id = $this->request->post['delivery_id'];
        
        if (isset($manifest_id)) {
            // Store


            $order_ids[] = $manifest_id;

            $send['orders'] = $order_ids;

            $payResp = $this->load->controller( 'payment/mpesa/apiDSComplete',$send);
            
            if($payResp){
                $response['status'] = $payResp['status'];

                if(!$response['status']) {
                    $response['message'] = $payResp['error'];    
                }
                
            }

            /* end */
            
        } else {
            $response['error'] = 'Missing data';
        }

        $log->write('deliversystem_distributed responing');
        $log->write($response);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
        
        //return $response;
    }

    public function updategeneralid() {
        
        $order_product_query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "order_product WHERE general_product_id is null" );


        //echo "<pre>";print_r($order_product_query->rows);die;
        foreach ( $order_product_query->rows as $order_product ) {

            //get general p id

            $order_detail_query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_store_id =".$order_product['product_id'] )->row;

            $general_product_id = isset($order_detail_query['product_id'])?$order_detail_query['product_id']:null;

            //echo "<pre>";print_r($general_product_id);die;

            $this->db->query( "UPDATE " . DB_PREFIX . "order_product SET general_product_id = " . (int) $general_product_id ." WHERE order_product_id = '" . (int) $order_product['order_product_id'] . "'" );
        }

    }
    
}

