<?php

// ## localhost
$hostname = "localhost";
$username = "root";
$password = "";
$db="better_list";

// ## code.hybclinet
// $hostname = "localhost";
// $username = "u886461235_betterlist";
// $password = "Pass#@123";
// $db="u886461235_betterlist";

// ## better list DATAbase 
// $hostname = "localhost";
// $username = "";
// $password = ""; 
// $db = "";

$charset = 'utf8mb4';
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$hostname;dbname=$db;charset=$charset";
try 
{
    $pdo = new PDO($dsn, $username, $password, $options);
} 
catch (PDOException $e) 
{
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}


?>