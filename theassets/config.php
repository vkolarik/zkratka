<?php
$banned = array("8.8.8.8", "4.4.4.4");
if(in_array($_SERVER['REMOTE_ADDR'], $banned)){
   echo "<html><head><title>Banned</title></head><div align='center'><h1>Jsi zabanován :D</h1></div></html>";
   exit();
}

//DEFINED FOR FUTURE
$timestamp = $_SERVER['REQUEST_TIME'];
$clientip = $_SERVER['REMOTE_ADDR'];
$uri = $_SERVER['REQUEST_URI'];

//DATABASE
$db_name = "zkratka";
$db_host = "89.203.249.159:3306";
$db_user = "zkratka_client";
$db_password = "rYH0yTAfHd2vPmus";


$db = mysqli_connect($db_host,$db_user,$db_password,$db_name);

// Check connection
if (mysqli_connect_errno()){ echo "Failed to connect to MySQL: " . mysqli_connect_error(); exit; }

?>