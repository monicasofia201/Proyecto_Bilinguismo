<?php
//https://getbootstrap.com/docs/5.0/components/modal/
include_once('conex.php');
header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn=Conectarse();
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function obtenerSolicitud() {
    $conn = Conectarse();
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Obtener el filtro desde $_POST o $_GET
    $filtro = isset($_POST['dato_filtro']) ? $_POST['dato_filtro'] : (isset($_GET['dato_filtro']) ? $_GET['dato_filtro'] : '');


    // Consulta a la tabla userprofile
    $sql = "SELECT ts.nombre AS nom_Soli, u.apellido, u.nombre AS nom_user, u.correo AS correo_U, pbl.nombre_poblacion AS nom_pblc,
                    s.fecha_creacion, ur.nombre AS nom_Ures, ur.correo AS correo_Ures, s.fecha_respuesta, s.fecha_asignada, s.fecha_cancelada
                FROM 
                    solicitud s
                LEFT JOIN
                    detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
                LEFT JOIN
                    tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
                LEFT JOIN 
                    userprofile u ON s.id_userprofile = u.id_userprofile
                LEFT JOIN
                    poblacion pbl ON u.cod_poblacion = pbl.cod_poblacion
                LEFT JOIN
                    userprofile ur ON s.id_responsable = ur.id_userprofile
                LEFT JOIN
                    estado e ON s.id_estado = e.id_estado
                WHERE e.nombre = '$filtro'";

    $resultado = mysqli_query($conn, $sql);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conn));
    }

    $solicitudes = array();
    while ($row = mysqli_fetch_assoc($resultado)) {
        $solicitudes[] = $row;
    }

    // Cierra la conexión
    mysqli_close($conn);

    return $solicitudes;
}
function enviarCorreo($correo, $nueva_contraseña) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'flortasconjersoncamilo@gmail.com'; // Tu dirección de correo de Gmail
        $mail->Password = 'goeh dnhu zzcu gkbm'; // Tu contraseña de Gmail
        $mail->SMTPSecure = 'tls'; // Activa la encriptación TLS
        $mail->Port = 587; // Puerto TCP para TLS

        // Remitente y destinatario
        $mail->setFrom('flortasconjersoncamilo@gmail.com', 'Bili');
        $mail->addAddress($correo);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Solicitud Aceptada ';
        $mail->Body    = $nueva_contraseña;

        // Enviar el correo
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "No se pudo enviar la Solicitud. Error: {$mail->ErrorInfo}";
        return false;
    }
}

