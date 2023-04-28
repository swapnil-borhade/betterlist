<?php 
if(!isset($pdo))
    header("Location: ../../view/404.php");

include("functions.php");  

function insertSupport($pdo)
{
    $data_from_api = json_decode(file_get_contents("php://input"),true);
    $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';
    $topicname = isset($data_from_api["topicname"]) ? sanitize_data($data_from_api["topicname"]) : '';
    $website = isset($data_from_api["website"]) ? sanitize_data($data_from_api["website"]) : '';
    $subject = isset($data_from_api["subject"]) ? sanitize_data($data_from_api["subject"]) : '';
    $message = isset($data_from_api["message"]) ? sanitize_data($data_from_api["message"]) : '';
    $policy = isset($data_from_api["policy"]) ? sanitize_data($data_from_api["policy"]) : '';
    if(empty($topicname) || $topicname == '')
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Topic Name Can't be Empty.",
        );
    }
    elseif(!preg_match('/^[a-zA-Z\s]*$/', $topicname))
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Allow only alphabets.",
        );
    }
    elseif(empty($website) || $website == '')
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Website Can't be Empty.",
        );
    }
    elseif(!preg_match('/^[A-Za-z0-9\s\d=\'@$-,.()&#]*$/', $subject))
    {
        $response = array(
            "success" => false,
            "error" => true,
            "message" => "Allow only alphabets.",
        );
    }
    elseif (empty($message) || $message == '') 
    {
        $response = array(
            "success" => false,
            "message" => "Please enter message.",
        );
    }
    elseif ((empty($policy) || $policy == '') && $policy != 'policy') 
    {
        $response = array(
            "success" => false,
            "message" => "Please enter policy.",
        );
    }
    else
    {
        $data = array(
            "userid" => $userid,
            "topicname"=> $topicname,
            "website" => $website,
            "subject" => $subject,
            "message" => $message,
            "policy" => 1,
            "is_active" => 1
        );

        $data_check = array(
            "userid" => $userid,
            "is_active" => 1,
        );
        $emailcheck_sql ="SELECT id FROM `tbl_support` WHERE userid = :userid and is_active = :is_active";
        $stmtemailcheck = $pdo->prepare($emailcheck_sql);
        $stmtemailcheck->execute($data_check);
        $resultemailcheck = $stmtemailcheck->fetch(PDO::FETCH_ASSOC);
        if($resultemailcheck)
        {
            $response = array(
                "success" => false,
                "error" => true,
                "message" => "already request found.",
            );
            
        }
        else
        {
            $sql_insert ="INSERT INTO `tbl_support`(`userid`, `topicname`, `website`, `subject`, `message`, `policy`, `is_active`) VALUES (:userid,:topicname,:website,:subject,:message,:policy,:is_active)";
            $stmt_insert = $pdo->prepare($sql_insert);
            if($stmt_insert->execute($data))
            {
                $response = array(
                    "success" => true,
                    "message" => "Your request has been added.",
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
                    "message" => "something went to wrong.pls try letter",
                );
            }
        }
    }
    header("HTTP/1.1 200 OK");
    echo json_encode($response);
}
?>