<?php
// Incluir el archivo de conexión a la base de datos y otras configuraciones necesarias
include_once('../../include/conex.php');
session_name($session_name);
session_start();

// Verificar si hay una sesión activa
if (isset($_SESSION['id_userprofile'])) {
    $conn = Conectarse(); // Establecer conexión a la base de datos

    // Lógica para gestionar las observaciones de matrícula
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar qué acción se está realizando (editar, eliminar, etc.)
        if (isset($_POST['accion'])) {
            $accion = $_POST['accion'];

            if ($accion == 'eliminar') {
                if (isset($_POST['idobs'])) {
                    $idobs = mysqli_real_escape_string($conn, $_POST['idobs']);
                    // Query para eliminar la observación de matrícula
                    $query = "DELETE FROM matriculaobs WHERE idobs = $idobs";

                    if (mysqli_query($conn, $query)) {
                        echo "Observación eliminada correctamente.";
                    } else {
                        echo "Error al eliminar la observación: " . mysqli_error($conn);
                    }
                }
            }
            // Puedes agregar más lógica para otras acciones como editar, agregar, etc.
        }
    }

    // Consulta para obtener todas las observaciones de matrícula
    $query = "SELECT * FROM matriculaobs";
    $resultado = mysqli_query($conn, $query);

    // Verificar si se obtuvieron resultados
    if ($resultado && mysqli_num_rows($resultado) > 0) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Postulacion a Programa De Formacion Nuevo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../herramientas/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../herramientas/css/layout.css">
</head>
<body>
    <div class="layout">
        <!-- Menú de navegación -->
        <aside class="layout__aside">
            <div class="aside__user-info">
                <?php include_once('menu.php'); ?>
            </div>
            <div>
                <?php include_once('cabeceraMenu.php'); ?>
            </div>
        </aside>
        <div class="layout__content">
            <div class="content__page">
                <div class="container mt-5">
                    <h1 class="mb-4"> Postulacion a Programa De Formacion Nuevo</h1>
                    <div class="btn btn-primary mb-4">
                        <a href="GenerarPDF.php" style="color:#FFF; text-decoration:none;" target="_blank">Visualizar en PDF</a>
                    </div>
                    <div class="btn btn-primary mb-4">
                        <a href="DescargarPDF.php" style="color:#FFF; text-decoration:none;" target="_blank">Descargar PDF</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Alumno</th>
                                    <th>Código Alumno</th>
                                    <th>Código Matrícula</th>
                                    <th>Fecha</th>
                                    <th>Observación</th>
                                    <th>Programa de Formación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($fila = mysqli_fetch_assoc($resultado)) {
                                    echo "<tr>";
                                    echo "<td>{$fila['idobs']}</td>";
                                    echo "<td>{$fila['idalumno']}</td>";
                                    echo "<td>{$fila['codalumno']}</td>";
                                    echo "<td>{$fila['codmatri']}</td>";
                                    echo "<td>{$fila['fecha']}</td>";
                                    echo "<td>{$fila['obs']}</td>";
                                    echo "<td>{$fila['programaformacion']}</td>";
                                    echo '<td><button class="btn btn-danger eliminar" data-id="' . $fila['idobs'] . '">Eliminar</button></td>';
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts al final del cuerpo para una carga más rápida -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Manejar la eliminación de observaciones de matrícula
            $('.eliminar').click(function() {
                var idobs = $(this).data('id');
                if (confirm('¿Estás seguro de eliminar esta observación?')) {
                    $.post('', {accion: 'eliminar', idobs: idobs}, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });
        });
    </script>
</body>
</html>
<?php
        // Liberar el resultado
        mysqli_free_result($resultado);

        // Cerrar la conexión
        mysqli_close($conn);
    } else {
        echo "No hay matrículas observadas disponibles.";
    }
} else {
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
    header("Location: ../../index.php");
}
?>
