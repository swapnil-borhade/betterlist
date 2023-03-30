<?php
date_default_timezone_set("UTC");
session_start();
include('../api/connection.php');
include('../controller/functions.php');

$message = forgotPasswordEmail($pdo);
// confirm Email ID
function forgotPasswordEmail($pdo)
{
	$userid = decryp($_GET['id']);
	$time = decryp($_GET['time']);
	$today = date("Y-m-d H:i:s");
	$expir_time = date($time + 15 * 60); //# D * H * M * S

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
        return $response['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Pawssword Email</title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p><?php echo $message;?></p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>