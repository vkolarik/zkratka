<?php
require("theassets/config.php");
require("theassets/cookie.php");

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

$img_size = $_POST['upload_size'];
$absolutepath = "https://i.zkra.tk/shoots/20".Date('y')."/".Date('m')."/".Date('d')."/$rand_address.png";
mysqli_query($db, "INSERT INTO records (`address`, `absolute`, `type`, `author`, `uploaded_at`, `timeout`, `last_shown`, `count`, `size`, `cropped`) VALUES (\"$rand_address\",\"$absolutepath\",1,\"$clientid\",$timestamp,7,$timestamp,0,$img_size,0)");

//Save the image
$sourcePath = $_FILES['imgfile']['tmp_name'];       // Storing source path of the file in a variable
$targetPath = "/var/www/izkratk/shoots/20".Date('y')."/".Date('m')."/".Date('d')."/$rand_address.png"; // Target path where file is to be stored
move_uploaded_file($sourcePath,$targetPath) ;    // Moving Uploaded file
//LOG
mysqli_query($db, "INSERT INTO `actions` (`time`, `userid`, `userip`, `action`, `data1`, `data2`) VALUES (\"$timestamp\", \"$clientid\", \"$clientip\", \"upload_file\", \"https://zkra.tk/a$rand_address\", \"$targetPath\")");
header("Location: https://zkra.tk/a".$rand_address);
?>
