<?php
if($_SESSION['started'] != true){
	session_start();
	$_SESSION['started'] = true;
}

//DEBUG
/*echo "COOKIES:<br>";
echo "ID: ".$_COOKIE['id']."</br>";
echo "TOKEN: ".$_COOKIE['token']."</br>";
echo "SESSIION:</br>";
echo "LETGO: ".$_SESSION['letgo']."</br>";
if(isset($_SESSION['letgo'])){ $unverified = $_SESSION['letgo']; }else{ $unverified = $_COOKIE['id']; }
$verification = mysqli_fetch_array(mysqli_query($db, "SELECT id,token FROM clients WHERE id = $unverified"), MYSQLI_ASSOC);
echo "DB:</br>";
echo "ID: ".$verification['id']."</br>";
echo "TOKEN: ".$verification['token']."</br>";
*/

if(($_COOKIE['id'] == null || $_COOKIE['token'] == null) && $_SESSION['letgo'] == null){
	//Look for free ID
	$query = mysqli_query($db,"SELECT id FROM clients ORDER BY id DESC");
	$generatedid = mysqli_fetch_array($query, MYSQLI_ASSOC)['id'] + 1;
	//GENERATE TOKEN
	$numbers = "0123456789";
    $token = substr(str_shuffle($numbers),0,8);
	//INSERT NEW USER TO DB
	$clientip = $_SERVER['REMOTE_ADDR'];
	$command = "INSERT INTO clients (`id`, `ip`, `token`, `banned`, `count`, `day`, `last`, `created`) VALUES ($generatedid,\"$clientip\",$token,0,0,0,$timestamp, $timestamp)";
	mysqli_query($db, $command);
	//SET COOKIES
	setcookie("id", $generatedid, time()+86400*365);
    setcookie("token", $token, time()+86400*365);
	$_SESSION['letgo'] = $generatedid;
	$clientid = $generatedid;
}else{   //Updates LAST_VISIT in DB
	if(isset($_SESSION['letgo'])){ $unverified = $_SESSION['letgo']; }else{ $unverified = $_COOKIE['id']; }
	$verification = mysqli_fetch_array(mysqli_query($db, "SELECT id,token FROM clients WHERE id = $unverified"), MYSQLI_ASSOC);
	if(($verification['token'] == $_COOKIE['token'] && $_COOKIE['token'] != null) || isset($_SESSION['letgo'])){   //IF user set in cookie doesn t exist, logout
		if(isset($_SESSION['letgo'])){ $clientid = $_SESSION['letgo']; session_unset(); }else{ $clientid = $_COOKIE['id']; }
		mysqli_query($db, "UPDATE clients SET last = $timestamp WHERE id = $clientid");
	}else{
		//Look for free ID
		$query = mysqli_query($db,"SELECT id FROM clients ORDER BY id DESC");
		$generatedid = mysqli_fetch_array($query, MYSQLI_ASSOC)['id'] + 1;
		//GENERATE TOKEN
		$numbers = "0123456789";
		$token = substr(str_shuffle($numbers),0,8);
		//INSERT NEW USER TO DB
		$clientip = $_SERVER['REMOTE_ADDR'];
		mysqli_query($db, "INSERT INTO clients (`id`, `ip`, `token`, `banned`, `count`, `day`, `last`, `created`) VALUES ($generatedid,\"$clientip\",$token,0,0,0,$timestamp, $timestamp)");
		//SET COOKIES
		setcookie("id", $generatedid, time()+86400*365);
		setcookie("token", $token, time()+86400*365);
		$_SESSION['letgo'] = $generatedid;
	}
}
?>