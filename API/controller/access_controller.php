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
                'emailid'=>$emailid,
                'password'=>password_hash($password, PASSWORD_BCRYPT,['cost' => 12]),
                'company'=>$company,
                'country'=>$countryname,
            );

            $sqlinsertuser = "INSERT INTO `users`(`firstname`, `lastname`, `emailid`, `password`, `company`, `country`) VALUES (:firstname,:lastname,:emailid,:password,:company,:country)";
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
    $emailid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';
    
}

?>