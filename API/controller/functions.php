<?php

function sanitize_data($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

function curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS)
{
    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    return curl_setopt_array($curl, array(
        CURLOPT_URL => $CURLOPT_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_USERAGENT => $agent,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $CURLOPT_CUSTOMREQUEST,
        CURLOPT_POSTFIELDS =>$CURLOPT_POSTFIELDS,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic YWRtaW46YWRtaW4=',
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    return $response;
    curl_close($curl);
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

function getLoction()
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ip-api.com/json',
        CURLOPT_RETURNTRANSFER => true,
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response,true);
}

function verifyEmail($pdo,$id,$email)
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
	//mail($to,$subject,$message,$headers);
	return $random_number;
}

?>