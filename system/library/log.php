<?php

define("LOCAL_CLOUDWATCH_LOG", DIR_LOG . 'cloudwatch.log');
define("LOCAL_CLOUDWATCH_ERROR_LOG", DIR_LOG . 'cloudwatch.error.log');

require_once DIR_SYSTEM . '/vendor/aws-sdk-php/vendor/autoload.php';
require_once DIR_SYSTEM . '/vendor/event-loop/vendor/autoload.php';
require_once DIR_SYSTEM . '/vendor/rxphp/vendor/autoload.php';

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Aws\CloudWatchLogs\Exception\CloudWatchLogsException;
use Rx\Scheduler;
use Rx\Subject\Subject;

class Log
{
    private static $cloudWatchClient = null;

    private static $logEventsObservable = null;

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

        if (self::$logEventsObservable == null) {
            Scheduler::setDefaultFactory(function () {
                return new Scheduler\ImmediateScheduler();
            });

            self::$logEventsObservable = new Subject;
            self::$logEventsObservable
                ->bufferWithCount(50)
                ->subscribe(
                    function ($logEvents) {
                        self::writeLogsToCloudWatch($logEvents);
                    }
                );
        }
    }

    public function write($message)
    {
        // The message passed to the CloudWatch client must be non null
        if (strlen($message) == 0) {
            $message = "Empty log message";
        }

        self::$logEventsObservable->onNext(
            [
                'message' => strval($message),
                'timestamp' => round(microtime(true) * 1000),
            ]
        );
    }

    private static function writeLogsToCloudWatch($logEvents)
    {
        $options = [
            'logEvents' => $logEvents,
            'logGroupName' => AWS_CLOUDWATCH_LOGS_GROUP,
            'logStreamName' => AWS_CLOUDWATCH_LOGS_STREAM,
        ];

        $nextSequenceToken = self::getSequenceToken();

        if ($nextSequenceToken) {
            $options['sequenceToken'] = $nextSequenceToken;
        }

        try {
            /* TODO: Cloudwatch calls are made synchronously.
            *  The pcntl extension isn't available for windows which enables asynchronicity.
            *  Thus the calling the method without wait() which is blocking, does not work.
            *  Huge perfomance improvements will be realized when the calls
            *  to cloudwatch are asynchronous
            */
            $response = self::$cloudWatchClient->putLogEventsAsync($options)->wait();
            self::setSequenceToken($response['nextSequenceToken']);
        } catch (CloudWatchLogsException $exception) {
            file_put_contents(
                LOCAL_CLOUDWATCH_ERROR_LOG,
                $exception->getAwsErrorCode() . "\n" . $exception->getMessage() . "\n",
                FILE_APPEND
            );
        }
    }

    // Returns cloudwatch logs sequence token stored on the local filesystem
    private static function getSequenceToken()
    {
        return file_get_contents(LOCAL_CLOUDWATCH_LOG, true);
    }

    // Persists cloudwatch logs sequence token on the local filesystem
    private static function setSequenceToken($token)
    {
        file_put_contents(LOCAL_CLOUDWATCH_LOG, $token);
    }
}
