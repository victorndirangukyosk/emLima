<?php

class ControllerCatalogProduct extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        $this->getList();
    }

    public function add() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $product_id = $this->model_catalog_product->addProduct($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_category'])) {
                $url .= '&filter_category=' . $this->request->get['filter_category'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/product/edit', 'product_id=' . $product_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/product/edit', 'product_id=' . $this->request->get['product_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_product->deleteProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

            $this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function copy() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_product->copyProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

            $this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function updateInventory() {
        $this->load->language('catalog/product');
        $update_products = $this->request->get['updated_products'];
        //echo'<pre>';print_r($this->request->get);exit;
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->model('catalog/vendor_product');

        if (count($update_products) > 0) {
            foreach ($update_products as $product) {
                $data[] = $this->model_catalog_vendor_product->updateProductInventory($product['product_store_id'], $product);
            }
        }
        $this->session->data['success'] = 'Products stocks modified successfully!';
        echo 0;
        exit();
    }

    public function updateInventorysingle() {

        $json = [];
        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];
        $supplier_details = NULL;

        $log = new Log('error.log');
        $log->write($this->request->post);
        $log->write($this->request->get);

        if ($this->request->get['vendor_product_id'] != NULL && $this->request->get['vendor_product_uom'] != NULL && $this->request->get['buying_price'] != NULL && $this->request->get['procured_quantity'] != NULL && $this->request->get['rejected_quantity'] != NULL) {
            $this->load->language('catalog/product');
            $this->load->model('catalog/vendor_product');
            $this->load->model('user/farmer');
            $this->load->model('user/supplier');

            $supplier_details = $this->model_user_supplier->getSupplier($this->request->get['buying_source_id']);
            if ($supplier_details == NULL) {
                $supplier_details = $this->model_user_farmer->getFarmer($this->request->get['buying_source_id']);
            }

            $log->write('supplier_details');
            $log->write($supplier_details);
            $log->write('supplier_details');

            $product_details = $this->model_catalog_vendor_product->getProduct($this->request->get['vendor_product_id']);
            $log->write($product_details);
            $vendor_product_uom = $this->request->get['vendor_product_uom'];
            $buying_price = $this->request->get['buying_price'];
            $buying_source = $this->request->get['buying_source'];
            $buying_source_id = $this->request->get['buying_source_id'];
            $procured_quantity = $this->request->get['procured_quantity'];
            $rejected_quantity = $this->request->get['rejected_quantity'];
            $vendor_product_id = $this->request->get['vendor_product_id'];

            $product['rejected_qty'] = $rejected_quantity;
            $product['procured_qty'] = $procured_quantity;
            $product['current_buying_price'] = $buying_price;
            $product['source'] = $buying_source;
            //$product['current_qty'] = $procured_quantity - $rejected_quantity;
            $product['current_qty'] = $product_details['quantity'];
            $product['product_name'] = $product_details['name'];
            $product['product_id'] = $product_details['product_id'];

            $result = $this->model_catalog_vendor_product->updateProductInventory($vendor_product_id, $product);
            //$ret = $this->emailtemplate->sendmessage($get_farmer_phone['mobile'], $sms_message);
            $log->write('RESULT');
            $log->write($result);
            $log->write('RESULT');
            $json['data'] = $this->url->link('catalog/vendor_product/inventoryvoucher', 'token=' . $this->session->data['token'] . '&product_history_id=' . $result, 'SSL');
            $json['status'] = '200';
            $json['message'] = 'Products stocks modified successfully!';
            $this->session->data['success'] = 'Products stocks modified successfully!';
        } else {
            $json['status'] = '400';
            $json['message'] = 'All fields are mandatory!';
            $this->session->data['warning'] = 'All fields are mandatory!';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateMultiInventory() {
        $this->load->language('catalog/product');
        $update_products = $this->request->get['updated_products'];
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/product');
        $this->load->model('catalog/vendor_product');
        $log = new Log('error.log');
        /* foreach ($update_products as $update_product) {
          $log->write($update_product['40839']);
          } */
        foreach ($update_products as $key => $value) {
            foreach ($value as $ke => $val) {
                $product = array('rejected_qty' => $val['rejected_qty'], 'procured_qty' => $val['total_procured_qty'], 'current_qty' => $val['current_qty'], 'current_buying_price' => $val['buying_price'], 'source' => $val['source'], 'product_id' => $val['product_id'], 'product_name' => $val['product_name']);
                $data[] = $this->model_catalog_vendor_product->updateProductInventory($ke, $product);
            }
        }
        $this->session->data['success'] = 'Products stocks modified successfully!';
        echo 0;
        exit();
    }

    public function updateInventoryPricing() {
        $this->load->language('catalog/product');
        $update_products = $this->request->get['updated_products'];
        //echo'<pre>';print_r($this->request->get);exit;
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->model('catalog/vendor_product');

        if (count($update_products) > 0) {
            foreach ($update_products as $product) {
                $data[] = $this->model_catalog_vendor_product->updateProductInventoryPricing($product['product_store_id'], $product);
            }
        }
        $this->session->data['success'] = 'Products prices modified successfully!';
        echo 0;
        exit();
    }

    public function getProductInventoryHistory() {
        $this->load->model('catalog/vendor_product');
        $product_store_id = $this->request->get['product_store_id'];
        $res = $this->model_catalog_vendor_product->productInventoryHistory($product_store_id);
        $html = '';
        if (count($res) > 0) {
            $html .= '<div class="container"><table style="width:48%;" class="table table-bordered table-striped table-responsive">
	   <thead>
       <tr class="info">
        <th>Procured Qty</th>
		<th>Rejected Qty</th>
		<th>Prev Qty</th>
		<th>Updated Qty</th>
                <th>Previous Buying Price</th>
                <th>Buying Price</th>
                <th>Previous Source</th>
                <th>Source</th>
		<th>Updated Date</th>
                <th>Updated By</th>
      </tr>
      </thead>';
        } else {
            $html .= '<span>No History Found</span>';
        }

        if (count($res) > 0) {
            $html .= '<tbody>';
            foreach ($res as $product_history) {
                $html .= '<tr>
					<th>' . $product_history['procured_qty'] . '</th>
					<th>' . $product_history['rejected_qty'] . '</th>
					<th>' . $product_history['prev_qty'] . '</th>
					<th>' . $product_history['current_qty'] . '</th>
                                        <th>' . $product_history['prev_buying_price'] . '</th>
                                        <th>' . $product_history['buying_price'] . '</th>
                                        <th>' . $product_history['prev_source'] . '</th>
					<th>' . $product_history['source'] . '</th>
					<th>' . $product_history['date_added'] . '</th>
                                        <th>' . $product_history['added_user'] . '</th>
			   </tr>';
            }

            $html .= '</tbody></table><div>';
        }
        echo $html;
        exit();
    }

    public function getProductInventoryPriceHistory() {
        $this->load->model('catalog/vendor_product');
        $product_store_id = $this->request->get['product_store_id'];
        $res = $this->model_catalog_vendor_product->productInventoryPriceHistory($product_store_id);
        $html = '';
        if (count($res) > 0) {
            $html .= '<div class="container"><table style="width:48%;" class="table table-bordered table-striped table-responsive">
	   <thead>
       <tr class="info">
        <th>Product Name</th>
        <th>Product Store Id</th>
        <th>Buying Price</th>
        <th>Source</th>
        <th>Prev Buying Price</th>
        <th>Prev Source</th>
	<th>Updation Date</th>
        <th>Updated By</th>
      </tr>
      </thead>';
        } else {
            $html .= '<span>No History Found</span>';
        }

        if (count($res) > 0) {
            $html .= '<tbody>';
            foreach ($res as $product_history) {
                $html .= '<tr>
				    <th>' . $product_history['product_name'] . '</th>
                                        <th>' . $product_history['product_store_id'] . '</th>
					<th>' . $product_history['buying_price'] . '</th>
					<th>' . $product_history['source'] . '</th>
					<th>' . $product_history['prev_buying_price'] . '</th>
					<th>' . $product_history['prev_source'] . '</th>
					<th>' . $product_history['date_added'] . '</th>
					<th>' . $product_history['added_user'] . '</th>
			   </tr>';
            }

            $html .= '</tbody></table><div>';
        }
        echo $html;
        exit();
    }

    public function getProductCategoryPricesHistory() {
        $this->load->model('catalog/vendor_product');
        $product_store_id = $this->request->get['product_store_id'];
        $res = $this->model_catalog_vendor_product->productCategoryPriceHistory($product_store_id);
        $html = '';
        if (count($res) > 0) {
            $html .= '<div class="container"><table style="width:48%;" class="table table-bordered table-striped table-responsive">
        <thead>
        <tr class="info">
         <th>Product Name</th>
         <th>Product Id</th>
         <th>Price Category</th>
         <th>Price</th>
         <th>Updation Date</th>
         <th>Updation By</th>
       </tr>
       </thead>';
        } else {
            $html .= '<span>No History Found</span>';
        }

        if (count($res) > 0) {
            $html .= '<tbody>';
            foreach ($res as $product_history) {
                $html .= '<tr>
                     <th>' . $product_history['product_name'] . '</th>
                     <th>' . $product_history['product_id'] . '</th>
                     <th>' . $product_history['price_category'] . '</th>
                     <th>' . $product_history['price'] . '</th>
                     <th>' . $product_history['date_added'] . '</th>
                     <th>' . $product_history['updated_by_name'] . '</th>
                </tr>';
            }

            $html .= '</tbody></table><div>';
        }
        echo $html;
        exit();
    }

    public function updateCategoryPrices() {
        $this->load->language('catalog/product');
        $this->load->model('sale/customer_group');
        $price_categories = $this->model_sale_customer_group->getPriceCategories();
        $update_data = $this->request->get['updatedata'];
        //echo'<pre>';print_r($update_data);exit;
        //$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->model('catalog/vendor_product');

        foreach ($price_categories as $price_cat) {
            if ($update_data[$price_cat['price_category']]) {
                $data[] = $this->model_catalog_vendor_product->updateCategoryPrices($price_cat['price_category'], $update_data);
            }
        }
        $this->cacheProductPrices(75);
        $this->session->data['success'] = 'Product Category Prices modified successfully!';
        echo 0;
        exit();
    }

    protected function cacheProductPrices($store_id) {
        $this->cache->delete('category_price_data');
        $cache_price_data = [];
        //$sql = 'SELECT * FROM `' . DB_PREFIX . "product_category_prices` where `store_id` = $store_id";
        $sql = 'SELECT * FROM `' . DB_PREFIX . "product_category_prices` where `store_id` > 0";
        //echo $sql;exit;
        $resultsdata = $this->db->query($sql);
        //echo '<pre>'; print_r($resultsdata);exit;
        if (count($resultsdata->rows) > 0) {
            foreach ($resultsdata->rows as $result) {
                $cache_price_data[$result['product_store_id'] . '_' . $result['price_category'] . '_' . $result['store_id']] = $result['price'];
            }
        }
        $this->cache->set('category_price_data', $cache_price_data);
    }

    protected function getList() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = null;
        }

        if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
        } else {
            $filter_category = null;
        }

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
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

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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
            'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['products'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_model' => $filter_model,
            'filter_price' => $filter_price,
            'filter_category' => $filter_category,
            'filter_quantity' => $filter_quantity,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $this->load->model('tool/image');

        $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

        $results = $this->model_catalog_product->getProducts($filter_data);

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(0);

        foreach ($results as $result) {
            $category = $this->model_catalog_product->getProductCategories($result['product_id']);

            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            $data['products'][] = [
                'product_id' => $result['product_id'],
                'image' => $image,
                'name' => $result['name'],
                'model' => $result['model'],
                // 'price' => $result['price'],
                // 'special' => $result['special_price'],
                'category' => $category,
                // 'quantity' => $result['quantity'],
                'status' => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit' => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_select_store'] = $this->language->get('text_select_store');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_product_id'] = $this->language->get('column_product_id');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_category'] = $this->language->get('column_category');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');

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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
        $data['sort_model'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
        $data['sort_category'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p2c.category' . $url, 'SSL');
        $data['sort_price'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
        $data['sort_quantity'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
        $data['sort_order'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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
        $pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_model'] = $filter_model;
        $data['filter_price'] = $filter_price;
        $data['filter_category'] = $filter_category;
        $data['filter_quantity'] = $filter_quantity;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        if ($this->user->isVendor()) {
            $this->response->setOutput($this->load->view('catalog/vendor_product_list.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('catalog/product_list.tpl', $data));
        }
    }

    protected function getForm() {
        $data = $this->language->all();
        // leaving the followings for extension B/C purpose

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_amount'] = $this->language->get('text_amount');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_recurring'] = $this->language->get('entry_recurring');

        $data['help_tag'] = $this->language->get('help_tag');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_recurring_add'] = $this->language->get('button_recurring_add');

        $data['tab_general'] = $this->language->get('tab_general');

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

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = [];
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = [];
        }

        if (isset($this->error['model'])) {
            $data['error_model'] = $this->error['model'];
        } else {
            $data['error_model'] = '';
        }

        if (isset($this->error['date_available'])) {
            $data['error_date_available'] = $this->error['date_available'];
        } else {
            $data['error_date_available'] = '';
        }

        if (isset($this->error['seo_url'])) {
            $data['error_seo_url'] = $this->error['seo_url'];
        } else {
            $data['error_seo_url'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
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
            'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['product_id'])) {
            $data['action'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['product_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

            if ($this->user->isVendor() && $product_info['vendor_id'] != $this->user->getId()) {
                die('illegal access!');
            }
        }

        $data['is_vendor'] = $this->user->isVendor();

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['product_description'])) {
            $data['product_description'] = $this->request->post['product_description'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
        } else {
            $data['product_description'] = [];
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($product_info)) {
            $data['image'] = $product_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['model'])) {
            $data['model'] = $this->request->post['model'];
        } elseif (!empty($product_info)) {
            $data['model'] = $product_info['model'];
        } else {
            $data['model'] = '';
        }

        if (isset($this->request->post['sku'])) {
            $data['sku'] = $this->request->post['sku'];
        } elseif (!empty($product_info)) {
            $data['sku'] = $product_info['sku'];
        } else {
            $data['sku'] = '';
        }

        if (isset($this->request->post['upc'])) {
            $data['upc'] = $this->request->post['upc'];
        } elseif (!empty($product_info)) {
            $data['upc'] = $product_info['upc'];
        } else {
            $data['upc'] = '';
        }

        if (isset($this->request->post['special_price'])) {
            $data['special_price'] = $this->request->post['special_price'];
        } elseif (!empty($product_info)) {
            $data['special_price'] = $product_info['special_price'];
        } else {
            $data['special_price'] = '';
        }

        if (isset($this->request->post['location'])) {
            $data['location'] = $this->request->post['location'];
        } elseif (!empty($product_info)) {
            $data['location'] = $product_info['location'];
        } else {
            $data['location'] = '';
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['product_store'])) {
            $data['product_store'] = $this->request->post['product_store'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
        } else {
            $data['product_store'] = [0];
        }

        if (isset($this->request->post['seo_url'])) {
            $data['seo_url'] = $this->request->post['seo_url'];
        } elseif (!empty($product_info)) {
            $data['seo_url'] = $product_info['seo_url'];
        } else {
            $data['seo_url'] = [];
        }

        if (isset($this->request->post['shipping'])) {
            $data['shipping'] = $this->request->post['shipping'];
        } elseif (!empty($product_info)) {
            $data['shipping'] = $product_info['shipping'];
        } else {
            $data['shipping'] = 1;
        }

        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($product_info)) {
            $data['price'] = $product_info['price'];
        } else {
            $data['price'] = '';
        }

        $this->load->model('catalog/recurring');

        $data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

        if (isset($this->request->post['product_recurrings'])) {
            $data['product_recurrings'] = $this->request->post['product_recurrings'];
        } elseif (!empty($product_info)) {
            $data['product_recurrings'] = $this->model_catalog_product->getRecurrings($product_info['product_id']);
        } else {
            $data['product_recurrings'] = [];
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['tax_class_id'])) {
            $data['tax_class_id'] = $this->request->post['tax_class_id'];
        } elseif (!empty($product_info)) {
            $data['tax_class_id'] = $product_info['tax_class_id'];
        } else {
            $data['tax_class_id'] = 0;
        }

        if (isset($this->request->post['date_available'])) {
            $data['date_available'] = $this->request->post['date_available'];
        } elseif (!empty($product_info)) {
            $data['date_available'] = ('0000-00-00' != $product_info['date_available']) ? $product_info['date_available'] : '';
        } else {
            $data['date_available'] = date('Y-m-d');
        }

        if (isset($this->request->post['quantity'])) {
            $data['quantity'] = $this->request->post['quantity'];
        } elseif (!empty($product_info)) {
            $data['quantity'] = $product_info['quantity'];
        } else {
            $data['quantity'] = 1;
        }

        if (isset($this->request->post['minimum'])) {
            $data['minimum'] = $this->request->post['minimum'];
        } elseif (!empty($product_info)) {
            $data['minimum'] = $product_info['minimum'];
        } else {
            $data['minimum'] = 1;
        }

        if (isset($this->request->post['subtract'])) {
            $data['subtract'] = $this->request->post['subtract'];
        } elseif (!empty($product_info)) {
            $data['subtract'] = $product_info['subtract'];
        } else {
            $data['subtract'] = 1;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($product_info)) {
            $data['sort_order'] = $product_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        $this->load->model('localisation/stock_status');

        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        if (isset($this->request->post['stock_status_id'])) {
            $data['stock_status_id'] = $this->request->post['stock_status_id'];
        } elseif (!empty($product_info)) {
            $data['stock_status_id'] = $product_info['stock_status_id'];
        } else {
            $data['stock_status_id'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($product_info)) {
            $data['status'] = $product_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['weight'])) {
            $data['weight'] = $this->request->post['weight'];
        } elseif (!empty($product_info)) {
            $data['weight'] = $product_info['weight'];
        } else {
            $data['weight'] = '';
        }

        $this->load->model('localisation/weight_class');

        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        if (isset($this->request->post['weight_class_id'])) {
            $data['weight_class_id'] = $this->request->post['weight_class_id'];
        } elseif (!empty($product_info)) {
            $data['weight_class_id'] = $product_info['weight_class_id'];
        } else {
            $data['weight_class_id'] = $this->config->get('config_weight_class_id');
        }

        if (isset($this->request->post['length'])) {
            $data['length'] = $this->request->post['length'];
        } elseif (!empty($product_info)) {
            $data['length'] = $product_info['length'];
        } else {
            $data['length'] = '';
        }

        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (!empty($product_info)) {
            $data['width'] = $product_info['width'];
        } else {
            $data['width'] = '';
        }

        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (!empty($product_info)) {
            $data['height'] = $product_info['height'];
        } else {
            $data['height'] = '';
        }

        if (isset($this->request->post['default_variation_name'])) {
            $data['default_variation_name'] = $this->request->post['default_variation_name'];
        } elseif (!empty($product_info)) {
            $data['default_variation_name'] = $product_info['default_variation_name'];
        } else {
            $data['default_variation_name'] = '';
        }

        $this->load->model('localisation/length_class');

        $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        if (isset($this->request->post['length_class_id'])) {
            $data['length_class_id'] = $this->request->post['length_class_id'];
        } elseif (!empty($product_info)) {
            $data['length_class_id'] = $product_info['length_class_id'];
        } else {
            $data['length_class_id'] = $this->config->get('config_length_class_id');
        }

        $this->load->model('catalog/manufacturer');

        if (isset($this->request->post['manufacturer_id'])) {
            $data['manufacturer_id'] = $this->request->post['manufacturer_id'];
        } elseif (!empty($product_info)) {
            $data['manufacturer_id'] = $product_info['manufacturer_id'];
        } else {
            $data['manufacturer_id'] = 0;
        }

        if (isset($this->request->post['manufacturer'])) {
            $data['manufacturer'] = $this->request->post['manufacturer'];
        } elseif (!empty($product_info)) {
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

            if (!empty($manufacturer_info['name'])) {
                $data['manufacturer'] = $manufacturer_info['name'];
            } else {
                $data['manufacturer'] = '';
            }
        } else {
            $data['manufacturer'] = '';
        }

        // Categories
        $this->load->model('catalog/category');

        if (isset($this->request->post['product_category'])) {
            $categories = $this->request->post['product_category'];
        } elseif (isset($this->request->get['product_id'])) {
            $categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
        } else {
            $categories = [];
        }

        $data['product_categories'] = [];

        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $data['product_categories'][] = [
                    'category_id' => $category_info['category_id'],
                    'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name'],
                ];
            }
        }

        // Filters
        $this->load->model('catalog/filter');

        if (isset($this->request->post['product_filter'])) {
            $filters = $this->request->post['product_filter'];
        } elseif (isset($this->request->get['product_id'])) {
            $filters = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
        } else {
            $filters = [];
        }

        $data['product_filters'] = [];

        foreach ($filters as $filter_id) {
            $filter_info = $this->model_catalog_filter->getFilter($filter_id);

            if ($filter_info) {
                $data['product_filters'][] = [
                    'filter_id' => $filter_info['filter_id'],
                    'name' => $filter_info['group'] . ' &gt; ' . $filter_info['name'],
                ];
            }
        }

        // Attributes
        $this->load->model('catalog/attribute');

        if (isset($this->request->post['product_attribute'])) {
            $product_attributes = $this->request->post['product_attribute'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
        } else {
            $product_attributes = [];
        }

        $data['product_attributes'] = [];

        foreach ($product_attributes as $product_attribute) {
            $attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

            if ($attribute_info) {
                $data['product_attributes'][] = [
                    'attribute_id' => $product_attribute['attribute_id'],
                    'name' => $attribute_info['name'],
                    'product_attribute_description' => $product_attribute['product_attribute_description'],
                ];
            }
        }

        // Options
        $this->load->model('catalog/option');

        if (isset($this->request->post['product_option'])) {
            $product_options = $this->request->post['product_option'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
        } else {
            $product_options = [];
        }

        $data['product_options'] = [];

        foreach ($product_options as $product_option) {
            $product_option_value_data = [];

            if (isset($product_option['product_option_value'])) {
                foreach ($product_option['product_option_value'] as $product_option_value) {
                    $product_option_value_data[] = [
                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                        'option_value_id' => $product_option_value['option_value_id'],
                        'quantity' => $product_option_value['quantity'],
                        'subtract' => $product_option_value['subtract'],
                        'price' => $product_option_value['price'],
                        'price_prefix' => $product_option_value['price_prefix'],
                        'points' => $product_option_value['points'],
                        'points_prefix' => $product_option_value['points_prefix'],
                        'weight' => $product_option_value['weight'],
                        'weight_prefix' => $product_option_value['weight_prefix'],
                    ];
                }
            }

            $data['product_options'][] = [
                'product_option_id' => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id' => $product_option['option_id'],
                'name' => $product_option['name'],
                'type' => $product_option['type'],
                'value' => isset($product_option['value']) ? $product_option['value'] : '',
                'required' => $product_option['required'],
            ];
        }

        $data['option_values'] = [];

        foreach ($data['product_options'] as $product_option) {
            if ('select' == $product_option['type'] || 'radio' == $product_option['type'] || 'checkbox' == $product_option['type'] || 'image' == $product_option['type']) {
                if (!isset($data['option_values'][$product_option['option_id']])) {
                    $data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
                }
            }
        }

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if (isset($this->request->post['product_discount'])) {
            $product_discounts = $this->request->post['product_discount'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
        } else {
            $product_discounts = [];
        }

        $data['product_discounts'] = [];

        foreach ($product_discounts as $product_discount) {
            $data['product_discounts'][] = [
                'customer_group_id' => $product_discount['customer_group_id'],
                'quantity' => $product_discount['quantity'],
                'priority' => $product_discount['priority'],
                'price' => $product_discount['price'],
                'date_start' => ('0000-00-00' != $product_discount['date_start']) ? $product_discount['date_start'] : '',
                'date_end' => ('0000-00-00' != $product_discount['date_end']) ? $product_discount['date_end'] : '',
            ];
        }

        if (isset($this->request->post['product_special'])) {
            $product_specials = $this->request->post['product_special'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_specials = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
        } else {
            $product_specials = [];
        }

        $data['product_specials'] = [];

        foreach ($product_specials as $product_special) {
            $data['product_specials'][] = [
                'customer_group_id' => $product_special['customer_group_id'],
                'priority' => $product_special['priority'],
                'price' => $product_special['price'],
                'date_start' => ('0000-00-00' != $product_special['date_start']) ? $product_special['date_start'] : '',
                'date_end' => ('0000-00-00' != $product_special['date_end']) ? $product_special['date_end'] : '',
            ];
        }

        // Variations
        if (isset($this->request->post['product_variation'])) {
            $product_variations = $this->request->post['product_variation'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_variations = $this->model_catalog_product->getProductVariations($this->request->get['product_id']);
        } else {
            $product_variations = [];
        }

        $data['product_variations'] = [];

        foreach ($product_variations as $product_variation) {
            if (is_file(DIR_IMAGE . $product_variation['image'])) {
                $variation = $product_variation['image'];
                $thumb = $product_variation['image'];
            } else {
                $variation = '';
                $thumb = 'no_image.png';
            }

            $data['product_variations'][] = [
                'image' => $product_variation['image'],
                'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
                'name' => $product_variation['name'],
                'price' => $product_variation['price'],
                'special_price' => $product_variation['special_price'],
                'sort_order' => $product_variation['sort_order'],
            ];
        }

        // Images
        if (isset($this->request->post['product_image'])) {
            $product_images = $this->request->post['product_image'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
        } else {
            $product_images = [];
        }

        $data['product_images'] = [];

        foreach ($product_images as $product_image) {
            if (is_file(DIR_IMAGE . $product_image['image'])) {
                $image = $product_image['image'];
                $thumb = $product_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['product_images'][] = [
                'image' => $image,
                'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
                'sort_order' => $product_image['sort_order'],
            ];
        }

        if (isset($this->request->post['product_related'])) {
            $products = $this->request->post['product_related'];
        } elseif (isset($this->request->get['product_id'])) {
            $products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
        } else {
            $products = [];
        }

        $data['product_relateds'] = [];

        foreach ($products as $product_id) {
            $related_info = $this->model_catalog_product->getProduct($product_id);

            if ($related_info) {
                $data['product_relateds'][] = [
                    'product_id' => $related_info['product_id'],
                    'name' => $related_info['name'],
                ];
            }
        }

        if (isset($this->request->post['points'])) {
            $data['points'] = $this->request->post['points'];
        } elseif (!empty($product_info)) {
            $data['points'] = $product_info['points'];
        } else {
            $data['points'] = '';
        }

        if (isset($this->request->post['product_reward'])) {
            $data['product_reward'] = $this->request->post['product_reward'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
        } else {
            $data['product_reward'] = [];
        }

        // Text Editor
        $data['text_editor'] = $this->config->get('config_text_editor');

        if (empty($data['text_editor'])) {
            $data['text_editor'] = 'tinymce';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['product_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
            $this->error['model'] = $this->language->get('error_model');
        }

        $this->load->model('catalog/url_alias');

        foreach ($this->request->post['seo_url'] as $language_id => $value) {
            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($value, $language_id);

            if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }

            if ($url_alias_info && !isset($this->request->get['product_id'])) {
                $this->error['seo_url'][$language_id] = sprintf($this->language->get('error_seo_url'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCopy() {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_status'])) {
                $filter_status = $this->request->get['filter_status'];
            } else {
                $filter_status = null;
            }

            if (isset($this->request->get['filter_store'])) {
                $filter_store = $this->request->get['filter_store'];
            } else {
                $filter_store = '';
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_store' => $filter_store,
                'filter_status' => $filter_status,
                'filter_model' => $filter_model,
                'start' => 0,
                'limit' => $limit,
            ];

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'product_id' => $result['product_id'],
                    'default_variation_name' => strip_tags(html_entity_decode($result['default_variation_name'], ENT_QUOTES, 'UTF-8')),
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model' => $result['model'],
                    'option' => [],
                    'variations' => $this->model_catalog_product->getProductVariations($result['product_id']),
                        //'price' => $result['price']
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function category_autocomplete() {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/category');

            $filter_data = [
                'filter_name' => $this->request->get['filter_name'],
                'sort' => 'name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 5,
            ];

            $results = $this->model_catalog_category->getCategories($filter_data);

            foreach ($results as $result) {
                $result['index'] = $result['name'];
                if (strpos($result['name'], '&nbsp;&nbsp;&gt;&nbsp;&nbsp;')) {
                    $result['name'] = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $result['name']);
                    $result['name'] = end($result['name']);
                }

                $json[] = [
                    'category_id' => $result['category_id'],
                    'index' => $result['index'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
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

    public function changeStatus() {
        if (isset($this->request->get['status'])) {
            $status = $this->request->get['status'];
        } else {
            $status = 0;
        }

        $this->load->model('catalog/product');

        if (isset($this->request->get['selected'])) {
            foreach ($this->request->get['selected'] as $product_id) {
                $this->model_catalog_product->updateProduct($this->user->getId(), $product_id);
            }
        }

        echo 0;
    }

    public function autocompleteStoreProduct() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');
            $this->load->model('catalog/vendor_product');
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_status'])) {
                $filter_status = $this->request->get['filter_status'];
            } else {
                $filter_status = null;
            }

            if (isset($this->request->get['filter_store'])) {
                $filter_store = $this->request->get['filter_store'];
            } else {
                $filter_store = '';
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_store' => $filter_store,
                'filter_status' => $filter_status,
                'filter_model' => $filter_model,
                'start' => 0,
                'limit' => $limit,
            ];

            $results = $this->model_catalog_vendor_product->getStoreProducts($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'product_store_id' => $result['product_store_id'],
                    'default_variation_name' => strip_tags(html_entity_decode($result['default_variation_name'], ENT_QUOTES, 'UTF-8')),
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model' => $result['model'],
                    'option' => [],
                    'variations' => $this->model_catalog_vendor_product->getProductStoreVariations($result['product_store_id']),
                        //'price' => $result['price']
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getProductInventoryPriceHistorybyDate($startdate, $product_store_id, $enddate = null) {
        $this->load->model('catalog/vendor_product');
        // $product_store_id = $this->request->get['product_store_id'];
        $res = $this->model_catalog_vendor_product->productInventoryPriceHistorybydate($startdate, $product_store_id, null);
        // echo "<pre>";print_r($data['products']);die;

        if (count($res) > 0) {

            // foreach ($res as $product_history) {
            // $product_history['buying_price'] 
            // }
            return $res[0]['buying_price'];
        } else {

            return 'NA';
        }
    }

}
