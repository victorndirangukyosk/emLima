<?php

class ControllerApiApproval extends Controller
{
    public function getVendorApprovals($args = [])
    {
        $this->load->language('api/approvals');

        //echo "getVendorApprovals";

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/approval');

            if (isset($args['limit'])) {
                $limit = $args['limit'];
            } else {
                $limit = 10;
            }

            if (isset($args['start'])) {
                $start = $args['start'];
            } else {
                $start = 0;
            }

            if (isset($args['sort'])) {
                $sort = $args['sort'];
            } else {
                $sort = 'enquiry_id';
            }

            if (isset($args['order'])) {
                $order = $args['order'];
            } else {
                $order = 'DESC';
            }

            $data = [
                'sort' => $sort,
                'order' => $order,
                'start' => $start,
                'limit' => $limit,
            ];

            $total = $this->model_api_approval->getVendorApprovalTotal();

            $response['seller_approvals'] = [];

            $response['count'] = $total;
            $results = $this->model_api_approval->getAllVendorApproval($data);

            foreach ($results as $row) {
                //$row['view'] = $this->url->link('approvals/enquiries/view','enquiry_id='.$row['enquiry_id'].'&token='.$this->session->data['token']);
                //$row['approve'] = $this->url->link('approvals/enquiries/approve','enquiry_id='.$row['enquiry_id'].'&token='.$this->session->data['token']);
                $response['seller_approvals'][] = $row;
            }
            $json = $response;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addVendorApprove($args = [])
    {
        $this->language->load('api/approvals');

        $this->load->model('api/approval');

        $args['user_group_id'] = 11;

        //echo "<pre>";print_r($args);die;
        if (!isset($this->session->data['api_id']) || !isset($args['enquiry_id']) || !isset($args['commision']) || !isset($args['user_group_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $vendorData = $this->model_api_approval->getEnquiry($args['enquiry_id']);

            if (count($vendorData) > 0) {
                //echo "addVendorApprove";die;
                $args = $args + $vendorData;
                // echo "<pre>";print_r($vendorData);
                // echo "<pre>";print_r($args);die;
                if (isset($vendorData['email'])) {
                    //$vendorData = HTTPS_ADMIN ;

                    // 4 merchant mail and 5 admin mail

                    $subject = $this->emailtemplate->getSubject('Contact', 'contact_4', $vendorData);
                    $message = $this->emailtemplate->getMessage('Contact', 'contact_4', $vendorData);
                    //mishramanjari15@gmail.com
                    $mail = new mail($this->config->get('config_mail'));
                    $mail->setTo($vendorData['email']);
                    $mail->setFrom($this->config->get('config_from_email'));
                    //$mail->setReplyTo($vendorData['email']);
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                    $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
                    $mail->send();

                    $subject = $this->emailtemplate->getSubject('Contact', 'contact_5', $vendorData);
                    $message = $this->emailtemplate->getMessage('Contact', 'contact_5', $vendorData);

                    $mail = new mail($this->config->get('config_mail'));
                    $mail->setTo($this->config->get('config_email'));
                    $mail->setFrom($this->config->get('config_from_email'));
                    //$mail->setReplyTo($this->request->post['email']);
                    $mail->setSender($this->config->get('config_name'));
                    $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                    $mail->setHtml(html_entity_decode(strip_tags($message), ENT_QUOTES, 'UTF-8'));
                    $mail->send();
                }

                $json['success'] = 'Success: Enquiry Moved To Vendor List Successfully!';

                $this->model_api_approval->moveVendorEndquiry($args);
            } else {
                $json['error'] = 'Error: Enquiry Not found';
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getVendorEnquiryView($args = [])
    {
        //echo "getVendorEnquiryView";
        if (!isset($this->session->data['api_id']) || !isset($args['enquiry_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->language->load('api/enquiries_view');
            $this->load->model('api/approval');

            $json = $this->model_api_approval->getEnquiry($args['enquiry_id']);

            if (count($json) > 0) {
                $json['heading_title'] = $this->language->get('heading_title');

                $json['column_password'] = $this->language->get('column_password');
                $json['column_username'] = $this->language->get('column_username');
                $json['column_email'] = $this->language->get('column_email');
                $json['column_date'] = $this->language->get('column_date');
                $json['column_business'] = $this->language->get('column_business');
                $json['column_type'] = $this->language->get('column_type');
                $json['column_tin_no'] = $this->language->get('column_tin_no');
                $json['column_mobile'] = $this->language->get('column_mobile');
                $json['column_telephone'] = $this->language->get('column_telephone');
                $json['column_city'] = $this->language->get('column_city');
                $json['column_address'] = $this->language->get('column_address');
                $json['column_name'] = $this->language->get('column_name');
                $json['column_store_nam'] = $this->language->get('column_store_nam');
                $json['column_about_us'] = $this->language->get('column_about_us');
                $json['column_store_name'] = $this->language->get('column_store_name');
            } else {
                $json['error'] = 'Not Found';
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteApproval($args = [])
    {
        //echo "getVendorEnquiryDelete";print_r($args); die;
        $this->language->load('api/approvals');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('api/approval');

        if (isset($args['id']) || isset($this->session->data['api_id'])) {
            $this->model_api_approval->vendorApprovalDelete($args['id']);

            $json['success'] = 'Successfully: Deleted Enquiry ';
        } else {
            $json['error'] = $this->language->get('error_permission');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
