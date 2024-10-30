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

<head>
    <!-- Incluir enlaces a los archivos CSS y otros metadatos necesarios -->
    <?php include_once('cabecera.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Administrador</title>
    <script src="../../herramientas/js/biblioteca.js"></script>
    <link rel="stylesheet" href="../../herramientas/css/style.css">

    <link rel="stylesheet" href="../vista/css/category_new.css">

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
            <!-- Botón de Volver dentro del contenido principal -->
            <button type="button" class="btn nav-link nav-item-hover fixed-top-right" onclick="goBack()">
                <i class="fas fa-arrow-left fa-fw fa-lg"></i>
                <span class="nav-item">Volver</span>
            </button>

            <div class="pb-6 pt-6">
                <div class="is-fluid mb-6">
                    <h1 class="title">Biblioteca Bilingüismo<br>Secciones B-Team-Language</h1>
                </div>
                <div class="columns container">
                    <div class="column is-full">
                        <div class="field">
                            <label class="label">Nombre de la Sección a Ingresar:</label>
                            <div class="control">
                                <input class="input" type="text" id="nombre"
                                    pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" maxlength="50" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Idioma:</label>
                            <div class="control">
                                <select class="select" name="" id="id_idioma"></select>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Descripción:</label>
                            <div class="control">
                                <textarea class="textarea" id="descripcion"
                                    pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" maxlength="150"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <p class="control has-text-centered">
                        <button type="button" id="guardar_seccion" class="create-button">Guardar</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</div>








            
</body>

</html>
<?php
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
} else {
    header("Location: ../../index.php");
}
?>