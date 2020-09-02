<?php

class ArasttaModification
{
    public function checkVqmod($modification, $original)
    {
        $files = glob(DIR_VQMOD.'xml/*.xml', GLOB_BRACE);

        if (!empty($files)) {
            if ($files) {
                foreach ($files as $file) {
                    $xmls[] = file_get_contents($file);
                }
            }
        }

        $log = '';

        if (!empty($xmls)) {
            foreach ($xmls as $xml) {
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = false;
                $dom->loadXml($xml);

                $modification_node = $dom->getElementsByTagName('modification')->item(0);
                $file_nodes = $modification_node->getElementsByTagName('file');
                $modification_id = $modification_node->getElementsByTagName('id')->item(0)->nodeValue;

                $log[] = " VQmod Modification::refresh - Processing '".$modification_id."'";
                $log[] = '';

                foreach ($file_nodes as $file_node) {
                    $file_node_path = $file_node->getAttribute('path');
                    $file_node_name = $file_node->getAttribute('name');
                    $files = false;
                    $path = '';

                    if ('catalog' == substr($file_node_path.$file_node_name, 0, 7)) {
                        $path = DIR_CATALOG.substr($file_node_path.$file_node_name, 8);
                    } elseif ('admin' == substr($file_node_path.$file_node_name, 0, 5)) {
                        $path = DIR_APPLICATION.substr($file_node_path.$file_node_name, 6);
                    } elseif ('system' == substr($file_node_path.$file_node_name, 0, 6)) {
                        $path = DIR_SYSTEM.substr($file_node_path.$file_node_name, 7);
                    }
                    if ($path) {
                        $files = glob($path, GLOB_BRACE);
                    }
                    if (false === $files) {
                        $files = [];
                    }

                    $operation_nodes = $file_node->getElementsByTagName('operation');
                    $file_node_error = $file_node->getAttribute('error');

                    foreach ($files as $file) {
                        $key = $this->_vqmodGetFileKey($file);
                        if ('' == $key) {
                            $log[] = 'Modification::refresh - UNABLE TO GENERATE FILE KEY:';
                            $log[] = "  modification id = '$modification_id'";
                            $log[] = "  file name = '$file'";
                            $log[] = '';
                            continue;
                        }
                        if (!isset($modification[$key])) {
                            $modification[$key] = preg_replace('~\r?\n~', "\n", file_get_contents($file));
                            $original[$key] = $modification[$key];
                        }

                        foreach ($operation_nodes as $operation_node) {
                            $operation_node_error = $operation_node->getAttribute('error');
                            if (('skip' != $operation_node_error) && ('log' != $operation_node_error)) {
                                $operation_node_error = 'abort';
                            }

                            $ignoreif_node = $operation_node->getElementsByTagName('ignoreif')->item(0);
                            if ($ignoreif_node) {
                                $ignoreif_node_regex = $ignoreif_node->getAttribute('regex');
                                $ignoreif_node_value = trim($ignoreif_node->nodeValue);
                                if ('true' == $ignoreif_node_regex) {
                                    if (preg_match($ignoreif_node_value, $modification[$key])) {
                                        continue;
                                    }
                                } else {
                                    if (false !== strpos($modification[$key], $ignoreif_node_value)) {
                                        continue;
                                    }
                                }
                            }

                            $search_node = $operation_node->getElementsByTagName('search')->item(0);
                            $search_node_position = ($search_node->getAttribute('position')) ? $search_node->getAttribute('position') : 'replace';
                            $search_node_indexes = $this->_vqmodGetIndexes($search_node->getAttribute('index'));
                            $search_node_offset = ($search_node->getAttribute('offset')) ? $search_node->getAttribute('offset') : '0';
                            $search_node_regex = ($search_node->getAttribute('regex')) ? $search_node->getAttribute('regex') : 'false';
                            $search_node_trim = ('false' == $search_node->getAttribute('trim')) ? 'false' : 'true';
                            $search_node_value = ('true' == $search_node_trim) ? trim($search_node->nodeValue) : $search_node->nodeValue;

                            $add_node = $operation_node->getElementsByTagName('add')->item(0);
                            $add_node_trim = ('true' == $add_node->getAttribute('trim')) ? 'true' : 'false';
                            $add_node_value = ('true' == $add_node_trim) ? trim($add_node->nodeValue) : $add_node->nodeValue;

                            $index_count = 0;
                            $tmp = explode("\n", $modification[$key]);
                            $line_max = count($tmp) - 1;

                            // apply the next search and add operation to the file content
                            switch ($search_node_position) {
                                case 'top':
                                    $tmp[(int) $search_node_offset] = $add_node_value.$tmp[(int) $search_node_offset];
                                    break;
                                case 'bottom':
                                    $offset = $line_max - (int) $search_node_offset;
                                    if ($offset < 0) {
                                        $tmp[-1] = $add_node_value;
                                    } else {
                                        $tmp[$offset] .= $add_node_value;
                                    }
                                    break;
                                default:
                                    $changed = false;
                                    foreach ($tmp as $line_num => $line) {
                                        if (0 == strlen($search_node_value)) {
                                            if ('log' == $operation_node_error || 'abort' == $operation_node_error) {
                                                $log[] = 'Modification::refresh - EMPTY SEARCH CONTENT ERROR:';
                                                $log[] = "  modification id = '$modification_id'";
                                                $log[] = "  file name = '$file'";
                                                $log[] = '';
                                            }
                                            break;
                                        }

                                        if ('true' == $search_node_regex) {
                                            $pos = @preg_match($search_node_value, $line);
                                            if (false === $pos) {
                                                if ('log' == $operation_node_error || 'abort' == $operation_node_error) {
                                                    $log[] = 'Modification::refresh - INVALID REGEX ERROR:';
                                                    $log[] = "  modification id = '$modification_id'";
                                                    $log[] = "  file name = '$file'";
                                                    $log[] = "  search = '$search_node_value'";
                                                    $log[] = '';
                                                }
                                                continue 2; // continue with next operation_node
                                            } elseif (0 == $pos) {
                                                $pos = false;
                                            }
                                        } else {
                                            $pos = strpos($line, $search_node_value);
                                        }

                                        if (false !== $pos) {
                                            ++$index_count;
                                            $changed = true;

                                            if (!$search_node_indexes || ($search_node_indexes && in_array($index_count, $search_node_indexes))) {
                                                switch ($search_node_position) {
                                                    case 'before':
                                                        $offset = ($line_num - $search_node_offset < 0) ? -1 : $line_num - $search_node_offset;
                                                        $tmp[$offset] = empty($tmp[$offset]) ? $add_node_value : $add_node_value."\n".$tmp[$offset];
                                                        break;
                                                    case 'after':
                                                        $offset = ($line_num + $search_node_offset > $line_max) ? $line_max : $line_num + $search_node_offset;
                                                        $tmp[$offset] = $tmp[$offset]."\n".$add_node_value;
                                                        break;
                                                    case 'ibefore':
                                                        $tmp[$line_num] = str_replace($search_node_value, $add_node_value.$search_node_value, $line);
                                                        break;
                                                    case 'iafter':
                                                        $tmp[$line_num] = str_replace($search_node_value, $search_node_value.$add_node_value, $line);
                                                        break;
                                                    default:
                                                        if (!empty($search_node_offset)) {
                                                            for ($i = 1; $i <= $search_node_offset; ++$i) {
                                                                if (isset($tmp[$line_num + $i])) {
                                                                    $tmp[$line_num + $i] = '';
                                                                }
                                                            }
                                                        }
                                                        if ('true' == $search_node_regex) {
                                                            $tmp[$line_num] = preg_replace($search_node_value, $add_node_value, $line);
                                                        } else {
                                                            $tmp[$line_num] = str_replace($search_node_value, $add_node_value, $line);
                                                        }
                                                        break;
                                                }
                                            }
                                        }
                                    }

                                    if (!$changed) {
                                        $skip_text = ('skip' == $operation_node_error || 'log' == $operation_node_error) ? '(SKIPPED)' : '(ABORTING MOD)';
                                        if ('log' == $operation_node_error || $operation_node_error) {
                                            $log[] = "Modification::refresh - SEARCH NOT FOUND $skip_text:";
                                            $log[] = "  modification id = '$modification_id'";
                                            $log[] = "  file name = '$file'";
                                            $log[] = "  search = '$search_node_value'";
                                            $log[] = '';
                                        }

                                        if ('abort' == $operation_node_error) {
                                            break;
                                            // return $modification; // skip this XML file
                                        }
                                    }
                                    break;
                            }

                            ksort($tmp);

                            $modification[$key] = implode("\n", $tmp);
                        } // $operation_nodes
                    } // $files
                } // $file_nodes

                $log[] = "Modification::refresh - Done '".$modification_id."'";
                $log[] = '----------------------------------------------------------------';
                $log[] = '';
            }

            // Log
            if ((defined('DIR_LOGS'))) {
                $ocmod = new Log('ocmod.log');
                $ocmod->write(implode("\n", $log));
            }
        }
        $result = [];
        $result['modification'] = $modification;
        $result['original'] = $original;

        return $result;
    }

    public function _vqmodGetIndexes($search_node_index)
    {
        if ($search_node_index) {
            $tmp = explode(',', $search_node_index);
            foreach ($tmp as $k => $v) {
                if (!is_int($v)) {
                    unset($k);
                }
            }
            $tmp = array_unique($tmp);

            return empty($tmp) ? false : $tmp;
        } else {
            return false;
        }
    }

    public function _vqmodGetFileKey($file)
    {
        // Get the key to be used for the modification cache filename.
        $key = '';
        if (DIR_CATALOG == substr($file, 0, strlen(DIR_CATALOG))) {
            $key = 'catalog/'.substr($file, strlen(DIR_CATALOG));
        }
        if (DIR_APPLICATION == substr($file, 0, strlen(DIR_APPLICATION))) {
            $key = 'admin/'.substr($file, strlen(DIR_APPLICATION));
        }
        if (DIR_SYSTEM == substr($file, 0, strlen(DIR_SYSTEM))) {
            $key = 'system/'.substr($file, strlen(DIR_SYSTEM));
        }

        return $key;
    }
}
