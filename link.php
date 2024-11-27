<?php
require("theassets/config.php");
require("theassets/cookie.php");

$url = $_GET['url'];
if(filter_var($url, FILTER_VALIDATE_URL)){
	//Generate random
	function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars),0,$length);
	}
	$precheck = mysqli_query($db,"SELECT address FROM records WHERE absolute = \"$url\"");
	$prefetch = mysqli_fetch_array($precheck, MYSQLI_ASSOC);
	if($prefetch['address'] == null){
		do{
			$rand_address = "R".rand_string(3);
			$query = mysqli_query($db,"SELECT address FROM records WHERE address = \"$rand_address\"");
		}while(mysqli_fetch_array($query, MYSQLI_ASSOC)['address'] == $rand_address);
		$short = "https://zkra.tk/a".$rand_address;
		mysqli_query($db, "INSERT INTO records (`address`, `absolute`, `type`, `author`, `uploaded_at`, `timeout`, `last_shown`, `count`, `size`, `cropped`) VALUES (\"$rand_address\",\"$url\",2,\"$clientid\",$timestamp,999,$timestamp,0,0,0)");
		mysqli_query($db, "INSERT INTO `actions` (`time`, `userid`, `userip`, `action`, `data1`, `data2`) VALUES (\"$timestamp\", \"$clientid\", \"$clientip\", \"link_create\", \"$short\", \"$url\")");
		echo "<b>Link created: <a href=\"$short\" target=_blank>$short</a></b>";
	}else{
		$short = "https://zkra.tk/a".$prefetch['address'];
		echo "<b>Link created: <a href=\"$short\" target=_blank>$short</a></b>";
	}
}else{
	echo "<b>Invalid URL</b>";
}
?>