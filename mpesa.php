<?php

$BusinessShortCode = '705705';
$LipaNaMpesaPasskey = '8007821ca4a18721c0518a67938c855cd7c552c782a298f5dfd280ef22ae3cf7';
$timestamp = '20'.date('ymdhis');

$password = base64_encode($BusinessShortCode.$LipaNaMpesaPasskey.$timestamp);

echo $password;

echo '<br>';

echo $timestamp;
