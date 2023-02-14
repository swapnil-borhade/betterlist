<?php
session_start();

if(!isset($_SESSION["login"])) 
{
    header("Location:login.php");
}

include ('../controller/functions.php');
//#function.php function created.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Profile</h1>
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

                    <?php 
                    $api_response = json_decode(getuserinfo($_SESSION['userid']),true);
                    if($api_response['success'] == true)
                    {
                        $firstname = $api_response['data']['firstname']; 
                        $lastname = $api_response['data']['lastname']; 
                        $emailid = $api_response['data']['emailid']; 
                        $mobile = $api_response['data']['mobile']; 
                        $company = $api_response['data']['company']; 
                        $address = $api_response['data']['address']; 
                        $city = $api_response['data']['city']; 
                        $country = $api_response['data']['country']; ?>
                   
                        <form name="profile_page" method="post">
                            <div class="mb-3">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" name ="firstname" id="firstname" value="<?php echo $firstname;?>">
                            </div>
                            <div class="mb-3">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name ="lastname" id="lastname" value="<?php echo $lastname;?>">
                            </div>
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name ="mobile" id="mobile" value="<?php echo $mobile;?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email ID</label>
                                <input type="email" class="form-control" name ="email" id="email" value="<?php echo $emailid;?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" class="form-control" name="company" id="company" value="<?php echo $company;?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" id="address" value="<?php echo $address;?>">
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" name="city" id="city" value="<?php echo $city;?>">
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" name="country" id="country" value="<?php echo $country;?>">
                            </div>
                            <input type="hidden" name="userid" value="<?php echo $_SESSION['userid'];?>">
                            <input type="hidden" name="action" value="updateuser">
                            <div id="show_server_error"></div>
                            <button type="submit" name ="submit" class="btn btn-primary">Update User</button>
                        </form>
                        <?php }
                        else
                        { ?><h2>No data</h2>
                        <?php }?>
                </div>
            </div>
        </div>
    </section>
    <?php include('../assets/includes/script-links.php'); ?>
</body>
</html>