<?php

class ModelApiReturn extends Model {
	public function addReturn($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET order_id = '" . (int)$data['order_id'] . "', product_id = '" . (int)$data['product_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', product = '" . $this->db->escape($data['product']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', opened = '" . (int)$data['opened'] . "', return_reason_id = '" . (int)$data['return_reason_id'] . "', return_action_id = '" . (int)$data['return_action_id'] . "', return_status_id = '" . (int)$data['return_status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', date_added = NOW(), date_modified = NOW()");
		
		return $this->db->getLastId();
	}

	public function getReturnStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY name";

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$return_status_data = $this->cache->get('return_status.' . (int)$this->config->get('config_language_id'));

			if (!$return_status_data) {
				$query = $this->db->query("SELECT return_status_id, name FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$return_status_data = $query->rows;

				$this->cache->set('return_status.' . (int)$this->config->get('config_language_id'), $return_status_data);
			}

			return $return_status_data;
		}
	}

	public function editReturn($return_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET  return_action_id = '" . (int)$data['return_action_id']  . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");

		/*$this->db->query("UPDATE `" . DB_PREFIX . "return` SET order_id = '" . (int)$data['order_id'] . "', product_id = '" . (int)$data['product_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', product = '" . $this->db->escape($data['product']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', opened = '" . (int)$data['opened'] . "', return_reason_id = '" . (int)$data['return_reason_id'] . "', return_action_id = '" . (int)$data['return_action_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");*/
	}

	public function deleteReturn($return_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE return_id = '" . (int)$return_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");
	}

	public function getReturn($return_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT o.store_id FROM ". DB_PREFIX ."order o WHERE o.order_id = r.order_id) AS store_id  , (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = r.customer_id) AS customer FROM `" . DB_PREFIX . "return` r WHERE r.return_id = '" . (int)$return_id . "'");

