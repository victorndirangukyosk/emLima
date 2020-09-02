<?php

class ControllerProductRecipe extends Controller
{
    public function index()
    {
        $this->load->language('product/recipe');

        $this->load->model('assets/recipe');

        $this->load->model('tool/image');

        $data['heading_title'] = $title = $this->language->get('heading_title');

        $this->document->setTitle($title);
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/layout_categories.css');
        $this->document->addStyle('front/ui/theme/'.$this->config->get('config_template').'/stylesheet/recipe.css?v=1');
        $this->document->addScript('front/ui/theme/'.$this->config->get('config_template').'/javascript/recipe.js');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_product_limit');
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
        ];

        // Set the last category breadcrumb
        $data['breadcrumbs'][] = [
            'text' => $title,
            'href' => $this->url->link('product/recipe'),
        ];

        $data['recipes'] = [];

        if (isset($this->request->get['category_id'])) {
            $data['category_id'] = $this->request->get['category_id'];
        } else {
            $data['category_id'] = 0;
        }

        $filter_data = [
            'filter_category' => $data['category_id'],
            'start' => ($page - 1) * $limit,
            'limit' => $limit,
        ];

        $data['categories'] = $this->model_assets_recipe->getCategories();

        if ($data['category_id']) {
            $results = $this->model_assets_recipe->getRecipes($filter_data);
        } else {
            $results = $this->model_assets_recipe->getPopularRecipes($filter_data);
        }

        //echo "<pre>";print_r($results);die;
        foreach ($results as $result) {
            if ($result['image']) {
                $thumb = $this->model_tool_image->resize($result['image'], 200, 70);
            } else {
                $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
            }
            $data['recipes'][] = [
                'recipe_id' => $result['recipe_id'],
                'title' => $result['title'],
                'author' => $result['author'],
                'video' => $result['video'],
                //'thumb' => 'image/' . $result['image'],
                'thumb' => $thumb, ];
        }

        $total = $this->model_assets_recipe->getTotalRecipes($filter_data);

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_popular'] = $this->language->get('text_popular');

        $pagination = new Pagination();
        $pagination->total = (int) $total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('product/recipe', 'category_id=0&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $data['base'] = $server;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/product/recipe.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/product/recipe.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/recipe.tpl', $data));
        }
    }

    public function view()
    {
        $this->load->language('product/recipe');

        $this->load->model('assets/recipe');
        $this->load->model('assets/product');

        $this->load->model('tool/image');

        if (isset($this->request->get['recipe_id'])) {
            $recipe_id = $this->request->get['recipe_id'];
        } else {
            $recipe_id = 0;
        }

        //recipe
        $data['recipe'] = $this->model_assets_recipe->getRecipe($recipe_id);

        //ingradient products
        $products = $this->model_assets_recipe->getProducts($recipe_id);

        $data['products'] = [];

        foreach ($products as $product) {
            if ($product['image']) {
                $thumb = $this->model_tool_image->resize($product['image'], 100, 100);
            } else {
                $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            //print_r($product);die;
            //items to bye
            $items = [];

            if (isset($this->session->data['config_store_id'])) {
                //9
                $dataFilter = [
                   'filter_name' => $product['model'],
                   'filter_tag' => $product['model'],
                   'start' => 0,
                   'limit' => 5,
                ];

                $temp = $this->model_assets_product->getProducts($dataFilter);
                //echo "<pre>";print_r($temp);die;

                foreach ($temp as $item) {
                    if ($item['image']) {
                        $item['thumb'] = $this->model_tool_image->resize($item['image'], 100, 100);
                    } else {
                        $item['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                    }

                    // print_r($item);die;

                    //get qty in cart
                    $key = base64_encode(serialize(['product_store_id' => (int) $item['product_store_id'], 'store_id' => $this->session->data['config_store_id']]));

                    if (isset($this->session->data['cart'][$key])) {
                        $item['qty_in_cart'] = $this->session->data['cart'][$key]['quantity'];
                        $item['key'] = $key;
                    } else {
                        $item['key'] = '';
                        $item['qty_in_cart'] = 0;
                    }

                    $items[] = $item;
                }
            }

            $data['products'][] = [
                'thumb' => $thumb,
                'name' => $product['name'],
                'model' => $product['model'],
                'quantity' => $product['quantity'],
                'items' => $items, ];
        }

        //echo "<pre>";print_r($data);die;
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/product/recipe_view.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/product/recipe_view.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/recipe_view.tpl', $data));
        }
    }
}
