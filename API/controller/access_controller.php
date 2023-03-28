<?php 
if(!isset($pdo))
    header("Location: ../../view/404.php");

include("functions.php");  

function insertUser($pdo)
{
    $data_from_api = json_decode(file_get_contents('php://input'), true);
    $firstname = isset($data_from_api["FirstName"]) ? sanitize_data($data_from_api["FirstName"]) : '';
    $lastname = isset($data_from_api["LastName"]) ? sanitize_data($data_from_api["LastName"]) : '';
    $emailid = isset($data_from_api["EmailId"]) ? $data_from_api["EmailId"] : '';
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
    elseif(!filter_var($emailid ?? '', FILTER_VALIDATE_EMAIL)) 
	{
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Enter the valid email id.",
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

        $sqlemailcheck = 'SELECT `id`,`is_verify` FROM `tbl_users` WHERE `emailid`= :emailid and `is_active` = :is_active';
        $stmtemailcheck = $pdo->prepare($sqlemailcheck);
        $stmtemailcheck->execute($dataemailcheck);
        $resultemailcheck = $stmtemailcheck->fetch(PDO::FETCH_ASSOC);
        if(!$resultemailcheck)
        {
            $data = array(
                'firstname'=>$firstname,
                'lastname'=>$lastname,
                'emailid'=>$emailid,
                'company'=>$company,
                'country'=>$countryname,
            );
            $sqlinsertuser = "INSERT INTO `tbl_users`(`firstname`, `lastname`, `emailid`, `company`, `country`) VALUES (:firstname,:lastname,:emailid,:company,:country)";
            $stmtinsertuser = $pdo->prepare($sqlinsertuser);
            if($stmtinsertuser->execute($data))
            {
                $id = $pdo->lastInsertId();
                $url = welcomeEmail($id,$emailid);
                $response = array(
                    "success" => true,
                    "message" => "verification link send on email.",
                    "data" => array(
                        'id'=>$id,
                        'emailid'=>$emailid,
                        'url'=>$url
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
        elseif($resultemailcheck['is_verify'] == 0)
        {
            $data_update = array(
                'id' => $resultemailcheck['id'],
                'firstname'=>$firstname,
                'lastname'=>$lastname,
                'emailid'=>$emailid,
                'company'=>$company,
                'country'=>$countryname,
            );

            $sqlupdateuser = "UPDATE `tbl_users` SET `firstname`=:firstname,`lastname`=:lastname,`emailid`=:emailid,`company`=:company,`country`=:country WHERE `id`=:id";
            $stmtinsertuser = $pdo->prepare($sqlupdateuser);
            if($stmtinsertuser->execute($data_update))
            {
                $id = $data_update['id'];
                $url = welcomeEmail($id,$emailid);
                $response = array(
                    "success" => true,
                    "message" => "verification link send on email.",
                    "data" => array(
                        'id'=>$id,
                        'emailid'=>$emailid,
                        'url'=>$url
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
                "data" => array(
                    'userid'=> $resultemailcheck['id']
                )
            );
        }
    }
    header("HTTP/1.1 200 OK");
    echo json_encode($response);
}

// function verifyUserfromemail($pdo)
// {
//     $data_from_api = json_decode(file_get_contents('php://input'), true);
//     $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';
//     $data = array(
//         "userid" => $userid,
//         "is_active" => 1
//     );

//     $sql = "SELECT `id` FROM `tbl_users` where `id` = :userid and `is_active` = :is_active";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute($data);
//     $result = $stmt->fetch(PDO::FETCH_ASSOC);
//     $today = date("Y-m-d H:i:s");
//     // print_r($result);
//     if($result)
//     {
//         $sql_update_verify = "UPDATE `tbl_users` SET `is_verify`= '1' WHERE id = :userid and is_active = :is_active ";
//         $stmt_update_verify = $pdo->prepare($sql_update_verify);
//         if($stmt_update_verify->execute($data))
//         {
//             // # here add payment table to 1 year data
//             $end_date = date('Y-m-d H:i:s',strtotime('+365 days',strtotime($today)));
//             $insert_data = array(
//                 "userid" => $userid,
//                 "start_date" => $today,
//                 "end_date" => $end_date
//             );
//             $insert_sql = "INSERT INTO `tbl_payment`(`userid`, `start_date`, `end_date`) VALUES (:userid, :start_date, :end_date)";
//             $insert_stmt = $pdo->prepare($insert_sql);
//             if($insert_stmt->execute($insert_data))
//             {
//                 $insert_data_licences = array(
//                     "userid" => $userid,
//                     "license_key" => getlicensekey(),
//                     "start_date" => $today,
//                     "end_date" => $end_date
//                 );
//                 $insert_sql_licences = "INSERT INTO `tbl_license`(`userid`, `license_key`, `start_date`, `end_date`) VALUES (:userid, :license_key, :start_date, :end_date)";
//                 $insert_stmt_licences = $pdo->prepare($insert_sql_licences);
//                 $insert_stmt_licences->execute($insert_data_licences);
//             }
//             $response = array(
//                 "success" => true,
//                 "message" => "User verify successfully.",
//                 "data" => NULL
//             );
//         }
//         else
//         {
//             $response = array(
//                 "success" => false,
//                 "error" => true,
//                 "message" => "User verify not successfully. pls try again",
//                 "data" => NULL
//             );
//         }
//     }
//     else
//     {
//         $response = array(
//             "success" => false,
//             "error" => true,
//             "message" => "User not found. pls try again",
//             "data" => NULL
//         );
//     }
//     echo json_encode($response);
// }

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
        $sql = "SELECT * from `tbl_users` where emailid = :emailid and is_active = :is_active";
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
                    "message" => "User not verified. plz register again",
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
    $email = isset($data_from_api["EmailId"]) ? $data_from_api["EmailId"] : '';
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
            "emailid"=> $email,
            "is_active" => 1
        );
        $emailcheck_sql ="SELECT id FROM `tbl_users` WHERE emailid = :emailid and is_active = :is_active";
        $stmtemailcheck = $pdo->prepare($emailcheck_sql);
        $stmtemailcheck->execute($dataemailcheck);
        $resultemailcheck = $stmtemailcheck->fetch(PDO::FETCH_ASSOC);

        if($resultemailcheck)
        {
            $url = forgotpasswordEmail($resultemailcheck['id'],$email);
            $response = array(
                "success" => true,
                "message" => "forgot password Email sent successfully.",
                "data" => array(
                    "id" => (int)$resultemailcheck['id'],
                    "email" => $email,
                    'url' => $url
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

function setnewpassword($pdo)
{
    $data_from_api = json_decode(file_get_contents("php://input"),true);
    $userid = isset($data_from_api["userid"]) ? $data_from_api["userid"] : '';
    $newpassword = isset($data_from_api["newpassword"]) ? $data_from_api["newpassword"] : '';
    $confirmpassword = isset($data_from_api["confirmpassword"]) ? $data_from_api["confirmpassword"] : '';
    if(empty($userid) || $userid == '')
    {
        $response = array(
            "success" => false,
            "message" => "can't empty id",
        );
    }
    elseif(empty($newpassword) || strlen($newpassword) <= 7 || !preg_match("#[a-z]+#",$newpassword) || !preg_match("#[A-Z]+#",$newpassword) || !preg_match("#[0-9]+#",$newpassword) || !preg_match('/[\'£$%&*()}{@#~?><>,|=_+¬-]/',$newpassword))
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
        $datausercheck = array(
            "userpassword" => password_hash($newpassword, PASSWORD_BCRYPT,['cost' => 12]),
            "is_verify" => 1,
            "userid"=> $userid,
            "is_active" => 1
        );
        $usercheck_sql ="UPDATE `tbl_users` SET `password`=:userpassword,`is_verify`=:is_verify WHERE id = :userid and is_active = :is_active";
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