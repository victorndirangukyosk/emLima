<?php

class ModelToolBackup extends Model
{
    public function restore($sql)
    {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);

            if ($sql) {
                $this->db->query($sql);
            }
        }

        $this->cache->delete('*');
    }

    public function getTables()
    {
        $table_data = [];

        $query = $this->db->query('SHOW TABLES FROM `'.DB_DATABASE.'`');

        foreach ($query->rows as $result) {
            if (DB_PREFIX == utf8_substr($result['Tables_in_'.DB_DATABASE], 0, strlen(DB_PREFIX))) {
                if (isset($result['Tables_in_'.DB_DATABASE])) {
                    $table_data[] = $result['Tables_in_'.DB_DATABASE];
                }
            }
        }

        return $table_data;
    }

    public function backup($tables)
    {
        $this->trigger->fire('pre.admin.backup', $tables);

        $output = '';

        foreach ($tables as $table) {
            if (DB_PREFIX) {
                if (false === strpos($table, DB_PREFIX)) {
                    $status = false;
                } else {
                    $status = true;
                }
            } else {
                $status = true;
            }

            if ($status) {
                $output .= 'TRUNCATE TABLE `'.$table.'`;'."\n\n";

                $query = $this->db->query('SELECT * FROM `'.$table.'`');

                foreach ($query->rows as $result) {
                    $fields = '';

                    foreach (array_keys($result) as $value) {
                        $fields .= '`'.$value.'`, ';
                    }

                    $values = '';

                    foreach (array_values($result) as $value) {
                        $value = str_replace(["\x00", "\x0a", "\x0d", "\x1a"], ['\0', '\n', '\r', '\Z'], $value);
                        $value = str_replace(["\n", "\r", "\t"], ['\n', '\r', '\t'], $value);
                        $value = str_replace('\\', '\\\\', $value);
                        $value = str_replace('\'', '\\\'', $value);
                        $value = str_replace('\\\n', '\n', $value);
                        $value = str_replace('\\\r', '\r', $value);
                        $value = str_replace('\\\t', '\t', $value);

                        $values .= '\''.$value.'\', ';
                    }

                    $output .= 'INSERT INTO `'.$table.'` ('.preg_replace('/, $/', '', $fields).') VALUES ('.preg_replace('/, $/', '', $values).');'."\n";
                }

                $output .= "\n\n";
            }
        }

        $this->trigger->fire('post.admin.backup');

        return $output;
    }


    public function getAllTables()
    {
        $table_data = [];

        $query = $this->db->query('SHOW TABLES FROM `'.DB_DATABASE.'`');

        foreach ($query->rows as $result) {
            // if (DB_PREFIX == utf8_substr($result['Tables_in_'.DB_DATABASE], 0, strlen(DB_PREFIX))) {
                if (isset($result['Tables_in_'.DB_DATABASE])) {
                    $table_data[] = $result['Tables_in_'.DB_DATABASE];
                }
            // }
        }

        return $table_data;
    }


    public function backupToLocation($tables)
    {
        $this->trigger->fire('pre.admin.backup', $tables);

        $output = '';

        foreach ($tables as $table) {
            if (DB_PREFIX) {
                if (false === strpos($table, DB_PREFIX)) {
                    $status = false;
                } else {
                    $status = true;
                }
            } else {
                $status = true;
            }

            if ($status) {
                $output .= 'TRUNCATE TABLE `'.$table.'`;'."\n\n";

                $query = $this->db->query('SELECT * FROM `'.$table.'`');

                foreach ($query->rows as $result) {
                    $fields = '';

                    foreach (array_keys($result) as $value) {
                        $fields .= '`'.$value.'`, ';
                    }

                    $values = '';

                    foreach (array_values($result) as $value) {
                        $value = str_replace(["\x00", "\x0a", "\x0d", "\x1a"], ['\0', '\n', '\r', '\Z'], $value);
                        $value = str_replace(["\n", "\r", "\t"], ['\n', '\r', '\t'], $value);
                        $value = str_replace('\\', '\\\\', $value);
                        $value = str_replace('\'', '\\\'', $value);
                        $value = str_replace('\\\n', '\n', $value);
                        $value = str_replace('\\\r', '\r', $value);
                        $value = str_replace('\\\t', '\t', $value);

                        $values .= '\''.$value.'\', ';
                    }

                    $output .= 'INSERT INTO `'.$table.'` ('.preg_replace('/, $/', '', $fields).') VALUES ('.preg_replace('/, $/', '', $values).');'."\n";
                }

                $output .= "\n\n";
            }
        }

        $this->trigger->fire('post.admin.backup');

        // return $output;

        if (!file_exists(DIR_UPLOAD . 'backuptemp/')) {
            mkdir(DIR_UPLOAD . 'backuptemp/', 0777, true);
        }
        // unlink($filename);
        $folder_path = DIR_UPLOAD . 'backuptemp';
        $files = glob($folder_path . '/*');
        // Deleting all the files in the list 
        foreach ($files as $file) {
            if (is_file($file))
            {
                unlink($file); // Delete the given file  
            }
        }
        // echo "<pre>";print_r($file);     

        $filename=DB_DATABASE."_".date('Y-m-d_H-i-s', time())."_backup.sql";
            // $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/myText.txt","wb");
            $fp = fopen($folder_path ."/". $filename,"wb");
            fwrite($fp,$output);
            fclose($fp);

    }
}
