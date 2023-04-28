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
    <title>Support</title>
    <?php include('../assets/includes/header-links.php');?>
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Support</h1>
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
                    <form name="support_page" method="post">
                        <div class="mb-3">
                            <label for="topicname" class="form-label">Topic name</label>
                            <input type="text" class="form-control" name ="topicname" id="topicname" value="">
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="text" class="form-control" name ="website" id="website" value="">
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" name ="subject" id="subject" value="">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea rows="3" class="form-control" name="message" id="message"></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="checkbox" name="policy" id="policy" value="policy">
                            <label for="policy" class="form-label">By submitting this form, I agree to the Support Policy</label>
                        </div>
                        <input type="hidden" name="userid" value="<?php echo $_SESSION['userid'];?>">
                        <input type="hidden" name="action" value="support">
                        <div id="show_server_error"></div>
                        <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
                    </form>	
                </div>
            </div>
        </div>
    </section>
    <?php include('../assets/includes/script-links.php'); ?>
</body>
</html>