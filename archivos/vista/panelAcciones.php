<?php
// Incluir el archivo de configuración de conexión a la base de datos
include_once('../../include/conex.php');

// Establecer el tipo de contenido a HTML con el charset especificado en la configuración
header('Content-Type: text/html; charset='.$charset);

// Iniciar la sesión con el nombre de sesión configurado
session_name($session_name);
session_start();

// Verificar si existe una sesión activa con el id_userprofile
if (isset($_SESSION['id_userprofile'])){
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Incluir enlaces a los archivos CSS y otros metadatos necesarios -->
    <?php include_once('cabecera.php'); ?>
    <link rel="stylesheet" href="../../herramientas/css/style.css">
    <title>Acciones Bili</title>
    <style>
    .notificacion2 {
        position: absolute;
        left: 85%;

    }

    ::selection {
        color: #fff;
        background: #fd7d1c;
    }
    </style>
</head>


<body>
    <div class="layout">
        <!-- Menú de navegación -->
        <aside class="layout__aside">
            <div class="aside__user-info">
                <?php include_once('menu.php'); ?>
            </div>
        </aside>
        <div class="notificacion2">
            <?php 
                if($_SESSION['id_rol']==2){
                    include_once('notificacion.php');
                }
            ?>
        </div>
        <div class="container layout__content">
            <div class=" content__page">
                <div id="contenido">
                    <div class=" pt-16 rounded-container ">
                        <h1 class="title">Panel De Acciones</h1>
                        <div class="divider"></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="navbar">
                                    <?php 
                                        include_once('panel.php')
                                        ?>


                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<?php
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
} else {
    header("Location: ../../index.php");
}
?>