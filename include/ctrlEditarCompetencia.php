<?php
// Incluir el archivo de conexión a la base de datos y otras configuraciones necesarias
include_once('../../include/conex.php');
session_name($session_name);
session_start();
$conn = Conectarse();

// Verificar si hay una sesión activa
if (isset($_SESSION['id_userprofile'])) {
    $jTableResult = array();
    $jTableResult['rst'] = "";
    $jTableResult['ms'] = "";

    // Verificar si todas las claves necesarias están definidas en $_POST
    $requiredKeys = ['id_competencia', 'nombre'];
    $allKeysExist = true;

    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $_POST)) {
            $allKeysExist = false;
            break;
        }
    }

    if ($allKeysExist) {
        // Asignar valores de $_POST a variables
        $id_competencia = $_POST['id_competencia'];
        $nombre = $_POST['nombre'];

        // Consulta SQL para actualizar la competencia
        $query = "UPDATE competencia SET nombre = '$nombre' WHERE id_competencia = '$id_competencia'";

        // Ejecutar la consulta y verificar el resultado
        if (mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            $jTableResult['rst'] = "1";
            $jTableResult['ms'] = "Competencia actualizada con éxito.";
        } else {
            mysqli_rollback($conn);
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al actualizar la competencia: " . mysqli_error($conn);
        }
    } else {
        // Si faltan claves requeridas, maneja el error
        $jTableResult['rst'] = "0";
        $jTableResult['ms'] = "Error: Campos del formulario incompletos.";
    }

    // Devuelve el resultado como JSON
    echo json_encode($jTableResult);
} else {
    // Si no hay una sesión activa, redirigir al usuario a la página de inicio de sesión
    header("Location: ../../index.php");
}
?>

<?php
// Incluir el archivo de conexión a la base de datos y otras configuraciones necesarias
include_once('../../include/conex.php');
session_name($session_name);
session_start();
$conn = Conectarse();

// Verificar si hay una sesión activa
if (isset($_SESSION['id_userprofile'])) {
    $jTableResult = array();
    $jTableResult['rst'] = "";
    $jTableResult['ms'] = "";

    // Verificar si todas las claves necesarias están definidas en $_POST
    $requiredKeys = ['id_competencia', 'nombre'];
    $allKeysExist = true;

    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $_POST)) {
            $allKeysExist = false;
            break;
        }
    }

    if ($allKeysExist) {
        // Asignar valores de $_POST a variables
        $id_competencia = $_POST['id_competencia'];
        $nombre = $_POST['nombre'];

        // Consulta SQL para actualizar la competencia
        $query = "UPDATE competencia SET nombre = '$nombre' WHERE id_competencia = '$id_competencia'";

        // Ejecutar la consulta y verificar el resultado
        if (mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            $jTableResult['rst'] = "1";
            $jTableResult['ms'] = "Competencia actualizada con éxito.";
        } else {
            mysqli_rollback($conn);
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al actualizar la competencia: " . mysqli_error($conn);
        }
    } else {
        // Si faltan claves requeridas, maneja el error
        $jTableResult['rst'] = "0";
        $jTableResult['ms'] = "Error: Campos del formulario incompletos.";
    }

    // Devuelve el resultado como JSON
    echo json_encode($jTableResult);
} else {
    // Si no hay una sesión activa, redirigir al usuario a la página de inicio de sesión
    header("Location: ../../index.php");
}
?>
