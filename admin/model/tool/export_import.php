<?php

static $registry = null;

// Error Handler
function error_handler_for_export_import($errno, $errstr, $errfile, $errline)
{
    global $registry;

    switch ($errno) {
    case E_NOTICE:
    case E_USER_NOTICE:
        $errors = 'Notice';
        break;
    case E_WARNING:
    case E_USER_WARNING:
        $errors = 'Warning';
        break;
    case E_ERROR:
    case E_USER_ERROR:
        $errors = 'Fatal Error';
        break;
    default:
        $errors = 'Unknown';
        break;
    }

    $config = $registry->get('config');
    $url = $registry->get('url');
    $request = $registry->get('request');
    $session = $registry->get('session');
    $log = $registry->get('log');

    if ($config->get('config_error_log')) {
        $log->write('PHP '.$errors.':  '.$errstr.' in '.$errfile.' on line '.$errline);
    }

    if (('Warning' == $errors) || ('Unknown' == $errors)) {
        return true;
    }

    if (('Fatal Error' != $errors) && isset($request->get['path']) && ('tool/export_import/download' != $request->get['path'])) {
        if ($config->get('config_error_display')) {
            echo '<b>'.$errors.'</b>: '.$errstr.' in <b>'.$errfile.'</b> on line <b>'.$errline.'</b>';
        }
    } else {
        $session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
        $token = $request->get['token'];
        $link = $url->link('tool/export_import', 'token='.$token, 'SSL');
        header('Status: '. 302);
        header('Location: '.str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $link));
        exit();
    }

    return true;
}

