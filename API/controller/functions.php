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
?>