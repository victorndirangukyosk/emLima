<?php


class ModelCatalogManufacturer extends Model {
    
	public function addManufacturer($data) {
		$this->trigger->fire('pre.admin.manufacturer.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}

        foreach ($data['manufacturer_description'] as $language_id => $value) {
            $value['meta_title'] = empty($value['meta_title']) ? $value['name'] : $value['meta_title'];

            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

        // Set which layout to use with this manufacturer
        if (isset($data['manufacturer_layout'])) {
            foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_layout SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $alias = empty($value) ? $data['manufacturer_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($alias) . "', language_id = '" . $language_id . "'");
            }
        }

		$this->cache->delete('manufacturer');

		$this->trigger->fire('post.admin.manufacturer.add', $manufacturer_id);

		return $manufacturer_id;
	}

	public function editManufacturer($manufacturer_id, $data) {
		$this->trigger->fire('pre.admin.manufacturer.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}

        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

        foreach ($data['manufacturer_description'] as $language_id => $value) {
            empty($value['meta_title']) ? $value['meta_title'] = $value['name'] : $value['meta_title'];

            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

        if (isset($data['manufacturer_layout'])) {
            foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_layout SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        foreach ($data['seo_url'] as $language_id => $value) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "' AND language_id = '" . $this->db->escape($language_id) . "'");

            $alias = empty($value) ? $data['manufacturer_description'][$language_id]['name'] : $value;

            $alias = $this->model_catalog_url_alias->generateAlias($alias);

            if ($alias) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($alias) . "', language_id = '" . $language_id . "'");
            }
        }

		$this->cache->delete('manufacturer');

		$this->trigger->fire('post.admin.manufacturer.edit', $manufacturer_id);
	}

	public function deleteManufacturer($manufacturer_id) {
		$this->trigger->fire('pre.admin.manufacturer.delete', $manufacturer_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		$this->cache->delete('manufacturer');

		$this->trigger->fire('post.admin.manufacturer.delete', $manufacturer_id);
	}

	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id) WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $manufacturer = $query->row;
        $manufacturer['seo_url'] = array();

        $query = $this->db->query("SELECT keyword, language_id FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $manufacturer['seo_url'][$row['language_id']] = $row['keyword'];
            }
        }

        return $manufacturer;
	}

	public function getManufacturers($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            if (!empty($data['filter_name'])) {
                $sql .= " AND md.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
            }
            
            if (isset($data['filter_status']) and !is_null($data['filter_status'])) {
                $sql .= " AND m.status = '".$data['filter_status']."'";
            }

            $sort_data = array(
                'm.status',
                'm.sort_order'
            );
    
            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY md.name";
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
        else {
            $manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->config->get('config_language_id'));

            if (!$manufacturer_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_description md ON (m.manufacturer_id = md.manufacturer_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY md.name");

                $manufacturer_data = $query->rows;

                $this->cache->set('manufacturer.' . (int)$this->config->get('config_language_id'), $manufacturer_data);
            }

            return $manufacturer_data;
        }
	}

    public function getManufacturerDescriptions($manufacturer_id) {
        $manufacturer_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

        foreach ($query->rows as $result) {
            $manufacturer_description_data[$result['language_id']] = array(
                'name'             => $result['name'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword']
            );
        }

        return $manufacturer_description_data;
    }

	public function getManufacturerStores($manufacturer_id) {
		$manufacturer_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

    public function getManufacturerLayouts($manufacturer_id) {
        $manufacturer_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

        foreach ($query->rows as $result) {
            $manufacturer_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $manufacturer_layout_data;
    }

	public function getTotalManufacturers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer");

		return $query->row['total'];
	}

    public function getTotalManufacturersFilter($data) {
        $sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_description md ON m.category_id = md.category_id");

        $isWhere = 0;
        $_sql = array();

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $isWhere = 1;
            $_sql[] = "	md.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "m.status = '" . $this->db->escape($data['filter_status']) . "'";
        }

        if($isWhere) {
            $sql .= " WHERE " . implode(" AND ", $_sql);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalManufacturersByLayoutId($layout_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row['total'];
    }
}