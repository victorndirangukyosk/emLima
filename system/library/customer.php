<?php

class Customer {

    private $customer_id;
    private $firstname;
    private $lastname;
    private $email;
    private $telephone;
    private $fax;
    private $newsletter;
    private $customer_group_id;
    private $address_id;
    private $member_upto;
    private $sms_notification;
    private $mobile_notification;
    private $email_notification;
    private $payment_terms;
    private $customer_category;
    private $pezesha_customer_id;
    private $pezesha_customer_uuid;
    private $pezesha_identifier;

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->db = $registry->get('db');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');

        if (isset($this->session->data['customer_id'])) {
            $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . (int) $this->session->data['customer_id'] . "' AND status = '1'");

            if ($customer_query->num_rows) {

                /* SET CUSTOMER CATEGORY */
                if ($customer_query->row['customer_id'] > 0 && $customer_query->row['parent'] > 0) {
                    $parent_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($customer_query->row['parent']) . "' AND status = '1' AND approved='1'");
                    if ($customer_query->num_rows > 0 && $parent_customer_query->row['customer_id'] > 0) {
                        $this->customer_category = $parent_customer_query->row['customer_category'];
                    } else {
                        $this->customer_category = NULL;
                    }
                }

                if ($customer_query->row['customer_id'] > 0 && ($customer_query->row['parent'] == NULL || $customer_query->row['parent'] == 0)) {
                    $this->customer_category = $customer_query->row['customer_category'];
                }
                /* SET CUSTOMER CATEGORY */

