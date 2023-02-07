<?php 
if(!isset($pdo))
    header("Location: ../../view/404.php");

include("functions.php");  

function insertUser($pdo)
{
    $data_from_api = json_decode(file_get_contents('php://input'), true);
    $firstname = isset($data_from_api["FirstName"]) ? sanitize_data($data_from_api["FirstName"]) : '';
    $lastname = isset($data_from_api["LastName"]) ? sanitize_data($data_from_api["LastName"]) : '';
    $mobile = isset($data_from_api["Mobile"]) ? sanitize_data($data_from_api["Mobile"]) : '';
    $emailid = isset($data_from_api["EmailId"]) ? $data_from_api["EmailId"] : '';
    $password = isset($data_from_api["Password"]) ? $data_from_api["Password"] : '';
    $company = sanitize_data($data_from_api["Company"]);
    $countryname = isset($data_from_api["CountryName"]) ? $data_from_api["CountryName"] : '';

    if(empty($firstname) || !preg_match('/^[a-zA-Z\s]*$/', $firstname) || $firstname == '')
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "First Name Can't be Empty.",
        );
    }
    elseif(empty($lastname) || !preg_match('/^[a-zA-Z\s]*$/', $lastname) || $lastname == '')
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Last Name Can't be Empty.",
        );
    }
    elseif(empty($mobile))
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Mobile Can't be Empty.",
        );
    }
    elseif(!filter_var($emailid ?? '', FILTER_VALIDATE_EMAIL)) 
	{
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Enter the valid email id.",
        );
	}
    elseif(empty($password) || strlen($password) <= 7 || !preg_match("#[a-z]+#",$password) || !preg_match("#[A-Z]+#",$password) || !preg_match("#[0-9]+#",$password) || !preg_match('/[\'£$%&*()}{@#~?><>,|=_+¬-]/',$password))
	{
        $response = array(
            "success" => false,
            "message" => "Your Password Must Contain At Least 8 Characters Least 1 Number 1 Capital Letter 1 Lowercase Letter 1 Special Character!",
        );
	}
    elseif(empty($countryname) || !preg_match('/^[a-zA-Z\s]*$/', $countryname) || $countryname == '')
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Country Name Can't be Empty.",
        );
    }
    else
    {
        $dataemailcheck = array(
            'emailid' => $emailid,
            'is_active' => 1
        );

        $sqlemailcheck = 'SELECT `id` FROM `users` WHERE `emailid`= :emailid and `is_active` = :is_active';
        $stmtemailcheck = $pdo->prepare($sqlemailcheck);
        $stmtemailcheck->execute($dataemailcheck);
        $resultemailcheck = $stmtemailcheck->fetch(PDO::FETCH_ASSOC);
        if(!$resultemailcheck)
        {
            $data = array(
                'firstname'=>$firstname,
                'lastname'=>$lastname,
                'mobile'=>$mobile,
                'emailid'=>$emailid,
                'password'=>password_hash($password, PASSWORD_BCRYPT,['cost' => 12]),
                'company'=>$company,
                'country'=>$countryname,
            );
            $sqlinsertuser = "INSERT INTO `users`(`firstname`, `lastname`, `mobile`, `emailid`, `password`, `company`, `country`) VALUES (:firstname,:lastname,:mobile,:emailid,:password,:company,:country)";
            $stmtinsertuser = $pdo->prepare($sqlinsertuser);
            if($stmtinsertuser->execute($data))
            {
                $id = $pdo->lastInsertId();
                $otp = verifyEmail($pdo,$id,$emailid);

                $response = array(
                    "success" => true,
                    "message" => "data inserted successfully.",
                    "data" => array(
                        'id'=>$id,
                        'emailid'=>$emailid, 
                        'otp'=>$otp
                    )
                );
            }
            else
            {
                $response = array(
                    "success" => false,
                    "error" => true,
                    "message" => "data not inserted successfully. pls try again later",
                );
            }
        }
        else
        {
            $response = array(
                "success" => false,
                "error" => true,
                "message" => "user already exists.",
            );
        }
    }
    header("HTTP/1.1 200 OK");
    echo json_encode($response);
}

