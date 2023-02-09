<?php
session_start();

include ('functions.php');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
switch ($action) {
    case 'registeruser' : registeruser(); break;
    case 'loginuser' : loginuser(); break;
    case 'forgot_password_email' : forgotpassword(); break;
    case 'setnewpassword' : setnewpassword(); break;
    default : header('Location: ../404.php'); 
}

function forgotpassword()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'register.php/frogotpassword';
    $CURLOPT_CUSTOMREQUEST = 'GET';
    $CURLOPT_POSTFIELDS = '{
        "EmailId" : "'.$_POST['email'].'"
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


function setnewpassword()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'register.php/setnewpassword';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.$_POST['id'].'",
        "newpassword" : "'.$_POST['password'].'",
        "confirmpassword" : "'.$_POST['confirm_password'].'"
    }';


    //echo $CURLOPT_POSTFIELDS;
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    $response_array = json_decode($response, true);
    //print_r($response_array);
    if($response_array['success'] == true)
    {
        $_SESSION['forgotPassword'] = '';
    }
    echo $response;
}

function registeruser()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'register.php/userinfo';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "FirstName" : "'.sanitize_data($_POST['firstname']).'",
        "LastName" : "'.sanitize_data($_POST['lastname']).'",
        "Mobile" : "'.sanitize_data($_POST['mobile']).'",
        "EmailId" : "'.$_POST['email'].'",
        "Password" : "'.$_POST['password'].'",
        "Company" : "'.sanitize_data($_POST['company']).'",
        "CountryName" : "'.sanitize_data($_POST['country_name']).'"
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

function loginuser()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'register.php/userinfo';
    $CURLOPT_CUSTOMREQUEST = 'GET';
    $CURLOPT_POSTFIELDS = '{
        "EmailId" : "'.$_POST['email'].'",
        "Password" : "'.$_POST['password'].'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    $response_array = json_decode($response, true);
    if($response_array['success'] == true)
    {
        $_SESSION['userid'] = $response_array['data']['id'];
        $_SESSION['login'] = true;
    }
    echo $response;
}

?>