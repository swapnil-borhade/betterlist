
$(document).ready(function ()
{
    // var isReqInprogress = false;
    $.validator.addMethod("emailExt", function(value, element, param) {
        return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
    },"Your email id is not correct format");

    $.validator.addMethod("mobileValidation",function(value, element) {
        return !/^\d{8}$|^\d{10}$/.test(value) ? false : true;
    },"Mobile number invalid");

    //# register form submit js
    $("form[name=register_form_submit]").validate({
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
            mobile: {
                required:true,
                minlength:10,
                maxlength: 10,
                mobileValidation : true,
            },
            email: {
                required:true,
                email:true,
                emailExt:true
            },
            password: {
                minlength: 8,
            },
            confirm_password: {
                minlength: 8,
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
                url:"../controller/register_controller.php",
                data:$("form[name=register_form_submit]").serialize(),           
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
                        window.location.href = "registerOTP.php";
                    }
                }
            });
        }
    });

    //# login form submit js
    $("form[name=login_form_submit]").validate({
        errorElement: "span",
        errorClass: "help-inline",
        rules: {
            email: {
                required:true,
                email:true,
                emailExt:true
            },
            password: {
                required:true,
            },
        },
        submitHandler: function(form) 
        {
            $.ajax({
                type: "POST",
                url:"../controller/register_controller.php",
                data:$("form[name=login_form_submit]").serialize(),           
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
                        window.location.href = "index.php";
                    }
                }
            });
        }
    });

     //# forgot_password form submit js
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
