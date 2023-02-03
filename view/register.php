<?php
include ('../api/controller/functions.php');
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Register </title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
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
                    <form name="form_submit" method="post">
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
                        <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src= "../assets/js/jquery-3.6.3.js"></script>
    <script src= "../assets/js/popper.min.js"></script>
    <script src= "../assets/js/bootstrap.min.js"></script>
    <script src= "../assets/js/jquery.validate.min.js"></script>
    <script src= "../assets/js/additional-methods.min.js"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.6.3.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script> -->
    <script type="text/javascript">
        $(document).ready(function ()
        {
            // var isReqInprogress = false;
            $.validator.addMethod("emailExt", function(value, element, param) {
                return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
            },"Your email id is not correct format");

            $.validator.addMethod("mobileValidation",function(value, element) {
                return !/^\d{8}$|^\d{10}$/.test(value) ? false : true;
            },"Mobile number invalid");

            $("form[name=form_submit]").validate({
                errorElement: "span",
                errorClass: "help-inline",
                rules: {
                    firstname: { 
                        required:true,
                        minlength:3
                    },
                    lastname: {
                        required:true,
                        minlength:3
                    },
                    email: {
                        required:true,
                        email:true,
                        emailExt:true
                    },
                    password: {
                        minlength: 5,
                    },
                    confirm_password: {
                        minlength: 5,
                        equalTo: "#password"
                    },
                    company: {
                        required:false,
                        minlength:3
                    },
                    mobile: {
                        required:true,
                        minlength:10,
                        maxlength:10,
                        number:true
                    },
                },
                submitHandler: function(form) 
                {
                    $.ajax({
                        type: "POST",
                        url:"register_controller.php",
                        data:$("form[name=form_submit]").serialize(),           
                        dataType:"JSON",
                        success: function(response) 
                        {
                            console.log(response);
                            if(response.success == false)
                            {
                                alert ("error");
                            }
                            else if(response.success == true)
                            {
                                window.location.href = "registerOTP.php";
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>