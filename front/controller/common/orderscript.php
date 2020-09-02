<?php

class ControllerCommonOrderScript extends Controller
{
    public function index()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $filename = 'ordertoprocess.xlsx';
        $cwd = getcwd();
        chdir(DIR_SYSTEM.'PHPExcel');
        require_once 'Classes/PHPExcel.php';
        chdir($cwd);
        $objPHPExcel = PHPExcel_IOFactory::load($filename);
        $sheet = $objPHPExcel->getSheet(0);
        $total_rows = $sheet->getHighestRow();
        $total_columns = $sheet->getHighestColumn();
        $set_excel_query_all = [];
        $final_products_array = [];
        $notFoundArray = [];
        for ($row = 2; $row <= $total_rows; ++$row) {
            $singlerow = $sheet->rangeToArray('A'.$row.':'.$total_columns.$row, null, true, false);
            $product_data = $singlerow[0];
            $product_name = $product_data[0];
            //echo "<pre>";print_r($product_data);//die;

            $sql = 'SELECT ps.*,p2c.product_id,pd.name as product_name ,p.*,st.name as store_name,v.firstname as fs,v.lastname as ls,ps.status as sts,v.user_id as vendor_id from '.DB_PREFIX.'product_to_store ps LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (ps.product_id = p2c.product_id) LEFT JOIN '.DB_PREFIX.'product p ON (p.product_id = ps.product_id) LEFT JOIN '.DB_PREFIX.'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN '.DB_PREFIX.'store st ON (st.store_id = ps.store_id) LEFT JOIN '.DB_PREFIX.'user v ON (v.user_id = st.vendor_id)';
            $sql .= " WHERE pd.language_id = '".(int) $this->config->get('config_language_id')."'";
            $sql .= " AND pd.name LIKE '%".$this->db->escape($product_name)."%'";
            $sql .= ' GROUP BY ps.product_store_id';
            $sql .= ' ORDER BY pd.name';
            $sql .= ' ASC';
            $query = $this->db->query($sql);
            if (count($query->rows) > 0) {
                $tempArray = [];
                $tempArray['product_name'] = $product_name;
                $tempArray['variation_id'] = 0;
                $tempArray['product_id'] = $query->rows[0]['product_store_id'];
                $tempArray['quantity'] = $product_data[1];
                $tempArray['store_id'] = 75;
                $final_products_array[] = $tempArray;
            } else {
                $notFoundArray[] = $product_name;
            }
        }

        $data['products'] = $final_products_array;
        $data['notFoundProducts'] = $notFoundArray;

        /*echo "<pre>=============== Found Products =======================</br>";
        echo "<pre>";print_r($final_products_array);//die;
        echo "=============== Not Found Products =======================</br>";
        echo "<pre>";print_r($notFoundArray);//die;
        echo "=============================================</br>";
        if(count($notFoundArray) > 6){
            echo "Not Found these products in portal, Please correct name in excel then proceed !". implode(",",$notFoundArray);
        }else{

            //$data['products'] =
            echo "================== Process Start ==================</br>";
            /*if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/orderscript.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/orderscript.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/common/orderscript.tpl', $data));
            }*/

        //echo "<span>Proceed</span>";exit;
        /*   $urls =array(); */
        /*foreach($final_products_array as $product){
            $urls[] = BASE_URL.'/index.php?path=checkout/cart/add&action=script&product_id='.$product['product_id'].'&quantity='.$product['quantity'].'&variation_id=0&store_id=75';
            echo $url = BASE_URL.'/index.php?path=checkout/cart/add&action=script&product_id='.$product['product_id'].'&quantity='.$product['quantity'].'&variation_id=0&store_id=75';

            /*$response = $this->get_web_page($url);
            $resArr = array();
            $resArr = json_decode($response);
            echo "<pre>"; print_r($resArr); echo "</pre>";exit;*/
        //echo '<br><button type="button" data-toggle="tooltip" title="Proceed" class="btn btn-danger" onclick=""><i class="fa fa-trash-o"></i>Proceed</button>';exit;

        // Get cURL resource
        /* $curl = curl_init();
         // Set some options - we are passing in a useragent too here
         curl_setopt_array($curl, [
             CURLOPT_RETURNTRANSFER => 1,
             CURLOPT_URL => $url,
             CURLOPT_USERAGENT => 'Codular Sample cURL Request'
         ]);
         // Send the request & save response to $resp
         $resp = curl_exec($curl);
         echo "<pre>";print_r($resp);//die;
         // Close request to clear up some resources
         curl_close($curl);exit;

            }
        }*/
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/orderscript.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/orderscript.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/orderscript.tpl', $data));
        }
    }

    public function uploadOrderExcel()
    {
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/common/orderscript.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template').'/template/common/orderscript.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/orderscript.tpl', $data));
        }
    }

    public function uploadOrderExcelsubmit()
    {
        //echo "<pre>";print_r($_FILES);die;
        $target_dir = getcwd().'/';
        $target_file = $target_dir.basename($_FILES['filepath']['name']);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['filepath']['tmp_name'], $target_file);
        $redirectUrl = BASE_URL.'/index.php?path=common/orderscript';
        header("Location:$redirectUrl");
        exit();
    }

    public function get_web_page($url)
    {
        $options = [
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS => 10,     // stop after 10 redirects
            CURLOPT_ENCODING => '',     // handle compressed
            CURLOPT_USERAGENT => 'test', // name of client
            CURLOPT_AUTOREFERER => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT => 120,    // time-out on response
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        $content = curl_exec($ch);

        curl_close($ch);

        return $content;
    }
}
