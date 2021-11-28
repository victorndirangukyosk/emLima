<?php

    $postData = file_get_contents('php://input');
    //perform your processing here, e.g. log to file....
    $file = fopen('log.txt', 'w'); //url fopen should be allowed for this to occur
    if (false === fwrite($file, $postData)) {
        fwrite('Error: no data written');
    }

    fwrite("\r\n");
    fclose($file);

    echo '{"ResultCode": 0, "ResultDesc": "The service was accepted successfully", "ThirdPartyTransID": "1234567890"}';
