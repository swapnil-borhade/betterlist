<?php 
if(!isset($pdo))
    header("Location: ../../view/404.php");

include("functions.php");  

function getUser($pdo)
{
    $data_from_api = json_decode(file_get_contents('php://input'), true);
    $userid = isset($data_from_api["userid"]) ? $data_from_api["userid"] : '';
 
    $datacheck = array(
        'id' => $userid,
        'is_active' => 1
    );

    $sql = "SELECT * from `users` where id = :id and is_active = :is_active";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($datacheck);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result)
    {
        //## if user verified then login Scenario 
        if($result['is_verify'] == 1) 
        {
            $arr = array(
                "userid" => $result['id'],
                "firstname" => $result['firstname'],
                "lastname" => $result['lastname'],
                "emailid" => $result['emailid'],
                "mobile" => $result['mobile'],
                "company" => $result['company'],
                "address" => $result['address'],
                "city" => $result['city'],
                "country" => $result['country']
            );

            $response = array(
                "success" => true,
                "message" => "User not verified.",
                "data" => $arr
            );
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
    echo json_encode($response);
}

function updateUser($pdo)
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
        $emailcheck_sql ="SELECT id FROM `users` WHERE emailid = :emailid and is_active = :is_active";
        $stmtemailcheck = $pdo->prepare($emailcheck_sql);
        $stmtemailcheck->execute($dataemailcheck);
        $resultemailcheck = $stmtemailcheck->fetch(PDO::FETCH_ASSOC);
        if($resultemailcheck)
        {
            $mailget_otp = forgotpasswordEmail($pdo,$resultemailcheck['id'],$email);
            
            $response = array(
                "success" => true,
                "message" => "forgot password Email sent successfully.",
                "data" => array(
                    "id" => (int)$resultemailcheck['id'],
                    "email" => $email,
                    "otp" => $mailget_otp
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
?>