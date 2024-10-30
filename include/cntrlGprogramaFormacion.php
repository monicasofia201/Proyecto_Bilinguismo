<?php
//https://getbootstrap.com/docs/5.0/components/modal/
include_once('conex.php');
header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn=Conectarse();
function obtenerProgramacion() {
    $conn = Conectarse();
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }
    // Consulta a la tabla userprofile
    $sql = "SELECT  
            pf.id_programaformacion,
            pf.fecha_inicio,
            pf.fecha_cierre,
            u.nombre AS nom_usu, 
            u.numeroiden,
            u.apellido, 
            pf.nombre AS nom_pf,
            c.nombre AS nom_compe,
            ra.nombre AS nom_ra,
            pf.modalidad,
            pf.matriculados,
            r.nombre AS nom_rol,
            pf.ficha,
            e.inicio,
            e.termino
        FROM 
            convites cv
        LEFT JOIN
            eventos e ON cv.fk_id_evento = e.id_evento
        LEFT JOIN 
            programaformacion pf ON e.id_programaformacion = pf.id_programaformacion
        LEFT JOIN  
            userprofile u ON pf.fk_programado = u.id_userprofile
        LEFT JOIN
            competencia c ON pf.id_competencia = c.id_competencia
        LEFT JOIN
            resultadosaprendizaje ra ON pf.id_resultado_aprendizaje = ra.id_resultado_aprendizaje
        LEFT JOIN
            rol r ON u.id_rol = r.id_rol";
            
    $resultado = mysqli_query($conn, $sql);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conn));
    }

    $programaciones = array();
    while ($row = mysqli_fetch_assoc($resultado)) {
        $programaciones[] = $row;
    }

    // Cierra la conexión
    mysqli_close($conn);

    return $programaciones;
}
switch ($_REQUEST['action']) 
{
    case 'buscarPrograma':
        $jTableResult = array();
        $jTableResult['rstl']="";
        $jTableResult['msj']="";
        $jTableResult['listaUsu']='
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
                $jTableResult['msj']= "";
                $jTableResult['rslt']= "1";						
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
                    <h2>Datos Usuario</h2><br>
                    <h3>Imagen de perfil Usuario.</h3>
                    <h4>Nombre Usuario</h4>
                    <label>" . $registro['nom_usuario'] . " " . $registro['nombre_dos'] . " " . $registro['apellido'] . "</label><br>
                    <h4>Tipo Documento</h4><br>
                    <label>" . $registro['nom_doc'] . "</label><br>
                    <h4>Numero Documento</h4><br>
                    <label>" . $registro['numeroiden'] . "</label><br>
                    <hr><br>
                    <h2>Gestion Permisos</h2>
                    <h4>Estado Usuario</h4>
                    <select id='id_estado'>
                        <option value='" . $registro['id_estado'] . "'>" . $registro['nom_estado'] . "</option>
                    </select>
                    <h4>Rol Usuario</h4>
                    <select id='id_rol'>
                        <option value='" . $registro['id_rol'] . "'>" . $registro['nom_rol'] . "</option>
                    </select>";
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
    case 'MisPf_sin':
        $jTableResult = array();
        $jTableResult['rs'] = "";
        $jTableResult['Ms'] = "";
        $jTableResult['tabla'] = ""; 
    
        // Iniciar la construcción de la tabla
        $jTableResult['tabla'] .= '
        <div class="container">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>';
                            if ($_SESSION['id_rol'] == 3) {
                                $jTableResult['tabla'] .= '<th>Nombre Programa Formacion</th>
                                                            <th>Encargado</th>';
                            } else {
                                $jTableResult['tabla'] .= '<th>Tipo Solicitud</th>';
                            }
        $jTableResult['tabla'] .= '<th>Jornada</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        <tbody>';
            // Consulta para obtener las solicitudes y sus detalles asociados
            $busqueda = "SELECT 
                            p.nombre,
                            p.ficha,
                            p.id_programaformacion,
                            u.nombre AS nombre_responsable,
                            u.apellido,
                            e.nombre AS nombre_estado,
                            p.ficha,
                            j.nombre AS nombre_jornada
                        FROM 
                            programaformacion p
                        LEFT JOIN 
                            userprofile u ON p.fk_responsable = u.id_userprofile
                        LEFT JOIN 
                            estado e ON p.id_estado = e.id_estado
                        LEFT JOIN 
                            jornada j ON p.id_jornada = j.id_jornada
                        WHERE 
                            p.id_estado = 7 ";
                            if($_SESSION['id_rol']==2){
                                $busqueda .= "AND fk_programado = " . $_SESSION['id_userprofile'] . "";
                                
                            }
                            
                            $busqueda .= "ORDER BY p.id_programaformacion DESC";
            $result = mysqli_query($conn, $busqueda);
            
            if (mysqli_num_rows($result) > 0) {
                $jTableResult['rs'] = "1";
                // Recorrer los resultados y construir la lista de opciones
                while($registro = mysqli_fetch_array($result)) {
                    $jTableResult['tabla'] .= "<tr>
                                                <td>" . $registro['id_programaformacion'] . "</td>
                                                <td>" . $registro['nombre'] . "</td>";
                                                    if ($_SESSION['id_rol'] == 3) {
                                                        $jTableResult['tabla'] .= "<td>" . $registro['nombre_responsable'] . "" . $registro['apellido'] . "</td>";
                                                    }
                                                    $jTableResult['tabla'] .= "<td>" . $registro['nombre_jornada'] . "</td>
                                                                                <td>" . $registro['nombre_estado'] . "</td>
                                                                                <td>";
                                                    if ($_SESSION['id_rol'] == 3) {
                                                        $jTableResult['tabla'] .= '
                                                                                    <button id="modalCancel" class="btn btn-danger btn-sm local" data-bs-toggle="modal" data-bs-target="#cancelSolicitudModal" data-id="' . $registro['id_programaformacion'] . '">Denegar Soli</button>
                                                                                    <button  class="btn btn-success local" id="btn_ListPf" data-bs-toggle="modal" data-bs-target="#AceptSolicitudModal" data-id="' . $registro['id_programaformacion'] . '" > Seguimiento</button>';
                                                    }
                    $jTableResult['tabla'] .= "</td></tr>";
                }
                $jTableResult['tabla'] .= "</tbody></table></div>"; // Cerrar la tabla y los elementos HTML
        }
        
        print json_encode($jTableResult);
    break;
    case 'Listar_pf':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListPf'] = "";
        $id_programaformacion = mysqli_real_escape_string($conn, $_POST['id_programaformacion']);
            $query = "SELECT  
            pf.id_programaformacion,
            pf.fecha_inicio,
            pf.fecha_cierre,
            u.nombre AS nom_usu, 
            u.numeroiden,
            u.apellido, 
            pf.nombre AS nom_pf,
            c.nombre AS nom_compe,
            ra.nombre AS nom_ra,
            pf.modalidad,
            pf.matriculados,
            r.nombre AS nom_rol,
            pf.ficha,
            e.inicio,
            e.termino
        FROM 
            convites cv
        LEFT JOIN
            eventos e ON cv.fk_id_evento = e.id_evento
        LEFT JOIN 
            programaformacion pf ON e.id_programaformacion = pf.id_programaformacion
        LEFT JOIN  
            userprofile u ON pf.fk_programado = u.id_userprofile
        LEFT JOIN
            competencia c ON pf.id_competencia = c.id_competencia
        LEFT JOIN
            resultadosaprendizaje ra ON pf.id_resultado_aprendizaje = ra.id_resultado_aprendizaje
        LEFT JOIN
            rol r ON u.id_rol = r.id_rol
        WHERE 
            pf.id_programaformacion = '$id_programaformacion';";

        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['ListPf'] .= "
                    <div class='form-container'>
                        <label class='label-identifier'> Nombre Programa de Formacion</label><br>
                        <label id='solicitante' class='data-field'>
                        " . $registro['nom_pf'] . "</label>
                        <br>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Fecha inicio Programa de Formacion</h6>
                                <h6 class='data-field'>" . $registro['fecha_inicio'] . "</h6>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Fecha Fin Programa de Formacion</h6>
                                <h6 class='data-field'>" . $registro['fecha_cierre'] . "</h6>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Inicio Prgoramacion</h6>
                                <h6 class='data-field'>" . $registro['inicio'] . "</h6>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Competencia</h6>
                                <h6 class='data-field'>" . $registro['nom_compe'] . "
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Resultado de Aprendizaje</h6>
                                <h6 class='data-field'>" . $registro['nom_ra'] . "</h6>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Fin Programacion</h6>
                                <h6 class='data-field'>" . $registro['termino'] . "</h6>
                            </div>
                        </div>
                        
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Modalidad</h6>
                                <h6 class='data-field'>" . $registro['modalidad'] . "</h6>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Numero Matriculados</h6>
                                <h6 class='data-field'>" . $registro['matriculados'] . "</h6>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Ficha</h6>
                                <h6 class='data-field'>" . $registro['ficha'] . "</h6>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <label class='label-identifier' for='archivo'>Descargar Informacion Cargada por Usuario</label>
                                <label class='data-field'><a href='' download>Descargar Documento</a></label>
                            </div>
                        </div>
                        <h5>Datos Instructor Programado</h5>
                        <label>" . $registro['nom_usu'] . "</label>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Numero de Documento</h6>
                                <h6 class='data-field'>" . $registro['numeroiden'] . "</h6>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Cargo en el Aplicativo</h6>
                                <h6 class='data-field'>" . $registro['nom_rol'] . "</h6>
                            </div>
                        </div>
                        <br>
                        <label class='label-identifier'>Detalles</label>
                        <hr>
                        <h3 class='label-identifier'>Control de Desercion</h3>
                        <hr>
                        <div id='form_container'></div>
                        <h6 class='label-identifier'>Nombre Completo</h6>
                        <input type='text'><br>
                        <h6 class='label-identifier'>Numero de identificacion</h6>
                        <input type='number'>
                        <h6 class='label-identifier'>Correo Electronico</h6>
                        <input type='text'><br>
                        <h6>Nuevo</h6>
                        <button class='create-button' id='create'> Agregar</button>
                        <button id='btnAceptarSoli' class='close-button'  >Desertar</button>
                        <hr>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                        <button type='button' class='create-button' id='btnGuardarCambios3' data-id='" . $registro['id_programaformacion'] ." '>Terminar</button>
                    </div>";
            }
        } else {
            $jTableResult['rst'] = "";
            $jTableResult['ms'] = "NO HAN PROGRAMADO NADA PARA ESTA FICHA.";
        }
        echo json_encode($jTableResult);
    break;
    case 'aceptarSolicitudPf':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $id_programaformacion = $_POST['id_programaformacion'];
        // $mensaje = $_POST['mensaje'];
        $query = "UPDATE programaformacion 
        SET id_estado = 4
        WHERE id_programaformacion = '$id_programaformacion'";
        if ($result = mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            // enviar correo URGENTE MIRAR SI SE HACE DESDE PHPMAILER O SOLO EMAIL
            @mail($correo, 'Solicitud Exitosa', $mensaje);
            $jTableResult['msj'] = "Solicitud Confimada con éxito.";
            $jTableResult['rstl'] = "1";
        } else {
            mysqli_rollback($conn);
            $jTableResult['msj'] = "Error al Confirmar la solicitud.";
            $jTableResult['rstl'] = "0";
        }
        print json_encode($jTableResult);
    break;
    case 'exportarProgramacion':
        // Limpiar cualquier salida previa
        ob_clean();
        
        // Realiza la consulta para obtener los datos que deseas exportar
        $programaciones = obtenerProgramacion();
    
        if (!empty($programaciones)) {
            // Nombre del archivo con timestamp
            $filename = "programacion_" . date('YmdHis') . ".xls";
        
            // Establece los encabezados para forzar la descarga del archivo
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");
        
            // Abre un flujo de salida (output stream)
            $output = fopen("php://output", "w");
        
            // Escribe los nombres de las columnas manualmente
            $columnas = ["Nombre Programa", "Fecha Inicio Programa", "Fecha Fin Programa",  "Inicio Programacion", "Competencia", "Resultado de Aprendizaje", "Fin Programacion", "Modalidad", "Ficha", "Nombre Responsable", "Identificacion Responsable", "Cargo"];
            fputcsv($output, $columnas, "\t");
        
            // Escribe los valores de cada fila, eliminando filas con datos vacíos
            foreach ($programaciones as $solicitud) {
                $fila = [
                    $solicitud['nom_pf'],
                    date('Y-m-d', strtotime($solicitud['fecha_inicio'])),
                    date('Y-m-d', strtotime($solicitud['fecha_cierre'])),
                    date('Y-m-d', strtotime($solicitud['inicio'])),
                    $solicitud['nom_compe'],
                    $solicitud['nom_ra'],
                    date('Y-m-d', strtotime($solicitud['termino'])),
                    $solicitud['modalidad'],
                    $solicitud['ficha'],
                    $solicitud['nom_usu'],
                    $solicitud['numeroiden'],
                    $solicitud['nom_rol']
                ];
    
                // Filtrar datos vacíos
                if (!array_filter($fila)) {
                    continue;
                }
    
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