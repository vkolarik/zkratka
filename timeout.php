<?php
if($_GET['address'] != null && $_GET['value']){
	require("theassets/config.php");
	require("theassets/cookie.php");

	$verify = mysqli_fetch_array(mysqli_query($db, "SELECT author FROM records WHERE address = \"".$_GET['address']."\""), MYSQLI_ASSOC)['author'];
	if($verify != $clientid){ exit; }

	$allow = array(10, 24, 1, 7, 30, 365, 999);
	if (in_array ($_GET['value'], $allow)) {
		mysqli_query($db, "UPDATE records SET timeout = ".$_GET['value']." WHERE address = \"".$_GET['address']."\"");
	}
}
?>