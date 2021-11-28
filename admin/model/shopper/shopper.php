<?php

class ModelShopperShopper extends Model
{
    /* Definitions:
       --------------------------------------------------------
       South latitudes are negative, east longitudes are positive

       Passed to function:
       --------------------------------------------------------
        lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)
        lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)
        unit = the unit you desire for results
               where: 'M' is statute miles (default)
                      'K' is kilometers
                      'N' is nautical miles
    */

    public function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
    {
        $theta = $lon1 - $lon2;

        $dist = rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta))));

        $miles = $dist * 60 * 1.1515;

        if ('K' == $unit) {
            return $miles * 1.609344;
        } elseif ('N' == $unit) {
            return $miles * 0.8684;
        } else {
            return $miles;
        }
    }

    public function orderShipped($order_id)
    {
        $last_track_info = $this->lastPosition($order_id);

        $distance = round($last_track_info['distance'], 2);

        $shopper_commision = round($distance * $this->config->get('config_dri_charge'), 2);

        $config_shipped_status = $this->config->get('config_shipped_status');

        if ($config_shipped_status) {
            $sql = 'update `'.DB_PREFIX.'vendor_order` SET shopper_commision="'.$shopper_commision.'", ';
            $sql .= 'shopper_distance="'.$distance.'", shppr_delivered_date="'.date('Y-m-d').'", ';
            $sql .= 'order_status_id="'.$config_shipped_status[0].'" WHERE vendor_order_id="'.$order_id.'"';

            $this->db->query($sql);

            //update payment if cod
            $sql = 'select vo.store_id, vo.order_id, o.payment_code from `'.DB_PREFIX.'vendor_order` vo inner join `'.DB_PREFIX.'order` o on o.order_id = vo.order_id ';
            $sql .= 'WHERE vendor_order_id="'.$order_id.'"';
            $order_info = $this->db->query($sql)->row;

            if ('cod' == $order_info) {
                $this->load->model('vendor/order');
                $this->model_vendor_order->payment_status($order_info['order_id'], 1, $order_info['store_id']);
            }

            //set order history
            $this->db->query('INSERT INTO '.DB_PREFIX."order_history SET order_id = '".(int) $order_info['order_id']."', order_status_id = '".(int) $config_shipped_status[0]."', notify = '0', comment = 'Order shipped by shopper', date_added = NOW()");
        }

        //log action
        $this->db->query('INSERT INTO `'.DB_PREFIX.'shopper_order_log` SET shopper_id="'.$this->user->getId().'", vendor_order_id="'.$order_id.'", action="fullfilled", date_added=NOW()');
    }

    public function getOrder($order_id)
    {
        $sql = 'select vo.*, o.shipping_name, o.shipping_address, o.shipping_contact_no, c.name as shipping_city,';
        $sql .= 'o.payment_method, o.shipping_method from `'.DB_PREFIX.'vendor_order` vo ';
        $sql .= 'inner join `'.DB_PREFIX.'order` o on o.order_id = vo.order_id ';
        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'WHERE vo.shopper_id="'.$this->user->getId().'" AND vo.vendor_order_id="'.$order_id.'"';

        return $this->db->query($sql)->row;
    }

    public function lastPosition($order_id)
    {
        $sql = 'select * from `'.DB_PREFIX.'shopper_track` WHERE vendor_order_id="'.$order_id.'" AND ';
        $sql .= 'vendor_id="'.$this->user->getId().'" ORDER BY track_id DESC LIMIT 1';

        return $this->db->query($sql)->row;
    }

    //save shopper current position

    public function savePosition($order_id, $latitude, $longitude, $total_distance)
    {
        $sql = 'insert into `'.DB_PREFIX.'shopper_track` SET vendor_order_id="'.$order_id.'", distance="'.$total_distance.'", ';
        $sql .= 'vendor_id="'.$this->user->getId().'", latitude="'.$latitude.'", longitude="'.$longitude.'"';

        $this->db->query($sql);

        //update shopper current position
        $this->db->query('update `'.DB_PREFIX.'user` set latitude="'.$latitude.'", longitude="'.$longitude.'" WHERE user_id="'.$this->user->getId().'"');
    }

    public function getTrackInfo($order_id)
    {
        //order info: total, customer_info
        $sql = 'select vo.*,o.payment_method, c.name as shipping_city, ';
        $sql .= 'o.shipping_name, o.shipping_address, o.shipping_contact_no ';
        $sql .= 'from `'.DB_PREFIX.'vendor_order` vo inner join `'.DB_PREFIX.'order` o on o.order_id = vo.order_id ';
        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = o.shipping_city_id ';
        $sql .= 'WHERE vo.vendor_order_id="'.$order_id.'"';

        $result = $this->db->query($sql)->row;

        //tracking info: latitude, longitude
        $result['path'] = $this->db->query('select * from `'.DB_PREFIX.'shopper_track` WHERE vendor_order_id="'.$order_id.'"')->rows;

        //get min, max values for map bounds
        $sql = 'select MIN(latitude) as lat_min, MIN(longitude) as lng_min, MAX(latitude) as lat_max, ';
        $sql .= 'MAX(longitude) as lng_max from `'.DB_PREFIX.'shopper_track` WHERE vendor_order_id="'.$order_id.'"';

        $result['limits'] = $this->db->query($sql)->row;

        return $result;
    }

    public function addUser($data)
    {
        $sql = 'INSERT INTO `'.DB_PREFIX.'user` SET '
                ."username = '".$this->db->escape($data['username'])."', "
                ."user_group_id = '".(int) $data['user_group_id']."', "
                ."salt = '".$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))."', "
                ."password = '".$this->db->escape(sha1($salt.sha1($salt.sha1($data['password']))))."', "
                ."firstname = '".$this->db->escape($data['firstname'])."', "
                ."lastname = '".$this->db->escape($data['lastname'])."', "
                ."email = '".$this->db->escape($data['email'])."', "
                ."mobile = '".$this->db->escape($data['mobile'])."', "
                ."telephone = '".$this->db->escape($data['telephone'])."', "
                ."city_id  = '".$this->db->escape($data['city_id'])."', "
                ."address  = '".$this->db->escape($data['address'])."', "
                ."image = '".$this->db->escape($data['image'])."', "
                ."status = '".(int) $data['status']."', "
                .'date_added = NOW()';

        $this->db->query($sql);

        return $this->db->getLastId();
    }

    public function editUser($user_id, $data)
    {
        $sql = 'update `'.DB_PREFIX.'user` SET '
                ."username = '".$this->db->escape($data['username'])."', "
                ."firstname = '".$this->db->escape($data['firstname'])."', "
                ."lastname = '".$this->db->escape($data['lastname'])."', "
                ."email = '".$this->db->escape($data['email'])."', "
                ."user_group_id = '".(int) $data['user_group_id']."', "
                ."mobile = '".$data['mobile']."', "
                ."telephone = '".$data['telephone']."', "
                ."city_id = '".$data['city_id']."', "
                ."address = '".$data['address']."', "
                ."status = '".$data['status']."' "
                ."WHERE user_id='".$user_id."'";

        $this->db->query($sql);

        if ($data['password']) {
            $this->db->query('UPDATE `'.DB_PREFIX."user` SET salt = '".$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))."', password = '".$this->db->escape(sha1($salt.sha1($salt.sha1($data['password']))))."' WHERE user_id = '".(int) $user_id."'");
        }
    }

    public function editPassword($user_id, $password)
    {
        $this->db->query('UPDATE `'.DB_PREFIX."user` SET salt = '".$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))."', password = '".$this->db->escape(sha1($salt.sha1($salt.sha1($password))))."', code = '' WHERE user_id = '".(int) $user_id."'");
    }

    public function editCode($email, $code)
    {
        $this->db->query('UPDATE `'.DB_PREFIX."user` SET code = '".$this->db->escape($code)."' WHERE LCASE(email) = '".$this->db->escape(utf8_strtolower($email))."'");
    }

    public function deleteUser($user_id)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."user` WHERE user_id = '".(int) $user_id."'");
    }

    public function getUser($user_id)
    {
        $query = $this->db->query('SELECT *, (SELECT ug.name FROM `'.DB_PREFIX.'user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `'.DB_PREFIX."user` u WHERE u.user_id = '".(int) $user_id."'");

        return $query->row;
    }

    public function getUserByUsername($username)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."user` WHERE username = '".$this->db->escape($username)."'");

        return $query->row;
    }

    public function getUserByCode($code)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."user` WHERE code = '".$this->db->escape($code)."' AND code != ''");

        return $query->row;
    }

    public function getUsers($data = [])
    {
        $sql = 'SELECT u.*, c.name as city FROM `'.DB_PREFIX.'user` u ';
        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = u.city_id ';
        $sql .= 'WHERE user_group_id IN ('.$this->config->get('config_shopper_group_ids').')';

        if (isset($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (isset($data['filter_user_name']) && !is_null($data['filter_user_name'])) {
            $sql .= " AND u.username LIKE '".$this->db->escape($data['filter_user_name'])."%'";
        }

        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group'])) {
            $sql .= ' AND u.user_group_id LIKE ( SELECT ug.user_group_id FROM `'.DB_PREFIX."user_group` ug WHERE ug.name LIKE '".$this->db->escape($data['filter_user_group'])."%') ";
        }

        if (isset($data['filter_first_name']) && !is_null($data['filter_first_name'])) {
            $sql .= " AND u.firstname LIKE '".$this->db->escape($data['filter_first_name'])."%'";
        }

        if (isset($data['filter_last_name']) && !is_null($data['filter_last_name'])) {
            $sql .= " AND u.lastname LIKE '".$this->db->escape($data['filter_last_name'])."%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $sql .= " AND u.email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND u.status LIKE '".$this->db->escape($data['filter_status'])."%'";
        }

        $sort_data = [
            'u.username',
            'u.status',
            'u.date_added',
            'c.name',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY u.username';
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

        return $query->rows;
    }

    public function getTotalUsers()
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'user`';

        //filter shopper groups
        $sql .= ' WHERE user_group_id IN ('.$this->config->get('config_shopper_group_ids').')';

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalUsersFilter($data)
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX.'user` u ';
        $sql .= 'left join `'.DB_PREFIX.'city` c on c.city_id = u.city_id ';

        //filter shopper groups
        $sql .= ' WHERE user_group_id IN ('.$this->config->get('config_shopper_group_ids').')';

        if (isset($data['filter_city'])) {
            $sql .= " AND c.name LIKE '".$this->db->escape($data['filter_city'])."%'";
        }

        if (isset($data['filter_user_name']) && !is_null($data['filter_user_name'])) {
            $sql .= " AND u.username LIKE '".$this->db->escape($data['filter_user_name'])."%'";
        }

        if (isset($data['filter_user_group']) && !is_null($data['filter_user_group'])) {
            $sql .= ' AND u.user_group_id LIKE ( SELECT ug.user_group_id FROM `'.DB_PREFIX."user_group` ug WHERE ug.name LIKE '".$this->db->escape($data['filter_user_group'])."%') ";
        }

        if (isset($data['filter_first_name']) && !is_null($data['filter_first_name'])) {
            $sql .= " AND u.firstname LIKE '".$this->db->escape($data['filter_first_name'])."%'";
        }

        if (isset($data['filter_last_name']) && !is_null($data['filter_last_name'])) {
            $sql .= " AND u.lastname LIKE '".$this->db->escape($data['filter_last_name'])."%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $sql .= " AND u.email LIKE '".$this->db->escape($data['filter_email'])."%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND u.status LIKE '".$this->db->escape($data['filter_status'])."%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalUsersByGroupId($user_group_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."user` WHERE user_group_id = '".(int) $user_group_id."'");

        return $query->row['total'];
    }

    public function getTotalUsersByEmail($email)
    {
        $sql = 'SELECT COUNT(*) AS total FROM `'.DB_PREFIX."user` WHERE LCASE(email) = '".$this->db->escape(utf8_strtolower($email))."'";

        //filter shopper groups
        $sql .= ' AND user_group_id IN ('.$this->config->get('config_shopper_group_ids').')';

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTimeslots($user_id)
    {
        $result = [
            0 => $this->db->query('select * from `'.DB_PREFIX.'shopper_timeslot` WHERE shopper_id="'.$user_id.'" AND weekday="0"')->rows,
            1 => $this->db->query('select * from `'.DB_PREFIX.'shopper_timeslot` WHERE shopper_id="'.$user_id.'" AND weekday="1"')->rows,
            2 => $this->db->query('select * from `'.DB_PREFIX.'shopper_timeslot` WHERE shopper_id="'.$user_id.'" AND weekday="2"')->rows,
            3 => $this->db->query('select * from `'.DB_PREFIX.'shopper_timeslot` WHERE shopper_id="'.$user_id.'" AND weekday="3"')->rows,
            4 => $this->db->query('select * from `'.DB_PREFIX.'shopper_timeslot` WHERE shopper_id="'.$user_id.'" AND weekday="4"')->rows,
            5 => $this->db->query('select * from `'.DB_PREFIX.'shopper_timeslot` WHERE shopper_id="'.$user_id.'" AND weekday="5"')->rows,
            6 => $this->db->query('select * from `'.DB_PREFIX.'shopper_timeslot` WHERE shopper_id="'.$user_id.'" AND weekday="6"')->rows, ];

        return $result;
    }

    public function saveTimeslots($user_id, $data)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX.'shopper_timeslot` WHERE shopper_id="'.$user_id.'"');

        foreach ($data['timeslots'] as $weekday => $timeslots) {
            foreach ($timeslots as $timeslot) {
                $this->db->query('INSERT INTO `'.DB_PREFIX.'shopper_timeslot` SET shopper_id="'.$user_id.'", from_time="'.$timeslot['from_time'].'", to_time="'.$timeslot['to_time'].'", weekday="'.$weekday.'"');
            }
        }
    }

    public function addCredit($shopper_id, $description = '', $amount = '', $order_id = 0)
    {
        $user_info = $this->getUser($shopper_id);

        if ($user_info) {
            $this->db->query('INSERT INTO '.DB_PREFIX."shopper_wallet SET shopper_id = '".(int) $shopper_id."', order_id = '".(int) $order_id."', description = '".$this->db->escape($description)."', amount = '".(float) $amount."', date_added = NOW()");

            $this->load->language('mail/vendor');

            $store_name = $this->config->get('config_name');

            $message = sprintf($this->language->get('text_credit_received'), $this->currency->format($amount, $this->config->get('config_currency')))."\n\n";
            $message .= sprintf($this->language->get('text_credit_total'), $this->currency->format($this->getCreditTotal($shopper_id)));

            $mail = new Mail($this->config->get('config_mail'));
            $mail->setTo($user_info['email']);
            $mail->setFrom($this->config->get('config_from_email'));
            $mail->setSender($store_name);
            $mail->setSubject(sprintf($this->language->get('text_credit_subject'), $this->config->get('config_name')));
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();
        }
    }

    public function deleteCredit($order_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."shopper_wallet WHERE order_id = '".(int) $order_id."'");
    }

    public function getCredits($shopper_id, $start = 0, $limit = 10)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."shopper_wallet WHERE shopper_id = '".(int) $shopper_id."' ORDER BY date_added DESC LIMIT ".(int) $start.','.(int) $limit);

        return $query->rows;
    }

    public function getTotalCredits($shopper_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total  FROM '.DB_PREFIX."shopper_wallet WHERE shopper_id = '".(int) $shopper_id."'");

        return $query->row['total'];
    }

    public function getCreditTotal($shopper_id)
    {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM '.DB_PREFIX."shopper_wallet WHERE shopper_id = '".(int) $shopper_id."'");

        return $query->row['total'];
    }

    public function getTotalCreditsByOrderId($order_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."shopper_wallet WHERE order_id = '".(int) $order_id."'");

        return $query->row['total'];
    }

    //commision for today delivered orders

    public function getTodayTotalCommision($shopper_id)
    {
        $sql = 'select sum(shopper_commision) as total from `'.DB_PREFIX.'vendor_order` vo ';
        $sql .= 'WHERE vo.shopper_id="'.$shopper_id.'" ';
        $sql .= 'AND vo.shppr_delivered_date = "'.date('Y-m-d').'"';

        return $this->db->query($sql)->row['total'];
    }

    public function getTodayOrders($shopper_id)
    {
        $sql = 'select * from `'.DB_PREFIX.'vendor_order` vo ';
        $sql .= 'WHERE vo.shopper_id="'.$shopper_id.'" ';
        $sql .= 'AND vo.shppr_delivered_date = "'.date('Y-m-d').'"';

        return $this->db->query($sql)->rows;
    }

    public function getTodayRoute($shopper_id)
    {
        $sql = 'select st.* from `'.DB_PREFIX.'shopper_track` st ';
        $sql .= 'inner join `'.DB_PREFIX.'vendor_order` vo on vo.vendor_order_id = st.vendor_order_id ';
        $sql .= 'WHERE vo.shopper_id="'.$shopper_id.'" ';
        $sql .= 'AND vo.shppr_delivered_date="'.date('Y-m-d').'"';

        return $this->db->query($sql)->rows;
    }

    public function getLimits($shopper_id)
    {
        $sql = 'select MIN(st.latitude) as lat_min, MIN(st.longitude) as lng_min, MAX(st.latitude) as lat_max, ';
        $sql .= 'MAX(st.longitude) as lng_max from `'.DB_PREFIX.'shopper_track` st ';
        $sql .= 'inner join `'.DB_PREFIX.'vendor_order` vo on vo.vendor_order_id = st.vendor_order_id ';
        $sql .= 'WHERE vo.shppr_delivered_date="'.date('Y-m-d').'" AND vo.shopper_id="'.$shopper_id.'"';

        return $this->db->query($sql)->row;
    }

    public function updatePosition($latitude, $longitude, $user_id)
    {
        return $this->db->query('update `'.DB_PREFIX.'user` set latitude="'.$latitude.'", longitude="'.$longitude.'" WHERE user_id="'.$user_id.'"');
    }
}
