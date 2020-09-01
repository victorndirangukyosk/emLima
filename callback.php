<?php

    $file = fopen('log.txt', 'w'); //url fopen should be allowed for this to occur

        fwrite('Error: no data written');

    fwrite("\r\n");
    fclose($file);

    echo '{"ResultCode": 0, "ResultDesc": "The service was accepted successfully", "ThirdPartyTransID": "1234567890"}';
