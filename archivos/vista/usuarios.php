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
    <script type='text/javascript' src="../../herramientas/js/usuarios.js"></script>
    <link rel="stylesheet" href="../../herramientas/css/usuarios.css">
    <link rel="stylesheet" href="../../herramientas/css/style.css">

    <title>Administrar Perfiles de Usuario</title>


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
        <div class="container layout__content">
            <div class="content__page">
                <div id="contenido">
                    <div class=" pt-16 rounded-container ">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="navbar">
                                    <?php 
                                        include_once('panel.php')
                                        ?>
                                </div>
                                <div class="card-body">
                                    <div class="navbar2 d-flex align-items-center">
                                        <div class="d-flex align-items-center mr-2">
                                            <input type='text' name='dato_txt' id='dato_txt' title='Dato a buscar'
                                                placeholder='Buscar...' class="form-control form-control-lg">
                                        </div>
                                        <?php
                                            if ($_SESSION['id_rol'] == 3) {
                                                echo '
                                                    <div>
                                                        <button type="button" name="btn_Buscar" id="btn_Buscar" class="btn btn-primary btn-lg">
                                                            <i class="fa fa-search-plus" aria-hidden="true"></i>
                                                        </button>
                                                    </div>'
                                                ;
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="button" name="btn_Nuevo" id="ExportarUsu" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#">
                                <i class="fa-solid fa-cloud-arrow-down"></i> Exportar
                                </button>
                            </div>
                            <div id="Busuarios"></div>
                            <div id="usuarios"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- MODAL GESTION DE PERMISOS -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="true" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">GESTION DE PERMISOS</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="id_permiso"></div>
                <div class="modal-footer">
                    <button type="button" class="close-button" data-bs-dismiss="modal" name="btnVolver"
                        id="btnVolver">Salir</button>
                    <input class="create-button" type="submit" id="actualizarPermisousu" value="Gestionar">
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