<?php
if(!isset($pdo))
    header("Location: ../../view/404.php");

include("functions.php");  

function getLicense($pdo)
{
    $data_from_api = json_decode(file_get_contents('php://input'), true);
    $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';
    $draw = isset($data_from_api["draw"]) ? sanitize_data($data_from_api["draw"]) : '';
    $row = isset($data_from_api["row"]) ? sanitize_data($data_from_api["row"]) : '';
    $rowperpage = isset($data_from_api["rowperpage"]) ? sanitize_data($data_from_api["rowperpage"]) : '';
    $columnName = isset($data_from_api["columnName"]) ? sanitize_data($data_from_api["columnName"]) : '';
    $columnSortOrder = isset($data_from_api["columnSortOrder"]) ? sanitize_data($data_from_api["columnSortOrder"]) : '';
    $searchValue = isset($data_from_api["searchValue"]) ? sanitize_data($data_from_api["searchValue"]) : '';

    $datasearchArray = array();
    $searchQuery = "";

    if($searchValue != '')
    {
        $searchQuery = " AND (id LIKE :id OR 
            website LIKE :website OR 
            license_key LIKE :license_key OR
            start_date LIKE :start_date OR
            end_date LIKE :end_date)";

        $datasearchArray = array( 
            "userid" => $userid,
            "is_active" => 1,
            'id'=>"%$searchValue%",
            'website'=>"%$searchValue%",
            'license_key'=>"%$searchValue%",
            'start_date'=>"%$searchValue%",
            'end_date'=>"%$searchValue%"
        );
    }
    else
    {
        $datasearchArray = array( 
            "userid" => $userid,
            "is_active" => 1
        );
    }

    $data = array(
        "userid" => $userid,
        "is_active" => 1
    );

    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM `tbl_license` WHERE `userid` = :userid and `is_active` = :is_active");
    $stmt->execute($data);
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

    // Total number of records with filtering
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM `tbl_license` WHERE `userid` = :userid and `is_active` = :is_active ".$searchQuery);
    $stmt->execute($datasearchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

    // Fetch records
    $stmt = $pdo->prepare("SELECT * FROM `tbl_license` WHERE `userid` = :userid and `is_active` = :is_active ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

    // Bind values
    foreach ($datasearchArray as $key=>$search) {
        $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();
    $id = 1;
    foreach ($empRecords as $row) 
    {
        $data[] = array(
            "id"=>$id,
            "website"=>$row['website'],
            "license"=>$row['license_key'],
            "start_date"=>$row['start_date'],
            "end_date"=>$row['end_date'],
            //"action"=>"<a href='javascript:void(0)' data-id='$id' class='delete_popup btn' data-bs-toggle='modal' data-bs-target='#deletepopup' title='Delete'>Delete</a>"
        );
        $id++;
    }

    // Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );
    echo json_encode($response);
}

function setLicense($pdo)
{
    $data_from_api = json_decode(file_get_contents("php://input"),true);
    $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';

    if (empty($userid) || $userid == '')
    {
        $response = array(
            "success" => false,
            "message" => "Please enter userid.",
        );
    }
    else
    {
        $data_check = array(
            "id" => $userid,
            "is_active" => 1,
        );
        $usercheck_sql ="SELECT `id` FROM `tbl_users` WHERE id = :id and is_active = :is_active";
        $stmtusercheck = $pdo->prepare($usercheck_sql);
        $stmtusercheck->execute($data_check);
        $resultusercheck = $stmtusercheck->fetch(PDO::FETCH_ASSOC);
        if($resultusercheck)
        {
            $sql_license = "SELECT `id` from `tbl_license` where userid = :id and is_active = :is_active";
            $stmt_license = $pdo->prepare($sql_license);
            $stmt_license->execute($data_check);
            $count = $stmt_license->rowCount();
            $today = date("Y-m-d H:i:s");
            if($count < 5)
            {
                $sql_payment = "SELECT `end_date` from `tbl_payment` where userid = :id and is_active = :is_active";
                $stmt_payment = $pdo->prepare($sql_payment);
                $stmt_payment->execute($data_check);
                $result_payment = $stmt_payment->fetch(PDO::FETCH_ASSOC);
                $end_date = $result_payment['end_date'];
                $insert_data = array(
                    "userid" => $userid,
                    "license_key" => getlicensekey(),
                    "start_date" => $today,
                    "end_date" => $end_date
                );
                $insert_sql = "INSERT INTO `tbl_license`(`userid`, `license_key`, `start_date`, `end_date`) VALUES (:userid, :license_key, :start_date, :end_date)";
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
                        "message" => "user key not inserted try again.",
                    );
                }
            }
            else
            {
                $response = array(
                    "success" => false,
                    "error" => true,
                    "message" => "already 5 active user keys.",
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
    echo json_encode( $response);
}

function deleteLicense($pdo)
{
    $data_from_api = json_decode(file_get_contents("php://input"),true);
    $userid = isset($data_from_api["userid"]) ? sanitize_data($data_from_api["userid"]) : '';
    $licenseid = isset($data_from_api["licenseid"]) ? sanitize_data($data_from_api["licenseid"]) : '';
    if (empty($userid) || $userid == '')
    {
        $response = array(
            "success" => false,
            "message" => "Please enter userid.",
        );
    }
    elseif(empty($licenseid) || $licenseid == '')
    {
        $response = array(
            "success" => false,
            "message" => "Please enter licenseid.",
        );
    }
    else
    {
        $data_check = array(
            "id" => $licenseid,
            "userid" => $userid,
            "is_active" => 1,
        );
      
        $sql = "SELECT * from `tbl_license` where id = :id and userid = :userid and is_active = :is_active";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data_check);
        $result = $stmt->fetch();
        if($result)
        {
            $sql = "UPDATE `tbl_license` SET `is_active`= 0 WHERE id = :id and userid = :userid and is_active = :is_active";
            $stmt = $pdo->prepare($sql);
            if($stmt->execute($data_check))
            { 
                $response = array(
                    "success" => true,
                    "message" => "deleted Successfull.",
                );
            }
            else
            {
                $response = array(
                    "success" => false,
                    "message" => "somthing went wrong.",
                );
            }
        }
        else
        {
            $response = array(
                "success" => false,
                "message" => "user key not found.",
            );
        }
    }
    header("HTTP/1.1 200 OK");
    echo json_encode( $response);
}
?>