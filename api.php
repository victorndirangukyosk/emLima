<?php

header('Content-Type:application/json');
require 'data.php';

if (!empty($_GET['titles'])) {
    $titles = $_GET['titles'];
    $price = get_price($titles);

    if (empty($price)) {
        response(200, 'Movie Not Found', null);
    } else {
        response(200, 'Movie Found', $price);
    }
} else {
    response(400, 'Invalid Request', null);
}

function response($status, $status_message, $data)
{
    header('HTTP/1.1 '.$status);

    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}
