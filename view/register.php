<?php
include ('../api/controller/functions.php');
session_start(); 

if(isset($_SESSION["login"])) 
{
    header("Location:index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Register </title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Register</h1>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <form name="register_form_submit" method="post">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name </label>
                            <input type="text" class="form-control" name ="firstname" id="firstname">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name ="lastname" id="lastname">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" name ="email" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                        </div>
                        <div class="mb-3">
                            <label for="company" class="form-label">Company</label>
                            <input type="text" class="form-control" name="company" id="company">
                        </div>
                        <input type="hidden" name="country_name" value="<?php echo getLoction()['country'];?>">
                        <input type="hidden" name="action" value="registeruser">

                        <a href="login.php" class="">login</a>
                        <div id="show_server_error"></div>
                        <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
	<?php include('../assets/includes/script-links.php');?>
</body>
</html>