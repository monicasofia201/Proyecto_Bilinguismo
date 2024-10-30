<?php
// Incluir el archivo de conexión a la base de datos y otras configuraciones necesarias
include_once('../../include/conex.php');
header('Content-Type: text/html; charset=' . $charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn = Conectarse();
if($_POST)
{
	$name = $_SESSION['usuLog'];
    $msg=$_POST['msg'];
    
	$sql="INSERT INTO `chat`(`name`, `message`) VALUES ('".$name."', '".$msg."')";

	$query = mysqli_query($conn,$sql);
	if($query)
	{
		header('Location: instructor.php');
	}
	else
	{
		echo "Algo salió mal";
	}
	
	}
?>