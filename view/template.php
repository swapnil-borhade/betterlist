<?php
session_start();

if(!isset($_SESSION["login"])) 
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
    <title>License</title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <style>
        #generate_btn{
            display: none;
        }
    </style>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>View Template</h1>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <?php include('../assets/includes/left-menu.php') ?> 
                </div>
                <div class="col-md-8" id="generate_btn">
                    <form action="#" name="generate_key_form"  method="POST">
                        <input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['userid'];?>">
                        <button type="submit" name="submit">Generate key</button>
                    </form>

                    <div>

                    </div>


                </div>
            </div>
        </div>
    </section>
    <?php include('../assets/includes/script-links.php');?>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $.ajax({
                type: "POST",
                url:"../controller/license_controller.php",
                data: {"userid": "<?php echo $_SESSION['userid'];?>","action": "getlicenseinfo"},           
                dataType:"JSON",
                success: function (response) 
                {
                    console.log(response);
                    if(response.data.btn  == false)
                    {
                        $("#generate_btn").hide();
                    }
                    else if(response.data.btn == true)
                    {
                        $("#generate_btn").show(); 
                    }
                }
            });

            //# profile_update form submit js
            $("form[name=generate_key_form]").validate({
                submitHandler: function(form) 
                {
                    $.ajax({
                        type: "POST",
                        url:"../controller/license_controller.php",
                        data:{"userid": $("#userid").val(),"action": "setlicenseinfo"},            
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
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: ['colvis']
            });
        });
    </script>
</body>
</html>