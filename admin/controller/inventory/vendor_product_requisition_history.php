<?php

class ControllerInventoryVendorProductRequisitionHistory  extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('catalog/product');
        $this->document->setTitle($this->language->get('heading_title')); 
        $this->InventoryRequisitionHistory();
    }
    
 
     
    public function InventoryRequisitionHistory() {


        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_page_title'));

        // $this->load->model('inventory/vendor_product_requisiton');

      
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_store_id'])) {
            $filter_store_id = $this->request->get['filter_store_id'];
        } else {
            $filter_store_id = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'product_name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id=' . urlencode(html_entity_decode($this->request->get['filter_store_id'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('inventory/vendor_product_requisition_history', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['history'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_store_id' => $filter_store_id,
            'filter_model' => $filter_model,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $this->load->model('inventory/vendor_product_requisition');

        $history_total = $this->model_inventory_vendor_product_requisition->getTotalHistory($filter_data);

        $results = $this->model_inventory_vendor_product_requisition->getHistory($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {

            $data['history'][] = [
                'requisition_id' => $result['requisition_id'],
                'requested_by' => $result['requested_by'],
                'date' => $result['date'],
                'voucher' => $this->url->link('inventory/vendor_product_requisition_history/inventoryrequisitionvoucher', 'token=' . $this->session->data['token'] . '&requisition_id=' . $result['requisition_id'] . $url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_page_title');

        $data['text_list'] = $this->language->get('text_page_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_product_id'] = $this->language->get('column_product_id');
        $data['column_product_store_id'] = $this->language->get('column_product_store_id');
        $data['column_procured_qty'] = $this->language->get('column_procured_qty');
        $data['column_rejected_qty'] = $this->language->get('column_rejected_qty');
        $data['column_prev_quantity'] = $this->language->get('column_prev_quantity');
        $data['column_updated_quantity'] = $this->language->get('column_updated_quantity');
        $data['column_updation_date'] = $this->language->get('column_updation_date');
        $data['column_updated_by'] = $this->language->get('column_updated_by');
        $data['column_added_user_role'] = $this->language->get('column_added_user_role');
        $data['column_date_added'] = $this->language->get('column_date_added');

        $data['entry_name'] = $this->language->get('entry_product_name');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_added_end'] = 'To Date Added';
        $data['entry_store_name'] = $this->language->get('entry_store_name');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_company'])) {
            $url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('catalog/vendor_product/InventoryHistory', 'token=' . $this->session->data['token'] . '&sort=product_name' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('catalog/vendor_product/InventoryHistory', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $url .= '&filter_date_added_end=' . $this->request->get['filter_date_added_end'];
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $history_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/vendor_product/InventoryHistory', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($history_total - $this->config->get('config_limit_admin'))) ? $history_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $history_total, ceil($history_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_added_end'] = $filter_date_added_end;
        $data['filter_store_id'] = $filter_store_id;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('inventory/vendor_product_requisition_history.tpl', $data));
    }

   
    public function InventoryRequisitionHistoryexcel() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_store_id'])) {
            $filter_store_id = $this->request->get['filter_store_id'];
        } else {
            $filter_store_id = null;
        }

        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_date_added_end'])) {
            $filter_date_added_end = $this->request->get['filter_date_added_end'];
        } else {
            $filter_date_added_end = null;
        }

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_store_id' => $filter_store_id,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
        ];

        // echo "<pre>";print_r($filter_data);die;

        $data = [];
        $this->load->model('report/excel');

        $this->model_report_excel->download_inventoryhistoryexcel($data, $filter_data);
    }

    public function getUserByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];
        }
    }
 
    public function inventoryrequisitionvoucher() {
        $inventory_requisition_id = $this->request->get['requisition_id'];
        $this->load->model('inventory/vendor_product_requisition');

        $filter_data = [
            'requisition_id' => $this->request->get['requisition_id']
        ];
        
        $results = $this->model_inventory_vendor_product_requisition->getHistorybyID($filter_data);
        $log = new Log('error.log');
        $full_details = $results;
        
        try {
            require_once DIR_ROOT . '/vendor/autoload.php';
            $pdf = new \mikehaertl\wkhtmlto\Pdf;
            $template = $this->load->view('inventory/inventory_requisition_voucher.tpl', $full_details);
            $pdf->addPage($template);
            if (!$pdf->send("Inventory Requisition Voucher #" . $inventory_requisition_id . ".pdf")) {
                $error = $pdf->getError();
                echo $error;
                die;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
