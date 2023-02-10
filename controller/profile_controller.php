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
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'profile.php/userinfo';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.$_POST['userid'].'",
        "firstname": "'.$_POST['firstname'].'",
        "lastname": "'.$_POST['lastname'].'",
        "company": "'.$_POST['company'].'",
        "address": "'.$_POST['address'].'",
        "city": "'.$_POST['city'].'",
        "country": "'.$_POST['country'].'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    // $response_array = json_decode($response, true);
    // print_r($response_array);
    // if($response_array['success'] == true)
    // {
    //     $_SESSION['userid'] = $response_array['data']['id'];
    //     $_SESSION['OTP'] = $response_array['data']['otp'];
    // }
    echo $response;
}

?>