<?php
// Incluir el archivo de configuración de conexión a la base de datos
include_once('../../include/conex.php');

// Establecer el tipo de contenido a HTML con el charset especificado en la configuración
header('Content-Type: text/html; charset='.$charset);

// Iniciar la sesión con el nombre de sesión configurado
session_name($session_name);
session_start();
$conn=Conectarse();
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
    <script type='text/javascript' src="../../herramientas/js/GprogramaFormacion.js"></script>
    <link rel="stylesheet" href="../../herramientas/css/GprogramaFormacion.css">
    <link rel="stylesheet" href="../../herramientas/css/style.css">

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
        <div class="container layout__content">
            <div class="content__page">
                <br />
                <div class="navbar">
                    <?php 
                        include_once('panel.php')
                    ?>
                </div>
                <!-- NO 
                TOCAR -->
                <?php 
                    if($_SESSION['id_rol']=='2'){
                        echo'<a href="programar.php">Programacion</a>';
                    }
                    elseif($_SESSION['id_rol']=='3'){
                        echo'<a href="programar.php">Programar</a>
                        <div>
                            <button type="button" name="btn_Nuevo" id="ExportarProgramacion" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#">
                                <i class="fa-solid fa-cloud-arrow-down"></i> Exportar
                            </button>
                        </div>
                        ';
                    }
                ?>
                <div id="sin_contenido"></div>
            </div>
            <div id="pfB"></div>
        </div>
    </div>
    </div>

    <!-- MODAL 5 ASIGNACIONES [1]-->
    <div class="modal fade" id="AceptSolicitudModal" tabindex="-1" aria-labelledby="AceptSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">Gestionar Seguimiento Programa de Formacion</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="List_Gestion"></div>
                </div>
            </div>
        </div>
    </div>
    <!--MODAL4 Cancelar SOLI-->
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

    </div>
</body>
<?php
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
} else {
    header("Location: ../../index.php");
}
?>