
$(document).ready(function ()
{
    //# methods
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
                        window.location.href = "thankyou.php";
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
                        window.location.href = "forgotpasswordthankyou.php";
                        //console.log(response.data);
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

    //# profile_update form submit js
    $("form[name=profile_page]").validate({
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
            company: {
                required:false,
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
                required:false,
                minlength:3
            },
        },
        submitHandler: function(form) 
        {
            $.ajax({
                type: "POST",
                url:"../controller/profile_controller.php",
                data:$("form[name=profile_page]").serialize(),           
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
