<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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



    public function kwikDataUpload()
    {  
        $log = new Log('error.log');
        $log->write("kwik data upload");

               

        try
        {

        $sdk = new Aws\Sdk([
            'region' => 'ap-south-1',
            'version' => 'latest',
            'credentials' => [
                'key' => 'AKIAUWRTJZVBETGW5LVM',
                'secret' => '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'
            ]
        ]); 
        $log->write("kwik data upload1");

        // Use an Aws\Sdk class to create the S3Client object.
        $s3Client = $sdk->createS3();

        // echo BUCKET_PREFIX . "\n";die;
        $log->write(BUCKET_PREFIX);


        try {
           
            $resultBucketList = $s3Client->listBuckets();
            $bucketexists=false;
            $bucket = BUCKET_PREFIX.'kwikbasket-data';
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
                $s3Client->createBucket(['Bucket' => BUCKET_PREFIX.'kwikbasket-data']);
            }


           
            // $folder_path ."/". $filename,"wb"

        // $deliveryDate =   date("Y-m-d");// date("Y-m-d",strtotime("-1 days"));
        // $date = date("Y-m-d", strtotime("0 days")); 

        // $filter_data = [
        //     'filter_date' => $date,
        // ];
        // $this->load->model('scheduler/dbupdates');
        // $results = $this->model_scheduler_dbupdates->getView_kwik($filter_data);


        $query = $this->db->query('SELECT * FROM view_kwik ');
        $log->write("kwik data upload4");

        // echo $query->rows;die;
        //   echo "<pre>";print_r($query->rows);die;
                 
        $data['products'] = $query->rows;
        //   echo "<pre>";print_r($data);die;
        try{
        if ($query->num_rows > 0) {
            // echo "<pre>";print_r($data['products']);die;
            // $this->load->model('report/excel');
            // $file = $this->model_report_excel->mail_consolidated_order_sheet_excel($data);
        
            $delimiter = ","; 

        $log->write("kwik data upload5");

            if (!file_exists(DIR_UPLOAD . 'schedulertemp/')) {
                mkdir(DIR_UPLOAD . 'schedulertemp/', 0777, true);
            }
            // unlink($filename);
            $folder_path = DIR_UPLOAD . 'schedulertemp';
            $files = glob($folder_path . '/*');
            // Deleting all the files in the list 
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the given file  
                }
            }

            $filename = "kwik-data_" . date('Y-m-d') . ".csv"; 
            $file_Path = DIR_UPLOAD . 'schedulertemp/' . $filename;

            
            // Create a file pointer 
            $f = fopen($file_Path, 'a'); 
             
            // Set column headers 
            $fields = array('order_id', 'order_status_id', 'customer_id', 'account_manager_id', 'payment_method', 'order_total', 'vendor_product_id', 'general_product_id','product_name','unit','quantity','price','product_total','delivery_date','date','time','delivery_timeslot','dayofweek','month','year','city_id','city'); 
            fputcsv($f, $fields, $delimiter); 
        $log->write("kwik data upload6");

             // Output each row of the data, format line as csv and write to file pointer 
        // while($row = $this->db->query->fetch_assoc()){ 
            foreach($data['products'] as $row){
            // $status = ($row['status'] == 1)?'Active':'Inactive'; 
            $lineData = array($row['order_id'], $row['order_status_id'], $row['customer_id'], $row['account_manager_id'], $row['payment_method'], $row['order_total'], $row['vendor_product_id'], $row['general_product_id'], $row['product_name'], $row['unit'], $row['quantity'], $row['price'], $row['product_total'], $row['delivery_date'], $row['date'], $row['time'],$row['delivery_timeslot'],$row['dayofweek'],$row['month'],$row['year'],$row['city_id'],$row['city']); 
            fputcsv($f, $lineData, $delimiter); 
        } 
     
        // Move back to beginning of file 
        fseek($f, 0); 
        $log->write("kwik data upload7");
        
        // Set headers to download file rather than displayed 
        // header('Content-Type: text/csv'); 
        // header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        
        //output all remaining data on a file pointer 
        fpassthru($f); 
            }

            }
            catch(exception $e)
            {

                $log = new Log('error.log');
                $log->write($e->getMessage);
                echo $e->getMessage;die;

            }
            $log->write("kwik data upload6");

   
            // echo "<pre>";print_r($file);;
            // $objWriter->save(DIR_UPLOAD . 'schedulertemp/' . $filename);
    

            $key = basename($file_Path);
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'SourceFile' => $file_Path,
                'ACL' => 'private',
            ]);
            echo $result['ObjectURL'] . "\n";
        $log->write($result['ObjectURL']);

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
            $xtime = date("Y-m-d  H:i:s", strtotime("-1 days"));
             
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
            $log = new Log('error.log');
            // Catch an S3 specific exception.
            $log->write("Exception in -S3 specific exception");


            echo $e->getMessage();
        $log->write($e->getMessage());

        } catch (AwsException $e) {

            $log = new Log('error.log');
        $log->write("AwsException in kwik data upload");
        $log->write($e->getAwsErrorCode());


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
        catch(exception $e)
        {
            $log = new Log('error.log');
            $log->write("kwik data upload8");
            $log->write($e->getMessage());


        }
         
    }
    
}
