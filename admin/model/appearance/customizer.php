<?php

class ModelAppearanceCustomizer extends Model
{
    public function saveCustomizer($code, $data, $store_id = 0)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."setting` WHERE store_id = '".(int) $store_id."' AND `code` = '".$this->db->escape($code.'_'.$this->config->get('config_template'))."'");

        $customizerCss = '';

        foreach ($data as $key => $value) {
            if ('save' != $key && 'custom-css' != $key && 'custom-js' != $key && 'advance-conrol' != $key) {
                if (!is_array($value)) {
                    $this->db->query('INSERT INTO '.DB_PREFIX."setting SET store_id = '".(int) $store_id."', `code` = '".$this->db->escape($code.'_'.$this->config->get('config_template'))."', `key` = '".$this->db->escape($key)."', `value` = '".$this->db->escape($value)."'");
                } else {
                    $this->db->query('INSERT INTO '.DB_PREFIX."setting SET store_id = '".(int) $store_id."', `code` = '".$this->db->escape($code.'_'.$this->config->get('config_template'))."', `key` = '".$this->db->escape($key)."', `value` = '".$this->db->escape(serialize($value))."', serialized = '1'");
                }
            } elseif ('custom-css' == $key || 'custom-js' == $key) {
                $element = explode('-', $key);
                if ('css' == $element[1]) {
                    $value = iconv('CP1257', 'UTF-8', $value);
                    $this->filesystem->dumpFile(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/stylesheet/custom.css', $value, 0644);
                } else {
                    $value = iconv('CP1257', 'UTF-8', $value);
                    $this->filesystem->dumpFile(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/javascript/custom.js', $value, 0644);
                }
            }

            if ('save' != $key && 'sitename' != $key && 'font' != $key && 'custom-css' != $key && 'custom-js' != $key) {
                $item = $this->getCustomizerItem($key);

                if ('layout_width' == $key || 'container_background-color' == $key || 'container-color_color' == $key || 'container_background-image' == $key) {
                    $element = explode('_', $key);
                    if (!empty($item['selector'])) {
                        if (!empty($value)) {
                            if ('container_background-image' == $key) {
                                $customizerCss .= $item['selector']." { \r\n \t".$element[1]." : url('../../../../../image/".$value."'); \r\n } \n\n ";
                            } else {
                                $customizerCss .= $item['selector']." { \r\n \t".$element[1].' : '.$value."; \r\n } \n\n ";
                            }
                        }
                    } else {
                        if (!empty($value)) {
                            if ('container_background-image' == $key) {
                                $customizerCss .= "body { \r\n \t".$element[1]." : url('../../../../../image/".$value."'); \r\n } \n\n ";
                            } else {
                                $customizerCss .= "body { \r\n \t".$element[1].' : '.$value."; \r\n } \n\n ";
                            }
                        }
                    }
                } elseif ('logo' == $key) {
                    if (!empty($value)) {
                        $this->db->query('DELETE FROM `'.DB_PREFIX."setting` WHERE store_id = '".(int) $store_id."' AND `code` = 'config' AND `key` = 'config_logo'");
                        $this->db->query('INSERT INTO '.DB_PREFIX."setting SET store_id = '".(int) $store_id."', `code` = 'config', `key` = 'config_logo', `value` = '".$this->db->escape($value)."'");
                    }
                }

                if (false == $item || 'logo' == $key) {
                    continue;
                }

                $element = explode('_', $key);

                if ('image' != $item['type']) {
                    $customizerCss .= $item['selector']." { \r\n \t".$element[1].' : '.$value."; \r\n } \n\n ";
                } else {
                    if (!empty($value)) {
                        $customizerCss .= $item['selector']." { \r\n \t".$element[1]." : url('../../../../../image/".$value."'); \r\n } \n\n ";
                    }
                }
            } elseif ('font' == $key) {
                $customizerCss .= " body { \r\n \tfont-family : ".$value."; \r\n } \n\n ";
            }
        }

        $this->filesystem->dumpFile(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/stylesheet/customizer.css', $customizerCss, 0644);
    }

    public function resetCustomizer($code, $store_id = 0)
    {
        $this->db->query('DELETE FROM `'.DB_PREFIX."setting` WHERE store_id = '".(int) $store_id."' AND `code` = '".$this->db->escape($code.'_'.$this->config->get('config_template'))."'");
        $this->filesystem->remove(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/stylesheet/customizer.css');
        $this->filesystem->remove(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/stylesheet/custom.css');
        $this->filesystem->remove(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/javascript/custom.js');
    }

    public function getDefaultData($code, $store_id = 0)
    {
        $setting_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."setting WHERE store_id = '".(int) $store_id."' AND `code` = '".$this->db->escape($code.'_'.$this->config->get('config_template'))."'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $check_png = strpos($result['value'], '.png');
                $check_jpg = strpos($result['value'], '.jpg');
                $check_jpeg = strpos($result['value'], '.jpeg');
                $check_jpe = strpos($result['value'], '.jpe');
                $check_gif = strpos($result['value'], '.gif');
                $check_bmp = strpos($result['value'], '.bmp');
                $check_ico = strpos($result['value'], '.ico');

                $this->load->model('tool/image');

                if (false !== $check_png || false !== $check_jpg || false !== $check_jpeg || false !== $check_jpe || false !== $check_gif || false !== $check_bmp || false !== $check_ico) {
                    $setting_data[$result['key'].'_raw'] = $result['value'];
                    $result['value'] = $this->model_tool_image->resize($result['value'], 100, 100);
                }

                $setting_data[$result['key']] = $result['value'];
            } else {
                $setting_data[$result['key']] = unserialize($result['value']);
            }
        }

        if (is_file(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/stylesheet/custom.css')) {
            $setting_data['custom-css'] = file_get_contents(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/stylesheet/custom.css');
        }

        if (is_file(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/javascript/custom.js')) {
            $setting_data['custom-js'] = file_get_contents(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/javascript/custom.js');
        }

        return $setting_data;
    }

    public function getCustomizerItem($key)
    {
        $json = file_get_contents(DIR_CATALOG.'ui/theme/'.$this->config->get('config_template').'/customizer.json');
        $items = json_decode($json, true);

        foreach ($items as $item_name => $item_value) {
            if (!empty($item_value['control'][$key])) {
                return $item_value['control'][$key];
            }
        }

        return false;
    }

    public function changeTheme($template)
    {
        $this->db->query('UPDATE '.DB_PREFIX."setting SET `value` = '".$this->db->escape($template)."' WHERE `code` = 'config' AND `key` = 'config_template' AND store_id = '".$this->config->get('config_store_id')."'");
    }
}
