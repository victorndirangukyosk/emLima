<?php

class ControllerInventoryInventoryWastage extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('inventory/wastage');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('inventory/inventory_wastage');

        $this->getList();
    }



    protected function getList() {

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_vendor_name'])) {
            $filter_vendor_name = $this->request->get['filter_vendor_name'];
        } else {
            $filter_vendor_name = null;
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $filter_product_id_from = $this->request->get['filter_product_id_from'];
        } else {
            $filter_product_id_from = null;
        }

        if (isset($this->request->get['filter_product_id_to'])) {
            $filter_product_id_to = $this->request->get['filter_product_id_to'];
        } else {
            $filter_product_id_to = null;
        }

        if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
        } else {
            $filter_category = null;
        }


        if (isset($this->request->get['filter_store_id'])) {
            $filter_store_id = $this->request->get['filter_store_id'];
        } else {
            $filter_store_id = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }


        if (isset($this->request->get['filter_date_added'])) {
            $filter_date_added = $this->request->get['filter_date_added'];
        } else {
            $filter_date_added = null;
        }

        if (isset($this->request->get['filter_group_by_date'])) {
            $filter_group_by_date = $this->request->get['filter_group_by_date'];
        } else {
            $filter_group_by_date = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
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

        if (isset($this->request->get['filter_vendor_name'])) {
            $url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_group_by_date'])) {
            $url .= '&filter_group_by_date=' . urlencode(html_entity_decode($this->request->get['filter_group_by_date'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_product_id_to'])) {
            $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
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

        //echo $prices;exit;
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title') ,
            'href' => $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('inventory/inventory_wastage/add', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['products'] = [];
        $this->load->model('inventory/inventory_wastage');


        $filter_data = [
            'filter_name' => $filter_name,
            'filter_vendor_name' => $this->getUserByName($filter_vendor_name),
            'filter_product_id_from' => $filter_product_id_from,
            'filter_product_id_to' => $filter_product_id_to,
            'filter_category' => $filter_category,
            'filter_store_id' => $filter_store_id,
            'filter_status' => $filter_status,
            'filter_group_by_date' => $filter_group_by_date,
            'filter_date_added' => $filter_date_added,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $this->load->model('tool/image');

        $product_total = $this->model_inventory_inventory_wastage->getTotalProducts($filter_data);

        $results = $this->model_inventory_inventory_wastage->getProducts($filter_data);
        // echo '<pre>';print_r($results);die;

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(0);

        foreach ($results as $result) {


                if (is_file(DIR_IMAGE . $result['image'])) {
                    $image = $this->model_tool_image->resize($result['image'], 40, 40);
                    $bigimage = $this->model_tool_image->getImage($result['image']);
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                    $bigimage = $this->model_tool_image->getImage('no_image.png');
                }

                $data['products'][] = [

                    'product_store_id' => $result['product_store_id'],
                    'product_id' => $result['product_id'],
                    'wastage_qty' => $result['wastage_qty'],
                    'image' => $image,
                    'bigimage' => $bigimage,
                    'name' => $result['name'],//product_name
                    'unit' => $result['unit'],
                    'added_by_user' => $result['added_by_user'],
                    'cumulative_wastage' => $result['cumulative_wastage'],
                    'date_added' => $result['date_added'],

                ];
            }

        if ($this->user->isVendor()) {
            $data['is_vendor'] = 1;
        } else {
            $data['is_vendor'] = 0;
        }

        $data['heading_title'] =  $this->language->get('heading_title') ;

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_store_name'] = $this->language->get('column_store_name');

        $data['column_unit'] = $this->language->get('column_unit');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_category'] = $this->language->get('column_category');
        $data['column_product_id'] = $this->language->get('column_product_id');
        $data['column_vproduct_id'] = $this->language->get('column_vproduct_id');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_store_name'] = $this->language->get('entry_store_name');
        $data['entry_vendor_name'] = $this->language->get('entry_vendor_name');
        $data['entry_product_id_from'] = $this->language->get('entry_product_id_from');
        $data['entry_product_id_to'] = $this->language->get('entry_product_id_to');

        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor_name'])) {
            $url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
        }



        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_product_id_to'])) {
            $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_group_by_date'])) {
            $url .= '&filter_group_by_date=' . urlencode(html_entity_decode($this->request->get['filter_group_by_date'], ENT_QUOTES, 'UTF-8'));
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


            $data['sort_name'] = $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
            $data['sort_store'] = $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . '&sort=st.name' . $url, 'SSL');
            $data['sort_product_id'] = $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . '&sort=p.product_id' . $url, 'SSL');
            $data['sort_vproduct_id'] = $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . '&sort=ps.product_store_id' . $url, 'SSL');
            $data['sort_category'] = $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . '&sort=p2c.category' . $url, 'SSL');
            $data['sort_status'] = $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
            $data['sort_order'] = $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');


        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor_name'])) {
            $url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
        }



        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }



        if (isset($this->request->get['filter_product_id_to'])) {
            $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_store_id'])) {
            $url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . urlencode(html_entity_decode($this->request->get['filter_category'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_group_by_date'])) {
            $url .= '&filter_group_by_date=' . urlencode(html_entity_decode($this->request->get['filter_group_by_date'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');


            $pagination->url = $this->url->link('inventory/inventory_wastage', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');


        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_vendor_name'] = $filter_vendor_name;
        $data['filter_product_id_from'] = $filter_product_id_from;
        $data['filter_product_id_to'] = $filter_product_id_to;
        $data['filter_category'] = $filter_category;
        $data['filter_store_id'] = $filter_store_id;
        $data['filter_status'] = $filter_status;
        $data['filter_group_by_date'] = $filter_group_by_date;
        $data['filter_date_added'] = $filter_date_added;


        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');

        //echo "<pre>";print_r($data['heading_title'] );die;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->load->model('sale/customer_group');
        /* PREVIOUS CODE */

        //echo '<pre>';print_r($cachePrice_data);exit;
            $this->response->setOutput($this->load->view('inventory/vendor_product_wastage_lists.tpl', $data));

    }



    public function autocomplete() {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/general');

            $filter_data = [
                'filter_name' => $this->request->get['filter_name'],
                'sort' => 'name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_catalog_general->getProducts($filter_data);

            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
                $result['index'] = $result['name'];
                if (strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                    $result['name'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
                    $result['name'] = end($result['name']);
                }

                $json[] = [
                    'product_id' => $result['product_id'],
                    'index' => $result['index'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')) . ' - ' . $result['unit'],
                    'unit' => $result['unit'],
                ];
            }
        }

        $sort_order = [];

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        array_multisort($sort_order, SORT_ASC, $json);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getVariation() {
        $this->load->model('catalog/general');

        $product_variations = $this->model_catalog_general->getProductVariations($this->request->get['product_id']);

        if (!$product_variations) {
            $product_variations = [];
        }

        $data['product_variations'] = [];

        foreach ($product_variations as $product_variation) {
            $data['product_variations'][] = [
                'name' => $product_variation['name'],
                'product_id' => $product_variation['product_id'],
                'id' => $product_variation['id'],
            ];
        }

        $this->response->setOutput($this->load->view('catalog/variation_form.tpl', $data));
    }


    public function getUserByName($name) {
        if ($name) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "user` u WHERE CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($name) . "%'");

            return $query->row['user_id'];
        }
    }

    public function updateInventoryWastage() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $supplier_details = NULL;

        $log = new Log('error.log');
        $log->write($this->request->post);
        $log->write($this->request->get);


        // if ($this->request->get['vendor_product_id'] != NULL && $this->request->get['vendor_product_uom'] != NULL && $this->request->get['buying_price'] != NULL && $this->request->get['procured_quantity'] != NULL && $this->request->get['rejected_quantity'] != NULL) {
        if ($this->request->get['vendor_product_name'] != NULL && $this->request->get['vendor_product_uom'] != NULL  && $this->request->get['wastage_quantity'] != NULL) {
            $this->load->language('inventory/inventory_wastage');
            $this->load->model('inventory/inventory_wastage');


            $vendor_product_uom = $this->request->get['vendor_product_uom'];
            $wastage_quantity = $this->request->get['wastage_quantity'];
            $vendor_product_name = $this->request->get['vendor_product_name'];
            // $vendor_product_id = ;

            $result = $this->model_inventory_inventory_wastage->updateProductWastage($vendor_product_name, $vendor_product_uom,$wastage_quantity);
            $log->write('RESULT');
            $log->write($result);
            $log->write('RESULT');
            $json['status'] = '200';
            $json['message'] = 'Products wastage noted!';
            $this->session->data['success'] = 'Products wastage noted!';
        } else {
            $json['status'] = '400';
            $json['message'] = 'All fields are mandatory!';
            $this->session->data['warning'] = 'All fields are mandatory!';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