switch ($_REQUEST['action']) 
{

    case 'RegistroSoliNew':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        function sanitize_input($input) {
            return isset($input) && $input !== '' && $input !== 'undefined' ? "'" . $input . "'" : "NULL";
        }
        $id_resultado_aprendizaje = sanitize_input($_POST['id_resultado_aprendizaje']);
        $id_competencia = sanitize_input($_POST['id_competencia']);
        $nombre = sanitize_input($_POST['nombre']);
        $fecha_inicio = sanitize_input($_POST['fecha_inicio']);
        $jornada = sanitize_input($_POST['id_jornada']);
        $instagram = sanitize_input($_POST['instagram']);
        $facebook = sanitize_input($_POST['facebook']);
        $youtube = sanitize_input($_POST['youtube']);
        $twitter = sanitize_input($_POST['twitter']);
        $imagen = sanitize_input($_POST['imagen']);
        $dpto = sanitize_input($_POST['cod_dpto']);
        $municipio = sanitize_input($_POST['cod_municipio']);
        $vereda = sanitize_input($_POST['cod_poblado']);
        $ficha = sanitize_input($_POST['ficha']);
        $descripcion = sanitize_input($_POST['descripcion']);
        $id_nivel_formacion = sanitize_input($_POST['id_nivel_formacion']);
        $id_programaformacion = sanitize_input($_POST['id_programaformacion']);
        
        // Manejo de la carga del archivo
        $uploadDir = 'doc_usu/';
        $archivoPath = "NULL";
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
            $archivoPath = $uploadDir . basename($_FILES['archivo']['name']);
            
            // Crear el directorio si no existe
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $archivoPath)) {
                $jTableResult['rst'] = "0";
                $jTableResult['ms'] = "Error al mover el archivo";
                print json_encode($jTableResult);
                return; // Cambiado a return en lugar de break
            }
            
            // Ajuste para que archivoPath sea tratado como cadena de texto en la consulta
            $archivoPath = "'" . $archivoPath . "'";
        }
        
        // Consulta para insertar datos en la base de datos
        $query = "INSERT INTO detallesolicitud SET
                    id_tiposolicitud = '" . $_POST['id_tiposolicitud'] . "', 
                    nombre = $nombre,
                    id_jornada = $jornada, 
                    fecha_inicio = $fecha_inicio,
                    instagram = $instagram, 
                    facebook = $facebook, 
                    youtube = $youtube, 
                    twitter = $twitter, 
                    imagen = $imagen, 
                    descripcion = $descripcion,
                    cod_dpto = $dpto, 
                    cod_municipio = $municipio, 
                    ficha= $ficha,
                    id_competencia = $id_competencia,
                    id_resultado_aprendizaje = $id_resultado_aprendizaje,
                    id_nivel_formacion = $id_nivel_formacion,
                    id_programaformacion = $id_programaformacion,
                    cod_poblado = $vereda, 
                    documento = $archivoPath;";
    
        if ($result = mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            $jTableResult['rst'] = "1";
            $mensaje = "Su solicitud fue enviada con Exito\r\n a la coordinacion de Bilinguismo del centro agropecuario\r\nNombre de la solicitud .......";
            @mail('flortasconjersoncamilo@gmail.com', 'Solicitud Exitosa', $mensaje);
        } else {
            mysqli_rollback($conn);
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al guardar.";
        }
    
        print json_encode($jTableResult);
    break;
    case 'TipoSoliNew': // este nombra esta patentado. no utilizar. tomar otro diferente para cada uno.
        $jTableResult = array();
        $jTableResult['rst']="";
        $jTableResult['ms']="";
            $query="INSERT INTO tiposolicitud SET
                    id_rol ='".$_POST['id_rol']."', 
                    nombre ='".$_POST['nombre']."';"; 
            if($result= mysqli_query($conn,$query)){
                    mysqli_commit($conn);
                    $jTableResult['rst']= "1";
                    $jTableResult['ms']="Tipo de Solicitud Guardado con exito";
                    // El mensaje
                    $mensaje = "Su solicitud fue enviada con Exito\r\n a la coordinacion de Bilinguismo del centro agropecuario\r\nNombre de la solicitud .......";
                    @mail('flortasconjersoncamilo@gmail.com', 'Mi título', $mensaje);
                    }
            else{
                    mysqli_rollback($conn);
                    $jTableResult['ms']= "Error al guardar.";				
                    $jTableResult['rst']= "0";  }
        print json_encode($jTableResult);
    break;
    case 'Solicitud':
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
    
        $query = "SELECT MAX(id_detallesolicitud) as lastId FROM detallesolicitud;"; 
        $arreglo = mysqli_query($conn, $query);
        if ($arreglo) {
            $result = mysqli_fetch_array($arreglo);
            if ($result) {
                $varid = $result['lastId'];
                
                // Corregir la consulta SQL, cerrando el paréntesis en VALUES
                $query = "INSERT INTO solicitud (id_detallesolicitud, id_estado, id_userprofile, fecha_creacion) 
                            VALUES (?, 3, ?, NOW())";
                
                $stmt = mysqli_prepare($conn, $query);
                if ($stmt) {
                    // Enlazar las variables a la declaración preparada
                    mysqli_stmt_bind_param($stmt, 'ii', $varid, $_SESSION['id_userprofile']);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_commit($conn);
                        $jTableResult['ms'] = "Solicitud guardada con éxito.";
                        $jTableResult['rst'] = "1";
                    } else {
                        mysqli_rollback($conn);
                        $jTableResult['ms'] = "Error al guardar metadatos.";
                        $jTableResult['rst'] = "0";
                    }
                } else {
                    $jTableResult['ms'] = "Error al preparar la consulta.";
                    $jTableResult['rst'] = "0";
                }
            } else {
                $jTableResult['ms'] = "Error al obtener el último id de detallesolicitud.";
                $jTableResult['rst'] = "0";
            }
        } else {
            $jTableResult['ms'] = "Error en la consulta de id_detallesolicitud.";
            $jTableResult['rst'] = "0";
        }
        
        print json_encode($jTableResult);
    break;
    case 'detalleSolicitud':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListDetalle'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
                        $query = "SELECT 
                                s.id_solicitud,
                                s.fecha_creacion,
                                s.fecha_respuesta,
                                s.fecha_asignada,
                                u.nombre AS nombre_U, 
                                d.nombre_dpto AS nom_dpto, 
                                m.nombre_municipio AS nom_muni, 
                                p.nombre_poblado AS nom_vereda, 
                                et.nombre AS nom_estado, 
                                u.apellido,
                                u.id_rol,
                                ur.nombre_dos AS nom_dosR,
                                ur.apellido AS apellidoR,
                                ur.nombre AS nomR,
                                e.nombre AS nom_Empresa, 
                                ts.nombre AS Nombre_Solicitud, 
                                ds.id_tiposolicitud,
                                ds.descripcion,
                                ds.imagen,
                                ds.documento,
                                a.nombre AS nom_area,
                                pf.nombre AS nom_pgf,
                                pf.matriculados,
                                pf.fecha_inicio AS fecha_iniPf,
                                pf.fecha_cierre,
                                pf.horas_curso,
                                pf.ficha,
                                ds.fecha_inicio AS fecha_iniNoti,
                                epf.nombre AS estado_nom_pgf,
                                j.nombre AS nom_jornada,
                                pf.modalidad,
                                pf.nivel_formacion,
                                pf.tipo_formacion
                            FROM 
                                solicitud s
                                LEFT JOIN  
                                    detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
                                LEFT JOIN 
                                    programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
                                LEFT JOIN 
                                    nivelformacion nf ON pf.id_nivel_formacion = nf.id_nivel_formacion
                                LEFT JOIN 
                                    modalidad md ON pf.id_modalidad = md.id_modalidad
                                LEFT JOIN 
                                    jornada j ON pf.id_jornada = j.id_jornada
                                LEFT JOIN 
                                    estado epf ON pf.id_estado = epf.id_estado
                                LEFT JOIN  
                                    estado et ON s.id_estado = et.id_estado
                                LEFT JOIN  
                                    userprofile ur ON s.id_responsable = ur.id_userprofile
                                LEFT JOIN  
                                    userprofile u ON s.id_userprofile = u.id_userprofile
                                LEFT JOIN  
                                    departamentos d ON ds.cod_dpto = d.cod_dpto
                                LEFT JOIN  
                                    municipios m ON ds.cod_municipio = m.cod_municipio
                                LEFT JOIN  
                                    poblados p ON ds.cod_poblado = p.cod_poblado
                                LEFT JOIN  
                                    empresa e ON s.id_empresa = e.id_empresa
                                LEFT JOIN  
                                    tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
                                LEFT JOIN 
                                    area a ON ds.id_area = a.id_area
                            WHERE 
                                s.id_solicitud = '$id_solicitud';
                        ";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                if ($registro['id_tiposolicitud'] == 1){
                    $jTableResult['ListDetalle'] .= "
                                                        <div class='form-container'>
                                                            <div class='course-data-field'>
                                                                <label class='label-identifier'>Fecha Realizacion Solicitud</label>
                                                                <label class='data-field'>" . $registro['fecha_creacion'] . "</label>
                                                            </div>
                                                                            <div class='course-data-field'>
                                                                                <label class='label-identifier'>Empresa</label>
                                                                                <label class='data-field'>" . $registro['nom_Empresa'] . "</label>
                                                                            </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Solicitante</label>
                                                                    <label class='data-field' id='solicitante'>" . $registro['nombre_U'] . "</label>
                                                                </div>
                                                                <br>
                                                                    <h5 class='label-identifier'><strong>Ubicación Sugerida Para Solicitud</strong></h5>
                                                                        <div class='course-data-field'>
                                                                            <h6 class='label-identifier'>Departamento</h6>
                                                                            <label class='data-field'>" . $registro['nom_dpto'] . "</label>
                                                                        </div>
                                                                        <div class='course-data-field'>
                                                                            <h6 class='label-identifier'>Municipio</h6>
                                                                            <label class='data-field'>" . $registro['nom_muni'] . "</label>
                                                                        </div>
                                                                        <div class='course-data-field'>
                                                                            <h6 class='label-identifier'>Vereda</h6>
                                                                            <label class='data-field'>" . $registro['nom_vereda'] . "</label>
                                                                        </div><br>
                                                            <div class='course-data-field'>
                                                                <label class='label-identifier' for='detalles'>Detalles</label>
                                                                <br>
                                                                <textarea id='detalles' name='detalles'>" . $registro['descripcion'] . "</textarea>
                                                            </div>
                                                            <br>
                                                            <div class='course-data-field'>
                                                                <h4 class='label-identifier'>Documento para Descargar</h4>
                                                                <label class='data-field'><a href='../../include/" . $registro['documento'] . "' download>Descargar Documento</a></label>
                                                            </div>
                                                            <div class='course-data-field'>
                                                                <h6 class='label-identifier'>Responsable</h6>
                                                                <label class='data-field'>" . $registro['nomR'] . "" . $registro['apellidoR'] . "</label>
                                                            </div>
                                                            <hr>
                                                            <div class='course-data-container'>
                                                                <h2 class='label-identifier'>DATOS DE CURSO</h2>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Fecha Asignacion</label>
                                                                    <label class='data-field'>" . $registro['fecha_asignada'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Nombre curso</label>
                                                                    <label class='data-field'>" . $registro['nom_pgf'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Fecha inicio</label>
                                                                    <label class='data-field'>" . $registro['fecha_iniPf'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Fecha cierre</label>
                                                                    <label class='data-field'>" . $registro['fecha_cierre'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Numero de ficha</label>
                                                                    <label class='data-field'>" . $registro['ficha'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Horas de curso</label>
                                                                    <label class='data-field'>" . $registro['horas_curso'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Modalidad</label>
                                                                    <label class='data-field'>" . $registro['modalidad'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Jornada</label>
                                                                    <label class='data-field'>" . $registro['nom_jornada'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Nivel de Formacion</label>
                                                                    <label class='data-field'>" . $registro['nivel_formacion'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Tipo de Formacion</label>
                                                                    <label class='data-field'>" . $registro['tipo_formacion'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Matriculados</label>
                                                                    <label class='data-field'>" . $registro['matriculados'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Estado</label>
                                                                    <label class='data-field'>" . $registro['estado_nom_pgf'] . "</label>
                                                                </div>
                                                            </div>
                                                            <div class='course-data-field'>
                                                                <label class='label-identifier'>Fecha Respuesta Solicitud</label>
                                                                <label class='data-field'>" . $registro['fecha_respuesta'] . "</label>
                                                            </div>
                                                        </div>
                                                    ";
                }elseif($registro['id_tiposolicitud'] == 3){
                    $jTableResult['ListDetalle'] .= "
                    <div class='form-container'>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Fecha Realizacion Solicitud</label>
                            <label class='data-field'>" . $registro['fecha_creacion'] . "</label>
                        </div>
                            <div class='course-data-field'>
                                <label class='label-identifier'>Solicitante</label>
                                <label class='data-field' id='solicitante'>" . $registro['nombre_U'] . "</label>
                            </div>
                            <br>
                        <div class='course-data-field'>
                            <label class='label-identifier' for='detalles'>Detalles</label>
                            <br>
                            <textarea id='detalles' name='detalles'>" . $registro['descripcion'] . "</textarea>
                        </div>
                        <br>
                        <div class='course-data-field'>
                            <h4 class='label-identifier'>Documento para Descargar</h4>
                            <label class='data-field'><a href='../../include/" . $registro['documento'] . "' download>Descargar Documento</a></label>
                        </div>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Responsable</h6>
                            <label class='data-field'>" . $registro['nomR'] . "" . $registro['apellidoR'] . "</label>
                        </div>
                        <hr>
                            <div class='course-data-field'>
                                <label class='label-identifier'>Estado</label>
                                <label class='data-field'>" . $registro['estado_nom_pgf'] . "</label>
                            </div>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Fecha Respuesta Solicitud</label>
                            <label class='data-field'>" . $registro['fecha_respuesta'] . "</label>
                        </div>
                    </div>
                    ";
                }elseif($registro['id_tiposolicitud'] == 4){
                    $jTableResult['ListDetalle'] .= '
                            <style>
                                .text{
                                font-size:2rem;
                                }
                            </style>
                                <div class="">
                                <div class="row px-3 pb-3 justify-content-center">
                                    <div class="col-md-8">
                                        <h2 class="mb-4 font-weight-bold"></h2>
                                        <img class="img-fluid float-left w-50 mr-4 mb-3" src="../../include/' . $registro['imagen'] . '"
                                            alt="Image">
                                        <p class="text">
                                        ' . $registro['descripcion'] . '
                                        </p>
                                    </div>
                                </div>
                            </div>
                    ';
                }elseif($registro['id_tiposolicitud'] == 5){
                    $jTableResult['ListDetalle'] .= "
                    <div class='form-container'>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Fecha Realizacion Solicitud</label>
                            <label class='data-field'>" . $registro['fecha_creacion'] . "</label>
                        </div>
                            <div class='course-data-field'>
                                <label class='label-identifier'>Solicitante</label>
                                <label class='data-field' id='solicitante'>" . $registro['nombre_U'] . "</label>
                            </div>
                            <br>
                        <div class='course-data-field'>
                            <label class='label-identifier' for='detalles'>Detalles</label>
                            <br>
                            <textarea id='detalles' name='detalles'>" . $registro['descripcion'] . "</textarea>
                        </div>
                        <br>
                        <div class='course-data-field'>
                            <h4 class='label-identifier'>Documento para Descargar</h4>
                            <label class='data-field'><a href='../../include/" . $registro['documento'] . "' download>Descargar Documento</a></label>
                        </div>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Responsable</h6>
                            <label class='data-field'>" . $registro['nomR'] . "" . $registro['apellidoR'] . "</label>
                        </div>
                        <hr>
                            <div class='course-data-field'>
                                <label class='label-identifier'>Estado</label>
                                <label class='data-field'>" . $registro['estado_nom_pgf'] . "</label>
                            </div>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Fecha Respuesta Solicitud</label>
                            <label class='data-field'>" . $registro['fecha_respuesta'] . "</label>
                        </div>
                    </div>
                    ";
                }elseif($registro['id_tiposolicitud'] == 23){
                    $jTableResult['ListDetalle'] .= "
                                                        <div class='form-container'>
                                                            <div class='course-data-field'>
                                                                <label class='label-identifier'>Fecha Realizacion Solicitud</label>
                                                                <label class='data-field'>" . $registro['fecha_creacion'] . "</label>
                                                            </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Solicitante</label>
                                                                    <label class='data-field' id='solicitante'>" . $registro['nombre_U'] . "</label>
                                                                </div>
                                                                <br>
                                                            <div class='course-data-field'>
                                                                <label class='label-identifier' for='detalles'>Detalles</label>
                                                                <br>
                                                                <textarea id='detalles' name='detalles'>" . $registro['descripcion'] . "</textarea>
                                                            </div>
                                                            <br>
                                                            <div class='course-data-field'>
                                                                <h4 class='label-identifier'>Documento para Descargar</h4>
                                                                <label class='data-field'><a href='../../include/" . $registro['documento'] . "' download>Descargar Documento</a></label>
                                                            </div>
                                                            <div class='course-data-field'>
                                                                <h6 class='label-identifier'>Responsable</h6>
                                                                <label class='data-field'>" . $registro['nomR'] . "" . $registro['apellidoR'] . "</label>
                                                            </div>
                                                            <hr>
                                                            <div class='course-data-container'>
                                                                <h2 class='label-identifier'>DATOS DE CURSO</h2>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Fecha Asignacion</label>
                                                                    <label class='data-field'>" . $registro['fecha_asignada'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Nombre curso</label>
                                                                    <label class='data-field'>" . $registro['nom_pgf'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Fecha inicio</label>
                                                                    <label class='data-field'>" . $registro['fecha_iniPf'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Fecha cierre</label>
                                                                    <label class='data-field'>" . $registro['fecha_cierre'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Numero de ficha</label>
                                                                    <label class='data-field'>" . $registro['ficha'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Horas de curso</label>
                                                                    <label class='data-field'>" . $registro['horas_curso'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Modalidad</label>
                                                                    <label class='data-field'>" . $registro['modalidad'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Jornada</label>
                                                                    <label class='data-field'>" . $registro['nom_jornada'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Nivel de Formacion</label>
                                                                    <label class='data-field'>" . $registro['nivel_formacion'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Tipo de Formacion</label>
                                                                    <label class='data-field'>" . $registro['tipo_formacion'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Matriculados</label>
                                                                    <label class='data-field'>" . $registro['matriculados'] . "</label>
                                                                </div>
                                                                <div class='course-data-field'>
                                                                    <label class='label-identifier'>Estado</label>
                                                                    <label class='data-field'>" . $registro['estado_nom_pgf'] . "</label>
                                                                </div>
                                                            </div>
                                                            <div class='course-data-field'>
                                                                <label class='label-identifier'>Fecha Respuesta Solicitud</label>
                                                                <label class='data-field'>" . $registro['fecha_respuesta'] . "</label>
                                                            </div>
                                                        </div>
                                                    ";
                }

            }
        }else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        
        echo json_encode($jTableResult);
    break;
    case 'ListarSolicitud':
        $jTableResult = array();
        $id_solicitud = $_POST['id_solicitud'];
        $query = "SELECT solicitud.id_solicitud, detallesolicitud.*,e.nombre AS Nom_Estado, up.nombre AS Nom_Responsable, tiposolicitud.nombre AS Nombre_Solicitud, detallesolicitud.id_tiposolicitud
                    FROM solicitud
                    JOIN userprofile up ON solicitud.id_userprofile = up.id_userprofile
                    JOIN estado e ON solicitud.id_estado = e.id_estado
                    JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                    JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud 
                    WHERE solicitud.id_solicitud = '$id_solicitud'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $registro = mysqli_fetch_assoc($result); // Usar fetch_assoc para obtener una fila asociativa
            $jTableResult = [
                'rstl' => '1',
                'solicitud' => $registro
            ];
            if ($registro['id_tiposolicitud'] == 1) {
            }
        } else {
            $jTableResult = [
                'rstl' => '0',
                'msj' => 'Error al obtener los datos.'
            ];
        }
        echo json_encode($jTableResult);
    break;
    case 'ListarSolicitud_asignacion':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListAsign'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
                        $query = "SELECT 
                                    s.id_solicitud, 
                                    u.nombre, 
                                    u.id_rol,
                                    d.nombre_dpto AS nom_dpto, 
                                    m.nombre_municipio AS nom_muni, 
                                    p.nombre_poblado AS nom_vereda, 
                                    u.nombre_dos,
                                    u.apellido, 
                                    e.nombre AS nom_Empresa, 
                                    ts.nombre AS Nombre_Solicitud, 
                                    ds.id_tiposolicitud,
                                    ds.descripcion,
                                    ds.documento,
                                    a.nombre AS nom_area,
                                    pf.nombre AS pf_nombre
                                FROM 
                                    solicitud s
                                LEFT JOIN  
                                    userprofile u ON s.id_userprofile = u.id_userprofile
                                LEFT JOIN   
                                    detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
                                LEFT JOIN  
                                    departamentos d ON ds.cod_dpto = d.cod_dpto
                                LEFT JOIN  
                                    municipios m ON ds.cod_municipio = m.cod_municipio
                                LEFT JOIN  
                                    poblados p ON ds.cod_poblado = p.cod_poblado
                                LEFT JOIN   
                                    empresa e ON u.id_empresa = e.id_empresa
                                LEFT JOIN   
                                    tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
                                LEFT JOIN  
                                    area a ON ds.id_area = a.id_area
                                LEFT JOIN  
                                    programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
                                WHERE 
                                    s.id_solicitud = '$id_solicitud';";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['ListAsign'] .= "
                    <div class='form-container'>
                        ";
                        if ($registro['id_rol'] == 4){
                            
                            "
                            <label class='label-identifier'>Empresa</label>
                            <label class='data-field'>" . $registro['nom_Empresa'] . "</label>";
                        }
                        
                        $jTableResult['ListAsign'] .= " <label class='label-identifier'>Solicitante</label>
                        <label class='data-field' id='solicitante'>" . $registro['nombre'] . "</label>
                        <br>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Programa de Formacion Solicitado</h6>
                                <label class='data-field'>" . $registro['pf_nombre'] . "</label>
                            </div>
                        </div>
                        <h5 class='label-identifier'><strong>Ubicación Sugerida Para Solicitud</strong></h5>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Departamento</h6>
                                <label class='data-field'>" . $registro['nom_dpto'] . "</label>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Municipio</h6>
                                <label class='data-field'>" . $registro['nom_muni'] . "</label>
                            </div>
                        </div>
                        <div class='row mt-3'>
                            <div class='col-sm-12'>
                                <h6 class='label-identifier'>Vereda</h6>
                                <label class='data-field'>" . $registro['nom_vereda'] . "</label>
                            </div>
                        </div>
                        <br>
                        
                        <label class='label-identifier' for='detalles'>Detalles</label>
                        <br>
                        <textarea id='detalles' name='detalles'>" . $registro['descripcion'] . "</textarea>
                        <br>
                        <label class='label-identifier' for='archivo'>Descargar Información Cargada por Usuario</label>
                        <label class='data-field'><a href='../../include/" . $registro['documento'] . "' download>Descargar Documento</a></label>
                        <br/>
                        <hr>
                        <h3 class='label-identifier'>Asignación</h3>
                        <h6 class='label-identifier'>Responsable</h6>
                        <select id='id_responsable'></select>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                        <button type='button' class='create-button' id='btnGuardarCambios2' data-id='". $registro['id_solicitud'] . "'>Asignar</button>
                    </div>
                ";
            }
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        echo json_encode($jTableResult);
    break;
    case 'ListarSolicitud_pf':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListPf'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
        $query = "SELECT 
            s.id_solicitud, 
            u.nombre,
            u.id_rol, 
            d.nombre_dpto AS nom_dpto, 
            m.nombre_municipio AS nom_muni, 
            p.nombre_poblado AS nom_vereda, 
            et.nombre AS nom_estado, 
            u.nombre_dos,
            u.apellido,
            ur.nombre_dos AS nom_dosR,
            ur.apellido AS apellidoR,
            ur.nombre AS nomR,
            ts.nombre AS Nombre_Solicitud, 
            ds.id_tiposolicitud,
            ds.descripcion,
            ds.documento,
            a.nombre AS nom_area,
            pf.nombre AS nom_pf,
            pf.modalidad,
            pf.tipo_formacion,
            pf.nivel_formacion,
            pf.fecha_inicio,
            pf.fecha_cierre,
            pf.horas_curso,
            pf.id_programaformacion
            FROM 
                solicitud s
            LEFT JOIN  
                estado et ON s.id_estado = et.id_estado
            LEFT JOIN  
                userprofile ur ON s.id_responsable = ur.id_userprofile
            LEFT JOIN  
                userprofile u ON s.id_userprofile = u.id_userprofile
            LEFT JOIN  
                detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
            LEFT JOIN  
                departamentos d ON ds.cod_dpto = d.cod_dpto
            LEFT JOIN  
                municipios m ON ds.cod_municipio = m.cod_municipio
            LEFT JOIN  
                poblados p ON ds.cod_poblado = p.cod_poblado
            LEFT JOIN  
                tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
            LEFT JOIN 
                area a ON ds.id_area = a.id_area
            LEFT JOIN
                programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
            WHERE 
                s.id_solicitud = '$id_solicitud'
        ";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['ListPf'] .= "
                    <div class='container'>";
                        if ($registro['id_rol'] == 4){
                            echo
                            "
                            <label class='label-identifier'>Empresa</label>
                            <label class='data-field'>" . $registro['nom_Empresa'] . "</label>";
                        }
                        
                        $jTableResult['ListPf'] .= "
                        
                        <br>
                        <div class='course-data-field'>
                            <label class='label-identifier' for='detalles'>Detalles</label>
                            <br>
                            <textarea id='detalles' name='detalles' class='form-control'>" . $registro['descripcion'] . "</textarea>
                        </div>
                        <br>
                        <div class='course-data-field'>
                            <h1 class='label-identifier'>Documento para Descargar</h1>
                            <label class='data-field'><a href='../../include/" . $registro['documento'] . "' download>Descargar Documento</a></label>
                        </div>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Responsable</h6>
                            <label class='form-control'>" . $registro['nomR'] . "  " . $registro['apellidoR'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Estado</h6>
                            <label class=form-control'>" . $registro['nom_estado'] . "</label>
                        </div><br>
                        <h2 class='label-identifier'>DATOS DE CURSO</h2>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Nombre Curso</label>
                            <label class='data-field' id='nombre_programa2'>" . $registro['nom_pf'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Fecha inicio</label>
                            <label class=form-control'>" . $registro['fecha_inicio'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Fecha cierre</label>
                            <label class=form-control'>" . $registro['fecha_cierre'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Horas de curso</label>
                            <label class='data-field' id='horas_curso_label2'>" . $registro['horas_curso'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Modalidad</label>
                            <label class='data-field' id='id_modalidad_label2'>" . $registro['modalidad'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Nivel de Formacion</label>
                            <label class='data-field' id='nivel_formacion_label2'>" . $registro['nivel_formacion'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Tipo de Formacion</label>
                            <label class='data-field' id='tipo_formacion_label2'>" . $registro['tipo_formacion'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Numero de ficha</label>
                            <input type='number' id='ficha' value='' class='form-control' />
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Jornada</label>
                            <select id='id_jornada' class='form-control'>
                            </select>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Matriculados</label>
                            <input type='number' id='matriculados' value='0' class='form-control' />
                        </div>
                    ";

                if ($registro['id_tiposolicitud'] == '23') {
                    $queryUsuarios = "SELECT u.nombre AS nom_Uof, s.id_solicitud, uf.documento, u.correo, u.celular, td.nombre AS nom_doc, u.numeroiden
                                        FROM usersxoferta uf 
                                        JOIN solicitud s ON uf.id_solicitud = s.id_solicitud
                                        JOIN userprofile u ON uf.id_userprofile = u.id_userprofile
                                        JOIN tipodocumento td ON u.id_doc = td.id_doc
                                        WHERE uf.id_solicitud ='$id_solicitud'";
                    $resultado = mysqli_query($conn, $queryUsuarios);

                    if ($resultado) {
                        if (mysqli_num_rows($resultado) > 0) {
                            while ($regis = mysqli_fetch_array($resultado)) {
                                $jTableResult['rst'] = "1";
                                $jTableResult['ms'] = "Exitoso";
                                $jTableResult['ListPf'] .= "
                                <div id='usuarios'> 
                                    <hr>
                                    <h2 class='label-identifier'>Usuarios Interesados</h2>
                                    <h5 class='label-identifier'>Nombre</h5>
                                    <label class='data-field'>" . $regis['nom_Uof'] . "</label>
                                    <h5 class='label-identifier'>Correo</h5>
                                    <label class='data-field'>" . $regis['correo'] . "</label>
                                    <h5 class='label-identifier'>Telefono</h5>
                                    <label class='data-field'>" . $regis['celular'] . "</label>
                                    <h5 class='label-identifier'>Tipo de Documento</h5>
                                    <label class='data-field'>" . $regis['nom_doc'] . "</label>
                                    <h5 class='label-identifier'>Numero de Documento</h5>
                                    <label class='data-field'>" . $regis['numeroiden'] . "</label>
                                    
                                    <h4 class='label-identifier'>Documento para Descargar</h4>
                                    <label class='data-field'><a href='../../include/" . $regis['documento'] . "' download>Descargar Documento</a></label>
                                </div>";
                            }
                        } else {
                            $jTableResult['ListPf'] .= "
                                <h5 class='label-identifier'> no hay Usuarios Registrados Interesados en la Oferta</h5>";
                        }
                    } else {
                        $jTableResult['rst'] = "0";
                        $jTableResult['ms'] = "Error en la consulta de usuarios interesados: " . mysqli_error($conn);
                    }
                    // Bloques else if para verificar id_rol, solo si id_estado es 9
               
                        $jTableResult['ListPf'] .= "
                            <div class='modal-footer'>
                                <div class='course-buttons'>
                                    <button class='create-button' id='btn_Inicurso' data-id='" . $registro['id_solicitud'] . "'>Iniciar CURSO</button>
                                    <button class='close-button' type='button'  data-bs-dismiss='modal'>CERRAR</button>
                                </div>
                            </div>
                            ";
                    
                    
                }
                $jTableResult['ListPf'] .= "</div>
                    
            ";
            }
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        echo json_encode($jTableResult);
    break;
    case 'AlertaOferta':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListOf'] = "";
    
        $queryUsuarios = "SELECT 
                        s.id_solicitud,
                        COUNT(uf.id_userprofile) AS total_users,
                        SUM(CASE WHEN u.id_rol = 1 THEN 1 ELSE 0 END) AS role_1_users,
                        SUM(CASE WHEN u.id_rol = 4 THEN 1 ELSE 0 END) AS role_4_users
                    FROM 
                        usersxoferta uf
                    JOIN 
                        solicitud s ON uf.id_solicitud = s.id_solicitud
                    JOIN 
                        userprofile u ON uf.id_userprofile = u.id_userprofile
                    WHERE 
                        s.id_estado = 9";
                        if ($_SESSION['id_rol'] != 3) {
                            $queryUsuarios .= " AND s.id_userprofile='" . $_SESSION['id_userprofile'] . "'";
                        }
                        $queryUsuarios .= " GROUP BY s.id_solicitud;";
        
        $resultado = mysqli_query($conn, $queryUsuarios);
        
        if ($resultado) {
            while ($regis = mysqli_fetch_array($resultado)) {
                if ($regis['role_1_users'] >= 30) {
                    $jTableResult['rst'] = "1";
                    $jTableResult['ms'] .= $regis['id_solicitud'] . ", ";
                }
                if ($regis['role_4_users'] >= 1) {
                    $jTableResult['rst'] = "2";
                    $jTableResult['ms'] .= $regis['id_solicitud'] . ", ";
                }
            }
            // Elimina la última coma y espacio
            $jTableResult['ms'] = rtrim($jTableResult['ms'], ', ');
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        echo json_encode($jTableResult);
    break;
    case 'ListarOferta':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListOf'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
        $query = "SELECT 
            s.id_solicitud,
            s.id_estado, 
            u.nombre, 
            u.id_rol,
            pf.tipo_formacion,
            pf.modalidad,
            pf.nivel_formacion,
            d.nombre_dpto AS nom_dpto, 
            m_municipio.nombre_municipio AS nom_muni, 
            p.nombre_poblado AS nom_vereda, 
            u.nombre_dos,
            u.apellido, 
            au.nombre AS area_usu, 
            ts.nombre AS Nombre_Solicitud, 
            ds.id_tiposolicitud,
            ds.descripcion,
            a.nombre AS nom_area,
            pf.nombre AS nom_pf,
            pf.horas_curso,
            pf.fecha_inicio,
            pf.fecha_cierre
            FROM 
                solicitud s
            LEFT JOIN  
                userprofile u ON s.id_userprofile = u.id_userprofile
            LEFT JOIN   
                detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
            LEFT JOIN
                programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
            LEFT JOIN  
                departamentos d ON u.cod_dpto = d.cod_dpto
            LEFT JOIN  
                municipios m_municipio ON u.cod_municipio = m_municipio.cod_municipio
            LEFT JOIN  
                poblados p ON u.cod_poblado = p.cod_poblado
            LEFT JOIN   
                tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
            LEFT JOIN  
                area au ON u.id_area = au.id_area
            LEFT JOIN  
                area a ON pf.id_area = a.id_area
            LEFT JOIN
                jornada j ON pf.id_jornada = j.id_jornada
            LEFT JOIN
                modalidad m ON pf.id_modalidad = m.id_modalidad
            LEFT JOIN
                nivelformacion nf ON pf.id_nivel_formacion = nf.id_nivel_formacion
            WHERE 
                s.id_solicitud = '$id_solicitud'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['ListOf'] .= "
                <div class='form-container'>
                    <br>
                    <h5><strong>Informacion y Estado</strong></h5>
                    <label class='label-identifier'>Nombre Curso Ofertado</label>
                    <label class='data-field'>" . $registro['nom_pf'] . "</label>
                    <br>
                    <label class='label-identifier'>Jornada Curso Ofertado</label>
                    <label class='data-field'>" . $registro['tipo_formacion'] . "</label>
                    <br>
                    <label class='label-identifier'>Modalidad Curso Ofertado</label>
                    <label class='data-field'>" . $registro['modalidad'] . "</label>
                    <br>
                    <label class='label-identifier'>Nivel Formacion</label>
                    <label class='data-field'>" . $registro['nivel_formacion'] . "</label>
                    <br>
                    <label class='label-identifier'>Fecha Inicio de Curso Ofertado</label>
                    <label class='data-field'>" . $registro['fecha_inicio'] . "</label>
                    <br>
                    <label class='label-identifier'>Fecha Fin de Curso Ofertado</label>
                    <label class='data-field'>" . $registro['fecha_cierre'] . "</label>
                    <br>
                    <label class='label-identifier'>Detalles</label>
                    <br>
                    <textarea class='data-field' id='detalles' name='detalles'>" . $registro['descripcion'] . "</textarea>
                    <br>
                ";

                if ($registro['id_estado'] == '9') {
                    $queryUsuarios = "SELECT u.nombre AS nom_Uof, s.id_solicitud, uf.documento, u.correo, u.celular, td.nombre AS nom_doc, u.numeroiden
                                        FROM usersxoferta uf 
                                        JOIN solicitud s ON uf.id_solicitud = s.id_solicitud
                                        JOIN userprofile u ON uf.id_userprofile = u.id_userprofile
                                        JOIN tipodocumento td ON u.id_doc = td.id_doc
                                        WHERE uf.id_solicitud ='$id_solicitud'";
                    $resultado = mysqli_query($conn, $queryUsuarios);

                    if ($resultado) {
                        if (mysqli_num_rows($resultado) > 0) {
                            while ($regis = mysqli_fetch_array($resultado)) {
                                $jTableResult['rst'] = "1";
                                $jTableResult['ms'] = "Exitoso";
                                $documentURL = str_replace('C:/xampp/htdocs', '', $regis['documento']);

                                $jTableResult['ListOf'] .= "
                                <div id='usuarios'> 
                                    <hr>
                                    <h2 class='label-identifier'>Usuarios Interesados</h2>
                                    <h5 class='label-identifier'>Nombre</h5>
                                    <label class='data-field'>" . $regis['nom_Uof'] . "</label>
                                    <h5 class='label-identifier'>Correo</h5>
                                    <label class='data-field'>" . $regis['correo'] . "</label>
                                    <h5 class='label-identifier'>Telefono</h5>
                                    <label class='data-field'>" . $regis['celular'] . "</label>
                                    <h5 class='label-identifier'>Tipo de Documento</h5>
                                    <label class='data-field'>" . $regis['nom_doc'] . "</label>
                                    <h5 class='label-identifier'>Numero de Documento</h5>
                                    <label class='data-field'>" . $regis['numeroiden'] . "</label>
                                    
                                    <h1 class='label-identifier'>Documento para Descargar</h1>
                                    <label class='data-field'><a href='../../include/" . $regis['documento'] . "' download>Descargar Documento</a></label>
                                </div>";
                            }
                        } else {
                            $jTableResult['ListOf'] .= "
                                <h5 class='label-identifier'>Aun no hay Usuarios Interesados en la Oferta</h5>";
                        }
                    } else {
                        $jTableResult['rst'] = "0";
                        $jTableResult['ms'] = "Error en la consulta de usuarios interesados: " . mysqli_error($conn);
                    }
                    // Bloques else if para verificar id_rol, solo si id_estado es 9
                    if ($registro['id_rol'] != '2') {
                        $jTableResult['ListOf'] .= "
                            <hr>
                            <h3 class='label-identifier'>Asignacion</h3>
                            <h6 class='label-identifier'>Detalle Asignacion</h6>
                            <textarea  class='data-field' id='detalle_respuesta' name='detalles'></textarea>
                            <h6 class='label-identifier'>Responsable</h6>
                            <select id='id_responsable'></select>
                            <div class='course-buttons'>
                                <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                                <button type='button' class='create-button' id='btnGuardarCambios2' data-id='" . $registro['id_solicitud'] . "'>Asignar</button>
                            </div>";
                    } elseif ($registro['id_rol'] == '2') {
                        $jTableResult['ListOf'] .= "
                            <hr>
                            <h3 class='label-identifier'>Asignacion</h3>
                            <h6 class='label-identifier'>Detalle Asignacion</h6>
                            <textarea id='detalle_respuesta' name='detalles' class='data-field'></textarea>
                            <h6 class='label-identifier'>Responsable</h6>
                            <select id='id_responsable'></select>
                            <div class='course-buttons'>
                                <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                                <button type='button' class='create-button' id='btnGuardarCambios2' data-id='" . $registro['id_solicitud'] . "'>Asignar</button>
                            </div>
                            <hr>
                            <h3 class='label-identifier'>Responder</h3>
                            <h6 class='label-identifier'>Ficha</h6>
                            <textarea id='ficha' name='ficha'></textarea>
                            <br>
                            <h6 class='label-identifier'>Detalle Respuesta</h6>
                            <input type='text' id='detalle_respuesta' name='detalles'></input><br/>
                            <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                            <button id='btnAceptarSoliOf' class='create-button' data-id='" . $registro['id_solicitud'] . "'>Dar Respuesta</button>
                            ";
                    } 
                    
                }else  {
                    $jTableResult['ListOf'].='
                    <div class="modal-footer">
                    <button type="button" class="close-button" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="create-button" id="subirNoti2">Subir</button>
                </div>';
                }
                $jTableResult['ListOf'] .= "</div>";
            }
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        echo json_encode($jTableResult);
    break;
    case 'ListarSoliAsesoramiento':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListAA'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
        $query = "SELECT 
                    s.id_solicitud,
                    s.id_estado, 
                    u.nombre, 
                    u.id_rol,
                    j.nombre AS nom_jornada,
                    m.nombre AS nom_modalidad,
                    nf.nombre AS nom_nf,
                    d.nombre_dpto AS nom_dpto, 
                    m_municipio.nombre_municipio AS nom_muni, 
                    p.nombre_poblado AS nom_vereda, 
                    u.nombre_dos,
                    u.apellido, 
                    u.numeroiden,
                    au.nombre AS area_usu, 
                    ts.nombre AS Nombre_Solicitud, 
                    ds.id_tiposolicitud,
                    ds.descripcion,
                    a.nombre AS nom_area,
                    pf.nombre AS nom_pf,
                    pf.horas_curso,
                    pf.fecha_inicio,
                    pf.fecha_cierre,
                    r.nombre AS r_nombre,
                    td.nombre AS td_nombre
                FROM 
                    solicitud s
                LEFT JOIN  
                    userprofile u ON s.id_userprofile = u.id_userprofile
                LEFT JOIN   
                    detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
                LEFT JOIN
                    programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
                LEFT JOIN  
                    departamentos d ON u.cod_dpto = d.cod_dpto
                LEFT JOIN  
                    municipios m_municipio ON u.cod_municipio = m_municipio.cod_municipio
                LEFT JOIN  
                    poblados p ON u.cod_poblado = p.cod_poblado
                LEFT JOIN   
                    tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
                LEFT JOIN  
                    area au ON u.id_area = au.id_area
                LEFT JOIN  
                    area a ON pf.id_area = a.id_area
                LEFT JOIN
                    jornada j ON pf.id_jornada = j.id_jornada
                LEFT JOIN
                    modalidad m ON pf.id_modalidad = m.id_modalidad
                LEFT JOIN
                    nivelformacion nf ON pf.id_nivel_formacion = nf.id_nivel_formacion
                LEFT JOIN
                    rol r ON u.id_rol = r.id_rol
                LEFT JOIN
                    tipodocumento td ON u.id_doc=td.id_doc
                    WHERE 
                        s.id_solicitud = '$id_solicitud'
        ";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['ListAA'] .= "
                    <div class='form-container'>
                        <h1>Datos Usuario</h1><br>
                        <h4 class='label-identifier'>Nombre Usuario</h4>
                        <label class='data-field'>" . $registro['nombre'] . " " . $registro['nombre_dos'] . " " . $registro['apellido'] . "</label><br>
                        <h4 class='label-identifier'>Tipo Documento</h4><br>
                        <label class='data-field'>" . $registro['td_nombre'] . "</label><br>
                        <h4 class='label-identifier'>Numero Documento</h4><br>
                        <label>" . $registro['numeroiden'] . "</label><br>
                        <br>
                        <h4 class='label-identifier'>Rol Usuario</h4>
                            <label class='data-field'>" . $registro['r_nombre'] . "</label>
                            <hr>
                            <h3 class='label-identifier'>Asignacion</h3>
                            <hr>
                            <h6 class='label-identifier'>Detalle Asignacion</h6>
                            <textarea id='detalle_respuesta' name='detalles'></textarea>
                            <h6 class='label-identifier'>Responsable</h6>
                            <select id='id_responsable'></select>
                            <div class='course-buttons'>
                                <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                                <button type='button' class='create-button' id='btnGuardarCambios2' data-id='" . $registro['id_solicitud'] . "'>Asignar</button>
                            </div>
                            <hr>
                            <h3 class='label-identifier'>Responder</h3>
                            <hr>
                            <h6 class='label-identifier'>Detalle Respuesta</h6>
                            <textarea id='detalle_respuesta' name='detalles'></textarea><br/>
                            <button id='btnAceptarSoli' class='create-button' data-id='" . $registro['id_solicitud'] . "'>Dar Respuesta</button>
                    </div>
                ";
            }
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        echo json_encode($jTableResult);
    break;
    case 'aceptarSolicitudOf':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $id_solicitud = $_POST['id_solicitud'];
        $mensaje = $_POST['detalle_respuesta'];
        $correo=$_SESSION['correo'];
        $ficha = isset($_POST['ficha']) && !empty($_POST['ficha']) ? $_POST['ficha'] : NULL;
        // $mensaje = $_POST['mensaje'];
        $query = "UPDATE solicitud s  
          JOIN detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
          JOIN programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
          SET pf.ficha = '$ficha', pf.id_estado = '7',s.id_estado = '7'
          WHERE s.id_solicitud = '$id_solicitud'";

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
    case 'aceptarSolicitud':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
    
        $id_solicitud = $_POST['id_solicitud'];
        $mensaje = $_POST['detalle_respuesta'];
    
        // Actualiza el estado de la solicitud
        $query = "UPDATE solicitud 
                  SET id_estado = 4, fecha_respuesta = NOW() 
                  WHERE id_solicitud = '$id_solicitud'";
    
        // Recupera el correo del usuario relacionado con la solicitud
        $query2 = "SELECT u.correo 
                   FROM solicitud s 
                   JOIN userprofile u ON s.id_userprofile = u.id_userprofile 
                   WHERE s.id_solicitud = '$id_solicitud'";
    
        // Ejecutar la primera consulta para actualizar la solicitud
        if ($result = mysqli_query($conn, $query)) {
            // Ejecutar la segunda consulta para obtener el correo
            $result2 = mysqli_query($conn, $query2);
    
            if ($result2 && mysqli_num_rows($result2) > 0) {
                $row = mysqli_fetch_assoc($result2);
                $correo = $row['correo'];
    
                // Enviar correo usando la función enviarCorreo
                if (enviarCorreo($correo, $mensaje)) {
                    $jTableResult['msj'] = "Solicitud confirmada con éxito y correo enviado.";
                    $jTableResult['rstl'] = "1";
                } else {
                    $jTableResult['msj'] = "Solicitud confirmada, pero no se pudo enviar el correo.";
                    $jTableResult['rstl'] = "0";
                }
            } else {
                $jTableResult['msj'] = "Solicitud confirmada, pero no se pudo obtener el correo.";
                $jTableResult['rstl'] = "0";
            }
    
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
            $jTableResult['msj'] = "Error al confirmar la solicitud.";
            $jTableResult['rstl'] = "0";
        }
    
        print json_encode($jTableResult);
        break;
    
    case 'crgrTipoSolicitud':
        $jTableResult = array();                
        $jTableResult['lisTiposS']="";
        $idRol = $_SESSION['id_rol'];
        if ($_SESSION['id_rol'] == 3) {
            $query = "SELECT id_tiposolicitud, nombre FROM tiposolicitud WHERE id_tiposolicitud <> 4 AND id_tiposolicitud <> 23";
        } else {
            $query = "SELECT id_tiposolicitud, nombre 
                        FROM tiposolicitud 
                        WHERE id_rol = '" . $_SESSION['id_rol'] . "' 
                        OR adso = '" . $_SESSION['id_rol'] . "' 
                        OR adso2 = '" . $_SESSION['id_rol'] . "';";
        }
        $resultado = mysqli_query($conn, $query);
        
        while($registro = mysqli_fetch_array($resultado)) {
            // Agregar los radio buttons al resultado
            $jTableResult['lisTiposS'] .= "<input type='radio' name='tipo_solicitud' value='" . $registro['id_tiposolicitud'] . "'>" . $registro['nombre'] . "<br>";
        }
        // Devolver el resultado como JSON
        print json_encode($jTableResult);
    break;
    case 'eliminarSolicitud':
                $jTableResult = array();
                $jTableResult['rstl'] = "";
                $jTableResult['msj'] = "";
            
                $query = "DELETE FROM solicitud WHERE id_solicitud = '" . $_POST['id_solicitud'] . "'";
                if ($result = mysqli_query($conn, $query)) {
                    mysqli_commit($conn);
                    $jTableResult['msj'] = "Solicitud eliminada con éxito.";
                    $jTableResult['rstl'] = "1";
                } else {
                    mysqli_rollback($conn);
                    $jTableResult['msj'] = "Error al eliminar la solicitud.";
                    $jTableResult['rstl'] = "0";
                }
                print json_encode($jTableResult);
    break;
    case 'denegarSolicitud':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $id_solicitud = $_POST['id_solicitud'];
        $mensaje = $_POST['detalle_cancel'];
        $correo=$_SESSION['correo'];
        $query = "UPDATE solicitud 
        SET id_estado = 5, fecha_cancelada = NOW()
        WHERE id_solicitud = '$id_solicitud'";
        if ($result = mysqli_query($conn, $query)) {
            mysqli_commit($conn);
            @mail($correo, 'Solicitud Cancelada', $mensaje);
            $jTableResult['msj'] = "Solicitud Denegada con éxito.";
            $jTableResult['rstl'] = "1";
        } else {
            mysqli_rollback($conn);
            $jTableResult['msj'] = "Error al Denegar la solicitud.";
            $jTableResult['rstl'] = "0";
        }
        print json_encode($jTableResult);
    break;
    case 'actualizarSolicitud':
        $id_solicitud = $_POST['id_solicitud'];
        $responsable = isset($_POST['id_responsable']) ? $_POST['id_responsable'] : null;
        $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;
        $updateFields = [];
        if ($responsable !== null) {
            $updateFields[] = "solicitud.id_responsable = '$responsable'";
        }
        if ($descripcion !== null) {
            $updateFields[] = "detallesolicitud.descripcion = '$descripcion'";
        }
    
        if (!empty($updateFields)) {
            $updateQuery = implode(", ", $updateFields);
            $query = "UPDATE solicitud
                        JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                        JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud
                        SET solicitud.id_estado=6, $updateQuery
                        WHERE solicitud.id_solicitud = '$id_solicitud'";
    
            if ($result = mysqli_query($conn, $query)) {
                    $query = "UPDATE solicitud
                                SET fecha_asignada = NOW()
                                WHERE id_solicitud = '$id_solicitud'";
                    $resultado = mysqli_query($conn, $query);
                mysqli_commit($conn);
                $jTableResult['msj'] = "Realizado Con Exito.";
                $jTableResult['rstl'] = "1";
            } else {
                mysqli_rollback($conn);
                $jTableResult['msj'] = "Cancelado.";
                $jTableResult['rstl'] = "0";
            }
        } else {
            $jTableResult['msj'] = "No se proporcionaron datos para actualizar.";
            $jTableResult['rstl'] = "0";
        }
    
        print json_encode($jTableResult);
    break;
    case 'IniCursoAsign':
        $jTableResult = array();
        $jTableResult['msj'] = "";
        $jTableResult['rstl'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
        $ficha = isset($_POST['ficha']) ? $_POST['ficha'] : null;
        $jornada = isset($_POST['id_jornada']) ? $_POST['id_jornada'] : null;
        $matriculados = isset($_POST['matriculados']) ? $_POST['matriculados'] : null;
        $updateFields = [];
        if ($ficha !== null) {
            $updateFields[] = "pf.ficha = '$ficha'";
        }
        if ($jornada !== null) {
            $updateFields[] = "pf.id_jornada = '$jornada'";
        }
        if ($matriculados !== null) {
            $updateFields[] = "pf.matriculados = '$matriculados'";
        }
    
        if (!empty($updateFields)) {
            $updateQuery = implode(", ", $updateFields);
            $query = "UPDATE solicitud s
                        JOIN
                            detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
                        JOIN
                            programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
                        SET s.id_estado=4, pf.id_estado =7, $updateQuery
                        WHERE s.id_solicitud = '$id_solicitud'";
    
            if ($result = mysqli_query($conn, $query)) {
                    $query = "UPDATE solicitud
                                SET fecha_asignada = NOW()
                                WHERE id_solicitud = '$id_solicitud'";
                    $resultado = mysqli_query($conn, $query);
                mysqli_commit($conn);
                $jTableResult['msj'] = "Realizado Con Exito.";
                $jTableResult['rstl'] = "1";
            } else {
                mysqli_rollback($conn);
                $jTableResult['msj'] = "Cancelado.";
                $jTableResult['rstl'] = "0";
            }
        } else {
            $jTableResult['msj'] = "No se proporcionaron datos para actualizar.";
            $jTableResult['rstl'] = "0";
        }
    
        print json_encode($jTableResult);
    break;
    case 'MisSoli':
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
                                            $jTableResult['tabla'] .= '<th>Tipo Solicitud</th>
                                                                        <th>Solicitante</th>';
                                        } else {
                                            $jTableResult['tabla'] .= '<th>Tipo Solicitud</th>';
                                        }
                    $jTableResult['tabla'] .= '<th>Descripción</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                    <tbody>';
                    
                    // Consulta para verificar si el id_userprofile está presente en la tabla solicitud
                    $query = "SELECT id_userprofile FROM solicitud WHERE id_userprofile='" . $_SESSION['id_userprofile'] . "'";
                    $resultado = mysqli_query($conn, $query);
                    // Verificar si se encontraron resultados
                    if (mysqli_num_rows($resultado) > 0) {
                        // Consulta para obtener las solicitudes y sus detalles asociados
                        $busqueda = "SELECT solicitud.id_solicitud, detallesolicitud.descripcion, solicitud.id_estado, estado.nombre AS nombre_estado,
                                        tiposolicitud.nombre AS nombre_tipo, userprofile.nombre AS nombre_autor
                                    FROM solicitud
                                    JOIN estado ON solicitud.id_estado = estado.id_estado 
                                    JOIN userprofile ON solicitud.id_userprofile = userprofile.id_userprofile
                                    JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                                    JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud
                                    WHERE  solicitud.id_estado != 3 AND solicitud.id_estado != 9 AND solicitud.id_estado != 6 
                                    ";
                                    if ($_SESSION['id_rol'] != 3) {
                                        $busqueda .= " AND solicitud.id_userprofile='" . $_SESSION['id_userprofile'] . "'";
                                    }
                                    $busqueda .= " ORDER BY solicitud.id_solicitud DESC"; 
                        $result = mysqli_query($conn, $busqueda);
                        if (mysqli_num_rows($result) > 0) {
                            $jTableResult['rs'] = "1";  
                            while($registro = mysqli_fetch_array($result)) {
                                $jTableResult['tabla'] .= "<tr>
                                                            <td>" . $registro['id_solicitud'] . "</td>
                                                            <td>" . $registro['nombre_tipo'] . "</td>";
                                                                if ($_SESSION['id_rol'] == 3) {
                                                                    $jTableResult['tabla'] .= "<td>" . $registro['nombre_autor'] . "</td>";
                                                                }
                                                                $jTableResult['tabla'] .= "<td>" . $registro['descripcion'] . "</td>
                                                                                            <td>" . $registro['nombre_estado'] . "</td>
                                                                                            <td>";
                                                                if ($_SESSION['id_rol'] == 3) {
                                                                    $jTableResult['tabla'] .= '<button id="btnEditarSoli" class="btn btn-warning btn-sm  local" data-bs-toggle="modal" data-bs-target="#editSolicitudModal" data-id="' . $registro['id_solicitud'] . '">Ver Soli</button>
                                                                                                <button id="modalCancel" class="btn btn-danger btn-sm  local" data-bs-toggle="modal" data-bs-target="#cancelSolicitudModal" data-id="' . $registro['id_solicitud'] . '">Denegar Soli</button>
                                                                                                <button id="btnAceptarSoli" class="btn btn-success cursor:pointer;  local" data-id="' . $registro['id_solicitud'] . '">Aceptar Soli</button>';
                                                                } elseif ($registro['id_estado'] == 4){
                                                                    $jTableResult['tabla'].='<button id="detalleSolicitud" class="btn btn-warning btn-sm  local" data-bs-toggle="modal" data-bs-target="#detallesolicitud" data-id="' . $registro['id_solicitud'] . '">Ver Solicitud</button>';
                                                                }
                                                                else {
                                                                    $jTableResult['tabla'] .= '<button id="btnEditarSoli" class="btn btn-warning btn-sm  local" data-bs-toggle="modal" data-bs-target="#editSolicitudModal"  data-id="' . $registro['id_solicitud'] . '">Editar</button>
                                                                                                <button id="btnEliminarSoli" class="btn btn-danger btn-sm  local">Cancelar</button>';
                                                                }
                                $jTableResult['tabla'] .= "</td></tr>";
                            }
                    $jTableResult['tabla'] .= "</tbody></table></div></div>";
                }else{
                    $jTableResult['rs'] = "2";
                    $jTableResult['Ms'] = "Tu Solicitud Aun no tiene una respuesta.";
                }
            } else {
                $jTableResult['rs'] = "3";
                $jTableResult['Ms'] = "No has realizado ninguna Solicitud.";
            }
        
            print json_encode($jTableResult);
    break;
    case 'MisSoli_sin':
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
                                    $jTableResult['tabla'] .= '<th>Tipo Solicitud</th>
                                                                <th>Solicitante</th>';
                                } else {
                                    $jTableResult['tabla'] .= '<th>Tipo Solicitud</th>';
                                }
        $jTableResult['tabla'] .= '<th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        <tbody>';
    
        // Consulta para obtener las solicitudes y sus detalles asociados
        $busqueda = "SELECT solicitud.id_solicitud, detallesolicitud.descripcion, solicitud.id_estado, estado.nombre AS nombre_estado, tiposolicitud.id_tiposolicitud AS idtiposolicitud, 
                            tiposolicitud.nombre AS nombre_tipo, userprofile.nombre AS nombre_autor, detallesolicitud.id_programaformacion, programaformacion.nombre AS nombre_pf
                        FROM solicitud
                            LEFT JOIN estado ON solicitud.id_estado = estado.id_estado
                            LEFT JOIN userprofile ON solicitud.id_userprofile = userprofile.id_userprofile
                            LEFT JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                            LEFT JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud
                            LEFT JOIN programaformacion ON detallesolicitud.id_programaformacion = programaformacion.id_programaformacion
                        WHERE solicitud.id_estado = 3 
                        ORDER BY solicitud.id_solicitud DESC";
        $result = mysqli_query($conn, $busqueda);
        
        if (mysqli_num_rows($result) > 0) {
            $jTableResult['rs'] = "1";
    
            while ($registro = mysqli_fetch_array($result)) {
                $programaformacion = $registro['nombre_pf']; // Obtener el nombre del programa de formación

                // Consulta para contar cuántas veces se repite el nombre del programa de formación en las solicitudes
                $query2 = "SELECT COUNT(*) AS total
                    FROM solicitud s
                    JOIN detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
                    JOIN programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
                    WHERE pf.nombre = '$programaformacion' AND s.id_estado = 3";
                $result2 = mysqli_query($conn, $query2);
                $row2 = mysqli_fetch_assoc($result2);
                $total = $row2['total'];
                    
                // Construcción de la tabla
                $jTableResult['tabla'] .= "<tr>
                                            <td>" . $registro['id_solicitud'] . "</td>
                                            <td>" . $registro['nombre_tipo'] . "</td>";
                
                if ($_SESSION['id_rol'] == 3) {
                    $jTableResult['tabla'] .= "<td>" . $registro['nombre_autor'] . "</td>";
                }
    
                $jTableResult['tabla'] .= "<td>" . $registro['descripcion'] . "</td>
                                            <td>" . $registro['nombre_estado'] . "</td>
                                            <td>";
                if ($_SESSION['id_rol'] == 3) {
                    $jTableResult['tabla'] .= '<button id="modalCancel" class="btn btn-danger btn-sm local" data-bs-toggle="modal" data-bs-target="#cancelSolicitudModal" data-id="' . $registro['id_solicitud'] . '">Denegar Soli</button>';
    
                    if ($registro['idtiposolicitud'] == 1) {
                        $jTableResult['tabla'] .= ' <button id="btn_asign" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#AceptSolicitudModal" data-id="' . $registro['id_solicitud'] . '">Asignar</button>';
                        if ($total > 5) {
                            $jTableResult['tabla'] .= '<button id="ModalCursoOf" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#OfertAlert" data-id="' . $registro['id_solicitud'] . '">Ofertar</button>';
                        }
                    } elseif ($registro['idtiposolicitud'] == 2) {
                        $jTableResult['tabla'] .= ' <button id="btn_pf" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#AceptSolicitud2Modal" data-id="' . $registro['id_solicitud'] . '">Asignar</button>';
                    } elseif ($registro['idtiposolicitud'] == 3) {
                        $jTableResult['tabla'] .= ' <button id="BtnAsesoramientoA" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#AceptSolicitud3Modal" data-id="' . $registro['id_solicitud'] . '">Responder</button>';
                    } elseif ($registro['idtiposolicitud'] == 4) {
                        $jTableResult['tabla'] .= ' <button id="btn_subir" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#AceptSolicitud2Modal" data-id="' . $registro['id_solicitud'] . '">Subir</button>';
                    } elseif ($registro['idtiposolicitud'] == 5) {
                        $jTableResult['tabla'] .= ' <button id="Ecompetencia" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#AceptSolicitud5Modal" data-id="' . $registro['id_solicitud'] . '">Responder</button>';
                    } elseif ($registro['idtiposolicitud'] == 10) {
                        $jTableResult['tabla'] .= ' <button id="instructorProto" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#AceptSolicitud4Modal" data-id="' . $registro['id_solicitud'] . '">Responder</button>';
                    } elseif ($registro['idtiposolicitud'] == 23) {
                        $jTableResult['tabla'] .= '<button id="detalleOferta" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#OfertaModal" data-id="' . $registro['id_solicitud'] . '">Mirar Oferta</button>';
                    }
                }
    
                $jTableResult['tabla'] .= "</td></tr>";
            }
            $jTableResult['tabla'] .= "</tbody></table></div></div>"; // Cerrar la tabla y los elementos HTML
        }
    
        print json_encode($jTableResult);
    break;
    case 'MisOfertas':
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
                            <th>ID</th>
                            <th>Nombre Programa</th>';
                                if ($_SESSION['id_rol'] == 3) {
                                    $jTableResult['tabla'] .= '
                                                                <th>Autor Oferta</th>';
                                }
            $jTableResult['tabla'] .= '
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            <tbody>';
            // Consulta para verificar si el id_userprofile está presente en la tabla solicitud
                // Consulta para obtener las solicitudes y sus detalles asociados
                $busqueda = "SELECT solicitud.id_solicitud, detallesolicitud.descripcion, solicitud.id_estado, estado.nombre AS nombre_estado,
                                tiposolicitud.nombre AS nombre_tipo, userprofile.nombre AS nombre_autor,userprofile.correo, programaformacion.nombre AS nombre_pf, programaformacion.ficha
                            FROM solicitud
                            JOIN estado ON solicitud.id_estado = estado.id_estado 
                            JOIN userprofile ON solicitud.id_userprofile = userprofile.id_userprofile
                            JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                            JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud
                            JOIN programaformacion ON detallesolicitud.id_programaformacion = programaformacion.id_programaformacion
                            WHERE (detallesolicitud.id_categoria = 3 OR detallesolicitud.id_tiposolicitud= 1) AND solicitud.id_estado = 9 
                ";
                    if ($_SESSION['id_rol'] != 3) {
                    $busqueda .= " AND solicitud.id_userprofile='" . $_SESSION['id_userprofile'] . "'";
                    }
                    $busqueda .= " ORDER BY solicitud.id_solicitud DESC"; 
                    $result = mysqli_query($conn, $busqueda);
                if (mysqli_num_rows($result) > 0) {
                    $jTableResult['rs'] = "1";  
                    while($registro = mysqli_fetch_array($result)) {
                        $jTableResult['tabla'] .= "<tr>
                                                    <td>" . $registro['id_solicitud'] . "</td>
                                                    <td>" . $registro['nombre_pf'] . "</td>";
                                                        if ($_SESSION['id_rol'] == 3) {
                                                            $jTableResult['tabla'] .= "<td>
                                                            
                                                            " . $registro['nombre_autor'] . "<br>
                                                            " . $registro['correo'] . "
                                                            </td>";
                                                        }
                                                        $jTableResult['tabla'] .= "
                                                                                    <td>" . $registro['nombre_estado'] . "</td>";
                                                        $jTableResult['tabla'] .= '<td> 
                                                                                    <button id="modalCancel" class="btn btn-danger btn-sm  local" data-bs-toggle="modal" data-bs-target="#cancelSolicitudModal" data-id="' . $registro['id_solicitud'] . '">Cancelar Oferta</button>
                                                                                    <button id="detalleOferta" class="btn btn-success  local" data-bs-toggle="modal" data-bs-target="#OfertaModal"  cursor:pointer;" data-id="' . $registro['id_solicitud'] . '">Mirar Oferta</button>';
                        $jTableResult['tabla'] .= "</td></tr>";
                    }
                    $jTableResult['tabla'] .= "</tbody></table></div>";
            }else{
                $jTableResult['rs'] = "2";
                $jTableResult['Ms'] = "Tu Solicitud Aun no tiene una respuesta.";
            }
    
        print json_encode($jTableResult);
    break;
    case 'buscarSolicitud':
        $jTableResult = array();
        $jTableResult['rstl']="";
        $jTableResult['msj']="";
        $jTableResult['listaSoli']='
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>';
                            if ($_SESSION['id_rol'] == 3) {
                                $jTableResult['listaSoli'] .= '<th>Tipo Solicitud</th>
                                                            <th>Solicitante</th>';
                            } else {
                                $jTableResult['listaSoli'] .= '<th>Tipo Solicitud</th>';
                            }
                            $jTableResult['listaSoli'].='
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    <tbody>';
                    $id_rol = $_SESSION['id_rol'];
                    $id_userprofile = $_SESSION['id_userprofile'];
                    $dato_txt = $_POST['dato_txt'];
                    
                    // Construir la cláusula WHERE en función del rol del usuario
                    $whereClause = "";
                    if ($id_rol != 3) {
                        $whereClause .= "solicitud.id_userprofile = '$id_userprofile' AND ";
                    }
                    // Agregar el filtro de búsqueda
                    $whereClause .= "(userprofile.nombre = '$dato_txt' 
                                        OR tiposolicitud.nombre = '$dato_txt' 
                                        OR estado.nombre = '$dato_txt' 
                                        OR userprofile.apellido = '$dato_txt' 
                                        OR userprofile.correo = '$dato_txt' 
                                        OR userprofile.numeroiden = '$dato_txt' 
                                        OR userprofile.nombre_dos = '$dato_txt')";
                    // Construir la consulta completa
                    $query = "SELECT 
                                solicitud.id_solicitud, 
                                userprofile.nombre AS solicitante_nom, 
                                userprofile.nombre_dos AS solicitante_nomdos, 
                                userprofile.correo AS solicitante_correo, 
                                userprofile.apellido AS solicitante_apellido, 
                                userprofile.numeroiden AS solicitante_iden, 
                                estado.nombre AS nombre_estado, 
                                tiposolicitud.nombre AS nombre_tipo, 
                                detallesolicitud.descripcion, 
                                detallesolicitud.id_tiposolicitud,
                                solicitud.id_estado
                            FROM solicitud
                            JOIN estado ON solicitud.id_estado = estado.id_estado 
                            JOIN userprofile ON solicitud.id_userprofile = userprofile.id_userprofile
                            JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                            JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud
                            WHERE $whereClause 
                            ORDER BY solicitud.id_solicitud DESC";
            $resultado = mysqli_query($conn, $query);
            $numero = mysqli_num_rows($resultado);
            if($numero==0){
                $jTableResult['listaSoli']="<thead><tr><th scope='col'>&nbsp;&nbsp&nbsp;&nbsp;No existen coincidencias.</th></tr></thead>";
                $jTableResult['msj']= "NO EXISTEN DATOS.";
                $jTableResult['rslt']= "0";						
            }else{
                while($registro = mysqli_fetch_array($resultado)){
                    $jTableResult['listaSoli'].="<tr>";
                    $jTableResult['listaSoli'].="<tr>
                                                    <td>" . $registro['id_solicitud'] . "</td>
                                                    <td>" . $registro['nombre_tipo'] . "</td>";
                                                        if ($_SESSION['id_rol'] == 3) {
                                                            $jTableResult['listaSoli'] .= "<td>" . $registro['solicitante_nom'] . "</td>";
                                                        }
                                                        $jTableResult['listaSoli'] .= "<td>" . $registro['descripcion'] . "</td>
                                                                                    <td>" . $registro['nombre_estado'] . "</td>
                                                                                    <td>";
                                                            if ($registro['id_estado'] == 4){
                                                                    $jTableResult['listaSoli'].='<button id="detalleSolicitud" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#detallesolicitud" data-id="' . $registro['id_solicitud'] . '">Ver Soli</button>';
                                                                }
                                                        else {
                                                            $jTableResult['listaSoli'] .= '<button id="btnEditarSoli" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSolicitudModal" data-id="' . $registro['id_solicitud'] . '">Editar</button>
                                                                                        <button id="btnEliminarSoli" class="btn btn-danger btn-sm">Cancelar</button>';
                                                        }
                    $jTableResult['listaSoli'].="</tr>";
                }
                $jTableResult['msj']= "";
                $jTableResult['rstl']= "1";						
            }
        print json_encode($jTableResult);
    break;
    case 'FiltroSolicitud':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $jTableResult['listaSoli'] = '
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>';
        if ($_SESSION['id_rol'] == 3) {
            $jTableResult['listaSoli'] .= '<th>Tipo Solicitud</th>
                                            <th>Solicitante</th>';
        } else {
            $jTableResult['listaSoli'] .= '<th>Tipo Solicitud</th>';
        }
        $jTableResult['listaSoli'] .= '
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                <tbody>';
    
        $id_rol = $_SESSION['id_rol'];
        $id_userprofile = $_SESSION['id_userprofile'];
        $dato_txt = $_POST['dato_filtro'];
    
        // Construir la cláusula WHERE en función del rol del usuario
        $whereClause = "";
        if ($id_rol != 3) {
            $whereClause .= "solicitud.id_userprofile = '$id_userprofile' AND ";
        }
    
        // Manejar múltiples filtros
        $filtros = explode(',', $dato_txt);
        $filterConditions = array();
        foreach ($filtros as $filtro) {
            $filterConditions[] = "estado.nombre = '$filtro'";
        }
        $whereClause .= '(' . implode(' OR ', $filterConditions) . ')';
    
        // Construir la consulta completa
        $query = "SELECT 
                    solicitud.id_solicitud, 
                    userprofile.nombre AS solicitante_nom, 
                    userprofile.nombre_dos AS solicitante_nomdos, 
                    userprofile.correo AS solicitante_correo, 
                    userprofile.apellido AS solicitante_apellido, 
                    userprofile.numeroiden AS solicitante_iden, 
                    estado.nombre AS nombre_estado, 
                    tiposolicitud.nombre AS nombre_tipo, 
                    detallesolicitud.descripcion, 
                    detallesolicitud.id_tiposolicitud,
                    solicitud.id_estado
                FROM solicitud
                JOIN estado ON solicitud.id_estado = estado.id_estado 
                JOIN userprofile ON solicitud.id_userprofile = userprofile.id_userprofile
                JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud
                WHERE $whereClause 
                ORDER BY solicitud.id_solicitud DESC";
    
        $resultado = mysqli_query($conn, $query);
        $numero = mysqli_num_rows($resultado);
    
        if ($numero == 0) {
            $jTableResult['listaSoli'] = "<thead><tr><th scope='col'>&nbsp;&nbsp&nbsp;&nbsp;No existe respuesta de Filtro.</th></tr></thead>";
            $jTableResult['msj'] = "NO EXISTEN DATOS PARA ESTE FILTRO.";
            $jTableResult['rslt'] = "0";						
        } else {
            while ($registro = mysqli_fetch_array($resultado)) {
                $jTableResult['listaSoli'] .= "<tr>
                                                    <td>" . $registro['id_solicitud'] . "</td>
                                                    <td>" . $registro['nombre_tipo'] . "</td>";
                if ($_SESSION['id_rol'] == 3) {
                    $jTableResult['listaSoli'] .= "<td>" . $registro['solicitante_nom'] . "</td>";
                }
                $jTableResult['listaSoli'] .= "<td>" . $registro['descripcion'] . "</td>
                                                <td>" . $registro['nombre_estado'] . "</td>
                                                <td>";
                if ($registro['id_estado'] == 4) {
                    $jTableResult['listaSoli'] .= '<button id="detalleSolicitud" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#detallesolicitud" data-id="' . $registro['id_solicitud'] . '">Ver Soli</button>';
                } else {
                    $jTableResult['listaSoli'] .= '
                                                    <button id="btnEliminarSoli" class="btn btn-danger btn-sm">Cancelar</button>';
                }
                $jTableResult['listaSoli'] .= "</tr>";
            }
            $jTableResult['msj'] = "";
            $jTableResult['rstl'] = "1";						
        }
    
        print json_encode($jTableResult);
    break;
    case 'asignaciones':
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
                                $jTableResult['tabla'] .= '<th>Tipo Solicitud</th>
                                                            <th>Solicitante</th>';
                            } else {
                                $jTableResult['tabla'] .= '<th>Tipo Solicitud</th>';
                            }
        $jTableResult['tabla'] .= '<th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        <tbody>';
    
        // Consulta para verificar si el id_userprofile está presente en la tabla solicitud
        $query = "SELECT id_responsable FROM solicitud WHERE id_responsable='" . $_SESSION['id_userprofile'] . "'";
        $resultado = mysqli_query($conn, $query);
        // Verificar si se encontraron resultados
        if (mysqli_num_rows($resultado) > 0) {
            // Consulta para obtener las solicitudes y sus detalles asociados
            $busqueda = "SELECT solicitud.id_solicitud, detallesolicitud.descripcion, solicitud.id_estado, estado.nombre AS nombre_estado, tiposolicitud.id_tiposolicitud AS idtiposolicitud, 
                                tiposolicitud.nombre AS nombre_tipo, userprofile.nombre AS nombre_autor
                            FROM solicitud
                            JOIN estado ON solicitud.id_estado = estado.id_estado
                            JOIN userprofile ON solicitud.id_userprofile = userprofile.id_userprofile
                            JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                            JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud
                            WHERE solicitud.id_responsable='" . $_SESSION['id_userprofile'] . "' AND solicitud.id_estado=6
                            ORDER BY solicitud.id_solicitud DESC";
            $result = mysqli_query($conn, $busqueda);
            
            if (mysqli_num_rows($result) > 0) {
                $jTableResult['rs'] = "1";
                // Recorrer los resultados y construir la lista de opciones
                while($registro = mysqli_fetch_array($result)) {
                    $jTableResult['tabla'] .= "<tr>
                                                <td>" . $registro['id_solicitud'] . "</td>
                                                <td>" . $registro['nombre_tipo'] . "</td>";
                                                    
                                                    $jTableResult['tabla'] .= "<td>" . $registro['descripcion'] . "</td>
                                                                                <td>" . $registro['nombre_estado'] . "</td>
                                                                                <td>";
                                                    $jTableResult['tabla'] .= '
                                                                                <button id="modalCancel" class="btn btn-danger btn-sm  local" data-bs-toggle="modal" data-bs-target="#cancelSolicitudModal" data-id="' . $registro['id_solicitud'] . '">Denegar Soli</button>
                                                                                <button  class="btn btn-success  local"';
                                                    if ($registro['idtiposolicitud'] == 1) {
                                                                    $jTableResult['tabla'] .= ' id="btn_asign" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="' . $registro['id_solicitud'] . '" > Dar Respuesta</button>';
                                                                }
                                                                elseif ($registro['idtiposolicitud'] == 2) {
                                                                    $jTableResult['tabla'] .= ' id="" data-bs-toggle="modal" data-bs-target="#AceptSolicitudModal" data-id="' . $registro['id_solicitud'] . '"> Asignar</button>';
                                                                }
                                                                elseif ($registro['idtiposolicitud'] == 3) {
                                                                    $jTableResult['tabla'] .= ' id="btn_LRa" data-bs-toggle="modal" data-bs-target="#ListRaAsignModal" data-id="' . $registro['id_solicitud'] . '"> Dar Respuesta</button>';
                                                                }elseif ($registro['idtiposolicitud'] == 5) {
                                                                    $jTableResult['tabla'] .= ' id="btn_LEc"  data-bs-toggle="modal" data-bs-target="#ListEcAsignModal" data-id="' . $registro['id_solicitud'] . '">  Dar Respuesta</button>';
                                                                }elseif ($registro['idtiposolicitud'] == 23) {
                                                                    $jTableResult['tabla'] .= ' <button id="btn_pf" class="btn btn-success local" data-bs-toggle="modal" data-bs-target="#AceptSolicitud2Modal" data-id="' . $registro['id_solicitud'] . '">Dar Respuesta</button>';
                                                                }
                    $jTableResult['tabla'] .= "</td></tr>";
                }
                $jTableResult['tabla'] .= "</tbody></table></div>"; // Cerrar la tabla y los elementos HTML
            } else {
                $jTableResult['rs'] = "2";
                $jTableResult['Ms'] = "No hay solicitudes Asignadas";
            }
        }
    
        print json_encode($jTableResult);
    break;
    case 'Asignado_Crear':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['Asignform'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
                        $query = "SELECT 
                                    s.id_solicitud, 
                                    u.nombre,
                                    u.id_rol, 
                                    d.nombre_dpto AS nom_dpto, 
                                    m.nombre_municipio AS nom_muni, 
                                    p.nombre_poblado AS nom_vereda, 
                                    et.nombre AS nom_estado, 
                                    u.nombre_dos,
                                    u.apellido,
                                    ur.nombre_dos AS nom_dosR,
                                    ur.apellido AS apellidoR,
                                    ur.nombre AS nomR,
                                    e.nombre AS nom_Empresa, 
                                    ts.nombre AS Nombre_Solicitud, 
                                    ds.id_tiposolicitud,
                                    ds.descripcion,
                                    ds.documento,
                                    a.nombre AS nom_area,
                                    pf.nombre AS nom_pf,
                                    pf.modalidad,
                                    pf.tipo_formacion,
                                    pf.nivel_formacion,
                                    pf.fecha_inicio,
                                    pf.fecha_cierre,
                                    pf.horas_curso
                                FROM 
                                    solicitud s
                                LEFT JOIN  
                                    estado et ON s.id_estado = et.id_estado
                                LEFT JOIN  
                                    userprofile ur ON s.id_responsable = ur.id_userprofile
                                LEFT JOIN  
                                    userprofile u ON s.id_userprofile = u.id_userprofile
                                LEFT JOIN  
                                    detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
                                LEFT JOIN  
                                    departamentos d ON ds.cod_dpto = d.cod_dpto
                                LEFT JOIN  
                                    municipios m ON ds.cod_municipio = m.cod_municipio
                                LEFT JOIN  
                                    poblados p ON ds.cod_poblado = p.cod_poblado
                                LEFT JOIN  
                                    empresa e ON s.id_empresa = e.id_empresa
                                LEFT JOIN  
                                    tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
                                LEFT JOIN 
                                    area a ON ds.id_area = a.id_area
                                LEFT JOIN
                                    programaformacion pf ON ds.id_programaformacion = pf.id_programaformacion
                                WHERE 
                                    s.id_solicitud = '$id_solicitud';
                        ";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['Asignform'] .= "
                    <div class='container'>";
                        if ($registro['id_rol'] == 4){
                            echo
                            "
                            <label class='label-identifier'>Empresa</label>
                            <label class='data-field'>" . $registro['nom_Empresa'] . "</label>";
                        }
                        
                        $jTableResult['Asignform'] .= "
                        <div class='course-data-field'>
                            <label class='label-identifier'>Solicitante</label>
                            <label id='solicitante' class='form-control'>" . $registro['nombre'] . " " . $registro['nombre_dos'] . " " . $registro['apellido'] . "</label>
                        </div>
                        <br>
                        <h5 class='label-identifier'><strong>Ubicación Sugerida Para Solicitud</strong></h5>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Departamento</h6>
                            <h6 class='form-control'>" . $registro['nom_dpto'] . "</h6>
                        </div>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Municipio</h6>
                            <h6 class='form-control'>" . $registro['nom_muni'] . "</h6>
                        </div>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Vereda</h6>
                            <h6 class='form-control'>" . $registro['nom_vereda'] . "</h6>
                        </div>
                        <br>
                        <div class='course-data-field'>
                            <label class='label-identifier' for='detalles'>Detalles</label>
                            <br>
                            <textarea id='detalles' name='detalles' class='form-control'>" . $registro['descripcion'] . "</textarea>
                        </div>
                        <br>
                        <div class='course-data-field'>
                            <h4 class='label-identifier'>Documento para Descargar</h4>
                            <label class='data-field'><a href='../../include/" . $registro['documento'] . "' download>Descargar Documento</a></label>
                        </div>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Responsable</h6>
                            <label class='form-control'>" . $registro['nomR'] . "  " . $registro['apellidoR'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <h6 class='label-identifier'>Estado</h6>
                            <label class=form-control'>" . $registro['nom_estado'] . "</label>
                        </div><br>
                        <h2 class='label-identifier'>DATOS DE CURSO</h2>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Nombre Curso</label>
                            <label class='data-field' id='nombre_programa2'>" . $registro['nom_pf'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Fecha inicio</label>
                            <input type='date' id='fecha_inicio2' class='form-control' />
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Fecha cierre</label>
                            <input type='date' id='fecha_cierre2' class='form-control' />
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Numero de ficha</label>
                            <input type='number' id='ficha2' value='' class='form-control' />
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Horas de curso</label>
                            <label class='data-field' id='horas_curso_label2'>" . $registro['horas_curso'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Modalidad</label>
                            <label class='data-field' id='id_modalidad_label2'>" . $registro['modalidad'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Jornada</label>
                            <select id='id_jornada' class='form-control'>
                            </select>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Nivel de Formacion</label>
                            <label class='data-field' id='nivel_formacion_label2'>" . $registro['nivel_formacion'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Tipo de Formacion</label>
                            <label class='data-field' id='tipo_formacion_label2'>" . $registro['tipo_formacion'] . "</label>
                        </div>
                        <div class='course-data-field'>
                            <label class='label-identifier'>Matriculados</label>
                            <input type='number' id='matriculados' value='0' class='form-control' />
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class='course-buttons'>
                            <button class='create-button' id='btn_curso' data-id='" . $registro['id_solicitud'] . "'>CREAR CURSO</button>
                            <button class='close-button' type='button'  data-bs-dismiss='modal'>CERRAR</button>
                        </div>
                    </div>
                ";
                }
            }else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        
        echo json_encode($jTableResult);
    break;
    case 'registroCursoNew':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
    
        // Preparar la consulta SQL para insertar en programaformacion
        $query = "INSERT INTO programaformacion (nombre, fecha_cierre, fecha_inicio, modalidad, id_jornada, nivel_formacion,tipo_formacion, matriculados, id_estado, horas_curso, ficha) 
                    VALUES (?, ?, ?, ?,?, ?, ?, ?, 7, ?, ?)";
    
        // Preparar la consulta SQL
        if ($stmt = mysqli_prepare($conn, $query)) {
            // Bindear los parámetros
            mysqli_stmt_bind_param($stmt, "ssssissiii",
                $_POST['nombre_programa'],
                $_POST['fecha_cierre'],
                $_POST['fecha_inicio'],
                $_POST['modalidad'],
                $_POST['id_jornada'],
                $_POST['nivel_formacion'],
                $_POST['tipo_formacion'],
                $_POST['matriculados'],
                $_POST['horas_curso'],
                $_POST['ficha']
            );
    
            // Ejecutar la consulta
            if (mysqli_stmt_execute($stmt)) {
                // Obtener el último id insertado
                $id_programaformacion = mysqli_insert_id($conn);
    
                // Preparar la consulta para actualizar la solicitud
                $query_update = "UPDATE solicitud s
                JOIN detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
                SET s.id_estado = 4, 
                    s.fecha_respuesta = NOW(), 
                    ds.id_programaformacion = ?
                WHERE s.id_solicitud = ?";
    
                // Inicializar la declaración
                if ($stmt_update = mysqli_prepare($conn, $query_update)) {
                    // Enlazar los parámetros
                    mysqli_stmt_bind_param($stmt_update, 'ii', $id_programaformacion, $id_solicitud);
    
                    // Ejecutar la declaración
                    if (mysqli_stmt_execute($stmt_update)) {
                        mysqli_commit($conn);
                        $jTableResult['msj'] = "Registro guardado y solicitud actualizada con éxito.";
                        $jTableResult['rstl'] = "1";
                    } else {
                        mysqli_rollback($conn);
                        $jTableResult['msj'] = "Error al actualizar la solicitud.";
                        $jTableResult['rstl'] = "0";
                    }
    
                    // Cerrar el statement de actualización
                    mysqli_stmt_close($stmt_update);
                } else {
                    $jTableResult['msj'] = "Error al preparar la consulta para actualizar la solicitud.";
                    $jTableResult['rstl'] = "0";
                }
            } else {
                mysqli_rollback($conn);
                $jTableResult['msj'] = "Error al guardar en programaformacion.";
                $jTableResult['rstl'] = "0";
            }
    
            // Cerrar el statement de inserción
            mysqli_stmt_close($stmt);
        } else {
            $jTableResult['msj'] = "Error al preparar la consulta para guardar en programaformacion.";
            $jTableResult['rstl'] = "0";
        }
    
        // Enviar la respuesta en formato JSON
        echo json_encode($jTableResult);
    break;
    case 'noticiaCreado':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $jTableResult['noticia'] = "";
        $id_solicitud = $_POST['id_solicitud'];
    
        // Asegúrate de que la variable $id_solicitud está correctamente escapada para evitar inyección SQL
        $id_solicitud = mysqli_real_escape_string($conn, $id_solicitud);
    
        // Imprimir la consulta para depuración
            $query = "SELECT 
                            solicitud.id_solicitud,
                            detallesolicitud.imagen AS imagen, 
                            detallesolicitud.nombre AS titulo, 
                            detallesolicitud.fecha_inicio AS fecha_mostrada, 
                            detallesolicitud.descripcion,
                            detallesolicitud.id_detallesolicitud
                        FROM 
                            solicitud
                        JOIN 
                            detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                        WHERE 
                            detallesolicitud.id_tiposolicitud = 4 AND 
                            solicitud.id_estado = 3 AND 
                            solicitud.id_solicitud = '$id_solicitud'";
        
    
        $result = mysqli_query($conn, $query);
    
        // Verificar si se encontraron resultados
        if (mysqli_num_rows($result) > 0) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['msj'] = "Noticia Creada con Exito.";
                $jTableResult['rstl'] = "1";
                // Concatenar el contenido HTML para las tarjetas
                $jTableResult['noticia'] .= '
                <div class="row blog-item px-3 pb-5">
                    <div class="col-md-5" id="foto">
                        <img src="../../include/' . $registro["imagen"] . '" class="img-fluid mb-4 mb-md-0" accordingly alt=" Image">
                    </div>
                    <div class="col-md-7">
                        <h3 class="mt-md-4 px-md-3 mb-2 py-2 bg-white font-weight-bold">' . $registro["titulo"] . '</h3>
                        
                         <div class="d-flex mb-3">
                            <p>' . $registro["descripcion"] . '</p>                                       
                        </div>
                        <div class="d-flex mb-3">
                            <small class="mr-2 text-muted"><i class="fa fa-calendar-alt"></i> ' . $registro["fecha_mostrada"] . '</small>     
                        </div>
                        
                        
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="close-button" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="create-button" id="subirNoti">Subir</button>
                </div>';
            }
        } else {
            mysqli_rollback($conn);
            $jTableResult['msj'] = "Error al Crear la noticia.";
            $jTableResult['rstl'] = "0";
        }
        print json_encode($jTableResult);
    break;
    case 'Cancel':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['msj'] = "";
        $jTableResult['cancel'] = "";
        $id_solicitud = $_POST['id_solicitud'];
        // Identifica que la variable $id_solicitud está correctamente escapada para evitar inyección SQL
        $id_solicitud = mysqli_real_escape_string($conn, $id_solicitud);
            $query = "SELECT 
                            s.id_solicitud
                        FROM 
                            solicitud s
                        WHERE 
                            s.id_solicitud = '$id_solicitud'";
        $result = mysqli_query($conn, $query);
        // Verificar si se encontraron resultados
        if (mysqli_num_rows($result) > 0) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['msj'] = "Cancel Creada con Exito.";
                $jTableResult['rst'] = "1";
                // Concatenar el contenido HTML para las tarjetas
                $jTableResult['cancel'] .= '
                <div class="row mt-3">
                            <div class="col-sm-12">
                                <h6 class="modal-title">Motivo Denegacion...</h6>
                                <input type="text" class="form-control modal-textbox" id="detalle_cancel" name="detalle_cancel"
                                    placeholder="Motivo Denegacion Solicitud" title="Motivo Denegacion">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="close-button" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarSoli" type="button" class="create-button" data-id="' . $registro['id_solicitud'] . '">Confirmar</button>
                    </div>';
            }
        } else {
            mysqli_rollback($conn);
            $jTableResult['msj'] = "Error al Crear.";
            $jTableResult['rst'] = "0";
        }
        print json_encode($jTableResult);
    break;
    case 'OfertarCursoModal':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['msj'] = "";
        $jTableResult['modal'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
    
        $query = "SELECT s.id_solicitud FROM solicitud s WHERE s.id_solicitud = '$id_solicitud'";
        $result = mysqli_query($conn, $query);
    
        if (mysqli_num_rows($result) > 0) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['msj'] = "Modal generado con éxito.";
                $jTableResult['rst'] = "1";
                $jTableResult['modal'] .= '
                    <div class="modal-body">
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <h6 class="modal-title">Mensaje de Confirmación...</h6>
                                <textarea class="form-control modal-textbox" id="detalle_cancel" name="detalle_cancel"
                                    placeholder="Motivo Solicitud" title="Motivo"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="close-button" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="create-button" id="subirNoti3" data-id="' . $registro['id_solicitud'] . '">Guardar Cambios</button>
                    </div>';
            }
        } else {
            $jTableResult['msj'] = "Error al generar el modal.";
            $jTableResult['rst'] = "0";
        }
        
        print json_encode($jTableResult);
    break;
    case 'instructorProto':
            $jTableResult = array();
            $jTableResult['rstl'] = "";
            $jTableResult['msj'] = "";
            $jTableResult['tarjeta'] = "";
            $id_solicitud = $_POST['id_solicitud'];
            $query = "SELECT 
                        solicitud.id_solicitud,
                        solicitud.id_estado,
                        j.nombre AS nombre_jornada,
                        r.nombre AS nombre_rol, 
                        userprofile.nombre, 
                        userprofile.id_rol,
                        userprofile.nombre_dos, 
                        userprofile.apellido, 
                        detallesolicitud.id_tiposolicitud,
                        detallesolicitud.twitter, 
                        detallesolicitud.facebook, 
                        detallesolicitud.youtube, 
                        detallesolicitud.instagram
                        FROM 
                            solicitud
                            JOIN 
                                userprofile ON solicitud.id_userprofile = userprofile.id_userprofile
                            JOIN 
                                detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                            JOIN
                                rol r ON userprofile.id_rol = r.id_rol
                            JOIN
                                jornada j ON detallesolicitud.id_jornada = j.id_jornada
                        WHERE 
                            detallesolicitud.id_tiposolicitud = 10 AND 
                            solicitud.id_estado = 3 AND solicitud.id_solicitud = '$id_solicitud';
                            "; 
            
            $result = mysqli_query($conn, $query);
            
            // Verificar si se encontraron resultados
            if (mysqli_num_rows($result) > 0) {
                while($registro = mysqli_fetch_array($result)) {
                    $jTableResult['msj'] = "Instructor Creado con Exito.";
                    $jTableResult['rstl'] = "1";
                    $jTableResult['tarjeta'] .= "
                    
                    
                <div class='row'>
                    <div class='col-sm-5'>
                        <ul class='navbar-nav'>
                            <div class='container'>
                            <div class='card'>
                                <div class='front'>
                                    
                                    
                                    <a href='perfil.php'>
                                        <center><img src='' alt='' width='100' height='100' class='profile-photo'>
                                        </center> </a>
                                    <p class='heading'> ". $registro["nombre"] . "". $registro["nombre_dos"] . "". $registro["apellido"] . " </p>
                                    <p class='follow'>". $registro["nombre_jornada"] . "
                                    </p></div>
                                    <div class='back'>
                                    <p class='heading'>". $registro["nombre_rol"] . "</p>
                                    
                                    <a href='../../archivos/vista/enviarmensaje.php'>
                                        <center><img src='' alt='' width='100' height='100' class='profile-photo'>
                                        </center> </a>
                                    <div class='icons'>
                                        
                                

                                    </div>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                    <button type='button' class='create-button' id='CrearInstru' data-id='" . $registro['id_solicitud'] . "'>Subir</button>
                </div>
                        
                        
                    ";
                }
            } else {
                mysqli_rollback($conn);
                $jTableResult['msj'] = "Error al Crear el Instructor.";
                $jTableResult['rstl'] = "0";
            }
            print json_encode($jTableResult);
    break;
    case 'CrearInstru':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $id_solicitud = $_POST['id_solicitud'];
        $query = "UPDATE solicitud s
            JOIN userprofile up ON up.id_userprofile = s.id_userprofile
            SET s.fecha_respuesta = NOW(),
                up.id_rol = '2',
                s.id_estado ='4'
            WHERE s.id_solicitud = '$id_solicitud'";

    
        $result = mysqli_query($conn, $query);
    
        // Verificar si la consulta fue exitosa
        if ($result) {
            // Verificar si se encontraron resultados
            if (mysqli_affected_rows($conn) > 0) {
                $jTableResult['msj'] = "Instructor Creado con Exito.";
                $jTableResult['rstl'] = "1";
            } else {
                mysqli_rollback($conn);
                $jTableResult['msj'] = "Error al Crear el Instructor.";
                $jTableResult['rstl'] = "0";
            }
        } else {
            // Manejar el error de la consulta
            $jTableResult['msj'] = "Error en la consulta: " . mysqli_error($conn);
            $jTableResult['rstl'] = "0";
        }
    
        print json_encode($jTableResult);
    break;
    case 'ListarEcompetencia':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListEc'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
        $query = "SELECT 
            td.nombre AS nom_doc,
            r.nombre AS nom_rol,
            s.id_solicitud,
            s.id_estado, 
            u.nombre,
            u.nombre_dos,
            u.apellido,
            u.numeroiden, 
            u.id_rol,
            d.nombre_dpto AS nom_dpto, 
            m_municipio.nombre_municipio AS nom_muni, 
            p.nombre_poblado AS nom_vereda, 
            u.nombre_dos,
            u.apellido, 
            ts.nombre AS Nombre_Solicitud, 
            ds.id_tiposolicitud,
            ds.descripcion
            FROM 
                solicitud s
            LEFT JOIN  
                userprofile u ON s.id_userprofile = u.id_userprofile
            LEFT JOIN   
                detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
            LEFT JOIN  
                departamentos d ON u.cod_dpto = d.cod_dpto
            LEFT JOIN  
                municipios m_municipio ON u.cod_municipio = m_municipio.cod_municipio
            LEFT JOIN  
                poblados p ON u.cod_poblado = p.cod_poblado
            LEFT JOIN   
                tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
            LEFT JOIN   
                tipodocumento td ON u.id_doc = td.id_doc
            LEFT JOIN   
                rol r ON u.id_rol = r.id_rol
            WHERE 
                s.id_solicitud = '$id_solicitud'
        ";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['ListEc'] .= "
                    <div class='form-container'>
                        <h1>Datos Usuario</h1><br>
                        <h4 class='label-identifier'>Nombre Usuario</h4>
                        <label class='data-field'>" . $registro['nombre'] . " " . $registro['nombre_dos'] . " " . $registro['apellido'] . "</label><br>
                        <h4 class='label-identifier'>Tipo Documento</h4><br>
                        <label class='data-field'>" . $registro['nom_doc'] . "</label><br>
                        <h4 class='label-identifier'>Numero Documento</h4><br>
                        <label>" . $registro['numeroiden'] . "</label><br>
                        <br>
                        <h4 class='label-identifier'>Rol Usuario</h4>
                            <label class='data-field'>" . $registro['nom_rol'] . "</label>
                            <hr>
                            <h3 class='label-identifier'>Asignacion</h3>
                            <hr>
                            <h6 class='label-identifier'>Detalle Asignacion</h6>
                            <textarea id='detalle_asignacion' name='detalles'></textarea>
                            <h6 class='label-identifier'>Responsable</h6>
                            <select id='id_responsable'></select>
                            <div class='course-buttons'>
                                <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                                <button type='button' class='create-button' id='btnGuardarCambios2' data-id='" . $registro['id_solicitud'] . "'>Asignar</button>
                            </div>
                            <hr>
                            <h3 class='label-identifier'>Responder</h3>
                            <hr>
                            <h6 class='label-identifier'>Detalle Respuesta</h6>
                            <textarea id='detalle_respuesta' name='detalles'></textarea><br/>
                            <button id='btnAceptarSoli' class='create-button' data-id='" . $registro['id_solicitud'] . "'>Dar Respuesta</button>
                    </div>";
            }
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        echo json_encode($jTableResult);
    break;
    case 'asignacion_Listar_Ec':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListEc'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
        $query = "SELECT 
            td.nombre AS nom_doc,
            r.nombre AS nom_rol,
            s.id_solicitud,
            s.id_estado, 
            u.nombre,
            u.nombre_dos,
            u.apellido,
            u.numeroiden, 
            u.id_rol,
            d.nombre_dpto AS nom_dpto, 
            m_municipio.nombre_municipio AS nom_muni, 
            p.nombre_poblado AS nom_vereda, 
            u.nombre_dos,
            u.apellido, 
            ts.nombre AS Nombre_Solicitud, 
            ds.id_tiposolicitud,
            ds.descripcion
            FROM 
                solicitud s
            LEFT JOIN  
                userprofile u ON s.id_userprofile = u.id_userprofile
            LEFT JOIN   
                detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
            LEFT JOIN  
                departamentos d ON u.cod_dpto = d.cod_dpto
            LEFT JOIN  
                municipios m_municipio ON u.cod_municipio = m_municipio.cod_municipio
            LEFT JOIN  
                poblados p ON u.cod_poblado = p.cod_poblado
            LEFT JOIN   
                tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
            LEFT JOIN   
                tipodocumento td ON u.id_doc = td.id_doc
            LEFT JOIN   
                rol r ON u.id_rol = r.id_rol
            WHERE 
                s.id_solicitud = '$id_solicitud'
        ";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['ListEc'] .= "
                    <div class='form-container'>
                        <h1>Datos Usuario</h1><br>
                        <h4 class='label-identifier'>Nombre Usuario</h4>
                        <label class='data-field'>" . $registro['nombre'] . " " . $registro['nombre_dos'] . " " . $registro['apellido'] . "</label><br>
                        <h4 class='label-identifier'>Tipo Documento</h4><br>
                        <label class='data-field'>" . $registro['nom_doc'] . "</label><br>
                        <h4 class='label-identifier'>Numero Documento</h4><br>
                        <label>" . $registro['numeroiden'] . "</label><br>
                        <br>
                        <h4 class='label-identifier'>Rol Usuario</h4>
                            <label class='data-field'>" . $registro['nom_rol'] . "</label>
                            <hr>
                            <h3 class='label-identifier'>Responder</h3>
                            <h6 class='label-identifier'>Detalle Respuesta</h6>
                            <textarea id='detalle_respuesta' name='detalles'></textarea><br/>
                            <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                            <button id='btnAceptarSoli' class='create-button' data-id='" . $registro['id_solicitud'] . "'>Dar Respuesta</button>
                    </div>";
            }
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        echo json_encode($jTableResult);
    break;
    case 'asignacion_Listar_Ra':
        $jTableResult = array();
        $jTableResult['rst'] = "";
        $jTableResult['ms'] = "";
        $jTableResult['ListRa'] = "";
        $id_solicitud = mysqli_real_escape_string($conn, $_POST['id_solicitud']);
        $query = "SELECT 
            td.nombre AS nom_doc,
            r.nombre AS nom_rol,
            s.id_solicitud,
            s.id_estado, 
            u.nombre,
            u.nombre_dos,
            u.apellido,
            u.numeroiden, 
            u.id_rol,
            d.nombre_dpto AS nom_dpto, 
            m_municipio.nombre_municipio AS nom_muni, 
            p.nombre_poblado AS nom_vereda, 
            u.nombre_dos,
            u.apellido, 
            ts.nombre AS Nombre_Solicitud, 
            ds.id_tiposolicitud,
            ds.descripcion
            FROM 
                solicitud s
            LEFT JOIN  
                userprofile u ON s.id_userprofile = u.id_userprofile
            LEFT JOIN   
                detallesolicitud ds ON s.id_detallesolicitud = ds.id_detallesolicitud
            LEFT JOIN  
                departamentos d ON u.cod_dpto = d.cod_dpto
            LEFT JOIN  
                municipios m_municipio ON u.cod_municipio = m_municipio.cod_municipio
            LEFT JOIN  
                poblados p ON u.cod_poblado = p.cod_poblado
            LEFT JOIN   
                tiposolicitud ts ON ds.id_tiposolicitud = ts.id_tiposolicitud
            LEFT JOIN   
                tipodocumento td ON u.id_doc = td.id_doc
            LEFT JOIN   
                rol r ON u.id_rol = r.id_rol
            WHERE 
                s.id_solicitud = '$id_solicitud'
        ";
        $result = mysqli_query($conn, $query);
        if ($result) {
            while ($registro = mysqli_fetch_array($result)) {
                $jTableResult['rst'] = "1";
                $jTableResult['ms'] = "Exitoso";
                $jTableResult['ListRa'] .= "
                    <div class='form-container'>
                        <h1>Datos Usuario</h1><br>
                        <h4 class='label-identifier'>Nombre Usuario</h4>
                        <label class='data-field'>" . $registro['nombre'] . " " . $registro['nombre_dos'] . " " . $registro['apellido'] . "</label><br>
                        <h4 class='label-identifier'>Tipo Documento</h4><br>
                        <label class='data-field'>" . $registro['nom_doc'] . "</label><br>
                        <h4 class='label-identifier'>Numero Documento</h4><br>
                        <label>" . $registro['numeroiden'] . "</label><br>
                        <br>
                        <h4 class='label-identifier'>Rol Usuario</h4>
                            <label class='data-field'>" . $registro['nom_rol'] . "</label>
                            <hr>
                            <h3 class='label-identifier'>Responder</h3>
                            <h6 class='label-identifier'>Detalle Respuesta</h6>
                            <textarea id='detalle_respuesta' name='detalles'></textarea><br/>
                            <button type='button' class='close-button' data-bs-dismiss='modal'>Cerrar</button>
                            <button id='btnAceptarSoli' class='create-button' data-id='" . $registro['id_solicitud'] . "'>Dar Respuesta</button>
                    </div>";
            }
        } else {
            $jTableResult['rst'] = "0";
            $jTableResult['ms'] = "Error al obtener los datos.";
        }
        echo json_encode($jTableResult);
    break;
    case 'exportarSolicitud':
        // Limpiar cualquier salida previa
        ob_clean();
        
        // Realiza la consulta para obtener los datos que deseas exportar
        $solicitudes = obtenerSolicitud();
    
        if (!empty($solicitudes)) {
            // Nombre del archivo con timestamp
            $filename = "solicitudes_" . date('YmdHis') . ".xls";
        
            // Establece los encabezados para forzar la descarga del archivo
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Expires: 0");
        
            // Abre un flujo de salida (output stream)
            $output = fopen("php://output", "w");
        
            // Escribe los nombres de las columnas manualmente
            $columnas = ["Nombre", "Apellido", "Correo Electrónico", "Tipo Solicitud", 
            "Fecha Creacion Solicitud", "Poblacion", "Fecha Asignada", "Nombre Responsable", "Correo Responsable",
            "Fecha Cancelada", "Fecha Respuesta"];
            fputcsv($output, $columnas, "\t");
        
            // Escribe los valores de cada fila, eliminando filas con datos vacíos
            foreach ($solicitudes as $solicitud) {
                $fila = [
                    $solicitud['nom_user'],
                    $solicitud['apellido'],
                    $solicitud['correo_U'],
                    $solicitud['nom_Soli'],
                    date('Y-m-d', strtotime($solicitud['fecha_creacion'])),
                    $solicitud['nom_pblc'],
                    date('Y-m-d', strtotime($solicitud['fecha_asignada'])),
                    $solicitud['nom_Ures'],
                    $solicitud['correo_Ures'],
                    date('Y-m-d', strtotime($solicitud['fecha_cancelada'])),
                    date('Y-m-d', strtotime($solicitud['fecha_respuesta']))
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