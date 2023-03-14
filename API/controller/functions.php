<?php

function sanitize_data($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

function timezone($time,$timezone)
{
    $objDateTime = new DateTime($time);
    $timezone_array["UTC"] = $objDateTime->format('Y-m-d H:i:s');

    $objDateTimeZone = new DateTimeZone($timezone);
    $objDateTime->setTimeZone($objDateTimeZone);

    $timezone_array["local_time"] = $objDateTime->format('Y-m-d H:i:s');
    return ($timezone_array);
}

function verifyEmail($pdo,$id,$email)
{
    $chars ="0123456789";
    $random_number = substr(str_shuffle( $chars ), 0, 4 );
	$to = $email;
	$subject = "FBSM OTP: ".$random_number;
	$message = "<html>
		<body>
			<p>The OTP for register is ".$random_number.".</p>
		</body>
	</html>";
	//### Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
	//### More headers
	$headers .= 'From: Hybreed <swapnil@hybreed.co>' . "\r\n";
	// mail($to,$subject,$message,$headers);
	return $random_number;
}

function forgotpasswordEmail($pdo,$id,$email)
{
    $chars ="0123456789";
    $random_number = substr(str_shuffle( $chars ), 0, 4 );
	$to = $email;
	$subject = "FBSM OTP: ".$random_number;
	$message = "<html>
		<body>
			<p>The OTP for reset password is ".$random_number.".</p>
		</body>
	</html>";
	//### Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
	//### More headers
	$headers .= 'From: Hybreed <swapnil@hybreed.co>' . "\r\n";
	// mail($to,$subject,$message,$headers);
	return $random_number;
}

function getlicensekey()
{
    $val_length = 16;
    $result = '';
    $module_length = 40;   // we use sha1, so module is 40 chars
    $steps = round(($val_length/$module_length) + 0.5);
    for( $i=0; $i<$steps; $i++ )
    {
        $result .= sha1(uniqid() . md5(rand()));
    }
    return substr($result, 0, $val_length);
}

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
    foreach ($empRecords as $row) {
        $id = $row['id'];
        $data[] = array(
            "id"=>$id,
            "website"=>$row['website'],
            "validity"=>$row['start_date']." - ".$row['end_date'],
            "action"=>"<a href='javascript:void(0)' data-id='$id' class='delete_popup btn' data-bs-toggle='modal' data-bs-target='#deletepopup' title='Delete'>Delete</a>"
        );
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