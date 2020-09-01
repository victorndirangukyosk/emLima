<?php

class ControllerCatalogRecipe extends Controller
{
    private $error = [];

    public function index()
    {
        $this->load->language('catalog/recipe');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('catalog/recipe');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $recipe_id = $this->model_catalog_recipe->addRecipe($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/recipe/edit', 'recipe_id='.$recipe_id.'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/recipe/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/recipe', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('catalog/recipe');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_catalog_recipe->editRecipe($this->request->get['recipe_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/recipe/edit', 'recipe_id='.$this->request->get['recipe_id'].'&token='.$this->session->data['token'].$url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('catalog/recipe/add', 'token='.$this->session->data['token'].$url, 'SSL'));
            }

            $this->response->redirect($this->url->link('catalog/recipe', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('catalog/recipe');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/recipe');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $recipe_id) {
                $this->model_catalog_recipe->deleteRecipe($recipe_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/recipe', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'od.name';
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

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/recipe', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $data['add'] = $this->url->link('catalog/recipe/add', 'token='.$this->session->data['token'].$url, 'SSL');
        $data['delete'] = $this->url->link('catalog/recipe/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $data['recipes'] = [];

        $filter_data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];

        $recipe_total = $this->model_catalog_recipe->getTotalRecipes();

        $results = $this->model_catalog_recipe->getRecipes($filter_data);

        foreach ($results as $result) {
            $data['recipes'][] = [
                'recipe_id' => $result['recipe_id'],
                'title' => $result['title'],
                'author' => $result['author'],
                'sort_order' => $result['sort_order'],
                'edit' => $this->url->link('catalog/recipe/edit', 'token='.$this->session->data['token'].'&recipe_id='.$result['recipe_id'].$url, 'SSL'),
            ];
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_title'] = $this->language->get('column_title');
        $data['column_author'] = $this->language->get('column_author');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_sort_order'] = $this->language->get('column_sort_order');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

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

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['sort_title'] = $this->url->link('catalog/recipe', 'token='.$this->session->data['token'].'&sort=r.title'.$url, 'SSL');
        $data['sort_author'] = $this->url->link('catalog/recipe', 'token='.$this->session->data['token'].'&sort=r.author'.$url, 'SSL');
        $data['sort_sort_order'] = $this->url->link('catalog/recipe', 'token='.$this->session->data['token'].'&sort=r.sort_order'.$url, 'SSL');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $recipe_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/recipe', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($recipe_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recipe_total - $this->config->get('config_limit_admin'))) ? $recipe_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recipe_total, ceil($recipe_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/recipe_list.tpl', $data));
    }

    protected function getForm()
    {
        $this->load->model('tool/image');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['recipe_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_choose'] = $this->language->get('text_choose');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_radio'] = $this->language->get('text_radio');
        $data['text_checkbox'] = $this->language->get('text_checkbox');
        $data['text_image'] = $this->language->get('text_image');
        $data['text_input'] = $this->language->get('text_input');
        $data['text_text'] = $this->language->get('text_text');
        $data['text_textarea'] = $this->language->get('text_textarea');
        $data['text_file'] = $this->language->get('text_file');
        $data['text_date'] = $this->language->get('text_date');
        $data['text_datetime'] = $this->language->get('text_datetime');
        $data['text_time'] = $this->language->get('text_time');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_directions'] = $this->language->get('tab_directions');
        $data['tab_products'] = $this->language->get('tab_products');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_qty'] = $this->language->get('column_qty');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_recipe_value'] = $this->language->get('entry_recipe_value');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_categories'] = $this->language->get('entry_categories');
        $data['entry_author'] = $this->language->get('entry_author');
        $data['entry_utube_link'] = $this->language->get('entry_utube_link');
        $data['entry_directions'] = $this->language->get('entry_directions');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_recipe_value_add'] = $this->language->get('button_recipe_value_add');
        $data['button_remove'] = $this->language->get('button_remove');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = '';
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = '';
        }

        if (isset($this->error['directions'])) {
            $data['error_directions'] = $this->error['directions'];
        } else {
            $data['error_directions'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/recipe', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        if (!isset($this->request->get['recipe_id'])) {
            $data['action'] = $this->url->link('catalog/recipe/add', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/recipe/edit', 'token='.$this->session->data['token'].'&recipe_id='.$this->request->get['recipe_id'].$url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/recipe', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['recipe_id']) && ('POST' != $this->request->server['REQUEST_METHOD'])) {
            $recipe_info = $this->model_catalog_recipe->getRecipe($this->request->get['recipe_id']);
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['video'])) {
            $data['video'] = $this->request->post['video'];
        } elseif (isset($this->request->get['recipe_id'])) {
            $data['video'] = $recipe_info['video'];
        } else {
            $data['video'] = '';
        }

        $this->load->model('catalog/recipe_category');

        $data['categories'] = $this->model_catalog_recipe_category->getCategories();

        if (isset($this->request->post['category'])) {
            $data['category'] = $this->request->post['category'];
        } elseif (isset($this->request->get['recipe_id'])) {
            $data['category'] = $this->model_catalog_recipe_category->getRecipeCategories($this->request->get['recipe_id']);
        } else {
            $data['category'] = [];
        }

        if (isset($this->request->post['title'])) {
            $data['title'] = $this->request->post['title'];
        } elseif (isset($this->request->get['recipe_id'])) {
            $data['title'] = $recipe_info['title'];
        } else {
            $data['title'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (isset($this->request->get['recipe_id'])) {
            $data['description'] = $recipe_info['description'];
        } else {
            $data['description'] = '';
        }

        if (isset($this->request->post['directions'])) {
            $data['directions'] = $this->request->post['directions'];
        } elseif (!empty($recipe_info)) {
            $data['directions'] = $recipe_info['directions'];
        } else {
            $data['directions'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($recipe_info)) {
            $data['sort_order'] = $recipe_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['author'])) {
            $data['author'] = $this->request->post['author'];
        } elseif (isset($this->request->get['recipe_id'])) {
            $data['author'] = $recipe_info['author'];
        } else {
            $data['author'] = '';
        }

        if (isset($this->request->post['products'])) {
            $products = $this->request->post['products'];
        } elseif (isset($this->request->get['recipe_id'])) {
            $products = $this->model_catalog_recipe->getProducts($this->request->get['recipe_id']);
        } else {
            $products = [];
        }

        $data['products'] = [];

        foreach ($products as $product) {
            if (is_file(DIR_IMAGE.$product['image'])) {
                $thumb = $this->model_tool_image->resize($product['image'], 100, 100);
            } else {
                $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
            }

            $data['products'][] = [
                'name' => $product['name'],
                'model' => $product['model'],
                'quantity' => $product['quantity'],
                'image' => $product['image'],
                'thumb' => $thumb,
            ];
        }

        if (isset($recipe_info['image']) && is_file(DIR_IMAGE.$recipe_info['image'])) {
            $data['image'] = $recipe_info['image'];
            $data['thumb'] = $this->model_tool_image->resize($recipe_info['image'], 100, 100);
        } else {
            $data['image'] = '';
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
        } else {
            $data['success'] = '';
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/recipe_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/recipe')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['title'])) {
            $this->error['title'] = $this->language->get('error_title');
        }

        if (empty($this->request->post['description'])) {
            $this->error['description'] = $this->language->get('error_description');
        }

        if (empty($this->request->post['directions'])) {
            $this->error['directions'] = $this->language->get('error_directions');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'catalog/recipe')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = [];

        if (isset($this->request->get['filter_name'])) {
            $this->load->language('catalog/recipe');

            $this->load->model('catalog/recipe');

            $this->load->model('tool/image');

            $filter_data = [
                'filter_name' => $this->request->get['filter_name'],
                'start' => 0,
                'limit' => 5,
            ];

            $recipes = $this->model_catalog_recipe->getRecipes($filter_data);

            foreach ($recipes as $recipe) {
                $recipe_value_data = [];

                if ('select' == $recipe['type'] || 'radio' == $recipe['type'] || 'checkbox' == $recipe['type'] || 'image' == $recipe['type']) {
                    $recipe_values = $this->model_catalog_recipe->getRecipeValues($recipe['recipe_id']);

                    foreach ($recipe_values as $recipe_value) {
                        if (is_file(DIR_IMAGE.$recipe_value['image'])) {
                            $image = $this->model_tool_image->resize($recipe_value['image'], 50, 50);
                        } else {
                            $image = $this->model_tool_image->resize('no_image.png', 50, 50);
                        }

                        $recipe_value_data[] = [
                            'recipe_value_id' => $recipe_value['recipe_value_id'],
                            'name' => strip_tags(html_entity_decode($recipe_value['name'], ENT_QUOTES, 'UTF-8')),
                            'image' => $image,
                        ];
                    }

                    $sort_order = [];

                    foreach ($recipe_value_data as $key => $value) {
                        $sort_order[$key] = $value['name'];
                    }

                    array_multisort($sort_order, SORT_ASC, $recipe_value_data);
                }

                $type = '';

                if ('select' == $recipe['type'] || 'radio' == $recipe['type'] || 'checkbox' == $recipe['type'] || 'image' == $recipe['type']) {
                    $type = $this->language->get('text_choose');
                }

                if ('text' == $recipe['type'] || 'textarea' == $recipe['type']) {
                    $type = $this->language->get('text_input');
                }

                if ('file' == $recipe['type']) {
                    $type = $this->language->get('text_file');
                }

                if ('date' == $recipe['type'] || 'datetime' == $recipe['type'] || 'time' == $recipe['type']) {
                    $type = $this->language->get('text_date');
                }

                $json[] = [
                    'recipe_id' => $recipe['recipe_id'],
                    'name' => strip_tags(html_entity_decode($recipe['name'], ENT_QUOTES, 'UTF-8')),
                    'category' => $type,
                    'type' => $recipe['type'],
                    'recipe_value' => $recipe_value_data,
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
}
