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

    $sql = "SELECT * from `tbl_users` where id = :id and is_active = :is_active";
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
                "message" => "User found.",
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
    $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';
    $firstname = isset($data_from_api["firstname"]) ? sanitize_data($data_from_api["firstname"]) : '';
    $lastname = isset($data_from_api["lastname"]) ? sanitize_data($data_from_api["lastname"]) : '';
    $mobile = isset($data_from_api["mobile"]) ? sanitize_data($data_from_api["mobile"]) : '';
    $company = isset($data_from_api["company"]) ? sanitize_data($data_from_api["company"]) : '';
    $address = isset($data_from_api["address"]) ? sanitize_data($data_from_api["address"]) : '';
    $city = isset($data_from_api["city"]) ? sanitize_data($data_from_api["city"]) : '';
    $country = isset($data_from_api["country"]) ? sanitize_data($data_from_api["country"]) : '';

    //<script>alert("test");</script>




    echo $address;
    exit();


    if(empty($firstname) || $firstname == '')
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "First Name Can't be Empty.",
        );
    }
    elseif(!preg_match('/^[a-zA-Z\s]*$/', $firstname))
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Allow only alphabets.",
        );
    }
    elseif(empty($lastname) || $lastname == '')
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Last Name Can't be Empty.",
        );
    }
    elseif(!preg_match('/^[a-zA-Z\s]*$/', $lastname))
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Allow only alphabets.",
        );
    }
    elseif(!preg_match('/^[0-9]*$/', $mobile))
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Only alpha numeric value.",
        );
    }
    
    elseif(!preg_match('/^[A-Za-z0-9\s\d=\'@$-,.()&#]*$/', $company))
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Allow only alphabets.",
        );
    }
    elseif (empty($country) || $country == '') 
    {
        $response = array(
            "success" => false,
            "message" => "Please enter country.",
        );
    }
    else
    {
        $data = array(
            "id" => $userid,
            "firstname"=> $firstname,
            "lastname" => $lastname,
            "mobile" => $mobile,
            "company" => $company,
            "address" => $address,
            "city" => $city,
            "country" => $country,
            "is_active" => 1
        );

        $data_check = array(
            "id" => $userid,
            "is_active" => 1,
        );
        $emailcheck_sql ="SELECT id FROM `tbl_users` WHERE id = :id and is_active = :is_active";
        $stmtemailcheck = $pdo->prepare($emailcheck_sql);
        $stmtemailcheck->execute($data_check);
        $resultemailcheck = $stmtemailcheck->fetch(PDO::FETCH_ASSOC);
        if($resultemailcheck)
        {
            $sql_update ="UPDATE `tbl_users` SET `firstname` = :firstname, `lastname` = :lastname, `mobile` = :mobile, `company` = :company, `address` = :address, `city` = :city, `country` = :country WHERE id = :id and is_active = :is_active";
            $stmt_update = $pdo->prepare($sql_update);
            if($stmt_update->execute($data))
            {
                $response = array(
                    "success" => true,
                    "message" => "Your profile details has been updated.",
                    "data" => array(
                        "userid" => $userid
                    )
                );
            }
            else
            {
                $response = array(
                    "success" => false,
                    "error" => true,
                    "message" => "user not found.",
                );
            }
        }
        else
        {
            $response = array(
                "success" => false,
                "error" => true,
                "message" => "user not found.",
            );
        }
    }
    header("HTTP/1.1 200 OK");
    echo json_encode($response);
}
?>