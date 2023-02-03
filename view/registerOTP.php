<?php
session_start();
echo  $_SESSION['OTP'];

if(isset($_POST['submit']))
{
    if($_POST['otp'] == $_SESSION['OTP'])
    {
        echo 'true';
    }
    else
    {
        echo "not match otp";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
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
    <!-- <script type="text/javascript">
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
                            elseif(response.success == true)
                            {
                                window.location.href = "<?php echo $folder_url;?>"+"test2.php?qid="+id;
                            }
                        }
                    });
                }
            });
        });
    </script> -->
</body>
</html>