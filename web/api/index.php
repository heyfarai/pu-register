<?php

    include_once('../../lib/php/config.inc.php');

    $host = $HOST_URLS[PHP_ENV]['TICKETS_HOST_URL'];

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "$host/api/staff/"
    ));

    // Send the request & save response to $resp
    $res = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);

    header('Content-Type: application/json');
    $msg = array('checked' => true);

    echo (json_encode($msg));

?>
