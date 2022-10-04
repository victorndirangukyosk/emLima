<?php


class ControllerCommonProductEntry extends Controller {

    private $error = [];    

    public function index() {

        $this->load->language('common/productentry');

        $this->document->setTitle("Product Entry");

        $this->load->model('common/productentry');

        $this->getList();
    }

    

    public function add() {

        //  echo "<pre>";print_r($this->request->post);die;
        $this->load->language('common/productentry');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('common/productentry');

        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            //echo "<pre>";print_r($this->request->post);die;
            $product_entry_id = $this->model_common_productentry->addProductEntry($this->request->post);
         
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_source'])) {
                $url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
            }

            

            // if (isset($this->request->get['filter_price'])) {
            //     $url .= '&filter_price=' . $this->request->get['filter_price'];
            // }

            

            // if (isset($this->request->get['filter_quantity'])) {
            //     $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            // }

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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('common/productentry/edit', 'product_entry_id=' . $product_entry_id . $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('common/productentry/add', $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('common/productentry', $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('common/productentry');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('common/productentry');


        if (('POST' == $this->request->server['REQUEST_METHOD']) && $this->validateForm()) {
            $this->model_common_productentry->editProductEntry($this->request->get['product_entry_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';
            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_source'])) {
                $url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
            }

             

            // if (isset($this->request->get['filter_price'])) {
            //     $url .= '&filter_price=' . $this->request->get['filter_price'];
            // }
           

            // if (isset($this->request->get['filter_quantity'])) {
            //     $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            // }


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

            if (isset($this->request->post['button']) and 'save' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('common/productentry/edit', 'product_entry_id=' . $this->request->get['product_entry_id'] , $url, 'SSL'));
            }

            if (isset($this->request->post['button']) and 'new' == $this->request->post['button']) {
                $this->response->redirect($this->url->link('common/productentry/add', $url, 'SSL'));
            }

            $this->response->redirect($this->url->link('common/productentry', $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('common/productentry');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('common/productentry');

        if (isset($this->request->post['selected']) ) {
            foreach ($this->request->post['selected'] as $product_entry_id) {
                $this->model_common_productentry->deleteProductEntry($product_entry_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_source'])) {
                $url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
            }

            

            // if (isset($this->request->get['filter_price'])) {
            //     $url .= '&filter_price=' . $this->request->get['filter_price'];
            // }          


            // if (isset($this->request->get['filter_quantity'])) {
            //     $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            // }


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

            $this->response->redirect($this->url->link('common/productentry', $url, 'SSL'));
        }

        $this->getList();
    }

    

    protected function getList() {

        if (isset($this->request->get['filter_source'])) {
            $filter_source = $this->request->get['filter_source'];
        } else {
            $filter_source = null;
        }
       
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }             

            // if (isset($this->request->get['filter_quantity'])) {
            //     $filter_quantity = $this->request->get['filter_quantity'];
            // } else {
            //     $filter_quantity = null;
            // }

            // if (isset($this->request->get['filter_price'])) {
            //     $filter_price = $this->request->get['filter_price'];
            // } else {
            //     $filter_price = null;
            // } 

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
            $sort = 'p.product_name';
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

        if (isset($this->request->get['filter_source'])) {
            $url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
        }

          
        // if (isset($this->request->get['filter_price'])) {
        //     $url .= '&filter_price=' . $this->request->get['filter_price'];
        // }

       

        // if (isset($this->request->get['filter_quantity'])) {
        //     $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        // }

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
            'text' => '',
            'href' => '',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Product Entry',
            'href' => $this->url->link('common/productentry',  $url, 'SSL'),
        ];

        $data['add'] = $this->url->link('common/productentry/add',  $url, 'SSL');
        $data['delete'] = $this->url->link('common/productentry/delete',  $url, 'SSL');

        $data['products'] = [];

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_source' => $filter_source,            
            // 'filter_price' => $filter_price,
            // 'filter_quantity' => $filter_quantity,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,           
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
        ];


        $product_total = $this->model_common_productentry->getTotalProductsEntry($filter_data);

        $results = $this->model_common_productentry->getProductEntries($filter_data);

        //echo "<pre>";print_r($results);die;
         
        foreach ($results as $result) {
           

            $data['products'][] = [
                'product_entry_id' => $result['product_entry_id'],
                'name' => $result['product_name'],
                'unit' => $result['unit'],
                'quantity' => $result['quantity'],
                'price' => $result['price'],
                'source' => $result['source'],
                'edit' => $this->url->link('common/productentry/edit',  '&product_entry_id=' . $result['product_entry_id'] . $url, 'SSL'),
            ];
        }

        //echo "<pre>";print_r($data['products']);die;

         $data['heading_title'] = "Product Entry";

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_id'] = $this->language->get('column_id');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_unit'] = $this->language->get('column_unit');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_source'] = $this->language->get('column_source');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_source'] = $this->language->get('entry_source');


        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_date_added_end'] = $this->language->get('entry_date_added_end');


        $data['button_add'] = $this->language->get('button_add');
        $data['button_close'] = $this->language->get('button_close');
        $data['button_submit'] = $this->language->get('button_submit');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_show_filter'] = $this->language->get('button_show_filter');
        $data['button_hide_filter'] = $this->language->get('button_hide_filter');
        $data['button_save_changes'] = $this->language->get('button_save_changes');


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

        if (isset($this->request->get['filter_source'])) {
            $url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
        }
     


        // if (isset($this->request->get['filter_price'])) {
        //     $url .= '&filter_price=' . $this->request->get['filter_price'];
        // }

        // if (isset($this->request->get['filter_quantity'])) {
        //     $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        // }

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

       

        if ('ASC' == $order) {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('common/productentry', '&sort=p.product_name' . $url, 'SSL');
        $data['sort_source'] = $this->url->link('common/productentry', '&sort=p.source' . $url, 'SSL');
        $data['sort_product_entry_id'] = $this->url->link('common/productentry', '&sort=p.product_entry_id' . $url, 'SSL');
        $data['sort_price'] = $this->url->link('common/productentry', '&sort=p.price' . $url, 'SSL');
        $data['sort_quantity'] = $this->url->link('common/productentry', '&sort=p.quantity' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_source'])) {
            $url .= '&filter_source=' . urlencode(html_entity_decode($this->request->get['filter_source'], ENT_QUOTES, 'UTF-8'));
        }

       
        // if (isset($this->request->get['filter_price'])) {
        //     $url .= '&filter_price=' . $this->request->get['filter_price'];
        // }

        

        // if (isset($this->request->get['filter_quantity'])) {
        //     $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        // }

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

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');

        //echo "<pre>";print_r($url);die;
        $pagination->url = $this->url->link('common/productentry',  $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_source'] = $filter_source;
       
        $data['filter_price'] = $filter_price;
        $data['filter_quantity'] = $filter_quantity;
        $data['filter_date_added'] = $filter_date_added;
        $data['filter_date_added_end'] = $filter_date_added_end;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        //echo "<pre>";print_r($data);die;
        $this->response->setOutput($this->load->view('common/productentry_list.tpl', $data));
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['product_entry_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_source'] = $this->language->get('entry_source');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_confirm'] = $this->language->get('entry_confirm');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_unit'] = $this->language->get('entry_unit');
        $data['entry_email'] = $this->language->get('entry_email');
       
        $data['button_save'] = $this->language->get('button_save');
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        $data['button_cancel'] = $this->language->get('button_cancel');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['unit'])) {
            $data['error_unit'] = $this->error['unit'];
        } else {
            $data['error_unit'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
        }

        if (isset($this->error['source'])) {
            $data['error_source'] = $this->error['source'];
        } else {
            $data['error_source'] = '';
        }

        if (isset($this->error['quantity'])) {
            $data['error_quantity'] = $this->error['quantity'];
        } else {
            $data['error_quantity'] = '';
        }

        if (isset($this->error['price'])) {
            $data['error_price'] = $this->error['price'];
        } else {
            $data['error_price'] = '';
        }

       
        $url = '';

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

        

        if (!isset($this->request->get['product_entry_id'])) {
            $data['action'] = $this->url->link('common/productentry/add', $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('common/productentry/edit', '&product_entry_id=' . $this->request->get['product_entry_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('common/productentry', $url, 'SSL');

        if (isset($this->request->get['product_entry_id'])) {
            $product_entry_info = $this->model_common_productentry->getProductEntry($this->request->get['product_entry_id']);
            $data['product_entry_id'] = $product_entry_info['product_entry_id'];
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($product_entry_info)) {
            $data['name'] = $product_entry_info['product_name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['unit'])) {
            $data['unit'] = $this->request->post['unit'];
        } elseif (!empty($product_entry_info)) {
            $data['unit'] = $product_entry_info['unit'];
        } else {
            $data['unit'] = '';
        }

        if (isset($this->request->post['source'])) {
            $data['source'] = $this->request->post['source'];
        } elseif (!empty($product_entry_info)) {
            $data['source'] = $product_entry_info['source'];
        } else {
            $data['source'] = '';
        }

        if (isset($this->request->post['quantity'])) {
            $data['quantity'] = $this->request->post['quantity'];
        } elseif (!empty($product_entry_info)) {
            $data['quantity'] = $product_entry_info['quantity'];
        } else {
            $data['quantity'] = '';
        }


        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($product_entry_info)) {
            $data['price'] = $product_entry_info['price'];
        } else {
            $data['price'] = '';
        }

                  

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/productentry_form.tpl', $data));
    }

    protected function validateForm() {
        

            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
                $this->error['name']= $this->language->get('error_name');
            }
            

        if ((utf8_strlen($this->request->post['unit']) < 1) || (utf8_strlen($this->request->post['unit']) > 100)) {
            $this->error['unit'] = $this->language->get('error_unit');
        }

        if ( ( utf8_strlen( $this->request->post['source'] ) < 3 ) ) {
          $this->error['source'] = $this->language->get( 'error_source' );
          } 

        if ((utf8_strlen($this->request->post['price']) < 1)) {
            $this->error['price'] = $this->language->get('error_price');
        }

        if ((utf8_strlen($this->request->post['quantity']) < 1)) {
            $this->error['quantity'] = $this->language->get('error_quantity');
        }
 
         

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }
 
        return !$this->error;
    }

    

    public function export_excel() {
        $data = [];
        $this->load->model('report/excel');
        if (isset($this->request->get['filter_source'])) {
            $filter_source = $this->request->get['filter_source'];
        } else {
            $filter_source = null;
        }
       
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }             

            // if (isset($this->request->get['filter_quantity'])) {
            //     $filter_quantity = $this->request->get['filter_quantity'];
            // } else {
            //     $filter_quantity = null;
            // }

            // if (isset($this->request->get['filter_price'])) {
            //     $filter_price = $this->request->get['filter_price'];
            // } else {
            //     $filter_price = null;
            // } 

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
            $sort = 'p.product_entry_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }


        $filter_data = [
            'filter_source' => $filter_source,
            'filter_name' => $filter_name,
            // 'filter_quantity' => $filter_quantity,
            // 'filter_price' => $filter_price,
            'filter_date_added' => $filter_date_added,
            'filter_date_added_end' => $filter_date_added_end,
            'sort' => $sort,
            'order' => $order,
           
        ];

        //  echo "<pre>";print_r($filter_data);die;


        $this->model_report_excel->download_product_entry_excel($filter_data);
    }

   

    // public function autocomplete() {
    //     $json = [];

    //     if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
    //         $this->load->model('catalog/general');
    //         $this->load->model('catalog/option');

    //         if (isset($this->request->get['filter_name'])) {
    //             $filter_name = $this->request->get['filter_name'];
    //         } else {
    //             $filter_name = '';
    //         }

    //         if (isset($this->request->get['filter_status'])) {
    //             $filter_status = $this->request->get['filter_status'];
    //         } else {
    //             $filter_status = null;
    //         }

    //         if (isset($this->request->get['filter_store'])) {
    //             $filter_store = $this->request->get['filter_store'];
    //         } else {
    //             $filter_store = '';
    //         }

    //         if (isset($this->request->get['filter_model'])) {
    //             $filter_model = $this->request->get['filter_model'];
    //         } else {
    //             $filter_model = '';
    //         }

    //         if (isset($this->request->get['limit'])) {
    //             $limit = $this->request->get['limit'];
    //         } else {
    //             $limit = 5;
    //         }

    //         $filter_data = [
    //             'filter_name' => $filter_name,
    //             'filter_store' => $filter_store,
    //             'filter_status' => $filter_status,
    //             'filter_model' => $filter_model,
    //             'start' => 0,
    //             'limit' => $limit,
    //         ];

    //         $results = $this->model_catalog_general->getProducts($filter_data);

    //         foreach ($results as $result) {
    //             $json[] = [
    //                 'product_id' => $result['product_id'],
    //                 'default_variation_name' => strip_tags(html_entity_decode($result['default_variation_name'], ENT_QUOTES, 'UTF-8')),
    //                 'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
    //                 'model' => $result['model'],
    //                 'option' => [],
    //                 'variations' => $this->model_catalog_general->getProductVariations($result['product_id']),
    //                 'price' => $result['price'],
    //             ];
    //         }
    //     }

    //     $this->response->addHeader('Content-Type: application/json');
    //     $this->response->setOutput(json_encode($json));
    // }

   

     

     
}
