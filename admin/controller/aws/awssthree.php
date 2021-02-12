<?php

require_once DIR_SYSTEM . '/vendor/aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;

class ControllerAwsAwssThree extends Controller {

    public function index() {
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
            //$s3Client->createBucket(['Bucket' => 'kwikbasket-logs']);

            $bucket = 'kwikbasket-logs';
            $file_Path = DIR_LOG . date('Y-m-d') . '.log';
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
