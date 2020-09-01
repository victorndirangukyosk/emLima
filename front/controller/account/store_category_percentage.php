<?php

    define('DB_PREFIX', 'hf7_');

    echo 'store_category';
    $servername = 'instagolocal-db1.ca4nh7tsvfdw.us-west-2.rds.amazonaws.com';
    $username = 'instago497Fresh';
    $password = 'Es54pjzY7Yy75Ghjdkciw77yew87ydw8ygdhh';
    $dbname = 'www.instagolocal.com';

    //$this->load->model('assets/category');

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('Connection failed: '.$conn->connect_error);
    }

    $sql = 'SELECT * FROM '.DB_PREFIX.'store';

    $query = $conn->query($sql);

    if ($query) {
        while ($row = $query->fetch_assoc()) {
            $parent_id = 0;
            $language_id = 1;
            $store_id = $row['store_id'];

            $sql1 = 'SELECT * FROM '.DB_PREFIX.'category c LEFT JOIN '.DB_PREFIX.'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN '.DB_PREFIX."category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c2s.store_id = '".$store_id."' AND c.parent_id = '".(int) $parent_id."' AND cd.language_id = '".(int) $language_id."'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)";

            $results = $conn->query($sql1);

            //foreach ( $results as $result ) {
            if ($results) {
                while ($result = $results->fetch_assoc()) {
                    //echo "<pre>";print_r($results);die;
                    $filter_data = [
                        'filter_category_id' => $result['category_id'],
                        'filter_sub_category' => true,
                        'store_id' => $row['store_id'],
                    ];

                    //$children = $this->model_assets_category->getCategories($result['category_id']);

                    $parent_id = $result['category_id'];

                    //print_r($parent_id);die;
                    $query2 = 'SELECT * FROM '.DB_PREFIX.'category c LEFT JOIN '.DB_PREFIX.'category_description cd ON (c.category_id = cd.category_id) LEFT JOIN '.DB_PREFIX."category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '".(int) $parent_id."' AND cd.language_id = '".(int) $language_id."'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name) ";

                    //echo "<pre>";print_r($query2);die;

                    $children = $conn->query($query2);

                    //foreach ($children as $child) {
                    if ($children) {
                        while ($child = $children->fetch_assoc()) {
                            //echo "<pre>";print_r($child);die;
                            $child_filter_data = [
                                'filter_category_id' => $child['category_id'],
                                'filter_sub_category' => true,
                                'store_id' => $row['store_id'],
                            ];

                            $max_discount = getCategoryProducts($child_filter_data);

                            //echo "<pre>";print_r($max_discount);die;

                            //$this->db->query("UPDATE " . DB_PREFIX . "category_to_store SET max_discount ='" . (int) $max_discount . "' where store_id=".$row['store_id']." and category_id=".$child['category_id']);

                            echo 'store_category max_discount start';
                            echo $max_discount;
                            echo $row['store_id'];
                            echo $child['category_id'];

                            echo 'store_category max_discount end';

                            $query3 = 'UPDATE '.DB_PREFIX."category_to_store SET max_discount ='".(int) $max_discount."' where store_id=".$row['store_id'].' and category_id='.$child['category_id'];

                            //echo "<pre>";print_r($query2);die;

                            $conn->query($query3);
                        }
                    }

                    $max_discount = getCategoryProducts($filter_data);

                    echo 'store_category max_discount start';
                    echo $max_discount;
                    echo $row['store_id'];
                    echo $result['category_id'];

                    echo 'store_category max_discount end';

                    //$this->db->query("UPDATE " . DB_PREFIX . "category_to_store SET max_discount ='" . (int) $max_discount . "' where store_id=".$row['store_id']." and category_id=".$result['category_id']);
                    $query4 = 'UPDATE '.DB_PREFIX."category_to_store SET max_discount ='".(int) $max_discount."' where store_id=".$row['store_id'].' and category_id='.$result['category_id'];

                    //echo "<pre>";print_r($query2);die;

                    $conn->query($query4);
                }
            }
        }
    }

    die;

    function getCategoryProducts($filter_data)
    {
        //echo "<pre>";print_r($filter_data);die;
        //$this->load->model( 'assets/product' );

        $store_id = $filter_data['store_id'];

        $max_discount = 0;
        //$results = $this->model_assets_product->getProductsForCron( $filter_data );
        $servername = 'instagolocal-db1.ca4nh7tsvfdw.us-west-2.rds.amazonaws.com';
        $username = 'instago497Fresh';
        $password = 'Es54pjzY7Yy75Ghjdkciw77yew87ydw8ygdhh';
        $dbname = 'www.instagolocal.com';

        //$this->load->model('assets/category');

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die('Connection failed: '.$conn->connect_error);
        }

        $sql = 'SELECT * FROM '.DB_PREFIX.'product p LEFT JOIN '.DB_PREFIX.'product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN '.DB_PREFIX.'product_to_category p2c ON (p2c.product_id = p2s.product_id) LEFT JOIN '.DB_PREFIX."category_path c2p ON (c2p.category_id = p2c.category_id) WHERE p.status = '1' AND p2s.store_id = '".(int) $filter_data['store_id']."' And p2c.category_id = ".$filter_data['filter_category_id'];

        $query = $conn->query($sql);

        if ($query) {
            //echo "<pre>";print_r($results);die;
            while ($result = $query->fetch_assoc()) {
                if ($result['quantity'] <= 0) {
                    continue;
                }

                $discount = '';

                $s_price = $result['special_price'];
                $o_price = $result['price'];

                $percent_off = null;
                if (isset($s_price) && isset($o_price) && 0 != $o_price && 0 != $s_price) {
                    $percent_off = (($o_price - $s_price) / $o_price) * 100;
                }

                if ($percent_off > 5 && $percent_off > $max_discount) {
                    $max_discount = $percent_off;
                }
            }
        }

        return number_format($max_discount, 0);
    }
