<?php

class ModelAppearanceCustomizer extends Model
{
    public function getDefaultData($code, $store_id = 0)
    {
        $setting_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."setting WHERE store_id = '".(int) $store_id."' AND `code` = '".$this->db->escape($code.'_'.$this->config->get('config_template'))."'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $setting_data[$result['key']] = $result['value'];
            } else {
                $setting_data[$result['key']] = unserialize($result['value']);
            }
        }

        return $setting_data;
    }
}
