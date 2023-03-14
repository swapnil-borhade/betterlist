<?php
session_start();

include ('functions.php');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
switch ($action) {
    case 'getlicenseinfo' : getlicenseinfo(); break;
    case 'setlicenseinfo' : setlicenseinfo(); break;
    case 'deleteLicense' : deleteLicense(); break;
    default : header('Location: ../404.php'); 
}

function getlicenseinfo()
{
    $columnIndex = $_POST['order'][0]['column']; // Column index

    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'license.php/licenseinfo';
    $CURLOPT_CUSTOMREQUEST = 'GET';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.sanitize_data($_POST['userid']).'",
        "draw" : "'.$_POST['draw'].'",
        "row" : "'.$_POST['start'].'",
        "rowperpage" : "'.$_POST['length'].'",
        "columnName" : "'.$_POST['columns'][$columnIndex]['data'].'",
        "columnSortOrder" : "'.$_POST['order'][0]['dir'].'",
        "searchValue" : "'.$_POST['search']['value'].'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    echo $response;
}

function setlicenseinfo()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'license.php/licenseinfo';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.sanitize_data($_POST['userid']).'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    echo $response;  
}

function deleteLicense()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'license.php/licenseinfo';
    $CURLOPT_CUSTOMREQUEST = 'DELETE';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.sanitize_data($_POST['userid']).'",
        "licenseid" : "'.sanitize_data($_POST['licenseid']).'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    echo $response;  
}

?>