function verifyUserfromemail($pdo)
{
    $data_from_api = json_decode(file_get_contents('php://input'), true);
    $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';
    $data = array(
        "userid" => $userid,
        "is_active" => 1
    );

    $sql = "SELECT `id` FROM `users` where `id` = :userid and `is_active` = :is_active";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // print_r($result);
    if($result)
    {
        $sql_update_verify = "UPDATE `users` SET `is_verify`= '1' WHERE id = :userid and is_active = :is_active ";
        $stmt_update_verify = $pdo->prepare($sql_update_verify);
        if($stmt_update_verify->execute($data))
        {
            $response = array(
                "success" => true,
                "message" => "User verify successfully.",
                "data" => NULL
            );
        }
        else
        {
            $response = array(
                "success" => false,
                "error" => true,
                "message" => "User verify not successfully. pls try again",
                "data" => NULL
            );
        }
    }
    else
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "User not found. pls try again",
            "data" => NULL
        );
    }
    echo json_encode($response);
}

function getUser($pdo)
{
    $data_from_api = json_decode(file_get_contents('php://input'), true);
    $emailid = isset($data_from_api["EmailId"]) ? $data_from_api["EmailId"] : '';
    $password = isset($data_from_api["Password"]) ? $data_from_api["Password"] : '';

    if(!filter_var($emailid ?? '', FILTER_VALIDATE_EMAIL)) 
	{
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Enter the valid email id.",
        );
	}
    elseif(empty($password))
	{
        $response = array(
            "success" => false,
            "message" => "Enter the Password.",
        );
	}
    else
    {
        $datacheck = array(
            'emailid' => $emailid,
            'is_active' => 1
        );

        $sql = "SELECT * from `users` where emailid = :emailid and is_active = :is_active";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($datacheck);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            //## if user verified then login Scenario 
            if($result['is_verify'] == 1) 
            {
                if (password_verify($password, $result['password']))
                {
                    $response = array(
                        "success" => true,
                        "message" => "User logged in successfully.",
                        "data" => array(
                            "is_verify" => true,
                            "login" => true,
                            "id" =>$result['id'],
                            "useremail" => $result['emailid']
                        )
                    );
                }
                else
                {
                    $response = array(
                        "success" => false,
                        "error" => true,
                        "message" => "User password in Wrong.",
                    );
                }
            }
            else
            {
                $response = array(
                    "success" => false,
                    "error" => true,
                    "message" => "User not verified.",
                );
            }
        }   
        else
        {
            $response = array(
                "success" => false,
                "error" => true,
                "message" => "User not found.",
            );
        }
    }
    echo json_encode($response);
}

function getforgetPassword($pdo)
{
    $data_from_api = json_decode(file_get_contents("php://input"),true);
    $email = isset($data_from_api["email"]) ? $data_from_api["email"] : '';

    if (!filter_var($email ?? '', FILTER_VALIDATE_EMAIL)) 
    {
        $response = array(
            "success" => false,
            "message" => "Please enter the valid email id.",
        );
    }
    else
    {
        $dataemailcheck = array(
            "useremail"=> $email,
            "is_active" => 1
        );

        $emailcheck_sql ="SELECT userid,passwordEditValue FROM `tbl_users` WHERE useremail = :useremail and is_active = :is_active";
        $stmtemailcheck = $pdo->prepare($emailcheck_sql);
        $stmtemailcheck->execute($dataemailcheck);
        $resultemailcheck = $stmtemailcheck->fetch(PDO::FETCH_ASSOC);
   
        if($resultemailcheck)
        {
            $mailget_otp = forgotpasswordEmail($pdo,$resultemailcheck['userid'],$email);

            $sql_update = "UPDATE `tbl_users` SET `passwordEditValue`= '".$mailget_otp."' WHERE userid = '".$resultemailcheck['userid']."'";
            $stmtupdate= $pdo->prepare($sql_update);
            $stmtupdate->execute();
            
            $response = array(
                "success" => true,
                "message" => "forgot password Email sent successfully.",
                "data" => array(
                    "userid" => (int)$resultemailcheck['userid'],
                    "useremail" => $email
                )
            );
        }
        else
        {
            $response = array(
                "success" => false,
                "message" => "no email in database.",
            );
        }
    }
    header("HTTP/1.1 200 OK");
    echo json_encode( $response);
}

