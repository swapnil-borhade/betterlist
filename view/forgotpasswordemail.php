<?php
date_default_timezone_set("UTC");
session_start();
include('../api/connection.php');
include('../controller/functions.php');

forgotPasswordEmail($pdo);
// confirm Email ID
function forgotPasswordEmail($pdo)
{
	$userid = decryp($_GET['id']);

	$time = decryp($_GET['time']);
	$today = date("Y-m-d H:i:s");
	$expir_time = date($time + 15 * 60);

	if(time() < $expir_time) 
	{
		$data = array(
			"userid" => $userid
		);

		$sql_check = "SELECT `is_verify`,`is_active` FROM `tbl_users` WHERE id = :userid";
		$stmt = $pdo->prepare($sql_check);
		$stmt->execute($data);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            if($result['is_verify'] == 1 && $result['is_active'] == 1) 
            {
                $response = array(
                    "success" => true,
                    "message" => "user verified. set new password",
                );
            }
            else
            {
                $response = array(
                    "success" => false,
                    "error" => true,
                    "message" => "user not verified. plz register again",
                );
            }
        }
        else
        {
            $response = array(
                "success" => false,
                "error" => true,
                "message" => "user not found.",
            );
        }
	}
	else
	{
		$response = array(
			"success" => false,
			"error" => true,
			"message" => "The link is expired. login screen",
		);
	}

    if($response['success']==true)
    {
		$_SESSION['userid'] = encryp($userid);
        $_SESSION['forgotPassword'] = true;
        header('Location: newpassword.php');
        die();
    }
    else
    { 
        echo $response['message']; 
    }
}

?>