<?php

require_once DIR_SYSTEM.'/vendor/autoload.php'; // Loads the library
require DIR_SYSTEM.'/vendor/twilio-php-master/Twilio/autoload.php';
use Twilio\Rest\Client;

require DIR_SYSTEM.'/vendor/zenvia/human_gateway_client_api/HumanClientMain.php';

class ControllerToolFunctionalTesting extends Controller
{
    public function index()
    {
        $log = new Log('error.log');
        $log->write('sms twilio 2');
        $log->write($to);
        if (isset($this->request->post['to']) && isset($this->request->post['message'])) {
            $to = $this->request->post['to'];
            $message = $this->request->post['message'];

            $this->sendmessage($to, $message);

            return true;
        }

        return false;
    }

    public function sendmessage($to, $message)
    {
        $log = new Log('error.log');

        //zenvia,twilio

        if ('twilio' == $this->config->get('config_sms_protocol')) {
            $sid = $this->config->get('config_sms_sender_id');
            $token = $this->config->get('config_sms_token');
            $from = $this->config->get('config_sms_number');

            // Your Account Sid and Auth Token from twilio.com/user/account
            //$sid = "AC75111c89124c19fffb2538524b8701ae";
            //$token = "e4231d69832c9c7c65ecc78512d9ec1c";

            //$sid = "ACe596b1c5068a7076d1a05552a66503f3";
            //$token = "a15911012556c6795359cba517bb7328";

            $log->write('sms twilio 2');
            $log->write($to);
            if ('+' != substr($to, 0, 1)) {
                $to = '+'.$to;
            }
            //$log->write($to);
            $client = new Client($sid, $token);

            try {
                $sms = $client->messages->create(
                $to,
                    [
                        'from' => $from,
                        //'from' => '+19789864215',
                        'body' => $message,
                    ]
                );
            } catch (Exception $exception) {
                return false;
            }
        } else {
            $log->write('sms else  2');

            $msg_list = '919454740940; test0; 004'."\ N";

            $humanMultipleSend = new HumanMultipleSend('human.fake.hc', 'aBc123');

            $callBack = HumanMultipleSend::CALLBACK_FINAL_STATUS;

            $type = HumanMultipleSend::TYPE_C;

            $Responses = $humanMultipleSend->sendMultipleList($type, $msg_list, $callBack);
        }

        return true;
    }
}
