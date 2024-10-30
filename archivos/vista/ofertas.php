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
    <script type='text/javascript' src="../../herramientas/js/ofertas.js"></script>
    <link rel="stylesheet" href="../../herramientas/css/solicitud.css">
    <link rel="stylesheet" href="../../herramientas/css/style.css">
</head>
<style>
.modal-content {
    font-size: 2.1rem;
    /* Aumenta el tamaño de la fuente para todo el contenido del modal */
}

.modal-header h1,
.modal-body label,
.modal-body input,
.modal-body textarea,
.modal-body select,
.modal-footer button,
.modal-footer input {
    font-size: 1.2rem;
    /* Ajusta el tamaño de la fuente para títulos y otros elementos específicos */
}

#tipo_soli .form-container h6,
#tipo_soli .form-container select,
#tipo_soli .form-container input,
#tipo_soli .form-container label {
    font-size: 1.5rem;
    /* Ajusta el tamaño de la fuente para los elementos específicos dentro del div #tipo_soli */
}
</style>

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
            <div class=" content__page">
                <br />
                <div class="row">
                    <div class="navbar">
                        <?php 
                            include_once('panel.php')
                        ?>
                    </div>
                </div>
                <i class="fa-solid fa-cloud-arrow-down"></i>
                <div id="oferta_curso"></div>
                <div id="solisB"></div>
            </div>
        </div>
    </div>
    </div>
    <!-- solicitud 5  -->
    <div class="modal fade" id="AceptSolicitud5Modal" tabindex="-1" aria-labelledby="AceptSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id=""> Evaluacion Por Competencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="Ecompetencia_form"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- instructormodal -->
    <div class="modal fade" id="AceptSolicitud4Modal" tabindex="-1" aria-labelledby="AceptSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id=""> Prototipo de Instructor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="Tjinstructor"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- [A]-->
    <div class="modal fade" id="AceptSolicitud3Modal" tabindex="-1" aria-labelledby="AceptSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">Manejo de Respuesta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="form_AA"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL DETALLE OFERTA CURSO -->
    <div class="modal fade" id="OfertaModal" tabindex="-1" aria-labelledby="OfertaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id=""> Oferta Curso</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="form_Of"></div>
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
                    <h1 class="modal-title fs-5" id=""> Prototipo de Noticia/Oferta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="noticia_creada"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL DETALLE SOLICITUD -->
    <div class="modal fade" id="detallesolicitud" tabindex="-1" aria-labelledby="detalleSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">Detalle SOLICITUD</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="datlleSolicitud"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL 5 ASIGNACIONES [1]-->
    <div class="modal fade" id="AceptSolicitudModal" tabindex="-1" aria-labelledby="AceptSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">GESTIONAR Estado SOLICITUD</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="form_asignaciones"></div>
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
            </div>
        </div>
    </div>
    <!--MODAL3 EDITAR SOLI-->
    <div class="modal fade" id="editSolicitudModal" tabindex="-1" aria-labelledby="editSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editSolicitudLabel">Editar Solicitud</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editableFields"></div>
                    <select id="responsable"></select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="close-button" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="create-button" id="btnGuardaCambios">Guardar
                        Cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!--MODAL2 COORDINACION-->
    <div class="modal fade" id="createTipoSolicitudModal" tabindex="-1" aria-labelledby="createTipoSolicitudLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTipoSolicitudLabel">Nuevo Tipo Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Asigna el nombre de tu Tipo de Solicitud</label>
                    <input type="text" id="nombre"> <br>
                    <label for="nombreTipoSolicitud">Asigna un rol a tu Tipo de Solicitud</label>
                    <div id="id_rol"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="close-button" data-bs-dismiss="modal >Volver</button>
                        <button type=" button" class="create-button" id="btnNewSoli">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal1 -->
    <div class="modal fade" id="staticBackdrop" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">GESTIONAR NUEVA SOLICITUD</h1>
                    <?php
                            if ($_SESSION['id_rol'] == 3) {
                                    echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTipoSolicitudModal">
                                        Crear Tipo Soli
                                        </button>';
                                    }
                                ?>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" onchange="MostrarTipo_soli()">
                    <div id="id_tiposolicitud"></div>
                </div>
                <div id="tipo_soli"></div>
                <div class="modal-footer">
                    <button type="button" class="close-button" data-bs-dismiss="modal" name="btnVolver"
                        id="btnVolver">Salir</button>
                    <input class="create-button" type="submit" name="btnEnviar" id="btnEnviar" value="Enviar">
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