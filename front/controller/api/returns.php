<?php

class ControllerApiReturns extends Controller
{
    public function getReturns($args = [])
    {
        $this->load->language('api/orders');
        $this->load->model('api/return');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        //echo "<pre>";print_r($this->session->data['api_id']);die;
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->get['filter_return_id'])) {
                $filter_return_id = $this->request->get['filter_return_id'];
            } else {
                $filter_return_id = null;
            }

            if (isset($this->request->get['filter_order_id'])) {
                $filter_order_id = $this->request->get['filter_order_id'];
            } else {
                $filter_order_id = null;
            }

            if (isset($this->request->get['store_id'])) {
                $filter_store_id = $this->request->get['store_id'];
            } else {
                $filter_store_id = null;
            }

            if (isset($this->request->get['filter_customer'])) {
                $filter_customer = $this->request->get['filter_customer'];
            } else {
                $filter_customer = null;
            }

            if (isset($this->request->get['filter_product'])) {
                $filter_product = $this->request->get['filter_product'];
            } else {
                $filter_product = null;
            }

            if (isset($this->request->get['filter_unit'])) {
                $filter_unit = $this->request->get['filter_unit'];
            } else {
                $filter_unit = null;
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = null;
            }

            if (isset($this->request->get['filter_store'])) {
                $filter_store = $this->request->get['filter_store'];
            } else {
                $filter_store = null;
            }

            if (isset($this->request->get['filter_return_status_id'])) {
                $filter_return_status_id = $this->request->get['filter_return_status_id'];
            } else {
                $filter_return_status_id = null;
            }

            if (isset($this->request->get['filter_date_added'])) {
                $filter_date_added = $this->request->get['filter_date_added'];
            } else {
                $filter_date_added = null;
            }

            if (isset($this->request->get['filter_date_modified'])) {
                $filter_date_modified = $this->request->get['filter_date_modified'];
            } else {
                $filter_date_modified = null;
            }

            if (isset($this->request->get['date_from'])) {
                $date_from = $this->request->get['date_from'];
            } else {
                $date_from = null;
            }

            if (isset($this->request->get['date_to'])) {
                $date_to = $this->request->get['date_to'];
            } else {
                $date_to = null;
            }

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'r.order_id';
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

            $data['returns'] = [];

            $filter_data = [
                'filter_return_id' => $filter_return_id,
                'filter_order_id' => $filter_order_id,
                'filter_store_id' => $filter_store_id,
                'filter_customer' => $filter_customer,
                'filter_product' => $filter_product,
                'filter_unit' => $filter_unit,
                'filter_model' => $filter_model,
                'filter_store' => $filter_store_id,
                'filter_return_status_id' => $filter_return_status_id,
                'filter_date_added' => $filter_date_added,
                'filter_date_modified' => $filter_date_modified,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                'limit' => $this->config->get('config_limit_admin'),
            ];

            //echo "<pre>";print_r($filter_data);die;
            $return_total = $this->model_api_return->getTotalReturns($filter_data);

            $results = $this->model_api_return->getReturns($filter_data);

            //echo "<pre>";print_r($results);die;

            foreach ($results as $result) {
                if ($result['store_id'] != $filter_store_id) {
                    continue;
                }

                $data['returns'][] = [
                    'return_id' => $result['return_id'],
                    'order_id' => $result['order_id'],
                    'customer' => $result['customer'],
                    'product' => html_entity_decode($result['product'], ENT_QUOTES, 'UTF-8'),
                    'unit' => $result['unit'],
                    'quantity' => $result['quantity'],
                    'price' => $result['price'],
                    'total_price' => ($result['price'] * $result['quantity']),
                    'model' => $result['model'],
                    'store_id' => $result['store_id'],
                    'store_name' => $this->model_api_return->getStore($result['store_id']),
                    'status' => $result['status'],
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                ];
            }

            //echo "<pre>";print_r($data['returns']);die;

            $pagination = new Pagination();
            $pagination->total = $return_total;
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_limit_admin');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($return_total - $this->config->get('config_limit_admin'))) ? $return_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $return_total, ceil($return_total / $this->config->get('config_limit_admin')));

            $data['return_total'] = $return_total;
            $data['return_statuses'] = $this->model_api_return->getReturnStatuses();

            $json['data'] = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getReturnDetails($args = [])
    {
        //echo "<pre>";print_r("getReturnDetails");die;

        $log = new Log('error.log');

        $log->write('inside getReturnDetails');

        $this->load->language('api/orders');
        $this->load->model('api/return');
        $this->load->model('api/offer');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        //echo "<pre>";print_r($this->session->data['api_id']);die;
        if (!isset($this->session->data['api_id']) && isset($this->request->get['return_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $return_info = [];

            if (isset($this->request->get['return_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
                $return_info = $this->model_api_return->getReturn($this->request->get['return_id']);
            }

            $data = $return_info;

            //echo "<pre>";print_r($return_info);die;
            if (isset($this->request->post['store'])) {
                $data['store'] = $this->request->post['store'];
            } elseif (!empty($return_info)) {
                $data['store'] = $this->model_api_return->getStore($return_info['store_id']);
            } else {
                $data['store'] = '';
            }

            if (isset($data['price'])) {
                $data['total_price'] = $this->currency->format(($data['price'] * $data['quantity']), $this->config->get('config_currency'), false);

                $data['price'] = $this->currency->format($data['price'], $this->config->get('config_currency'), false);
            } else {
                $data['total_price'] = '';
            }

            if (isset($data['date_ordered'])) {
                $data['date_ordered'] = date($this->language->get('date_format_short'), strtotime($data['date_ordered']));
            }

            if (isset($data['date_added'])) {
                $data['date_added'] = date($this->language->get('date_format_short'), strtotime($data['date_added']));
            }

            if (isset($data['date_modified'])) {
                $data['date_modified'] = date($this->language->get('date_format_short'), strtotime($data['date_modified']));
            }

            $data['return_reasons'] = $this->model_api_return->getReturnReasons();

            $data['return_actions'] = $this->model_api_return->getReturnActions();

            $data['return_statuses'] = $this->model_api_return->getReturnStatuses();

            $data['histories'] = [];

            $results = $this->model_api_return->getReturnHistories($this->request->get['return_id'], 0, 10);

            foreach ($results as $result) {
                $data['histories'][] = [
                    'notify' => $result['notify'] ? 'Yes' : 'No',
                    'status' => $result['status'],
                    'comment' => nl2br($result['comment']),
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                ];
            }

            $data['history_total'] = $this->model_api_return->getTotalReturnHistories($this->request->get['return_id']);

            $this->load->model('assets/product');
            $this->load->model('tool/image');

            $log->write($data['store_id'].'p_id'.$data['product_id']);

            $product = $this->model_assets_product->getProductForPopupByApi($data['store_id'], $data['product_id']);

            $log->write($product);

            //echo ("product");
            if ($product) {
                $product['model'] = $product['model'];

                $product['name'] = html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8');

                $product['description'] = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');

                $currency_value = false;

                if (isset($args['currency_code'])) {
                    $currency_code = $args['currency_code'];
                } else {
                    $currency_code = $this->config->get('config_currency');
                }

                $product['nice_price'] = $this->currency->format($product['price'], $currency_code, $currency_value);

                if (0 == $product['special_price'] || 0.00 == $product['special_price'] || is_null($product['special_price'])) {
                    $product['special_price'] = $product['price'];
                    $product['nice_special_price'] = $this->currency->format($product['special_price'], $currency_code, $currency_value);
                } else {
                    $product['nice_special_price'] = $this->currency->format($product['special_price'], $currency_code, $currency_value);
                }

                $thumb_width = $this->config->get('config_image_thumb_width', 300);
                $thumb_height = $this->config->get('config_image_thumb_height', 300);

                $thumb_zoomwidth = $this->config->get('config_zoomimage_thumb_width', 600);
                $thumb_zoomheight = $this->config->get('config_zoomimage_thumb_height', 600);
                $tmpImg = $product['image'];

                if (!empty($product['image'])) {
                    $product['images'] = $this->model_tool_image->resize($product['image'], $thumb_width, $thumb_height);

                    $product['zoom_images'] = $this->model_tool_image->resize($tmpImg, $thumb_zoomwidth, $thumb_zoomheight);
                } else {
                    $product['images'] = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);

                    $product['zoom_images'] = $this->model_tool_image->resize('placeholder.png', $thumb_zoomwidth, $thumb_zoomheight);
                }

                //unset($product['image']);
            }

            //$log->write($product);

            $data['product'] = html_entity_decode($data['product'], ENT_QUOTES, 'UTF-8');

            $data['product_details'] = $product;

            $json['data'] = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editReturn($args = [])
    {
        //echo "<pre>";print_r("editReturn");die;
        $this->load->language('api/general');
        $this->load->model('api/return');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $args['notify'] = 0;

        $this->request->post = $args;

        //echo "<pre>";print_r($this->session->data['api_id']);die;
        if (!isset($this->session->data['api_id']) || !isset($this->request->get['return_id']) || !isset($args['return_action_id']) || !isset($args['comment'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            //return_status_id required by return histry

            $this->model_api_return->editReturn($this->request->get['return_id'], $this->request->post);

            $this->model_api_return->addReturnHistory($this->request->get['return_id'], $this->request->post);

            $json['message'] = $this->language->get('text_edited_successfully');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
