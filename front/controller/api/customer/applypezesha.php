<?php

class ControllerApiCustomerApplypezesha extends Controller {

    private $error = [];


    public function getInfo() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{

        $this->load->language('account/pezesha');
        $this->load->model('account/credit');
        $this->load->model('account/customer');
        if($this->customer->getId()!=null && $this->customer->getId()!="")
        {
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $data['national_id'] = $customer_info['national_id'];
        $data['kra'] = $customer_info['kra'];
        $data['dob'] = $customer_info['dob'] != NULL ? date('d/m/Y', strtotime($customer_info['dob'])) : NULL;
        $data['gender'] = $customer_info['gender'];
        $data['customer_id'] = $customer_info['customer_id'];
        $json['data'] = $data;
        $json['message'][] = ['type' => 'success', 'body' => 'Info fetched'];

        // echo "<pre>";print_r($json);die;
        }
        else{
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Please login again'];
            http_response_code(400);
        }

        }
        catch(exception $ex)
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => $ex->getMessage()];
            http_response_code(400);
        }
        finally{
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        }

    }


    public function addupdatecustomerinfo() {
        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{
            if($this->customer->getId()==null || $this->customer->getId()==null)
            {
                $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Please login again'];
            http_response_code(400);
            return;

            }
        $log = new Log('error.log');
        $log->write($this->request->post);
        $this->load->model('account/customer');
        $data['gender'] = $this->request->post['gender'];
        $data['kra'] = $this->request->post['kra'];
        $data['national_id'] = $this->request->post['national_id'];
        $data['customer_id'] =$this->customer->getId();
        $date = $this->request->post['dob'];
        if (isset($date) && $date != NULL) {
            $date = DateTime::createFromFormat('d/m/Y', $date);
            $this->request->post['dob'] = $date->format('Y-m-d');
        } else {
            $this->request->post['dob'] = null;
        }
        $data['dob'] = $this->request->post['dob'];

        if($data['gender']==null ||$data['kra']==null || $data['national_id']==null  )
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Gender ,KRA,National_ID,DoB are mandatory.Please check. '];
            http_response_code(400);
            return;
        }


     $this->model_account_customer->updatecustomerinfo($this->customer->getId(), $data);
     $json['message'][] = ['type' => 'success', 'body' => 'Updated'];
    //  $data['asd']=$this->addpezesha();
     $json['data'] = $data;

        }
        catch(exception $ex)
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => $ex->getMessage()];
            http_response_code(400);
        }
        finally{
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        }
    }

    public function addpezesha() {
        $json = [];$userregistration=[];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        try{
            if($this->customer->getId()==null || $this->customer->getId()==null)
            {
                $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => 'Please login again'];
            http_response_code(400);
            return;

            }
        $log = new Log('error.log');
        $log->write($this->request->post);
        $val=1;

        $userregistration = $this->load->controller('account/applypezesha/userregistration');

                // echo "<pre>";print_r(json_encode($userregistration));die;

        $data['accrptterms'] = $this->load->controller('account/applypezesha/accrptterms',1);
        $data['dataingestion'] = $this->load->controller('account/applypezesha/dataingestion',1);

    //    $json['message'][] = ['type' => 'success', 'body' => 'Updated'];
        $json['data'] = $data;

        }
        catch(exception $ex)
        {
            $json['status'] = 10013;
            $json['message'][] = ['type' => 'error', 'body' => $ex->getMessage()];
            http_response_code(400);
        }
        finally{
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        }
    }

    public function SendDocuments() {
        $log = new Log('error.log');
        $this->load->model('account/customer');
        $documents = $this->model_account_customer->getCustomerDocuments($this->customer->getId());
        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        if ($documents != NULL && count($documents) > 0) {

            $customer_pezehsa['firstname'] = $customer_info['firstname'];
            $customer_pezehsa['lastname'] = $customer_info['lastname'];
            $customer_pezehsa['companyname'] = $customer_info['company_name'];
            $customer_pezehsa['companyname'] = $customer_info['company_name'];
            $customer_pezehsa['pezesha_documents'] = $this->getPezeshaDocumentsTemplate();

            $log->write('EMAIL SENDING');
            $log->write($customer_pezehsa);
            $log->write('EMAIL SENDING');

            $subject = $this->emailtemplate->getSubject('Customer', 'customer_97', $customer_pezehsa);
            $message = $this->emailtemplate->getMessage('Customer', 'customer_97', $customer_pezehsa);
            try {
                $mail = new Mail($this->config->get('config_mail'));
                $mail->setTo('documents.kwikbasket@yopmail.com');
                $mail->setFrom($this->config->get('config_from_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setHTML($message);
                $mail->send();
            } catch (Exception $e) {

            }
        }
    }

    public function getPezeshaDocumentsTemplate() {
        $log = new Log('error.log');

        $this->load->model('account/customer');
        $customer_documents = $this->model_account_customer->getCustomerDocuments($this->customer->getId());

        $html = '';
        $html .= '<table class="table table-bordered" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;border-collapse: collapse!important;border-spacing: 0;background-color: transparent;width: 100%;max-width: 100%;margin-bottom: 20px;border: 1px solid #ddd;">';
        $html .= '<thead class="thead-bg" style="background: #EC7122;color: #fff;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;display: table-header-group;">'
                . '<tr style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;page-break-inside: avoid;">'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">S.NO</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">DOCUMENT</th>'
                . '<th scope="col" style="background-color: #EC7122 !important;color: #000;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: bottom;border-top: 1px solid #ddd;border-bottom: 2px solid #ddd;border: 1px solid #ddd!important;border-bottom-width: 2px;">ACTION</th>'
                . '</tr>'
                . '</thead>';
        $html .= '<tbody style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;">';
        $count = 1;
        foreach ($customer_documents as $customer_document) {
            $html .= '<tr style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;page-break-inside: avoid;">
            <th scope="row" style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;text-align: left;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $count . '</th>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;">' . $customer_document['name'] . '</td>
            <td style="-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;border: 1px solid #ddd!important;background-color: #fff!important;"><a href="'.$customer_document['path'].'">View Document</a></td>
        </tr>';
            $count++;
        }
        $html .= '</tbody></table>';
        return $html;
    }

}
