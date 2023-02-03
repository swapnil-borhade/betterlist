<?php
session_start();

include ('../api/controller/functions.php');
define('baseUrl', 'http://localhost/swapnil/work/better_list/API/');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
switch ($action) {
    case 'registeruser' : registeruser(); break;
    default : header('Location: ../404.php'); 
}

function registeruser()
{
    $data = array(
        "firstname" => sanitize_data($_POST['firstname']),
        "lastname" => sanitize_data($_POST['lastname']),
        "email" => $_POST['email'],
        "password" => $_POST['password'],
        "company" => sanitize_data($_POST['company']),
        "country_name" => sanitize_data($_POST['country_name']),
    );
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'register.php/userinfo';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "FirstName" : "'.$data['firstname'].'",
        "LastName" : "'.$data['lastname'].'",
        "EmailId" : "'.$data['email'].'",
        "Password" : "'.$data['password'].'",
        "Company" : "'.$data['company'].'",
        "CountryName" : "'.$data['country_name'].'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);

    $response_array = json_decode($response, true);
    // print_r($response_array);
    if($response_array['success'] == true)
    {
        $_SESSION['userid'] = $response_array['data']['id'];
        $_SESSION['OTP'] = $response_array['data']['otp'];
    }
    echo $response;
}

?>