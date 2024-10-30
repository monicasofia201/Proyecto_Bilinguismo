<?php
// Incluir el archivo de conexión a la base de datos y otras configuraciones necesarias
include_once('../../include/conex.php');
header('Content-Type: text/html; charset=' . $charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn = Conectarse();

// Verificar si hay una sesión activa
if (isset($_SESSION['id_userprofile'])) {
    // Consulta para obtener los datos del usuario usando una declaración preparada
    $query = "SELECT * FROM userprofile WHERE id_userprofile = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['id_userprofile']);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se ejecutó la consulta correctamente
    if ($resultado && $resultado->num_rows > 0) {
        // Recuperar los datos del usuario
        $fila = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Incluir enlaces a los archivos CSS y otros metadatos necesarios -->
    <?php include_once('cabecera.php'); ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario </title>
    <link rel="stylesheet" href="../../herramientas/css/perfil.css">
</head>

<body>
    <div class="layout">
        <!-- Menú de navegación -->
        <aside class="layout__aside">


        </aside>
        <!-- Contenido principal -->
        <div class="container layout__content">
            <div class="content__page">
                <div id="contenido">
                    <!-- Sección para mostrar y editar el perfil del usuario -->

                </div>
                <!-- Campos del formulario -->




            </div>

        </div>
    </div>


</body>
<!-- MODAL CAMBIO CONTRASEÑA -->


</html>

<?php
    } else {
        // Si hay un error en la consulta, imprimir el mensaje de error
        echo "Error al ejecutar la consulta: " . mysqli_error($conn);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
} else {
    // Si no hay una sesión activa, redirigir al usuario a la página de inicio de sesión
    header("Location: ../../index.php");
}
?>