<?php

class ModelToolExportImportVendor extends Model
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

    public function validateHeading(&$data, &$expected, &$multilingual = [])
    {
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

        $log = new Log('error.log');
        $log->write('validateHeading product');
        $log->write($heading);

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

    protected function setCellRowNew($worksheet, $row/* 1-based */, $data, &$style = null)
    {
        //echo "<pre>";print_r($data);die;
        $worksheet->fromArray($data, null, 'A'.$row, true);
        foreach ($data as $col => $val) {
            if (1 == $col) {
                $worksheet->setCellValueExplicit('A'.$row, $val, PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
        if (!empty($style)) {
            $worksheet->getStyle("$row:$row")->applyFromArray($style, false);
        }
    }

    protected function setCellRowNewForExport($worksheet, $row/* 1-based */, $data, &$style = null)
    {
        //echo "<pre>";print_r($data);die;
        end($data);
        $last_key = key($data);

        //echo "<pre>";print_r($key);die;

        $i = 0;
        for ($i = 0; $i <= $last_key; ++$i) {
            if (!isset($data[$i])) {
                $data[$i] = '';
            }
        }

        //echo "<pre>";print_r($data);die;

        ksort($data);

        $worksheet->fromArray($data, null, 'A'.$row, true);
        foreach ($data as $col => $val) {
            if (0 == $col) {
                $worksheet->setCellValueExplicit('A'.$row, $val, PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
        if (!empty($style)) {
            $worksheet->getStyle("$row:$row")->applyFromArray($style, false);
        }
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

    public function downloadModelsNotPresent($export_type = 'p', $offset = null, $rows = null, $min_id = null, $max_id = null, $model_not_found)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        // we use our own error handler
        global $registry;
        $registry = $this->registry;
        /*set_error_handler( 'error_handler_for_export_import', E_ALL );
        register_shutdown_function( 'fatal_error_shutdown_handler_for_export_import' );*/

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

            //$range = 'A1:B1'.$latestBLColumn.$row;

            $workbook->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $workbook->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //$workbook->getDefaultStyle()->getNumberFormat()->setFormatCode( '0000000000000' );

            // pre-define some commonly used styles
            $box_format = [
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => 'F0F0F0'],
                ],
            ];
            $text_format = [];
            $price_format = [
                'numberformat' => [
                    'code' => '######0.00',
                ],
                'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT],
            ];
            $weight_format = [
                'numberformat' => [
                    'code' => '##0.00',
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
            ];

            // create the worksheets
            $worksheet_index = 0;
            switch ($export_type) {
            case 'p':
                // creating the Products worksheet
                $workbook->setActiveSheetIndex($worksheet_index++);
                $worksheet = $workbook->getActiveSheet();

                //$worksheet = $workbook->getActiveSheet()->setCellValueExplicit('A4', '0029', PHPExcel_Cell_DataType::TYPE_STRING);

                /*$worksheet->setCellValueExplicit(
                    'A1',
                    '0054672351',
                    PHPExcel_Cell_DataType::TYPE_STRING
                );*/

                $worksheet->setTitle('Report');

                //PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_MyValueBinder() );
                $this->populateModelsNotPresentWorksheet($worksheet, $languages, $default_language_id, $price_format, $box_format, $weight_format, $text_format, $offset, $rows, $min_id, $max_id, $model_not_found);
                $worksheet->freezePaneByColumnAndRow(1, 2);

                break;
            default:
                break;
            }

            $workbook->setActiveSheetIndex(0);

            // redirect output to client browser
            $datetime = date('Y-m-d');
            switch ($export_type) {
            case 'p':
                $filename = 'products-not-present-'.$datetime;
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

            //echo "<pre>";print_r("Er");die;

            // Clear the spreadsheet caches
            $this->clearSpreadsheetCache();

            //exit;
            return;
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

    public function downloadVendor($export_type, $offset = null, $rows = null, $min_id = null, $max_id = null)
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
            ];
            $text_format = [];
            $price_format = [
                'numberformat' => [
                    'code' => '######0.00',
                ],
                'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT],
            ];
            $weight_format = [
                'numberformat' => [
                    'code' => '##0.00',
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
            ];

            /*foreach(range('A','Z') as $columnID) {
                $workbook->getActiveSheet()->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }*/

            // create the worksheets
            $worksheet_index = 0;
            switch ($export_type) {
            case 'p':
                // creating the Products worksheet
                $workbook->setActiveSheetIndex($worksheet_index++);
                $worksheet = $workbook->getActiveSheet();
                $worksheet->setTitle('Report');
                $this->populateVendorProductsWorksheet($worksheet, $languages, $default_language_id, $price_format, $box_format, $weight_format, $text_format, $offset, $rows, $min_id, $max_id);
                $worksheet->freezePaneByColumnAndRow(1, 2);

                /*// creating the ProductVariations worksheet
                $workbook->createSheet();
                $workbook->setActiveSheetIndex( $worksheet_index++ );
                $worksheet = $workbook->getActiveSheet();
                $worksheet->setTitle( 'ProductVariations' );
                $this->populateVendorProductVariationsWorksheet( $worksheet, $languages, $default_language_id, $box_format, $text_format, $min_id, $max_id );
                $worksheet->freezePaneByColumnAndRow( 1, 2 );*/
                break;
            default:
                break;
            }

            $workbook->setActiveSheetIndex(0);

            // redirect output to client browser
            $datetime = date('Y-m-d');
            switch ($export_type) {
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

    public function getStores($data = [])
    {
        $stores = $this->db->query('SELECT *  FROM '.DB_PREFIX.'excel_store_mapping WHERE vendor_id = '.$this->user->getId())->rows;

        //echo "<pre>";print_r($stores);die;
        return $stores;

        /*$sql  = "SELECT s.*, c.name as city FROM " . DB_PREFIX . "store s ";
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'city` c on c.city_id = s.city_id ';

        if (!empty($data['filter_vendor'])) {
            $sql .= " inner join `" . DB_PREFIX . "user` u on u.user_id = s.vendor_id";
        }

        $implode = array();

        if ($this->user->isVendor()) {
            $implode[] = "s.vendor_id = '" . $this->user->getId() . "'";
        }

        if (!empty($data['filter_vendor'])) {
            $implode[] = "CONCAT(u.firstname,' ',u.lastname) LIKE '" . $this->db->escape($data['filter_vendor']). "%'";
        }

        if (!empty($data['filter_city'])) {
            $implode[] = "c.name LIKE '" . $this->db->escape($data['filter_city']) . "%'";
        }
        if (!empty($data['filter_name'])) {
            $implode[] = "s.name LIKE '" .$this->db->escape( $data['filter_name'] ). "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "s.date_added = '" . $this->db->escape($data['filter_date_added']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "s.status = '" . $this->db->escape($data['filter_status']) . "'";
        }

        if($implode){
            $sql .= " WHERE " . implode(' AND ', $implode);
        }

        $sort_data = array(
            's.name',
            's.status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $this->db->escape($data['sort']);
        } else {
            $sql .= " ORDER BY s.name";
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

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        return $this->db->query($sql)->rows;*/
    }

    public function populateVendorProductsWorksheet(&$worksheet, &$languages, $default_language_id, &$price_format, &$box_format, &$weight_format, &$text_format, $offset = null, $rows = null, &$min_id = null, &$max_id = null)
    {
        $j = 0;

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Barcode'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Item name'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Department'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Sell Price'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('PriceDropSP'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('StartDate'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('EndDate'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Purchase Block'), 10) + 1);

        $stores = $this->getStores();

        $mappedStore = [];

        foreach ($stores as $store) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen($this->getMappedStore($store['store_id'])), 10) + 1);

            $mappedStore[$store['store_id']] = $j;
        }

        //echo "<pre>";print_r($mappedStore);die;

        // The product headings row and column styles

        $styles = [];
        $data = [];
        $i = 1;
        $j = 0;

        $data[$j++] = 'Barcode';
        $data[$j++] = 'Item name';
        $data[$j++] = 'Department';
        $data[$j++] = 'Sell Price';

        $data[$j++] = 'PriceDropSP';
        $data[$j++] = 'StartDate';
        $data[$j++] = 'EndDate';
        $data[$j++] = 'Purchase Block';

        foreach ($stores as $store) {
            $data[$j++] = $this->getMappedStore($store['store_id']);
        }

        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);
        // The actual products data
        ++$i;
        $j = 0;

        $product_fields = [];
        $exist_meta_title = false;

        //$products = $this->getGeneralProducts( $languages, $default_language_id, $product_fields, $exist_meta_title, $offset, $rows, $min_id, $max_id );
        $products = $this->getDistinctProducts($languages, $default_language_id, $offset, $rows, $min_id, $max_id);

        //echo "<pre>";print_r($products);die;
        $len = count($products);
        $min_id = $products[0]['product_store_id'];
        $max_id = $products[$len - 1]['product_store_id'];
        foreach ($products as $row) {
            $data = [];
            $worksheet->getRowDimension($i)->setRowHeight(26);

            $tmp_name = '';

            foreach ($row['name'] as $key => $value) {
                $tmp_name = $value;
                break;
            }

            $data[$j++] = $row['model'];
            $data[$j++] = ''; //$tmp_name ." - " .$row['unit'];
            $data[$j++] = '';
            $data[$j++] = $row['price'];
            $data[$j++] = $row['special_price'];

            $data[$j++] = '';
            $data[$j++] = '';
            $data[$j++] = '';

            //echo "<pre>";print_r($mappedStore);die;
            $all_products = $this->getAProductsAllStore($languages, $default_language_id, $offset, $rows, $min_id, $max_id, $row['product_id']);

            //echo "<pre>";print_r($all_products);die;

            foreach ($all_products as $pro) {
                if (isset($mappedStore[$pro['store_id']])) {
                    $j = $mappedStore[$pro['store_id']] - 1;

                    //echo "<pre>";print_r($j);die;
                    $data[$j++] = $pro['quantity'];
                }
            }

            $this->setCellRowNewForExport($worksheet, $i, $data);

            ++$i;
            $j = 0;
        }
        $this->setColumnStyles($worksheet, $styles, 2, $i - 1);
    }

    protected function getAProductsAllStore(&$languages, $default_language_id, $offset = null, $rows = null, $min_id = null, $max_id = null, $product_id)
    {
        $sql = 'SELECT ';
        $sql .= '  ps.product_store_id,';
        $sql .= '  ps.product_sr_id,';
        $sql .= '  ps.product_id,';
        $sql .= '  p.unit,';
        $sql .= '  p.model,';
        $sql .= '  ps.store_id,';
        //$sql .= "  pd.name,";

        $sql .= '  ps.price,';
        $sql .= '  ps.special_price,';
        $sql .= '  ps.tax_percentage,';
        $sql .= '  ps.tax_class_id,';
        $sql .= '  ps.quantity,';
        $sql .= '  ps.min_quantity,';
        $sql .= '  ps.subtract_quantity,';
        $sql .= '  ps.status ';
        $sql .= 'FROM `'.DB_PREFIX.'product_to_store` ps ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'product_description` pd ON ps.product_id = pd.product_id ';

        $sql .= 'LEFT JOIN `'.DB_PREFIX.'store` st ON ps.store_id = st.store_id ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'product` p ON p.product_id = pd.product_id ';
        $sql .= "where st.vendor_id ='".$this->user->getId()."' ";
        $sql .= "and ps.product_id ='".$product_id."' ";
        /*if ( isset( $min_id ) && isset( $max_id ) ) {
            $sql .= " AND ps.product_store_id BETWEEN $min_id AND $max_id ";
        }*/

        $sql .= 'GROUP BY ps.product_store_id ';
        $sql .= 'ORDER BY ps.product_store_id ';
        if (isset($offset) && isset($rows)) {
            $sql .= "LIMIT $offset,$rows; ";
        } else {
            $sql .= '; ';
        }
        //echo $sql;die;
        $results = $this->db->query($sql);

        //echo "<pre>";print_r($results);die;
        $product_descriptions = $this->getProductDescriptions($languages, $offset, $rows, $min_id, $max_id);

        //echo "<pre>";print_r($product_descriptions);die;

        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                $pd_key = $this->getKeyFromProductDescription($product_descriptions[$language_code], $results->rows[$key]['product_id']);

                $log = new Log('error.log');
                //$log->write($key);
                //$log->write($results->rows[$key]['product_id']);

                //echo "<pre>";print_r($key);die;//254

                if (isset($product_descriptions[$language_code][$pd_key])) {
                    $results->rows[$key]['name'][$language_code] = $product_descriptions[$language_code][$pd_key]['name'];
                    $results->rows[$key]['description'][$language_code] = $product_descriptions[$language_code][$pd_key]['description'];
                    if (isset($exist_meta_title)) {
                        $results->rows[$key]['meta_title'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_title'];
                    }
                    $results->rows[$key]['meta_description'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_description'];
                    $results->rows[$key]['meta_keyword'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_keyword'];
                    $results->rows[$key]['tag'][$language_code] = $product_descriptions[$language_code][$pd_key]['tag'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                    $results->rows[$key]['description'][$language_code] = '';
                    if (isset($exist_meta_title)) {
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

    public function populateSampleProductsWorksheet(&$worksheet, &$languages, $default_language_id, &$price_format, &$box_format, &$weight_format, &$text_format, $offset = null, $rows = null, &$min_id = null, &$max_id = null)
    {
        $j = 0;

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Barcode'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Item name'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Department'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Sell Price'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('PriceDropSP'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('StartDate'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('EndDate'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Purchase Block'), 10) + 1);

        $stores = $this->getStores();

        $mappedStore = [];

        foreach ($stores as $store) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen($this->getMappedStore($store['store_id'])), 10) + 1);

            $mappedStore[$store['store_id']] = $j;
        }

        //echo "<pre>";print_r($mappedStore);die;

        // The product headings row and column styles

        $styles = [];
        $data = [];
        $i = 1;
        $j = 0;

        $data[$j++] = 'Barcode';
        $data[$j++] = 'Item name';
        $data[$j++] = 'Department';
        $data[$j++] = 'Sell Price';

        $data[$j++] = 'PriceDropSP';
        $data[$j++] = 'StartDate';
        $data[$j++] = 'EndDate';
        $data[$j++] = 'Purchase Block';

        foreach ($stores as $store) {
            $data[$j++] = $this->getMappedStore($store['store_id']);
        }

        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);
        // The actual products data
        ++$i;
        $j = 0;
        $products = $this->getDistinctProducts($languages, $default_language_id, $offset, $rows, $min_id, $max_id);

        //echo "<pre>";print_r($products);die;
        $len = count($products);
        $min_id = $products[0]['product_store_id'];
        $max_id = $products[$len - 1]['product_store_id'];
        foreach ($products as $row) {
            $data = [];
            $worksheet->getRowDimension($i)->setRowHeight(26);

            $tmp_name = '';

            foreach ($row['name'] as $key => $value) {
                $tmp_name = $value;
                break;
            }

            $data[$j++] = $row['model'];
            $data[$j++] = ''; //$tmp_name ." - " .$row['unit'];
            $data[$j++] = '';
            $data[$j++] = $row['price'];
            $data[$j++] = $row['special_price'];

            $data[$j++] = '';
            $data[$j++] = '';
            $data[$j++] = '';

            //echo "<pre>";print_r($mappedStore);die;
            $all_products = $this->getAProductsAllStore($languages, $default_language_id, $offset, $rows, $min_id, $max_id, $row['product_id']);

            //echo "<pre>";print_r($all_products);die;

            foreach ($all_products as $pro) {
                if (isset($mappedStore[$pro['store_id']])) {
                    $j = $mappedStore[$pro['store_id']] - 1;

                    //echo "<pre>";print_r($j);die;
                    $data[$j++] = $pro['quantity'];
                }
            }

            $this->setCellRowNewForExport($worksheet, $i, $data);

            ++$i;
            $j = 0;

            if (4 == $i) {
                break;
            }
        }
        $this->setColumnStyles($worksheet, $styles, 2, $i - 1);
    }

    public function populateModelsNotPresentWorksheet(&$worksheet, &$languages, $default_language_id, &$price_format, &$box_format, &$weight_format, &$text_format, $offset = null, $rows = null, &$min_id = null, &$max_id = null, $model_not_found = [])
    {
        $j = 1;
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Barcode'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Item name'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Department'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Sell Price'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('PriceDropSP'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('StartDate'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('EndDate'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Purchase Block'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Quantity'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('Store ID'), 10) + 1);
        // The product headings row and column styles

        //$worksheet->setCellValueByColumnAndRow(PHPExcel_Cell_DataType::TYPE_STRING.);

        $styles = [];
        $data = [];
        $i = 1;
        $j = 1;
        $data[$j++] = 'Barcode';
        $data[$j++] = 'Item name';
        $data[$j++] = 'Department';
        $data[$j++] = 'Sell Price';
        $data[$j++] = 'PriceDropSP';
        $data[$j++] = 'StartDate';
        $data[$j++] = 'EndDate';
        $data[$j++] = 'Purchase Block';
        $data[$j++] = 'Quantity';
        $data[$j++] = 'Store ID';

        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);
        // The actual products data
        ++$i;
        $j = 1;
        $products = $model_not_found;
        $len = count($products);

        foreach ($products as $key => $value) {
            $data = [];
            $worksheet->getRowDimension($i)->setRowHeight(26);

            $data[$j++] = $value['model'];
            $data[$j++] = $value['item_name'];
            $data[$j++] = $value['department'];
            $data[$j++] = $value['price'];
            $data[$j++] = $value['special_price'];
            $data[$j++] = $value['start_date'];
            $data[$j++] = $value['end_date'];
            $data[$j++] = $value['purchase_block'];
            $data[$j++] = $value['quantity'];
            $data[$j++] = $value['store_id'];

            $this->setCellRowNew($worksheet, $i, $data, $box_format);

            ++$i;
            $j = 1;
        }
        //$this->setColumnStyles( $worksheet, $styles, 2, $i - 1 );
    }

    protected function getDistinctProducts(&$languages, $default_language_id, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        $sql = 'SELECT ';
        $sql .= '  ps.product_store_id,';
        $sql .= '  ps.product_sr_id,';
        $sql .= '  ps.product_id,';
        $sql .= '  p.unit,';
        $sql .= '  p.model,';
        $sql .= '  ps.store_id,';
        //$sql .= "  pd.name,";

        $sql .= '  ps.price,';
        $sql .= '  ps.special_price,';
        $sql .= '  ps.tax_percentage,';
        $sql .= '  ps.tax_class_id,';
        $sql .= '  ps.quantity,';
        $sql .= '  ps.min_quantity,';
        $sql .= '  ps.subtract_quantity,';
        $sql .= '  ps.status ';
        $sql .= 'FROM `'.DB_PREFIX.'product_to_store` ps ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'product_description` pd ON ps.product_id = pd.product_id ';

        $sql .= 'LEFT JOIN `'.DB_PREFIX.'store` st ON ps.store_id = st.store_id ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'product` p ON p.product_id = pd.product_id ';
        $sql .= "where st.vendor_id ='".$this->user->getId()."' ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= " AND ps.product_store_id BETWEEN $min_id AND $max_id ";
        }

        $sql .= 'GROUP BY ps.product_id ';
        $sql .= 'ORDER BY ps.product_store_id ';
        if (isset($offset) && isset($rows)) {
            $sql .= "LIMIT $offset,$rows; ";
        } else {
            $sql .= '; ';
        }
        //echo $sql;die;
        $results = $this->db->query($sql);

        //echo "<pre>";print_r($results);die;
        $product_descriptions = $this->getProductDescriptions($languages, $offset, $rows, $min_id, $max_id);

        //echo "<pre>";print_r($product_descriptions);die;

        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                $pd_key = $this->getKeyFromProductDescription($product_descriptions[$language_code], $results->rows[$key]['product_id']);

                $log = new Log('error.log');
                //$log->write($key);
                //$log->write($results->rows[$key]['product_id']);

                //echo "<pre>";print_r($key);die;//254

                if (isset($product_descriptions[$language_code][$pd_key])) {
                    $results->rows[$key]['name'][$language_code] = $product_descriptions[$language_code][$pd_key]['name'];
                    $results->rows[$key]['description'][$language_code] = $product_descriptions[$language_code][$pd_key]['description'];
                    if (isset($exist_meta_title)) {
                        $results->rows[$key]['meta_title'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_title'];
                    }
                    $results->rows[$key]['meta_description'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_description'];
                    $results->rows[$key]['meta_keyword'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_keyword'];
                    $results->rows[$key]['tag'][$language_code] = $product_descriptions[$language_code][$pd_key]['tag'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                    $results->rows[$key]['description'][$language_code] = '';
                    if (isset($exist_meta_title)) {
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

    protected function getProducts(&$languages, $default_language_id, $offset = null, $rows = null, $min_id = null, $max_id = null)
    {
        $sql = 'SELECT ';
        $sql .= '  ps.product_store_id,';
        $sql .= '  ps.product_sr_id,';
        $sql .= '  ps.product_id,';
        $sql .= '  p.unit,';
        $sql .= '  p.model,';
        $sql .= '  ps.store_id,';
        //$sql .= "  pd.name,";

        $sql .= '  ps.price,';
        $sql .= '  ps.special_price,';
        $sql .= '  ps.tax_percentage,';
        $sql .= '  ps.tax_class_id,';
        $sql .= '  ps.quantity,';
        $sql .= '  ps.min_quantity,';
        $sql .= '  ps.subtract_quantity,';
        $sql .= '  ps.status ';
        $sql .= 'FROM `'.DB_PREFIX.'product_to_store` ps ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'product_description` pd ON ps.product_id = pd.product_id ';

        $sql .= 'LEFT JOIN `'.DB_PREFIX.'store` st ON ps.store_id = st.store_id ';
        $sql .= 'LEFT JOIN `'.DB_PREFIX.'product` p ON p.product_id = pd.product_id ';
        $sql .= "where st.vendor_id ='".$this->user->getId()."' ";
        if (isset($min_id) && isset($max_id)) {
            $sql .= " AND ps.product_store_id BETWEEN $min_id AND $max_id ";
        }

        $sql .= 'GROUP BY ps.product_store_id ';
        $sql .= 'ORDER BY ps.product_store_id ';
        if (isset($offset) && isset($rows)) {
            $sql .= "LIMIT $offset,$rows; ";
        } else {
            $sql .= '; ';
        }
        //echo $sql;die;
        $results = $this->db->query($sql);

        //echo "<pre>";print_r($results);die;
        $product_descriptions = $this->getProductDescriptions($languages, $offset, $rows, $min_id, $max_id);

        //echo "<pre>";print_r($product_descriptions);die;

        foreach ($languages as $language) {
            $language_code = $language['code'];
            foreach ($results->rows as $key => $row) {
                $pd_key = $this->getKeyFromProductDescription($product_descriptions[$language_code], $results->rows[$key]['product_id']);

                $log = new Log('error.log');
                //$log->write($key);
                //$log->write($results->rows[$key]['product_id']);

                //echo "<pre>";print_r($key);die;//254

                if (isset($product_descriptions[$language_code][$pd_key])) {
                    $results->rows[$key]['name'][$language_code] = $product_descriptions[$language_code][$pd_key]['name'];
                    $results->rows[$key]['description'][$language_code] = $product_descriptions[$language_code][$pd_key]['description'];
                    if (isset($exist_meta_title)) {
                        $results->rows[$key]['meta_title'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_title'];
                    }
                    $results->rows[$key]['meta_description'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_description'];
                    $results->rows[$key]['meta_keyword'][$language_code] = $product_descriptions[$language_code][$pd_key]['meta_keyword'];
                    $results->rows[$key]['tag'][$language_code] = $product_descriptions[$language_code][$pd_key]['tag'];
                } else {
                    $results->rows[$key]['name'][$language_code] = '';
                    $results->rows[$key]['description'][$language_code] = '';
                    if (isset($exist_meta_title)) {
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

    /*protected function getProductDescriptions( &$languages, $offset = null, $rows = null, $min_id = null, $max_id = null ) {

        // some older versions of OpenCart use the 'product_tag' table
        $exist_table_product_tag = false;
        $query = $this->db->query( "SHOW TABLES LIKE '" . DB_PREFIX . "product_tag'" );
        $exist_table_product_tag = ( $query->num_rows > 0 );

        // query the product_description table for each language
        $product_descriptions = array();
        foreach ( $languages as $language ) {
            $language_id = $language['language_id'];
            $language_code = $language['code'];
            $sql = "SELECT p.product_id, " . ( ( $exist_table_product_tag ) ? "GROUP_CONCAT(pt.tag SEPARATOR \",\") AS tag, " : "" ) . "pd.* ";
            $sql .= "FROM `" . DB_PREFIX . "product` p ";
            $sql .= "LEFT JOIN `" . DB_PREFIX . "product_description` pd ON pd.product_id=p.product_id AND pd.language_id='" . (int) $language_id . "' ";
            if ( $exist_table_product_tag ) {
                $sql .= "LEFT JOIN `" . DB_PREFIX . "product_tag` pt ON pt.product_id=p.product_id AND pt.language_id='" . (int) $language_id . "' ";
            }
            if ( isset( $min_id ) && isset( $max_id ) ) {
                $sql .= "WHERE p.product_id BETWEEN $min_id AND $max_id ";
            }



            $sql .= "GROUP BY p.product_id ";
            $sql .= "ORDER BY p.product_id ";
            if ( isset( $offset ) && isset( $rows ) ) {
                $sql .= "LIMIT $offset,$rows; ";
            } else {
                $sql .= "; ";
            }
            $query = $this->db->query( $sql );
            $product_descriptions[$language_code] = $query->rows;
        }
        return $product_descriptions;
    }*/

    protected function getKeyFromProductDescription($product_descriptions, $o_key)
    {
        foreach ($product_descriptions as $key => $value) {
            if ($value['product_id'] == $o_key) {
                return $key;
            }
        }

        return 0;
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

            //$sql .= "LEFT JOIN `" . DB_PREFIX . "product_to_store` st ON ps.product_id = pd.product_id ";

            $sql .= 'LEFT JOIN `'.DB_PREFIX."product_description` pd ON pd.product_id=p.product_id AND pd.language_id='".(int) $language_id."' ";
            if ($exist_table_product_tag) {
                $sql .= 'LEFT JOIN `'.DB_PREFIX."product_tag` pt ON pt.product_id=p.product_id AND pt.language_id='".(int) $language_id."' ";
            }

            //$sql .= "where st.vendor_id ='".$this->user->getId()."' ";

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

    public function populateVendorProductVariationsWorksheet(&$worksheet, &$languages, $default_language_id, &$box_format, &$text_format, $min_id = null, $max_id = null)
    {
        $j = 0;

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_variation_store_id') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_store_id') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('product_store_sr_id') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('variation_id') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('variation_name') + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('model') + 25);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('price') + 25);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(strlen('special_price') + 25);

        // The heading row and column styles
        $styles = [];
        $data = [];
        $i = 1;
        $j = 0;

        $data[$j++] = 'id';
        $data[$j++] = 'product_store_sr_id';
        $data[$j++] = 'variation_id';
        $data[$j++] = 'variation_name';
        $data[$j++] = 'model';
        $data[$j++] = 'price';
        $data[$j++] = 'special_price';
        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        ++$i;
        $j = 0;
        $product_attributes = $this->getProductVariations($languages, $min_id, $max_id);
        foreach ($product_attributes as $row) {
            $worksheet->getRowDimension($i)->setRowHeight(13);
            $data = [];
            //$data[$j++] = $row['id'];
            $data[$j++] = $row['product_variation_store_id'];
            $data[$j++] = $row['product_store_id'];
            $data[$j++] = $row['variation_id'];
            $data[$j++] = $row['name'];
            $data[$j++] = $row['model'];
            $data[$j++] = $row['price'];
            $data[$j++] = $row['special_price'];
            $this->setCellRow($worksheet, $i, $data);
            ++$i;
            $j = 0;
        }
        $this->setColumnStyles($worksheet, $styles, 2, $i - 1);
    }

    protected function getProductVariations($languages, $min_id, $max_id)
    {
        $this->db->select('product_variation_store_id,product_to_store.product_store_id,variation_id,product_variation.name as name,product_variation.model as  model,variation_to_product_store.price,variation_to_product_store.special_price');
        $this->db->join('product_variation', 'product_variation.id = variation_to_product_store.variation_id', 'left');

        $this->db->join('product_to_store', 'product_to_store.product_store_id = variation_to_product_store.product_store_id', 'left');
        $this->db->join('store', 'store.store_id = product_to_store.store_id', 'left');
        $this->db->where('vendor_id', $this->user->getId());
        $query = $this->db->get('variation_to_product_store')->rows;
        //echo $this->db->last_query();die;
        return $query;
    }

    /*
        uploading Starts here

    */

    protected function saveModelNotPresent($products)
    {
        //echo "<pre>";print_r($products);die;

        $this->db->query('TRUNCATE '.DB_PREFIX.'model_not_present');

        foreach ($products as $product) {
            /*$data['store_id'] = $product['store_id'];
            $data['price'] = $product['price'];
            $data['special_price'] = $product['special_price'];
            $data['quantity'] = $product['quantity'];
            $data['status'] = "'".$product['status']."'";
            $data['subtract_quantity'] = $product['subtract_quantity'];
            $data['model'] = $product['model'];
            $this->db->insert('model_not_present',$data);*/

            $query1 = $this->db->query('select * from '.DB_PREFIX."model_not_present where model = '".$product['model']."'");

            if (!$query1->num_rows) {
                $this->db->query('INSERT INTO '.DB_PREFIX."model_not_present SET store_id = '".$product['store_id']."', item_name = '".$this->db->escape($product['item_name'])."', department = '".$product['department']."', purchase_block = '".$product['purchase_block']."', end_date = '".$product['end_date']."', start_date = '".$product['start_date']."', price = '".$product['price']."', special_price = '".$product['special_price']."', quantity = '".ceil($product['quantity'])."', status = '".$product['status']."', subtract_quantity = '".$product['subtract_quantity']."', model = '".$product['model']."'");
            }
        }
    }

    public function upload($filename, $incremental = false)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        // if ( $this->user->isVendor() ) {
        //     $user_id = $this->user->getId();
        // }else {
        //     $user_id = 0;
        // }

        global $registry;
        $registry = $this->registry;
        set_error_handler('error_handler_for_export_import', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export_import');

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

            //echo "<pre>";print_r("er");die;

            $this->clearCache();
            $this->session->data['export_import_nochange'] = 0;
            $available_product_ids = [];

            $resp = $this->uploadProducts($reader, $incremental, $available_product_ids);

            //echo "<pre>";print_r($resp);die;
            if (count($resp) > 0) {
                //$this->downloadModelsNotPresent('p', null, null, null, null,$resp);
                $this->saveModelNotPresent($resp);

                //echo "<pre>";print_r("er");die;
                return $resp;
            }
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

    protected function clearCache()
    {
        $this->cache->delete('*');
    }

    protected function validateUpload(&$reader)
    {
        $ok = true;

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
            if ('Report' == $name) {
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

        /*if ( !$this->validateProductIdColumns( $reader ) ) {
            return false;
        }*/

        return $ok;
    }

    protected function validateProductIdColumns(&$reader)
    {
        $data = $reader->getSheetByName('Report');
        if (null == $data) {
            return true;
        }
        $ok = true;

        $has_missing_product_ids = false;
        $product_ids = [];
        $k = $data->getHighestRow();
        for ($i = 1; $i < $k; ++$i) {
            $product_store_id = trim($this->getCell($data, $i, 1));
            $product_store_sr_no = trim($this->getCell($data, $i, 2));

            if ('' == $product_store_id && '' == $product_store_sr_no) {
                if (!$has_missing_product_ids) {
                    $msg = str_replace('%1', 'Products', $this->language->get('error_missing_product_id'));
                    $this->log->write($msg);
                    $has_missing_product_ids = true;
                }
                $ok = false;
                continue;
            }
            $product_ids[] = $product_store_id;
        }

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
                $product_store_id = trim($this->getCell($data, $i, 2));
                $product_store_sr_no = trim($this->getCell($data, $i, 3));

                if ('' == $product_store_id && '' == $product_store_sr_no) {
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

    protected function validateProducts(&$reader)
    {
        $data = $reader->getSheetByName('Report');
        if (null == $data) {
            return true;
        }

        //$expected_heading = array( "barcode","item code", "item name","department","purchase block","sell price", "quantity");
        $expected_heading = ['barcode', 'item name', 'department', 'sell price', 'pricedropsp', 'startdate', 'enddate', 'purchase block'];
        $expected_multilingual = []; //array( "name");

        //echo "<pre>";print_r($data);die;
        /*		Array
        (
            [0] => barcode
            [1] => item code
            [2] => item name
            [3] => department
            [4] => purchase block
            [5] => sell price
            [6] => abc branch
        )*/
        return $this->validateHeading($data, $expected_heading, $expected_multilingual);
    }

    protected function validateProductVariations(&$reader)
    {
        $data = $reader->getSheetByName('ProductVariations');
        if (null == $data) {
            return true;
        }

        $expected_heading = ['id', 'product_store_sr_id', 'variation_id', 'variation_name', 'model', 'price', 'special_price'];

        return $this->validateHeading($data, $expected_heading);
    }

    protected function getAvailableProductIds(&$data)
    {
        $available_product_ids = [];

        $k = $data->getHighestRow();
        for ($i = 1; $i < $k; ++$i) {
            $j = 1;

            $product_store_id = trim($this->getCell($data, $i, $j++));
            if ('' == $product_store_id) {
                continue;
            }
            $available_product_ids[$product_store_id] = $product_store_id;
        }

        return $available_product_ids;
    }

    protected function getMappingStoreId($name)
    {
        $se = $this->db->query('SELECT store_id  FROM '.DB_PREFIX."excel_store_mapping WHERE text = '".$name."' and vendor_id=".$this->user->getId());
        if ($se->num_rows) {
            return $se->row['store_id'];
        }

        return 0;
    }

    protected function getMappedStore($store_id)
    {
        $se = $this->db->query('SELECT text  FROM '.DB_PREFIX."excel_store_mapping WHERE store_id = '".$this->db->escape($store_id)."' and vendor_id=".$this->user->getId());
        if ($se->num_rows) {
            return $se->row['text'];
        }

        return '';
    }

    public function uploadProducts(&$reader, $incremental, &$available_product_ids = [])
    {
        // get worksheet, if not there return immediately
        $data = $reader->getSheetByName('Report');

        //echo "<pre>";print_r($data);die;

        if (null == $data) {
            return;
        }

        //echo "<pre>";print_r("er");die;
        $log = new Log('error.log');
        $log->write("storeProductIntoDatabase product -Excel upload");
        // $log->write($data);
        
        $available_product_ids = [];

        if ($incremental) {
            $available_product_ids = $this->getAvailableProductIds($data);
        } else {
            $query = $this->db->query('select product_store_id from '.DB_PREFIX.'product_to_store as ps left join '.DB_PREFIX.'store as s on s.store_id = ps.store_id where  vendor_id = '.$this->user->getId().' ');

            $product_store_id = '';

            foreach ($query->rows as $temp) {
                $product_store_id .= $temp['product_store_id'].',';
            }

            if ($product_store_id) {
                $ids = rtrim($product_store_id, ',');
                //print_r($ids);die;
                $log->write($ids);

                
                $this->deleteProducts($ids);
                //$this->deleteVariations($ids);
            }
        }

        //die;

        // find the installed languages
        $languages = $this->getLanguages();

        // load the worksheet cells and store them to the database
        $first_row = [];
        $i = 0;
        $k = $data->getHighestRow();
        $all_product_ids = [];

        $model_not_found = [];

        $default_store_id = '';

        $this->load->model('sale/order');
        $store_rows = $this->model_sale_order->getStoreIdByVendorId($this->user->getId());

        foreach ($store_rows as $st_row) {
            $default_store_id = $st_row['store_id'];
            break;
        }

        //echo "<pre>";print_r($default_store_id);die;
        $store_ids = [];

        for ($i = 0; $i < $k; ++$i) {
            if (0 == $i) {
                $max_col = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
                for ($j = 1; $j <= $max_col; ++$j) {
                    $fr = $this->getCell($data, $i, $j);
                    $first_row[] = $fr;
                    if (!empty($fr) && $j > 8) {
                        $tmp = [];
                        //echo "<pre>";print_r($fr);die;
                        $get_store_id = $this->getMappingStoreId($fr);

                        //echo "<pre>";print_r($get_store_id);die;
                        if ($get_store_id) {
                            $tmp['store_id'] = $get_store_id;
                            $tmp['position'] = $j;

                            array_push($store_ids, $tmp);
                        }
                    }
                }

                continue;
            }

            //echo "<pre>ferf";print_r($store_ids);

            $j = 1;
            $model = trim($this->getCell($data, $i, $j++));

            $item_code = ''; //trim( $this->getCell( $data, $i, $j++ ) );
            $item_name = trim($this->getCell($data, $i, $j++));
            $department = trim($this->getCell($data, $i, $j++));

            $price = trim($this->getCell($data, $i, $j++));
            $b = str_replace(',', '', $price);

            if (is_numeric($b)) {
                $price = $b;
            }

            $special_price = trim($this->getCell($data, $i, $j++));
            $b = str_replace(',', '', $special_price);

            if (is_numeric($b)) {
                $special_price = $b;
            }

            //$special_price = trim( $this->getCell( $data, $i, $j++ ) );
            $start_date = trim($this->getCell($data, $i, $j++));
            $end_date = trim($this->getCell($data, $i, $j++));

            $purchase_block = trim($this->getCell($data, $i, $j++));

            $status = 'YES';
            $subtract_quantity = 1;

            $product = [];

            $query1 = $this->db->query('select * from '.DB_PREFIX."product where model = '".$model."'");

            $product['price'] = $price;
            $product['special_price'] = $special_price;
            //$product['quantity'] = $quantity;
            $product['subtract_quantity'] = $subtract_quantity;
            $product['status'] = $status;
            $product['model'] = $model;

            $product['start_date'] = $start_date;
            $product['end_date'] = $end_date;

            $product['item_code'] = $item_code;
            $product['item_name'] = $item_name;
            $product['department'] = $department;
            $product['purchase_block'] = $purchase_block;

            $product_id = 0;

            if ($incremental && false) {
                $this->deleteProduct($product_store_id);
            }

            //echo "<pre>";print_r($product);die;
            $this->moreProductCells($i, $j, $data, $product);

            //echo "<pre>";print_r($store_ids);

            /*if($query1->num_rows) {
                $product_id = $query1->row['product_id'];
            } else {

                $product['quantity'] = 0;
                $product['store_id'] = 0;

                array_push($model_not_found, $product);

                continue;
            }*/

            foreach ($store_ids as $val) {
                $store_id = $val['store_id'];
                $quantity = trim($this->getCell($data, $i, $val['position']));

                $b = str_replace(',', '', $quantity);

                if (is_numeric($b)) {
                    $quantity = $b;
                }

                $product['quantity'] = $quantity;
                $product['store_id'] = $store_id;

                if ($query1->num_rows) {
                    $product_id = $query1->row['product_id'];
                } else {
                    array_push($model_not_found, $product);

                    continue;
                }

                $product['product_id'] = $product_id;

                $tempData['product_id'] = $product_id;
                $tempData['store_id'] = $store_id;

                array_push($all_product_ids, $tempData);

                $this->storeProductIntoDatabase($product, $incremental);
            }

            //$this->storeProductIntoDatabase( $product, $languages, $product_fields, $exist_table_product_tag, $exist_meta_title, $layout_ids, $available_store_ids, $manufacturers, $weight_class_ids, $length_class_ids, $url_alias_ids,$incremental );
        }

        //echo "<pre>";print_r($model_not_found);die;
        if (count($all_product_ids) > 0) {
            $this->updateProductToStoreTable($all_product_ids);
        }

        return $model_not_found;
    }

    protected function moreProductCells($i, &$j, &$worksheet, &$product)
    {
        return;
    }

    protected function deleteProduct($product_store_id, $exist_table_product_tag)
    {
        $sql = 'DELETE FROM `'.DB_PREFIX."product_to_store` WHERE `product_store_id` = '$product_store_id';\n";
        $this->db->query($sql);
        $sql = 'DELETE FROM `'.DB_PREFIX."variation_to_product_store` WHERE `product_store_id` = '$product_store_id';\n";
        $this->db->query($sql);
        // $this->multiquery( $sql );
    }

    protected function storeProductIntoDatabaseOld(&$product, $incremental)
    {
        // extract the product details
        $log = new Log('error.log');
        $log->write('storeProductIntoDatabase product');
        $log->write($product);

        $data['product_sr_id'] = $product['product_sr_id'];
        $data['product_id'] = $product['product_id'];
        $data['store_id'] = $product['store_id'];
        $status = $product['status'];
        $exists = $this->db->query('select * from '.DB_PREFIX.'product_to_store where product_id="'.$data['product_id'].'" and store_id="'.$data['store_id'].'"');

        if ($exists->num_rows) {
            $exists = true;
        } else {
            $exists = false;
        }

        if ($exists && $incremental) {
            //echo "<pre>";print_r("in if nicremen");die;
            $subtract_quantity = strlen($product['subtract_quantity']) ? $product['subtract_quantity'] : 'false';
            $data['status'] = (('TRUE' == strtoupper($status)) || ('YES' == strtoupper($status)) || ('ENABLED' == strtoupper($status))) ? 1 : 0;
            $data['subtract_quantity'] = (('TRUE' == strtoupper($subtract_quantity)) || ('YES' == strtoupper($subtract_quantity)) || ('ENABLED' == strtoupper($subtract_quantity))) ? 1 : 0;

            $sql = 'UPDATE `'.DB_PREFIX.'product_to_store` SET '
                //. "product_id='" . $product_id . "', "
                ."product_sr_id='".$product['product_sr_id']."', "
                ."price='".$product['price']."', "
                ."special_price='".$product['special_price']."', "
                ."tax_class_id='".$product['tax_class_id']."', "
                ."quantity='".$product['quantity']."', "

                ."min_quantity='".$product['min_quantity']."', "
                ."subtract_quantity='".$product['subtract_quantity']."',"
                ."status='".$data['status']."' "
                .' where product_id='.$product['product_id']
                .' and store_id='.$product['store_id'];

            $this->db->query($sql);

            return true;
        } else {
            //$data['product_store_id'] = $product['product_store_id'];
            $data['product_sr_id'] = $product['product_sr_id'];
            $data['product_id'] = $product['product_id'];
            $data['store_id'] = $product['store_id'];
            $data['price'] = strlen($product['price']) ? $product['price'] : 0;
            $data['special_price'] = strlen($product['special_price']) ? $product['special_price'] : 0;
            $data['tax_class_id'] = strlen($product['tax_class_id']) ? $product['tax_class_id'] : 0;
            $data['quantity'] = strlen($product['quantity']) ? $product['quantity'] : 0;
            $data['min_quantity'] = strlen($product['min_quantity']) ? $product['min_quantity'] : 1;
            $subtract_quantity = strlen($product['subtract_quantity']) ? $product['subtract_quantity'] : 'false';
            $status = $product['status'];

            $data['status'] = (('TRUE' == strtoupper($status)) || ('YES' == strtoupper($status)) || ('ENABLED' == strtoupper($status))) ? 1 : 0;
            $data['subtract_quantity'] = (('TRUE' == strtoupper($subtract_quantity)) || ('YES' == strtoupper($subtract_quantity)) || ('ENABLED' == strtoupper($subtract_quantity))) ? 1 : 0;
            $this->db->insert('product_to_store', $data);

            return	$this->db->getLastId();
        }
    }

    protected function storeProductIntoDatabase(&$product, $incremental)
    {
        // extract the product details
        $log = new Log('error.log');
        $log->write('storeProductIntoDatabase product');
        $log->write($product);

        //echo "<pre>";print_r($products);die;
        //$data['product_sr_id'] = $product['product_sr_id'];
        $data['product_id'] = $product['product_id'];
        $data['store_id'] = $product['store_id'];
        $status = $product['status'];
        $exists = $this->db->query('select * from '.DB_PREFIX.'product_to_store where product_id="'.$data['product_id'].'" and store_id="'.$data['store_id'].'"');

        if ($exists->num_rows) {
            $exists = true;
        } else {
            $exists = false;
        }

        if (empty($product['quantity']) || $product['quantity'] <= 0) {
            $product['quantity'] = 0;
            //return true;
        }

        if ($exists && $incremental) {
            //echo "<pre>";print_r("in if nicremen");die;
            $subtract_quantity = strlen($product['subtract_quantity']) ? $product['subtract_quantity'] : 'false';
            $data['status'] = (('TRUE' == strtoupper($status)) || ('YES' == strtoupper($status)) || ('ENABLED' == strtoupper($status)) || empty($status) || $status) ? 1 : 0;
            $data['subtract_quantity'] = (('TRUE' == strtoupper($subtract_quantity)) || ('YES' == strtoupper($subtract_quantity)) || ('ENABLED' == strtoupper($subtract_quantity))) ? 1 : 0;

            $sql = 'UPDATE `'.DB_PREFIX.'product_to_store` SET '
                //. "product_id='" . $product_id . "', "
                //. "product_sr_id='" . $product['product_sr_id'] . "', "
                ."price='".$product['price']."', "
                ."special_price='".$product['special_price']."', "

                ."quantity='".$product['quantity']."', "

                //. "min_quantity='" . $product['min_quantity'] . "', "
                ."subtract_quantity='".$product['subtract_quantity']."' "
                //. "status='" . $data['status'] . "' "
                .' where product_id='.$product['product_id']
                .' and store_id='.$product['store_id'];

            $this->db->query($sql);

            return true;
        } else {
            //$data['product_store_id'] = $product['product_store_id'];
            //$data['product_sr_id'] = $product['product_sr_id'];
            /*$data['product_id'] = $product['product_id'];
            $data['store_id'] = $product['store_id'];
            $data['price'] = strlen($product['price'])?$product['price']:0;
            $data['special_price'] = strlen($product['special_price'])?$product['special_price']:0;
            $data['quantity'] = strlen($product['quantity'])?$product['quantity']:0;

            $subtract_quantity = strlen($product['subtract_quantity'])?$product['subtract_quantity']: "false";
            $data['status'] = ( ( strtoupper( $status ) == "TRUE" ) || ( strtoupper( $status ) == "YES" ) || ( strtoupper( $status ) == "ENABLED" ) || empty($status) ) ? 1 : 0;
            $data['subtract_quantity'] = ( ( strtoupper( $subtract_quantity ) == "TRUE" ) || ( strtoupper( $subtract_quantity ) == "YES" ) || ( strtoupper( $subtract_quantity ) == "ENABLED" ) ) ? 1 : 0;
            $this->db->insert('product_to_store',$data);
               return	$this->db->getLastId();*/
        }
    }

    protected function uploadProductVariations(&$reader, $incremental, &$available_category_ids = [])
    {
        // get worksheet if there
        $data = $reader->getSheetByName('ProductVariations');
        if (null == $data) {
            return;
        }

        // if incremental then find current product IDs else delete all old categories
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

            $product_store_sr_id = $this->getCell($data, $i, $j++, '0');

            if ($product_store_sr_id) {
                $product_query = $this->db->query('select product_store_id from '.DB_PREFIX.'product_to_store where product_sr_id="'.$product_store_sr_id.'"');

                if ($product_query->num_rows) {
                    $product_store_sr_id = $product_query->row['product_store_id'];
                } else {
                    continue;
                }
            }

            $variation_id = $this->getCell($data, $i, $j++, '0');
            $variation_name = $this->getCell($data, $i, $j++, '0');
            $model = $this->getCell($data, $i, $j++, '0');
            $price = trim($this->getCell($data, $i, $j++));
            $special_price = $this->getCell($data, $i, $j++, '0');

            $variation = [];
            $variation['product_store_id'] = $product_store_sr_id;
            $variation['variation_id'] = $variation_id;
            $variation['price'] = $price;
            $variation['special_price'] = $special_price;

            if ($incremental) {
                if ($available_variation_ids) {
                    if (in_array((int) $variation_id, $available_variation_ids)) {
                        $this->deleteVariation($product_store_id);
                    }
                }
            }
            $this->moreVariationCells($i, $j, $data, $variation);
            $this->storeVariationIntoDatabase($variation);
        }
    }

    protected function deleteVariation($product_store_id)
    {
        $this->db->query('delete from `'.DB_PREFIX.'variation_to_product_store` WHERE product_store_id="'.$product_store_id.'"');
    }

    protected function moreVariationCells($i, &$j, &$worksheet, &$variation)
    {
        return;
    }

    protected function storeVariationIntoDatabase($variation)
    {
        $data = [
             'variation_id' => $variation['variation_id'],
             'product_store_id' => $variation['product_store_id'],
             'price' => $variation['price'],
             'special_price' => $variation['special_price'],
           ];

        $this->db->insert('variation_to_product_store', $data);

        return	$this->db->getLastId();
    }

    protected function getAvailableVariationIds()
    {
        $sql = 'SELECT pv.product_store_id from '.DB_PREFIX.'variation_to_product_store pv';
        $result = $this->db->query($sql);

        $product_store_id = [0];
        foreach ($result->rows as $row) {
            if (!in_array((int) $row['product_store_id'], $product_store_id)) {
                $product_store_id[] = (int) $row['product_store_id'];
            }
        }
        //print_r($product_store_id);die;
        return $product_store_id;
    }

    protected function deleteProducts($product_ids)
    {
        $sql = 'DELETE FROM `'.DB_PREFIX.'product_to_store` WHERE product_store_id IN ('.$product_ids.");\n";
        $this->db->query($sql);
        $sql = 'DELETE FROM `'.DB_PREFIX.'variation_to_product_store` WHERE product_store_id IN ('.$product_ids.");\n";
        $this->db->query($sql);
    }

    protected function updateProductToStoreTable(&$all_product_ids)
    {
        foreach ($all_product_ids as $value) {
            $product_to_store_ids = $this->getProductToStoreVariations($value['product_id'], $value['store_id']);

            if ($product_to_store_ids) {
                $this->db->query('UPDATE '.DB_PREFIX."product_to_store SET product_to_store_ids = '".$this->db->escape($product_to_store_ids)."' WHERE product_id = '".(int) $value['product_id']."' AND store_id = '".(int) $value['store_id']."'");
            }
        }

        return true;
    }

    protected function getProductToStoreVariations($product_id, $store_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product p WHERE p.product_id = '".(int) $product_id."'");

        $product_info = $query->row;

        if ($product_info) {
            $variations_id = explode(',', $product_info['variations_id']);

            $product_to_store_ids = [];

            foreach ($variations_id as $variation) {
                $query = $this->db->query('SELECT * FROM '.DB_PREFIX."product_to_store p WHERE p.product_id = '".(int) $variation."' AND p.store_id = '".(int) $store_id."'");

                $exists = $query->row;

                if ($exists) {
                    array_push($product_to_store_ids, $exists['product_store_id']);
                }
            }

            $product_to_store_ids = implode(',', $product_to_store_ids);

            return $product_to_store_ids;
        }
    }

    // download products

    public function downloadGeneralProductsForSample($export_type, $offset = null, $rows = null, $min_id = null, $max_id = null)
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

            //echo "<pre>";print_r($export_type);print_r($min_id);print_r($max_id);die;
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
                $worksheet->setTitle('Report');
                // echo "<pre>";
                // print_r($worksheet);die;
                //$this->populateProductsWorksheet( $worksheet, $languages, $default_language_id, $price_format, $box_format, $weight_format, $text_format, $offset, $rows, $min_id, $max_id );
                $this->populateSampleProductsWorksheet($worksheet, $languages, $default_language_id, $price_format, $box_format, $weight_format, $text_format, $offset, $rows, $min_id, $max_id);
                $worksheet->freezePaneByColumnAndRow(1, 2);

                // creating the ProductVariations worksheet
                /*$workbook->createSheet();
                $workbook->setActiveSheetIndex( $worksheet_index++ );
                $worksheet = $workbook->getActiveSheet();
                $worksheet->setTitle( 'ProductVariations' );
                $this->populateProductVariationsWorksheet( $worksheet, $languages, $default_language_id, $box_format, $text_format, $min_id, $max_id );
                $worksheet->freezePaneByColumnAndRow( 1, 2 );*/

                /*$workbook->createSheet();
                $workbook->setActiveSheetIndex( $worksheet_index++ );
                $worksheet = $workbook->getActiveSheet();
                $worksheet->setTitle( 'AdditionalImages' );

                $this->populateAdditionalImagesWorksheet($worksheet, $box_format, $text_format, $min_id, $max_id);
                    $worksheet->freezePaneByColumnAndRow(1, 2);
*/
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

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('product_store_id'), 4) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('product_sr_id'), 4) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('product_id'), 4) + 1);
        foreach ($languages as $language) {
            $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('name') + 4, 30) + 1);
        }

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('unit'), 20) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('store_id'), 20) + 1);
        //added
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('price'), 5) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('special_price'), 5) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('tax_class_id'), 10) + 1);

        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('quantity'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('min_quantity'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('subtract_quantity'), 10) + 1);
        $worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('status'), 10) + 1);

        // The product headings row and column styles

        $styles = [];
        $data = [];
        $i = 1;
        $j = 0;
        $data[$j++] = 'product_store_id';
        $data[$j++] = 'product_sr_id';
        $data[$j++] = 'product_id';
        foreach ($languages as $language) {
            $styles[$j] = &$text_format;
            $data[$j++] = 'name('.$language['code'].')';
        }
        //added
        $data[$j++] = 'unit';
        $data[$j++] = 'store_id';
        $data[$j++] = 'price';
        $data[$j++] = 'special_price';
        $data[$j++] = 'tax_class_id';
        $data[$j++] = 'quantity';
        $data[$j++] = 'min_quantity';
        $data[$j++] = 'subtract_quantity';
        $data[$j++] = 'status';

        $worksheet->getRowDimension($i)->setRowHeight(30);
        $this->setCellRow($worksheet, $i, $data, $box_format);

        // The actual products data
        ++$i;
        $j = 0;

        $products = $this->getGeneralProducts($languages, $default_language_id, $product_fields, $exist_meta_title, $offset, $rows, $min_id, $max_id);
        $len = count($products);
        $min_id = $products[0]['product_id'];
        $max_id = $products[$len - 1]['product_id'];
        foreach ($products as $row) {
            $data = [];
            $worksheet->getRowDimension($i)->setRowHeight(26);
            $product_id = $row['product_id'];
            $data[$j++] = '';
            $data[$j++] = '';
            $data[$j++] = $product_id; //product_sr_no
            foreach ($languages as $language) {
                $data[$j++] = html_entity_decode($row['name'][$language['code']], ENT_QUOTES, 'UTF-8');
            }
            //added
            $data[$j++] = $row['unit'];
            $data[$j++] = '';

            $data[$j++] = $row['default_price'];
            $data[$j++] = $row['default_price'];

            $data[$j++] = '';
            $data[$j++] = '';
            $data[$j++] = '';
            $data[$j++] = '';

            $data[$j++] = (0 == $row['status']) ? 'false' : 'true';

            $this->setCellRow($worksheet, $i, $data);
            ++$i;
            $j = 0;
        }
        $this->setColumnStyles($worksheet, $styles, 2, $i - 1);
    }

    protected function getGeneralProducts(&$languages, $default_language_id, $product_fields, $exist_meta_title, $offset = null, $rows = null, $min_id = null, $max_id = null)
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

        if (isset($min_id) && isset($max_id)) {
            $sql .= " AND p.product_id BETWEEN $min_id AND $max_id ";
        }

        $sql .= 'GROUP BY p.product_id ';
        $sql .= 'ORDER BY p.product_id ';
        if (isset($offset) && isset($rows)) {
            $sql .= "LIMIT $offset,$rows; ";
        } else {
            $sql .= '; ';
        }
        $results = $this->db->query($sql);
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

    public function downloadCategoryPricesSample()
    {
        $this->load->model('catalog/vendor_product');
        $this->load->model('report/excel');
        $results = $this->model_catalog_vendor_product->getProducts();
        $this->model_report_excel->download_vendorproduct_category_prices($results);
        //echo '<pre>';print_r($results);exit;
    }
}
