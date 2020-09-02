<?php

class ModelSaleVendorOrder extends Model
{
    public function getShopperInfo($shopper_id)
    {
        $sql = 'select CONCAT(firstname," ",lastname) as name, email, latitude, longitude,';
        $sql .= 'telephone, address from `'.DB_PREFIX.'user` where user_id="'.$shopper_id.'"';

        return $this->db->query($sql)->row;
    }

    public function getShopper($shopper_id)
    {
        $row = $this->db->query('select CONCAT(firstname," ",lastname) as name from `'.DB_PREFIX.'user` where user_id="'.$shopper_id.'"')->row;

        if ($row) {
            return $row['name'];
        }
    }

    public function getVendorOrder($vendor_order_id)
    {
        $sql = "SELECT o.invoice_prefix, vo.shopper_commision, vo.shopper_id, vo.vendor_id, o.payment_code, o.payment_method, vo.store_name, o.comment, c.name as shipping_city, o.shipping_contact_no, o.shipping_address, o.shipping_name, o.shipping_method, s.email, s.telephone, s.fax, o.invoice_no, o.shipping_code, vo.store_id, vo.payment_status, vo.vendor_order_id, o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, vo.order_status_id, vo.total, vo.currency_code, vo.currency_value, vo.date_added, vo.commision, vo.payment_status  FROM `".DB_PREFIX.'vendor_order` vo ';
        $sql .= 'inner join '.DB_PREFIX.'order o on vo.order_id = o.order_id ';
        $sql .= 'inner join '.DB_PREFIX.'store s on s.store_id = vo.store_id ';
        $sql .= 'left join '.DB_PREFIX.'city c on c.city_id = o.shipping_city_id ';
        $sql .= 'WHERE vo.vendor_order_id="'.$vendor_order_id.'"';

        if ($this->user->isVendor()) {
            $sql .= ' AND vo.vendor_id="'.$this->user->getId().'"';
        }

        return $this->db->query($sql)->row;
    }

    public function assign_shopper()
    {
        $sql = 'update `'.DB_PREFIX.'vendor_order` SET shopper_id="'.$this->request->post['shopper_id'].'" ';
        $sql .= 'WHERE vendor_order_id="'.$this->request->post['vendor_order_id'].'" ';

        $this->db->query($sql);

        //log action
        $this->db->query('INSERT INTO `'.DB_PREFIX.'shopper_order_log` SET shopper_id="'.$this->request->post['shopper_id'].'", vendor_order_id="'.$this->request->post['vendor_order_id'].'", action="assigned", date_added=NOW()');
    }

    /*
     * update payment status for sub order
     */
}
