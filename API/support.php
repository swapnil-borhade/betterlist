<?php
include("connection.php");
include("auth.php");
include("controller/support_controller.php");

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
		case ($action == 'supportinfo' && $method == 'POST'): insertSupport($pdo); break;
		default: methodNotallowed(); break;
	}
}
else
{
	header("HTTP/1.1 401 Unauthorized");
	echo json_encode(array('data' => $response));
}
?>