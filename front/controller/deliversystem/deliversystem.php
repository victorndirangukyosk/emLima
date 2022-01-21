<?php

class ControllerDeliversystemDeliversystem extends Controller {

    private $error = [];

    public function updateDeliveryAddress($data) {
        $log = new Log('timeslot.log');

        $log->write('updateDelivery DS');

        $log->write($data);

        $response['status'] = false;

        if (isset($data['tokens']) && isset($data['body'])) {
            $log->write('updateDelivery DS if');

            $token = $data['tokens'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/updateDeliveryAddress');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);
            $result = curl_exec($curl);

            $log->write('updateDelivery log');
            $log->write($result);
            curl_close($curl);
            $result = json_decode($result);
            if (isset($result->error)) {
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

        if (isset($data['tokens']) && isset($data['body'])) {
            $log->write('updateDelivery DS if');

            $token = $data['tokens'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/updateDeliveryDateTime');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);
            $result = curl_exec($curl);

            $log->write('updateDelivery log');
            $log->write($result);
            curl_close($curl);
            $result = json_decode($result);
            if (isset($result->error)) {
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

        if (isset($data['tokens']) && isset($data['body'])) {
            $log->write('updateDelivery DS if');

            $token = $data['tokens'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/update_delivery');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);
            $result = curl_exec($curl);

            $log->write('updateDelivery log');
            $log->write($result);
            curl_close($curl);
            $result = json_decode($result);
            if (isset($result->error)) {
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
        if (isset($data['email']) && isset($data['password'])) {
            $email = $data['email'];

            $password = $data['password'];

            $log->write($email);
            $log->write($password);
            $log->write($password);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/authenticate');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, ['email' => $email, 'password' => $password]);
            $result = curl_exec($curl);

            $log->write('getToken res');

            $log->write($result);

            curl_close($curl);
            $result = json_decode($result);

            if (!isset($result->error)) {
                $response['status'] = true;
                $response['token'] = $result->token;
            }
        }

        return $response;
    }

    public function updateEcomProductStatus() {
        /* echo "Not now man. I am still updating.. :)";
          die; */

        /* $st = '[1490,2553]';

          $st = json_decode($st);
          echo "<pre>";print_r($st);die;

          foreach ($st as $p_id) {
          echo $p_id;
          }
          echo "<pre>";print_r($st);die; */
        $response['status'] = false;

        $json = [];

        $this->load->model('account/order');
        $this->load->model('account/return');
        $this->load->model('assets/product');

        $log = new Log('error.log');
        $log->write('updateEcomProductStatus');

        $log->write($this->request->post);

        /* $entityBody = file_get_contents('php://input');
          $body = json_decode($entityBody,true); */

        /* $manifest_id = 712;

          $p_name =  'Cerveja Pilsen Skol Lata';
          $p_unit =  '350ml'; */

        $manifest_id = $this->request->post['delivery_id'];

        $p_name = $this->request->post['p_name'];
        $p_unit = $this->request->post['p_unit'];

        if (isset($manifest_id) && isset($p_name)) {
            // Store

            $order_info = $this->model_account_order->getAdminOrder($manifest_id);

            $orderProducts = $this->model_account_order->getOrderProducts($manifest_id);

            /* $realproducts = $this->model_account_order->hasRealOrderProducts($order_id);

              if($realproducts) {
              $products = $this->model_account_order->getRealOrderProducts($order_id);
              } else {
              $products = $this->model_account_order->getOrderProducts($order_id);
              } */

            //echo "<pre>";print_r($orderProducts);die;

            if ($order_info) {
                $success = false;
                foreach ($orderProducts as $tmp_product) {
                    $product = $this->model_assets_product->getProductByProductStoreId($tmp_product['product_id']);

                    //echo "<pre>";print_r($product);die;
                    if (trim($product['name']) == trim($p_name) && $product['unit'] == $p_unit) {
                        $send_data = $order_info;
                        $send_data = array_merge($send_data, $product);
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

                if (!$success) {
                    $response['message'][] = ['type' => 'product not found', 'body' => 'product not found'];
                } else {
                    $response['message'][] = ['type' => 'success', 'body' => 'return initiated'];

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

        $log->write('createDelivery log');
        //$log->write($data);

        if (isset($data['token']) && isset($data['body'])) {
            $token = $data['token'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/create_delivery');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);
            $result = curl_exec($curl);

            $log->write($result);

            curl_close($curl);
            $result = json_decode($result);
            if (isset($result->error)) {
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

        if (isset($data['token']) && isset($data['delivery_id'])) {
            $cSession = curl_init();
            $token = $data['token'];
            $delivery_id = $data['delivery_id'];

            curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/deliveries/' . $delivery_id);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);
            //step3
            $result = curl_exec($cSession);

            curl_close($cSession);
            $result = json_decode($result);

            //echo "<pre>";print_r($result->message);die;
            if (isset($result->message)) {
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

        curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/deliveries');
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $result = curl_exec($cSession);
        //step4
        curl_close($cSession);
        $result = json_decode($result);

        return $result;
    }

    public function getProductStatus($data) {
        $response['status'] = false;
        //print_r("getProductStatuss");

        if (isset($data['token']) && isset($data['delivery_id'])) {
            $cSession = curl_init();
            $token = $data['token'];
            $delivery_id = $data['delivery_id'];

            curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/products_status/' . $delivery_id);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);
            //step3
            $result = curl_exec($cSession);
            //step4
            //print_r($result);
            curl_close($cSession);
            $result = json_decode($result);

            if (isset($result->message)) {
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
        if (isset($data['token']) && isset($data['delivery_id']) && isset($data['rating'])) {
            $cSession = curl_init();
            $token = $data['token'];
            $delivery_id = $data['delivery_id'];
            $ratings = $data['rating'];
            $review = isset($data['review']) ? $data['review'] : '';

            curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/ratings');
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_POSTFIELDS, ['delivery_id' => $delivery_id, 'ratings' => $ratings, 'review' => $review]);

            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);
            //step3
            $result = curl_exec($cSession);
            //step4
            //print_r($result);

            return $result;
            /* curl_close($cSession);
              $result = json_decode($result);

              if(isset($result->error))
              {
              $response['status'] = false;
              $response['error'] = $result->error;
              } else {
              $response['status'] = true;
              $response['data'] = $result;
              } */
        }

        return $response;
    }

    public function updateCancelledOrder($data) {
        $response['status'] = false;

        $log = new Log('error.log');

        $log->write('updateCancelledOrder');
        $log->write($data);

        $this->load->model('account/order');
        $this->load->model('checkout/order');

        // refund stripe captured

        $stripe_info_order = $this->model_account_order->getStripeOrderPaymentId($data['delivery_id']);

        $log->write('refund stripe_info_order');
        $log->write($stripe_info_order);

        if ($stripe_info_order) {
            $order_info = $this->model_checkout_order->getOrder($data['delivery_id']);

            if ($this->initStripe() && count($order_info) > 0) {
                $param['charge'] = $stripe_info_order['stripe_order_id'];
                /* $re = \Stripe\Refund::create(array(
                  "charge" => $param['charge']
                  )); */
                try {
                    $re = \Stripe\Refund::create([
                                'charge' => $param['charge'],
                                'amount' => round($order_info['total'] * 100, 2),
                    ]);

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

        if (isset($data['token']) && isset($data['delivery_id'])) {
            $log->write('updateCancelledOrder if');

            $cSession = curl_init();
            $token = $data['token'];

            $log->write($data);

            curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/cancelOrder');
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_POSTFIELDS, $data);

            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);
            //step3
            $result = curl_exec($cSession);

            $log->write($result);

            return $result;
        }

        return $response;
    }

// ok so i will make a url which will be called by delivery system (mu url) whenever status is changed from delviery system adn that my url will make an entry in history
//http://dev.suacompraonline.com.br/index.php?path=deliversystem/deliversystem/updateOrderHistory
    public function updateOrderHistory() {
        /* echo "Not now man. I am still updating.. :)";
          die; */
        $response['status'] = true;
        $response['deliversystem_distributed'] = false;
        $response['ds_transfer'] = false;
        $json = [];

        $log = new Log('error.log');
        $log->write('api/order');

        $log->write($this->request->post);

        /* $entityBody = file_get_contents('php://input');
          $body = json_decode($entityBody,true); */

        $manifest_id = $this->request->post['manifest_id'];

        $status = $this->request->post['status'];

        $log->write($status . ' ' . $manifest_id);

        if (isset($manifest_id) && isset($status)) {
            // Store
            $log->write('api/order validate');

            $this->load->model('localisation/order_status');
            $this->load->model('account/order');

            $order_status = $this->model_localisation_order_status->getOrderStatuses();

            $order_status_id = 'no';

            $delvieryStatus = $this->model_localisation_order_status->getDeliveryOrderStatusByCode($status);

            $log->write($delvieryStatus);

            if (count($delvieryStatus) > 0) {
                $order_status_id = $delvieryStatus['order_status_id'];
            }

            /* foreach ($order_status as $order_state) {
              # code...
              if(strtolower($order_state['name']) == strtolower($status) ) {
              $order_status_id = $order_state['order_status_id'];
              break;
              }
              } */

            /* start */

            // If order is not completed already

            $order_info = $this->model_account_order->getAdminOrder($manifest_id);

            if ($order_info && $order_info['order_status_id'] != $order_status_id && !in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
                $log->write('not completed take action');

                $log->write($order_status_id);
                $dataAddHisory['order_id'] = $manifest_id;
                $dataAddHisory['order_status_id'] = $order_status_id;
                $dataAddHisory['notify'] = 1;
                $dataAddHisory['append'] = 0;
                $dataAddHisory['comment'] = '';

                $url = HTTPS_SERVER;
                $api = 'api/order/addHistory';

                if (isset($api) && 'no' != $order_status_id) {
                    $url_data = [];
                    $log->write('if');
                    foreach ($dataAddHisory as $key => $value) {
                        if ('path' != $key && 'token' != $key && 'store_id' != $key) {
                            $url_data[$key] = $value;
                        }
                    }

                    $curl = curl_init();

                    // Set SSL if required
                    if ('https' == substr($url, 0, 5)) {
                        curl_setopt($curl, CURLOPT_PORT, 443);
                    }

                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                    /* curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']); */
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_URL, $url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                    $json = curl_exec($curl);
                    $log->write('json');
                    $log->write($url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                    $log->write($json);
                    curl_close($curl);

                    // order payment distribution

                    $this->load->model('account/order');
                    if (in_array($order_status_id, $this->config->get('config_complete_status'))) {
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

        if (isset($data['token'])) {
            $cSession = curl_init();
            $token = $data['token'];

            $url = $this->config->get('config_shopper_link') . '/api/status_codes';

            curl_setopt($cSession, CURLOPT_URL, $url);

            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer ' . $token, 'Accept: application/json']);

            //step3
            $result = curl_exec($cSession);

            curl_close($cSession);
            $result = json_decode($result, true);

            $response['status'] = true;
            $response['data'] = $result;
        }

        return $response;
    }

    public function getShippingPrice($data) {
        $response['status'] = false;
        //http://shopper.suacompraonline.com.br
        //echo "<pre>";print_r($this->config->get('config_shopper_link'));die;
        if (isset($data['latitude']) && isset($data['longitude']) && isset($data['dropoff_lat']) && isset($data['city']) && isset($data['delivery_priority']) && isset($data['dropoff_lng'])) {
            //http://localhost/deliverysystem.gatoo.eu/public/api/calculate_price

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link') . '/api/calculate_price');
            //curl_setopt($curl, CURLOPT_URL, "http://localhost/deliverysystem.gatoo.eu/public/api/calculate_price");

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($curl);
            curl_close($curl);

            $result = json_decode($result);

            //echo "<pre>";print_r($result);die;

            if (isset($result->status) && $result->status) {
                $response['status'] = true;
                $response['data'] = $result->data;
            }
        }

        return $response;
    }

    public function distributeOrderPayment() {
        /* echo "Not now man. I am still updating.. :)";
          die; */
        $response['status'] = false;
        $json = [];

        $log = new Log('error.log');
        $log->write('api/order');

        $entityBody = file_get_contents('php://input');

        $log->write(json_decode($entityBody));

        $body = json_decode($entityBody, true);

        $manifest_id = $body['manifest_id'];

        $status = $body['status'];

        $log->write($status . ' ' . $manifest_id);

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
        $postData = json_decode($postData);

        $stkCallback = $postData->Body;

        $log->write($stkCallback);

        $log->write($stkCallback->stkCallback->MerchantRequestID);
        $log->write('above is merchant request');

        $manifest_id = $this->model_payment_mpesa->getMpesaOrder($stkCallback->stkCallback->MerchantRequestID);
        $log->write('order_id' . $manifest_id);

        if ($manifest_id == 0 || $manifest_id == false) {
            $manifest_id_customer = $this->model_payment_mpesa->getMpesaCustomer($stkCallback->stkCallback->MerchantRequestID);
            $log->write('customer_id' . $manifest_id_customer);
            $log->write('manifest_id_customer' . $manifest_id_customer);
            $file = fopen('system/log/mpesa_customer_log.txt', 'w+'); //url fopen should be allowed for this to occur
            if (false === fwrite($file, $postData)) {
                fwrite('Error: no data written');
            }
            fclose($file);
        } else {

            $file = fopen('system/log/mpesa_log.txt', 'w+'); //url fopen should be allowed for this to occur
            if (false === fwrite($file, $postData)) {
                fwrite('Error: no data written');
            }
            fclose($file);
        }


        if (isset($manifest_id) && $manifest_id > 0) {
            // Store
            //save CallbackMetadata MpesaReceiptNumber

            if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                    $log->write($value);

                    if ('MpesaReceiptNumber' == $value->Name) {
                        $this->model_payment_mpesa->insertOrderTransactionId($manifest_id, $value->Value);
                    }
                }
            }

            $order_info = $this->model_account_order->getAdminOrder($manifest_id);

            if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode && $order_info && !$order_info['order_status_id']) {
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
                $dataAddHisory['paid'] = 'Y';

                $url = HTTPS_SERVER;
                $api = 'api/order/addHistory';

                if (isset($api)) {
                    $url_data = [];
                    $log->write('if');
                    foreach ($dataAddHisory as $key => $value) {
                        if ('path' != $key && 'token' != $key && 'store_id' != $key) {
                            $url_data[$key] = $value;
                        }
                    }

                    $curl = curl_init();

                    // Set SSL if required
                    if ('https' == substr($url, 0, 5)) {
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
                    $log->write('json');
                    $log->write($url . 'index.php?path=' . $api . ($url_data ? '&' . http_build_query($url_data) : ''));

                    $log->write($json);
                    curl_close($curl);

                    $response['status'] = true;
                }
            }
        } else if (isset($manifest_id_customer)) {
            //save CallbackMetadata MpesaReceiptNumber
            $log->write("callback url called in mpesa top up");
            $amount_topup = 0;
            if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                    $log->write($value);

                    if ('MpesaReceiptNumber' == $value->Name) {
                        $this->model_payment_mpesa->insertCustomerTransactionId($manifest_id_customer, $value->Value, $stkCallback->stkCallback->MerchantRequestID);
                        // $transaction_id=$value->Value;wrong
                        $log->write('mpesa wallet customer id not coming');
                        $log->write($manifest_id_customer);
                    }

                    if ('Amount' == $value->Name) {
                        $amount_topup = $value->Value;
                    }
                }
            }
            $log->write("callback url called in mpesa top up1");

            // $order_info = $this->model_account_order->getAdminOrder($manifest_id);
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($manifest_id_customer);
            $this->load->model('payment/mpesa');
            if (isset($manifest_id_customer) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode) {//&& $customer_info
                //success pending to processing
                $log->write("callback url called in mpesa top up2");

                $order_status_id = $this->config->get('config_order_status_id');
                $log->write('updateMpesaStatus validate');
                $dataAddCredit['customer_id'] = $manifest_id_customer;
                $dataAddCredit['order_status_id'] = $order_status_id;
                $dataAddCredit['notify'] = 0;
                $dataAddCredit['append'] = 0;
                $dataAddCredit['comment'] = '';
                $this->load->model('payment/mpesa');
                $this->model_payment_mpesa->addCustomerHistoryTransaction($manifest_id_customer, $this->config->get('mpesa_order_status_id'), $amount_topup, 'mPesa Online', 'mpesa', $stkCallback->stkCallback->MerchantRequestID);

                $response['status'] = true;
            }
        } else {
            $response['status'] = false;
            $response['error'] = 'Missing data';
        }

        return $response;
    }

    public function mpesaOrderStatusTransactions() {
        $MpesaReceiptNumber = NULL;
        $response['status'] = false;

        $this->load->model('payment/mpesa');
        $this->load->model('account/order');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $postData = file_get_contents('php://input');

        $log = new Log('error.log');
        $log->write('updateMpesaOrderStatus_Transactions');
        $log->write($postData);

        $file = fopen('system/log/mpesa_transactions_log.txt', 'w+'); //url fopen should be allowed for this to occur
        if (false === fwrite($file, $postData)) {
            fwrite('Error: no data written');
        }
        fclose($file);

        $postData = json_decode($postData);

        $stkCallback = $postData->Body;

        $log->write($stkCallback);

        $log->write($stkCallback->stkCallback->MerchantRequestID);
        $this->load->controller('payment/mpesa/mpesacallbackupdate', $stkCallback->stkCallback);

        $manifest_id = $this->model_payment_mpesa->getMpesaOrders($stkCallback->stkCallback->MerchantRequestID);

        $log->write('order_id');
        $log->write($manifest_id);
        $log->write('order_id');

        if (is_array($manifest_id) && count($manifest_id) > 0) {
            foreach ($manifest_id as $manifest_ids) {

                $log->write($manifest_ids['order_id']);
                $log->write($manifest_ids);
                // Store
                //save CallbackMetadata MpesaReceiptNumber

                if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                    foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                            $this->model_payment_mpesa->insertOrderTransactionId($manifest_ids['order_id'], $value->Value);
                            $this->model_payment_mpesa->updateMpesaOrderByMerchant($manifest_ids['order_id'], $value->Value, $stkCallback->stkCallback->CheckoutRequestID);
                        }
                    }
                }

                $order_info = $this->model_checkout_order->getOrder($manifest_ids['order_id']);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode && $order_info != NULL && $customer_info != NULL) {
                    $this->model_payment_mpesa->addOrderHistoryTransaction($order_info['order_id'], $this->config->get('mpesa_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'mPesa Online', 'mpesa');
                    //REMOVED FOR EXCEPTION
                    //$this->load->controller('payment/mpesa/mpesacallbackupdatemail', $stkCallback->stkCallback);
                    $log->write('updateMpesaOrderStatus_Transactions SUCCESS');
                }
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 != $stkCallback->stkCallback->ResultCode && $order_info != NULL && $customer_info != NULL) {
                    $this->model_payment_mpesa->addOrderHistoryTransactionFailed($order_info['order_id'], $this->config->get('mpesa_failed_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'mPesa Online', 'mpesa', $order_info['paid']);
                    //REMOVED FOR EXCEPTION
                    //$this->load->controller('payment/mpesa/mpesacallbackupdatemailfail', $stkCallback->stkCallback);
                    $log->write('updateMpesaOrderStatus_Transactions FAILED');
                }
            }
        }

        if (is_array($manifest_id) && count($manifest_id) > 0) {
            foreach ($manifest_id as $manifest_ids) {

                $log->write($manifest_ids['order_id']);
                $log->write($manifest_ids);
                // Store
                //save CallbackMetadata MpesaReceiptNumber

                if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                    foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                        }
                    }
                }

                $order_info = $this->model_checkout_order->getOrder($manifest_ids['order_id']);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode && $order_info != NULL && $customer_info != NULL) {
                    $this->load->controller('payment/mpesa/mpesacallbackupdatemail', $stkCallback->stkCallback);
                    $log->write('updateMpesaOrderStatus_Transactions SUCCESS');
                }
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 != $stkCallback->stkCallback->ResultCode && $order_info != NULL && $customer_info != NULL) {
                    $this->load->controller('payment/mpesa/mpesacallbackupdatemailfail', $stkCallback->stkCallback);
                    $log->write('updateMpesaOrderStatus_Transactions FAILED');
                }
            }
        }
        return $response;
    }

    public function mpesamobileOrderStatusTransactions() {
        $MpesaReceiptNumber = NULL;
        $response['status'] = false;

        $this->load->model('payment/mpesa');
        $this->load->model('account/order');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $postData = file_get_contents('php://input');

        $log = new Log('error.log');
        $log->write('updateMpesamobileOrderStatus_Transactions');
        $log->write($postData);

        $file = fopen('system/log/mpesa_mobile_transactions_log.txt', 'w+'); //url fopen should be allowed for this to occur
        if (false === fwrite($file, $postData)) {
            fwrite('Error: no data written');
        }
        fclose($file);

        $postData = json_decode($postData);

        $stkCallback = $postData->Body;

        $log->write($stkCallback);

        $log->write($stkCallback->stkCallback->MerchantRequestID);
        $this->load->controller('payment/mpesa/mpesacallbackupdate', $stkCallback->stkCallback);

        $manifest_id = $this->model_payment_mpesa->getMpesaOrders($stkCallback->stkCallback->MerchantRequestID);

        $log->write('order_id');
        $log->write($manifest_id);
        $log->write('order_id');

        if (is_array($manifest_id) && count($manifest_id) > 0) {
            foreach ($manifest_id as $manifest_ids) {

                $log->write($manifest_ids['order_id']);
                // Store
                //save CallbackMetadata MpesaReceiptNumber

                if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                    foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                            $this->model_payment_mpesa->insertOrderTransactionId($manifest_ids['order_id'], $value->Value);
                            $this->model_payment_mpesa->updateMpesaOrderByMerchant($manifest_ids['order_id'], $value->Value, $stkCallback->stkCallback->CheckoutRequestID);
                        }
                    }
                }

                $order_info = $this->model_checkout_order->getOrder($manifest_ids['order_id']);
                $customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode && $order_info != NULL && $customer_info != NULL) {
                    $this->model_payment_mpesa->addOrderHistoryTransaction($order_info['order_id'], $this->config->get('mpesa_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'mPesa Online', 'mpesa');
                    $this->load->controller('payment/mpesa/mpesacallbackupdatemail', $stkCallback->stkCallback);
                    $log->write('updateMpesaOrderStatus_Transactions SUCCESS');
                }
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 != $stkCallback->stkCallback->ResultCode && $order_info != NULL && $customer_info != NULL) {
                    $this->model_payment_mpesa->addOrderHistoryTransactionFailed($order_info['order_id'], $this->config->get('mpesa_failed_order_status_id'), $customer_info['customer_id'], 'customer', $order_info['order_status_id'], 'mPesa Online', 'mpesa', $order_info['paid']);
                    $this->load->controller('payment/mpesa/mpesacallbackupdatemailfail', $stkCallback->stkCallback);
                    $log->write('updateMpesaOrderStatus_Transactions FAILED');
                }
            }
        }

        return $response;
    }

    public function mpesaMobileCheckoutOrderStatusTransactions() {
        $MpesaReceiptNumber = NULL;
        $response['status'] = false;

        $this->load->model('payment/mpesa');
        $this->load->model('account/order');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $postData = file_get_contents('php://input');

        $log = new Log('error.log');
        $log->write('MPESA MOBILE CHECKOUT');
        $log->write($postData);

        $file = fopen('system/log/mpesa_mobile_checkout_log.txt', 'w+'); //url fopen should be allowed for this to occur
        if (false === fwrite($file, $postData)) {
            fwrite('Error: no data written');
        }
        fclose($file);

        $postData = json_decode($postData);

        $stkCallback = $postData->Body;

        $log->write($stkCallback);

        $log->write($stkCallback->stkCallback->MerchantRequestID);
        //$this->load->controller('payment/mpesa/mpesacallbackupdate', $stkCallback->stkCallback);

        $manifest_id = $this->model_payment_mpesa->getMpesaOrders($stkCallback->stkCallback->MerchantRequestID);

        $log->write('order_reference_number');
        $log->write($manifest_id);
        $log->write('order_reference_number');

        if (is_array($manifest_id) && count($manifest_id) > 0) {
            foreach ($manifest_id as $manifest_ids) {

                $log->write($manifest_ids['order_reference_number']);
                // Store
                //save CallbackMetadata MpesaReceiptNumber

                if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                    foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                            $this->model_payment_mpesa->insertMobileCheckoutOrderTransactionId($manifest_ids['order_reference_number'], $value->Value);
                        }
                    }
                }

                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode) {
                    $response['status'] = true;
                    $log->write('MOBILE CHECKOUT SUCCESS');
                }
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 != $stkCallback->stkCallback->ResultCode) {
                    $log->write('MOBILE CHECKOUT FAILED');
                }
            }
        }

        return $response;
    }

    public function mpesamobileOrderStatusTransactionss() {
        $MpesaReceiptNumber = NULL;
        $response['status'] = false;

        $this->load->model('payment/mpesa');
        $this->load->model('account/order');
        $this->load->model('checkout/order');
        $this->load->model('account/customer');

        $postData = file_get_contents('php://input');

        $log = new Log('error.log');
        $log->write('CALLBACK MPESA MOBILE CHECKOUT');
        $log->write($postData);

        $file = fopen('system/log/mpesa_mobile_checkout_log.txt', 'w+'); //url fopen should be allowed for this to occur
        if (false === fwrite($file, $postData)) {
            fwrite('Error: no data written');
        }
        fclose($file);

        $postData = json_decode($postData);

        $stkCallback = $postData->Body;

        $log->write($stkCallback);

        $log->write($stkCallback->stkCallback->MerchantRequestID);
        //$this->load->controller('payment/mpesa/mpesacallbackupdate', $stkCallback->stkCallback);

        $manifest_id = $this->model_payment_mpesa->getMpesaOrders($stkCallback->stkCallback->MerchantRequestID);

        $log->write('CALLBACK order_reference_number');
        $log->write($manifest_id);
        $log->write('CALLBACK order_reference_number');

        if (is_array($manifest_id) && count($manifest_id) > 0) {
            foreach ($manifest_id as $manifest_ids) {

                $log->write($manifest_ids['order_reference_number']);
                $transaction_details = $this->model_payment_mpesa->getOrderTransactionDetails($manifest_ids['order_reference_number']);
                $log->write('CALLBACK TRANSACTION DETAILS');
                $log->write($transaction_details);
                $log->write('CALLBACK TRANSACTION DETAILS');
                // Store
                //save CallbackMetadata MpesaReceiptNumber

                if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                    foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                            if (is_array($transaction_details) && count($transaction_details) <= 0) {
                                $this->model_payment_mpesa->insertMpesaOrderTransaction($manifest_ids['order_id'], $manifest_ids['order_reference_number'], $MpesaReceiptNumber);
                            }
                            if (is_array($transaction_details) && count($transaction_details) > 0) {
                                $this->model_payment_mpesa->updateMpesaOrderTransaction($manifest_ids['order_id'], $manifest_ids['order_reference_number'], $MpesaReceiptNumber);
                            }
                            //$this->model_payment_mpesa->insertMobileCheckoutOrderTransactionId($manifest_ids['order_reference_number'], $value->Value);
                            $this->model_payment_mpesa->updateMpesaOrderByMerchant($manifest_ids['order_id'], $value->Value, $stkCallback->stkCallback->CheckoutRequestID);
                        }
                    }
                }

                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode) {
                    $response['status'] = true;
                    $log->write('CALLBACK MOBILE CHECKOUT SUCCESS');
                }
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 != $stkCallback->stkCallback->ResultCode) {
                    $log->write('MOBILE CHECKOUT FAILED');
                }
            }
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

        $file = fopen('system/log/mpesa_log.txt', 'w'); //url fopen should be allowed for this to occur
        if (false === fwrite($file, $postData)) {
            fwrite('Error: no data written');
        }
        fclose($file);

        $postData = json_decode($postData);

        $stkCallback = $postData->Body;

        $log->write($stkCallback);

        $log->write($stkCallback->stkCallback->MerchantRequestID);

        $manifest_id = $this->model_payment_mpesa->getMpesaOrder($stkCallback->stkCallback->MerchantRequestID);

        $log->write('order_id' . $manifest_id);

        //echo '{"ResultCode": 0, "ResultDesc": "The service was accepted successfully", "ThirdPartyTransID": "1234567890"}';

        if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode) {
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

            if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {
                foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                    $log->write($value);

                    if ('MpesaReceiptNumber' == $value->Name) {
                        $this->model_payment_mpesa->updateMpesaOrder($manifest_id, $value->Value);
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
                $url_data = [];
                $log->write('if');
                foreach ($dataAddHisory as $key => $value) {
                    if ('path' != $key && 'token' != $key && 'store_id' != $key) {
                        $url_data[$key] = $value;
                    }
                }

                $curl = curl_init();

                // Set SSL if required
                if ('https' == substr($url, 0, 5)) {
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
                $log->write('json');
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
                if (isset($txncd)) {
                    $this->model_payment_mpesa->insertOrderTransactionId($order['order_id'], $txncd);
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
        /* echo "Not now man. I am still updating.. :)";
          die; */
        $response['status'] = false;

        $json = [];

        $this->load->model('account/order');

        $log = new Log('error.log');
        $log->write('api/order');

        $log->write($this->request->post);

        /* $entityBody = file_get_contents('php://input');
          $body = json_decode($entityBody,true); */

        $manifest_id = $this->request->post['delivery_id'];

        $phone_number = $this->request->post['phone_number'];

        $log->write($phone_number . ' ' . $manifest_id);

        if (isset($manifest_id) && isset($phone_number)) {
            // Store

            $order_info = $this->model_account_order->getAdminOrder($manifest_id);

            if ($order_info && !in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
                $order_ids[] = $manifest_id;

                $send['orders'] = $order_ids;
                $send['mpesa_phonenumber'] = $phone_number;

                $log->write($send);
                $log->write('send');
                $pay_res = $this->load->controller('payment/mpesa/apiDSConfirm', $send);

                $log->write($pay_res);
                $log->write('pay_res');

                if (!$pay_res['status']) {
                    if (isset($pay_res['response']->errorMessage)) {
                        $response['message'][] = ['type' => 'payment error', 'body' => $pay_res['response']->errorMessage];
                    }
                } else {
                    //success wait for listner
                    $response['message'][] = ['type' => 'success', 'body' => $pay_res['message']];

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
        /* echo "Not now man. I am still updating.. :)";
          die; */
        $response['status'] = false;
        $response['message'] = '';

        $this->load->model('account/order');
        $this->load->model('payment/mpesa');

        $log = new Log('error.log');
        $log->write('api/order');

        $log->write($this->request->post);

        /* $entityBody = file_get_contents('php://input');
          $body = json_decode($entityBody,true); */

        $manifest_id = $this->request->post['delivery_id'];

        if (isset($manifest_id)) {
            // Store

            $order_ids[] = $manifest_id;

            $send['orders'] = $order_ids;

            $payResp = $this->load->controller('payment/mpesa/apiDSComplete', $send);

            if ($payResp) {
                $response['status'] = $payResp['status'];

                if (!$response['status']) {
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
        $order_product_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'order_product WHERE general_product_id is null');

        //echo "<pre>";print_r($order_product_query->rows);die;
        foreach ($order_product_query->rows as $order_product) {
            //get general p id

            $order_detail_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'product_to_store WHERE product_store_id =' . $order_product['product_id'])->row;

            $general_product_id = isset($order_detail_query['product_id']) ? $order_detail_query['product_id'] : null;

            //echo "<pre>";print_r($general_product_id);die;

            $this->db->query('UPDATE ' . DB_PREFIX . 'order_product SET general_product_id = ' . (int) $general_product_id . " WHERE order_product_id = '" . (int) $order_product['order_product_id'] . "'");
        }
    }

    public function mpesamobileOrderStatusTransactionsTest() {

        $postData = file_get_contents('php://input');

        $log = new Log('error.log');
        $log->write('updateMpesamobileOrderStatus_Transactions_test');
        $log->write($postData);

        $file = fopen('system/log/mpesa_mobile_test_transactions_log.txt', 'w+'); //url fopen should be allowed for this to occur
        if (false === fwrite($file, $postData)) {
            fwrite('Error: no data written');
        }
        fclose($file);

        $postData = json_decode($postData);
        $log->write($postData);
    }

    public function mpesamobileTopupStatus() {
        try {
            $MpesaReceiptNumber = NULL;
            $response['status'] = false;

            $this->load->model('payment/mpesa');
            $this->load->model('account/order');
            $this->load->model('checkout/order');
            $this->load->model('account/customer');

            $postData = file_get_contents('php://input');

            $log = new Log('error.log');
            $log->write('CALLBACK MPESA MOBILE -Wallet TOPUP');
            $log->write($postData);

            $file = fopen('system/log/mpesa_mobile_topup_log.txt', 'w+'); //url fopen should be allowed for this to occur
            if (false === fwrite($file, $postData)) {
                fwrite('Error: no data written');
            }
            fclose($file);

            $postData = json_decode($postData);

            $stkCallback = $postData->Body;

            $log->write($stkCallback);

            $log->write($stkCallback->stkCallback->MerchantRequestID);
            //$this->load->controller('payment/mpesa/mpesacallbackupdate', $stkCallback->stkCallback);
            // $manifest_id = $this->model_payment_mpesa->getMpesaOrders($stkCallback->stkCallback->MerchantRequestID);
            // $manifest_id =$manifest_id_customer= $this->model_payment_mpesa->getMpesaCustomers($stkCallback->stkCallback->MerchantRequestID);
            $manifest_id = $this->model_payment_mpesa->getMpesaCustomers($stkCallback->stkCallback->MerchantRequestID);

            $manifest_id_customer = $manifest_id['customer_id'];
            $log->write('CALLBACK customer_reference_number');
            $log->write($manifest_id_customer);
            $log->write('CALLBACK customer_reference_number');

            // if (is_array($manifest_id) && count($manifest_id) > 0) {
            if (isset($manifest_id_customer)) {
                // foreach ($manifest_id as $manifest_ids) {

                $log->write($manifest_id['order_reference_number']);
                $transaction_details = $this->model_payment_mpesa->getOrderTransactionDetails($manifest_id['order_reference_number']);
                $log->write('CALLBACK TRANSACTION DETAILS');
                $log->write($transaction_details);
                $log->write('CALLBACK TRANSACTION DETAILS');
                // Store
                //save CallbackMetadata MpesaReceiptNumber
                // $amount_topup =0;
                if (isset($stkCallback->stkCallback->CallbackMetadata->Item)) {

                    foreach ($stkCallback->stkCallback->CallbackMetadata->Item as $key => $value) {
                        $log->write($value);

                        if ('MpesaReceiptNumber' == $value->Name) {
                            $MpesaReceiptNumber = $value->Value;
                            // $this->model_payment_mpesa->insertCustomerTransactionId($manifest_id, $value->Value);

                            if (is_array($transaction_details) && count($transaction_details) <= 0) {
                                $this->model_payment_mpesa->insertMpesaCustomerTransaction($manifest_id['order_id'], $manifest_id['customer_id'], $manifest_id['order_reference_number'], $MpesaReceiptNumber, $stkCallback->stkCallback->MerchantRequestID);
                            }
                            if (is_array($transaction_details) && count($transaction_details) > 0) {
                                $this->model_payment_mpesa->updateMpesaOrderTransaction($manifest_id['order_id'], $manifest_id['customer_id'], $manifest_id['order_reference_number'], $MpesaReceiptNumber);
                            }

                            //$this->model_payment_mpesa->insertMobileCheckoutOrderTransactionId($manifest_ids['order_reference_number'], $value->Value);
                            $this->model_payment_mpesa->updateMpesaCustomerByMerchant($manifest_id['order_id'], $manifest_id['customer_id'], $value->Value, $stkCallback->stkCallback->CheckoutRequestID);
                        }
                        if ('Amount' == $value->Name) {
                            $amount_topup = $value->Value;
                        }
                    }
                }
                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomer($manifest_id_customer);
                $this->load->model('payment/mpesa');
                $log->write('payment /topup added to wallet   before If condition 111');

                if (isset($manifest_id_customer) && isset($stkCallback->stkCallback->ResultCode) && 0 == $stkCallback->stkCallback->ResultCode) {
                    $log->write('payment /topup added to wallet   before If condition 222');
                    $order_status_id = $this->config->get('config_order_status_id');
                    $log->write('updateMpesaStatus validate');
                    $dataAddCredit['customer_id'] = $manifest_id;
                    $dataAddCredit['order_status_id'] = $order_status_id;
                    $dataAddCredit['notify'] = 0;
                    $dataAddCredit['append'] = 0;
                    $dataAddCredit['comment'] = '';
                    $this->load->model('payment/mpesa');
                    // $this->model_payment_mpesa->addCustomerHistoryTransaction($manifest_id_customer, $this->config->get('mpesa_order_status_id'), $amount_topup, 'mPesa Online', 'mpesa', $stkCallback->stkCallback->MerchantRequestID);
                    $this->model_payment_mpesa->addCustomerHistoryTransaction($manifest_id_customer, $this->config->get('mpesa_order_status_id'), $amount_topup, 'mPesa Online', 'mpesa', $stkCallback->stkCallback->MerchantRequestID);

                    $response['status'] = true;
                    $log->write('CALLBACK MOBILE TOPUP SUCCESS');
                    $log->write('payment /topup added to wallet');
                }
                if (isset($manifest_id) && isset($stkCallback->stkCallback->ResultCode) && 0 != $stkCallback->stkCallback->ResultCode) {
                    $log->write('MOBILE TOPUP FAILED');
                    $log->write('MOBILE TOPUP FAILED');
                }
                // }
            }
        } catch (exception $ex) {
            $log = new Log('error.log');
            $log->write('MOBILE TOPUP FAILED');
            $log->write($ex);
        } finally {

            return $response;
        }
    }

    public function pezeshacallback() {

        $this->load->model('pezesha/pezeshaloanreceivables');
        $postData = file_get_contents('php://input');

        $log = new Log('error.log');
        $log->write('pezesha_call_back');
        $log->write($postData);

        $file = fopen('system/log/pezesha_' . date('Y-m-d') . '.txt', 'a+'); //url fopen should be allowed for this to occur
        if (false === fwrite($file, date('Y-m-d H:i:s') . ': ' . $postData . "\n")) {
            fwrite('Error: no data written');
        }
        fclose($file);
        $postData = json_decode($postData, true);
        $log = new Log('error.log');
        $log->write($postData);
        if ($this->validate($postData)) {
            $orders = $postData['order_id'];
            foreach ($orders as $order) {
                $postData['order'] = $order;
                $this->model_pezesha_pezeshaloanreceivables->loanmpesadetails($postData);
            }
            $json['status'] = 200;
            $json['success'] = 1;
            $json['message'] = 'Loan Details Saved Successfull!';
        } else {
            $log->write('ERROR');
            $json['status'] = 400;
            $json['success'] = 0;

            foreach ($this->error as $key => $value) {
                $json['message'][] = ['type' => $key, 'body' => $value];
            }

            http_response_code(400);
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate($data) {
        if (!isset($data['order_id']) || !is_array($data['order_id']) || (is_array($data['order_id']) && count($data['order_id']) <= 0)) {
            $this->error['order_id'] = 'Order Id Is Required!';
        }

        if (isset($data['order_id']) && is_array($data['order_id']) && count($data['order_id']) > 0) {
            $log = new Log('error.log');
            $this->load->model('pezesha/pezeshaloanreceivables');
            $unable_to_find = NULL;
            foreach ($data['order_id'] as $order_id) {
                $pezesha_loan_details = $this->model_pezesha_pezeshaloanreceivables->findPezeshaLoanById($order_id);
                if (count($pezesha_loan_details) == 0) {
                    $unable_to_find .= ' #' . $order_id;
                }
            }
        }

        if ($unable_to_find != NULL) {
            $this->error['order_id'] = 'Invalid Order Ids(' . $unable_to_find . ')';
        }

        if (!isset($data['type']) || $data['type'] == NULL) {
            $this->error['type'] = 'Loan Type Is Required!';
        }

        if (!isset($data['merchant_id']) || $data['merchant_id'] == NULL) {
            $this->error['merchant_id'] = 'Merchant ID Is Required!';
        }

        if (!isset($data['pezesha_id']) || $data['pezesha_id'] == NULL) {
            $this->error['pezesha_id'] = 'Pezesha ID Is Required!';
        }

        if (!isset($data['loan_id']) || $data['loan_id'] == NULL) {
            $this->error['loan_id'] = 'Loan ID Is Required!';
        }

        if (!isset($data['amount']) || $data['amount'] <= 0 || $data['amount'] == NULL) {
            $this->error['loan_id'] = 'Loan ID Is Required!';
        }

        if (!isset($data['account']) || $data['account'] == NULL) {
            $this->error['account'] = 'National ID Is Required!';
        }

        if (!isset($data['mpesa_reference']) || $data['mpesa_reference'] == NULL) {
            $this->error['mpesa_reference'] = 'Mpesa Reference Is Required!';
        }

        if (!isset($data['transaction_date']) || $data['transaction_date'] == NULL) {
            $this->error['transaction_date'] = 'Transaction Date Is Required!';
        }

        return !$this->error;
    }

    public function getPezeshaReceivedPayments() {
        $log = new Log('error.log');
        $log->write('CRONTAB');
        $log->write(date('d-m-y h:i:sa'));
        $log->write('CRONTAB');
        $auth_response = $this->load->controller('payment/pezesha/auth');
        $this->load->model('pezesha/pezeshaloanreceivables');
        $pezesha_loan_details = $this->model_pezesha_pezeshaloanreceivables->getPezeshaReceivables();

        $transactions_details = array();
        foreach ($pezesha_loan_details as $pezesha_loan_detail) {
            $tr = NULL;
            $tr['transaction_id'] = $pezesha_loan_detail['mpesa_reference'];
            $tr['merchant_id'] = $pezesha_loan_detail['merchant_id'];
            $tr['face_amount'] = $pezesha_loan_detail['amount'];
            $tr['transaction_time'] = $pezesha_loan_detail['transaction_date'];
            $category = array('key' => 'category', 'value' => 'Fresh Produce');
            $location = array('key' => 'location', 'value' => $pezesha_loan_detail['shipping_address']);
            $tr['other_details'][] = $category;
            $tr['other_details'][] = $location;
            $transactions_details[] = $tr;
        }

        /* $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($transactions_details)); */

        $body = array('channel' => $this->config->get('pezesha_channel'), 'transactions' => $transactions_details);
        //$body = http_build_query($body);
        $body = json_encode($body);
        $log->write($body);
        $curl = curl_init();
        if ($this->config->get('pezesha_environment') == 'live') {
            curl_setopt($curl, CURLOPT_URL, 'https://api.pezesha.com/mfi/v1.1/data');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
        } else {
            curl_setopt($curl, CURLOPT_URL, 'https://staging.api.pezesha.com/mfi/v1.1/data');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Authorization:Bearer ' . $auth_response]);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);

        $log->write($result);
        curl_close($curl);
        $result = json_decode($result, true);
        $log->write($result);
        $json = $result;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        return $json;
    }

    public function getunpaidorderssevendayscredit() {
        $log = new Log('error.log');

        $json = [];
        $data['filter_status'] = 1;
        $data['filter_payment_terms'] = '7 Days Credit';
        $this->load->model('account/order');
        $this->load->model('sale/order');
        $this->load->model('report/excel');
        $all_customers = $this->model_account_order->getUnpaidOrdersCutomerIds();
        $all_customers = array_column($all_customers, 'customer_id');
        $all_customers = array_unique($all_customers);
        $all_customers = implode(",", $all_customers);
        $data['filter_customer_id_array'] = $all_customers;
        $all_unpaid_customers = $this->model_sale_order->getCustomersNew($data);
        $log = new Log('error.log');
        $data['pending_order_id'] = NULL;

        foreach ($all_unpaid_customers as $customer) {
            $data['customer_id'] = $customer['customer_id'];
            $page = 1;
            $results_orders = $this->model_account_order->getOrdersNewByCustomerId($customer['customer_id'], ($page - 1) * 10, 10, $NoLimit = true);
            $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
            if (count($results_orders) > 0) {
                foreach ($results_orders as $order) {
                    if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                        $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                        if (empty($order['transcation_id'])) {
                            $data['pending_order_id'][$customer['customer_id']][] = $order['order_id'];
                        }
                    }
                }
            }
            $data['filter_order_id_array'] = implode(",", $data['pending_order_id'][$customer['customer_id']]);
            //$this->model_report_excel->download_customer_unpaid_order_excel($data);
        }
        $this->load->controller('pdf/pdf', $data);

        $json['status'] = 200;
        $json['data'] = $data['pending_order_id'];
        $json['unpaid_orders'] = count($data['pending_order_id']);
        $json['success'] = 'Customer Unpaid Orders';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        //return $data;
    }

    public function getunpaidordersfifteendayscredit() {

        $json = [];
        $data['filter_status'] = 1;
        $data['filter_payment_terms'] = '15 Days Credit';
        $this->load->model('account/order');
        $this->load->model('sale/order');
        $this->load->model('report/excel');
        $all_customers = $this->model_account_order->getUnpaidOrdersCutomerIds();
        $all_customers = array_column($all_customers, 'customer_id');
        $all_customers = array_unique($all_customers);
        $all_customers = implode(",", $all_customers);
        $data['filter_customer_id_array'] = $all_customers;
        $all_unpaid_customers = $this->model_sale_order->getCustomersNew($data);
        $log = new Log('error.log');
        $data['pending_order_id'] = NULL;

        foreach ($all_unpaid_customers as $customer) {
            $data['customer_id'] = $customer['customer_id'];
            $log->write($customer);
            $page = 1;
            $results_orders = $this->model_account_order->getOrdersNewByCustomerId($customer['customer_id'], ($page - 1) * 10, 10, $NoLimit = true);
            $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
            if (count($results_orders) > 0) {
                foreach ($results_orders as $order) {
                    if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                        $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                        if (empty($order['transcation_id'])) {
                            $data['pending_order_id'][$customer['customer_id']][] = $order['order_id'];
                        }
                    }
                }
            }
            $data['filter_order_id_array'] = implode(",", $data['pending_order_id'][$customer['customer_id']]);
            //$this->model_report_excel->download_customer_unpaid_order_excel($data);
        }
        $this->load->controller('pdf/pdf', $data);

        $json['status'] = 200;
        $json['data'] = $data['pending_order_id'];
        $json['unpaid_orders'] = count($data['pending_order_id']);
        $json['success'] = 'Customer Unpaid Orders';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        //return $data;
    }

    public function getunpaidordersthirtydayscredit() {
        $log = new Log('error.log');
        $json = [];
        $data['filter_status'] = 1;
        $data['filter_payment_terms'] = '30 Days Credit';
        $this->load->model('account/order');
        $this->load->model('sale/order');
        $this->load->model('report/excel');
        $all_customers = $this->model_account_order->getUnpaidOrdersCutomerIds();
        $all_customers = array_column($all_customers, 'customer_id');
        $all_customers = array_unique($all_customers);
        $all_customers = implode(",", $all_customers);
        $data['filter_customer_id_array'] = $all_customers;
        $all_unpaid_customers = $this->model_sale_order->getCustomersNew($data);
        $data['pending_order_id'] = NULL;

        foreach ($all_unpaid_customers as $customer) {
            $data['customer_id'] = $customer['customer_id'];
            $page = 1;
            $results_orders = $this->model_account_order->getOrdersNewByCustomerId($customer['customer_id'], ($page - 1) * 10, 10, $NoLimit = true);
            $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
            if (count($results_orders) > 0) {
                foreach ($results_orders as $order) {
                    if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                        $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                        if (empty($order['transcation_id'])) {
                            $data['pending_order_id'][$customer['customer_id']][] = $order['order_id'];
                        }
                    }
                }
            }
            $data['filter_order_id_array'] = implode(",", $data['pending_order_id'][$customer['customer_id']]);
            //$this->model_report_excel->download_customer_unpaid_order_excel($data);
        }
        $this->load->controller('pdf/pdf', $data);

        $json['status'] = 200;
        $json['data'] = $data['pending_order_id'];
        $json['unpaid_orders'] = count($data['pending_order_id']);
        $json['success'] = 'Customer Unpaid Orders';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        //return $data;
    }

    public function getunpaidorderspaymentondelivery() {
        $log = new Log('error.log');
        $json = [];
        $data['filter_status'] = 1;
        $data['filter_payment_terms'] = 'Payment On Delivery';
        $this->load->model('account/order');
        $this->load->model('sale/order');
        $this->load->model('report/excel');
        $all_customers = $this->model_account_order->getUnpaidOrdersCutomerIds();
        $all_customers = array_column($all_customers, 'customer_id');
        $all_customers = array_unique($all_customers);
        $all_customers = implode(",", $all_customers);
        $data['filter_customer_id_array'] = $all_customers;
        $all_unpaid_customers = $this->model_sale_order->getCustomersNew($data);
        $data['pending_order_id'] = NULL;

        foreach ($all_unpaid_customers as $customer) {
            $data['customer_id'] = $customer['customer_id'];
            $page = 1;
            $results_orders = $this->model_account_order->getOrdersNewByCustomerId($customer['customer_id'], ($page - 1) * 10, 10, $NoLimit = true);
            $PaymentFilter = ['mPesa On Delivery', 'Cash On Delivery', 'mPesa Online', 'Corporate Account/ Cheque Payment', 'PesaPal', 'Interswitch', 'Pezesha'];
            if (count($results_orders) > 0) {
                foreach ($results_orders as $order) {
                    if (in_array($order['payment_method'], $PaymentFilter) && ($order['order_status_id'] == 4 || $order['order_status_id'] == 5)) {
                        $order['transcation_id'] = $this->model_sale_order->getOrderTransactionId($order['order_id']);
                        if (empty($order['transcation_id'])) {
                            $data['pending_order_id'][$customer['customer_id']][] = $order['order_id'];
                        }
                    }
                }
            }
            $data['filter_order_id_array'] = implode(",", $data['pending_order_id'][$customer ['customer_id']]);
            //$this->model_report_excel->download_customer_unpaid_order_excel($data);
        }
        $this->load->controller('pdf/pdf', $data);

        $json['status'] = 200;
        $json['data'] = $data['pending_order_id'];
        $json['unpaid_orders'] = count($data['pending_order_id']);
        $json['success'] = 'Customer Unpaid Orders';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        //return $data;
    }

    public function createorderwithmissingproducts() {
        $log = new Log('error.log');
        $data = NULL;
        $order_id = base64_decode($this->request->get['order_id']);
        //$order_id = $this->request->get['order_id'];
        if (is_numeric($order_id) && $order_id > 0) {

            $this->load->model('checkout/order');
            $this->load->model('sale/order');
            $this->load->model('assets/product');
            $this->load->model('account/customer');

            $order_info = $this->model_sale_order->getOrder($order_id);
            if ($order_info != NULL && is_array($order_info) && count($order_info) > 0) {
                $totals = $this->model_sale_order->getOrderTotals($order_id);
                $filter['filter_order_id'] = $order_id;
                $products = $this->model_checkout_order->getOrderedMissingProducts($filter);

                $sub_total = 0;
                $tax = 0;

                $i = 0;
                foreach ($products as $product) {
                    $product_info = $this->model_assets_product->getProduct($product['product_id'], true);
                    $data['products'][] = [
                        'order_id' => $order_id,
                        'product_id' => $product['product_id'],
                        'general_product_id' => $product_info['product_id'],
                        'variation_id' => 0,
                        'vendor_id' => $product_info['merchant_id'],
                        'store_id' => $product_info['store_id'],
                        'name' => $product_info['name'],
                        'unit' => $product_info['unit'],
                        'model' => $product_info['model'],
                        'model' => $product_info['model'],
                        'quantity' => $product['quantity_required'],
                        'price' => $product['mp_price'],
                        'total' => $product['mp_price'] * $product['quantity_required'],
                        'tax' => $this->tax->getTax($product['mp_price'], $product_info['tax_class_id']),
                        'reward' => 0,
                        'product_type' => 'replacable',
                        'product_note' => $product['product_note'],
                    ];
                    $sub_total += $product['mp_price'] * $product['quantity_required'];
                    $tax += $product['mp_tax'] * $product['quantity_required'];
                    $i++;
                }

                $new_total = $sub_total + $tax;
                $order_info['total'] = $new_total;

                $total_data = [];

                foreach ($totals as $total) {
                    if ($total['code'] == 'sub_total') {
                        $total_data[] = [
                            'code' => $total['code'],
                            'title' => $total['title'],
                            'value' => $sub_total,
                            'sort_order' => $total['sort_order'],
                            'actual_value' => NULL,
                        ];
                    } elseif ($total['code'] == 'tax') {
                        $total_data[] = [
                            'code' => $total['code'],
                            'title' => $total['title'],
                            'value' => $tax,
                            'sort_order' => $total['sort_order'],
                            'actual_value' => NULL,
                        ];
                    } elseif ($total['code'] == 'transaction_fee') {
                        $total_data[] = [
                            'code' => $total['code'],
                            'title' => $total['title'],
                            'value' => $total['value'],
                            'sort_order' => $total['sort_order'],
                            'actual_value' => NULL,
                        ];
                    } elseif ($total['code'] == 'total') {
                        $total_data[] = [
                            'code' => $total['code'],
                            'title' => $total['title'],
                            'value' => $new_total,
                            'sort_order' => $total['sort_order'],
                            'actual_value' => NULL,
                        ];
                    } else {
                        $total_data[] = [
                            'code' => $total['code'],
                            'title' => $total['title'],
                            'value' => $total['value'],
                            'sort_order' => $total['sort_order'],
                            'actual_value' => NULL,
                        ];
                    }
                }

                usort($total_data, function ($a, $b) {
                    return $a['sort_order'] <=> $b['sort_order'];
                });

                $transaction_details['customer_id'] = $order_info['customer_id'];
                $transaction_details['no_of_products'] = $i;
                $transaction_details['total'] = $order_info['total'];

                $new_order_id = $this->model_sale_order->CreateOrder($order_info);
                $new_order_info = $this->model_sale_order->getOrder($new_order_id);
                $customer_info = $this->model_account_customer->getCustomer($new_order_info['customer_id']);
                $new_product_id = $this->model_sale_order->InsertProductsByOrderId($data['products'], $new_order_id);
                $new_total_id = $this->model_sale_order->InsertOrderTotals($total_data, $new_order_id);
                $new_transaction_id = $this->model_sale_order->InsertOrderTransactionDetails($transaction_details, $new_order_id);
                $new_order_history_id = $this->model_checkout_order->addOrderHistory($order_id, $new_order_info['order_status_id'], 'Missing Products Order', false, $customer_info['firstname'] . ' ' . $customer_info['lastname'], 'customer', NULL, '');

                if (strlen($new_order_info['shipping_name']) <> 0) {
                    $address = $new_order_info['shipping_name'] . '<br />' . $new_order_info['shipping_address'] . '<br /><b>Contact No.:</b> ' . $new_order_info['shipping_contact_no'];
                } else {
                    $address = '';
                }

                $payment_address = '';
                $special = NULL;
                $order_href = $new_order_info['store_url'] . 'index.php?path=account/order/info&order_id=' . $new_order_info['order_id'];
                $order_pdf_href = '';
                $invoice_no = NULL;

                $email_data = array(
                    'template_id' => 'order_' . (int) $new_order_info['order_status_id'],
                    'order_info' => $new_order_info,
                    'address' => $address,
                    'payment_address' => $payment_address,
                    'special' => $special,
                    'order_href' => $order_href,
                    'order_pdf_href' => $order_pdf_href,
                    'order_status' => $new_order_info['order_status_id'],
                    'totals' => $totals,
                    'tax_amount' => $tax,
                    'order_id' => $new_order_id,
                    'invoice_no' => !empty($invoice_no) ? $invoice_no : '',
                    'order_products_list' => $this->load->controller('emailtemplate/emailtemplate/getOrderProductListTemplate', $new_order_info)
                );

                $subject = $this->emailtemplate->getSubject('OrderAll', 'order_' . (int) $new_order_info['order_status_id'], $email_data);
                $message = $this->emailtemplate->getMessage('OrderAll', 'order_' . (int) $new_order_info['order_status_id'], $email_data);
                $sms_message = $this->emailtemplate->getSmsMessage('OrderAll', 'order_' . (int) $new_order_info['order_status_id'], $email_data);

                try {
                    if ($customer_info['email_notification'] == 1 && $this->emailtemplate->getEmailEnabled('OrderAll', 'order_' . (int) $new_order_info['order_status_id'])) {
                        $mail = new mail($this->config->get('config_mail'));
                        $mail->setTo($new_order_info['email']);
                        $mail->setFrom($this->config->get('config_from_email'));
                        $mail->setSender($new_order_info['store_name']);
                        $mail->setSubject($subject);
                        $mail->setHtml($message);
                        $mail->send();
                        $log->write('mail end');
                    }
                } catch (Exception $e) {
                    
                }
            }
            $log->write($order_info);
            $log->write($data);
            $log->write($total_data);
        }
    }

}
