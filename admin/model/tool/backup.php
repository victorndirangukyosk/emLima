<?php


require_once DIR_SYSTEM . '/vendor/aws/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;


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
                $filelastmodified = filemtime($file);
        //24 hours in a day * 3600 seconds per hour
        if((time() - $filelastmodified) > 1*3600)
        {
           unlink($path . $file);
        }


                // unlink($file); // Delete the given file  
            }

        }
        // echo "<pre>";print_r($file);     

        $filename=DB_DATABASE."_".date('Y-m-d_H-i-s', time())."_backup.sql";
            // $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/myText.txt","wb");
            $fp = fopen($folder_path ."/". $filename,"wb");
            fwrite($fp,$output);
            fclose($fp);



            // Create an SDK class used to share configuration across clients.
        $sdk = new Aws\Sdk([
            'region' => 'ap-south-1',
            'version' => 'latest',
            'credentials' => [
                'key' => 'AKIAUWRTJZVBETGW5LVM',
                'secret' => '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'
            ]
        ]);

        // Use an Aws\Sdk class to create the S3Client object.
        $s3Client = $sdk->createS3();

        try {
             $s3Client->createBucket(['Bucket' => 'kwikbasket-backups']);

            $bucket = 'kwikbasket-backups';
            // $folder_path ."/". $filename,"wb"
            $file_Path = $folder_path ."/". $filename;
            $key = basename($file_Path);
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'SourceFile' => $file_Path,
                'ACL' => 'private',
            ]);
        } catch (S3Exception $e) {
            // Catch an S3 specific exception.
            echo $e->getMessage();
        } catch (AwsException $e) {
            // This catches the more generic AwsException. You can grab information
            // from the exception using methods of the exception object.
            echo $e->getAwsRequestId() . "\n";
            echo $e->getAwsErrorType() . "\n";
            echo $e->getAwsErrorCode() . "\n";

            // This dumps any modeled response data, if supported by the service
            // Specific members can be accessed directly (e.g. $e['MemberName'])
            var_dump($e->toArray());
        }

    }
}
