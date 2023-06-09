<?php 

define('REQUIRED_FILE',DIR_SYSTEM.'vendor/ses/aws-autoloader.php'); 
require_once(DIR_SYSTEM . 'vendor/ses/SimpleEmailServiceMessage.php');                                               
require_once(DIR_SYSTEM . 'vendor/ses/SimpleEmailService.php');                                               
require_once(DIR_SYSTEM . 'vendor/ses/SimpleEmailServiceRequest.php');


require_once(DIR_SYSTEM . 'vendor/mailgun/vendor/autoload.php'); 


require REQUIRED_FILE;

use Aws\Ses\SesClient;
use Mailgun\Mailgun;

class mail{
protected $to;
    protected $cc;
    protected $bcc;
    protected $from;
    protected $return_path;
    protected $read_receipt_to;
    protected $sender;
    protected $reply_to;
    protected $subject;
    protected $text;
    protected $html;
    protected $charset = null;
    protected $attachments = array();
    protected $attachments_inline = array();
    protected $priority;
    public $config_mail_protocol = 'phpmail';
    public $config_mail_sendmail_path = '/usr/sbin/sendmail -bs';
    public $config_mail_smtp_hostname;
    public $config_mail_smtp_username;
    public $config_mail_smtp_password;
    public $config_mail_smtp_port = 25;
    public $config_mail_smtp_encryption = 'none';
    // Old variables, keeping for B/C
    public $protocol = 'phpmail';
    public $parameter = '';
    public $sendmail_path = '/usr/sbin/sendmail -bs';
    public $smtp_hostname;
    public $smtp_username;
    public $smtp_password;
    public $smtp_port = 25;
    public $smtp_timeout = 0;
    public $smtp_encryption = 'none'; 

    public $aws_id = '';
    public $aws_region = '';
    public $aws_secret = '';   



    public function __construct($config = array()) {

        if (!class_exists('aws-autoloader.php')) {
            require_once(DIR_SYSTEM . 'vendor/ses/aws-autoloader.php');
        }
        //$log = new Log('error.log');

        //$log->write('in mail constant'.$config."$$$$$");
        //$log->write($config);
        
        foreach ($config as $key => $value) {
            if (!strpos($key, 'config_mail_')) {
                $this->set('config_mail_' . $key, $value);
            } else {
                $this->set($key, $value);
            }
        }
    }

    public function get($name) {
        return $this->$name;
    }

    public function set($name, $value, $decode = true, $is_array = false) {
        //$log = new Log('error.log');
        if ($is_array == true) {
            $array = $this->$name;
            $array[] = $value;

            $this->$name = $array;
        } else {
            if ($decode == true) {
                $this->$name = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            } else {
                $this->$name = $value;
            }
        }
        //$log->write($this->$name);
    }

    public function setTo($to, $decode = true) {
        $this->set('to', $to, $decode);
    }

    public function setCc($cc, $decode = true) {
        $this->set('cc', $cc, $decode);
    }

    public function setBcc($bcc, $decode = true) {
        $this->set('bcc', $bcc, $decode);
    }

    public function setFrom($from, $decode = true) {
        $this->set('from', $from, $decode);
    }

    public function setReturnPath($return_path, $decode = true) {
        $this->set('return_path', $return_path, $decode);
    }

    public function setReadReceiptTo($read_receipt_to, $decode = true) {
        $this->set('read_receipt_to', $read_receipt_to, $decode);
    }

    public function setSender($sender, $decode = true) {
        $this->set('sender', $sender, $decode);
    }

    public function setReplyTo($reply_to, $decode = true) {
        $this->set('reply_to', $reply_to, $decode);
    }

    public function setSubject($subject, $decode = true) {
        $this->set('subject', $subject, $decode);
    }

    public function setText($text, $decode = true) {
        $this->set('text', $text, $decode);
    }

    public function setHtml($html, $decode = true) {
        $this->set('html', $html, $decode);
    }

    public function setCharset($charset, $decode = false) {
        $this->set('charset', $charset, $decode);
    }

    public function addAttachment($filename, $decode = false) {
        $this->set('attachments', $filename, $decode, true);
    }

    public function addAttachmentInline($filename, $decode = false) {
        $this->set('attachments_inline', $filename, $decode, true);
    }

    public function setPriority($priority, $decode = false) {
        $this->set('priority', $priority, $decode);
    }

    public function send() {
        // Check First
        if (!$this->to) {
            trigger_error('Error: E-Mail to required!');
            exit();
        }

        if (!$this->from) {
            trigger_error('Error: E-Mail from required!');
            exit();
        }

        if (!$this->sender) {
            trigger_error('Error: E-Mail sender required!');
            exit();
        }

        if (!$this->subject) {
            trigger_error('Error: E-Mail subject required!');
            exit();
        }

        if ((!$this->text) && (!$this->html)) {
            trigger_error('Error: E-Mail message required!');
            exit();
        }

        if($this->get('config_mail_protocol') == 'mailgun') {

            $mailgun_key = $this->get('config_mail_mailgun');
            $mailgun_domain = $this->get('config_mail_mailgun_domain');

            # First, instantiate the SDK with your API credentials
            
            $mg = Mailgun::create($mailgun_key);

            $mg->messages()->send($mailgun_domain, [
              'from'    => $this->from,
              'to'      => $this->to,
              'subject' => $this->subject,
              'html'    => $this->html
            ]);

            return true;

        } elseif ($this->get('config_mail_protocol') == 'aws') {
           
            $aws_id = $this->get('config_mail_aws_access_id');
            $aws_secret  = $this->get('config_mail_aws_secret_key');
            $aws_region  = $this->get('config_mail_aws_region');

            $id = "AWS_ACCESS_KEY_ID=".$aws_id;
            $secret = "AWS_SECRET_ACCESS_KEY=".$aws_secret;
            putenv($id);
            putenv($secret);

            $client = SesClient::factory(array(
                'version'=> 'latest',     
                'region' => $aws_region
            ));

            $request = array();
            $request['Source'] = $this->from;
            $request['Destination']['ToAddresses'] = array($this->to);
            $request['Message']['Subject']['Data'] = $this->subject;
            $request['Message']['Body']['Html']['Data'] = $this->html;

            if($this->reply_to) {
                $request['ReplyToAddresses'] = array($this->reply_to);
            } else  {
                //$this->setReplyTo(array($this->from => $this->sender), false);
            }
            
            //echo "<pre>";print_r('fdbf');die;

            try {
                 $result = $client->sendEmail($request);
                 $messageId = $result->get('MessageId');

                 //echo "<pre>";print_r($result);die;
                 //echo("Email sent! Message ID: $messageId"."\n");
                 /*$log = new Log('error.log');
    
                $log->write('in fergreht $$$$$');
                $log->write($result);
                $log->write($request);
                $log->write($this->reply_to);*/
                return $result;

            } catch (Exception $e) {
                /*echo("The email was not sent. Error message: ");
                echo($e->getMessage()."\n");

                echo "false";*/
                //echo "<pre>";print_r($e->getMessage());die;
                return false;
            }

        } elseif ($this->get('config_mail_protocol') == 'phpmail') {
           
        } elseif ($this->get('config_mail_protocol') == 'sendmail') {
           
        } elseif ($this->get('config_mail_protocol') == 'smtp') {
           
        }
    }
}


?>