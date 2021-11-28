<?php

class ControllerDeliversystemDeliversystem extends Controller
{
    private $error = [];

    public function getToken($data)
    {
        $response['status'] = false;

        if (isset($data['email']) && isset($data['password'])) {
            $email = $data['email'];

            $password = $data['password'];

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link').'/api/authenticate');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, ['email' => $email, 'password' => $password]);
            $result = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($result);
            if (!isset($result->error)) {
                $response['status'] = true;
                $response['token'] = $result->token;
            }
        }

        return $response;
    }

    public function createDelivery($data)
    {
        $response['status'] = false;
        //print_r("createDelivery");

        if (isset($data['tokens']) && isset($data['body'])) {
            $token = $data['tokens'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link').'/api/create_delivery');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer '.$token, 'Accept: application/json']);
            $result = curl_exec($curl);

            $log = new Log('error.log');

            $log->write('createDelivery log');
            $log->write($result);
//            echo "<pre>";print_r($result);die;
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

    public function editDelivery($data)
    {
        $log = new Log('error.log');

        $log->write('editDelivery DS');

        $log->write($data);

        $response['status'] = false;

        if (isset($data['tokens']) && isset($data['body'])) {
            $log->write('editDelivery DS if');

            $token = $data['tokens'];
            $body = $data['body'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->config->get('config_shopper_link').'/api/edit_delivery');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //Setting post data as xml
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer '.$token, 'Accept: application/json']);
            $result = curl_exec($curl);

            $log->write('editDelivery log');
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

    public function getDeliveryStatus($data)
    {
        $response['status'] = false;
        //print_r("getDeliveryStatus");

        if (isset($data['tokens']) && isset($data['delivery_id'])) {
            $cSession = curl_init();
            $token = $data['tokens'];
            $delivery_id = $data['delivery_id'];
            //step2
            //curl_setopt($cSession,CURLOPT_URL,“http://deliveryapp.dev/api/deliveries?token=“.$_SESSION[‘token’]);
            curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link').'/api/deliveries/'.$delivery_id);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer '.$token, 'Accept: application/json']);
            //step3
            $result = curl_exec($cSession);
            //echo "<pre>";
            //print_r($result);
            //error case : {"message":"Delivery not found."}

            //step4
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

    public function getDeliveries($data)
    {
        $cSession = curl_init();

        $token = '';
        //step2
        //curl_setopt($cSession,CURLOPT_URL,“http://deliveryapp.dev/api/deliveries?token=“.$_SESSION[‘token’]);
        curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link').'/api/deliveries');
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $result = curl_exec($cSession);
        //step4
        curl_close($cSession);
        $result = json_decode($result);

        return $result;
    }

    public function getProductStatus($data)
    {
        $response['status'] = false;
        //print_r("getProductStatuss");

        if (isset($data['tokens']) && isset($data['delivery_id'])) {
            $cSession = curl_init();
            $token = $data['tokens'];
            $delivery_id = $data['delivery_id'];

            curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link').'/api/products_status/'.$delivery_id);
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cSession, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer '.$token, 'Accept: application/json']);
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

    public function postRating($data)
    {
        $response['status'] = false;
        print_r('getDeliveryStatus');
        /*
            delivery_id
            ratings 1
        */
        if (isset($data['tokens']) && isset($data['delivery_id']) && isset($data['rating'])) {
            $cSession = curl_init();
            $token = $data['tokens'];
            $delivery_id = $data['delivery_id'];
            $ratings = $data['rating'];

            curl_setopt($cSession, CURLOPT_URL, $this->config->get('config_shopper_link').'/api/ratings');
            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, ['delivery_id' => $delivery_id, 'ratings' => $ratings]);

            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer '.$token, 'Accept: application/json']);
            //step3
            $result = curl_exec($cSession);
            //step4
            curl_close($cSession);
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

    public function getAllDeliveryStatus($data)
    {
        $response['status'] = false;
        //print_r("getDeliveryStatus");

        if (isset($data['token'])) {
            $cSession = curl_init();
            $token = $data['token'];

            $url = $this->config->get('config_shopper_link').'/api/status_codes';

            curl_setopt($cSession, CURLOPT_URL, $url);

            curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($cSession, CURLOPT_HTTPHEADER, ['AUTHORIZATION: Bearer '.$token, 'Accept: application/json']);

            //echo "ss";
            //step3
            $result = curl_exec($cSession);

            //echo "sss";
            curl_close($cSession);
            //$result = json_decode($result,true);

            $response['status'] = true;
            $response['data'] = $result;
        }

        return $response;
    }
}
