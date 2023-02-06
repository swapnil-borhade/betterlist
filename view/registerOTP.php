<?php
session_start();
echo $_SESSION['OTP'];

if(isset($_SESSION["login"])) 
{
    header("Location:index.php");
}

include ('../api/controller/functions.php');
define('baseUrl', 'https://code.hybclient.com/betterlist/API/');
// define('baseUrl', 'http://localhost/swapnil/work/betterlist/API/');
if(isset($_POST['submit']))
{
    if($_POST['otp'] == $_SESSION['OTP'])
    {
        $userid =  $_SESSION['userid'];
        $curl = curl_init();
        $CURLOPT_URL = baseUrl.'register.php/verifyUserfromemail';
        $CURLOPT_CUSTOMREQUEST = 'POST';
        $CURLOPT_POSTFIELDS = '{
            "userid" : "'.$userid.'"
        }';
        curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
        $response = curl_exec($curl);
        $response_arr = (json_decode($response,true));
        if($response_arr['success']==true)
        {
            $_SESSION['login'] = true;
            header('Location: index.php');
            die();
        }
        else
        { echo $response_arr['message']; }
    }
    else{ echo "not match otp"; }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>OTP</h1>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form name="submitotp" method="post">
                        <div class="mb-3">
                            <label for="otp" class="form-label">OTP</label>
                            <input type="text" class="form-control" name ="otp" id="otp">
                            <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
	<?php include('../assets/includes/script-links.php'); ?>
</body>
</html>