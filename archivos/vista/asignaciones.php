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
<!Doctype html>
<html lang="es">

<head>
	<?php
        include_once('cabecera.php');
        include_once ('parametros_index.php');
    ?>
    <script type='text/javascript' src="../../herramientas/js/asignaciones.js"></script>
    <link rel="stylesheet" href="../../herramientas/css/asignaciones.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>asignaciones</title>
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
        <div class="notificacion2">
            <?php 
                if($_SESSION['id_rol']=='2'){
                    include_once('notificacion.php');
                }
            ?>
        </div>
        <!-- Contenido principal -->
        <div class="layout__content">
            <div class="container content__page">
                <br />
                <div class="navbar">
                    <?php 
                        include_once('panel.php')
                    ?>
                </div>
				<div id="tablecontenido"></div>
            </div>
        </div>
    </div>
    <!-- MODAL GESTION DE PERMISOS -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="true" data-bs-keyboard="true" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="">GESTION DE Estados</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                        <div id="asignado"></div>
                    
                </div>
            </div>
    </div>
    <!-- cancel modal -->
    <div class="modal fade" id="cancelSolicitudModal" data-bs-backdrop="true" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="cancelSolicitudLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Estas Seguro de Denegar la Solicitud?
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cancel"></div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- Ac -->
    <div class="modal fade" id="ListRaAsignModal" tabindex="-1" aria-labelledby="AceptSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id=""> Gestion de Respuesta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="form_Ra"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- rA -->
    <div class="modal fade" id="ListEcAsignModal" tabindex="-1" aria-labelledby="ListEcAsignLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id=""> Gestion de Respuesta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="form_Ec"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL 5 ASIGNACIONES [2]-->
    <div class="modal fade" id="AceptSolicitud2Modal" tabindex="-1" aria-labelledby="AceptSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id=""> </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="form_pf"></div>
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