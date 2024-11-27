<?php
require("theassets/config.php");
require("theassets/cookie.php");

//Get the base-64 string from data
$filteredData=substr($_POST['img_val'], strpos($_POST['img_val'], ",")+1);

//Decode the string
$unencodedData=base64_decode($filteredData);

//HERE I WILL CHECK
//-------------------------------------------------

//Generate random
function rand_string( $length ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);
}
do{
	$rand_address = rand_string(4);
	$query = mysqli_query($db,"SELECT address WHERE address = \"$rand_address\"");
}while(mysqli_fetch_array($query, MYSQLI_ASSOC)['address'] == $rand_address);

$img_size = $_POST['size_val'];
$absolutepath = "https://i.zkra.tk/shoots/20".Date('y')."/".Date('m')."/".Date('d')."/$rand_address.png";
mysqli_query($db, "INSERT INTO records (`address`, `absolute`, `type`, `author`, `uploaded_at`, `timeout`, `last_shown`, `count`, `size`, `cropped`) VALUES (\"$rand_address\",\"$absolutepath\",1,\"$clientid\",$timestamp,7,$timestamp,0,$img_size,0)");

//Save the image
file_put_contents("/var/www/izkratk/shoots/20".Date('y')."/".Date('m')."/".Date('d')."/$rand_address.png", $unencodedData);
mysqli_query($db, "INSERT INTO `actions` (`time`, `userid`, `userip`, `action`, `data1`, `data2`) VALUES (\"$timestamp\", \"$clientid\", \"$clientip\", \"save_file\", \"https://zkra.tk/a$rand_address\", \"$absolutepath\")");
header("Location: https://zkra.tk/a".$rand_address);
?>
