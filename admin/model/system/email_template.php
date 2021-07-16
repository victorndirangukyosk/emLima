<?php

class ModelSystemEmailtemplate extends Model
{
    public function editEmailTemplate($email_template, $data)
    {
        $this->trigger->fire('pre.admin.emailTempalte.edit', $data);

        $email_template = $this->request->get['email_template'];

        $item = explode('_', $email_template);
        $sql = 'SELECT id FROM '.DB_PREFIX."email WHERE type = '".$item[0]."' AND text_id = '".(int) $item[1]."'";

        // echo "<pre>";print_r($item);die;
        if (count($item) > 2) {
            $sql .= "AND text = '{$item[2]}'";
        }
        $query = $this->db->query($sql);
        //echo $sql;die;

        $email_id = $query->row['id'];

        $sql = 'SELECT id FROM '.DB_PREFIX."email_description WHERE email_id = '".(int) $email_id."'";
        $query = $this->db->query($sql);

        //$this->db->query( "DELETE FROM " . DB_PREFIX . "email_description WHERE email_id = '" . (int) $email_id . "'" );

        if (!empty($query->row)) {
            foreach ($data['email_template_description'] as $language_id => $value) {
                $sql = 'UPDATE '.DB_PREFIX.'email_description SET';
                $sql .= " name = '".$this->db->escape($value['name'])."', description = '".$this->db->escape($value['description'])."' ,sms='".$value['sms']."',sms_status='".$value['sms_status']."',email_status='".$value['email_status']."' ,mobile_notification='".$value['mobile_notification']."',mobile_notification_title='".$value['mobile_notification_title']."',mobile_notification_template='".$value['mobile_notification_template']."'";
                $sql .= " WHERE email_id = '".(int) $email_id."' AND language_id = '".(int) $language_id."' ";

                $this->db->query($sql);
                //echo $this->db->last_query();die;
            }
        } else {
            foreach ($data['email_template_description'] as $language_id => $value) {
                $sql = 'INSERT INTO '.DB_PREFIX.'email_description SET';
                $sql .= " email_id = '".(int) $email_id."', name = '".$this->db->escape($value['name'])."', description = '".$this->db->escape($value['description'])."',";
                $sql .= " status = '1', sms='".$value['sms']."',sms_status='".$value['sms_status']."',email_status='".$value['email_status']."', language_id = '".(int) $language_id."',mobile_notification='".$value['mobile_notification']."',mobile_notification_title='".$value['mobile_notification_title']."',mobile_notification_template='".$value['mobile_notification_template']."'";
                $this->db->query($sql);
            }
        }

        $this->trigger->fire('post.admin.emailTempalte.edit', $email_template);
    }

    public function getEmailTempalte($email_template)
    {
        $item = explode('_', $email_template);
        //echo "<pre>";print_r($item);die;
        $sql = 'SELECT * FROM '.DB_PREFIX.'email AS e';
        $sql .= ' LEFT JOIN '.DB_PREFIX.'email_description AS ed ON ed.email_id = e.id';
        $sql .= " WHERE e.type = '{$item[0]}' AND e.text_id = '{$item[1]}'";

        if (count($item) > 2) {
            $sql .= "AND e.text = '{$item[2]}'";
        }

        $query = $this->db->query($sql);
        // echo "<pre>";print_r($sql);die;
        foreach ($query->rows as $result) {
            $email_tempalte_data[$result['language_id']] = [
                'text' => $result['text'],
                'text_id' => $result['text_id'],
                'type' => $result['type'],
                'context' => $result['context'],
                'name' => $result['name'],
                'description' => $result['description'],
                'status' => $result['status'],
                'sms' => $result['sms'],
                'sms_status' => $result['sms_status'],
                'email_status' => $result['email_status'],
                'mobile_notification' => $result['mobile_notification'],
                'mobile_notification_template' => $result['mobile_notification_template'],
                'mobile_notification_title' => $result['mobile_notification_title'],
            ];
        }

        return $email_tempalte_data;
    }

    public function getEmailTempaltes($data = [])
    {
        $sql = 'SELECT * FROM `'.DB_PREFIX.'email` AS e';

        $isWhere = 0;
        $_sql = [];

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;

            $sql .= ' LEFT JOIN `'.DB_PREFIX.'email_description` AS ed ON e.id = ed.email_id ';
            $_sql[] = "ed.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_text']) && !is_null($data['filter_text'])) {
            $isWhere = 1;

            $_sql[] = "e.text LIKE '".$this->db->escape($data['filter_text'])."%'";
        }

        if (isset($data['filter_context']) && !is_null($data['filter_context'])) {
            $isWhere = 1;

            $_sql[] = "e.context LIKE '".$this->db->escape($data['filter_context'])."%'";
        }

        if (isset($data['filter_type']) && !is_null($data['filter_type'])) {
            $isWhere = 1;

            $filterType = $this->_getEmailTypes($data['filter_type']);

            $_sql[] = "e.type = '".$this->db->escape($filterType)."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "e.status LIKE '".$this->db->escape($data['filter_status'])."%'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $sort_data = [
            'name',
            'sort_order',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY e.type';
        }

        if (isset($data['order']) && ('DESC' == $data['order'])) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= ' LIMIT '.(int) $data['start'].','.(int) $data['limit'];
        }

        $query = $this->db->query($sql);

        if (!empty($query->num_rows)) {
            foreach ($query->rows as $key => $email_temp) {
                $query->rows[$key]['textOriginal'] = $query->rows[$key]['text'];
                if ('order' == $email_temp['type']) {
                    $_result = $this->db->query('SELECT * FROM `'.DB_PREFIX."order_status` WHERE order_status_id ='".$email_temp['text_id']."' AND language_id ='".$this->config->get('config_language_id')."'");
                    if (!empty($_result->num_rows) && !empty($_result->row['name'])) {
                        $query->rows[$key]['text'] = $_result->row['name'];
                    }
                }
            }
        }

