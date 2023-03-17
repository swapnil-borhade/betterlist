<?php
date_default_timezone_set("UTC");
session_start();
include('../api/connection.php');

confirmEmailid($pdo);
// confirm Email ID
function confirmEmailid($pdo)
{
	$userid = decryp($_GET['id']);
	$time = decryp($_GET['time']);
	$today = date("Y-m-d H:i:s");
	$expir_time = date($time + 2 * 24 * 60 * 60);

	if(time() < $expir_time) 
	{
		$data = array(
			"userid" => $userid,
			"is_active" => 1
		);
		$sql_check = "SELECT `is_verify` FROM `tbl_users` WHERE id = :userid and is_active = :is_active";
		$stmt = $pdo->prepare($sql_check);
		$stmt->execute($data);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if($result['is_verify'] != 1) 
		{
			$sql = "UPDATE `tbl_users` SET `is_verify`= 1 WHERE id = :userid and is_active = :is_active";
			$stmt = $pdo->prepare($sql);
			if($stmt->execute($data))
			{
				$end_date = date('Y-m-d H:i:s',strtotime('+365 days',strtotime($today)));
				$insert_data = array(
					"userid" => $userid,
					"start_date" => $today,
					"end_date" => $end_date
				);
				$insert_sql = "INSERT INTO `tbl_payment`(`userid`, `start_date`, `end_date`) VALUES (:userid, :start_date, :end_date)";
				$insert_stmt = $pdo->prepare($insert_sql);
				if($insert_stmt->execute($insert_data))
				{
					$insert_data_licences = array(
						"userid" => $userid,
						"license_key" => getlicensekey(),
						"start_date" => $today,
						"end_date" => $end_date
					);
					$insert_sql_licences = "INSERT INTO `tbl_license`(`userid`, `license_key`, `start_date`, `end_date`) VALUES (:userid, :license_key, :start_date, :end_date)";
					$insert_stmt_licences = $pdo->prepare($insert_sql_licences);
					$insert_stmt_licences->execute($insert_data_licences);
				}

				$response = array(
					"success" => true,
					"message" => "User verify successfully.",
				);
			}
			else
			{
				$response = array(
					"success" => false,
					"error" => true,
					"message" => "The link is expired. login screen",
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
        $_SESSION['login'] = true;
        header('Location: index.php');
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