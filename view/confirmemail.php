<?php
date_default_timezone_set("UTC");
session_start();
include('../api/connection.php');
include('../controller/functions.php');

$userid = decryp($_GET['id']);
$time = decryp($_GET['time']);
$today = date("Y-m-d H:i:s");
$expir_time = date($time + 60 * 60); //# D * H * M * S

if(time() < $expir_time) 
{
    $_SESSION['userid'] = encryp($userid);
    $_SESSION['forgotPassword'] = true;
    header('Location: newpassword.php');
    die();
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Email</title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>The link is expired.</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>