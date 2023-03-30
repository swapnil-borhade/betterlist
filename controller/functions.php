<?php

// ## code.hybclient server
define('baseUrl', 'https://code.hybclient.com/betterlist/api/');

// ## localhost server
// define('baseUrl', 'http://localhost/swapnil/work/betterlist/api/');

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
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://www.geoplugin.net/json.gp?ip='.$ipaddress,
        CURLOPT_RETURNTRANSFER => true,
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response,true);
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

//# home screen data
function getHomeScreen()
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'home.php/homescreen';
    $CURLOPT_CUSTOMREQUEST = 'GET';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.decryp($_SESSION['userid']).'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    return $response;
}

//# profile page data
function getuserinfo($userid)
{
    $curl = curl_init();
    $CURLOPT_URL = baseUrl.'profile.php/userinfo';
    $CURLOPT_CUSTOMREQUEST = 'GET';
    $CURLOPT_POSTFIELDS = '{
        "userid" : "'.$userid.'"
    }';
    curl_call($curl,$CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS);
    $response = curl_exec($curl);
    return $response;
}
?>