<?php
// Incluir el archivo de conexión a la base de datos y otras configuraciones necesarias
include_once('../../include/conex.php');
header('Content-Type: text/html; charset=' . $charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn = Conectarse();
$sql="SELECT * FROM `chat`";

	$query = mysqli_query($conn,$sql);
// Verificar si existe una sesión activa con el id_userprofile
if (isset($_SESSION['id_userprofile'])){
?>



<!Doctype html>
<html lang="es">

<head>
    <?php
        include_once('cabecera.php');

        ?>


    <script type='text/javascript' src="../../herramientas/js/instructor.js"></script>
    <link rel="stylesheet" href="../../herramientas/css/instructor.css">
    <link rel="stylesheet" href="../../herramientas/css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>instructores de bilinguismo</title>

</head>


<body>
    <div class="layout">
        <!-- Menú de navegación -->
        <aside class="layout__aside">
            <div class="aside__user-info">
                <?php
                    // Incluir el menú de navegación
                    include_once('menu.php');
                    ?>
            </div>
        </aside>
        <!-- Contenido principal -->
        <div class="container layout__content">
            <div class="content__page">
                <h1 class="title">Multilingualism-Team<br> </h1>

                <div class="divider"></div>


                <div>
                    <div class="col-sm-10 ">


                        <div>
                            <div id="id_cardInstru" class="card-container"></div>
                        </div>

                    </div>

                    <?php
                        if ($_SESSION['id_rol'] == '2' || $_SESSION['id_rol'] == '3') {
                            include_once('chatpage.php');}
                        // Incluir el 
                        ?>
                </div>

</body>

<?php
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
} else {
    header("Location: ../../index.php");
}
?>