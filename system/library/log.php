<?php

define("LOCAL_CLOUDWATCH_LOG", DIR_LOG.'cloudwatch.log');
define("LOCAL_CLOUDWATCH_ERROR_LOG", DIR_LOG.'cloudwatch.error.log');

require_once DIR_SYSTEM . '/vendor/aws-sdk-php/vendor/autoload.php';

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Aws\CloudWatchLogs\Exception\CloudWatchLogsException;

class Log
{
    private static $cloudWatchClient = null;


    public function __construct($filename)
    {
        if (self::$cloudWatchClient == null) {
            self::$cloudWatchClient = new CloudWatchLogsClient([
                'version'     => 'latest',
                'region'      => AWS_CLOUDWATCH_LOGS_REGION,
                'credentials' => [
                    'key'    => AWS_ACCESS_KEY,
                    'secret' => AWS_SECRET_ACCESS_KEY,
                ],
            ]);
            
        }
    }

    public function write($message)
    {
        // The message passed to the CloudWatch client must be non null
        if (strlen($message) == 0) {
            $message = "Empty log message";
        }

        $options = [
            'logEvents' => [
                [
                    'message' => $message,
                    'timestamp' => round(microtime(true) * 1000),
                ],
            ],
            'logGroupName' => AWS_CLOUDWATCH_LOGS_GROUP,
            'logStreamName' => AWS_CLOUDWATCH_LOGS_STREAM,
        ];

        $nextSequenceToken = $this->getSequenceToken();

        if($nextSequenceToken) {
            $options['sequenceToken'] = $nextSequenceToken;
        }

        try {
            $response = self::$cloudWatchClient->putLogEvents($options);
            $this->setSequenceToken($response['nextSequenceToken']);
            
        } catch (CloudWatchLogsException $exception) {
            file_put_contents(LOCAL_CLOUDWATCH_ERROR_LOG, 
                            $exception->getAwsErrorCode() . "\n" . $exception->getMessage() . "\n",
                            FILE_APPEND);
        }
    }

    // Returns cloudwatch logs sequence token stored on the local filesystem
    private function getSequenceToken()
    {
        return file_get_contents(LOCAL_CLOUDWATCH_LOG, true);
    }

    // Persists cloudwatch logs sequence token on the local filesystem
    private function setSequenceToken($token)
    {
        file_put_contents(LOCAL_CLOUDWATCH_LOG, $token);
    }
}
