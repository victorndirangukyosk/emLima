<?php

require_once DIR_SYSTEM.'/vendor/aws-sdk-php/vendor/autoload.php';

use Aws\CloudWatchLogs\CloudWatchLogsClient;

class Log
{
    private $cloudWatchClient;

    public function __construct($filename)
    {
        $this->cloudWatchClient = new CloudWatchLogsClient([
            'version'     => 'latest',
            'region'      => AWS_CLOUDWATCH_LOGS_REGION,
            'credentials' => [
                'key'    => AWS_ACCESS_KEY,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ],
        ]);
    }

    public function write($message)
    {
        // The message passed to the CloudWatch client must be non null
        if(strlen($message) == 0) {
            $message = "Empty log message";
        }

        $this->cloudWatchClient->putLogEventsAsync([
            'logEvents' => [
                [
                    'message' => $message, 
                    'timestamp' => time(),
                ],
            ],
            'logGroupName' => AWS_CLOUDWATCH_LOGS_GROUP,
            'logStreamName' => AWS_CLOUDWATCH_LOGS_STREAM,
        ])->then(
            function($response) {
                echo $response."<br>";
            },
            function($error) {
                echo $error."<br>";
            }
        );
    }
}
