<?php

class ModelAccountWishList extends Model
{
    public function getWishlistProduct($wishlist_id)
    {
        $order_query = $this->db->query('SELECT * from '.DB_PREFIX.'wishlist_products wl inner join '.DB_PREFIX."product p on p.product_id = wl.product_id WHERE wl.wishlist_id = '".$wishlist_id."'");

        $data = [];

        if ($order_query->num_rows) {
            $wishlist_info = $this->db->query('select * from `'.DB_PREFIX.'wishlist` WHERE wishlist_id="'.$wishlist_id.'"')->row;

            if ($wishlist_info) {
                $wishlist_name = $wishlist_info['name'];
            } else {
                $wishlist_name = '';
            }

            //echo "<pre>";print_r($order_query->rows);die;
            foreach ($order_query->rows as $wishlist) {
                $this->load->model('tool/image');

                if ($wishlist['image'] != NULL && file_exists(DIR_IMAGE.$wishlist['image'])) {
                    $image = $this->model_tool_image->resize($wishlist['image'], 80, 100);
            } else if($wishlist['image'] == NULL || !file_exists(DIR_IMAGE.$wishlist['image'])) {
                    $image = $this->model_tool_image->resize('placeholder.png', 80, 100);
                }
            $store_product = $this->db->query('select * from `'.DB_PREFIX.'product_to_store` WHERE product_id="'.$wishlist['product_id'].'"')->row;
            $store_product_detail = $store_product;
            $log = new Log('error.log');
            /*$log->write('store_product_detail');
            $log->write($store_product_detail);
            $log->write('store_product_detail');*/
                $temp = [
                    'name' => $wishlist_name,
                    'wishlist_id' => $wishlist['wishlist_id'],
                    'product_id' => $wishlist['product_id'],
                    'product_store_id' => $store_product_detail['product_store_id'],
                    'store_id' => $store_product_detail['store_id'],
                    'quantity' => $wishlist['quantity'],
                    'name' => $wishlist['name'],
                    'image' => $image,
                    'unit' => $wishlist['unit'],
                    'product_note' => $wishlist['product_note'],

                    //for priice add here
                ];

                array_push($data, $temp);
            }

            //echo "<pre>";print_r($data);die;

            return $data;
        } else {
            return $data;
        }
    }

    public function getAvailableOrderedProducts($order_id)
    {

        
        $order_query = $this->db->query('SELECT * from '.DB_PREFIX.'order_product ol inner join '.DB_PREFIX."product p on p.product_id = ol.general_product_id WHERE ol.order_id = '".$order_id."'");


        //    echo "<pre>";print_r('SELECT * from '.DB_PREFIX.'order_product ol inner join '.DB_PREFIX."product p on p.product_id = ol.product_id WHERE ol.order_id = '".$order_id."'");die;

        $data = [];

        if ($order_query->num_rows) {
            
            //   echo "<pre>";print_r($order_query->rows);die;
            foreach ($order_query->rows as $orderlist) {          

                $temp = [
                    // 'name' => $wishlist_name,
                    'order_id' => $orderlist['order_id'],
                    'product_id' => $orderlist['general_product_id'],
                    'quantity' => $orderlist['quantity'],
                    'name' => $orderlist['name'],
                    // 'image' => $image,
                    'unit' => $orderlist['unit'],
                    'store_id' => $orderlist['store_id']
                    //for priice add here
                ];

                array_push($data, $temp);
            }

            //  echo "<pre>";print_r($data);die;

            return $data;
        } else {
            return $data;
        }
    }

    public function getWishlist($wishlist_id)
    {
        $query = $this->db->query('SELECT * from  '.DB_PREFIX.'wishlist where wishlist_id = '.(int) $wishlist_id);

        return $query->row;
    }

    public function getWishlists($start = 0, $limit = 20)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        /*if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }*/

        $query = $this->db->query('SELECT * from  '.DB_PREFIX.'wishlist where customer_id = '.(int) $this->customer->getId().' LIMIT '.(int) $start.','.(int) $limit);

