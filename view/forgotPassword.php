<?php
?>
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
                    <h1>Forgot Password</h1>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="" method="post" name = "forgotpassword_email_from">
                        <label for="email" class="form-label">Email Id</label>
                        <input type="email" class="form-control" name ="email" id="email">
                        <input type="hidden" name="action" value="forgot_password_email">
                        <div id="show_server_error"></div>
                        <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php include('../assets/includes/script-links.php'); ?>

    <script>
        $(document).ready(function ()
        {
            //# register form submit js
            $("form[name=forgotpassword_email_from]").validate({
                    errorElement: "span",
                    errorClass: "help-inline",
                    rules: {
                        email: {
                            required:true,
                            email:true,
                            emailExt:true
                        }
                    },
                    submitHandler: function(form) 
                    {
                        $.ajax({
                            type: "POST",
                            url:"../controller/register_controller.php",
                            data:$("form[name=forgotpassword_email_from]").serialize(),           
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
                                    window.location.href = "forgotpasswordOTP.php";
                                }
                            }
                        });
                    }
                });
        });
    </script>
</body>
</html>