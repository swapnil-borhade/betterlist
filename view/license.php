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
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>License</h1>
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
                <div class="col-md-8">
                    <form action="#" name="generate_key_form"  method="POST">
                        <button type="button" id="generate_key_btn" data-id="<?php echo $_SESSION['userid'];?>" class="">Generate key</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container mt-5">
            <table id="table_license" data-id="<?php echo $_SESSION['userid']?>" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Website</th>
                        <th>License</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
            </table>
	    </div>
    </section>
    <?php include('../assets/includes/script-links.php');?>
</body>
</html>