        return $query->rows;
    }

    public function deleteWishlists($wishlist_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."wishlist_products` WHERE wishlist_id = '".$wishlist_id."'");

        $this->db->query('DELETE FROM `'.DB_PREFIX."wishlist` WHERE wishlist_id = '".$wishlist_id."'");

        return true;
    }

    public function deleteAWishlistProducts($wishlist_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."wishlist_products` WHERE wishlist_id = '".$wishlist_id."'");

        return true;
    }

    public function deleteWishlistProduct($wishlist_id, $product_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."wishlist_products` WHERE wishlist_id = '".$wishlist_id."' and product_id = ".$product_id);

        return true;
    }

    public function updateWishlistProduct($wishlist_id, $product_id, $quantity)
    {
        $this->db->query('UPDATE `'.DB_PREFIX.'wishlist_products`  SET quantity = '.$quantity." WHERE wishlist_id = '".$wishlist_id."' and product_id = ".$product_id);

        return true;
    }

    public function getTotalWishlist()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'wishlist` w WHERE customer_id = '.(int) $this->customer->getId());

        //return $query;
        return $query->row['total'];
    }

    public function getTotalWishlistQuantity()
    {
        $query = $this->db->query('SELECT  SUM(wp.quantity) AS total FROM `'.DB_PREFIX.'wishlist` w join `'.DB_PREFIX.'wishlist_products` wp on w.wishlist_id =wp.wishlist_id WHERE customer_id = '.(int) $this->customer->getId());

        //return $query;
        return $query->row['total'];
    }

    public function getWishlistPresent($name)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'wishlist` w WHERE customer_id = '.(int) $this->customer->getId()." and name ='".$name."'");

        //return $query;
        return $query->row['total'];
    }

    public function getWishlistID($name)
    {
        $query = $this->db->query('SELECT wishlist_id AS total FROM `'.DB_PREFIX.'wishlist` w WHERE customer_id = '.(int) $this->customer->getId()." and name ='".$name."'");

        //return $query;
        return $query->row['total'];
    }

    public function createWishlist($name)
    {
        $query = $this->db->query('INSERT into `'.DB_PREFIX.'wishlist` set customer_id = '.(int) $this->customer->getId().", name ='".$name."'");

        $log = new Log('error.log');

        $wishlist_id = $this->db->getLastId();
        $log->write($wishlist_id);
        $log->write('wishlist_id');

        $query = $this->db->query('UPDATE  `'.DB_PREFIX.'wishlist` set wishlist_id = '.(int) $wishlist_id." where id ='".$wishlist_id."'");

        return $wishlist_id;
    }

    public function addProductToWishlist($wishlist_id, $product_id)
    {
        $query = $this->db->query('INSERT into `'.DB_PREFIX.'wishlist_products` set wishlist_id = '.(int) $wishlist_id.",product_id ='".$product_id."',quantity = 1");

        //return $query;
        return true;
    }

    public function addProductToWishlistWithQuantity($wishlist_id, $product_id, $quantity,$product_note='')
    {
        $query1 = $this->db->query('Select COUNT(*) AS total from `'.DB_PREFIX.'wishlist_products` where wishlist_id = '.(int) $wishlist_id.' and product_id ='.$product_id);
        if ($query1->row['total'] > 0) {
            //$query = $this->db->query("UPDATE  `" . DB_PREFIX . "wishlist_products` set quantity = " . (int) $quantity ." where wishlist_id ='".$wishlist_id."'" );
            if($product_note!=""){
            $query = $this->db->query('UPDATE `'.DB_PREFIX.'wishlist_products`  SET quantity = '.$quantity.",product_note ='".$product_note."' WHERE wishlist_id = '".$wishlist_id."' and product_id = ".$product_id);
            }else{
            $query = $this->db->query('UPDATE `'.DB_PREFIX.'wishlist_products`  SET quantity = '.$quantity." WHERE wishlist_id = '".$wishlist_id."' and product_id = ".$product_id);
            }
        } else {
            $query = $this->db->query('INSERT into `'.DB_PREFIX.'wishlist_products` set wishlist_id = '.(int) $wishlist_id.",product_id ='".$product_id."',product_note ='".$product_note."',quantity = ".$quantity.'');
        }
        //return $query;
        return true;
    }

    public function getProductOfWishlist($wishlist_id, $product_id)
    {
        $query = $this->db->query('Select * from `'.DB_PREFIX.'wishlist_products` where wishlist_id = '.(int) $wishlist_id.' and product_id ='.$product_id);

        return $query->row;
    }

    public function getWishlistPresentById($id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'wishlist` w WHERE customer_id = '.(int) $this->customer->getId()." and wishlist_id ='".$id."'");

        //return $query;
        return $query->row['total'];
    }

    public function getWishlistPresentForCustomer()
    {
        $query = $this->db->query('SELECT  wishlist_id AS total FROM `'.DB_PREFIX.'wishlist` w WHERE customer_id = '.(int) $this->customer->getId().'');

        //return $query;
        return $query->row['total'];
    }

    public function getWishlistIDCustomerProduct($product_id)
    {
        $query = $this->db->query('SELECT  COUNT(*) AS total FROM `'.DB_PREFIX.'wishlist` w join `'.DB_PREFIX.'wishlist_products` wp on w.wishlist_id =wp.wishlist_id WHERE customer_id = '.(int) $this->customer->getId().' and product_id ='.$product_id);

        // echo "<pre>";print_r($query);die;
        return $query->row['total'];
    }

    public function deleteWishlistProductByID($product_id)
    {
        $query = $this->db->query('SELECT  wishlist_id AS id FROM `'.DB_PREFIX.'wishlist` w WHERE customer_id = '.(int) $this->customer->getId().'');
        $wishlistID = $query->row['id'];

        if ($wishlistID > 0) {
            // echo ("DELETE FROM `" . DB_PREFIX . "wishlist_products` wp  WHERE wishlist_id = " . (int)$wishlistID . " and product_id = ".$product_id);die;

            $query = $this->db->query('DELETE FROM `'.DB_PREFIX.'wishlist_products`   WHERE wishlist_id = '.(int) $wishlistID.' and product_id = '.$product_id);

            return true;
        }

        return false;
    }

    public function CheckSaveBasketExits($list_name)
    {
        $query = $this->db->query('Select * from `'.DB_PREFIX."wishlist` where name = '".$list_name."' and customer_id = ".(int) $this->customer->getId().'');

        return $query->row;
    }



  
}
