<?php
session_start();

include ('functions.php');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
switch ($action) {
    case 'support' : Support(); break;
    default : header('Location: ../404.php'); 
}

function Support()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'support.php/supportinfo';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.decryp($_POST['userid']).'",
        "topicname": "'.$_POST['topicname'].'",
        "website": "'.$_POST['website'].'",
        "subject": "'.$_POST['subject'].'",
        "message": "'.$_POST['message'].'",
        "policy": "'.$_POST['policy'].'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    echo $response;
}

?>