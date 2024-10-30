<?php
// include('config.php');
$charset="utf-8";
$session_name 	= "sesionBilinguismo";
function Conectarse(){
	$servername 	= "localhost";
	$db 			= "bilinguismo";
	$username 		= "root";
	$password 		= "";
	$conn = mysqli_connect($servername, $username, $password, $db);
	if (!$conn) {die("Error de Conexion: " . mysqli_connect_error());	}
	else		{  return $conn;											} 
}
?>

