<?php
session_start();
echo $_SESSION['OTP'];

if(isset($_SESSION["login"])) 
{
    header("Location:index.php");
}
elseif(!isset($_SESSION["OTP"]))
{
    header("Location:login.php");
}
$error = '';
if(isset($_POST['submit']))
{
    if($_POST['otp'] == $_SESSION['OTP'])
    {
        $userid = $_SESSION['userid'];
        $_SESSION['forgotPassword'] = "true";
        $_SESSION['OTP'] = '';
        header('Location: newpassword.php');
        die();
    }
    else{ $error = "not match otp"; }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password OTP</title>
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
                            <div id="error"><?php echo $error;?></div>
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