<?php 
if(!isset($pdo))
    header("Location: ../../view/404.php");

include("functions.php");  

function getHomescreen($pdo)
{
    $data_from_api = json_decode(file_get_contents('php://input'), true);
    $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';

    $datacheck = array(
        'id' => $userid,
        'is_active' => 1
    );

    $sqlcheck = 'SELECT * FROM `tbl_users` WHERE `id`= :id and `is_active` = :is_active';
    $stmtcheck = $pdo->prepare($sqlcheck);
    $stmtcheck->execute($datacheck);
    $resultcheck = $stmtcheck->fetch(PDO::FETCH_ASSOC);
    if($resultcheck)
    {
        //print_r($resultcheck);
        $arr = array(
            "firstname" => $resultcheck['firstname'],
            "lastname" => $resultcheck['lastname'],
            "emailid" => $resultcheck['emailid'],
            "mobile" => $resultcheck['mobile'],
            "company" => $resultcheck['company'],
        );

        $response = array(
            "success" => true,
            "message" => "data found.",
            "data" => $arr
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
    header("HTTP/1.1 200 OK");
    echo json_encode($response);
}

?>