function getforgetPasswordOTP($pdo)
{
    $data_from_api = json_decode(file_get_contents("php://input"),true);
    $email = isset($data_from_api["email"]) ? $data_from_api["email"] : '';
    $otp = isset($data_from_api["otp"]) ? $data_from_api["otp"] : '';

    if (!filter_var($email ?? '', FILTER_VALIDATE_EMAIL)) 
    {
        $response = array(
            "success" => false,
            "message" => "Please enter the valid email id.",
        );
    }
    else if (empty($otp)) 
    {
        $response = array(
            "success" => false,
            "message" => "Please enter the OTP.",
        );
    }
    else
    {
        $dataemailcheck = array(
            "useremail" => $email,
            "is_active" => 1
        );

        $emailcheck_sql ="SELECT userid,passwordEditValue FROM `tbl_users` WHERE useremail = :useremail and is_active = :is_active";
        $stmtemailcheck = $pdo->prepare($emailcheck_sql);
        $stmtemailcheck->execute($dataemailcheck);
        $resultemailcheck = $stmtemailcheck->fetch(PDO::FETCH_ASSOC);
   
        if($resultemailcheck)
        {
            if($resultemailcheck['passwordEditValue'] == $otp)
            {
                $response = array(
                    "success" => true,
                    "message" => "otp match.",
                    "data" => array(
                        "userid" => (int)$resultemailcheck['userid'],
                        "useremail" => $email,
                        "set_new_password_screen" => true,
                    )
                );
            }
            else
            {
                $response = array(
                    "success" => false,
                    "error" => true,
                    "message" => "otp not match. please check your email account.",
                );
            }
        }
        else
        {
            $response = array(
                "success" => false,
                "error" => true,
                "message" => "no email in database.",
            );
        }
    }
    header("HTTP/1.1 200 OK");
    echo json_encode( $response);
}

function setNewPassword($pdo,$userid)
{
    $data_from_api = json_decode(file_get_contents("php://input"),true);
    $newpassword = isset($data_from_api["newpassword"]) ? $data_from_api["newpassword"] : '';
    $confirmpassword = isset($data_from_api["confirmpassword"]) ? $data_from_api["confirmpassword"] : '';

    if(empty($newpassword) || strlen($newpassword) <= 7 || !preg_match("#[a-z]+#",$newpassword) || !preg_match("#[A-Z]+#",$newpassword) || !preg_match("#[0-9]+#",$newpassword) || !preg_match('/[\'£$%&*()}{@#~?><>,|=_+¬-]/',$newpassword))
	{
        $response = array(
            "success" => false,
            "message" => "Your Password Must Contain At Least 8 Characters Least 1 Number 1 Capital Letter 1 Lowercase Letter 1 Special Character!",
        );
	}
    elseif($newpassword !== $confirmpassword)
    {
        $response = array(
            "success" => false,
            "message" => "Your Confirm Password Must Same as Your New Password.",
        );
    }
    else
    {
        $data = array("userid"=> $userid);
        $datausercheck = array(
            "userpassword" => password_hash($newpassword, PASSWORD_BCRYPT,['cost' => 12]),
            "userid"=> $userid,
            "is_active" => 1
        );

        $usercheck_sql ="UPDATE `tbl_users` SET `userpassword`=:userpassword, `passwordEditValue`= 0 WHERE userid = :userid and is_active = :is_active";
        $stmtusercheck = $pdo->prepare($usercheck_sql);
        if($stmtusercheck->execute($datausercheck))
        {
            $response = array(
                "success" => true,
                "message" => "New Password Updated.",
            );
        }
        else
        {
            $response = array(
                "success" => false,
                "error" => true,
                "message" => "Something is wrong. Please try again after sometime.",
            );
        }
    }
    header("HTTP/1.1 200 OK");
    echo json_encode($response);
}
?>