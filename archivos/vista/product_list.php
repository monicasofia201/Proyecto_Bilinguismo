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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Administrador</title>
    <script src="../../herramientas/js/biblioteca.js"></script>
    <link rel="stylesheet" href="../../herramientas/css/style.css">

    
    <link rel="stylesheet" href="css/product_list.css">


    <title>Libros</title>
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
            <div class=" is-fluid mb-6"> <button type="button" class="btn nav-link nav-item-hover fixed-top-right"
                    onclick="goBack()">
                    <i class="fas fa-arrow-left fa-fw fa-lg"></i>
                    <span class="nav-item">Volver</span>
                </button>
                <script>
                function goBack() {
                    window.history.back();
                }
                </script>
                
                <h1 class="title">Biblioteca Bilingüismo<br>Secciones B-Team-Language </h1>
                <div class="divider"></div>

                <h1 class="title">Libros</h1>
                <h2 class="subtitle">Lista De libros</h2>
                <div id="libros"></div>
            </div>
        </div>


        
</body>

</html>
<?php
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
} else {
    header("Location: ../../index.php");
}
?>