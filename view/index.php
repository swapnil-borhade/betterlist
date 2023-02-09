<?php
session_start();

if(!isset($_SESSION["login"])) 
{
    header("Location:login.php");
}


include ('../controller/functions.php');
$curl = curl_init();
$CURLOPT_URL = baseUrl.'home.php/homescreen';
$CURLOPT_CUSTOMREQUEST = 'GET';
$CURLOPT_POSTFIELDS = '{
    "userid" : "'.$_SESSION['userid'].'"
}';
curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
$response = curl_exec($curl);


$response_arr = (json_decode($response,true));
if($response_arr['success']==true)
{
    $_SESSION['firstname'] = $response_arr['data']['firstname'];
    $_SESSION['lastname'] = $response_arr['data']['lastname'];
    $_SESSION['emailid'] = $response_arr['data']['emailid'];
    $_SESSION['mobile'] = $response_arr['data']['mobile'];
}
else
{
    echo "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Dashboard </h1>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <?php include('../assets/includes/left-menu.php') ?> 
                </div>
                <div class="col-md-8">
                    <h2> Welcome Back, <?php echo$_SESSION['firstname']?></h2>
                </div>
            </div>
        </div>
    </section>
    <?php include('../assets/includes/script-links.php'); ?>
</body>
</html>