<?php 
//Config
$timestamp = time();

$db_name = "zkratka";
$db_host = "127.0.0.1";
$db_user = "zkratka_client";
$db_password = "GhTjTmRB9RdbN2KP";
//Connect
$db = mysqli_connect($db_host,$db_user,$db_password,$db_name);
// Check connection
if (mysqli_connect_errno()){ echo "Failed to connect to MySQL: " . mysqli_connect_error(); exit; }

//Funkce co vraci sekundy podle kodu timeout
function getSeconds($n){
	switch($n){
		case 10:
			return 600;
		break;
		case 24:
			return 3600;
		break;
		case 1:
			return 86400;
		break;
		case 7:
			return 604800;
		break;
		case 30:
			return 2592000;
		break;
		case 365:
			return 31536000;
		break;
		case 999:
			return false;
		break;
		default:
			return 604800;
		break;
	}
}

//Dotaz do databaze, predelani na normalni array
$rec_array = mysqli_query($db, 'SELECT * FROM records WHERE type=1');
$records = array();
while($x = mysqli_fetch_array($rec_array, MYSQLI_ASSOC)){ $records[] = $x; }

//Vlastni cyklus
foreach($records as $record){
	$y = getSeconds($record['timeout']);
	if($y != false){  //Pokud neni nastaveno navzdy
		if(($timestamp - $y) > $record['last_shown']){ //Pokud uz ubehla doba
			unlink(str_replace("https://i.zkra.tk", "/var/www/izkratk", $record['absolute'])); //Smazat na disku
			mysqli_query($db, "DELETE FROM records WHERE id=".$record['id']); //Smazat v DB
		}
	}
}
?>