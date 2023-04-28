<?php

//#localhost
define('siteUrl', 'http://localhost/swapnil/work/betterlist/view/');

//# code.hybclient.com
// define('siteUrl', 'https://code.hybclient.com/betterlist/view/');

function sanitize_data($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

function encryp($id)
{
	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$encryption_iv = '1234567891011121';
	$encryption_key = "hybreed";
	$encryption = openssl_encrypt($id, $ciphering, $encryption_key, $options, $encryption_iv);
	return $encryption;
}

function decryp($id)
{
	$ciphering = "AES-128-CTR";
	$options = 0;
	$decryption_iv = '1234567891011121';
	$decryption_key = "hybreed";
	$decryption = openssl_decrypt($id, $ciphering, $decryption_key, $options, $decryption_iv);
	return $decryption;
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

function welcomeEmail($userid,$email,$paymenttype)
{
	$userid = encryp($userid);
    $time = encryp(time());
	$paymenttype = encryp($paymenttype);
	$url = siteUrl."confirmemail.php?id=$userid&time=$time&payment=$paymenttype";
	$to = $email;
	$subject = "Confirm Email.";

	$message = "<html>
		<head>
			<title>HTML email</title>
		</head>
		<body>
			<p>Verify your Email Id. <a href='".$url."' targel='_blank'>Click here</a> to Verify</p>
		</body>
	</html>";

	//### Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
	//### More headers
	$headers .= 'From: Hybreed <swapnil@hybreed.co>' . "\r\n";
	return $url;
	// if(mail($to,$subject,$message,$headers))
	// {
	// 	return $url;
	// }
}

function forgotpasswordEmail($id,$email)
{
	$userid = encryp($id);
    $time = encryp(time());
	$url = siteUrl."forgotpasswordemail.php?id=$userid&time=$time";
	$to = $email;
	$subject = "Forgot password Email.";

	$message = "<html>
		<head>
			<title>HTML email</title>
		</head>
		<body>
			<p>Verify your Email Id. <a href='".$url."' targel='_blank'>Click here</a> to Verify</p>
		</body>
	</html>";

	//### Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
	//### More headers
	$headers .= 'From: Hybreed <swapnil@hybreed.co>' . "\r\n";

	return $url;
	// if(mail($to,$subject,$message,$headers))
	// {
	// 	return $url;
	// }
	// mail($to,$subject,$message,$headers);
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

function getUrl()
{
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";   
    else  
        $url = "http://";   
        
    // Append the host(domain name, ip) to the URL.   
    $url.= $_SERVER['HTTP_HOST'];   
    
    // Append the requested resource location to the URL   
    $url.= $_SERVER['REQUEST_URI'];    
      
    return $url;
}


?>