<?php
include("connection.php");
include("auth.php");
include("controller/license_controller.php");

//## check api Authorization
$response = checkAuthorization();

//## check action to do function call
//## change here second last folder call for action.
//$action = basename(dirname($_SERVER['REQUEST_URI']));

//## users id the last folder name for action.
$action = basename($_SERVER['REQUEST_URI']);

//## check method get,post,put,delete 
$method = $_SERVER['REQUEST_METHOD'];

if($response === 200)
{
	switch ($action)
	{
		case ($action == 'licenseinfo' && $method == 'GET'): getLicense($pdo); break;
		case ($action == 'licenseinfo' && $method == 'POST'): setLicense($pdo); break;
		case ($action == 'licenseinfo' && $method == 'DELETE'): deleteLicense($pdo); break;
	
		default: methodNotallowed(); break;
	}
}
else
{
	header("HTTP/1.1 401 Unauthorized");
	echo json_encode(array('data' => $response));
}
?>