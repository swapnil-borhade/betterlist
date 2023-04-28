
$(document).ready(function ()
{
    //# methods
    $.validator.addMethod("emailExt", function(value, element, param) {
        return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
    },"Your email id is not correct format");

    $.validator.addMethod("mobileValidation",function(value, element) {
        //return !/^\d{8}$|^\d{10}$/.test(value) ? false : true;
        return this.optional(element) || /^\d{8,}$/.test(phone_number.replace(/\s/g, ""));
    },"Mobile number invalid");

    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z]+$/);
    },"Allow only alphabets.");

    $.validator.addMethod("Password", function(value) {
        return /^[A-Za-z0-9\d=\'£$%&*()}{@#~?><>,|=_+¬-]*$/.test(value) // consists of only these
            && /[A-Z]/.test(value) // has a upercase letter
            && /[a-z]/.test(value) // has a lowercase letter
            && /\d/.test(value) // has a digit
            && /[\'£$%&*()}{@#~?><>,|=_+¬-]/.test(value) // has a special characters
    },"Your Password Must Contain At Least 8 Characters Least 1 Number 1 Capital Letter 1 Lowercase Letter 1 Special Character!");

    $.validator.addMethod("Company", function(value) {
        return /^[A-Za-z0-9\s\d=\'@$-,.()&#]*$/.test(value) // consists of only these
    },"Company name invalid");
    


    //# register form submit js
    $("form[name=register_form_submit]").validate({
        errorElement: "span",
        errorClass: "help-inline",
        rules: {
            firstname: { 
                alpha:true,
                required:true,
                minlength:3
            },
            lastname: {
                alpha:true,
                required:true,
                minlength:3
            },
            email: {
                required:true,
                email:true,
                emailExt:true
            },
            company: {
                required:false,
                Company:false,
                minlength:3
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
                    if(response.success == false)
                    {
                        $("#show_server_error").html(response.message);
                    }
                    else if(response.success == true)
                    {
                        console.log(response.data.url);
                        //window.location.href = "thankyou.php";
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
                    if(response.success == false)
                    {
                        $("#show_server_error").html(response.message);
                    }
                    else if(response.success == true)
                    {
                        window.location.href = "dashboard.php";
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
                    if(response.success == false)
                    {
                        $("#show_server_error").html(response.message);
                    }
                    else if(response.success == true)
                    {
                        //window.location.href = "forgotpasswordthankyou.php";
                        console.log(response.data.url);
                    }
                }
            });
        }
    });

    //# new password form submit js
    $("form[name=new_password_form_submit]").validate({
        errorElement: "span",
        errorClass: "help-inline",
        rules: {
            password: {
                required: true,
                minlength: 8,
                Password:true
            },
            confirm_password: {
                required: true,
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

    //# profile_update form submit js
    $("form[name=profile_page]").validate({
        errorElement: "span",
        errorClass: "help-inline",
        rules: {
            firstname: { 
                alpha:true,
                required:true,
                minlength:3
            },
            lastname: {
                alpha:true,
                required:true,
                minlength:3
            },
            mobile: {
                required:false,
                //minlength: 10,
                //maxlength: 10,
                mobileValidation : true,
            },
            email: {
                required:true,
                email:true,
                emailExt:true
            },
            company: {
                required:false,
                Company:false,
                minlength:3
            },
            address: {
                required:false,
                minlength:3
            },
            city: {
                required:false,
                minlength:3
            },
            country: {
                required:true,
                minlength:3
            },
        },
        submitHandler: function(form) 
        {
            $.ajax({
                type: "POST",
                url:"../controller/profile_controller.php",
                data:$("form[name=profile_page]").serializeArray(),           
                dataType:"JSON",
                success: function(response) 
                {
                    if(response.success == false)
                    {
                        $("#show_server_error").html(response.message);
                    }
                    else if(response.success == true)
                    {
                        $("#show_server_error").html(response.message); 
                    }
                }
            });
        }
    });

    //# profile_update form submit js
    $("form[name=support_page]").validate({
        errorElement: "span",
        errorClass: "help-inline",
        rules: {
            topicname: { 
                alpha:true,
                required:true,
                minlength:3
            },
            website: {
                alpha:true,
                required:true,
                minlength:3
            },
            subject: {
                required:true,
                minlength:3
            },
            message: {
                required:true,
                minlength:3
            },
            policy: {
                required:true,
                minlength:1
            },
        },
        submitHandler: function(form) 
        {
            $.ajax({
                type: "POST",
                url:"../controller/support_controller.php",
                data:$("form[name=support_page]").serializeArray(),           
                dataType:"JSON",
                success: function(response) 
                {
                    if(response.success == false)
                    {
                        $("#show_server_error").html(response.message);
                    }
                    else if(response.success == true)
                    {
                        $("#show_server_error").html(response.message); 
                    }
                }
            });
        }
    });

    //# license page js
    var userid = $('#table_license').data('id');
    $('#table_license').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'../controller/license_controller.php',
            'type':'post',
            'data': {'userid': userid,'action': 'getlicenseinfo'},
            'dataType':'JSON',
        },
        'columns': [
            { data: 'id'},
            { data: 'website'},
            { data: 'license'},
            { data: 'start_date'},
            { data: 'end_date'}
            // { data: 'action'}
        ]
    });

    $(document).on('click', '#generate_key_btn', function() 
    {
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            url:"../controller/license_controller.php",
            data:{"userid": id,"action": "setlicenseinfo"},
            dataType:"JSON",
            success: function(response)
            {
                if(response.success == true)
                {
                    //window.location.href = "license.php";
                    $('#table_license').DataTable().ajax.reload();
                }
                if(response.success == false)
                {
                    window.alert(response.message);
                }
            }
        });
    });
});
