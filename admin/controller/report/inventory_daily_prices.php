<?php

class ControllerReportInventoryDailyPrices extends Controller
{


    public function index()
    {
        $this->load->language('report/inventory_daily_prices');
        $this->document->setTitle($this->language->get('heading_title'));

        // $category_prices = $this->getCategoriesProductPrices();

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = '';
        }
        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = '';
        }
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

        // if (isset($this->request->get['filter_price'])) {
        //     $filter_price = $this->request->get['filter_price'];
        // } else {
        //     $filter_price = null;
        // }

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

        // if (isset($this->request->get['sort'])) {
        //     $sort = $this->request->get['sort'];
        // } else {
        //     $sort = 'pd.name';
        // }

        // if (isset($this->request->get['order'])) {
        //     $order = $this->request->get['order'];
        // } else {
        //     $order = 'ASC';
        // }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';
        if (isset($this->request->get['filter_date_start'])) {
            $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
        }
        if (isset($this->request->get['filter_date_end'])) {
            $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
        }
        // if (isset($this->request->get['filter_order_status_id'])) {
        //     $url .= '&filter_order_status_id='.$this->request->get['filter_order_status_id'];
        // }
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_vendor_name'])) {
            $url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
        }

        // if (isset($this->request->get['filter_price'])) {
        //     $url .= '&filter_price=' . $this->request->get['filter_price'];
        // }

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
        // if (isset($this->request->get['filter_quantity'])) {
        //     $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        // }

        if (isset($this->request->get['filter_category_price'])) {
            $url .= '&filter_category_price=' . $this->request->get['filter_category_price'];
        }

        // if (isset($this->request->get['sort'])) {
        //     $url .= '&sort=' . $this->request->get['sort'];
        // }

        // if (isset($this->request->get['order'])) {
        //     $url .= '&order=' . $this->request->get['order'];
        // }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/inventory_daily_prices', 'token='.$this->session->data['token'].$url, 'SSL'),
        ];

        $this->load->model('catalog/vendor_product');


        $data['customers'] = []; 

        // $this->load->model('catalog/vendor_product');
        // $category_price_prods = NULL;
        // if(isset($this->request->get['filter_category_price'])) {
        // $category_price_prods = $this->model_catalog_vendor_product->getCategoryPriceDetailsByCategoryName(75, $this->request->get['filter_category_price']);    
        // $category_price_prods = array_column($category_price_prods, 'product_store_id');

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_vendor_name' => $filter_vendor_name,
            // 'filter_price' => $filter_price,
            'filter_product_id_from' => $filter_product_id_from,
            'filter_model' => $filter_model,
            'filter_product_id_to' => $filter_product_id_to,
            'filter_category' => $filter_category,
            'filter_store_id' => $filter_store_id,
            'filter_status' => $filter_status,
            'filter_quantity' => $filter_quantity,
            'filter_category_price' => $filter_category_price,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
            // 'filter_order_status_id' => $filter_order_status_id,
            // //'filter_customer' => $filter_customer,
            // 'filter_company' => $filter_company,
            // 'sort' => $sort,
            // 'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin'),
            // 'filter_category_price_prods' => isset($this->request->get['filter_category_price']) ? $category_price_prods : NULL,

        ];

        if ('' != $filter_date_start && '' != $filter_date_end) {
            // $company_total =  $this->model_report_customer->getTotalValidCompanies($filter_data);

        //   $customerresults = $this->model_report_customer->getValidProducts($filter_data);


          $product_total = $this->model_catalog_vendor_product->getTotalProducts($filter_data);

          $productresults = $this->model_catalog_vendor_product->getProducts($filter_data);


        //   echo "<pre>";print_r($customerresults);die;
                        $days=[];   
                        $begin = new DateTime($filter_date_start);
                        $end   = new DateTime($filter_date_end);

                        for($i = $begin; $i <= $end; $i->modify('+1 day')){
                            $iDateFrom= $i->format("Y-m-d");
                            array_push($days,  $i->format("Y-m-d"));
                        }
         
        // $this->load->model('report/customer');

        //   $days1 = $this->model_report_customer->getmonths($filter_data);//need to check simple way
        //  echo "<pre>";print_r($days);die;

        // $product_total = $this->model_catalog_vendor_product->getTotalProducts($filter_data);

        // $results = $this->model_catalog_vendor_product->getProducts($filter_data);
            } else {
                    $product_total = 0;
                    $productresults = null;
                }

                
                $this->load->model('sale/order');

                if (is_array($productresults) && count($productresults) > 0) {
                    $log = new Log('error.log');
                    $log->write('Yes It Is Array');
                    $i=0;
        // $this->load->model('catalog/product');

                    foreach ($productresults as $result) {               
                         $priceperday=0;
                        $data['products'][] = [
                            // 'store_name' => $result['store_name'],
                            //'vendor_name'=>$result['fs'].' '.$result['ls'],
                            // 'General Product ID' => $result['product_id'],
                            // 'Vendor Product ID' => $result['product_store_id'],

                            // 'price' => $result['price'],
                            // 'special_price' => $result['special_price'],
                            // 'quantity' => $result['quantity'],
                            // 'source' => $result['source'],
                            // 'image' => $image,
                            // 'bigimage' => $bigimage,
                            'Product Name' => $result['product_name'],
                            'Unit' => $result['unit'],
                            'Last Updated Buying Price' => $result['buying_price'],

                            //'weight' => $result['weight'],
                            // 'model' => $result['model'],
                            // 'category' => $category,
                            // 'category_price_status' => array_key_exists('category_price_status', $result) ? $result['category_price_status'] : '',
                            // 'Status' => ($result['sts']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),                  
                            ];
                        // $totalOrders=0;
                        // $OrdersValue=0;

                // $this->load->model('catalog/product');

                        foreach ($days as $day) {
                            $priceperday=$this->model_catalog_vendor_product->getProductInventoryPriceHistorybyDate($day,$result['product_store_id'],null);
                            $date=$day;
                            // $totalOrders= $totalOrders+$totalpermonth['TotalOrders'];
                            // $OrdersValue=$OrdersValue+$totalpermonth['Total'];
                            //$data['customers'][$i][$monthname]=$this->currency->format($totalpermonth['Total'], $this->config->get('config_currency'));
                            if($priceperday==null)
                            $priceperday='NA';
                            
                                $data['products'][$i][$date]=$priceperday;

                        }
                        // $data['products'][$i]['Total']= number_format($OrdersValue);
                        // $data['products'][$i]['Order Count']= $totalOrders;
                        // if($OrdersValue>0 && $totalOrders>0)
                        // {
                        // $data['customers'][$i]['Avg. Order Value']= number_format(($OrdersValue/$totalOrders),2);
                        // }
                        // else
                        // {
                        //     $data['customers'][$i]['Avg. Order Value']=0;
                        // }
                        $i++;
                    }
                }
                    //    echo "<pre>";print_r($data['customers']);die;
                    // echo "<pre>";print_r($data['products']);die;

                $data['heading_title'] = $this->language->get('heading_title');
                $data['text_list'] = $this->language->get('text_list');
                $data['text_enabled'] = $this->language->get('text_enabled');
                $data['text_disabled'] = $this->language->get('text_disabled');
                $data['text_no_results'] = $this->language->get('text_no_results');
                $data['text_confirm'] = $this->language->get('text_confirm');
                $data['text_all_status'] = $this->language->get('text_all_status');
                $data['column_customer'] = $this->language->get('column_customer');
                $data['column_email'] = $this->language->get('column_email');
                $data['column_customer_group'] = $this->language->get('column_customer_group');
                $data['column_status'] = $this->language->get('column_status');
                $data['column_orders'] = $this->language->get('column_orders');
                $data['column_products'] = $this->language->get('column_products');
                $data['column_total'] = $this->language->get('column_total');
                $data['column_action'] = $this->language->get('column_action');
                $data['entry_date_start'] = $this->language->get('entry_date_start');
                $data['entry_date_end'] = $this->language->get('entry_date_end');
                $data['entry_month_start'] = $this->language->get('entry_month_start');
                $data['entry_month_end'] = $this->language->get('entry_month_end');
                $data['entry_status'] = $this->language->get('entry_status');
                $data['entry_customer'] = $this->language->get('entry_customer'); 

                $this->load->model('catalog/category');
                $data['categories'] = $this->model_catalog_category->getCategories(0);
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
                // $data['button_edit'] = $this->language->get('button_edit');
                $data['button_filter'] = $this->language->get('button_filter');
                $data['button_show_filter'] = $this->language->get('button_show_filter');
                $data['button_hide_filter'] = $this->language->get('button_hide_filter');

                $data['token'] = $this->session->data['token'];
                $this->load->model('localisation/order_status');
                $data['order_statuses'] = $this->model_localisation_order_status->getValidOrderStatuses();

                $this->load->model('sale/customer');
                // $data['customer_names'] = $this->model_sale_customer->getCustomers(null);
                $url = '';
                if (isset($this->request->get['filter_date_start'])) {
                    $url .= '&filter_date_start='.$this->request->get['filter_date_start'];
                }
                if (isset($this->request->get['filter_date_end'])) {
                    $url .= '&filter_date_end='.$this->request->get['filter_date_end'];
                }
                
                    if (isset($this->request->get['filter_name'])) {
                        $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
                    }
            
                    if (isset($this->request->get['filter_vendor_name'])) {
                        $url .= '&filter_vendor_name=' . urlencode(html_entity_decode($this->request->get['filter_vendor_name'], ENT_QUOTES, 'UTF-8'));
                    }
            
                    // if (isset($this->request->get['filter_price'])) {
                    //     $url .= '&filter_price=' . $this->request->get['filter_price'];
                    // }
            
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
            
                    // if ('ASC' == $order) {
                    //     $url .= '&order=DESC';
                    // } else {
                    //     $url .= '&order=ASC';
                    // }
            
                    if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                    }
            
                $pagination = new Pagination();
                $pagination->total = $product_total;
                $pagination->page = $page;
                $pagination->limit = $this->config->get('config_limit_admin');
                $pagination->url = $this->url->link('report/inventory_daily_prices', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');
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
                $data['filter_date_start'] = $filter_date_start;
                $data['filter_date_end'] = $filter_date_end;
                // $data['filter_order_status_id'] = $filter_order_status_id;
                // $data['filter_customer'] = $filter_customer;
                // $data['filter_company'] = $filter_company;
                $data['header'] = $this->load->controller('common/header');
                $data['column_left'] = $this->load->controller('common/column_left');
                $data['footer'] = $this->load->controller('common/footer');

                $this->response->setOutput($this->load->view('report/inventory_daily_prices.tpl', $data));
            }

    public function getmonthname($month){

        if($month==1)
        {
           $name="January"; 

        }
        else if($month==2)
        {
           $name="February"; 

        }
        else if($month==3)
        {
           $name="March"; 

        }
        else if($month==4)
        {
           $name="April"; 

        }
        else if($month==5)
        {
           $name="May"; 

        }
        else if($month==6)
        {
           $name="June"; 

        }
        else if($month==7)
        {
           $name="July"; 

        }
        else if($month==8)
        {
           $name="August"; 

        }
        else if($month==9)
        {
           $name="September"; 

        }
        else if($month==10)
        {
           $name="October"; 

        }
        else if($month==11)
        {
           $name="November"; 

        }
        else if($month==12)
        {
           $name="December"; 

        }
        return $name;

    }
    
    public function inventory_daily_prices_excel()
    {
        $this->load->language('report/inventory_daily_prices');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        }  

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        // if (isset($this->request->get['filter_order_status_id'])) {
        //     $filter_order_status_id = $this->request->get['filter_order_status_id'];
        // } else {
        //     $filter_order_status_id = 0;
        // }

        // if (isset($this->request->get['filter_customer'])) {
        //     $filter_customer = $this->request->get['filter_customer'];
        // } else {
        //     $filter_customer = 0;
        // }

        // if (isset($this->request->get['filter_company'])) {
        //     $filter_company = $this->request->get['filter_company'];
        // } else {
        //     $filter_company = 0;
        // }

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

        // if (isset($this->request->get['filter_price'])) {
        //     $filter_price = $this->request->get['filter_price'];
        // } else {
        //     $filter_price = null;
        // }

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


         $this->load->model('catalog/vendor_product');

        $filter_data = [
            'filter_name' => $filter_name,
            'filter_vendor_name' => $filter_vendor_name,
            // 'filter_price' => $filter_price,
            'filter_product_id_from' => $filter_product_id_from,
            'filter_model' => $filter_model,
            'filter_product_id_to' => $filter_product_id_to,
            'filter_category' => $filter_category,
            'filter_store_id' => $filter_store_id,
            'filter_status' => $filter_status,
            'filter_quantity' => $filter_quantity,
            'filter_category_price' => $filter_category_price,
            'filter_date_start' => $filter_date_start,
            'filter_date_end' => $filter_date_end,
        ];

        if ('' != $filter_date_start && '' != $filter_date_end) {
            $product_total = $this->model_catalog_vendor_product->getTotalProducts($filter_data);

          $productresults = $this->model_catalog_vendor_product->getProducts($filter_data);
          $days=[];   
          $begin = new DateTime($filter_date_start);
          $end   = new DateTime($filter_date_end);

          for($i = $begin; $i <= $end; $i->modify('+1 day')){
              $iDateFrom= $i->format("Y-m-d");
              array_push($days,  $i->format("Y-m-d"));
          }

            } else {
            $product_total = 0;
            $productresults = null;
        }

        $this->load->model('sale/order');
        if (is_array($productresults) && count($productresults) > 0) {
            $log = new Log('error.log');
            $log->write('Yes It Is Array');
            $i=0;
            foreach ($productresults as $result) {               
                $priceperday=0;
                $data['products'][] = [
                    // 'store_name' => $result['store_name'],
                    //'vendor_name'=>$result['fs'].' '.$result['ls'],
                    // 'General Product ID' => $result['product_id'],
                    // 'Vendor Product ID' => $result['product_store_id'],

                    // 'price' => $result['price'],
                    // 'special_price' => $result['special_price'],
                    // 'quantity' => $result['quantity'],
                    // 'source' => $result['source'],
                    // 'image' => $image,
                    // 'bigimage' => $bigimage,
                    'Product Name' => $result['product_name'],
                    'Unit' => $result['unit'],
                    'Last Updated Buying Price' => $result['buying_price'],

                    //'weight' => $result['weight'],
                    // 'model' => $result['model'],
                    // 'category' => $category,
                    // 'category_price_status' => array_key_exists('category_price_status', $result) ? $result['category_price_status'] : '',
                    // 'Status' => ($result['sts']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),                  
                    ];
                // $totalOrders=0;
                // $OrdersValue=0;
                foreach ($days as $day) {
                    $priceperday=$this->model_catalog_vendor_product->getProductInventoryPriceHistorybyDate($day,$result['product_store_id'],null);
                    $date=$day;
                    // $totalOrders= $totalOrders+$totalpermonth['TotalOrders'];
                    // $OrdersValue=$OrdersValue+$totalpermonth['Total'];
                    //$data['customers'][$i][$monthname]=$this->currency->format($totalpermonth['Total'], $this->config->get('config_currency'));
                    if($priceperday==null)
                    $priceperday='NA';
                    
                        $data['products'][$i][$date]=$priceperday;

                }
                // $data['customers'][$i]['Total']=number_format($OrdersValue);
                // $data['customers'][$i]['Order Count']= $totalOrders;
                // if($OrdersValue>0 && $totalOrders>0)
                // {
                // $data['customers'][$i]['Avg. Order Value']= number_format(($OrdersValue/$totalOrders),2);
                // }
                // else
                // {
                //     $data['customers'][$i]['Avg. Order Value']=0;
                // }
                // // echo "<pre>";print_r($data['customers']);die;
                $i++;
            }
        }
            //    echo "<pre>";print_r($data['products']);die;
            // echo "<pre>";print_r($data['customers']);die;

        $this->load->model('report/excel');
        $this->model_report_excel->download_inventory_daily_prices_excel($data['products']);
    }

    
}
