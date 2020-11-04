<?php 
require_once DIR_SYSTEM.'/vendor/autoload.php'; // Loads the library
require DIR_SYSTEM. '/vendor/twilio-php-master/Twilio/autoload.php';
use Twilio\Rest\Client;

class Sms {

    
    public function __construct($registry) {
        
        $this->config = $registry->get( 'config' );
    }

    public function sendmessage($to,$message){


    	
        $sender_id = $this->config->get('config_sms_sender_id');
        $username  = $this->config->get('config_sms_username');
        $password  = $this->config->get('config_sms_password');

       	/*$url= 'http://login.smsgatewayhub.com/smsapi/pushsms.aspx?user='.$username.'&pwd='.$password.'&to='.$to.'&sid='.$sender_id.'&msg='.urlencode($message).'&fl=0&gwid=2'; 
        
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ));
        //Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        print_r($resp);die;

        return $resp;*/

        $sid = $this->config->get('config_sms_sender_id');
        $token  = $this->config->get('config_sms_token');
        $from  = $this->config->get('config_sms_number');
        $log = new Log('error.log');
        // Your Account Sid and Auth Token from twilio.com/user/account
        //$sid = "AC75111c89124c19fffb2538524b8701ae";
        //$token = "e4231d69832c9c7c65ecc78512d9ec1c";

        //$sid = "ACe596b1c5068a7076d1a05552a66503f3";
        //$token = "a15911012556c6795359cba517bb7328";

        $log->write("sms 2");
        $log->write($to);
        if(substr($to,0,1) != '+') {
            $to = '+'.$to;
        }
        //$log->write($to);
        $client = new Client($sid, $token);
        
        try {
            $sms = $client->messages->create(
            $to,
                array(
                    'from' => $from,
                    //'from' => '+19789864215',
                    'body' => $message
                )
            );
        } catch (Exception $exception) {
            $log->write($exception);           
            return false;
        } 
        return true;
    }


}