		return $query->row;
	}

	public function getStore($store_id) {
		$query = $this->db->query("SELECT name FROM `" . DB_PREFIX . "store` WHERE store_id = '" . $store_id . "'");

		if(isset($query->row['name'])) {
			return $query->row['name'];
		}
		return '';
	}

	public function getVendorReturns($data = array()) {
		$sql = "SELECT *,r.order_id as o_order_id, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "return` r";
                $sql .= " INNER JOIN `".DB_PREFIX."order_product` op on op.product_id = r.product_id";
                
		$implode = array();

        $implode[] = 'op.vendor_id = "'.$this->user->getId().'"';
                
		if (isset($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_store'])) {
			$implode[] = "r.store = '" . $this->db->escape($data['filter_store']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

                 $sql .= ' GROUP BY r.return_id';
                
		$sort_data = array(
			'r.return_id',
			'r.order_id',
			'customer',
			'r.product',
			'r.model',
			'r.store',
			'status',
			'r.date_added',
			'r.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.return_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
                
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getVendorTotalReturns($data = array()) {
		$sql = "SELECT *,r.order_id as o_order_id, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "return` r";
                $sql .= " INNER JOIN `".DB_PREFIX."order_product` op on op.product_id = r.product_id";
                
		$implode = array();

        $implode[] = 'op.vendor_id = "'.$this->user->getId().'"';
                
		if (isset($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_store'])) {
			$implode[] = "r.store = '" . $this->db->escape($data['filter_store']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

         $sql .= ' GROUP BY r.return_id';
                
                
		$query = $this->db->query($sql);

		return count($query->rows);
	}

    public function getReturns($data = array()) {
		$sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT o.store_id FROM ". DB_PREFIX ."order o WHERE o.order_id = r.order_id) AS store_id  , (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "return` r";

		//echo "<pre>";print_r($sql);die;
		$implode = array();

		if (isset($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		/*if (!empty($data['filter_store_id'])) {
			$implode[] = "store_id = '" . (int)$data['filter_store_id'] . "'";
		}*/


		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_unit'])) {
			$implode[] = "r.unit = '" . $this->db->escape($data['filter_unit']) . "'";
		}


		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}
/*
		if (!empty($data['filter_store'])) {
			$implode[] = "r.store = '" . $this->db->escape($data['filter_store']) . "'";
		}*/

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		/* my added */
		/*if (!empty($data['date_from'])) {
            $implode[] = "o.date_added >= '" . $this->db->escape($data['date_from']) . " 00:00:00'";
        }

        if (!empty($data['date_to'])) {
            $implode[] = "o.date_added <= '" . $this->db->escape($data['date_to']) . " 23:59:59'";
        }*/
        if (!empty($data['date_from'])) {
            $implode[] = "DATE(r.date_added) >= DATE('" . $this->db->escape($data['date_from']) . " 00:00:00')";
        }

        if (!empty($data['date_to'])) {
            $implode[] = "DATE(r.date_added) <= DATE('" . $this->db->escape($data['date_to']) . " 23:59:59')";
        }


        /* my added end */
        


		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'r.return_id',
			'r.order_id',
			'customer',
			'r.product',
			'r.unit',
			'r.model',
			'r.store',
			'status',
			'r.date_added',
			'r.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.return_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}


		//echo "<pre>";print_r($sql);die;
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getReturnReasons($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY name";

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$return_reason_data = $this->cache->get('return_reason.' . (int)$this->config->get('config_language_id'));

			if (!$return_reason_data) {
				$query = $this->db->query("SELECT return_reason_id, name FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$return_reason_data = $query->rows;

				$this->cache->set('return_reason.' . (int)$this->config->get('config_language_id'), $return_reason_data);
			}

			return $return_reason_data;
		}
	}

	public function getReturnActions($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY name";

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$return_action_data = $this->cache->get('return_action.' . (int)$this->config->get('config_language_id'));

			if (!$return_action_data) {
				$query = $this->db->query("SELECT return_action_id, name FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$return_action_data = $query->rows;

				$this->cache->set('return_action.' . (int)$this->config->get('config_language_id'), $return_action_data);
			}

			return $return_action_data;
		}
	}

	public function getTotalReturns($data = array()) {
		$sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT o.store_id FROM ". DB_PREFIX ."order o WHERE o.order_id = r.order_id) AS store_id  , (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "return` r";

		//echo "<pre>";print_r($sql);die;
		$implode = array();

		if (isset($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_unit'])) {
			$implode[] = "r.unit = '" . $this->db->escape($data['filter_unit']) . "'";
		}


		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		/*if (!empty($data['filter_store'])) {
			$implode[] = "store_id = '" . $this->db->escape($data['filter_store']) . "'";
		}*/

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['date_from'])) {
            $implode[] = "DATE(r.date_added) >= DATE('" . $this->db->escape($data['date_from']) . " 00:00:00')";
        }

        if (!empty($data['date_to'])) {
            $implode[] = "DATE(r.date_added) <= DATE('" . $this->db->escape($data['date_to']) . " 23:59:59')";
        }
        

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'r.return_id',
			'r.order_id',
			'customer',
			'r.product',
			'r.unit',
			'r.model',
			'r.store',
			'status',
			'r.date_added',
			'r.date_modified'
		);


		//echo "<pre>";print_r($sql);die;
		$query = $this->db->query($sql);

		$count = $query->num_rows;

		$log = new Log('error.log');
        $log->write('getreturn Model');

		if (!empty($data['filter_store_id'])) {

			$log->write('getreturn Model if');
			$count = 0;
			$filter_store_id = (int)$data['filter_store_id'];

			$log->write($query->rows);

			foreach ($query->rows as $row) {

			
				if($row['store_id'] == $filter_store_id) {

					$log->write("query rows if");
					++$count;
				}
			}

		}

		return $count;

		/*echo "<pre>";print_r($count);
		echo "<pre>";print_r($query);die;*/
	}

	public function getTotalReturnsByReturnStatusId($return_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_status_id = '" . (int)$return_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnsByReturnReasonId($return_reason_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_reason_id = '" . (int)$return_reason_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnsByReturnActionId($return_action_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_action_id = '" . (int)$return_action_id . "'");

		return $query->row['total'];
	}

	public function addReturnHistory($return_id, $data) {

		$this->load->model('account/address');
		$this->load->model('account/order');
		$this->load->model('account/activity');
		

		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET return_status_id = '" . (int)$data['return_status_id'] . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "return_history SET return_id = '" . (int)$return_id . "', return_status_id = '" . (int)$data['return_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		if ($data['notify'] && false) {
			$return_query = $this->db->query("SELECT *, rs.name AS status FROM `" . DB_PREFIX . "return` r LEFT JOIN " . DB_PREFIX . "return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "'");

			if ($return_query->num_rows) {
				$this->load->language('mail/return');

				$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $return_id);

				$message  = $this->language->get('text_return_id') . ' ' . $return_id . "\n";
				$message .= $this->language->get('text_date_added') . ' ' . date($this->language->get('date_format_short'), strtotime($return_query->row['date_added'])) . "\n\n";
				$message .= $this->language->get('text_return_status') . "\n";
				$message .= $return_query->row['status'] . "\n\n";

				if ($data['comment']) {
					$message .= $this->language->get('text_comment') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}

				$message .= $this->language->get('text_footer');

				$mail = new Mail($this->config->get('config_mail'));
				$mail->setTo($return_query->row['email']);
				$mail->setFrom($this->config->get('config_from_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
			}
		}

		$return_query = $this->db->query("SELECT *, rs.name AS status FROM `" . DB_PREFIX . "return` r LEFT JOIN " . DB_PREFIX . "return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		// custoemr credit and vendor debit wallet on complete

		if($return_query->num_rows && $data['return_status_id'] == $this->config->get('config_complete_return_status_id') /*&& $return_query->row['customer_id'] != 0*/) {

			//echo "<pre>";print_r($return_query->row);die;

			$amount = $return_query->row['price'] * $return_query->row['quantity'];
			$order_id = $return_query->row['order_id'];

			$order_info = $this->model_account_order->getAdminOrder($order_id);

			//echo "<pre>";print_r($order_info);die;
			$vendor_id = $this->model_account_address->getVendorId($order_info['store_id']);

			//echo "<pre>";print_r($amount."order_id".$order_id."vendor_id".$vendor_id);die;

			if($order_info['payment_code'] != 'cod') {
				$this->model_account_activity->addCreditAdminCopy($return_query->row['customer_id'], 'Return ID # '.$return_id,$amount );	
			}
			

			$this->model_account_activity->addCreditVendorCopy($vendor_id, 'Return ID # '.$return_id, (-1 *$amount), $order_id ,0);

			/* new code*/

			$log = new Log('error.log');
			$log->write("return c");
			$log->write($order_info['shipping_code']);

			

			if($order_info['shipping_code'] == 'store_delivery.store_delivery') {

				// if shipping method is standard

				$log->write("return if");

				$this->load->model( 'tool/image' );
				$this->load->model( 'sale/order' );


				$store_info =  $this->model_tool_image->getStore($order_info['store_id']);

				$free_delivery_amount = 99999999;

            	if($store_info) {
            		$free_delivery_amount =$store_info['min_order_cod'];	
            	}


				$log->write($free_delivery_amount);


		        $new_total = 0;

		        $totals = $this->model_sale_order->getOrderTotals($order_id);

		        foreach ($totals as $total) {
		            if($total['code'] == 'total') {
		                $new_total = $total['value'];
		                break;
		            }
		        }

				$total_return_amount = $this->getTotalReturnAmountOfOrder($order_id);

				$log->write($new_total);
				$log->write($total_return_amount);

				if(($new_total - $total_return_amount) < $free_delivery_amount && $new_total >= $free_delivery_amount) {

					//charge 
					$total_returns = $this->getReturnShippingCharged($order_id);

					$log->write("total_returns if");

					$log->write($total_returns);

					if($total_returns ==  1) {

						$log->write("total_returns if if");

						// if sum of all returns of order 
						$this->model_account_activity->addCreditAdminCopy($return_query->row['customer_id'], 'Order Return Shipping charge # '.$order_id,-$store_info['cost_of_delivery']);	
						

					}
				}
			}
			
			


		}
		
		//echo "<pre>";print_r($return_query->row);die;

	}

	public function getReturnShippingCharged($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return WHERE order_id = '" . (int)$order_id . "' and return_status_id='".$this->config->get('config_complete_return_status_id')."'");

		return $query->row['total'];
	}
	

	public function getTotalReturnAmountOfOrder($order_id) {
		$query = $this->db->query("SELECT SUM(price * quantity) AS total FROM " . DB_PREFIX . "return WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}


	public function getReturnHistories($return_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM " . DB_PREFIX . "return_history rh LEFT JOIN " . DB_PREFIX . "return_status rs ON rh.return_status_id = rs.return_status_id WHERE rh.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReturnHistories($return_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");

		return $query->row['total'];
	}

	public function getTotalReturnHistoriesByReturnStatusId($return_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_status_id = '" . (int)$return_status_id . "' GROUP BY return_id");

		return $query->row['total'];
	}
}