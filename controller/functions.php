<?php


// ## code.hybclient server
//define('baseUrl', 'https://code.hybclient.com/betterlist/api/');

// ## localhost server
define('baseUrl', 'http://localhost/swapnil/work/betterlist/api/');


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
    $ipadr = get_client_ip();
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://www.geoplugin.net/json.gp?ip='.$ipadr,
        CURLOPT_RETURNTRANSFER => true,
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response,true);
}

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

function get_client_ip() {
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
    return $ipaddress;
}

?>