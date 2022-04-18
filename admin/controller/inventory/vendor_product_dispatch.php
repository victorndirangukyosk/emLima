<?php

class ControllerInventoryVendorProductDispatch  extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/general');

        $this->getList();
    }

    
    protected function getList() {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_tax_class_id'])) {
            $filter_tax_class_id = $this->request->get['filter_tax_class_id'];
        } else {
            $filter_tax_class_id = null;
        }

        if (isset($this->request->get['filter_vendor_name'])) {
            $filter_vendor_name = $this->request->get['filter_vendor_name'];
        } else {
            $filter_vendor_name = null;
        }

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = null;
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $filter_product_id_from = $this->request->get['filter_product_id_from'];
        } else {
            $filter_product_id_from = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
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

        if (isset($this->request->get['filter_category_price'])) {
            $filter_category_price = $this->request->get['filter_category_price'];
        } else {
            $filter_category_price = null;
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

        if (isset($this->request->get['filter_price_category_status'])) {
            $filter_price_category_status = $this->request->get['filter_price_category_status'];
        } else {
            $filter_price_category_status = null;
        }

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = null;
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

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
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
        if (isset($this->request->get['filter_price_category_status'])) {
            $url .= '&filter_price_category_status=' . $this->request->get['filter_price_category_status'];
        }
        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_category_price'])) {
            $url .= '&filter_category_price=' . $this->request->get['filter_category_price'];
        }

        if (isset($this->request->get['filter_tax_class_id'])) {
            $url .= '&filter_tax_class_id=' . $this->request->get['filter_tax_class_id'];
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
            'text' => ((false == $inventory) && (false == $prices)) ? $this->language->get('heading_title') : ((true == $prices) ? 'Products Category Prices' : 'Inventory Management'),
            'href' => $this->url->link('catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('catalog/vendor_product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['copy'] = $this->url->link('catalog/vendor_product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('catalog/vendor_product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['products'] = [];
        $this->load->model('inventory/vendor_product_dispatch');
        $category_price_prods = NULL;
        if (isset($this->request->get['filter_category_price'])) {
            $category_price_prods = $this->model_inventory_vendor_product_dispatch->getCategoryPriceDetailsByCategoryName(75, $this->request->get['filter_category_price']);
            $category_price_prods = array_column($category_price_prods, 'product_store_id');
            /* $log = new Log('error.log');
              $log->write('category_price_prods');
              $log->write($category_price_prods);
              $log->write('category_price_prods'); */
        }

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_vendor_name' => $this->getUserByName($filter_vendor_name),
            'filter_price' => $filter_price,
            'filter_product_id_from' => $filter_product_id_from,
            'filter_model' => $filter_model,
            'filter_product_id_to' => $filter_product_id_to,
            'filter_category' => $filter_category,
            'filter_store_id' => $filter_store_id,
            'filter_status' => $filter_status,
            'filter_price_category_status' => $filter_price_category_status,
            'filter_quantity' => $filter_quantity,
            'filter_category_price' => $filter_category_price,
            'filter_tax_class_id' => $filter_tax_class_id,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            'filter_category_price_prods' => isset($this->request->get['filter_category_price']) ? $category_price_prods : NULL,
        ];

        $this->load->model('tool/image');

        $product_total = $this->model_inventory_vendor_product_dispatch->getTotalProducts($filter_data);

        $results = $this->model_inventory_vendor_product_dispatch->getProducts($filter_data);
        //echo '<pre>';print_r($results);
        if (isset($this->request->get['filter_category_price'])) {
            $modified_res = [];
            $modified_res_new = [];
            if (count($results) > 0) {
                foreach ($results as $res) {
                    if (isset($category_prices[$res['product_store_id'] . '_' . $this->request->get['filter_category_price'] . '_75'])) {
                        $modified_res[] = $res;
                    }
                }
                if (count($modified_res) > 0) {
                    foreach ($modified_res as $modified) {
                        //$log = new Log('error.log');
                        //$log->write($modified);
                        $category_price_details = $this->model_catalog_vendor_product->getCategoryPriceDetails($modified['product_store_id'], $modified['product_id'], $modified['product_name'], $modified['store_id'], $this->request->get['filter_category_price']);
                        //$log->write($category_price_details);
                        if (is_array($category_price_details) && count($category_price_details) > 0 && array_key_exists('status', $category_price_details)) {
                            $modified['category_price_status'] = $category_price_details['status'];
                        } else {
                            $modified['category_price_status'] = 1;
                        }
                        $modified_res_new[] = $modified;
                    }
                }
            }

            $results = $modified_res_new;
            //$results = $modified_res;
            //$product_total = count($results);
        }

        $results_count = $this->model_inventory_vendor_product_dispatch->getProductsCount($filter_data);
        if (isset($this->request->get['filter_category_price'])) {
            $modified_res_count = [];
            if (count($results_count) > 0) {
                foreach ($results_count as $results_cou) {
                    if (isset($category_prices[$results_cou['product_store_id'] . '_' . $this->request->get['filter_category_price'] . '_75'])) {
                        $modified_res_count[] = $results_cou;
                    }
                }
            }

            $results_count = $modified_res_count;
            $product_total = count($results_count);
        }

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(0);
        $this->load->model('inventory/vendor_product_dispatch');

        foreach ($results as $result) {
            if (isset($this->request->get['filter_price_category_status']) && $result['category_price_status'] == $this->request->get['filter_price_category_status']) {
                $category = $this->model_inventory_vendor_product_dispatch->getProductCategories($result['product_id']);

                if (is_file(DIR_IMAGE . $result['image'])) {
                    $image = $this->model_tool_image->resize($result['image'], 40, 40);
                    $bigimage = $this->model_tool_image->getImage($result['image']);
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                    $bigimage = $this->model_tool_image->getImage('no_image.png');
                }

                $data['products'][] = [
                    // 'buying_price' => $result['buying_price'],
                    // 'source' => $result['source'],
                    'buying_price_store' => $result['buying_price_store'],
                    'source_store' => $result['source_store'],
                    'store_name' => $result['store_name'],
                    //'vendor_name'=>$result['fs'].' '.$result['ls'],
                    'product_store_id' => $result['product_store_id'],
                    'product_id' => $result['product_id'],
                    'price' => $result['price'],
                    'special_price' => $result['special_price'],
                    // 'quantity' => $result['quantity'],
                    'quantity_store' => $result['quantity_store'],

                    'image' => $image,
                    'bigimage' => $bigimage,
                    'name' => $result['product_name'],
                    'unit' => $result['unit'],
                    //'weight' => $result['weight'],
                    'model' => $result['model'],
                    'category' => $category,
                    'category_price_status' => array_key_exists('category_price_status', $result) ? $result['category_price_status'] : '',
                    'status' => ($result['sts']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'edit' => $this->url->link('catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $result['product_store_id'] . $url, 'SSL'),
                ];
            }
            if (!isset($this->request->get['filter_price_category_status'])) {
                $category = $this->model_inventory_vendor_product_dispatch->getProductCategories($result['product_id']);

                if (is_file(DIR_IMAGE . $result['image'])) {
                    $image = $this->model_tool_image->resize($result['image'], 40, 40);
                    $bigimage = $this->model_tool_image->getImage($result['image']);
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                    $bigimage = $this->model_tool_image->getImage('no_image.png');
                }

                $data['products'][] = [
                    // 'buying_price' => $result['buying_price'],
                    // 'source' => $result['source'],
                    'buying_price_store' => $result['buying_price_store'],
                    'source_store' => $result['source_store'],
                    'store_name' => $result['store_name'],
                    //'vendor_name'=>$result['fs'].' '.$result['ls'],
                    'product_store_id' => $result['product_store_id'],
                    'product_id' => $result['product_id'],
                    'price' => $result['price'],
                    'special_price' => $result['special_price'],
                    // 'quantity' => $result['quantity'],
                    'quantity_store' => $result['quantity_store'],
                    'image' => $image,
                    'bigimage' => $bigimage,
                    'name' => $result['product_name'],
                    'unit' => $result['unit'],
                    //'weight' => $result['weight'],
                    'model' => $result['model'],
                    'category' => $category,
                    'category_price_status' => array_key_exists('category_price_status', $result) ? $result['category_price_status'] : '',
                    'status' => ($result['sts']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                    'edit' => $this->url->link('catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $result['product_store_id'] . $url, 'SSL'),
                ];
            }
        }

        if ($this->user->isVendor()) {
            $data['is_vendor'] = 1;
        } else {
            $data['is_vendor'] = 0;
        }

        $data['heading_title'] = ((false == $inventory) && (false == $prices)) ? $this->language->get('heading_title') : ((true == $prices) ? 'Products Category Prices' : 'Inventory Management');

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
        $data['column_model'] = $this->language->get('column_model');
        $data['column_product_id'] = $this->language->get('column_product_id');
        $data['column_vproduct_id'] = $this->language->get('column_vproduct_id');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_store_name'] = $this->language->get('entry_store_name');
        $data['entry_vendor_name'] = $this->language->get('entry_vendor_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_product_id_from'] = $this->language->get('entry_product_id_from');
        $data['entry_product_id_to'] = $this->language->get('entry_product_id_to');

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

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['filter_price_category_status'])) {
            $url .= '&filter_price_category_status=' . $this->request->get['filter_price_category_status'];
        }

        if (isset($this->request->get['filter_category_price'])) {
            $url .= '&filter_category_price=' . $this->request->get['filter_category_price'];
        }

        if (isset($this->request->get['filter_tax_class_id'])) {
            $url .= '&filter_tax_class_id=' . $this->request->get['filter_tax_class_id'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

  
            $data['sort_name'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
            $data['sort_model'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');

            $data['sort_store'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=st.name' . $url, 'SSL');

            $data['sort_product_id'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.product_id' . $url, 'SSL');

            $data['sort_vproduct_id'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=ps.product_store_id' . $url, 'SSL');

            $data['sort_category'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p2c.category' . $url, 'SSL');
            $data['sort_price'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
            $data['sort_quantity'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=ps.quantity' . $url, 'SSL');
            $data['sort_status'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
            $data['sort_order'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
    

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor_name'])) {
            $url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['filter_price_category_status'])) {
            $url .= '&filter_price_category_status=' . $this->request->get['filter_price_category_status'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_category_price'])) {
            $url .= '&filter_category_price=' . $this->request->get['filter_category_price'];
        }

        if (isset($this->request->get['filter_tax_class_id'])) {
            $url .= '&filter_tax_class_id=' . $this->request->get['filter_tax_class_id'];
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

        
            $pagination->url = $this->url->link('inventory/vendor_product_dispatch/inventory', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
        

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_vendor_name'] = $filter_vendor_name;
        $data['filter_price'] = $filter_price;
        $data['filter_product_id_from'] = $filter_product_id_from;
        $data['filter_model'] = $filter_model;
        $data['filter_product_id_to'] = $filter_product_id_to;
        $data['filter_category'] = $filter_category;
        $data['filter_store_id'] = $filter_store_id;
        $data['filter_status'] = $filter_status;
        $data['filter_price_category_status'] = $filter_price_category_status;
        $data['filter_category_price'] = $filter_category_price;
        $data['filter_tax_class_id'] = $filter_tax_class_id;
        $this->load->model('localisation/tax_class');
        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');

        // echo "<pre>";print_r($data['heading_title'] );die;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->load->model('sale/customer_group');
        /* PREVIOUS CODE */
        //$data['price_categories'] =  $this->model_sale_customer_group->getPriceCategories();
        $data['price_categories_list'] = $this->model_sale_customer_group->getPriceCategories();
        $data['price_categories'] = $this->model_sale_customer_group->getPriceCategoriesfilter($filter_category_price);
        $data['category_prices'] = $category_prices;
        // echo '<pre>';print_r($cachePrice_data);exit;
        
            $data['inventory_history'] = $this->url->link('inventory/vendor_product_dispatch/InventoryDispatchHistory', 'token=' . $this->session->data['token'], 'SSL');
            $this->response->setOutput($this->load->view('inventory/vendor_product_dispatch _lists.tpl', $data));
       
    }

    protected function getInventoryList($inventory = false, $prices = false) {
        $category_prices = $this->getCategoriesProductPrices();
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

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = null;
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $filter_product_id_from = $this->request->get['filter_product_id_from'];
        } else {
            $filter_product_id_from = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
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

        if (isset($this->request->get['filter_category_price'])) {
            $filter_category_price = $this->request->get['filter_category_price'];
        } else {
            $filter_category_price = null;
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

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = null;
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

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
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
        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_category_price'])) {
            $url .= '&filter_category_price=' . $this->request->get['filter_category_price'];
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
            'text' => ((false == $inventory) && (false == $prices)) ? $this->language->get('heading_title') : ((true == $prices) ? 'Products Category Prices' : 'Inventory Management'),
            'href' => $this->url->link('catalog/vendor_product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('catalog/vendor_product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['copy'] = $this->url->link('catalog/vendor_product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('catalog/vendor_product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['products'] = [];
        $this->load->model('catalog/vendor_product');
        $category_price_prods = NULL;
        if (isset($this->request->get['filter_category_price'])) {
            $category_price_prods = $this->model_catalog_vendor_product->getCategoryPriceDetailsByCategoryName(75, $this->request->get['filter_category_price']);
            $category_price_prods = array_column($category_price_prods, 'product_store_id');
            /* $log = new Log('error.log');
              $log->write('category_price_prods');
              $log->write($category_price_prods);
              $log->write('category_price_prods'); */
        }

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_vendor_name' => $filter_vendor_name,
            'filter_price' => $filter_price,
            'filter_product_id_from' => $filter_product_id_from,
            'filter_model' => $filter_model,
            'filter_product_id_to' => $filter_product_id_to,
            'filter_category' => $filter_category,
            'filter_store_id' => $filter_store_id,
            'filter_status' => $filter_status,
            'filter_quantity' => $filter_quantity,
            'filter_category_price' => $filter_category_price,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            'filter_category_price_prods' => isset($this->request->get['filter_category_price']) ? $category_price_prods : NULL,
        ];

        $this->load->model('tool/image');

        $product_total = $this->model_catalog_vendor_product->getTotalProducts($filter_data);

        $results = $this->model_catalog_vendor_product->getProducts($filter_data);
        //echo '<pre>';print_r($results);
        if (isset($this->request->get['filter_category_price'])) {
            $modified_res = [];
            $modified_res_new = [];
            if (count($results) > 0) {
                foreach ($results as $res) {
                    if (isset($category_prices[$res['product_store_id'] . '_' . $this->request->get['filter_category_price'] . '_75'])) {
                        $modified_res[] = $res;
                    }
                }
                if (count($modified_res) > 0) {
                    foreach ($modified_res as $modified) {
                        //$log = new Log('error.log');
                        //$log->write($modified);
                        $category_price_details = $this->model_catalog_vendor_product->getCategoryPriceDetails($modified['product_store_id'], $modified['product_id'], $modified['product_name'], $modified['store_id'], $this->request->get['filter_category_price']);
                        //$log->write($category_price_details);
                        if (is_array($category_price_details) && count($category_price_details) > 0 && array_key_exists('status', $category_price_details)) {
                            $modified['category_price_status'] = $category_price_details['status'];
                        } else {
                            $modified['category_price_status'] = 1;
                        }
                        $modified_res_new[] = $modified;
                    }
                }
            }

            $results = $modified_res_new;
            //$results = $modified_res;
            //$product_total = count($results);
        }

        $results_count = $this->model_catalog_vendor_product->getProductsCount($filter_data);
        if (isset($this->request->get['filter_category_price'])) {
            $modified_res_count = [];
            if (count($results_count) > 0) {
                foreach ($results_count as $results_cou) {
                    if (isset($category_prices[$results_cou['product_store_id'] . '_' . $this->request->get['filter_category_price'] . '_75'])) {
                        $modified_res_count[] = $results_cou;
                    }
                }
            }

            $results_count = $modified_res_count;
            $product_total = count($results_count);
        }

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(0);

        foreach ($results as $result) {
            $category = $this->inventory_vendor_product_dispatch->getProductCategories($result['product_id']);

            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
                $bigimage = $this->model_tool_image->getImage($result['image']);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                $bigimage = $this->model_tool_image->getImage('no_image.png');
            }

            $data['products'][] = [
                'store_name' => $result['store_name'],
                //'vendor_name'=>$result['fs'].' '.$result['ls'],
                'product_store_id' => $result['product_store_id'],
                'product_id' => $result['product_id'],
                'price' => $result['price'],
                'special_price' => $result['special_price'],
                'quantity' => $result['quantity'],
                'buying_price' => $result['buying_price'],
                'source' => $result['source'],
                'image' => $image,
                'bigimage' => $bigimage,
                'name' => $result['product_name'],
                'unit' => $result['unit'],
                //'weight' => $result['weight'],
                'model' => $result['model'],
                'category' => $category,
                'category_price_status' => array_key_exists('category_price_status', $result) ? $result['category_price_status'] : '',
                'status' => ($result['sts']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit' => $this->url->link('catalog/vendor_product/edit', 'token=' . $this->session->data['token'] . '&store_product_id=' . $result['product_store_id'] . $url, 'SSL'),
            ];
        }

        if ($this->user->isVendor()) {
            $data['is_vendor'] = 1;
        } else {
            $data['is_vendor'] = 0;
        }

        $data['heading_title'] = ((false == $inventory) && (false == $prices)) ? $this->language->get('heading_title') : ((true == $prices) ? 'Products Category Prices' : 'Inventory Management');

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
        $data['column_model'] = $this->language->get('column_model');
        $data['column_product_id'] = $this->language->get('column_product_id');
        $data['column_vproduct_id'] = $this->language->get('column_vproduct_id');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_store_name'] = $this->language->get('entry_store_name');
        $data['entry_vendor_name'] = $this->language->get('entry_vendor_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_product_id_from'] = $this->language->get('entry_product_id_from');
        $data['entry_product_id_to'] = $this->language->get('entry_product_id_to');

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

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['filter_category_price'])) {
            $url .= '&filter_category_price=' . $this->request->get['filter_category_price'];
        }

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

         
           
            $data['sort_name'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
            $data['sort_model'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');

            $data['sort_store'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=st.name' . $url, 'SSL');

            $data['sort_product_id'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.product_id' . $url, 'SSL');

            $data['sort_vproduct_id'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=ps.product_store_id' . $url, 'SSL');

            $data['sort_category'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p2c.category' . $url, 'SSL');
            $data['sort_price'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
            $data['sort_quantity'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=ps.quantity' . $url, 'SSL');
            $data['sort_status'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
            $data['sort_order'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
        
        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor_name'])) {
            $url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
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
            $url .= '&filter_category=' . $this->request->get['filter_category'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_category_price'])) {
            $url .= '&filter_category_price=' . $this->request->get['filter_category_price'];
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

        if (false == $inventory && false == $prices) {
            $pagination->url = $this->url->link('catalog/vendor_product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
        } elseif (true == $inventory) {
            $pagination->url = $this->url->link('catalog/vendor_product/Manageinventory', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
        } elseif (true == $prices) {
            $pagination->url = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
        }

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_vendor_name'] = $filter_vendor_name;
        $data['filter_price'] = $filter_price;
        $data['filter_product_id_from'] = $filter_product_id_from;
        $data['filter_model'] = $filter_model;
        $data['filter_product_id_to'] = $filter_product_id_to;
        $data['filter_category'] = $filter_category;
        $data['filter_store_id'] = $filter_store_id;
        $data['filter_status'] = $filter_status;
        $data['filter_category_price'] = $filter_category_price;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');

        //echo "<pre>";print_r($data['heading_title'] );die;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->load->model('sale/customer_group');
        /* PREVIOUS CODE */
        //$data['price_categories'] =  $this->model_sale_customer_group->getPriceCategories();
        $data['price_categories_list'] = $this->model_sale_customer_group->getPriceCategories();
        $data['price_categories'] = $this->model_sale_customer_group->getPriceCategoriesfilter($filter_category_price);
        $data['category_prices'] = $category_prices;
        //echo '<pre>';print_r($cachePrice_data);exit;
        
            $this->response->setOutput($this->load->view('inventory/vendor_product_dispatch _lists.tpl', $data));
        
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

   

    public function inventory() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/general');

        $this->getList(true);
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

        if ($this->request->get['vendor_product_id'] != NULL && $this->request->get['vendor_product_uom'] != NULL && $this->request->get['buying_price'] != NULL && $this->request->get['received_quantity'] != NULL ) {
            $this->load->language('catalog/product');
            $this->load->language('catalog/product');
            $this->load->model('inventory/vendor_product_dispatch');
            $this->load->model('user/farmer');
            $this->load->model('catalog/vendor_product');
            // $this->load->model('user/supplier');

            // $supplier_details = $this->model_user_supplier->getSupplier($this->request->get['buying_source_id']);
            // if ($supplier_details == NULL) {
            //     $supplier_details = $this->model_user_farmer->getFarmer($this->request->get['buying_source_id']);
            // }

            // $log->write('supplier_details');
            // $log->write($supplier_details);
            // $log->write('supplier_details');

            $product_details = $this->model_catalog_vendor_product->getProduct($this->request->get['vendor_product_id']);
            $log->write($product_details);
            $vendor_product_uom = $this->request->get['vendor_product_uom'];
            $buying_price = $this->request->get['buying_price'];
            // $buying_source = $this->request->get['buying_source'];
            // $buying_source_id = $this->request->get['buying_source_id'];
            $received_quantity = $this->request->get['received_quantity'];
            // $rejected_quantity = $this->request->get['rejected_quantity'];
            $vendor_product_id = $this->request->get['vendor_product_id'];

            $product['received_quantity'] = $received_quantity;
            // $product['procured_qty'] = $procured_quantity;
            $product['current_buying_price'] = $buying_price;
            // $product['source'] = $buying_source;
            //$product['current_qty'] = $procured_quantity - $rejected_quantity;
            $product['current_qty'] = $product_details['quantity_store'];
            $product['product_name'] = $product_details['name'];
            $product['product_id'] = $product_details['product_id'];

            $result = $this->model_inventory_vendor_product_dispatch->updateProductInventory($vendor_product_id, $product);
            //$ret = $this->emailtemplate->sendmessage($get_farmer_phone['mobile'], $sms_message);
            $log->write('RESULT');
            $log->write($result);
            $log->write('RESULT');
            $json['data'] = $this->url->link('inventory/vendor_product_dispatch/inventorydispatchvoucher', 'token=' . $this->session->data['token'] . '&product_history_id=' . $result, 'SSL');
            $json['status'] = '200';
            $json['message'] = 'Products received data modified successfully!';
            $this->session->data['success'] = 'Products received data modified successfully!';
        } else {
            $json['status'] = '400';
            $json['message'] = 'All fields are mandatory!';
            $this->session->data['warning'] = 'All fields are mandatory!';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateMultiInventory() {
        $update_products = $this->request->get['updated_products'];
        $this->load->model('inventory/vendor_product_dispatch');
        $log = new Log('error.log');
        /* foreach ($update_products as $update_product) {
          $log->write($update_product['40839']);
          } */
        foreach ($update_products as $key => $value) {
            foreach ($value as $ke => $val) {
                $product = array('rejected_qty' => $val['rejected_qty'], 'procured_qty' => $val['total_procured_qty'], 'current_qty' => $val['current_qty'], 'current_buying_price' => $val['buying_price'], 'source' => $val['source'], 'product_id' => $val['product_id'], 'product_name' => $val['product_name']);
                $data[] = $this->model_inventory_vendor_product_dispatch->updateProductInventory($ke, $product);
            }
        }
        $this->session->data['success'] = 'Products stocks modified successfully!';
        echo 0;
        exit();
    }


    public function InventoryDispatchHistory() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_page_title'));

        $this->load->model('catalog/vendor_product');

        $data['kondutoStatus'] = $this->config->get('config_konduto_status');
        $data['konduto_public_key'] = $this->config->get('config_konduto_public_key');

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
            'href' => $this->url->link('catalog/vendor_product/InventoryHistory', 'token=' . $this->session->data['token'] . $url, 'SSL'),
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

        $history_total = $this->model_catalog_vendor_product->getTotalProductInventoryHistory($filter_data);

        $results = $this->model_catalog_vendor_product->getProductInventoryHistory($filter_data);

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {

            $data['history'][] = [
                'product_history_id' => $result['product_history_id'],
                'product_store_id' => $result['product_store_id'],
                'product_id' => $result['product_id'],
                'procured_qty' => $result['procured_qty'],
                'rejected_qty' => $result['rejected_qty'],
                'prev_qty' => $result['prev_qty'],
                'current_qty' => $result['current_qty'],
                'product_name' => $result['product_name'],
                'source' => $result['source'],
                'buying_price' => $result['buying_price'],
                'date_added' => $result['date_added'],
                'added_by' => $result['added_by'],
                'added_user_role' => $result['added_user_role'],
                'added_user' => $result['added_user'],
                'voucher' => $this->url->link('catalog/vendor_product/inventoryvoucher', 'token=' . $this->session->data['token'] . '&product_history_id=' . $result['product_history_id'] . $url, 'SSL'),
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

        $this->response->setOutput($this->load->view('catalog/inventory_history.tpl', $data));
    }

   
    public function InventoryDispatchHistoryexcel() {
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


    public function getProductDispatchHistory() {
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


    public function inventorydispatchvoucher() {
        $inventory_history_id = $this->request->get['product_history_id'];
        $this->load->model('catalog/vendor_product');

        $filter_data = [
            'filter_product_history_id' => $this->request->get['product_history_id']
        ];
        $results = $this->model_catalog_vendor_product->getProductInventoryHistory($filter_data);
        $vendor_product_details = $this->model_catalog_vendor_product->getProduct($results[0]['product_store_id']);
        $product_details = $this->model_catalog_vendor_product->getProductDetail($results[0]['product_id']);
        $log = new Log('error.log');
        $full_details = $results[0];
        $full_details['model'] = $product_details['model'];
        $full_details['unit'] = $product_details['unit'];
        $full_details['sub_total'] = $this->currency->format($full_details['buying_price'] * $full_details['procured_qty'], $this->config->get('config_currency'));
        $full_details['total'] = $this->currency->format($full_details['buying_price'] * $full_details['procured_qty'], $this->config->get('config_currency'));
        $full_details['buying_price'] = $this->currency->format($full_details['buying_price'], $this->config->get('config_currency'));

        try {
            require_once DIR_ROOT . '/vendor/autoload.php';
            $pdf = new \mikehaertl\wkhtmlto\Pdf;
            $template = $this->load->view('catalog/inventory_voucher.tpl', $full_details);
            $pdf->addPage($template);
            if (!$pdf->send("Inventory Voucher #" . $inventory_history_id . ".pdf")) {
                $error = $pdf->getError();
                echo $error;
                die;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
