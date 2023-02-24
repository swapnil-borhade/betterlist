<?php

use JetBrains\PhpStorm\ArrayShape;

if(!isset($pdo))
    header("Location: ../../view/404.php");

include("functions.php");  

function getLicense($pdo)
{
    $data_from_api = json_decode(file_get_contents('php://input'), true);
    $userid = isset($data_from_api["userid"]) ? $data_from_api["userid"] : '';
 
    $datacheck = array(
        'userid' => $userid,
        'is_active' => 1
    );

    $sql = "SELECT * from `users_key` where userid = :userid and is_active = :is_active";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($datacheck);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $today = date("Y-m-d H:i:s");

    if($result)
    {
        if($result['end_date'] >= $today)
        {
            $response = array(
                "success" => true,
                "message" => "User key found.",
                "data" => array(
                    "btn" =>false,
                    "key" => "disable generate key btn"
                )
            );
        }
        else
        {
            $response = array(
                "success" => true,
                "message" => "User key not found.",
                "data" => array(
                    "btn" =>true,
                    "key" => "enable generate key btn"
                )
            );
        }
    }   
    else
    {
        $response = array(
            "success" => true,
            "message" => "User key not found.",
            "data" => array(
                "btn" =>true,
                "key" => "enable generate key btn"
            )
        );
    }
    echo json_encode($response);
}

function setLicense($pdo)
{
    $data_from_api = json_decode(file_get_contents("php://input"),true);
    $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';
    $generated_key = isset($data_from_api["generated_key"]) ? sanitize_data($data_from_api["generated_key"]) : '';

    if (empty($userid) || $userid == '') 
    {
        $response = array(
            "success" => false,
            "message" => "Please enter userid.",
        );
    }
    elseif(empty($generated_key) || $generated_key == '') 
    {
        $response = array(
            "success" => false,
            "message" => "Please enter generated key.",
        );
    }
    else
    {
        $data_check = array(
            "id" => $userid,
            "is_active" => 1,
        );
        $usercheck_sql ="SELECT id FROM `users` WHERE id = :id and is_active = :is_active";
        $stmtusercheck = $pdo->prepare($usercheck_sql);
        $stmtusercheck->execute($data_check);
        $resultusercheck = $stmtusercheck->fetch(PDO::FETCH_ASSOC);
        if($resultusercheck)
        {
            $sql = "SELECT * from `users_key` where userid = :id and is_active = :is_active";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data_check);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $today = date("Y-m-d H:i:s");
            if($result)
            {
                if($result['end_date'] <= $today)
                {
                    $end_date = date('Y-m-d H:i:s',strtotime('+365 days',strtotime($today)));
                    $insert_data = array(
                        "userid" => $userid,
                        "unique_key" => $generated_key,
                        "start_date" => $today,
                        "end_date" => $end_date
                    );
                    $insert_sql = "INSERT INTO `users_key`(`userid`, `unique_key`, `start_date`, `end_date`) VALUES (:userid, :unique_key, :start_date, :end_date)";
                    $insert_stmt = $pdo->prepare($insert_sql);
                    if($insert_stmt->execute($insert_data))
                    {
                        $response = array(
                            "success" => true,
                            "message" => "user id inserted successfully.",
                            "data" => array(
                                "user_key" => $pdo->lastInsertId()
                            )
                        );
                    }
                    else
                    {
                        $response = array(
                            "success" => false,
                            "error" => true,
                            "message" => "user_key not inserted try again.",
                        );
                    }
                }
                else
                {
                    $response = array(
                        "success" => false,
                        "error" => true,
                        "message" => "already active user_key.",
                    );
                }
            }
            else
            {
                $end_date = date('Y-m-d H:i:s',strtotime('+365 days',strtotime($today)));
                $insert_data = array(
                    "userid" => $userid,
                    "unique_key" => $generated_key,
                    "start_date" => $today,
                    "end_date" => $end_date
                );
                $insert_sql = "INSERT INTO `users_key`(`userid`, `unique_key`, `start_date`, `end_date`) VALUES (:userid, :unique_key, :start_date, :end_date)";
                $insert_stmt = $pdo->prepare($insert_sql);
                if($insert_stmt->execute($insert_data))
                {
                    $response = array(
                        "success" => true,
                        "message" => "user id inserted successfully.",
                        "data" => array(
                            "user_key" => $pdo->lastInsertId()
                        )
                    );
                }
                else
                {
                    $response = array(
                        "success" => false,
                        "error" => true,
                        "message" => "user_key not inserted try again.",
                    );
                }
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
    echo json_encode( $response);
}
?>