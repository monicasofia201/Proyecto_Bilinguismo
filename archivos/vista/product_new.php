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


    <link rel="stylesheet" href="css/product_new.css">

    <title>Libros</title>
</head>





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
    <div class="layout__content">
            <div class="container content__page">
                <br />
                <h1 class="title">Biblioteca Bilingüismo<br>Nuevo libro B-Team-Language</h1>

                <form id="addBookForm" enctype="multipart/form-data">
                <div class="columns">
                    <div class="column is-half">
                        <div class="field">
                            <label class="label">Título:</label>
                            <div class="control">
                                <input class="input" type="text" name="titulo" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Prologo Corto del Libro:</label>
                            <div class="control">
                                <input class="input" type="text" name="prologo" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Autor:</label>
                            <div class="control">
                                <input class="input" type="text" name="autor" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Descripcion Corta Autor:</label>
                            <div class="control">
                                <input class="input" type="text" name="descripcion_autor" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Año de Publicación:</label>
                            <div class="control">
                                <input class="input" type="number" name="anio_publicacion" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Sección:</label>
                            <div class="control">
                                <select id="fk_seccion"></select>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Archivo PDF:</label>
                            <div class="control">
                                <input class="input" type="file" name="archivo_pdf" accept="application/pdf" required>
                            </div>
                        </div>
                        <input type="hidden" name="fk_publicante" value="1">
                        <!-- ID del usuario publicante -->
                        <div class="field">
                            <p class="control has-text-centered">
                                <button type="button" id="guardar_libro" class="create-button">Guardar</button>
                            </p>
                        </div>
                    </div> <!-- .column -->
                </div> <!-- .columns -->
            </form>
            </div>
        </div>

            <!-- Formulario para añadir un libro -->
            
        </div> <!-- .container -->
    </div> <!-- .content__page -->
</div> <!-- .layout -->













<script>
    function goBack() {
        window.history.back();
    }
</script>





<?php
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
} else {
    header("Location: ../../index.php");
}
?>