<?php

class ControllerApprovalsProduct extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('approvals/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('approvals/product');
        $this->getList();
    }

    public function approve()
    {
        $this->load->language('approvals/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('approvals/product');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_approvals_product->approveProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model='.urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price='.$this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity='.$this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->response->redirect($this->url->link('approvals/product', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    public function delete()
    {
        $this->load->language('approvals/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('approvals/product');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_approvals_product->deleteProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model='.urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price='.$this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity='.$this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status='.$this->request->get['filter_status'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->response->redirect($this->url->link('approvals/product', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        $this->load->language('approvals/product');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
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
        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.$this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['delete'] = $this->url->link('approvals/product/delete', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['approve'] = $this->url->link('approvals/product/apporve', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('approvals/product', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['stores'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $this->load->model('tool/image');

        $store_total = $this->model_approvals_product->getTotalProductsStore($filter_data);

        $results = $this->model_approvals_product->getProductsStore($filter_data);

        foreach ($results as $result) {
            $total_product = $this->model_approvals_product->getTotalProductsByStore($result['s_id']);

            $data['stores'][] = [
                'store_id' => $result['s_id'],
                'name' => $result['name'],
                'address' => $result['address'],
                'status' => $result['status'],
                'total_product' => $total_product,
                'list' => $this->url->link('approvals/product/product_list', 'token='.$this->session->data['token'].'&store_id='.$result['s_id'].$url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_store_list'] = $this->language->get('text_store_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_view'] = $this->language->get('button_view');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_address'] = $this->language->get('column_address');
        $data['column_total_products'] = $this->language->get('column_total_products');
        $data['column_category'] = $this->language->get('column_category');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['text_no_results'] = $this->language->get('text_no_results');
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

        $pagination = new Pagination();
        $pagination->total = $store_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('approvals/product', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
        $data['results'] = sprintf($this->language->get('text_pagination'), ($store_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($store_total - $this->config->get('config_limit_admin'))) ? $store_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $store_total, ceil($store_total / $this->config->get('config_limit_admin')));

        $data['pagination'] = $pagination->render();

        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('approvals/store_list.tpl', $data));
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
            $this->load->model('approvals/product');
            $this->load->model('catalog/option');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
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
                'filter_model' => $filter_model,
                'start' => 0,
                'limit' => $limit,
            ];

            $results = $this->model_approvals_product->getProducts($filter_data);

            foreach ($results as $result) {
                $option_data = [];

                $product_options = $this->model_approvals_product->getProductOptions($result['product_id']);

                foreach ($product_options as $product_option) {
                    $option_info = $this->model_catalog_option->getOption($product_option['option_id']);

                    if ($option_info) {
                        $product_option_value_data = [];

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

                            if ($option_value_info) {
                                $product_option_value_data[] = [
                                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                                    'option_value_id' => $product_option_value['option_value_id'],
                                    'name' => $option_value_info['name'],
                                    'price' => (float) $product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
                                    'price_prefix' => $product_option_value['price_prefix'],
                                ];
                            }
                        }

                        $option_data[] = [
                            'product_option_id' => $product_option['product_option_id'],
                            'product_option_value' => $product_option_value_data,
                            'option_id' => $product_option['option_id'],
                            'name' => $option_info['name'],
                            'type' => $option_info['type'],
                            'value' => $product_option['value'],
                            'required' => $product_option['required'],
                        ];
                    }
                }

                $json[] = [
                    'product_id' => $result['product_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model' => $result['model'],
                    'option' => $option_data,
                    'price' => $result['price'],
                ];
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function product_list()
    {
        $this->load->language('approvals/product');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
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
        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = [];
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name='.urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status='.$this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }
        $this->load->model('catalog/category');
        $this->load->model('approvals/product');

        $data['categories'] = $this->model_catalog_category->getCategories(0);

        $filter_data = [
            'filter_store_id' => $this->request->get['store_id'],
            'filter_name' => $filter_name,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $this->load->model('tool/image');

        $product_total = $this->model_approvals_product->getTotalProducts($filter_data);

        $results = $this->model_approvals_product->getProducts($filter_data);

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(0);
        $data['products'] = [];

        foreach ($results as $result) {
            $category = $this->model_approvals_product->getProductCategories($result['product_id']);

            if (is_file(DIR_IMAGE.$result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            $special = false;

            $data['products'][] = [
                'product_id' => $result['product_id'],
                'product_store_id' => $result['product_store_id'],
                'image' => $image,
                'name' => $result['name'],
                'model' => $result['model'],
                'price' => $result['price'],
                'quantity' => $result['quantity'],
                'category' => $category,
                'status' => ($result['sts']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'), ];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('approvals/product/product_list', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['pagination'] = $pagination->render();

        $data['heading_title'] = $this->language->get('heading_product_title');

        $data['text_list'] = $this->language->get('text_list');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_product_name'] = $this->language->get('entry_product_name');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['column_category'] = $this->language->get('column_category');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_product_name');
        $data['button_enable'] = $this->language->get('button_enable');
        $data['token'] = $this->session->data['token'];
        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;

        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('approvals/product_list.tpl', $data));
    }
}
