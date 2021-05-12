<?php

class ControllerCommonHeader extends Controller
{
    public function index()
    {
        $data['title'] = $this->document->getTitle();

        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }

        $this->load->model('tool/image');

        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['links'] = $this->document->getLinks();
        $data['styles'] = $this->document->getStyles();
        $data['scripts'] = $this->document->getScripts();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        if (is_file(DIR_IMAGE.$this->config->get('config_icon'))) {
            //$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
            $data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 30, 30);
        } else {
            $data['icon'] = '';
        }

        $this->load->language('common/header');

        $data = $this->language->all($data, ['text_logged']);
        // leaving the followings for extension B/C purpose
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new'] = $this->language->get('text_new');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
        $data['text_logout'] = $this->language->get('text_logout');

        if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
            $data['logged'] = '';

            $data['home'] = $this->url->link('common/dashboard', '', 'SSL');
        } else {
            $data['logged'] = true;

            $data['home'] = $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL');
            $data['setting'] = $this->url->link('setting/setting', 'token='.$this->session->data['token'], 'SSL');

            $data['accountsetting'] = $this->url->link('account/settings', 'token='.$this->session->data['token'], 'SSL');

            $data['logout'] = $this->url->link('common/logout', 'token='.$this->session->data['token'], 'SSL');
            $data['farmer_logout'] = $this->url->link('common/logout/farmer', 'token='.$this->session->data['token'], 'SSL');

            $data['preturn_update'] = $this->user->hasPermission('access', 'common/update');
            $data['update'] = $this->url->link('common/update', 'token='.$this->session->data['token'], 'SSL');

            // News Added Menu
            $data['new_category'] = $this->url->link('catalog/category/add', 'token='.$this->session->data['token'], 'SSL');
            $data['new_customer'] = $this->url->link('sale/customer/add', 'token='.$this->session->data['token'], 'SSL');
            $data['new_product'] = $this->url->link('catalog/general/add', 'token='.$this->session->data['token'], 'SSL');
            $data['new_store'] = $this->url->link('setting/store/add', 'token='.$this->session->data['token'], 'SSL');

            //Products
            $this->load->model('catalog/vendor_product');

            $product_total = $this->model_catalog_vendor_product->getTotalProducts(['filter_quantity' => 0]);

            $data['product_total'] = $product_total;

            $data['product'] = $this->url->link('catalog/vendor_product', 'token='.$this->session->data['token'].'&filter_quantity=0', 'SSL');

            $product_low_total = $this->model_catalog_vendor_product->getTotalProducts(['filter_quantity' => 10]);

            $data['product_low_total'] = $product_low_total;

            $data['low_stock'] = $this->url->link('catalog/vendor_product', 'token='.$this->session->data['token'].'&filter_quantity=10', 'SSL');

            // Orders
            $this->load->model('sale/order');

            if ($this->user->isVendor()) {
                // Processing Orders
                $data['order_status_total'] = $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $this->config->get('config_processing_status'))]);
                $data['order_status'] = $this->url->link('sale/order', 'token='.$this->session->data['token'].'&filter_order_status='.implode(',', $this->config->get('config_processing_status')), 'SSL');

                // Complete Orders
                $data['complete_status_total'] = $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $this->config->get('config_complete_status'))]);
                $data['complete_status'] = $this->url->link('sale/order', 'token='.$this->session->data['token'].'&filter_order_status='.implode(',', $this->config->get('config_complete_status')), 'SSL');

                $data['alert_order'] = $data['order_status_total'] + $data['complete_status_total'];

                $data['alerts'] = $product_total;

                // Returns
                $this->load->model('sale/return');

                $return_total = $this->model_sale_return->getVendorTotalReturns($filter_data = []);

                //$return_total = count($results);

                $data['return_total'] = $return_total;

                $data['return'] = $this->url->link('sale/return', 'token='.$this->session->data['token'], 'SSL');
            } else {
                // Customers
                $this->load->model('report/customer');

                $data['online_total'] = $this->model_report_customer->getTotalCustomersOnline();

                $data['online'] = $this->url->link('report/customer_online', 'token='.$this->session->data['token'], 'SSL');

                $this->load->model('sale/customer');

                $filter_cust_data = [                    
                    'filter_approved' => 0,                   
                    'filter_parent_customer' => $filter_parent_customer,
                    'filter_parent_customer_id' => $filter_parent_customer_id,
                     ];

                $customer_total = $this->model_sale_customer->getTotalCustomers($filter_cust_data);

                $data['customer_total'] = $customer_total;
                $data['customer_approval'] = $this->url->link('sale/customer', 'token='.$this->session->data['token'].'&filter_approved=0&sort=c.date_added&order=DESC', 'SSL');

                // Processing Orders
                $data['order_status_total'] = $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $this->config->get('config_processing_status'))]);
                $data['order_status'] = $this->url->link('sale/order', 'token='.$this->session->data['token'].'&filter_order_status='.implode(',', $this->config->get('config_processing_status')), 'SSL');

                // Complete Orders
                $data['complete_status_total'] = $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $this->config->get('config_complete_status'))]);
                $data['complete_status'] = $this->url->link('sale/order', 'token='.$this->session->data['token'].'&filter_order_status='.implode(',', $this->config->get('config_complete_status')), 'SSL');

                // Returns
                $this->load->model('sale/return');

                $return_total = $this->model_sale_return->getTotalReturns(['filter_return_status_id' => $this->config->get('config_return_status_id')]);

                $data['return_total'] = $return_total;

                $data['return'] = $this->url->link('sale/return', 'token='.$this->session->data['token'], 'SSL');

                $data['alert_order'] = $return_total + $data['order_status_total'] + $data['complete_status_total'];

                $data['alerts'] = $customer_total + $product_total + $return_total;

                $data['alert_customer'] = $customer_total;
            }

            // Online Stores
            $data['stores'] = [];

            $data['stores'][] = [
                'name' => $this->config->get('config_name'),
                'href' => HTTP_CATALOG,
            ];

            $data['alert_product'] = $product_total;
            $data['alert_update'] = $this->update->countUpdates();

            $this->load->language('common/menu');

            $this->load->model('user/user');
            $this->load->model('user/farmer');

            $this->load->model('tool/image');
            
            $log = new Log('error.log');
            $user_info = NULL;
            if($this->user->getId() != NULL) {
            $user_info = $this->model_user_user->getUser($this->user->getId());
            }
            
            if($this->user->getFarmerId() != NULL) {
            $user_info = $this->model_user_farmer->getFarmer($this->user->getFarmerId());
            }

            if ($user_info) {
                $data['firstname'] = isset($user_info['firstname']) ? $user_info['firstname'] : $user_info['first_name'];
                $data['lastname'] = isset($user_info['lastname']) ? $user_info['lastname'] : $user_info['last_name'];
                $data['username'] = $user_info['username'];

                $data['user_group'] = $user_info['user_group'];

                if (is_file(DIR_IMAGE.$user_info['image'])) {
                    $data['image'] = $this->model_tool_image->resize($user_info['image'], 45, 45);
                } else {
                    $data['image'] = $this->model_tool_image->resize('no_image.png', 45, 45);
                }
            } else {
                $data['username'] = '';
                $data['image'] = '';
            }

            $this->load->model('setting/store');

            $data['url_user'] = $this->url->link('user/user/edit', 'user_id='.$this->user->getId().'&token='.$this->session->data['token'], 'SSL');

            $data['url_user'] = $this->url->link('user/user/edit', 'user_id='.$this->user->getId().'&token='.$this->session->data['token'], 'SSL');
        }

        $data['sitename'] = (strlen($this->config->get('config_name')) > 14) ? substr($this->config->get('config_name'), 0, 14).'...' : $this->config->get('config_name');

        if (isset($this->session->data['token'])) {
            $data['site_url'] = $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'); //HTTPS_CATALOG;
        } else {
            $data['site_url'] = '';
        }

        $data['search'] = $this->load->controller('search/search');

        return $this->load->view('common/header.tpl', $data);
    }
}
