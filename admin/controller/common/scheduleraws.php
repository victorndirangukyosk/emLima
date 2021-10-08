<?php 

require_once DIR_SYSTEM . '/vendor/aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;

class ControllerCommonSchedulerAWS extends Controller {

    private $error = [];

        
    public function backupDB()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        $this->load->language('tool/backup');
 
            // $this->response->addheader('Pragma: public');
            // $this->response->addheader('Expires: 0');
            // $this->response->addheader('Content-Description: File Transfer');
            // $this->response->addheader('Content-Type: application/octet-stream');
            // $this->response->addheader('Content-Disposition: attachment; filename='.DB_DATABASE.'_'.date('Y-m-d_H-i-s', time()).'_backup.sql');
            // $this->response->addheader('Content-Transfer-Encoding: binary');

            $this->load->model('tool/backup');
            // $data['tables'] = $this->model_tool_backup->getAllTables();//all tables in DB
            // $data['tables'] = $this->model_tool_backup->getTables();//all hf7_ tables
            $data['tables'] = $this->model_tool_backup->getSelectedTables();// only main transaction tables
            // echo "<pre>";print_r($data['tables']);die;


            // $this->response->setOutput($this->model_tool_backup->backupToLocation($data['tables']));
           $this->model_tool_backup->backupToLocation($data['tables']);
         
    }



    public function logfileUpload()
    {  
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

        // echo BUCKET_PREFIX . "\n";die;

        try {
           
            $resultBucketList = $s3Client->listBuckets();
            $bucketexists=false;
            $bucket = BUCKET_PREFIX.'kwikbasket-log';
            foreach ($resultBucketList['Buckets'] as $bucketlist) {
                // Each Bucket value will contain a Name and CreationDate
                //  echo "{$bucketlist['Name']} - {$bucketlist['CreationDate']}\n";
                if($bucketlist['Name']==$bucket)
                {
                    $bucketexists=true;
                }
            }
            if($bucketexists==false)
            {
                $s3Client->createBucket(['Bucket' => BUCKET_PREFIX.'kwikbasket-log']);
            }


           
            // $folder_path ."/". $filename,"wb"
            $file_Path = DIR_LOG . date('Y-m-d', strtotime("-1 days")) . '.log';
            $key = basename($file_Path);
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'SourceFile' => $file_Path,
                'ACL' => 'private',
            ]);
            echo $result['ObjectURL'] . "\n";
            //insert the log file url in table with date.
            $this->load->model('scheduler/dbupdates');
            $result = $this->model_scheduler_dbupdates->insertLogURL($result['ObjectURL']);
            if($result==1)
            {
            unlink($file_Path);
            }
            #region delete previous files
            $iterator = $s3Client->getIterator('ListObjects', array(
                'Bucket' => $bucket
            ));
            $xtime = date("Y-m-d  H:i:s", strtotime("-3 days"));
             
            foreach($iterator as $object){
                echo "{$object['Key']} - {$object['CreationDate']}- {$object['LastModified']}\n";
                $uploaded =$object["LastModified"];
                if($uploaded < $xtime)
                {
                    
                    $s3Client->deleteObject(array(
                        "Bucket"        => $bucket,
                        "Key"           => $object["Key"]
                    ));
                }
            }

            #endregion

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
