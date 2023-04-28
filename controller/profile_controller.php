<?php
session_start();

include ('functions.php');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
switch ($action) {
    case 'updateuser' : updateuser(); break;
    default : header('Location: ../404.php'); 
}

function updateuser()
{
    echo $_POST['address'];
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'profile.php/userinfo';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.decryp($_POST['userid']).'",
        "firstname": "'.$_POST['firstname'].'",
        "lastname": "'.$_POST['lastname'].'",
        "mobile": "'.$_POST['mobile'].'",
        "company": "'.$_POST['company'].'",
        "address": "'.$_POST['address'].'",
        "city": "'.$_POST['city'].'",
        "country": "'.$_POST['country'].'"
    }';

    echo $CURLOPT_POSTFIELDS;
    exit();

    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    echo $response;
}

?>