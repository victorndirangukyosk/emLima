<?php

class ControllerCatalogGeneral extends Controller {

    private $error = [];

    public function index() {
        $this->load->language('catalog/general');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/general');

        $this->getList();
    }

    public function add() {

        //  echo "<pre>";print_r($this->request->post);die;
        $this->load->language('catalog/general');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/general');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            //echo "<pre>";print_r($this->request->post);die;
            $product_id = $this->model_catalog_general->addProduct($this->request->post);

            $new_variations_products_id[] = $product_id;

            if (isset($this->request->post['product_variation']) && count($this->request->post['product_variation']) > 0) {
                foreach ($this->request->post['product_variation'] as $key => $value) {
                    $this->request->post['unit'] = $value['unit'];
                    $this->request->post['weight'] = $value['weight'];
                    $this->request->post['product_price'] = $value['product_price'];

                    $new_variations_products_id[] = $this->model_catalog_general->addProduct($this->request->post);
                }

                foreach ($new_variations_products_id as $key => $value) {
                    $send = $new_variations_products_id;
                    unset($send[$key]);
                    $this->model_catalog_general->saveProductVariations($value, $send);
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_product_id_from'])) {
                $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_product_id_to'])) {
                $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
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
                $this->response->redirect($this->url->link('catalog/general/edit', 'product_id=' . $product_id . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/general/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/general', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('catalog/general');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/general');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_catalog_general->editProduct($this->request->get['product_id'], $this->request->post);

            $new_variations_products_id[] = $this->request->get['product_id'];

            if (isset($this->request->get['product_id'])) {
                $tmp = $this->model_catalog_general->newGetProductVariations($this->request->get['product_id']);

                //echo "<pre>";print_r($tmp);die;
                if (!$tmp) {
                    //$new_variations_products_id[] = $this->request->get['product_id'];
                } else {
                    $new_variations_products_id[] = $tmp[0]['product_id'];
                }
            }

            if (isset($this->request->post['product_variation']) && count($this->request->post['product_variation']) > 0) {
                foreach ($this->request->post['product_variation'] as $key => $value) {
                    $this->request->post['unit'] = $value['unit'];
                    $this->request->post['weight'] = $value['weight'];
                    $this->request->post['product_price'] = $value['product_price'];

                    $new_variations_products_id[] = $this->model_catalog_general->addProduct($this->request->post);
                }

                //echo "<pre>";print_r($new_variations_products_id);die;
                foreach ($new_variations_products_id as $key => $value) {
                    $send = $new_variations_products_id;
                    unset($send[$key]);
                    $this->model_catalog_general->saveProductVariations($value, $send);
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_product_id_from'])) {
                $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_product_id_to'])) {
                $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
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
                $this->response->redirect($this->url->link('catalog/general/edit', 'product_id=' . $this->request->get['product_id'] . '&token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/general/add', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/general', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('catalog/general');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/general');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_general->deleteProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_product_id_from'])) {
                $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
            }
            if (isset($this->request->get['filter_product_id_to'])) {
                $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
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

            $this->response->redirect($this->url->link('catalog/general', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function copy() {
        $this->load->language('catalog/general');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/general');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_general->copyProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_product_id_from'])) {
                $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_product_id_to'])) {
                $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
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

            $this->response->redirect($this->url->link('catalog/general', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
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

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_product_id_to'])) {
            $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('catalog/general/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['copy'] = $this->url->link('catalog/general/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['delete'] = $this->url->link('catalog/general/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['products'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_model' => $filter_model,
            'filter_product_id_from' => $filter_product_id_from,
            'filter_product_id_to' => $filter_product_id_to,
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

        $product_total = $this->model_catalog_general->getTotalProducts($filter_data);

        $results = $this->model_catalog_general->getProducts($filter_data);

        //echo "<pre>";print_r($results);die;
        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(0);

        foreach ($results as $result) {
            $category = $this->model_catalog_general->getProductCategories($result['product_id']);

            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
                $bigimage = $this->model_tool_image->getImage($result['image']);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
                $bigimage = $this->model_tool_image->getImage('no_image.png');
            }

            $data['products'][] = [
                'product_id' => $result['product_id'],
                'image' => $image,
                'bigimage' => $bigimage,
                'name' => $result['name'],
                'unit' => $result['unit'],
                'model' => $result['model'],
                'category' => $category,
                'status' => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit' => $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL'),
            ];
        }

        //echo "<pre>";print_r($data['products']);die;

        if ($this->user->isVendor()) {
            $data['is_vendor'] = 1;
        } else {
            $data['is_vendor'] = 0;
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_select_store'] = $this->language->get('text_select_store');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_id'] = $this->language->get('column_id');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_category'] = $this->language->get('column_category');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_unit'] = $this->language->get('column_unit');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_model'] = $this->language->get('entry_model');

        $data['entry_product_id_from'] = $this->language->get('entry_product_id_from');
        $data['entry_product_id_to'] = $this->language->get('entry_product_id_to');

        $data['entry_weight'] = $this->language->get('entry_weight');

        $data['entry_product_price'] = $this->language->get('entry_product_price');

        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_close'] = $this->language->get('button_close');
        $data['button_submit'] = $this->language->get('button_submit');
        $data['button_edit_variation'] = $this->language->get('button_edit_variation');
        $data['button_sell_selected'] = $this->language->get('button_sell_selected');
        $data['button_all_sell_selected'] = $this->language->get('button_all_sell_selected');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');
        $data['button_save_changes'] = $this->language->get('button_save_changes');

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

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_product_id_to'])) {
            $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
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

        $data['sort_name'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
        $data['sort_model'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');

        $data['sort_product_id'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . '&sort=p.product_id' . $url, 'SSL');

        $data['sort_category'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . '&sort=p2c.category' . $url, 'SSL');
        $data['sort_price'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
        $data['sort_quantity'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
        $data['sort_order'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_product_id_to'])) {
            $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
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

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
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

        //echo "<pre>";print_r($url);die;
        $pagination->url = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_model'] = $filter_model;
        $data['filter_product_id_from'] = $filter_product_id_from;
        $data['filter_product_id_to'] = $filter_product_id_to;

        $data['filter_price'] = $filter_price;
        $data['filter_category'] = $filter_category;
        $data['filter_quantity'] = $filter_quantity;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        //echo "<pre>";print_r($data);die;
        $this->response->setOutput($this->load->view('catalog/general_list.tpl', $data));
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

        /*
          My added
         */
        $data['entry_product'] = $this->language->get('entry_product_name');
        $data['product_unit'] = $this->language->get('product_unit');

        $data['product_weight'] = $this->language->get('product_weight');

        if ($this->user->isVendor()) {
            $data['is_vendor'] = 1;
        } else {
            $data['is_vendor'] = 0;
        }

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

        if (isset($this->error['product_price'])) {
            $data['error_product_price'] = $this->error['product_price'];
        } else {
            $data['error_product_price'] = '';
        }

        if (isset($this->error['unit'])) {
            $data['error_unit'] = $this->error['unit'];
        } else {
            $data['error_unit'] = '';
        }

        if (isset($this->error['weight'])) {
            $data['error_weight'] = $this->error['weight'];
        } else {
            $data['error_weight'] = '';
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

        if (isset($this->request->get['filter_product_id_from'])) {
            $url .= '&filter_product_id_from=' . urlencode(html_entity_decode($this->request->get['filter_product_id_from'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_product_id_to'])) {
            $url .= '&filter_product_id_to=' . urlencode(html_entity_decode($this->request->get['filter_product_id_to'], ENT_QUOTES, 'UTF-8'));
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
            'href' => $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . $url, 'SSL'),
        ];

        if (!isset($this->request->get['product_id'])) {
            $data['action'] = $this->url->link('catalog/general/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/general/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['product_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $product_info = $this->model_catalog_general->getProduct($this->request->get['product_id']);

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
            $data['product_description'] = $this->model_catalog_general->getProductDescriptions($this->request->get['product_id']);
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

        if (isset($this->request->post['product_types'])) {
            $data['product_types'] = $this->request->post['product_types'];
        } elseif (!empty($product_info)) {
            $data['product_types'] = explode(',', $product_info['produce_type']);
        } else {
            $data['product_types'] = '';
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

        if (isset($this->request->post['weight'])) {
            $data['weight'] = $this->request->post['weight'];
        } elseif (!empty($product_info)) {
            $data['weight'] = $product_info['weight'];
        } else {
            $data['weight'] = '';
        }

        if (isset($this->request->post['unit'])) {
            $data['unit'] = $this->request->post['unit'];
        } elseif (!empty($product_info)) {
            $data['unit'] = $product_info['unit'];
        } else {
            $data['unit'] = '';
        }

        if (isset($this->request->post['product_price'])) {
            $data['product_price'] = $this->request->post['product_price'];
        } elseif (!empty($product_info)) {
            $data['product_price'] = $product_info['default_price'];
        } else {
            $data['product_price'] = '';
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['product_store'])) {
            $data['product_store'] = $this->request->post['product_store'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_store'] = $this->model_catalog_general->getProductStores($this->request->get['product_id']);
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

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($product_info)) {
            $data['sort_order'] = $product_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($product_info)) {
            $data['status'] = $product_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['default_variation_name'])) {
            $data['default_variation_name'] = $this->request->post['default_variation_name'];
        } elseif (!empty($product_info)) {
            $data['default_variation_name'] = $product_info['default_variation_name'];
        } else {
            $data['default_variation_name'] = '';
        }

        // Categories
        $this->load->model('catalog/category');

        if (isset($this->request->post['product_category'])) {
            $categories = $this->request->post['product_category'];
        } elseif (isset($this->request->get['product_id'])) {
            $categories = $this->model_catalog_general->getProductCategories($this->request->get['product_id']);
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
            $filters = $this->model_catalog_general->getProductFilters($this->request->get['product_id']);
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
        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if (isset($this->request->post['product_special'])) {
            $product_specials = $this->request->post['product_special'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_specials = $this->model_catalog_general->getProductSpecials($this->request->get['product_id']);
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

        if (isset($this->request->get['product_id'])) {
            $product_variations = $this->model_catalog_general->newGetProductVariations($this->request->get['product_id']);

            if (!$product_variations) {
                $product_variations = [];
            }
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
                'sort_order' => $product_variation['sort_order'],
                'product_id' => $product_variation['product_id'],
                'model' => $product_variation['model'],
                'weight' => $product_variation['weight'],
                'default_price' => $product_variation['default_price'],
                'unit' => $product_variation['unit'],
                    //'id' => $product_variation['id'],
            ];
        }

        //echo "<pre>";print_r($data['product_variations']);die;
        // Images
        if (isset($this->request->post['product_image'])) {
            $product_images = $this->request->post['product_image'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_images = $this->model_catalog_general->getProductImages($this->request->get['product_id']);
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
                'product_image_id' => $product_image['product_image_id'],
                    //'product_id' => $product_image['product_id'],
            ];
        }
        //end
        // new variation tab
        if (isset($this->request->get['product_id'])) {
            if (!empty($product_info) && trim($product_info['variations_id'])) {
                $products = explode(',', $product_info['variations_id']);
            } else {
                $products = [];
            }
        } else {
            $products = [];
        }

        $this->load->model('catalog/product');

        $data['variation_product'] = [];

        foreach ($products as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            //echo "<pre>";print_r($product_info);die;
            if ($product_info && isset($product_info['product_id'])) {
                $data['variation_product'][] = [
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name'],
                ];
            }
        }
        // end
        // Text Editor
        $data['text_editor'] = $this->config->get('config_text_editor');

        if (empty($data['text_editor'])) {
            $data['text_editor'] = 'tinymce';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('catalog/general_form.tpl', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/general') || $this->user->isVendor()) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['product_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
            /* if ( ( utf8_strlen( $value['unit'] ) < 2 ) || ( utf8_strlen( $value['unit'] ) > 100 ) ) {
              $this->error['unit'] =  $this->language->get( 'error_unit' );
              } */
        }

        if ((utf8_strlen($this->request->post['unit']) < 1) || (utf8_strlen($this->request->post['unit']) > 100)) {
            $this->error['unit'] = $this->language->get('error_unit');
        }

        /* if ( ( utf8_strlen( $this->request->post['model'] ) <> 13 ) ) {
          $this->error['model'] = $this->language->get( 'error_model' );
          } */

        if ((utf8_strlen($this->request->post['product_price']) < 1) || (utf8_strlen($this->request->post['product_price']) > 64)) {
            $this->error['product_price'] = $this->language->get('error_product_price');
        }

        $this->load->model('catalog/url_alias');

        // foreach ( $this->request->post['seo_url'] as $language_id => $value ) {
        // 	$url_alias_info = $this->model_catalog_url_alias->getUrlAlias( $value, $language_id );
        // 	if ( $url_alias_info && isset( $this->request->get['product_id'] ) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id'] ) {
        // 		$this->error['seo_url'][$language_id] = sprintf( $this->language->get( 'error_seo_url' ) );
        // 	}
        // 	if ( $url_alias_info && !isset( $this->request->get['product_id'] ) ) {
        // 		$this->error['seo_url'][$language_id] = sprintf( $this->language->get( 'error_seo_url' ) );
        // 	}
        // }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if ($this->request->post['image'] == null || $this->request->post['image'] == "") {
            $this->error['warning'] = $this->error['warning'] . ' Please upload image .';
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/general') || $this->user->isVendor()) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCopy() {
        if (!$this->user->hasPermission('modify', 'catalog/general')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
            $this->load->model('catalog/general');
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

            $results = $this->model_catalog_general->getProducts($filter_data);

            foreach ($results as $result) {
                $json[] = [
                    'product_id' => $result['product_id'],
                    'default_variation_name' => strip_tags(html_entity_decode($result['default_variation_name'], ENT_QUOTES, 'UTF-8')),
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model' => $result['model'],
                    'option' => [],
                    'variations' => $this->model_catalog_general->getProductVariations($result['product_id']),
                    'price' => $result['price'],
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

        $this->load->model('catalog/general');

        if (isset($this->request->get['selected'])) {
            foreach ($this->request->get['selected'] as $product_id) {
                $this->model_catalog_general->updateProduct($this->user->getId(), $product_id);
            }
        }
        echo 0;
    }

    public function getVariationData() {
        $variation_id = $this->request->get['variation_id'];
        $product_id = $this->request->get['product_id'];
        $this->load->model('catalog/general');
        $results = $this->model_catalog_general->getVariations($variation_id, $product_id);
        $this->load->model('tool/image');
        if (is_file(DIR_IMAGE . $results['image'])) {
            $thumb = $results['image'];
        } else {
            $thumb = 'no_image.png';
        }

        $json = [
            'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
            'product_id' => $results['product_id'],
            'variation_id' => $results['id'],
            'name' => $results['name'],
            'sort_order' => $results['sort_order'],
            'img' => $results['image'],
            'model' => $results['model'],
        ];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateVariation() {
        if (!$this->user->hasPermission('modify', 'catalog/general') || $this->user->isVendor()) {
            $this->error['warning'] = $this->language->get('error_permission');
        } else {
            $this->load->model('catalog/general');
            $json = $this->model_catalog_general->updateVariations($this->request->post);
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function deleteVariation() {
        if (!$this->user->hasPermission('modify', 'catalog/general') || $this->user->isVendor()) {
            $this->error['warning'] = $this->language->get('error_permission');
        } else {
            $variation_id = $this->request->get['variation_id'];
            $this->load->model('catalog/general');
            //echo "rth";
            $json = $this->model_catalog_general->newDeleteVariations($variation_id);
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function deleteImage() {
        if (!$this->user->hasPermission('modify', 'catalog/general') || $this->user->isVendor()) {
            $this->error['warning'] = $this->language->get('error_permission');
        } else {
            $product_image_id = $this->request->get['product_image_id'];
            $this->load->model('catalog/general');
            $json = $this->model_catalog_general->deleteImage($product_image_id);
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
    }

    public function convertProductImage() {
        $log = new Log('error.log');
        $filter_data = [];
        $this->load->model('catalog/general');
        $results = $this->model_catalog_general->getProducts($filter_data);
        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $log->write(DIR_IMAGE . $result['image']);
                $image_array = explode('.', $result['image']);
                if (is_array($image_array)) {
                    $log->write(end($image_array));
                    $image_extension = end($image_array);
                    if ($image_extension = 'jpg' || $image_extension = 'jpeg') {
                        $destination = DIR_IMAGE . 'data/Asian Vegetable/';
                        $this->convertImageToWebP(DIR_IMAGE . $result['image'], $destination);
                        exit;
                    }

                    if ($image_extension = 'png') {
                        $destination = DIR_IMAGE . 'data/Asian Vegetable/';
                        $this->convertImageToWebP(DIR_IMAGE . $result['image'], $destination);
                        exit;
                    }
                }
            }
        }
    }

    function convertImageToWebP($source, $destination, $quality = 100) {
        try {
            $log = new Log('error.log');
            $extension = pathinfo($source, PATHINFO_EXTENSION);
            if ($extension == 'jpeg' || $extension == 'jpg') {
                $log->write($extension);
                $image = imagecreatefromjpeg($source);
            } elseif ($extension == 'gif') {
                $log->write($extension);
                $image = imagecreatefromgif($source);
            } elseif ($extension == 'png') {
                $log->write($extension);
                $image = imagecreatefrompng($source);
            }
            return imagewebp($image, $destination, $quality);
        } catch (Exception $e) {
            $log = new Log('error.log');
            $log->write($e);
        }
    }

}
