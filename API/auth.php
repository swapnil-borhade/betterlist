<?php
date_default_timezone_set("Asia/Kolkata");
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

//###### check Authorization Api data ######//
function checkAuthorization()
{
    if(isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER']=='admin' && isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW']=='admin') 
    {
        $response = 200; 
    } 
    else 
    {
        $response = array(
            'Status Code' => '401',
            'msg' => 'Authorization error'
        );
    }
    return $response;
}

//###### method Not allowed data ######//
function methodNotallowed()
{
	header("HTTP/1.0 405 Method Not Allowed");
		$response = array(
			'Status Code' => '405',
			'msg' => ' Method Not Allowed'
		);
	echo json_encode(array('data' => $response));
}
?>