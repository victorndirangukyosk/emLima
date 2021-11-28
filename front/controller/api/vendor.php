<?php

class Controllerapivendor extends Controller
{
    public function addUser($data = [])
    {
        //echo 'dddd';exit;
        $json = [];
        try {
            $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
            /*$sql = "INSERT INTO `" . DB_PREFIX . "user` SET "
                . "username = '" . trim($data['username']) . "', "
                . "user_group_id = '" . (int)$data['user_group_id'] . "', "
                . "salt = '" . trim($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', "
                . "password = '" . trim(sha1($salt . sha1($salt . sha1($data['password'])))) . "', "
                . "firstname = '" . trim($data['firstname']) . "', "
                . "lastname = '" . trim($data['lastname']) . "', "
                . "email = '" . trim($data['email']) . "', "
                . "commision = '" . trim($data['commision']) . "', "
                . "fixed_commision = '" . trim($data['fixed_commision']) . "', "
                //. "free_from = '" . trim($data['free_from']) . "', "
                //. "free_to = '" . trim($data['free_to']) . "', "
                . "tin_no = '" .trim($data['tin_no']) . "', "
                . "mobile = '" . trim($data['mobile']) . "', "
                . "telephone = '" . trim($data['telephone']) . "', "
                . "city_id = '" . trim($data['city_id']) . "', "
                . "address = '" . trim($data['address']) . "', "
                . "image = '" . trim($data['image']) . "', "
                . "status = '" . (int)$data['status'] . "', "
                . "date_added = NOW()";*/

            $sql = 'INSERT INTO `'.DB_PREFIX.'user` SET '
                ."username = '".trim($data['username'])."', "
                ."user_group_id = '".(int) $data['user_group_id']."', "
                ."salt = '".trim($salt = substr(md5(uniqid(rand(), true)), 0, 9))."', "
                ."password = '".trim(sha1($salt.sha1($salt.sha1($data['password']))))."', "
                ."firstname = '".trim($data['firstname'])."', "
                ."lastname = '".trim($data['lastname'])."', "
                ."email = '".trim($data['email'])."', "
                ."commision = '".trim($data['commision'])."', "
                ."fixed_commision = '".trim($data['fixed_commision'])."', "
                ."code = '".trim($data['code'])."', "
                ."ip = '".trim($data['ip'])."', "
                ."longitude = '".trim($data['longitude'])."', "
                ."latitude = '".trim($data['latitude'])."', "
                ."tin_no = '".trim($data['tin_no'])."', "
                ."mobile = '".trim($data['mobile'])."', "
                ."telephone = '".trim($data['telephone'])."', "
                ."city_id = '".trim($data['city_id'])."', "
                ."address = '".trim($data['address'])."', "
                ."image = '".trim($data['image'])."', "
                ."status = '".(int) $data['status']."', "
                ."business = '".(int) $data['business']."', "
                ."store_name = '".(int) $data['store_name']."', "
                ."type = '".(int) $data['type']."', "
                .'date_added = NOW()';

            $result = $conn->query($sql); //exit;
            $vendor_id = $conn->insert_id;
            $data['vendor_id'] = $vendor_id;
            //$json = array();
            $json['success'] = 'Vendor added successfully';
            $json['status'] = 200;
            $json['data'] = $data;
            $this->addVendorBank($data, $vendor_id);
            $this->add_excel_store_mapping($data, $vendor_id);
            //$json = $data;
            $this->response->addHeader('Content-Type: application/json');
        } catch (Exception $e) {
            $json['success'] = 'Vendor not added successfully';
            $json['status'] = 400;

            //echo 'ssss<pre>';print_r($e);exit;
        }

        //$this->addVendorBank($data,$vendor_id);
        //$this->add_excel_store_mapping($data,$vendor_id);
        //$json = $data;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
        $conn->close();
    }

    public function addVendorBank($data, $vendor_id)
    {
        $conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $conn->query('DELETE FROM '.DB_PREFIX."vendor_bank_account WHERE vendor_id = '".(int) $vendor_id."'");

        $conn->query('INSERT INTO '.DB_PREFIX."vendor_bank_account SET vendor_id = '".(int) $vendor_id."', bank_account_number = '".$data['bank_account_number']."', bank_account_name = '".trim($data['bank_account_name'])."', bank_name = '".trim($data['bank_name'])."', bank_branch_name = '".trim($data['bank_branch_name'])."', bank_account_type = '".trim($data['bank_account_type'])."'");
        $conn->close();
    }

    public function add_excel_store_mapping($data, $vendor_id)
    {
        if (isset($data['excel_store_mapping'])) {
            foreach ($data['excel_store_mapping'] as $key => $value) {
                $this->db->query('UPDATE '.DB_PREFIX."excel_store_mapping SET text = '".trim($value['text'])."', vendor_id = '".$vendor_id."', store_id = '".$value['store_id']."' WHERE id = '".(int) $key."'");
            }
        }

        if (isset($data['excel_store'])) {
            foreach ($data['excel_store'] as $key => $value) {
                $this->db->query('INSERT INTO '.DB_PREFIX."excel_store_mapping SET text = '".trim($value['text'])."', vendor_id = '".$vendor_id."', store_id = '".$value['store_id']."'");
            }
        }
    }
}
