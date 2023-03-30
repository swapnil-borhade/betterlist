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

function registeruser()
{
    if($_POST['payment_type'] != 'free')
    {
        $response = array(
            'success' => true,
            'data' => array(
                'url' => 'payment_gateway'
            ),
        );
        echo json_encode($response);
    }
    else
    {
        $curl = curl_init();
        $CURLOPT_URL = baseUrl.'register.php/userinfo';
        $CURLOPT_CUSTOMREQUEST = 'POST';
        $CURLOPT_POSTFIELDS = '{
            "FirstName" : "'.sanitize_data($_POST['firstname']).'",
            "LastName" : "'.sanitize_data($_POST['lastname']).'",
            "EmailId" : "'.$_POST['email'].'",
            "Company" : "'.sanitize_data($_POST['company']).'",
            "CountryName" : "'.sanitize_data($_POST['country_name']).'",
            "PaymentType" : "'.sanitize_data($_POST['payment_type']).'"
        }';
        curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
        $response = curl_exec($curl);
        echo $response;
    }
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
        $_SESSION['userid'] = encryp($response_array['data']['id']);
        $_SESSION['login'] = true;
    }
    echo $response;
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
    echo $response;
}

function setnewpassword()
{
    $payment_type = (isset($_POST['payment_type']))  ? ', "PaymentType" : "'.$_POST['dsad'].'"' : '' ;
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'register.php/setnewpassword';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.decryp($_POST['id']).'",
        "newpassword" : "'.$_POST['password'].'",
        "confirmpassword" : "'.$_POST['confirm_password'].'"'.
        $payment_type.'
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    $response_array = json_decode($response, true);
    if($response_array['success'] == true)
    {
        unset($_SESSION['forgotPassword']);
        if(isset($_SESSION['paymenttype']))
        {
            unset($_SESSION['paymenttype']);
        }
    }
    echo $response;
}

?>