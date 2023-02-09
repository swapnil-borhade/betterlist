<?php
include ('../controller/functions.php');
session_start(); 

if(isset($_SESSION["login"])) 
{
    header("Location:index.php");
}
elseif(!isset($_SESSION['forgotPassword']))
{
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> New Password </title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>New Password</h1>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <form name="new_password_form_submit" method="post">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $_SESSION['userid']; ?>">
                        <input type="hidden" name="action" value="setnewpassword">
                        <div id="show_server_error"></div>
                        <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
	<?php include('../assets/includes/script-links.php');?>


    <script type="text/javascript">
        $(document).ready(function()
        {
            //# register form submit js
            $("form[name=new_password_form_submit]").validate({
                errorElement: "span",
                errorClass: "help-inline",
                rules: {
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    }
                },
                submitHandler: function(form) 
                {
                    $.ajax({
                        type: "POST",
                        url:"../controller/register_controller.php",
                        data:$("form[name=new_password_form_submit]").serialize(),           
                        dataType:"JSON",
                        success: function(response) 
                        {
                            console.log(response);
                            if(response.success == false)
                            {
                                $("#show_server_error").html(response.message);
                            }
                            else if(response.success == true)
                            {
                                window.location.href = "login.php";
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>