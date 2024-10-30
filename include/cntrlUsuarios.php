<?php
//https://getbootstrap.com/docs/5.0/components/modal/
include_once('conex.php');
header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn=Conectarse();
function obtenerUsuarios() {
    $conn = Conectarse();
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Consulta a la tabla userprofile
    $sql = "SELECT u.nombre, u.apellido, u.correo, pbl.nombre_poblacion FROM userprofile u LEFT JOIN poblacion pbl ON u.cod_poblacion = pbl.cod_poblacion";
    $resultado = mysqli_query($conn, $sql);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conn));
    }

    $usuarios = array();
    while ($row = mysqli_fetch_assoc($resultado)) {
        $usuarios[] = $row;
    }

    // Cierra la conexión
    mysqli_close($conn);

    return $usuarios;
}
switch ($_REQUEST['action']) 
{
    case 'buscarUsuario':
        $jTableResult = array();
        $jTableResult['rstl']="";
        $jTableResult['msj']="";
        $jTableResult['listaUsu']='
            <div class="container">
                        
                        <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Identificacion</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Permisos</th>
                        </tr>
                    </thead>
                    <tbody>';
            $query="SELECT id_userprofile, nombre, nombre_dos, correo, apellido, numeroiden, id_estado FROM userprofile WHERE nombre ='".$_POST['dato_txt']."' OR
                        apellido = '".$_POST['dato_txt']."' OR correo = '".$_POST['dato_txt']."' OR numeroiden ='".$_POST['dato_txt']."' OR nombre_dos ='".$_POST['dato_txt']."'";
            $resultado = mysqli_query($conn, $query);
            $numero = mysqli_num_rows($resultado);
            if($numero==0){
                $jTableResult['listaUsu']="<thead><tr><th scope='col'>&nbsp;&nbsp&nbsp;&nbsp;No existen coincidencias.</th></tr></thead>";
                $jTableResult['msj']= "NO EXISTEN DATOS.";
                $jTableResult['rslt']= "0";						
            }else{
                while($registro = mysqli_fetch_array($resultado)){
                    $jTableResult['listaUsu'].="<tr>";
                    $jTableResult['listaUsu'].="
                                                <td>".$registro['id_userprofile']."</td>
                                                <td>".$registro['nombre']."</td>
                                                <td>".$registro['apellido']."</td>
                                                <td>".$registro['numeroiden']."</td>
                                                <td>".$registro['correo']."</td>
                                                <td>".$registro['id_estado']."</td>
                                                <td><button id='btn_permiso'type='button'class='btn btn-success' data-id='".$registro['id_userprofile']."' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Gestionar</button></td>
                                                ";
                    $jTableResult['listaUsu'].="</tr>";
                }
                $jTableResult['listaUsu'].="</div>
                </div>";
                $jTableResult['msj']= "";
                $jTableResult['rslt']= "1";						
            }
            
        print json_encode($jTableResult);
    break;
    case 'Usuarios':
        $jTableResult = array();
        $jTableResult['rstl']="";
        $jTableResult['msj']="";
        $jTableResult['Usu']='
            <div class="container table-responsive">
                        
                        <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Identificacion</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Permisos</th>
                        </tr>
                    </thead>
                    <tbody>';
            $query="SELECT u.id_userprofile, u.nombre, u.correo, u.apellido, u.numeroiden, e.nombre AS nom_estado FROM userprofile u JOIN estado e ON u.id_estado = e.id_estado WHERE u.id_estado=1";
            $resultado = mysqli_query($conn, $query);
            $numero = mysqli_num_rows($resultado);
            if($numero==0){
                $jTableResult['Usu']="<thead><tr><th scope='col'>&nbsp;&nbsp&nbsp;&nbsp;No Existen Usuarios Activos</th></tr></thead>";
                $jTableResult['msj']= "NO EXISTEN DATOS.";
                $jTableResult['rslt']= "0";						
            }else{
                while($registro = mysqli_fetch_array($resultado)){
                    $jTableResult['rslt']= "1";
                    $jTableResult['Usu'].="<tr>";
                    $jTableResult['Usu'].="
                                                <td>".$registro['id_userprofile']."</td>
                                                <td>".$registro['nombre']."</td>
                                                <td>".$registro['apellido']."</td>
                                                <td>".$registro['numeroiden']."</td>
                                                <td>".$registro['correo']."</td>
                                                <td>".$registro['nom_estado']."</td>
                                                <td><button id='btn_permiso'type='button'class='btn btn-success' data-id='".$registro['id_userprofile']."' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Gestionar</button></td>
                                                ";
                    $jTableResult['Usu'].="</tr>";
                }
                $jTableResult['Usu'].="</div>
                </div>";
                $jTableResult['msj']= "";
            }
            
        print json_encode($jTableResult);
    break;
    case 'permisoUsuario':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $jTableResult['listaPermiso'] = "";
        $id_userprofile = mysqli_real_escape_string($conn, $_POST['id_userprofile']);
        
        $query = "SELECT 
                    up.id_userprofile, 
                    up.nombre AS nom_usuario, 
                    up.nombre_dos, 
                    up.correo, 
                    up.apellido, 
                    up.numeroiden, 
                    up.id_estado, 
                    up.id_rol, 
                    r.nombre AS nom_rol, 
                    e.nombre AS nom_estado, 
                    td.nombre AS nom_doc
                    FROM 
                        userprofile up
                    LEFT JOIN 
                        tipodocumento td ON up.id_doc = td.id_doc
                    LEFT JOIN 
                        rol r ON up.id_rol = r.id_rol
                    LEFT JOIN 
                        estado e ON up.id_estado = e.id_estado
                    WHERE 
                        up.id_userprofile = '$id_userprofile'";
        
        $resultado = mysqli_query($conn, $query);
    
        if (!$resultado) {
            $jTableResult['msj'] = "Error en la consulta SQL: " . mysqli_error($conn);
            $jTableResult['rstl'] = "0";
            print json_encode($jTableResult);
            break;
        }
        
        $numero = mysqli_num_rows($resultado);
        if ($numero == 0) {
            $jTableResult['msj'] = "NO EXISTEN DATOS.";
            $jTableResult['rstl'] = "0";
        } else {
            while ($registro = mysqli_fetch_array($resultado)) {
                $jTableResult['listaPermiso'] .= "
                    <div class='form-container'>
                        <h2>Datos Usuario</h2><br>
                        <h3>Imagen de perfil Usuario.</h3>
                        <h4 class='label-identifier'>Nombre Usuario</h4>
                        <label class='data-field'>" . $registro['nom_usuario'] . " " . $registro['nombre_dos'] . " " . $registro['apellido'] . "</label><br>
                        <h4 class='label-identifier'>Tipo Documento</h4><br>
                        <label class='data-field'>" . $registro['nom_doc'] . "</label><br>
                        <h4 class='label-identifier'>Numero Documento</h4><br>
                        <label>" . $registro['numeroiden'] . "</label><br>
                        <hr><br>
                        <h2>Gestion de Permisos</h2>
                        <h4 class='label-identifier'>Estado Usuario</h4>
                        <select id='id_estado'>
                            <option value='" . $registro['id_estado'] . "'>" . $registro['nom_estado'] . "</option>
                        </select>
                        <h4 class='label-identifier'>Rol Usuario</h4>
                        <select id='id_rol'>
                            <option value='" . $registro['id_rol'] . "'>" . $registro['nom_rol'] . "</option>
                        </select>
                    </div>";
            }
            $jTableResult['msj'] = "";
            $jTableResult['rstl'] = "1";
        }
    
        print json_encode($jTableResult);
    break;
    case 'GestionPermiso':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $jTableResult['listaPermiso'] = "";
        $id_userprofile = mysqli_real_escape_string($conn, $_POST['id_userprofile']);
        $id_estado = mysqli_real_escape_string($conn, $_POST['id_estado']);
        $id_rol = mysqli_real_escape_string($conn, $_POST['id_rol']);
        $query = "UPDATE userprofile 
                SET 
                    id_estado = '$id_estado',
                    id_rol = '$id_rol'
                WHERE 
                    id_userprofile = '$id_userprofile'";
        $resultado = mysqli_query($conn, $query);
        if (!$resultado) {
            $jTableResult['msj'] = "Error en la consulta SQL: " . mysqli_error($conn);
            $jTableResult['rstl'] = "0";
            print json_encode($jTableResult);
            break;
        } else {
            $jTableResult['msj'] = "Permisos Concedidos Exitosamente";
            $jTableResult['rstl'] = "1";
        }

    
        print json_encode($jTableResult);
    break;
    case 'actualizarusuario': 
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
    
        $id_userprofile = mysqli_real_escape_string($conn, $_SESSION['id_userprofile']);
        $nombre_imagen = '';
    
        // Procesar la carga de la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $imagen_temp = $_FILES['imagen']['tmp_name'];
            $nombre_imagen = basename($_FILES['imagen']['name']);
            $ruta_destino = "uploads/" . $nombre_imagen; // Ruta donde se guardará la imagen
    
            // Mover la imagen al directorio de destino
            if (!move_uploaded_file($imagen_temp, $ruta_destino)) {
                $jTableResult['msj'] = "Error al guardar la imagen.";
                $jTableResult['rstl'] = "0";
                print json_encode($jTableResult);
                break;
            }
        }
        // Construir la consulta SQL
        $query = "UPDATE userprofile SET 
                        nombre = '".$_POST['nombre']."', 
                        nombre_dos = '".$_POST['nombre_dos']."', 
                        celular = '".$_POST['celular']."',
                        correo = '".$_POST['correo']."',
                        apellido = '".$_POST['apellido']."', 
                        apellido_dos = '".$_POST['apellido_dos']."'";
        
        // Si se ha cargado una imagen, agregarla a la consulta
        if ($nombre_imagen !== '') {
            $query .= ", imagen = '$nombre_imagen'";
        }
    
        $query .= " WHERE id_userprofile = '$id_userprofile';";
    
        try {
            if ($result = mysqli_query($conn, $query)) {
                mysqli_commit($conn);
                $jTableResult['msj'] = "Registro guardado con éxito.";
                $jTableResult['rstl'] = "1";
            } else {
                throw new Exception(mysqli_error($conn));
            }
        } catch (mysqli_sql_exception $e) {
            mysqli_rollback($conn);
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $jTableResult['msj'] = "Error: la identificación está duplicada. Por favor ingrese otra.";
            } else {
                $jTableResult['msj'] = "Error al guardar: " . $e->getMessage();
            }
            $jTableResult['rstl'] = "0";
        }
        
        print json_encode($jTableResult);
    break;
    case 'exportarUsuarios':
        // Limpiar cualquier salida previa
        ob_clean();
    
        // Realiza la consulta para obtener los datos que deseas exportar
        $usuarios = obtenerUsuarios();
    
        if (!empty($usuarios)) {
            // Nombre del archivo con timestamp
            $filename = "usuarios_" . date('YmdHis') . ".xls";
    
            // Establece los encabezados para forzar la descarga del archivo
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");
    
            // Abre un flujo de salida (output stream)
            $output = fopen("php://output", "w");
    
            // Escribe los nombres de las columnas manualmente
            $columnas = ["Nombre", "Apellido", "Correo Electrónico", "Poblacion"];
            fputcsv($output, $columnas, "\t");
    
            // Escribe los valores de cada fila
            foreach ($usuarios as $usuario) {
                $fila = [
                    $usuario['nombre'],
                    $usuario['apellido'],
                    $usuario['correo'],
                    $usuario['nombre_poblacion']

                ];
                fputcsv($output, $fila, "\t");
            }
    
            // Cierra el flujo de salida
            fclose($output);
            exit;
        } else {
            echo json_encode([
                'rstl' => '0',
                'msj' => 'No hay datos a exportar.'
            ]);
        }
    break;
    
}

mysqli_close($conn);
    ?>