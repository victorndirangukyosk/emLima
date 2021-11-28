<?php

class ModelTransactionsPackage extends Model
{
    public function getTotal($data)
    {
        $sql = 'select count(*) as total from '.DB_PREFIX.'package_transactions pt';
        $sql .= ' INNER JOIN '.DB_PREFIX.'packages p on p.package_id = pt.package_id';
        $sql .= ' INNER JOIN '.DB_PREFIX.'user u on u.user_id = pt.vendor_id';

        $implode = [];

        if ($data['filter_package']) {
            $implode[] = 'p.name = "'.$data['filter_package'].'"';
        }

        if ($data['filter_vendor']) {
            $implode[] = 'u.username = "'.$data['filter_vendor'].'"';
        }

        if ($data['filter_transaction_no']) {
            $implode[] = 'pt.transaction_no = "'.$data['filter_transaction_no'].'"';
        }

        if ($data['filter_amount']) {
            $implode[] = 'pt.amount = "'.$data['filter_amount'].'"';
        }

        if ($data['filter_date_added']) {
            $implode[] = 'pt.date_added = "'.$data['filter_date_added'].'"';
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        return $this->db->query($sql)->row['total'];
    }

    public function getList($data)
    {
        $sql = 'select p.name as package, u.username as vendor, pt.* from '.DB_PREFIX.'package_transactions pt';
        $sql .= ' INNER JOIN '.DB_PREFIX.'packages p on p.package_id = pt.package_id';
        $sql .= ' INNER JOIN '.DB_PREFIX.'user u on u.user_id = pt.vendor_id';

        $implode = [];

        if ($data['filter_package']) {
            $implode[] = 'p.name = "'.$data['filter_package'].'"';
        }

        if ($data['filter_vendor']) {
            $implode[] = 'u.username = "'.$data['filter_vendor'].'"';
        }

        if ($data['filter_transaction_no']) {
            $implode[] = 'pt.transaction_no = "'.$data['filter_transaction_no'].'"';
        }

        if ($data['filter_amount']) {
            $implode[] = 'pt.amount = "'.$data['filter_amount'].'"';
        }

        if ($data['filter_date_added']) {
            $implode[] = 'pt.date_added = "'.$data['filter_date_added'].'"';
        }

        if ($implode) {
            $sql .= ' WHERE '.implode(' AND ', $implode);
        }

        return $this->db->query($sql)->rows;
    }
}
