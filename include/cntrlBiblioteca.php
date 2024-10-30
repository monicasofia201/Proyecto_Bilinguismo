<?php
include_once('conex.php');
header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn = Conectarse();

switch ($_REQUEST['action']) 
{
    case 'NewSeccion':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        
        $nombre = sanitize_input($_POST['nombre']);
        $descripcion = sanitize_input($_POST['descripcion']);
        $id_idioma = sanitize_input($_POST['id_idioma']);
        
        $query = "INSERT INTO secciones (id_idioma, nombre, descripcion) VALUES ('$id_idioma', '$nombre', '$descripcion')";
        
        if ($result = mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            $jTableResult['rst'] = "1";
            $jTableResult['ms'] = "Sección creada con éxito.";
        } else {
            mysqli_rollback($conn);
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al guardar.";
        }
        
        print json_encode($jTableResult);
    break;
    case 'NewBook':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
    
        $titulo = sanitize_input($_POST['titulo']);
        $prologo = sanitize_input($_POST['prologo']);
        $autor = sanitize_input($_POST['autor']);
        $descripcion_autor = sanitize_input($_POST['descripcion_autor']);
        $anio_publicacion = sanitize_input($_POST['anio_publicacion']);
        $fk_seccion = sanitize_input($_POST['fk_seccion']);
        
        // Obtener el id_userprofile de la sesión
        if (isset($_SESSION['id_userprofile'])) {
            $fk_publicante = $_SESSION['id_userprofile'];
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error: Usuario no autenticado.";
            print json_encode($jTableResult);
            return;
        }
        
        // Manejo del archivo PDF
        if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
            $pdf_tmp_name = $_FILES['archivo_pdf']['tmp_name'];
            $pdf_name = $_FILES['archivo_pdf']['name'];
            $pdf_folder = 'doc_usu/';
            
            // Verificar si el directorio existe, si no, crearlo
            if (!file_exists($pdf_folder)) {
                if (!mkdir($pdf_folder, 0755, true)) {
                    $jTableResult['rst'] = "0";
                    $jTableResult['ms'] = "Error al crear el directorio.";
                    print json_encode($jTableResult);
                    return;
                }
            }
    
            $pdf_path = $pdf_folder . basename($pdf_name);
    
            if (move_uploaded_file($pdf_tmp_name, $pdf_path)) {
                $query = "INSERT INTO libros (titulo, prologo, autor, descripcion_autor, anio_publicacion, fk_seccion, fk_publicante, archivo_pdf) VALUES 
                            ('$titulo', '$prologo', '$autor', '$descripcion_autor', $anio_publicacion, $fk_seccion, $fk_publicante, '$pdf_path')";
                
                if ($result = mysqli_query($conn, $query)) {
                    mysqli_commit($conn);
                    $jTableResult['rst'] = "1";
                    $jTableResult['ms'] = "Libro guardado con éxito";
                } else {
                    mysqli_rollback($conn);
                    $jTableResult['rst'] = "0";
                    $jTableResult['ms'] = "Error al guardar el libro.";
                }
            } else {
                $jTableResult['rst'] = "0";
                $jTableResult['ms'] = "Error al subir el archivo PDF.";
            }
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "No se recibió un archivo PDF válido.";
        }
    
        print json_encode($jTableResult);
    break;
    

}

function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}
?>
