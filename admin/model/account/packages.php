<?php

class ModelAccountPackages extends Model
{
    public function getPackage($package_id)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."packages WHERE package_id = '".(int) $package_id."'")->row;
    }

    //save package transaction
    public function savePayment($package_id, $data)
    {
        //remove prefix to get real package_id
        $package_id = str_replace($this->config->get('config_package_prefix'), '', $package_id);

        //add transaction history
        $this->db->query('insert into '.DB_PREFIX.'package_transactions set transaction_no="'.$data['mihpayid'].'", vendor_id="'.$this->user->getId().'", package_id="'.$package_id.'", date_added="'.date('Y-m-d').'", amount="'.$data['amount'].'"');

        //ad vendor active package
        //$this->db->query('delete from vendor_to_package where vendor_id = "'.$this->user->getId().'"');

        $data = $this->getPackage($package_id);
        $date_start = date('Y-m-d');
        $date_end = date('Y-m-d', strtotime('+'.$data['free_year'].' year +'.$data['free_month'].' month'));
        $this->db->query('insert into '.DB_PREFIX.'vendor_to_package set vendor_id="'.$this->user->getId().'", package_id="'.$package_id.'", name="'.$data['name'].'", priority="'.$data['priority'].'", date_start="'.$date_start.'", date_end="'.$date_end.'"');

        //get user
        $user = $this->db->query()->row;

        //if free account period than add
        if (strtotime($user['free_to']) > time()) {
            $date_end = date('Y-m-d', strtotime('+'.$data['free_year'].' year +'.$data['free_month'].' month', strtotime($user['free_to'])));
        }

        //update free_from, free_to
        $this->db->query('update '.DB_PREFIX.'user set free_from="'.$date_start.'", free_to="'.$date_end.'"');
    }

    public function getPackages($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'packages';

        $sql .= ' where status=1';

        $sql .= ' ORDER BY priority DESC';

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

    public function getTotal()
    {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'packages where status=1';

        return $this->db->query($sql)->row['total'];
    }

    public function getUser($user_id)
    {
        return $this->db->query('SELECT * FROM '.DB_PREFIX."user WHERE user_id = '".(int) $user_id."' AND status = '1'")->row;
    }

    public function getCity($city_id)
    {
        return $this->db->query('select name from '.DB_PREFIX.'city WHERE city_id="'.$city_id.'"')->row;
    }

    public function getVendorToPackage($vendor_id)
    {
        return $this->db->query('select * from '.DB_PREFIX.'vendor_to_package where vendor_id="'.$vendor_id.'" AND date_end > "'.date('Y-m-d').'" ORDER BY vp DESC')->row;
    }
}
