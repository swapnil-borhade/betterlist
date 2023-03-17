<?php
date_default_timezone_set("UTC");
session_start();
include('../api/connection.php');

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
                    "message" => "user not verified.",
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
		$_SESSION['userid'] = $userid;
        $_SESSION['forgotPassword'] = true;
        header('Location: newpassword.php');
        die();
    }
    else
    { 
        echo $response['message']; 
    }
}

function decryp($id)
{
	$ciphering = "AES-128-CTR";
	$options = 0;
	$decryption_iv = '1234567891011121';
	$decryption_key = "hybreed";
	$decryption = openssl_decrypt($id, $ciphering, $decryption_key, $options, $decryption_iv);
	return $decryption;
}

function getlicensekey()
{
    $val_length = 16;
    $result = '';
    $module_length = 40;   // we use sha1, so module is 40 chars
    $steps = round(($val_length/$module_length) + 0.5);
    for( $i=0; $i<$steps; $i++ )
    {
        $result .= sha1(uniqid() . md5(rand()));
    }
    return substr($result, 0, $val_length);
}
?>