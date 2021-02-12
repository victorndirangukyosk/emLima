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
                'key' => '',
                'secret' => ''
            ]
        ]);

        // Use an Aws\Sdk class to create the S3Client object.
        $s3Client = $sdk->createS3();

        try {
            $s3Client->createBucket(['Bucket' => 'credright-documents']);
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