        $result = $query->rows;

        return $result;
    }

    protected function _getEmailTypes($item)
    {
        $result = ['order', 'customer', 'affiliate', 'Contact', 'contact', 'cron', 'mail'];
        if ($item < 1 || $item > 7) {
            $item = 1;
        }

        return $result[$item - 1];
    }

    public function getEmailTempaltesStores($email_template)
    {
        $manufacturer_store_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."manufacturer_to_store WHERE email_template = '".(int) $email_template."'");

        foreach ($query->rows as $result) {
            $manufacturer_store_data[] = $result['store_id'];
        }

        return $manufacturer_store_data;
    }

    public function getTotalEmailTempaltes($data)
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'email AS e';

        $isWhere = 0;
        $_sql = [];

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;

            $sql .= ' LEFT JOIN `'.DB_PREFIX.'email_description` AS ed ON e.id = ed.email_id ';
            $_sql[] = "ed.name LIKE '".$this->db->escape($data['filter_name'])."%'";
        }

        if (isset($data['filter_text']) && !is_null($data['filter_text'])) {
            $isWhere = 1;

            $_sql[] = "e.text LIKE '".$this->db->escape($data['filter_text'])."%'";
        }

        if (isset($data['filter_context']) && !is_null($data['filter_context'])) {
            $isWhere = 1;

            $_sql[] = "e.context LIKE '".$this->db->escape($data['filter_context'])."%'";
        }

        if (isset($data['filter_type']) && !is_null($data['filter_type'])) {
            $isWhere = 1;

            $filterType = $this->_getEmailTypes($data['filter_type']);

            $_sql[] = "e.type = '".$this->db->escape($filterType)."'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "e.status LIKE '".$this->db->escape($data['filter_status'])."%'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getShortCodes($email_template)
    {
        $item = explode('_', $email_template);

        $result = [];

        //echo "<pre>";print_r($email_template);die;

        $codes = [];

        switch ($item[0]) {
            case 'admin':
                $codes = $this->emailtemplate->getLoginFind();
                break;
            case 'affiliate':
                $codes = $this->emailtemplate->getAffiliateFind();
                break;
            case 'contact':
                $codes = $this->emailtemplate->getContactFind();
                break;
            case 'customer':
                $codes = $this->emailtemplate->getCustomerFind();
                break;
            case 'order':
                $codes = $this->emailtemplate->getOrderAllFind();
                break;
            case 'reviews':
                $codes = $this->emailtemplate->getReviewFind();
                break;
            case 'voucher':
                $codes = $this->emailtemplate->getVoucherFind();
                break;
            case 'invoice':
                $codes = $this->emailtemplate->getInvoiceFind();
                break;
            case 'stock':
                $codes = $this->emailtemplate->getStockFind();
                break;
            case 'return':
                $codes = $this->emailtemplate->getReturnFind();
                break;
            case 'coming_soon':
                $codes = $this->emailtemplate->getComingSoonFind();
                break;
            case 'referral':
                $codes = $this->emailtemplate->getReferralFind();
                break;
            case 'login':
                $codes = $this->emailtemplate->getLoginFind();
                break;
            case 'tax':
                $codes = $this->emailtemplate->getTaxFind();
                break;
            case 'total':
                $codes = $this->emailtemplate->getTotalFind();
                break;
            case 'products':
                $codes = $this->emailtemplate->getProductFind();
                break;
            case 'comment':
                $codes = $this->emailtemplate->getCommentFind();
                break;
            case 'order_voucher':
                $codes = $this->emailtemplate->getOrderVoucherFind();
                break;
            case 'seller':
                $codes = $this->emailtemplate->getSellerFind();
                break;
            case 'loginotp':
            $codes = $this->emailtemplate->getLoginOTPFind();
            break;
            case 'registerotp':
            $codes = $this->emailtemplate->getRegisterOTPFind();
            break;
            case 'vendororder':
            $codes = $this->emailtemplate->getVendorOrderFind();
            break;
            case 'vendorreturn':
            $codes = $this->emailtemplate->getVendorReturnFind();
            break;

            case 'comingsoon':
            $codes = $this->emailtemplate->getComingSoonFind();
            break;

            case 'ConsolidatedOrderSheet':
                $codes = $this->emailtemplate->getConsolidatedOrderSheetFind();
                break;

            case 'NewDeviceLogin':
            $codes = $this->emailtemplate->getNewDeviceLoginFind();
            break;

            case 'StockOut':
            $codes = $this->emailtemplate->getStockOutFind();
            break;

            case 'Feedback':
                $codes = $this->emailtemplate->getFeedbackFind();
                break;
                case 'customerstatement':
                    $codes = $this->emailtemplate->getCustomerStatementFind();
                    break;
        }

        foreach ($codes as $code) {
            $result[] = [
                'code' => $code,
                'text' => $this->language->get($code),
            ];
        }

        return $result;
    }

    public function getEmailTemplate($email_template)
    {
        $item = explode('_', $email_template);

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX.'email AS e LEFT JOIN '.DB_PREFIX."email_description AS ed ON ed.email_id = e.id WHERE e.type = '".$item[0]."' AND e.text_id = '".$item[1]."'");

        foreach ($query->rows as $result) {
            $email_template_data[$result['language_id']] = [
                'text' => $result['text'],
                'text_id' => $result['text_id'],
                'type' => $result['type'],
                'context' => $result['context'],
                'name' => $result['name'],
                'description' => $result['description'],
                'status' => $result['status'],
            ];
        }

        return $email_template_data;
    }
}
