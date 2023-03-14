<?php
session_start();

include ('functions.php');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
switch ($action) {
    case 'getlicenseinfo' : getlicenseinfo(); break;
    case 'setlicenseinfo' : setlicenseinfo(); break;
    case 'deleteLicense' : deleteLicense(); break;
    default : header('Location: ../404.php'); 
}

function getlicenseinfo()
{
    $columnIndex = $_POST['order'][0]['column']; // Column index

    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'license.php/licenseinfo';
    $CURLOPT_CUSTOMREQUEST = 'GET';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.sanitize_data($_POST['userid']).'",
        "draw" : "'.$_POST['draw'].'",
        "row" : "'.$_POST['start'].'",
        "rowperpage" : "'.$_POST['length'].'",
        "columnName" : "'.$_POST['columns'][$columnIndex]['data'].'",
        "columnSortOrder" : "'.$_POST['order'][0]['dir'].'",
        "searchValue" : "'.$_POST['search']['value'].'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    echo $response;
}

function setlicenseinfo()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'license.php/licenseinfo';
    $CURLOPT_CUSTOMREQUEST = 'POST';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.sanitize_data($_POST['userid']).'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    echo $response;  
}

function deleteLicense()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'license.php/licenseinfo';
    $CURLOPT_CUSTOMREQUEST = 'DELETE';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.sanitize_data($_POST['userid']).'",
        "licenseid" : "'.sanitize_data($_POST['licenseid']).'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    echo $response;  
}

// function test()
// {
// }

function test($pdo)
{
    // Reading value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $searchArray = array();
    // Search
    $searchQuery = "";

    if($searchValue != '')
    {
        $searchQuery = " AND (id LIKE :id OR 
            website LIKE :website OR 
            start_date LIKE :start_date OR
            end_date LIKE :end_date)";

        $searchArray = array( 
            'id'=>"%$searchValue%",
            'website'=>"%$searchValue%",
            'start_date'=>"%$searchValue%",
            'end_date'=>"%$searchValue%"
        );
    }
    // Total number of records without filtering
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM `tbl_license` WHERE `is_active` = 1");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

    // Total number of records with filtering
    $stmt = $pdo->prepare("SELECT COUNT(*) AS allcount FROM `tbl_license` WHERE `is_active` = 1 ".$searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

    // Fetch records
    $stmt = $pdo->prepare("SELECT * FROM `tbl_license` WHERE `is_active` = 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

    // Bind values
    foreach ($searchArray as $key=>$search) {
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


?>