                /* SET CUSTOMER PEZESHA */
                if ($customer_query->row['customer_id'] > 0 && ($customer_query->row['parent'] == NULL || $customer_query->row['parent'] == 0)) {
                    $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $this->session->data['customer_id'] . "'");
                }
                if ($customer_query->row['customer_id'] > 0 && $customer_query->row['parent'] > 0) {
                    $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $customer_query->row['parent'] . "'");
                }
                if ($customer_query->num_rows > 0 && $pezesha_customer_query->num_rows > 0 && $pezesha_customer_query->row['customer_id'] > 0) {
                    $this->pezesha_customer_id = $pezesha_customer_query->row['pezesha_customer_id'];
                    $this->pezesha_customer_uuid = $pezesha_customer_query->row['customer_uuid'];
                    $this->pezesha_identifier = $pezesha_customer_query->row['customer_id'];
                } else {
                    $this->pezesha_customer_id = NULL;
                    $this->pezesha_customer_uuid = NULL;
                    $this->pezesha_identifier = NULL;
                }
                /* SET CUSTOMER PEZESHA */
                /* SET CUSTOMER PEZESHA */

                $this->customer_id = $customer_query->row['customer_id'];
                $this->firstname = $customer_query->row['firstname'];
                $this->lastname = $customer_query->row['lastname'];
                $this->email = $customer_query->row['email'];
                $this->telephone = $customer_query->row['telephone'];
                $this->fax = $customer_query->row['fax'];
                $this->newsletter = $customer_query->row['newsletter'];
                $this->customer_group_id = $customer_query->row['customer_group_id'];
                $this->address_id = $customer_query->row['address_id'];
                $this->member_upto = $customer_query->row['member_upto'];
                $this->sms_notification = $customer_query->row['sms_notification'];
                $this->mobile_notification = $customer_query->row['mobile_notification'];
                $this->email_notification = $customer_query->row['email_notification'];
                $this->payment_terms = $customer_query->row['payment_terms'];

                $this->db->query('UPDATE ' . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int) $this->customer_id . "'");

                $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

                if (!$query->num_rows) {
                    $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_ip SET customer_id = '" . (int) $this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
                }
            } else {
                $this->logout();
            }
        }
    }

    public function login($email, $password, $override = false) {
        if ($override) {
            $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");
        } else {
            $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1' AND approved = '1'");
        }

        if ($customer_query->num_rows) {
            $this->session->data['customer_id'] = $customer_query->row['customer_id'];
            $this->session->data['parent'] = $customer_query->row['parent'];
            $log = new Log('error.log');
            $log->write('FROM HERE PARENT CUSTOMER SESSION ASSIGN system_library_customer.php login');

            /* SET CUSTOMER CATEGORY */
            if ($customer_query->row['customer_id'] > 0 && $customer_query->row['parent'] > 0) {
                $parent_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($customer_query->row['parent']) . "' AND status = '1' AND approved='1'");
                if ($customer_query->num_rows > 0 && $parent_customer_query->row['customer_id'] > 0) {
                    $this->customer_category = $parent_customer_query->row['customer_category'];
                } else {
                    $this->customer_category = NULL;
                }
            }

            if ($customer_query->row['customer_id'] > 0 && ($customer_query->row['parent'] == NULL || $customer_query->row['parent'] == 0)) {
                $this->customer_category = $customer_query->row['customer_category'];
            }
            /* SET CUSTOMER CATEGORY */

            /* SET CUSTOMER PEZESHA */
            if ($customer_query->row['customer_id'] > 0 && ($customer_query->row['parent'] == NULL || $customer_query->row['parent'] == 0)) {
                $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $this->session->data['customer_id'] . "'");
            }
            if ($customer_query->row['customer_id'] > 0 && $customer_query->row['parent'] > 0) {
                $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $customer_query->row['parent'] . "'");
            }
            if ($customer_query->num_rows > 0 && $pezesha_customer_query->num_rows > 0 && $pezesha_customer_query->row['customer_id'] > 0) {
                $this->pezesha_customer_id = $pezesha_customer_query->row['pezesha_customer_id'];
                $this->pezesha_customer_uuid = $pezesha_customer_query->row['customer_uuid'];
                $this->pezesha_identifier = $pezesha_customer_query->row['customer_id'];
            } else {
                $this->pezesha_customer_id = NULL;
                $this->pezesha_customer_uuid = NULL;
                $this->pezesha_identifier = NULL;
            }
            /* SET CUSTOMER PEZESHA */

            /* if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
              $cart = unserialize($customer_query->row['cart']);

              foreach ($cart as $key => $value) {
              if (!array_key_exists($key, $this->session->data['cart'])) {
              $this->session->data['cart'][$key] = $value;
              } else {
              $this->session->data['cart'][$key] += $value;
              }
              }
              } */

            if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
                if (!isset($this->session->data['wishlist'])) {
                    $this->session->data['wishlist'] = [];
                }

                $wishlist = unserialize($customer_query->row['wishlist']);

                foreach ($wishlist as $product_id) {
                    if (!in_array($product_id, $this->session->data['wishlist'])) {
                        $this->session->data['wishlist'][] = $product_id;
                    }
                }
            }

            $this->customer_id = $customer_query->row['customer_id'];
            $this->firstname = $customer_query->row['firstname'];
            $this->lastname = $customer_query->row['lastname'];
            $this->email = $customer_query->row['email'];
            $this->telephone = $customer_query->row['telephone'];
            $this->fax = $customer_query->row['fax'];
            $this->newsletter = $customer_query->row['newsletter'];
            $this->customer_group_id = $customer_query->row['customer_group_id'];
            $this->address_id = $customer_query->row['address_id'];
            $this->sms_notification = $customer_query->row['sms_notification'];
            $this->mobile_notification = $customer_query->row['mobile_notification'];
            $this->email_notification = $customer_query->row['email_notification'];
            $this->payment_terms = $customer_query->row['payment_terms'];

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int) $this->customer_id . "'");

            return true;
        } else {
            return false;
        }
    }

    public function loginByPhone($customer_id, $override = true) {
        if ($override) {
            $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($customer_id) . "' AND status = '1' AND approved='1'");
        } else {
            $customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($customer_id) . "' AND status = '1'  AND approved='1'");
        }

        if ($customer_query->num_rows) {
            $this->session->data['customer_id'] = $customer_query->row['customer_id'];
            $this->session->data['customer_category'] = isset($customer_query->row['customer_category']) ? $customer_query->row['customer_category'] : null;
            $this->session->data['parent'] = $customer_query->row['parent'];
            $log = new Log('error.log');

            /* SET CUSTOMER CATEGORY */
            if ($customer_query->row['customer_id'] > 0 && $customer_query->row['parent'] > 0) {
                $parent_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($customer_query->row['parent']) . "' AND status = '1' AND approved='1'");
                if ($customer_query->num_rows > 0 && $parent_customer_query->row['customer_id'] > 0) {
                    $this->customer_category = $parent_customer_query->row['customer_category'];
                } else {
                    $this->customer_category = NULL;
                }
            }

            if ($customer_query->row['customer_id'] > 0 && ($customer_query->row['parent'] == NULL || $customer_query->row['parent'] == 0)) {
                $this->customer_category = $customer_query->row['customer_category'];
            }
            /* SET CUSTOMER CATEGORY */
            $log->write('FROM HERE PARENT CUSTOMER SESSION ASSIGN system_library_customer.php loginByPhone');

            /* SET CUSTOMER PEZESHA */
            if ($customer_query->row['customer_id'] > 0 && ($customer_query->row['parent'] == NULL || $customer_query->row['parent'] == 0)) {
                $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $this->session->data['customer_id'] . "'");
            }
            if ($customer_query->row['customer_id'] > 0 && $customer_query->row['parent'] > 0) {
                $pezesha_customer_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "pezesha_customers WHERE customer_id = '" . (int) $customer_query->row['parent'] . "'");
            }
            if ($customer_query->num_rows > 0 && $pezesha_customer_query->num_rows > 0 && $pezesha_customer_query->row['customer_id'] > 0) {
                $this->pezesha_customer_id = $pezesha_customer_query->row['pezesha_customer_id'];
                $this->pezesha_customer_uuid = $pezesha_customer_query->row['customer_uuid'];
                $this->pezesha_identifier = $pezesha_customer_query->row['customer_id'];
            } else {
                $this->pezesha_customer_id = NULL;
                $this->pezesha_customer_uuid = NULL;
                $this->pezesha_identifier = NULL;
            }
            /* SET CUSTOMER PEZESHA */
            /* if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
              $cart = unserialize($customer_query->row['cart']);

              foreach ($cart as $key => $value) {
              if (!array_key_exists($key, $this->session->data['cart'])) {
              $this->session->data['cart'][$key] = $value;
              } else {
              $this->session->data['cart'][$key] += $value;
              }
              }
              } */

            if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
                if (!isset($this->session->data['wishlist'])) {
                    $this->session->data['wishlist'] = [];
                }

                $wishlist = unserialize($customer_query->row['wishlist']);

                foreach ($wishlist as $product_id) {
                    if (!in_array($product_id, $this->session->data['wishlist'])) {
                        $this->session->data['wishlist'][] = $product_id;
                    }
                }
            }

            $this->customer_id = $customer_query->row['customer_id'];
            $this->firstname = $customer_query->row['firstname'];
            $this->lastname = $customer_query->row['lastname'];
            $this->email = $customer_query->row['email'];
            $this->telephone = $customer_query->row['telephone'];
            $this->fax = $customer_query->row['fax'];
            $this->newsletter = $customer_query->row['newsletter'];
            $this->customer_group_id = $customer_query->row['customer_group_id'];
            $this->address_id = $customer_query->row['address_id'];
            $this->sms_notification = $customer_query->row['sms_notification'];
            $this->mobile_notification = $customer_query->row['mobile_notification'];
            $this->email_notification = $customer_query->row['email_notification'];
            $this->payment_terms = $customer_query->row['payment_terms'];

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int) $this->customer_id . "'");

            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int) $this->customer_id . "'");

        unset($this->session->data['customer_id']);

        $this->customer_id = '';
        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->telephone = '';
        $this->fax = '';
        $this->newsletter = '';
        $this->customer_group_id = '';
        $this->address_id = '';
        $this->sms_notification = '';
        $this->mobile_notification = '';
        $this->email_notification = '';
        $this->payment_terms = '';
        $this->customer_category = '';
        $this->pezesha_customer_id = '';
        $this->pezesha_customer_uuid = '';
        $this->pezesha_identifier = '';
    }

    public function isLogged() {
        return $this->customer_id;
    }

    public function getMemberUpto() {
        return $this->member_upto;
    }

    public function getId() {
        return $this->customer_id;
    }

    public function getFirstName() {
        return $this->firstname;
    }

    public function getLastName() {
        return $this->lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getFax() {
        return $this->fax;
    }

    public function getNewsletter() {
        return $this->newsletter;
    }

    public function getGroupId() {
        return $this->customer_group_id;
    }

    public function getAddressId() {
        return $this->address_id;
    }

    public function getBalance() {
        $query = $this->db->query('SELECT SUM(amount) AS total FROM ' . DB_PREFIX . "customer_credit WHERE customer_id = '" . (int) $this->customer_id . "'");

        return $query->row['total'];
    }

    public function getRewardPoints() {
        $query = $this->db->query('SELECT SUM(points) AS total FROM ' . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $this->customer_id . "'");

        return $query->row['total'];
    }

    public function getSmsNotification() {
        return $this->sms_notification;
    }

    public function getMobileNotification() {
        return $this->mobile_notification;
    }

    public function getEmailNotification() {
        return $this->email_notification;
    }

    public function getPaymentTerms() {
        return $this->payment_terms;
    }

    public function getCustomerCategory() {
        return $this->customer_category;
    }

    public function getCustomerPezeshaId() {
        return $this->pezesha_customer_id;
    }

    public function getCustomerPezeshauuId() {
        return $this->pezesha_customer_uuid;
    }

    public function getCustomerPezeshaIdentifier() {
        return $this->pezesha_identifier;
    }

    public function setVariables($data) {
        $this->customer_id = $data['customer_id'];
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->email = $data['email'];
        $this->telephone = $data['telephone'];
        $this->fax = $data['fax'];
        $this->newsletter = $data['newsletter'];
        $this->customer_group_id = $data['customer_group_id'];
        $this->address_id = $data['address_id'];
        $this->member_upto = $data['member_upto'];
        $this->sms_notification = $data['sms_notification'];
        $this->mobile_notification = $data['mobile_notification'];
        $this->email_notification = $data['email_notification'];
        $this->payment_terms = $data['payment_terms'];
        $this->customer_category = $data['customer_category'];
        $this->pezesha_customer_id = $data['pezesha_customer_id'];
        $this->pezesha_customer_uuid = $data['pezesha_customer_uuid'];
        $this->pezesha_identifier = $data['pezesha_identifier'];
    }

}
