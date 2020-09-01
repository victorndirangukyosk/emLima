<?php

class ControllerApiOffers extends Controller
{
    private $error = [];

    public function getOffers($args = [])
    {
        $this->load->language('api/general');
        $this->load->model('api/offer');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        //echo "<pre>";print_r($this->session->data['api_id']);die;
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = null;
            }

            if (isset($this->request->get['filter_date_start'])) {
                $filter_date_start = $this->request->get['filter_date_start'];
            } else {
                $filter_date_start = null;
            }

            if (isset($this->request->get['filter_date_end'])) {
                $filter_date_end = $this->request->get['filter_date_end'];
            } else {
                $filter_date_end = null;
            }

            if (isset($this->request->get['filter_store'])) {
                $filter_store = $this->request->get['filter_store'];
            } else {
                $filter_store = null;
            }

            if (isset($this->request->get['store_id'])) {
                $filter_store_id = $this->request->get['store_id'];
            } else {
                $filter_store_id = null;
            }

            if (isset($this->request->get['filter_status'])) {
                $filter_status = $this->request->get['filter_status'];
            } else {
                $filter_status = null;
            }

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
            } else {
                $sort = 'name';
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

            if (isset($this->request->get['store_id'])) {
                $data['filter_store_id'] = $this->request->get['store_id'];
            } elseif (!empty($offer_info)) {
                $data['filter_store_id'] = $offer_info['filter_store_id']; //get filter_store_idname by id
            } else {
                $data['filter_store_id'] = '';
            }

            $filter_data = [
                'filter_name' => $filter_name,
                'filter_date_start' => $filter_date_start,
                'filter_date_end' => $filter_date_end,
                'filter_store' => $filter_store_id,
                'filter_status' => $filter_status,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                'limit' => $this->config->get('config_limit_admin'),
            ];

            if (!empty($filter_name) || !empty($filter_date_start) || !empty($filter_date_end) || !empty($filter_store) || !empty($filter_status) || !empty($filter_store_id)) {
                $offer_total = $this->model_api_offer->getTotalOffersFilter($filter_data);
            } else {
                $offer_total = $this->model_api_offer->getTotalOffers();
            }

            //echo "<pre>";print_r($filter_data);die;
            $results = $this->model_api_offer->getOffers($filter_data);

            //echo "<pre>";print_r($results);die;
            foreach ($results as $result) {
                $products = $this->model_api_offer->getOfferProducts($result['offer_id']);

                $data['offers'][] = [
                    'offer_id' => $result['offer_id'],
                    'name' => $result['name'],
                    'store_name' => $result['store_name'],
                    'discount' => $result['discount'],
                    'total_offer_product' => count($products),
                    'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
                    'date_end' => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
                    'status' => ($result['status'] ? 'Enabled' : 'Disabled'),
                ];
            }

            $pagination = new Pagination();
            $pagination->total = $offer_total;
            $pagination->page = $page;
            $pagination->limit = $this->config->get('config_limit_admin');

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($offer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($offer_total - $this->config->get('config_limit_admin'))) ? $offer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $offer_total, ceil($offer_total / $this->config->get('config_limit_admin')));

            $json['data'] = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getOffer($args = [])
    {
        $this->load->language('api/orders');
        $this->load->model('api/offer');
        $this->load->model('api/products');
        $this->load->model('tool/image');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        //echo "<pre>";print_r($this->session->data['api_id']);die;
        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->get['offer_id'])) {
                $data['offer_id'] = $this->request->get['offer_id'];
            } else {
                $data['offer_id'] = 0;
            }

            if (isset($this->request->get['offer_id']) && ('POST' != !$this->request->server['REQUEST_METHOD'])) {
                $offer_info = $this->model_api_offer->getOffer($this->request->get['offer_id']);
            }

            $products = $this->model_api_offer->getOfferProducts($this->request->get['offer_id']);

            //echo "<pre>";print_r($offer_info);die;

            $data['offer_products'] = [];

            foreach ($products as $product_id) {
                $product_info = $this->model_api_offer->getProduct($product_id);

                $product = $this->model_api_products->getProduct($product_id, $this->request->get['store_id']);

                //print_r($product);die;
                //echo ("product");

                $product['model'] = $product_info['model'];
                $product['unit'] = $product_info['unit'];

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

                $images = [];
                $product['images'] = [];

                $thumb_width = $this->config->get('config_image_thumb_width', 300);
                $thumb_height = $this->config->get('config_image_thumb_height', 300);

                if (!empty($product['image'])) {
                    $images[] = $this->model_tool_image->resize($product['image'], $thumb_width, $thumb_height);
                } else {
                    $images[] = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);
                }
                unset($product['image']);

                //$extra_images = $this->model_catalog_product->getProductImages($product['product_id']);
                $extra_images = $this->model_api_products->getProductImages($product['product_id']);
                if (!empty($extra_images)) {
                    foreach ($extra_images as $extra_image) {
                        $images[] = $this->model_tool_image->resize($extra_image['image'], $thumb_width, $thumb_height);

                        //$product['images'][] = $this->model_tool_image->resize($extra_image['image'], $thumb_width, $thumb_height);
                    }
                }

                //echo "<pre>";print_r($images);die;
                foreach ($images as $image) {
                    $product['images'][] = $image;
                    /*if ($this->request->server['HTTPS']) {
                        $product['images'][] = str_replace($this->config->get('config_ssl'), '', $image);
                    } else {
                        $product['images'][] = str_replace($this->config->get('config_url'), '', $image);
                    }*/
                }

                $data['offer_products'][] = $product;
                /*
                if ($product_info) {
                    $data['offer_products'][] = array(
                        'product_id' => $product_info['product_id'],
                        'name'       => $product_info['name'],
                    );
                }*/
            }
            //echo "<pre>";print_r($data['offer_product']);die;

            if (isset($this->request->post['date_start'])) {
                $data['date_start'] = $this->request->post['date_start'];
            } elseif (!empty($offer_info)) {
                $data['date_start'] = ('0000-00-00' != $offer_info['date_start'] ? $offer_info['date_start'] : '');
            } else {
                $data['date_start'] = date('Y-m-d', time());
            }

            if (isset($this->request->post['name'])) {
                $data['name'] = $this->request->post['name'];
            } elseif (!empty($offer_info)) {
                $data['name'] = $offer_info['name'];
            } else {
                $data['name'] = true;
            }

            if (isset($this->request->post['discount'])) {
                $data['discount'] = $this->request->post['discount'];
            } elseif (!empty($offer_info)) {
                $data['discount'] = $offer_info['discount'];
            } else {
                $data['discount'] = true;
            }

            if (isset($this->request->post['date_end'])) {
                $data['date_end'] = $this->request->post['date_end'];
            } elseif (!empty($offer_info)) {
                $data['date_end'] = ('0000-00-00' != $offer_info['date_end'] ? $offer_info['date_end'] : '');
            } else {
                $data['date_end'] = date('Y-m-d', strtotime('+1 month'));
            }

            if (isset($this->request->post['status'])) {
                $data['status'] = $this->request->post['status'];
            } elseif (!empty($offer_info)) {
                $data['status'] = $offer_info['status'];
            } else {
                $data['status'] = true;
            }

            $json['data'] = $data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editOffer($args = [])
    {
        $log = new Log('error.log');
        $log->write('editOffer  api');
        $log->write($args);
        /*$log->write($this->request->post);
        $log->write($this->request->put);*/

        //echo "<pre>";print_r("editOffer");die;
        $this->load->language('api/general');
        $this->load->model('api/offer');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->request->post = $args;

        $log->write($this->request->post);

        /*   Array
        (
            [name] => Refer & Earn
            [store_name] => Rainbow Grocery
            [store_id] => 8
            [product] =>
            [offer_product] => Array
                (
                    [0] => 762
                    [1] => 2128
                    [2] => 2178
                    [3] => 2390
                    [4] => 2432
                    [5] => 1580
                    [6] => 2427
                )

            [date_start] => 2017-05-24
            [date_end] => 2017-11-03
            [status] => 1
            [button] => save
        )*/
        //echo "<pre>";print_r($this->request->post);die;
        if (!isset($this->session->data['api_id']) && isset($this->request->get['offer_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->model_api_offer->editOffer($this->request->get['offer_id'], $this->request->post);

            $json['message'] = $this->language->get('text_edited_successfully');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteOfferProduct($args = [])
    {
        //echo "<pre>";print_r("deleteOfferProduct");die;

        //product_id = product table id
        $this->load->language('api/general');
        $this->load->model('api/offer');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->request->post = $args;

        //echo "<pre>";print_r($this->request->get);die;
        if (!isset($this->session->data['api_id']) && isset($this->request->get['offer_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->model_api_offer->editDeleteOfferProduct($this->request->get['offer_id'], $this->request->get['product_id']);

            $json['message'] = $this->language->get('text_deleted');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteOfferDelete($args = [])
    {
        //echo "<pre>";print_r("deleteOfferDelete");die;

        //product_id = product table id
        $this->load->language('api/general');
        $this->load->model('api/offer');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->request->post = $args;

        //echo "<pre>";print_r($this->request->get);die;
        if (!isset($this->session->data['api_id']) && isset($this->request->get['offer_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->model_api_offer->deleteOffer($this->request->get['offer_id']);

            $json['message'] = $this->language->get('text_deleted');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOfferProduct($args = [])
    {
        //echo "<pre>";print_r("addOfferProduct");die;

        //product_id = product table id
        $this->load->language('api/general');
        $this->load->model('api/offer');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->request->post = $args;

        //echo "<pre>";print_r($this->request->post);die;
        if (!isset($this->session->data['api_id']) || !isset($this->request->get['offer_id']) || !isset($this->request->post['product_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->model_api_offer->addOfferProducts($this->request->get['offer_id'], $args['product_id']);

            $json['message'] = $this->language->get('text_added_successfully');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addOfferCreate($args = [])
    {
        //echo "<pre>";print_r("addOfferCreate");die;

        //product_id = product table id
        $this->load->language('api/general');
        $this->load->model('api/offer');

        $json['status'] = 200;
        $json['data'] = [];
        $json['message'] = [];

        $this->request->post = $args;

        //echo "<pre>";print_r($this->request->post);die;
        if (!isset($this->session->data['api_id']) || !isset($args['name']) || !isset($args['date_start']) || !isset($args['date_end']) || !isset($args['status'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            //echo "<pre>";print_r($args);die;
            $this->model_api_offer->addOffer($args);

            $json['message'] = $this->language->get('text_added_successfully');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