function fatal_error_shutdown_handler_for_export_import()
{
    $last_error = error_get_last();
    if (E_ERROR === $last_error['type']) {
        // fatal error
        error_handler_for_export_import(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}

class ModelToolExportImport extends Model
{
    private $error = [];

    protected function clean(&$str, $allowBlanks = false)
    {
        $result = '';
        $n = strlen($str);
        for ($m = 0; $m < $n; ++$m) {
            $ch = substr($str, $m, 1);
            if ((' ' == $ch) && (!$allowBlanks) || ("\n" == $ch) || ("\r" == $ch) || ("\t" == $ch) || ("\0" == $ch) || ("\x0B" == $ch)) {
                continue;
            }
            $result .= $ch;
        }

        return $result;
    }

    protected function multiquery($sql)
    {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);
            if ($sql) {
                $this->db->query($sql);
            }
        }
    }

    protected function startsWith($haystack, $needle)
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }

        return substr($haystack, 0, strlen($needle)) == $needle;
    }

    protected function endsWith($haystack, $needle)
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }

        return substr($haystack, strlen($haystack) - strlen($needle), strlen($needle)) == $needle;
    }

    protected function getDefaultLanguageId()
    {
        $code = $this->config->get('config_language');
        $sql = 'SELECT language_id FROM `'.DB_PREFIX."language` WHERE code = '$code'";
        $result = $this->db->query($sql);
        $language_id = 1;
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $language_id = $row['language_id'];
                break;
            }
        }

        return $language_id;
    }

    protected function getLanguages()
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'language` WHERE `status`=1 ORDER BY `code`');

        return $query->rows;
    }

    protected function getLayoutIds()
    {
        $result = $this->db->query('SELECT * FROM `'.DB_PREFIX.'layout`');
        $layout_ids = [];
        foreach ($result->rows as $row) {
            $layout_ids[$row['name']] = $row['layout_id'];
        }

        return $layout_ids;
    }

    public function getCell(&$worksheet, $row, $col, $default_val = '')
    {
        --$col; // we use 1-based, PHPExcel uses 0-based column index
        ++$row; // we use 0-based, PHPExcel uses 1-based row index
        $val = ($worksheet->cellExistsByColumnAndRow($col, $row)) ? $worksheet->getCellByColumnAndRow($col, $row)->getValue() : $default_val;
        if (null === $val) {
            $val = $default_val;
        }

        return $val;
    }

    public function validateHeading(&$data, &$expected, &$multilingual)
    {
        //echo "<pre>";print_r($expected);die;
        $default_language_code = $this->config->get('config_language');
        $heading = [];
        $k = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
        $i = 0;
        for ($j = 1; $j <= $k; ++$j) {
            $entry = $this->getCell($data, $i, $j);
            $bracket_start = strripos($entry, '(', 0);
            if (false === $bracket_start) {
                if (in_array($entry, $multilingual)) {
                    return false;
                }
                $heading[] = strtolower($entry);
            } else {
                $name = strtolower(substr($entry, 0, $bracket_start));
                if (!in_array($name, $multilingual)) {
                    return false;
                }
                $bracket_end = strripos($entry, ')', $bracket_start);
                if ($bracket_end <= $bracket_start) {
                    return false;
                }
                if ($bracket_end + 1 != strlen($entry)) {
                    return false;
                }
                $language_code = strtolower(substr($entry, $bracket_start + 1, $bracket_end - $bracket_start - 1));
                if (count($heading) <= 0) {
                    return false;
                }
                if ($heading[count($heading) - 1] != $name) {
                    $heading[] = $name;
                }
            }
        }

        //echo "<pre>";print_r($heading);die;
        for ($i = 0; $i < count($expected); ++$i) {
            if (!isset($heading[$i])) {
                return false;
            }
            if ($heading[$i] != $expected[$i]) {
                return false;
            }
        }

        return true;
    }

    protected function setColumnStyles(&$worksheet, &$styles, $min_row, $max_row)
    {
        if ($max_row < $min_row) {
            return;
        }
        foreach ($styles as $col => $style) {
            $from = PHPExcel_Cell::stringFromColumnIndex($col).$min_row;
            $to = PHPExcel_Cell::stringFromColumnIndex($col).$max_row;
            $range = $from.':'.$to;
            $worksheet->getStyle($range)->applyFromArray($style, false);
        }
    }

    protected function setCellRow($worksheet, $row/* 1-based */, $data, &$style = null)
    {
        $worksheet->fromArray($data, null, 'A'.$row, true);
        //  foreach ($data as $col=>$val) {
        //   $worksheet->setCellValueByColumnAndRow( $col, $row, $val );
        //  }
        if (!empty($style)) {
            //   $from = 'A'.$row;
            //   $to = PHPExcel_Cell::stringFromColumnIndex(count($data)-1).$row;
            //   $range = $from.':'.$to;
            //   $worksheet->getStyle( $range )->applyFromArray( $style, false );
            $worksheet->getStyle("$row:$row")->applyFromArray($style, false);
        }
    }

    protected function setCell(&$worksheet, $row/* 1-based */, $col/* 0-based */, $val, &$style = null)
    {
        $worksheet->setCellValueByColumnAndRow($col, $row, $val);
        if (!empty($style)) {
            $worksheet->getStyleByColumnAndRow($col, $row)->applyFromArray($style, false);
        }
    }

    public function getMaxCategoryId()
    {
        $query = $this->db->query('SELECT MAX(category_id) as max_category_id FROM `'.DB_PREFIX.'category`');
        if (isset($query->row['max_category_id'])) {
            $max_id = $query->row['max_category_id'];
        } else {
            $max_id = 0;
        }

        return $max_id;
    }

    public function getMinCategoryId()
    {
        $query = $this->db->query('SELECT MIN(category_id) as min_category_id FROM `'.DB_PREFIX.'category`');
        if (isset($query->row['min_category_id'])) {
            $min_id = $query->row['min_category_id'];
        } else {
            $min_id = 0;
        }

        return $min_id;
    }

    public function getCountCategory()
    {
        $query = $this->db->query('SELECT COUNT(category_id) as count_category FROM `'.DB_PREFIX.'category`');
        if (isset($query->row['count_category'])) {
            $count = $query->row['count_category'];
        } else {
            $count = 0;
        }

        return $count;
    }

    public function getMaxProductId()
    {
        $query = $this->db->query('SELECT MAX(product_id) as max_product_id FROM `'.DB_PREFIX.'product`');
        if (isset($query->row['max_product_id'])) {
            $max_id = $query->row['max_product_id'];
        } else {
            $max_id = 0;
        }

        return $max_id;
    }

    public function getMinProductId()
    {
        $query = $this->db->query('SELECT MIN(product_id) as min_product_id FROM `'.DB_PREFIX.'product`');
        if (isset($query->row['min_product_id'])) {
            $min_id = $query->row['min_product_id'];
        } else {
            $min_id = 0;
        }

        return $min_id;
    }

    public function getCountProduct()
    {
        $query = $this->db->query('SELECT COUNT(product_id) as count_product FROM `'.DB_PREFIX.'product`');
        if (isset($query->row['count_product'])) {
            $count = $query->row['count_product'];
        } else {
            $count = 0;
        }

        return $count;
    }

    // download general products strts here
    public function populateProductsWorksheet(&$worksheet, &$languages, $default_language_id, &$price_format, &$box_format, &$weight_format, &$text_format, $offset = null, $rows = null, &$min_id = null, &$max_id = null)
    {
        // get list of the field names, some are only available for certain OpenCart versions
        $query = 'DESCRIBE `'.DB_PREFIX.'product`';

        $query = $this->db->query($query);

        $product_fields = [];
        foreach ($query->rows as $row) {
            $product_fields[] = $row['Field'];
        }

        // Opencart versions from 2.0 onwards also have product_description.meta_title
        $sql = 'SHOW COLUMNS FROM `'.DB_PREFIX."product_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('product_id'), 4) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('product_sr_no'), 4) + 1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name') + 4, 30) + 1);
        }
        //added
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('default_price'), 5) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('unit'), 20) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('weight'), 20) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('variations_id'), 25) + 1);
        //end
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('categories'), 12) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('model'), 8) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('image_name'), 12) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('default_variation_name'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_modified'), 19) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('status'), 5) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('seo_keyword'), 16) + 1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('description') + 4, 32) + 1);
        }
        if ($exist_meta_title) {
            foreach ($languages as $language) {
                $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_title') + 4, 20) + 1);
            }
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_description') + 4, 32) + 1);
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_keywords') + 4, 32) + 1);
        }

        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('tags') + 4, 32) + 1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 8) + 1);

        // The product headings row and column styles

        $styles = [];
        $data = [];
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        $data[$j++] = 'product_sr_no';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        //added
        $data[$j++] = 'default_price';
        $data[$j++] = 'unit';
        $data[$j++] = 'weight';
        $data[$j++] = 'variations_id';
        //end
        $styles[$j] = &$text_format;
        $data[$j++] = 'categories';
        $styles[$j] = &$text_format;
        $data[$j++] = 'model';
        $styles[$j] = &$text_format;
        $data[$j++] = 'image_name';
        $data[$j++] = 'default_variation_name';
        $data[$j++] = 'date_added';
        $data[$j++] = 'date_modified';
        $data[$j++] = 'status';
        $styles[$j] = &$text_format;
        $data[$j++] = 'seo_keyword';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'description('.$language['code'].')';
        }
        if ($exist_meta_title) {
            foreach ($languages as $language) {
                $styles[$j] = &$text_format;
                $data[$j++] = 'meta_title('.$language['code'].')';
            }
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'meta_description('.$language['code'].')';
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'meta_keywords('.$language['code'].')';
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'tags('.$language['code'].')';
        }
        $data[$j++] = 'sort_order';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual products data
        ++$i;
        $j = 0;

        $products = $this->getProducts($languages, $default_language_id, $product_fields, $exist_meta_title, $offset, $rows, $min_id, $max_id);
        $len = count($products);
        $min_id = $products[0]['product_id'];
        $max_id = $products[$len - 1]['product_id'];

        //echo "<pre>";print_r($products);die;
        foreach ($products as $row) {
            $data = [];
            $worksheet->getRowDimension($i)->setRowHeight(26);
            $product_id = $row['product_id'];
            $data[$j++] = $product_id;
            $data[$j++] = $product_id; //product_sr_no
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            //added
            $data[$j++] = $row['default_price'];
            $data[$j++] = $row['unit'];
            $data[$j++] = $row['weight'];
            $data[$j++] = $row['variations_id'];
            //end
            $data[$j++] = $row['categories'];

            $data[$j++] = $row['model'];
            $data[$j++] = $row['image_name'];
            $data[$j++] = $row['default_variation_name'];
            $data[$j++] = $row['date_added'];
            $data[$j++] = $row['date_modified'];
            $data[$j++] = (0 == $row['status']) ? 'false' : 'true';
            $data[$j++] = ($row['keyword']) ? $row['keyword'] : '';
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['description'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            if ($exist_meta_title) {
                foreach ($languages as $language) {
                    $data[$j++] = html_entity_decode($row['meta_title'][$language['code']], ENT_QUOTES, 'UTF-8');
                }
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['meta_description'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['meta_keyword'][$language['code']], ENT_QUOTES, 'UTF-8');
            }

            $store_id_list = '';
            if (isset($store_ids[$product_id])) {
                foreach ($store_ids[$product_id] as $store_id) {
                    $store_id_list .= ('' == $store_id_list) ? $store_id : ','.$store_id;
                }
            }
            $data[$j++] = $store_id_list;

            $data[$j++] = $row['sort_order'];

            //$this->setCellRow( $worksheet, $i, $data );
            $this->setCellRowNewForExport($worksheet, $i, $data);

            //break;
            ++$i;
            $j = 0;
        }
        $this->setColumnStyles($worksheet, $styles, 2, $i - 1);
    }

    protected function setCellRowNewForExport($worksheet, $row/* 1-based */, $data, &$style = null)
    {
        //echo "<pre>";print_r($data);die;
        end($data);
        $last_key = key($data);

        //echo "<pre>";print_r($last_key);die;

        $i = 0;
        for ($i = 0; $i <= $last_key; ++$i) {
            if (!isset($data[$i])) {
                $data[$i] = '';
            }
        }

        //echo "<pre>";print_r($row);die;

        ksort($data);

        $worksheet->fromArray($data, null, 'A'.$row, true);
        foreach ($data as $col => $val) {
            if (8 == $col) {
                $worksheet->setCellValueExplicit('I'.$row, $val, PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
        if (!empty($style)) {
            $worksheet->getStyle("$row:$row")->applyFromArray($style, false);
        }
    }

    protected function getProducts(&$languages, $default_language_id, $product_fields, $exist_meta_title, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        $sql = 'SELECT ';
        $sql .= '  p.product_id,';
        $sql .= '  GROUP_CONCAT( DISTINCT CAST(pc.category_id AS CHAR(11)) SEPARATOR "," ) AS categories,';
        $sql .= '  p.default_variation_name,';
        $sql .= '  p.model,';
        $sql .= '  p.image AS image_name,';
        $sql .= '  p.date_added,';
        $sql .= '  p.date_modified,';
        $sql .= '  p.status,';
        $sql .= '  p.default_price,';
        $sql .= '  p.unit,';
        $sql .= '  p.weight,';
        $sql .= '  p.variations_id,';

        $sql .= '  p.sort_order,';
        $sql .= '  ua.keyword,';
        $sql .= '  GROUP_CONCAT( DISTINCT CAST(pr.related_id AS CHAR(11)) SEPARATOR "," ) AS related ';
        $sql .= 'FROM `'.DB_PREFIX.'product` p ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'product_to_category` pc ON p.product_id=pc.product_id ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX."url_alias` ua ON ua.query=CONCAT('product_id=',p.product_id) ";
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'product_related` pr ON pr.product_id=p.product_id ';

        //echo "<pre>";print_r($max_id);die;
        if (isset($min_id) && isset($max_id)) {
            $sql .= " where p.product_id BETWEEN $min_id AND $max_id ";
        }

        $sql .= 'GROUP BY p.product_id ';
        $sql .= 'ORDER BY p.product_id ';
        if (isset($offset) && isset($rows)) {
            $sql .= "LIMIT $offset,$rows; ";
        } else {
            $sql .= '; ';
        }

        //echo "<pre>";print_r($sql);die;
        $results = $this->db->query($sql);

        //echo "<pre>";print_r($results);die;

        $product_descriptions = $this->getProductDescriptions($languages, $offset, $rows, $min_id, $max_id);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($product_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $product_descriptions[$language_code][$key]['name'];
                    $results->rows[$key]['description'][$language_code] = $product_descriptions[$language_code][$key]['description'];
                    if ($exist_meta_title) {
                        $results->rows[$key]['meta_title'][$language_code] = $product_descriptions[$language_code][$key]['meta_title'];
                    }
                    $results->rows[$key]['meta_description'][$language_code] = $product_descriptions[$language_code][$key]['meta_description'];
                    $results->rows[$key]['meta_keyword'][$language_code] = $product_descriptions[$language_code][$key]['meta_keyword'];
                    $results->rows[$key]['tag'][$language_code] = $product_descriptions[$language_code][$key]['tag'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                    $results->rows[$key]['description'][$language_code] = '';
                    if ($exist_meta_title) {
                        $results->rows[$key]['meta_title'][$language_code] = '';
                    }
                    $results->rows[$key]['meta_description'][$language_code] = '';
                    $results->rows[$key]['meta_keyword'][$language_code] = '';
                    $results->rows[$key]['tag'][$language_code] = '';
                }
            }
        }

        return $results->rows;
    }

    protected function getProductDescriptions(&$languages, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        // some older versions of OpenCart use the 'product_tag' table
        $exist_table_product_tag = false;
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."product_tag'");
        $exist_table_product_tag = ($query->num_rows > 0);

        // query the product_description table for each language
        $product_descriptions = [];
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql = 'SELECT p.product_id, '.(($exist_table_product_tag) ? 'GROUP_CONCAT(pt.tag SEPARATOR ",") AS tag, ' : '').'pd.* ';
            $sql .= 'FROM `'.DB_PREFIX.'product` p ';
            $sql .= 'LEFT JOIN `'.DB_PREFIX."product_description` pd ON pd.product_id=p.product_id AND pd.language_id='".(int) $language_id."' ";
            if ($exist_table_product_tag) {
                $sql .= 'LEFT JOIN `'.DB_PREFIX."product_tag` pt ON pt.product_id=p.product_id AND pt.language_id='".(int) $language_id."' ";
            }
            if (isset($min_id) && isset($max_id)) {
                $sql .= "WHERE p.product_id BETWEEN $min_id AND $max_id ";
            }

            $sql .= 'GROUP BY p.product_id ';
            $sql .= 'ORDER BY p.product_id ';
            if (isset($offset) && isset($rows)) {
                $sql .= "LIMIT $offset,$rows; ";
            } else {
                $sql .= '; ';
            }
            $query = $this->db->query($sql);
            $product_descriptions[$language_code] = $query->rows;
        }

        return $product_descriptions;
    }

    protected function getProductVariations($languages, $min_id, $max_id)
    {
        $sql = 'select pv.* from `'.DB_PREFIX.'product_variation` pv ';
        $sql .= 'inner join `'.DB_PREFIX.'product` p on p.product_id = pv.product_id ';

        if (isset($min_id) && isset($max_id)) {
            $sql .= " AND p.product_id BETWEEN $min_id AND $max_id ";
        }

        $sql .= 'ORDER BY pv.product_id ASC';

        return $this->db->query($sql)->rows;
    }

    protected function populateProductVariationsWorksheet(&$worksheet, &$languages, $default_language_id, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('id') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_id') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_sr_no') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('name') + 25);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('image') + 25);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('model') + 2);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('sort_order'));

        // The heading row and column styles
        $styles = [];
        $data = [];
        $i = 1;
        $j = 0;
        $data[$j++] = 'id';
        $data[$j++] = 'product_id';
        $data[$j++] = 'product_sr_no';
        $data[$j++] = 'name';
        $data[$j++] = 'image';
        $data[$j++] = 'model';
        $data[$j++] = 'sort_order';

        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        ++$i;
        $j = 0;
        $product_attributes = $this->getProductVariations($languages, $min_id, $max_id);
        foreach ($product_attributes as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = [];
            $data[$j++] = $row['id'];
            $data[$j++] = $row['product_id'];
            $data[$j++] = $row['product_id'];
            $data[$j++] = $row['name'];
            $data[$j++] = $row['image'];
            $data[$j++] = $row['model'];
            $data[$j++] = $row['sort_order'];
            $this->setCellRow($worksheet, $i, $data);
            ++$i;
            $j = 0;
        }
        $this->setColumnStyles($worksheet, $styles, 2, $i - 1);
    }

    protected function populateCategoriesWorksheet(&$worksheet, &$languages, &$box_format, &$text_format, $offset = null, $rows = null, &$min_id = null, &$max_id = null)
    {
        // Opencart versions from 2.0 onwards also have category_description.meta_title
        $sql = 'SHOW COLUMNS FROM `'.DB_PREFIX."category_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('category_id') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('parent_id') + 1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name') + 4, 30) + 1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('top'), 5) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('columns') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('sort_order') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('image_name'), 12) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_added'), 19) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('date_modified'), 19) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('seo_keyword'), 16) + 1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('description'), 32) + 1);
        }
        if ($exist_meta_title) {
            foreach ($languages as $language) {
                $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_title'), 20) + 1);
            }
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_description'), 32) + 1);
        }
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('meta_keywords'), 32) + 1);
        }
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('store_ids'), 16) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('layout'), 16) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('status'), 5) + 1);

        // The heading row and column styles
        $styles = [];
        $data = [];
        $i = 1;
        $j = 0;
        $data[$j++] = 'category_id';
        $data[$j++] = 'parent_id';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        $data[$j++] = 'top';
        $data[$j++] = 'columns';
        $data[$j++] = 'sort_order';
        $styles[$j] = &$text_format;
        $data[$j++] = 'image_name';
        $styles[$j] = &$text_format;
        $data[$j++] = 'date_added';
        $styles[$j] = &$text_format;
        $data[$j++] = 'date_modified';
        $styles[$j] = &$text_format;
        $data[$j++] = 'seo_keyword';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'description('.$language['code'].')';
        }
        if ($exist_meta_title) {
            foreach ($languages as $language) {
                $styles[$j] = &$text_format;
                $data[$j++] = 'meta_title('.$language['code'].')';
            }
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'meta_description('.$language['code'].')';
        }
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'meta_keywords('.$language['code'].')';
        }
        $styles[$j] = &$text_format;
        $data[$j++] = 'store_ids';
        $styles[$j] = &$text_format;
        $data[$j++] = 'layout';
        $data[$j++] = 'status';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual categories data
        ++$i;
        $j = 0;
        $store_ids = $this->getStoreIdsForCategories();
        $layouts = $this->getLayoutsForCategories();
        $categories = $this->getCategories($languages, $exist_meta_title, $offset, $rows, $min_id, $max_id);
        $len = count($categories);
        $min_id = $categories[0]['category_id'];
        $max_id = $categories[$len - 1]['category_id'];
        foreach ($categories as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(26);
            $data = [];
            $data[$j++] = $row['category_id'];
            $data[$j++] = $row['parent_id'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $data[$j++] = (0 == $row['top']) ? 'false' : 'true';
            $data[$j++] = $row['column'];
            $data[$j++] = $row['sort_order'];
            $data[$j++] = $row['image'];
            $data[$j++] = $row['date_added'];
            $data[$j++] = $row['date_modified'];
            $data[$j++] = $row['keyword'];
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['description'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            if ($exist_meta_title) {
                foreach ($languages as $language) {
                    $data[$j++] = html_entity_decode($row['meta_title'][$language['code']], ENT_QUOTES, 'UTF-8');
                }
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['meta_description'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['meta_keyword'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            $store_id_list = '';
            $category_id = $row['category_id'];
            if (isset($store_ids[$category_id])) {
                foreach ($store_ids[$category_id] as $store_id) {
                    $store_id_list .= ('' == $store_id_list) ? $store_id : ','.$store_id;
                }
            }
            $data[$j++] = $store_id_list;
            $layout_list = '';
            if (isset($layouts[$category_id])) {
                foreach ($layouts[$category_id] as $store_id => $name) {
                    $layout_list .= ('' == $layout_list) ? $store_id.':'.$name : ','.$store_id.':'.$name;
                }
            }
            $data[$j++] = $layout_list;
            $data[$j++] = (0 == $row['status']) ? 'false' : 'true';
            $this->setCellRow($worksheet, $i, $data);
            ++$i;
            $j = 0;
        }
        $this->setColumnStyles($worksheet, $styles, 2, $i - 1);
    }

    public function getStoreIdsForCategories()
    {
        $sql = 'SELECT category_id, store_id FROM `'.DB_PREFIX.'category_to_store` cs;';
        $store_ids = [];
        $result = $this->db->query($sql);
        foreach ($result->rows as $row) {
            $categoryId = $row['category_id'];
            $store_id = $row['store_id'];
            if (!isset($store_ids[$categoryId])) {
                $store_ids[$categoryId] = [];
            }
            if (!in_array($store_id, $store_ids[$categoryId])) {
                $store_ids[$categoryId][] = $store_id;
            }
        }

        return $store_ids;
    }

    public function getLayoutsForCategories()
    {
        $sql = 'SELECT cl.*, l.name FROM `'.DB_PREFIX.'category_to_layout` cl ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'layout` l ON cl.layout_id = l.layout_id ';
        $sql .= 'ORDER BY cl.category_id, cl.store_id;';
        $result = $this->db->query($sql);
        $layouts = [];
        foreach ($result->rows as $row) {
            $categoryId = $row['category_id'];
            $store_id = $row['store_id'];
            $name = $row['name'];
            if (!isset($layouts[$categoryId])) {
                $layouts[$categoryId] = [];
            }
            $layouts[$categoryId][$store_id] = $name;
        }

        return $layouts;
    }

    protected function getCategories(&$languages, $exist_meta_title, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        $sql = 'SELECT c.*, ua.keyword FROM `'.DB_PREFIX.'category` c ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX."url_alias` ua ON ua.query=CONCAT('category_id=',c.category_id) ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE c.category_id BETWEEN $min_id AND $max_id ";
        }
        $sql .= 'GROUP BY c.`category_id` ';
        $sql .= 'ORDER BY c.`category_id` ASC ';
        if (isset($offset) && isset($rows)) {
            $sql .= "LIMIT $offset,$rows; ";
        } else {
            $sql .= '; ';
        }
        $results = $this->db->query($sql);
        $category_descriptions = $this->getCategoryDescriptions($languages, $offset, $rows, $min_id, $max_id);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                if (isset($category_descriptions[$language_code][$key])) {
                    $results->rows[$key]['name'][$language_code] = $category_descriptions[$language_code][$key]['name'];
                    $results->rows[$key]['description'][$language_code] = $category_descriptions[$language_code][$key]['description'];
                    if ($exist_meta_title) {
                        $results->rows[$key]['meta_title'][$language_code] = $category_descriptions[$language_code][$key]['meta_title'];
                    }
                    $results->rows[$key]['meta_description'][$language_code] = $category_descriptions[$language_code][$key]['meta_description'];
                    $results->rows[$key]['meta_keyword'][$language_code] = $category_descriptions[$language_code][$key]['meta_keyword'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                    $results->rows[$key]['description'][$language_code] = '';
                    if ($exist_meta_title) {
                        $results->rows[$key]['meta_title'][$language_code] = '';
                    }
                    $results->rows[$key]['meta_description'][$language_code] = '';
                    $results->rows[$key]['meta_keyword'][$language_code] = '';
                }
            }
        }

        return $results->rows;
    }

    protected function getCategoryDescriptions(&$languages, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        // query the category_description table for each language
        $category_descriptions = [];
        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql = 'SELECT c.category_id, cd.* ';
            $sql .= 'FROM `'.DB_PREFIX.'category` c ';
            $sql .= 'LEFT JOIN `'.DB_PREFIX."category_description` cd ON cd.category_id=c.category_id AND cd.language_id='".(int) $language_id."' ";
            if (isset($min_id) && isset($max_id)) {
                $sql .= "WHERE c.category_id BETWEEN $min_id AND $max_id ";
            }
            $sql .= 'GROUP BY c.`category_id` ';
            $sql .= 'ORDER BY c.`category_id` ASC ';
            if (isset($offset) && isset($rows)) {
                $sql .= "LIMIT $offset,$rows; ";
            } else {
                $sql .= '; ';
            }
            $query = $this->db->query($sql);
            $category_descriptions[$language_code] = $query->rows;
        }

        return $category_descriptions;
    }

    protected function clearSpreadsheetCache()
    {
        $files = glob(DIR_CACHE.'Spreadsheet_Excel_Writer'.'*');

        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    @unlink($file);
                    clearstatcache();
                }
            }
        }
    }

    // download products

    public function download($export_type, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        // we use our own error handler
        global $registry;
        $registry = $this->registry;
        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');

        // Use the PHPExcel package from http://phpexcel.codeplex.com/
        $cwd = getcwd();
        chdir(DIR_SYSTEM.'PHPExcel');
        require_once 'Classes/PHPExcel.php';
        chdir($cwd);

        // find out whether all data is to be downloaded
        $all = !isset($offset) && !isset($rows) && !isset($min_id) && !isset($max_id);

        // Memory Optimization
        if ($this->config->get('export_import_settings_use_export_cache')) {
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = ['memoryCacheSize' => '16MB'];
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        }

        try {
            // set appropriate timeout limit
            set_time_limit(1800);

            $languages = $this->getLanguages();
            $default_language_id = $this->getDefaultLanguageId();

            // create a new workbook
            $workbook = new PHPExcel();

            // set some default styles
            $workbook->getDefaultStyle()->getFont()->setName('Arial');
            $workbook->getDefaultStyle()->getFont()->setSize(10);
            //$workbook->getDefaultStyle()->getAlignment()->setIndent(0.5);
            $workbook->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $workbook->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $workbook->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            // pre-define some commonly used styles
            $box_format = [
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => 'F0F0F0'],
                ],
                /*
                      'alignment' => array(
                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                      'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                      'wrap'       => false,
                      'indent'     => 0
                      )
                     */
            ];
            $text_format = [
                /*
                      'numberformat' => array(
                      'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
                      ),
                      'alignment' => array(
                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                      'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                      'wrap'       => false,
                      'indent'     => 0
                      )
                     */
            ];
            $price_format = [
                'numberformat' => [
                    'code' => '######0.00',
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    /*
                  'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                  'wrap'       => false,
                  'indent'     => 0
                 */
                ],
            ];
            $weight_format = [
                'numberformat' => [
                    'code' => '##0.00',
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    /*
                  'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                  'wrap'       => false,
                  'indent'     => 0
                 */
                ],
            ];

            // create the worksheets
            $worksheet_index = 0;
            switch ($export_type) {
            case 'c':
                // creating the Categories worksheet
                $workbook->setActiveSheetIndex($worksheet_index++);
                $worksheet = $workbook->getActiveSheet();
                $worksheet->setTitle('Categories');
                $this->populateCategoriesWorksheet($worksheet, $languages, $box_format, $text_format, $offset, $rows, $min_id, $max_id);
                $worksheet->freezePaneByColumnAndRow(1, 2);
                break;

            case 'p':
                // creating the Products worksheet
                $workbook->setActiveSheetIndex($worksheet_index++);
                $worksheet = $workbook->getActiveSheet();
                $worksheet->setTitle('Products');
                // echo "<pre>";
                // print_r($worksheet);die;
                $this->populateProductsWorksheet($worksheet, $languages, $default_language_id, $price_format, $box_format, $weight_format, $text_format, $offset, $rows, $min_id, $max_id);
                $worksheet->freezePaneByColumnAndRow(1, 2);

                // creating the ProductVariations worksheet
                /*$workbook->createSheet();
                $workbook->setActiveSheetIndex( $worksheet_index++ );
                $worksheet = $workbook->getActiveSheet();
                $worksheet->setTitle( 'ProductVariations' );
                $this->populateProductVariationsWorksheet( $worksheet, $languages, $default_language_id, $box_format, $text_format, $min_id, $max_id );
                $worksheet->freezePaneByColumnAndRow( 1, 2 );*/

                $workbook->createSheet();
                $workbook->setActiveSheetIndex($worksheet_index++);
                $worksheet = $workbook->getActiveSheet();
                $worksheet->setTitle('AdditionalImages');

                $this->populateAdditionalImagesWorksheet($worksheet, $box_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);

                break;

            default:
                break;
            }

            $workbook->setActiveSheetIndex(0);

            // redirect output to client browser
            $datetime = date('Y-m-d');
            switch ($export_type) {
            case 'c':
                $filename = 'categories-'.$datetime;
                if (!$all) {
                    if (isset($offset)) {
                        $filename .= "-offset-$offset";
                    } elseif (isset($min_id)) {
                        $filename .= "-start-$min_id";
                    }
                    if (isset($rows)) {
                        $filename .= "-rows-$rows";
                    } elseif (isset($max_id)) {
                        $filename .= "-end-$max_id";
                    }
                }
                $filename .= '.xlsx';
                break;
            case 'p':
                $filename = 'products-'.$datetime;
                if (!$all) {
                    if (isset($offset)) {
                        $filename .= "-offset-$offset";
                    } elseif (isset($min_id)) {
                        $filename .= "-start-$min_id";
                    }
                    if (isset($rows)) {
                        $filename .= "-rows-$rows";
                    } elseif (isset($max_id)) {
                        $filename .= "-end-$max_id";
                    }
                }
                $filename .= '.xlsx';
                break;
            default:
                $filename = $datetime.'.xlsx';
                break;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
            $objWriter->setPreCalculateFormulas(false);
            $objWriter->save('php://output');

            // Clear the spreadsheet caches
            $this->clearSpreadsheetCache();
            exit;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP '.get_class($e).':  '.$errstr.' in '.$errfile.' on line '.$errline);
            }

            return;
        }
    }

    // download product for vendor

    public function upload($filename, $incremental = false)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $log = new Log('error.log');
        $log->write('upload 2');
        // if ( $this->user->isVendor() ) {
        //     $user_id = $this->user->getId();
        // }else {
        //     $user_id = 0;
        // }

        //echo "<pre>";print_r("Cer");die;
        $this->db->query('update '.DB_PREFIX.'product SET product_sr_no=0');

        // we use our own error handler
        global $registry;
        $registry = $this->registry;
        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');
        $log->write('upload 3');
        try {
            $this->session->data['export_import_nochange'] = 1;

            // we use the PHPExcel package from http://phpexcel.codeplex.com/
            $cwd = getcwd();
            chdir(DIR_SYSTEM.'PHPExcel');
            require_once 'Classes/PHPExcel.php';
            chdir($cwd);

            // Memory Optimization
            if ($this->config->get('export_import_settings_use_import_cache')) {
                $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                $cacheSettings = [' memoryCacheSize ' => '16MB'];
                PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            }

            // parse uploaded spreadsheet file
            $inputFileType = PHPExcel_IOFactory::identify($filename);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $reader = $objReader->load($filename);

            // read the various worksheets and load them to the database
            if (!$this->validateUpload($reader)) {
                return false;
            }
            $this->clearCache();
            $this->session->data['export_import_nochange'] = 0;
            $available_product_ids = [];
            $log->write('upload 3.4');
            if (!$this->user->isVendor()) {
                $log->write('upload 4');
                $this->uploadCategories($reader, $incremental);
            }

            $this->uploadProducts($reader, $incremental, $available_product_ids);
            $log->write('upload 10');
            $this->uploadAdditionalImages($reader, $incremental, $available_product_ids);
            $log->write('upload 11');
            //$this->uploadProductVariations( $reader, $incremental, $available_product_ids );
            return true;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP '.get_class($e).':  '.$errstr.' in '.$errfile.' on line '.$errline);
            }

            return false;
        }
    }

    public function uploadCategoryPrices($filename, $incremental = false)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $log = new Log('error.log');
        $log->write('upload 2');

        //$this->db->query( 'update '.DB_PREFIX.'product SET product_sr_no=0' );

        // we use our own error handler
        global $registry;
        $registry = $this->registry;
        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');
        $log->write('upload 3');
        /*try {*/
        $this->session->data['export_import_nochange'] = 1;

        // we use the PHPExcel package from http://phpexcel.codeplex.com/
        $cwd = getcwd();
        chdir(DIR_SYSTEM.'PHPExcel');
        require_once 'Classes/PHPExcel.php';
        chdir($cwd);

        // Memory Optimization
        if ($this->config->get('export_import_settings_use_import_cache')) {
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = [' memoryCacheSize ' => '16MB'];
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        }

        // parse uploaded spreadsheet file
        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $reader = $objReader->load($filename);

        // read the various worksheets and load them to the database
        if (!$this->validateUpload($reader)) {
            return false;
        }
        $this->clearCache();
        $this->session->data['export_import_nochange'] = 0;
        $available_product_ids = [];
        $log->write('upload 3.4');
        /*if ( !$this->user->isVendor() ) {
            $log->write('upload 4');
            $this->uploadCategories( $reader, $incremental );
        }*/
        $this->uploadCategoryPricesData($reader, $incremental);
        //$this->uploadProducts( $reader, $incremental, $available_product_ids );
        //$log->write('upload 10');
        //$this->uploadAdditionalImages($reader, $incremental, $available_product_ids);
        //$log->write('upload 11');
        //$this->uploadProductVariations( $reader, $incremental, $available_product_ids );
        return true;
        /*} catch ( Exception $e ) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = array( 'errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline );
            if ( $this->config->get( 'config_error_log' ) ) {
                $this->log->write( 'PHP ' . get_class( $e ) . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline );
            }
            return false;
        }*/
    }

    protected function validateCategories(&$reader)
    {
        $data = $reader->getSheetByName('Categories');
        if (null == $data) {
            return true;
        }

        // Opencart versions from 2.0 onwards also have category_description.meta_title
        $sql = 'SHOW COLUMNS FROM `'.DB_PREFIX."category_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        if ($exist_meta_title) {
            $expected_heading = ['category_id', 'parent_id', 'name', 'top', 'columns', 'sort_order', 'image_name', 'date_added', 'date_modified', 'seo_keyword', 'description', 'meta_title', 'meta_description', 'meta_keywords', 'store_ids', 'layout', 'status'];
            $expected_multilingual = ['name', 'description', 'meta_title', 'meta_description', 'meta_keywords'];
        } else {
            $expected_heading = ['category_id', 'parent_id', 'name', 'top', 'columns', 'sort_order', 'image_name', 'date_added', 'date_modified', 'seo_keyword', 'description', 'meta_description', 'meta_keywords', 'store_ids', 'layout', 'status'];
            $expected_multilingual = ['name', 'description', 'meta_description', 'meta_keywords'];
        }

        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProducts(&$reader)
    {
        $data = $reader->getSheetByName('Products');
        if (null == $data) {
            return true;
        }

        // get list of the field names, some are only available for certain OpenCart versions
        $query = $this->db->query('DESCRIBE `'.DB_PREFIX.'product`');
        $product_fields = [];
        foreach ($query->rows as $row) {
            $product_fields[] = $row['Field'];
        }

        // Opencart versions from 2.0 onwards also have product_description.meta_title
        $sql = 'SHOW COLUMNS FROM `'.DB_PREFIX."product_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        $expected_heading = ['product_id', 'product_sr_no', 'name', 'default_price', 'unit', 'weight', 'variations_id', 'categories'];

        $expected_heading = array_merge($expected_heading, ['model', 'image_name', 'default_variation_name', 'date_added', 'date_modified', 'status', 'seo_keyword', 'description']);
        if ($exist_meta_title) {
            $expected_heading[] = 'meta_title';
        }
        $expected_heading = array_merge($expected_heading, ['meta_description', 'meta_keywords', 'tags', 'sort_order']);
        if ($exist_meta_title) {
            $expected_multilingual = ['name', 'description', 'meta_title', 'meta_description', 'meta_keywords', 'tags'];
        } else {
            $expected_multilingual = ['name', 'description', 'meta_description', 'meta_keywords', 'tags'];
        }

        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProductVariations(&$reader)
    {
        $data = $reader->getSheetByName('ProductVariations');
        if (null == $data) {
            return true;
        }

        // get list of the field names, some are only available for certain OpenCart versions
        $query = $this->db->query('DESCRIBE `'.DB_PREFIX.'product`');
        $product_fields = [];
        foreach ($query->rows as $row) {
            $product_fields[] = $row['Field'];
        }

        $expected_heading = ['id', 'product_id', 'product_sr_no', 'name', 'image', 'model', 'sort_order'];
        $expected_multilingual = [];

        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProductIdColumns(&$reader)
    {
        $data = $reader->getSheetByName('Products');
        if (null == $data) {
            return true;
        }
        $ok = true;

        // only unique numeric product_ids can be used in worksheet 'Products'
        $has_missing_product_ids = false;
        $product_ids = [];
        $k = $data->getHighestRow();
        for ($i = 1; $i < $k; ++$i) {
            $product_id = trim($this->getCell($data, $i, 1));
            $product_sr_no = trim($this->getCell($data, $i, 2));

            if ('' == $product_id && '' == $product_sr_no) {
                if (!$has_missing_product_ids) {
                    $msg = str_replace('%1', 'Products', $this->language->get('error_missing_product_id'));
                    $this->log->write($msg);
                    $has_missing_product_ids = true;
                }
                $ok = false;
                continue;
            }
            $product_ids[] = $product_id;
        }

        // make sure product_ids are numeric entries and are also mentioned in worksheet 'Products'
        $worksheets = ['ProductVariations'];
        foreach ($worksheets as $worksheet) {
            $data = $reader->getSheetByName($worksheet);
            if (null == $data) {
                continue;
            }
            $has_missing_product_ids = false;
            $unlisted_product_ids = [];
            $k = $data->getHighestRow();
            for ($i = 1; $i < $k; ++$i) {
                $product_id = trim($this->getCell($data, $i, 2));
                $product_sr_no = trim($this->getCell($data, $i, 3));

                if ('' == $product_id && '' == $product_sr_no) {
                    if (!$has_missing_product_ids) {
                        $msg = str_replace('%1', $worksheet, $this->language->get('error_missing_product_id'));
                        $this->log->write($msg);
                        $has_missing_product_ids = true;
                    }
                    $ok = false;
                    continue;
                }
            }
        }

        return $ok;
    }

    protected function validateUpload(&$reader)
    {
        $ok = true;
        // worksheets must have correct heading rows
        if (!$this->validateCategories($reader)) {
            $this->log->write($this->language->get('error_categories_header'));
            $ok = false;
        }
        if (!$this->validateProducts($reader)) {
            $this->log->write($this->language->get('error_products_header'));
            $ok = false;
        }
        if (!$this->validateProductVariations($reader)) {
            $this->log->write($this->language->get('error_product_variations_header'));
            $ok = false;
        }

        // certain worksheets rely on the existence of other worksheets
        $names = $reader->getSheetNames();
        $exist_products = false;
        $exist_product_variations = false;
        foreach ($names as $name) {
            if ('Products' == $name) {
                $exist_products = true;
                continue;
            }
            if ('ProductVariations' == $name) {
                $exist_product_variations = true;
                continue;
            }
        }

        if (!$ok) {
            return false;
        }

        if (!$this->validateProductIdColumns($reader)) {
            return false;
        }

        return $ok;
    }

    protected function clearCache()
    {
        $this->cache->delete('*');
    }

    protected function uploadCategories(&$reader, $incremental)
    {
        // get worksheet if there
        $data = $reader->getSheetByName('Categories');
        if (null == $data) {
            return;
        }

        // Opencart versions from 2.0 onwards also have category_description.meta_title
        $sql = 'SHOW COLUMNS FROM `'.DB_PREFIX."category_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        // get old url_alias_ids
        $url_alias_ids = $this->getCategoryUrlAliasIds();

        // if incremental then find current category IDs else delete all old categories
        $available_category_ids = [];
        if ($incremental) {
            $available_category_ids = $this->getAvailableCategoryIds();
        } else {
            $this->deleteCategories($url_alias_ids);
        }

        // get pre-defined layouts
        $layout_ids = $this->getLayoutIds();

        // get pre-defined store_ids
        //$available_store_ids = $this->getAvailableStoreIds();
        $available_store_ids = $this->getAvailableStoreIdsAdmin();

        //echo "<pre>";print_r($available_store_ids);die;
        // find the installed languages
        $languages = $this->getLanguages();

        $first_row = [];
        $i = 0;
        $k = $data->getHighestRow();

        for ($i = 0; $i < $k; ++$i) {
            if (0 == $i) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j = 1; $j <= $max_col; ++$j) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $category_id = trim($this->getCell($data, $i, $j++));
            if ('' == $category_id) {
                continue;
            }
            $parent_id = $this->getCell($data, $i, $j++, '0');
            $names = [];
            while ($this->startsWith($first_row[$j - 1], 'name(')) {
                $language_code = substr($first_row[$j - 1], strlen('name('), strlen($first_row[$j - 1]) - strlen('name(') - 1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $top = $this->getCell($data, $i, $j++, ('0' == $parent_id) ? 'true' : 'false');
            $columns = $this->getCell($data, $i, $j++, ('0' == $parent_id) ? '1' : '0');
            $sort_order = $this->getCell($data, $i, $j++, '0');
            $image_name = trim($this->getCell($data, $i, $j++));
            $date_added = trim($this->getCell($data, $i, $j++));
            $date_added = ((is_string($date_added)) && (strlen($date_added) > 0)) ? $date_added : 'NOW()';
            $date_modified = trim($this->getCell($data, $i, $j++));
            $date_modified = ((is_string($date_modified)) && (strlen($date_modified) > 0)) ? $date_modified : 'NOW()';
            $seo_keyword = $this->getCell($data, $i, $j++);
            $descriptions = [];
            while ($this->startsWith($first_row[$j - 1], 'description(')) {
                $language_code = substr($first_row[$j - 1], strlen('description('), strlen($first_row[$j - 1]) - strlen('description(') - 1);
                $description = $this->getCell($data, $i, $j++);
                $description = htmlspecialchars($description);
                $descriptions[$language_code] = $description;
            }
            if ($exist_meta_title) {
                $meta_titles = [];
                while ($this->startsWith($first_row[$j - 1], 'meta_title(')) {
                    $language_code = substr($first_row[$j - 1], strlen('meta_title('), strlen($first_row[$j - 1]) - strlen('meta_title(') - 1);
                    $meta_title = $this->getCell($data, $i, $j++);
                    $meta_title = htmlspecialchars($meta_title);
                    $meta_titles[$language_code] = $meta_title;
                }
            }
            $meta_descriptions = [];
            while ($this->startsWith($first_row[$j - 1], 'meta_description(')) {
                $language_code = substr($first_row[$j - 1], strlen('meta_description('), strlen($first_row[$j - 1]) - strlen('meta_description(') - 1);
                $meta_description = $this->getCell($data, $i, $j++);
                $meta_description = htmlspecialchars($meta_description);
                $meta_descriptions[$language_code] = $meta_description;
            }
            $meta_keywords = [];
            while ($this->startsWith($first_row[$j - 1], 'meta_keywords(')) {
                $language_code = substr($first_row[$j - 1], strlen('meta_keywords('), strlen($first_row[$j - 1]) - strlen('meta_keywords(') - 1);
                $meta_keyword = $this->getCell($data, $i, $j++);
                $meta_keyword = htmlspecialchars($meta_keyword);
                $meta_keywords[$language_code] = $meta_keyword;
            }
            $store_ids = $this->getCell($data, $i, $j++);
            $layout = $this->getCell($data, $i, $j++, '');
            $status = $this->getCell($data, $i, $j++, 'true');
            $category = [];
            $category['category_id'] = $category_id;
            $category['image'] = $image_name;
            $category['parent_id'] = $parent_id;
            $category['sort_order'] = $sort_order;
            $category['date_added'] = $date_added;
            $category['date_modified'] = $date_modified;
            $category['names'] = $names;
            $category['top'] = $top;
            $category['columns'] = $columns;
            $category['descriptions'] = $descriptions;
            if ($exist_meta_title) {
                $category['meta_titles'] = $meta_titles;
            }
            $category['meta_descriptions'] = $meta_descriptions;
            $category['meta_keywords'] = $meta_keywords;
            $category['seo_keyword'] = $seo_keyword;
            $store_ids = trim($this->clean($store_ids, false));
            $category['store_ids'] = ('' == $store_ids) ? [] : explode(',', $store_ids);
            if (false === $category['store_ids']) {
                $category['store_ids'] = [];
            }
            $category['layout'] = ('' == $layout) ? [] : explode(',', $layout);
            if (false === $category['layout']) {
                $category['layout'] = [];
            }
            $category['status'] = $status;
            if ($incremental) {
                if ($available_category_ids) {
                    if (in_array((int) $category_id, $available_category_ids)) {
                        $this->deleteCategory($category_id);
                    }
                }
            }

            //echo "<pre>";print_r($category);die;

            $this->moreCategoryCells($i, $j, $data, $category);
            $this->storeCategoryIntoDatabase($category, $languages, $exist_meta_title, $layout_ids, $available_store_ids, $url_alias_ids);
        }
        // [store_ids] => Array
        //       (
        //           [0] => 0
        //           [1] => 8
        //           [2] => 9
        //           [3] => 43
        //       )
        // restore category paths for faster lookups on the frontend (only for newer OpenCart versions)
        $this->load->model('catalog/category');
        if (method_exists($this->model_catalog_category, 'repairCategories')) {
            $this->model_catalog_category->repairCategories(0);
        }
    }

    protected function uploadProducts(&$reader, $incremental, &$available_product_ids = [])
    {
        $log = new Log('error.log');
        $log->write('upload 3.4');

        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Products');
        if (null == $data) {
            return;
        }

        // save product view counts
        $view_counts = $this->getProductViewCounts();

        // save old url_alias_ids
        $url_alias_ids = $this->getProductUrlAliasIds();

        // some older versions of OpenCart use the 'product_tag' table
        $exist_table_product_tag = false;
        $query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."product_tag'");
        $exist_table_product_tag = ($query->num_rows > 0);

        // Opencart versions from 2.0 onwards also have product_description.meta_title
        $sql = 'SHOW COLUMNS FROM `'.DB_PREFIX."product_description` LIKE 'meta_title'";
        $query = $this->db->query($sql);
        $exist_meta_title = ($query->num_rows > 0) ? true : false;

        // if incremental then find current product IDs else delete all old products
        $available_product_ids = [];
        $log->write('upload 5');
        if ($incremental) {
            $log->write('upload 5.if');
            $available_product_ids = $this->getAvailableProductIds($data);
        } else {
            $log->write('upload 6');

            $query = $this->db->query('select product_id from '.DB_PREFIX.'product ');

            $product_ids = '';

            foreach ($query->rows as $temp) {
                $product_ids .= $temp['product_id'].',';
            }

            if ($product_ids) {
                $log->write('upload 5. delete');
                $ids = rtrim($product_ids, ',');
                //...
                //// $this->deleteProducts($ids, $exist_table_product_tag, $url_alias_ids);
                //$this->deleteVariations($ids);
            }
        }

        // find the installed languages
        $languages = $this->getLanguages();

        // get list of the field names, some are only available for certain OpenCart versions
        $query = $this->db->query('DESCRIBE `'.DB_PREFIX.'product`');
        $product_fields = [];
        foreach ($query->rows as $row) {
            $product_fields[] = $row['Field'];
        }

        // load the worksheet cells and store them to the database
        $first_row = [];
        $i = 0;
        $k = $data->getHighestRow();
        for ($i = 0; $i < $k; ++$i) {
            if (0 == $i) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j = 1; $j <= $max_col; ++$j) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $product_id = trim($this->getCell($data, $i, $j++));
            $product_sr_no = trim($this->getCell($data, $i, $j++));
            if ('' == $product_sr_no && '' == $product_id) {
                continue;
            }
            $names = [];
            while ($this->startsWith($first_row[$j - 1], 'name(')) {
                $language_code = substr($first_row[$j - 1], strlen('name('), strlen($first_row[$j - 1]) - strlen('name(') - 1);
                $name = $this->getCell($data, $i, $j++);
                $name = htmlspecialchars($name);
                $names[$language_code] = $name;
            }
            $default_price = $this->getCell($data, $i, $j++, '   ');

            $b = str_replace(',', '', $default_price);

            if (is_numeric($b)) {
                $default_price = $b;
            }

            $unit = $this->getCell($data, $i, $j++, '   ');
            $weight = $this->getCell($data, $i, $j++, '   ');
            $variations_id = $this->getCell($data, $i, $j++, '  ');

            $categories = $this->getCell($data, $i, $j++);

            $model = $this->getCell($data, $i, $j++, '   ');
            $image_name = $this->getCell($data, $i, $j++);
            $default_variation_name = $this->getCell($data, $i, $j++, '');

            $date_added = $this->getCell($data, $i, $j++);
            $date_added = ((is_string($date_added)) && (strlen($date_added) > 0)) ? $date_added : 'NOW()';
            $date_modified = $this->getCell($data, $i, $j++);
            $date_modified = ((is_string($date_modified)) && (strlen($date_modified) > 0)) ? $date_modified : 'NOW()';
            $status = $this->getCell($data, $i, $j++, 'true');

            $keyword = $this->getCell($data, $i, $j++);
            $descriptions = [];
            while ($this->startsWith($first_row[$j - 1], 'description(')) {
                $language_code = substr($first_row[$j - 1], strlen('description('), strlen($first_row[$j - 1]) - strlen('description(') - 1);
                $description = $this->getCell($data, $i, $j++);
                $description = htmlspecialchars($description);
                $descriptions[$language_code] = $description;
            }
            if ($exist_meta_title) {
                $meta_titles = [];
                while ($this->startsWith($first_row[$j - 1], 'meta_title(')) {
                    $language_code = substr($first_row[$j - 1], strlen('meta_title('), strlen($first_row[$j - 1]) - strlen('meta_title(') - 1);
                    $meta_title = $this->getCell($data, $i, $j++);
                    $meta_title = htmlspecialchars($meta_title);
                    $meta_titles[$language_code] = $meta_title;
                }
            }
            $meta_descriptions = [];
            while ($this->startsWith($first_row[$j - 1], 'meta_description(')) {
                $language_code = substr($first_row[$j - 1], strlen('meta_description('), strlen($first_row[$j - 1]) - strlen('meta_description(') - 1);
                $meta_description = $this->getCell($data, $i, $j++);
                $meta_description = htmlspecialchars($meta_description);
                $meta_descriptions[$language_code] = $meta_description;
            }
            $meta_keywords = [];
            while ($this->startsWith($first_row[$j - 1], 'meta_keywords(')) {
                $language_code = substr($first_row[$j - 1], strlen('meta_keywords('), strlen($first_row[$j - 1]) - strlen('meta_keywords(') - 1);
                $meta_keyword = $this->getCell($data, $i, $j++);
                $meta_keyword = htmlspecialchars($meta_keyword);
                $meta_keywords[$language_code] = $meta_keyword;
            }

            $tags = [];
            while ($this->startsWith($first_row[$j - 1], 'tags(')) {
                $language_code = substr($first_row[$j - 1], strlen('tags('), strlen($first_row[$j - 1]) - strlen('tags(') - 1);
                $tag = $this->getCell($data, $i, $j++);
                $tag = htmlspecialchars($tag);
                $tags[$language_code] = $tag;
            }
            $sort_order = $this->getCell($data, $i, $j++, '0');

            $product = [];

            $product['product_id'] = $product_id;
            $product['product_sr_no'] = $product_sr_no;
            $product['names'] = $names;

            $product['name'] = $name;

            $product['default_price'] = $default_price;

            $product['unit'] = $unit;
            $product['weight'] = $weight;
            $product['variations_id'] = $variations_id;

            $categories = trim($this->clean($categories, false));
            $product['categories'] = ('' == $categories) ? [] : explode(',', $categories);
            if (false === $product['categories']) {
                $product['categories'] = [];
            }

            $product['model'] = $model;
            $product['image'] = $image_name;
            $product['default_variation_name'] = $default_variation_name;
            $product['date_added'] = $date_added;
            $product['date_modified'] = $date_modified;
            $product['status'] = $status;
            $product['viewed'] = isset($view_counts[$product_id]) ? $view_counts[$product_id] : 0;
            $product['descriptions'] = $descriptions;

            if ($exist_meta_title) {
                $product['meta_titles'] = $meta_titles;
            }
            $product['meta_descriptions'] = $meta_descriptions;
            $product['seo_keyword'] = $keyword;
            $product['meta_keywords'] = $meta_keywords;
            $product['tags'] = $tags;
            $product['sort_order'] = $sort_order;
            if (!$incremental) {
                $log->write('upload in incremental');
                //....
                // $this->deleteProduct($product_id, $exist_table_product_tag);
            }
            $this->moreProductCells($i, $j, $data, $product);
            $this->storeProductIntoDatabase($product, $languages, $product_fields, $exist_table_product_tag, $exist_meta_title, $layout_ids, $available_store_ids, $manufacturers, $weight_class_ids, $length_class_ids, $url_alias_ids, $incremental);
        }
    }

    protected function getAvailableProductIds(&$data)
    {
        $available_product_ids = [];

        $k = $data->getHighestRow();
        for ($i = 1; $i < $k; ++$i) {
            $j = 1;

            $product_id = trim($this->getCell($data, $i, $j++));
            if ('' == $product_id) {
                continue;
            }

            $available_product_ids[$product_id] = $product_id;
        }

        return $available_product_ids;
    }

    protected function getProductViewCounts()
    {
        $query = $this->db->query('SELECT product_id, viewed FROM `'.DB_PREFIX.'product`');
        $view_counts = [];
        foreach ($query->rows as $row) {
            $product_id = $row['product_id'];
            $viewed = $row['viewed'];
            $view_counts[$product_id] = $viewed;
        }

        return $view_counts;
    }

    protected function getProductUrlAliasIds()
    {
        $sql = "SELECT url_alias_id, SUBSTRING( query, CHAR_LENGTH('product_id=')+1 ) AS product_id ";
        $sql .= 'FROM `'.DB_PREFIX.'url_alias` ';
        $sql .= "WHERE query LIKE 'product_id=%'";
        $query = $this->db->query($sql);
        $url_alias_ids = [];
        foreach ($query->rows as $row) {
            $url_alias_id = $row['url_alias_id'];
            $product_id = $row['product_id'];
            $url_alias_ids[$product_id] = $url_alias_id;
        }

        return $url_alias_ids;
    }

    protected function deleteProducts($product_ids, $exist_table_product_tag, &$url_alias_ids)
    {
        $sql = 'TRUNCATE  `'.DB_PREFIX."product`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_variation`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_description`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_to_category`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."product_to_store`;\n";

        $sql .= 'TRUNCATE  `'.DB_PREFIX."offer_products`;\n";

        //$sql .= "DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE product_id IN (" . $product_ids . ");\n";

        $arr = explode(',', $product_ids);

        if ($arr) {
            foreach ($arr as $product_id) {
                $sql .= 'DELETE FROM `'.DB_PREFIX."url_alias` WHERE `query` = 'product_id=".$product_id."';\n";
            }
        }

        $sql .= 'TRUNCATE `'.DB_PREFIX."product_related`;\n";

        $this->multiquery($sql);
        $sql = 'SELECT (MAX(url_alias_id)+1) AS next_url_alias_id FROM `'.DB_PREFIX.'url_alias` LIMIT 1';
        $query = $this->db->query($sql);
        $next_url_alias_id = $query->row['next_url_alias_id'];
        $sql = 'ALTER TABLE `'.DB_PREFIX."url_alias` AUTO_INCREMENT = $next_url_alias_id";
        $this->db->query($sql);
        $remove = [];
        foreach ($url_alias_ids as $product_id => $url_alias_id) {
            if ($url_alias_id >= $next_url_alias_id) {
                $remove[$product_id] = $url_alias_id;
            }
        }
        foreach ($remove as $product_id => $url_alias_id) {
            unset($url_alias_ids[$product_id]);
        }
    }

    protected function deleteProduct($product_id, $exist_table_product_tag)
    {
        $sql = 'DELETE FROM `'.DB_PREFIX."product` WHERE `product_id` = '$product_id';\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."product_variation` WHERE `product_id` = '$product_id';\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."product_description` WHERE `product_id` = '$product_id';\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."product_to_category` WHERE `product_id` = '$product_id';\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."product_to_store` WHERE `product_id` = '$product_id';\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."url_alias` WHERE `query` LIKE 'product_id=$product_id';\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."product_related` WHERE `product_id` = '$product_id';\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."product_to_layout` WHERE `product_id` = '$product_id';\n";
        if ($exist_table_product_tag) {
            $sql .= 'DELETE FROM `'.DB_PREFIX."product_tag` WHERE `product_id` = '$product_id';\n";
        }
        $this->multiquery($sql);
    }

    protected function moreProductCells($i, &$j, &$worksheet, &$product)
    {
        return;
    }

    protected function storeProductIntoDatabase(&$product, &$languages, &$product_fields, $exist_table_product_tag, $exist_meta_title, &$layout_ids, &$available_store_ids, &$manufacturers, &$weight_class_ids, &$length_class_ids, &$url_alias_ids, $incremental)
    {
        // extract the product details

        $product_id = $product['product_id'];
        $product_sr_no = $product['product_sr_no'];
        $names = $product['names'];

        $product_name = $this->db->escape($product['name']);

        $default_price = $product['default_price'];

        $unit = $product['unit'];
        $weight = $product['weight'];
        $variations_id = $product['variations_id'];

        $categories = $product['categories'];

        $model = $this->db->escape($product['model']);
        $image = $this->db->escape($product['image']);

        $default_variation_name = $this->db->escape(trim($product['default_variation_name']));

        $date_added = $product['date_added'];
        $date_modified = $product['date_modified'];
        $status = $product['status'];
        $status = (('TRUE' == strtoupper($status)) || ('YES' == strtoupper($status)) || ('ENABLED' == strtoupper($status))) ? 1 : 0;

        $viewed = $product['viewed'];
        //$descriptions = $this->db->escape($product['descriptions']);
        $descriptions = $product['descriptions'];

        if ($exist_meta_title) {
            $meta_titles = $product['meta_titles'];
        }
        $meta_descriptions = $product['meta_descriptions'];
        $keyword = $this->db->escape($product['seo_keyword']);

        $meta_keywords = $product['meta_keywords'];
        $tags = $product['tags'];
        $sort_order = $product['sort_order'];

        // generate and execute SQL for inserting the product

        // $exists = $this->db->query('select * from '.DB_PREFIX.'product where model="'.$model.'"');
        $exists = $this->db->query('select * from '.DB_PREFIX.'product where product_id="'.$product_id.'"');
        
        if ($exists->num_rows) {
            $exists = true;
        } else {
            $exists = false;
        }

        if ($exists && $incremental) {
            //delete product_description product_tag product_to_category

            $sql = 'DELETE FROM `'.DB_PREFIX."product_description` WHERE `product_id` = '".$product_id."';\n";

            $this->db->query($sql);

            $sql = 'DELETE FROM `'.DB_PREFIX."url_alias` WHERE `query` = 'product_id=".$product_id."';\n";

            $this->db->query($sql);

            $sql = 'DELETE FROM `'.DB_PREFIX."product_to_category` WHERE `product_id` = '".$product_id."';\n";

            $this->db->query($sql);

            $sql = 'UPDATE `'.DB_PREFIX.'product` SET '
                //. "product_id='" . $product_id . "', "
                ."product_sr_no='".$product_sr_no."', "

                ."name='".$product_name."', "
                ."default_price='".$default_price."', "
                ."unit='".$unit."', "
                ."weight='".$weight."', "
                ."variations_id='".$variations_id."', "

                ."default_variation_name='".$default_variation_name."', "
                ."image='".$image."', "
                ."status='".$status."', "
                .'date_added = NOW(), '
                .'date_modified = NOW(), '
                // ."sort_order='".$sort_order."' Where model=".$model;
                ."sort_order='".$sort_order."' Where product_id==".$product_id;

            $this->db->query($sql);

            if (!$product_id) {
                $product_id = $this->db->getLastId();
            }

            foreach ($languages as $language) {
                $language_code = $language['code'];
                $language_id = $language['language_id'];
                $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
                $description = isset($descriptions[$language_code]) ? $this->db->escape($descriptions[$language_code]) : '';
                if ($exist_meta_title) {
                    $meta_title = isset($meta_titles[$language_code]) ? $this->db->escape($meta_titles[$language_code]) : '';
                }
                $meta_description = isset($meta_descriptions[$language_code]) ? $this->db->escape($meta_descriptions[$language_code]) : '';
                $meta_keyword = isset($meta_keywords[$language_code]) ? $this->db->escape($meta_keywords[$language_code]) : '';

                $unit = isset($unit) ? $this->db->escape($unit) : '';

                $tag = isset($tags[$language_code]) ? $this->db->escape($tags[$language_code]) : '';
                if ($exist_table_product_tag) {
                    $sql = 'DELETE FROM `'.DB_PREFIX."product_tag` WHERE `product_id` = '".$product_id."';\n";

                    $this->db->query($sql);

                    if ($exist_meta_title) {
                        $sql = 'INSERT INTO `'.DB_PREFIX.'product_description` (`product_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ';
                        $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword' );";
                    } else {
                        $sql = 'INSERT INTO `'.DB_PREFIX.'product_description` (`product_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES ';
                        $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword' );";
                    }
                    $this->db->query($sql);
                    $sql = 'INSERT INTO `'.DB_PREFIX.'product_tag` (`product_id`,`language_id`,`tag`) VALUES ';
                    $sql .= "($product_id, $language_id, '$tag')";
                    $this->db->query($sql);
                } else {
                    if ($exist_meta_title) {
                        $sql = 'INSERT INTO `'.DB_PREFIX.'product_description` (`product_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`, `tag`) VALUES ';
                        $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword', '$tag' );";
                    } else {
                        $sql = 'INSERT INTO `'.DB_PREFIX.'product_description` (`product_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `tag`) VALUES ';
                        $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword', '$tag' );";
                    }

                    $this->db->query($sql);
                }
            }

            if (count($categories) > 0) {
                $sql = 'INSERT INTO `'.DB_PREFIX.'product_to_category` (`product_id`,`category_id`) VALUES ';
                $first = true;

                foreach ($categories as $category_id) {
                    $sql .= ($first) ? "\n" : ",\n";
                    $first = false;
                    $sql .= "($product_id,$category_id)";
                }
                $sql .= ';';
                $this->db->query($sql);
            }

            if ($keyword) {
                if (isset($url_alias_ids[$product_id])) {
                    $url_alias_id = $url_alias_ids[$product_id];
                    $sql = 'INSERT INTO `'.DB_PREFIX."url_alias` (`url_alias_id`,`query`,`keyword`) VALUES ($url_alias_id,'product_id=$product_id','$keyword');";
                    unset($url_alias_ids[$product_id]);
                } else {
                    $sql = 'INSERT INTO `'.DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('product_id=$product_id','$keyword');";
                }
                $this->db->query($sql);
            }
        } else {
            $existsByModel = $this->db->query('select * from '.DB_PREFIX.'product where model="'.$model.'"');

            if ($existsByModel->num_rows) {
                return 0;
            }

            $sql = 'INSERT INTO `'.DB_PREFIX.'product` SET '
                ."product_id='".$product_id."', "
                ."product_sr_no='".$product_sr_no."', "

                ."name='".$product_name."', "
                ."default_price='".$default_price."', "
                ."unit='".$unit."', "
                ."weight='".$weight."', "
                ."variations_id='".$variations_id."', "

                ."default_variation_name='".$default_variation_name."', "
                ."model='".$model."',"
                ."image='".$image."', "
                ."status='".$status."', "
                .'date_added = NOW(), '
                .'date_modified = NOW(), '
                ."sort_order='".$sort_order."'";

            $this->db->query($sql);

            if (!$product_id) {
                $product_id = $this->db->getLastId();
            }

            foreach ($languages as $language) {
                $language_code = $language['code'];
                $language_id = $language['language_id'];
                $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
                $description = isset($descriptions[$language_code]) ? $this->db->escape($descriptions[$language_code]) : '';
                if ($exist_meta_title) {
                    $meta_title = isset($meta_titles[$language_code]) ? $this->db->escape($meta_titles[$language_code]) : '';
                }
                $meta_description = isset($meta_descriptions[$language_code]) ? $this->db->escape($meta_descriptions[$language_code]) : '';
                $meta_keyword = isset($meta_keywords[$language_code]) ? $this->db->escape($meta_keywords[$language_code]) : '';

                $unit = isset($unit) ? $this->db->escape($unit) : '';

                $tag = isset($tags[$language_code]) ? $this->db->escape($tags[$language_code]) : '';
                if ($exist_table_product_tag) {
                    if ($exist_meta_title) {
                        $sql = 'INSERT INTO `'.DB_PREFIX.'product_description` (`product_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ';
                        $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword' );";
                    } else {
                        $sql = 'INSERT INTO `'.DB_PREFIX.'product_description` (`product_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES ';
                        $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword' );";
                    }
                    $this->db->query($sql);
                    $sql = 'INSERT INTO `'.DB_PREFIX.'product_tag` (`product_id`,`language_id`,`tag`) VALUES ';
                    $sql .= "($product_id, $language_id, '$tag')";
                    $this->db->query($sql);
                } else {
                    if ($exist_meta_title) {
                        $sql = 'INSERT INTO `'.DB_PREFIX.'product_description` (`product_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`, `tag`) VALUES ';
                        $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword', '$tag' );";
                    } else {
                        $sql = 'INSERT INTO `'.DB_PREFIX.'product_description` (`product_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`, `tag`) VALUES ';
                        $sql .= "( $product_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword', '$tag' );";
                    }

                    $this->db->query($sql);
                }
            }

            if (count($categories) > 0) {
                $sql = 'INSERT INTO `'.DB_PREFIX.'product_to_category` (`product_id`,`category_id`) VALUES ';
                $first = true;

                foreach ($categories as $category_id) {
                    $sql .= ($first) ? "\n" : ",\n";
                    $first = false;
                    $sql .= "($product_id,$category_id)";
                }
                $sql .= ';';
                $this->db->query($sql);
            }

            if ($keyword) {
                if (isset($url_alias_ids[$product_id])) {
                    $url_alias_id = $url_alias_ids[$product_id];
                    $sql = 'INSERT INTO `'.DB_PREFIX."url_alias` (`url_alias_id`,`query`,`keyword`) VALUES ($url_alias_id,'product_id=$product_id','$keyword');";
                    unset($url_alias_ids[$product_id]);
                } else {
                    $sql = 'INSERT INTO `'.DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('product_id=$product_id','$keyword');";
                }
                $this->db->query($sql);
            }
        }
    }

    protected function uploadProductVariations(&$reader, $incremental, &$available_category_ids = [])
    {
        // get worksheet if there
        $data = $reader->getSheetByName('ProductVariations');
        if (null == $data) {
            return;
        }

        // if incremental then find current category IDs else delete all old categories
        $available_variation_ids = [];
        if ($incremental) {
            $available_variation_ids = $this->getAvailableVariationIds();
        }

        $first_row = [];
        $i = 0;
        $k = $data->getHighestRow();

        for ($i = 0; $i < $k; ++$i) {
            if (0 == $i) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j = 1; $j <= $max_col; ++$j) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }

            $j = 1;

            $variation_id = $this->getCell($data, $i, $j++, '0');
            $product_id = $this->getCell($data, $i, $j++, '0');
            $product_sr_no = $this->getCell($data, $i, $j++, '0');

            if ('' == $product_id && '' == $product_sr_no) {
                continue;
            }

            if (!$product_id) {
                $product_query = $this->db->query('select product_id from '.DB_PREFIX.'product where product_sr_no="'.$product_sr_no.'"');
                if ($product_query->num_rows) {
                    $product_id = $product_query->row['product_id'];
                } else {
                    continue;
                }
            }

            $name = $this->getCell($data, $i, $j++, '0');
            $image = $this->getCell($data, $i, $j++, '0');
            $model = $this->getCell($data, $i, $j++, '0');

            $sort_order = trim($this->getCell($data, $i, $j++));

            $variation = [];
            $variation['product_id'] = $product_id;
            $variation['name'] = $name;
            $variation['image'] = $image;
            $variation['model'] = $model;
            $variation['sort_order'] = $sort_order;

            if ($incremental) {
                if ($available_variation_ids) {
                    if (in_array((int) $variation_id, $available_variation_ids)) {
                        $this->deleteVariation($variation_id);
                    }
                }
            }
            $this->moreVariationCells($i, $j, $data, $variation);
            $this->storeVariationIntoDatabase($variation);
        }
    }

    protected function moreVariationCells($i, &$j, &$worksheet, &$variation)
    {
        return;
    }

    protected function storeVariationIntoDatabase($variation)
    {
        $sql = 'insert into `'.DB_PREFIX.'product_variation` SET '
            .'product_id="'.$variation['product_id'].'", '
            .'name="'.$variation['name'].'", '
            .'image="'.$variation['image'].'", '
            .'model="'.$variation['model'].'", '
            .'sort_order="'.$variation['sort_order'].'"';
        $this->db->query($sql);
    }

    protected function getAvailableVariationIds()
    {
        $sql = 'SELECT pv.id from '.DB_PREFIX.'product_variation pv inner join '.DB_PREFIX.'product p on p.product_id = pv.product_id';
        $result = $this->db->query($sql);
        $variation_ids = [0];
        foreach ($result->rows as $row) {
            if (!in_array((int) $row['id'], $variation_ids)) {
                $variation_ids[] = (int) $row['id'];
            }
        }

        return $variation_ids;
    }

    protected function deleteVariation($variation_id)
    {
        $this->db->query('delete from `'.DB_PREFIX.'product_variation` WHERE id="'.$variation_id.'"');
    }

    /*
    	Category Start Here

     */
    protected function getCategoryUrlAliasIds()
    {
        $sql = "SELECT url_alias_id, SUBSTRING( query, CHAR_LENGTH('category_id=')+1 ) AS category_id ";
        $sql .= 'FROM `'.DB_PREFIX.'url_alias` ';
        $sql .= "WHERE query LIKE 'category_id=%'";
        $query = $this->db->query($sql);
        $url_alias_ids = [];
        foreach ($query->rows as $row) {
            $url_alias_id = $row['url_alias_id'];
            $category_id = $row['category_id'];
            $url_alias_ids[$category_id] = $url_alias_id;
        }

        return $url_alias_ids;
    }

    protected function getAvailableCategoryIds()
    {
        $sql = 'SELECT `category_id` FROM `'.DB_PREFIX.'category`;';
        $result = $this->db->query($sql);
        $category_ids = [];
        foreach ($result->rows as $row) {
            $category_ids[$row['category_id']] = $row['category_id'];
        }

        return $category_ids;
    }

    protected function getAvailableStoreIds()
    {
        $sql = 'SELECT store_id FROM `'.DB_PREFIX."store` WHERE vendor_id='".$this->user->getId()."';";
        $result = $this->db->query($sql);
        $store_ids = [0];
        foreach ($result->rows as $row) {
            if (!in_array((int) $row['store_id'], $store_ids)) {
                $store_ids[] = (int) $row['store_id'];
            }
        }

        return $store_ids;
    }

    protected function getAvailableStoreIdsAdmin()
    {
        $sql = 'SELECT store_id FROM `'.DB_PREFIX.'store`';
        $result = $this->db->query($sql);
        $store_ids = [0];
        foreach ($result->rows as $row) {
            if (!in_array((int) $row['store_id'], $store_ids)) {
                $store_ids[] = (int) $row['store_id'];
            }
        }

        return $store_ids;
    }

    protected function deleteCategory($category_id)
    {
        $sql = 'DELETE FROM `'.DB_PREFIX."category` WHERE `category_id` = '".(int) $category_id."' ;\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."category_description` WHERE `category_id` = '".(int) $category_id."' ;\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."category_to_store` WHERE `category_id` = '".(int) $category_id."' ;\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."url_alias` WHERE `query` LIKE 'category_id=".(int) $category_id."';\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."category_to_layout` WHERE `category_id` = '".(int) $category_id."' ;\n";
        $this->multiquery($sql);
        $sql = 'SHOW TABLES LIKE "'.DB_PREFIX.'category_path"';
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $sql = 'DELETE FROM `'.DB_PREFIX."category_path` WHERE `category_id` = '".(int) $category_id."'";
            $this->db->query($sql);
        }
    }

    protected function moreCategoryCells($i, &$j, &$worksheet, &$category)
    {
        return;
    }

    protected function storeCategoryIntoDatabase(&$category, &$languages, $exist_meta_title, &$layout_ids, &$available_store_ids, &$url_alias_ids)
    {
        // extract the category details
        $category_id = $category['category_id'];
        $image_name = $this->db->escape($category['image']);
        $parent_id = $category['parent_id'];
        $top = $category['top'];
        $top = (('TRUE' == strtoupper($top)) || ('YES' == strtoupper($top)) || ('ENABLED' == strtoupper($top))) ? 1 : 0;
        $columns = $category['columns'];
        $sort_order = $category['sort_order'];
        $date_added = $category['date_added'];
        $date_modified = $category['date_modified'];
        $names = $category['names'];
        $descriptions = $category['descriptions'];
        if ($exist_meta_title) {
            $meta_titles = $category['meta_titles'];
        }
        $meta_descriptions = $category['meta_descriptions'];
        $meta_keywords = $category['meta_keywords'];
        $seo_keyword = $category['seo_keyword'];
        $store_ids = $category['store_ids'];
        $layout = $category['layout'];
        $status = $category['status'];
        $status = (('TRUE' == strtoupper($status)) || ('YES' == strtoupper($status)) || ('ENABLED' == strtoupper($status))) ? 1 : 0;

        // generate and execute SQL for inserting the category
        $sql = 'INSERT INTO `'.DB_PREFIX.'category` (`category_id`, `image`, `parent_id`, `top`, `column`, `sort_order`, `date_added`, `date_modified`, `status`) VALUES ';
        $sql .= "( $category_id, '$image_name', $parent_id, $top, $columns, $sort_order, ";
        $sql .= ('NOW()' == $date_added) ? "$date_added," : "'$date_added',";
        $sql .= ('NOW()' == $date_modified) ? "$date_modified," : "'$date_modified',";
        $sql .= " $status);";
        $this->db->query($sql);
        foreach ($languages as $language) {
            $language_code = $language['code'];
            $language_id = $language['language_id'];
            $name = isset($names[$language_code]) ? $this->db->escape($names[$language_code]) : '';
            $description = isset($descriptions[$language_code]) ? $this->db->escape($descriptions[$language_code]) : '';
            if ($exist_meta_title) {
                $meta_title = isset($meta_titles[$language_code]) ? $this->db->escape($meta_titles[$language_code]) : '';
            }
            $meta_description = isset($meta_descriptions[$language_code]) ? $this->db->escape($meta_descriptions[$language_code]) : '';
            $meta_keyword = isset($meta_keywords[$language_code]) ? $this->db->escape($meta_keywords[$language_code]) : '';
            if ($exist_meta_title) {
                $sql = 'INSERT INTO `'.DB_PREFIX.'category_description` (`category_id`, `language_id`, `name`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ';
                $sql .= "( $category_id, $language_id, '$name', '$description', '$meta_title', '$meta_description', '$meta_keyword' );";
            } else {
                $sql = 'INSERT INTO `'.DB_PREFIX.'category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES ';
                $sql .= "( $category_id, $language_id, '$name', '$description', '$meta_description', '$meta_keyword' );";
            }
            $this->db->query($sql);
        }
        if ($seo_keyword) {
            if (isset($url_alias_ids[$category_id])) {
                $url_alias_id = $url_alias_ids[$category_id];
                $sql = 'INSERT INTO `'.DB_PREFIX."url_alias` (`url_alias_id`,`query`,`keyword`) VALUES ($url_alias_id,'category_id=$category_id','$seo_keyword');";
                unset($url_alias_ids[$category_id]);
            } else {
                $sql = 'INSERT INTO `'.DB_PREFIX."url_alias` (`query`,`keyword`) VALUES ('category_id=$category_id','$seo_keyword');";
            }
            $this->db->query($sql);
        }
        foreach ($store_ids as $store_id) {
            if (in_array((int) $store_id, $available_store_ids)) {
                $sql = 'INSERT INTO `'.DB_PREFIX."category_to_store` (`category_id`,`store_id`) VALUES ($category_id,$store_id);";
                $this->db->query($sql);
            }
        }
        $layouts = [];
        foreach ($layout as $layout_part) {
            $next_layout = explode(':', $layout_part);
            if (false === $next_layout) {
                $next_layout = [0, $layout_part];
            } elseif (1 == count($next_layout)) {
                $next_layout = [0, $layout_part];
            }
            if ((2 == count($next_layout)) && (in_array((int) $next_layout[0], $available_store_ids)) && (is_string($next_layout[1]))) {
                $store_id = (int) $next_layout[0];
                $layout_name = $next_layout[1];
                if (isset($layout_ids[$layout_name])) {
                    $layout_id = (int) $layout_ids[$layout_name];
                    if (!isset($layouts[$store_id])) {
                        $layouts[$store_id] = $layout_id;
                    }
                }
            }
        }
        foreach ($layouts as $store_id => $layout_id) {
            $sql = 'INSERT INTO `'.DB_PREFIX."category_to_layout` (`category_id`,`store_id`,`layout_id`) VALUES ($category_id,$store_id,$layout_id);";
            $this->db->query($sql);
        }
    }

    protected function deleteCategories(&$url_alias_ids)
    {
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX."category`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."category_description`;\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."category_to_store`;\n";
        $sql .= 'DELETE FROM `'.DB_PREFIX."url_alias` WHERE `query` LIKE 'category_id=%';\n";
        $sql .= 'TRUNCATE TABLE `'.DB_PREFIX."category_to_layout`;\n";
        $this->multiquery($sql);
        $sql = 'SHOW TABLES LIKE "'.DB_PREFIX.'category_path"';
        $query = $this->db->query($sql);
        if ($query->num_rows) {
            $sql = 'TRUNCATE TABLE `'.DB_PREFIX.'category_path`';
            $this->db->query($sql);
        }
        $sql = 'SELECT (MAX(url_alias_id)+1) AS next_url_alias_id FROM `'.DB_PREFIX.'url_alias` LIMIT 1';
        $query = $this->db->query($sql);
        $next_url_alias_id = $query->row['next_url_alias_id'];
        $sql = 'ALTER TABLE `'.DB_PREFIX."url_alias` AUTO_INCREMENT = $next_url_alias_id";
        $this->db->query($sql);
        $remove = [];
        foreach ($url_alias_ids as $category_id => $url_alias_id) {
            if ($url_alias_id >= $next_url_alias_id) {
                $remove[$category_id] = $url_alias_id;
            }
        }
        foreach ($remove as $category_id => $url_alias_id) {
            unset($url_alias_ids[$category_id]);
        }
    }

    protected function populateAdditionalImagesWorksheet(&$worksheet, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        // check for the existence of product_image.sort_order field
        $sql = 'SHOW COLUMNS FROM `'.DB_PREFIX."product_image` LIKE 'sort_order'";
        $query = $this->db->query($sql);
        $exist_sort_order = ($query->num_rows > 0) ? true : false;

        // Set the column widths
        $j = 0;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('product_id'), 4) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('image'), 70) + 1);
        if ($exist_sort_order) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('sort_order'), 5) + 1);
        }

        // The additional images headings row and colum styles
        $styles = [];
        $data = [];
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_id';
        $styles[$j] = &$text_format;
        $data[$j++] = 'image';
        if ($exist_sort_order) {
            $data[$j++] = 'sort_order';
        }
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual additional images data
        $styles = [];
        ++$i;
        $j = 0;
        $additional_images = $this->getAdditionalImages($min_id, $max_id, $exist_sort_order);
        foreach ($additional_images as $row) {
            $data = [];
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data[$j++] = $row['product_id'];
            $data[$j++] = $row['image'];
            if ($exist_sort_order) {
                $data[$j++] = $row['sort_order'];
            }

            $this->setCellRow($worksheet, $i, $data, $styles);
            ++$i;
            $j = 0;
        }
    }

    protected function getAdditionalImages($min_id = null, $max_id = null, $exist_sort_order = true)
    {
        if ($exist_sort_order) {
            $sql = 'SELECT product_id, image, sort_order ';
        } else {
            $sql = 'SELECT product_id, image ';
        }
        $sql .= 'FROM `'.DB_PREFIX.'product_image` ';
        if (isset($min_id) && isset($max_id)) {
            $sql .= "WHERE product_id BETWEEN $min_id AND $max_id ";
        }
        if ($exist_sort_order) {
            $sql .= 'ORDER BY product_id, sort_order, image;';
        } else {
            $sql .= 'ORDER BY product_id, image;';
        }
        $result = $this->db->query($sql);

        return $result->rows;
    }

    protected function uploadAdditionalImages(&$reader, $incremental, &$available_product_ids)
    {
        // get worksheet, if not there return immediately
        $log = new Log('error.log');

        $data = $reader->getSheetByName('AdditionalImages');
        if (null == $data) {
            return;
        }

        // if incremental then find current product IDs else delete all old additional images
        if ($incremental) {
            $unlisted_product_ids = $available_product_ids;
        } else {
            $this->deleteAdditionalImages();
        }

        // check for the existence of product_image.sort_order field
        $sql = 'SHOW COLUMNS FROM `'.DB_PREFIX."product_image` LIKE 'sort_order'";
        $query = $this->db->query($sql);
        $exist_sort_order = ($query->num_rows > 0) ? true : false;

        // load the worksheet cells and store them to the database
        $old_product_image_ids = [];
        $previous_product_id = 0;
        $i = 0;
        $k = $data->getHighestRow();
        for ($i = 0; $i < $k; ++$i) {
            $j = 1;
            if (0 == $i) {
                continue;
            }
            $product_id = trim($this->getCell($data, $i, $j++));
            if ('' == $product_id) {
                continue;
            }
            $image_name = $this->getCell($data, $i, $j++, '');
            if ($exist_sort_order) {
                $sort_order = $this->getCell($data, $i, $j++, '0');
            }
            $image = [];
            $image['product_id'] = $product_id;
            $image['image_name'] = $image_name;
            if ($exist_sort_order) {
                $image['sort_order'] = $sort_order;
            }
            if (($incremental) && ($product_id != $previous_product_id)) {
                $old_product_image_ids = $this->deleteAdditionalImage($product_id);
                if (isset($unlisted_product_ids[$product_id])) {
                    unset($unlisted_product_ids[$product_id]);
                }
            }
            $this->moreAdditionalImageCells($i, $j, $data, $image);
            $this->storeAdditionalImageIntoDatabase($image, $old_product_image_ids, $exist_sort_order);
            $previous_product_id = $product_id;
        }
        if ($incremental) {
            $this->deleteUnlistedAdditionalImages($unlisted_product_ids);
        }
    }

    protected function storeAdditionalImageIntoDatabase(&$image, &$old_product_image_ids, $exist_sort_order = true)
    {
        $product_id = $image['product_id'];
        $image_name = $image['image_name'];
        if ($exist_sort_order) {
            $sort_order = $image['sort_order'];
        }
        if (isset($old_product_image_ids[$product_id][$image_name])) {
            $product_image_id = $old_product_image_ids[$product_id][$image_name];
            if ($exist_sort_order) {
                $sql = 'INSERT INTO `'.DB_PREFIX.'product_image` (`product_image_id`,`product_id`,`image`,`sort_order` ) VALUES ';
                $sql .= "($product_image_id,$product_id,'".$this->db->escape($image_name)."',$sort_order)";
            } else {
                $sql = 'INSERT INTO `'.DB_PREFIX.'product_image` (`product_image_id`,`product_id`,`image` ) VALUES ';
                $sql .= "($product_image_id,$product_id,'".$this->db->escape($image_name)."')";
            }
            $this->db->query($sql);
            unset($old_product_image_ids[$product_id][$image_name]);
        } else {
            if ($exist_sort_order) {
                $sql = 'INSERT INTO `'.DB_PREFIX.'product_image` (`product_id`,`image`,`sort_order` ) VALUES ';
                $sql .= "($product_id,'".$this->db->escape($image_name)."',$sort_order)";
            } else {
                $sql = 'INSERT INTO `'.DB_PREFIX.'product_image` (`product_id`,`image` ) VALUES ';
                $sql .= "($product_id,'".$this->db->escape($image_name)."')";
            }
            $this->db->query($sql);
        }
    }

    protected function deleteUnlistedAdditionalImages(&$unlisted_product_ids)
    {
        foreach ($unlisted_product_ids as $product_id) {
            $sql = 'DELETE FROM `'.DB_PREFIX."product_image` WHERE product_id='".(int) $product_id."'";
            $this->db->query($sql);
        }
    }

    protected function moreAdditionalImageCells($i, &$j, &$worksheet, &$image)
    {
        return;
    }

    protected function deleteAdditionalImages()
    {
        $sql = 'TRUNCATE TABLE `'.DB_PREFIX.'product_image`';
        $this->db->query($sql);
    }

    protected function deleteAdditionalImage($product_id)
    {
        $sql = 'SELECT product_image_id, product_id, image FROM `'.DB_PREFIX."product_image` WHERE product_id='".(int) $product_id."'";
        $query = $this->db->query($sql);
        $old_product_image_ids = [];
        foreach ($query->rows as $row) {
            $product_image_id = $row['product_image_id'];
            $product_id = $row['product_id'];
            $image_name = $row['image'];
            $old_product_image_ids[$product_id][$image_name] = $product_image_id;
        }
        if ($old_product_image_ids) {
            $sql = 'DELETE FROM `'.DB_PREFIX."product_image` WHERE product_id='".(int) $product_id."'";
            $this->db->query($sql);
        }

        return $old_product_image_ids;
    }

    public function uploadCityZipcode($filename, $store_id)
    {
        //echo "<pre>";print_r($filename);die;
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $log = new Log('error.log');
        $log->write('upload 2');
        // if ( $this->user->isVendor() ) {
        //     $user_id = $this->user->getId();
        // }else {
        //     $user_id = 0;
        // }

        // we use our own error handler
        global $registry;
        $registry = $this->registry;
        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');

        try {
            $log->write('upload 2.4');
            $this->session->data['export_import_nochange'] = 1;

            // we use the PHPExcel package from http://phpexcel.codeplex.com/
            $cwd = getcwd();
            chdir(DIR_SYSTEM.'PHPExcel');
            require_once 'Classes/PHPExcel.php';
            chdir($cwd);

            // Memory Optimization
            if ($this->config->get('export_import_settings_use_import_cache')) {
                $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                $cacheSettings = [' memoryCacheSize ' => '16MB'];
                PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            }

            // parse uploaded spreadsheet file
            $inputFileType = PHPExcel_IOFactory::identify($filename);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $reader = $objReader->load($filename);

            $this->clearCache();
            $this->session->data['export_import_nochange'] = 0;
            $available_product_ids = [];

            $this->uploadZipcodes($reader, $store_id);

            return true;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP '.get_class($e).':  '.$errstr.' in '.$errfile.' on line '.$errline);
            }

            return false;
        }
    }

    protected function uploadZipcodes(&$reader, $store_id)
    {
        $log = new Log('error.log');
        $log->write('upload 3.4');

        //echo "<pre>";print_r($reader);die;
        // get worksheet, if not there return immediately

        //$data = $reader->getSheetNames();
        $data = $reader->getSheetByName('Worksheet');

        //echo "<pre>";print_r($data);die;

        if (null == $data) {
            return;
        }

        // load the worksheet cells and store them to the database
        $first_row = [];
        $i = 0;
        $k = $data->getHighestRow();

        for ($i = 0; $i < $k; ++$i) {
            if ($i <= 3) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j = 1; $j <= $max_col; ++$j) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $zipcode = trim($this->getCell($data, $i, $j));

            $product['city_zipcodes'][] = $zipcode;
        }

        //echo "<pre>";print_r($store_id);die;
        $this->storeStoreZipcodeIntoDatabase($product, $store_id);
    }

    protected function storeStoreZipcodeIntoDatabase($data, $store_id)
    {
        $data['zipcode'] = '';
        if (count($data['city_zipcodes']) > 0) {
            $data['zipcode'] = implode(',', $data['city_zipcodes']);
        }

        $sql = 'DELETE from '.DB_PREFIX."store_zipcodes  WHERE store_id = '".(int) $store_id."'";

        $this->db->query($sql);

        foreach ($data['city_zipcodes'] as $zipcode) {
            $this->db->query('INSERT INTO `'.DB_PREFIX.'store_zipcodes` set store_id="'.$store_id.'", zipcode="'.$zipcode.'"');
        }
    }

    public function uploadGeneralCityZipcode($filename, $city_id)
    {
        //echo "<pre>";print_r($filename);die;
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $log = new Log('error.log');
        $log->write('upload 2');
        // if ( $this->user->isVendor() ) {
        //     $user_id = $this->user->getId();
        // }else {
        //     $user_id = 0;
        // }

        // we use our own error handler
        global $registry;
        $registry = $this->registry;
        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');

        try {
            $log->write('upload 2.4');
            $this->session->data['export_import_nochange'] = 1;

            // we use the PHPExcel package from http://phpexcel.codeplex.com/
            $cwd = getcwd();
            chdir(DIR_SYSTEM.'PHPExcel');
            require_once 'Classes/PHPExcel.php';
            chdir($cwd);

            // Memory Optimization
            if ($this->config->get('export_import_settings_use_import_cache')) {
                $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                $cacheSettings = [' memoryCacheSize ' => '16MB'];
                PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            }

            // parse uploaded spreadsheet file
            $inputFileType = PHPExcel_IOFactory::identify($filename);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $reader = $objReader->load($filename);

            $this->clearCache();
            $this->session->data['export_import_nochange'] = 0;
            $available_product_ids = [];

            $this->uploadGeneralZipcodes($reader, $city_id);

            return true;
        } catch (Exception $e) {
            $errstr = $e->getMessage();
            $errline = $e->getLine();
            $errfile = $e->getFile();
            $errno = $e->getCode();
            $this->session->data['export_import_error'] = ['errstr' => $errstr, 'errno' => $errno, 'errfile' => $errfile, 'errline' => $errline];
            if ($this->config->get('config_error_log')) {
                $this->log->write('PHP '.get_class($e).':  '.$errstr.' in '.$errfile.' on line '.$errline);
            }

            return false;
        }
    }

    protected function uploadGeneralZipcodes(&$reader, $city_id)
    {
        $log = new Log('error.log');
        $log->write('upload 3.4');

        //echo "<pre>";print_r($reader);die;
        // get worksheet, if not there return immediately

        //$data = $reader->getSheetNames();
        $data = $reader->getSheetByName('Worksheet');

        //echo "<pre>";print_r($data);die;

        if (null == $data) {
            return;
        }

        // load the worksheet cells and store them to the database
        $first_row = [];
        $i = 0;
        $k = $data->getHighestRow();

        for ($i = 0; $i < $k; ++$i) {
            if ($i <= 3) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j = 1; $j <= $max_col; ++$j) {
                    $first_row[] = $this->getCell($data, $i, $j);
                }
                continue;
            }
            $j = 1;
            $zipcode = trim($this->getCell($data, $i, $j));

            $product['city_zipcodes'][] = $zipcode;
        }

        //echo "<pre>";print_r($city_id);die;
        $this->storeCityZipcodeIntoDatabase($product, $city_id);
    }

    protected function storeCityZipcodeIntoDatabase($data, $city_id)
    {
        foreach ($data['city_zipcodes'] as $zipcode) {
            $this->db->query('INSERT INTO '.DB_PREFIX."city_zipcodes SET city_id = '".(int) $city_id."', zipcode = '".$zipcode."'");
        }
    }

    protected function uploadCategoryPricesData($reader, $incremental)
    {
        $log = new Log('error.log');
        $log->write('upload 3.4');

        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Product_Prices');
        //echo '<pre>';print_r($data);exit;
        // $cache_price_data = array();
        // load the worksheet cells and store them to the database
        $customer_categoy_price = [];
        $first_row = [];
        $i = 0;
        $k = $data->getHighestRow();
        for ($i = 0; $i < $k; ++$i) {
            if (0 == $i) {
                /*$max_col = PHPExcel_Cell::columnIndexFromString( $data->getHighestColumn() );
                for ( $j = 1; $j <= $max_col; $j+=1 ) {
                    $first_row[] = $this->getCell( $data, $i, $j );
                }*/
                continue;
            }
            $j = 1;
            $product_store_id = trim($this->getCell($data, $i, $j++));
            $product_id = trim($this->getCell($data, $i, $j++));
            $name = trim($this->getCell($data, $i, $j++));
            $store_id = trim($this->getCell($data, $i, $j++));
            $price_category = trim($this->getCell($data, $i, $j++));
            $unit = trim($this->getCell($data, $i, $j++));
            $price = trim($this->getCell($data, $i, $j++));
            $status = trim($this->getCell($data, $i, $j++));
            $dataProduct = [];
            $dataProduct['product_store_id'] = $product_store_id;
            $dataProduct['product_id'] = $product_id;
            $dataProduct['product_name'] = $name;
            $dataProduct['store_id'] = $store_id;
            $dataProduct['price_category'] = $price_category;
            $dataProduct['price'] = $price;
            $dataProduct['status'] = $status;
            //echo $product_store_id.'==='.$product_id.'==='.$name.'==='.$store_id.'==='.$price_category.'==='.$price;
            //exit;
            //$cache_price_data[$product_store_id.'_'.$price_category.'_'.$store_id] = $price;

            /*if (! $incremental ) {
                $log->write('upload in incremental');
                $this->deleteCategoryPriceRow( $product_store_id, $store_id, $price_category);
            }*/
            $this->addUpdateCategoryProductPrice($product_store_id, $store_id, $price_category, $dataProduct);
            //$this->storeProductIntoDatabase( $product, $languages, $product_fields, $exist_table_product_tag, $exist_meta_title, $layout_ids, $available_store_ids, $manufacturers, $weight_class_ids, $length_class_ids, $url_alias_ids,$incremental );
            $customer_categoy_price[] = $price_category;
        }
        //exit;
        $this->cacheProductPrices($store_id);
        
        $customer_categoy_price = array_unique($customer_categoy_price);
        $log = new Log('error.log');
        $log->write($customer_categoy_price);
        if (is_array($customer_categoy_price) && count($customer_categoy_price) > 0) {
            foreach ($customer_categoy_price as $customer_categoy_pri) {
                $log->write($customer_categoy_pri);
                $this->load->model('account/customer');
                $customer_device_info = $this->model_account_customer->getCustomerByCategoryPrice($customer_categoy_pri);
                $log->write($customer_device_info);
                if (is_array($customer_device_info) && $customer_device_info != NULL) {
                    $this->model_account_customer->sendCustomerByCategoryPriceNotification($customer_device_info);
                }
            }
        }
        // $this->cache->delete('category_price_data');
        // $this->cache->set('category_price_data',$cache_price_data);
        return true;
    }

    protected function deleteCategoryPriceRow($product_store_id, $store_id, $price_category)
    {
        $sql = 'DELETE FROM `'.DB_PREFIX."product_category_prices` WHERE `product_store_id` = $product_store_id AND `price_category` = $price_category AND `store_id` = $store_id";
        $this->db->query($sql);
    }

    protected function addUpdateCategoryProductPrice($product_store_id, $store_id, $price_category, $data)
    {
        $sql = 'SELECT *  FROM `'.DB_PREFIX."product_category_prices` WHERE `product_store_id` = $product_store_id AND `price_category` = '$price_category' AND `store_id` = $store_id";
        $result = $this->db->query($sql);
        //echo "<pre>";print_r($result);
        if (count($result->rows)) {
            /* Update */
            $sql = 'UPDATE `'.DB_PREFIX."product_category_prices` SET price='".$data['price']."',product_name='".$data['product_name']."',status='".$data['status']."' WHERE product_store_id = '".$product_store_id."' AND price_category = '".$price_category."' AND  store_id = '".$store_id."' ";
            $this->db->query($sql);
        //echo "<pre>";print_r($sql);
        } else {
            /* Add */
            $matstring = implode("','", $data);
            $sql = 'INSERT INTO `'.DB_PREFIX."product_category_prices` (`product_store_id`, `product_id`, `product_name`, `store_id`, `price_category`, `price`, `status`) VALUES ('$matstring')";
            $this->db->query($sql);
            //echo "<pre>";print_r($sql);
            //$this->db->query($sql);
        }
    }

    protected function cacheProductPrices($store_id)
    {
        $this->cache->delete('category_price_data');
        $cache_price_data = [];
        $sql = 'SELECT * FROM `'.DB_PREFIX."product_category_prices` where `store_id` = $store_id";
        //echo $sql;exit;
        $resultsdata = $this->db->query($sql);
        //echo '<pre>'; print_r($resultsdata);exit;
        if (count($resultsdata->rows) > 0) {
            foreach ($resultsdata->rows as $result) {
                $cache_price_data[$result['product_store_id'].'_'.$result['price_category'].'_'.$store_id] = $result['price'];
            }
        }
        $this->cache->set('category_price_data', $cache_price_data);
    }
}
