<?php
//https://getbootstrap.com/docs/5.0/components/modal/
include_once('conex.php');
header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();

$conn=Conectarse();
function generarClave($longitud = 8) {
	$caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$longitud_caracteres = strlen($caracteres);
	$clave = '';
	for ($i = 0; $i < $longitud; $i++) {
		$clave .= $caracteres[rand(0, $longitud_caracteres - 1)];
	}
	return $clave;
}

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        $mail->Subject = 'Restablecimiento de Clave';
        $mail->Body    = $nueva_contraseña;

        // Enviar el correo
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        return false;
    }
}

switch ($_REQUEST['action']) 
	{
		case 'confirmar':
			$jTableResult = array();
			$jTableResult['validacion'] = "";
			$jTableResult['estado'] = "";
		
			// Buscar el hash de la contraseña basada en correo y número de identificación
			$query = "SELECT id_userprofile, id_estado, nombre, apellido, id_rol, correo, numeroiden, clave 
					  FROM userprofile
					  WHERE correo = ? 
					  AND numeroiden = ?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ss", $_POST['correo'], $_POST['numeroiden']);
			$stmt->execute();
			$result = $stmt->get_result();
		
			if ($result->num_rows == 0) {
				$jTableResult['validacion'] = "no";
			} else {
				$user = $result->fetch_assoc();
		
				// Verificar la contraseña usando password_verify
				if (password_verify($_POST['clave'], $user['clave'])) {
					$jTableResult['validacion'] = "si";
		
					if ($user['id_estado'] == '1') {
						$jTableResult['estado'] = "A";
						$_SESSION['usuLog'] = $user['nombre'] . " " . $user['apellido'];
						$_SESSION['id_userprofile'] = $user['id_userprofile'];
						$_SESSION['id_rol'] = $user['id_rol'];
						$_SESSION['correo'] = $user['correo'];
					} else {
						$jTableResult['estado'] = "I";
					}
				} else {
					$jTableResult['validacion'] = "no"; // Contraseña incorrecta
				}
			}
		
			$stmt->close();
			print json_encode($jTableResult);
		break;
		case 'actualizarContraseña':
			$jTableResult = array();
			$jTableResult['validacion'] = "";
			$jTableResult['estado'] = "";
		
			// Obtener la nueva contraseña y el id del usuario desde el POST
			$nuevaContraseña = $_POST['nuevaContraseña'];
			$idUsuario = $_SESSION['id_userprofile'];
		
			// Validar que la nueva contraseña no esté vacía
			if (empty($nuevaContraseña)) {
				$jTableResult['validacion'] = "no";
				$jTableResult['msj'] = "La nueva contraseña no puede estar vacía.";
			} else {
				// Cifrar la nueva contraseña
				$nuevaContraseñaHash = password_hash($nuevaContraseña, PASSWORD_BCRYPT);
		
				// Actualizar la contraseña en la base de datos
				$query = "UPDATE userprofile 
						  SET clave = ?
						  WHERE id_userprofile = ?";
				$stmt = $conn->prepare($query);
				$stmt->bind_param("si", $nuevaContraseñaHash, $idUsuario);
				
				if ($stmt->execute()) {
					$jTableResult['estado'] = "A";
					$jTableResult['validacion'] = "si";
					$jTableResult['msj'] = "Contraseña actualizada con éxito.";
				} else {
					$jTableResult['validacion'] = "no";
					$jTableResult['msj'] = "Error al actualizar la contraseña.";
				}
		
				$stmt->close();
			}
		
			print json_encode($jTableResult);
		break;
		case 'registroUsuNew': 
			$jTableResult = array();
			$jTableResult['rstl'] = "";
			$jTableResult['msj'] = "";
		
			// Encriptar la contraseña
			$clave = password_hash($_POST['clave_registro'], PASSWORD_BCRYPT);
		
			// Construir la consulta SQL
			$id_tpdoc = isset($_POST['id_tpdoc']) && !empty($_POST['id_tpdoc']) ? $_POST['id_tpdoc'] : NULL;
			$numeroiden_registro = isset($_POST['numeroiden_registro']) && !empty($_POST['numeroiden_registro']) ? $_POST['numeroiden_registro'] : NULL;
			$clave = isset($clave) && !empty($clave) ? $clave : NULL; // Asumiendo que $clave ya está definida previamente
			$nameusu = isset($_POST['nameusu']) && !empty($_POST['nameusu']) ? $_POST['nameusu'] : NULL;
			$cod_poblacion_regis = isset($_POST['cod_poblacion_regis']) && !empty($_POST['cod_poblacion_regis']) ? $_POST['cod_poblacion_regis'] : NULL;
			$id_genero = isset($_POST['id_genero']) && !empty($_POST['id_genero']) ? $_POST['id_genero'] : NULL;
			$celular = isset($_POST['celular']) && !empty($_POST['celular']) ? $_POST['celular'] : NULL;
			$correo_registro = isset($_POST['correo_registro']) && !empty($_POST['correo_registro']) ? $_POST['correo_registro'] : NULL;
			$apellidoUsu = isset($_POST['apellidoUsu']) && !empty($_POST['apellidoUsu']) ? $_POST['apellidoUsu'] : NULL;
			$cod_dpto = isset($_POST['cod_dpto']) && !empty($_POST['cod_dpto']) ? $_POST['cod_dpto'] : NULL;
			$cod_municipio = isset($_POST['cod_municipio']) && !empty($_POST['cod_municipio']) ? $_POST['cod_municipio'] : NULL;
			$cod_poblado = isset($_POST['cod_poblado']) && !empty($_POST['cod_poblado']) ? $_POST['cod_poblado'] : NULL;

			$query = "INSERT INTO userprofile SET
				id_doc = ".($id_tpdoc !== NULL ? "'$id_tpdoc'" : "NULL").", 
				numeroiden = ".($numeroiden_registro !== NULL ? "'$numeroiden_registro'" : "NULL").", 
				clave = ".($clave !== NULL ? "'$clave'" : "NULL").", 
				nombre = ".($nameusu !== NULL ? "'$nameusu'" : "NULL").", 
				fecha_registro = NOW(),
				id_estado = 1,
				id_rol = 5,
				cod_poblacion = ".($cod_poblacion_regis !== NULL ? "'$cod_poblacion_regis'" : "NULL").",
				id_genero = ".($id_genero !== NULL ? "'$id_genero'" : "NULL").",
				celular = ".($celular !== NULL ? "'$celular'" : "NULL").",
				correo = ".($correo_registro !== NULL ? "'$correo_registro'" : "NULL").",
				apellido = ".($apellidoUsu !== NULL ? "'$apellidoUsu'" : "NULL").", 
				cod_dpto = ".($cod_dpto !== NULL ? "'$cod_dpto'" : "NULL").", 
				cod_municipio = ".($cod_municipio !== NULL ? "'$cod_municipio'" : "NULL").", 
				cod_poblado = ".($cod_poblado !== NULL ? "'$cod_poblado'" : "NULL").";";
		
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
		case 'registroUsuNewE': 
			$jTableResult = array();
			$jTableResult['rstl']="";
			$jTableResult['msj']="";
			$clave = password_hash($_POST['clave_registro'], PASSWORD_BCRYPT);
			$query = "SELECT MAX(id_empresa) as lastId FROM empresa;";
			if ($arreglo=mysqli_query($conn,$query)){
				while($result=mysqli_fetch_array($arreglo)){
					$varid=$result['lastId'];
					$query="INSERT INTO userprofile SET
						id_doc='".$_POST['id_tpdoc']."', 
						numeroiden ='".$_POST['numeroiden_registro']."', 
						clave ='".$clave."', 
						nombre ='".$_POST['nameusu']."', 
						nombre_dos ='".$_POST['nombre_dos']."', 
						fecha_registro = NOW(),
						id_estado = 1,
						id_rol = 4,
						id_empresa = '".$varid."' ,
						id_genero = '".$_POST['id_genero']."',
						celular = '".$_POST['celular']."',
						correo ='".$_POST['correo_registro']."',
						apellido ='".$_POST['apellidoUsu']."', 
						apellido_dos ='".$_POST['apellidoUsu_dos']."';";
				if($result= mysqli_query($conn,$query)){
						mysqli_commit($conn);
						
						$jTableResult['msj']= "Registro guardado con exito.";
						$jTableResult['rstl']= "1"; }
				else{
						mysqli_rollback($conn);
						$jTableResult['msj']= "Error al guardar.";				
						$jTableResult['rstl']= "0";  }
				}
			}
				
			print json_encode($jTableResult);
		break;
		case 'registroEmpNew': 
			$jTableResult = array();
			$jTableResult['rstl']="";
			$jTableResult['msj']="";
				$query="INSERT INTO empresa SET 
						nombre='".$_POST['nombre_empresa']."', 
						Nit ='".$_POST['numeroiden_empresa']."', 
						telefono ='".$_POST['telefono_empresa']."', 
						correo ='".$_POST['correo_empresa']."',
						id_tipodoc = 3;";
				if($result= mysqli_query($conn,$query)){
						mysqli_commit($conn);
						
						$jTableResult['msj']= "Registro guardado con exito.";
						$jTableResult['rstl']= "1"; }
				else{
						mysqli_rollback($conn);
						$jTableResult['msj']= "Error al guardar.";				
						$jTableResult['rstl']= "0";  }
			print json_encode($jTableResult);
		break;
		case 'confirmarCcontraseña':
			$jTableResult = array();
			$jTableResult['validacion'] = "";
			$jTableResult['estado'] = "";
		
			// Buscar el hash de la contraseña basada en número de identificación y el id de usuario en la sesión
			$query = "SELECT id_userprofile, numeroiden, clave, id_estado 
					  FROM userprofile
					  WHERE numeroiden = ? 
					  AND id_userprofile = ?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("si", $_POST['numeroiden'], $_SESSION['id_userprofile']);
			$stmt->execute();
			$result = $stmt->get_result();
		
			if ($result->num_rows == 0) {
				$jTableResult['validacion'] = "no";
			} else {
				$user = $result->fetch_assoc();
		
				// Verificar la contraseña usando password_verify
				if (password_verify($_POST['contraseña'], $user['clave'])) {
					$jTableResult['validacion'] = "si";
					$jTableResult['estado'] = $user['id_estado'] == '1' ? "A" : "I";
				} else {
					$jTableResult['msj'] = "Contraseña Incorrecta"; // Contraseña incorrecta
				}
			}
		
			$stmt->close();
			print json_encode($jTableResult);
		break;
		
		case 'actualizarusuario':
			
			$jTableResult = array(); 
			$jTableResult['rstl'] = "";
			$jTableResult['msj'] = "";
		
			// Verificar si todas las claves necesarias están definidas en $_POST
			$requiredKeys = ['nombre', 'nombre_dos', 'clave', 'estadoUsu', 'apellido', 'apellido_dos', 'numeroDocumento', 'correoUsu', 'celular', 'cod_dpto', 'cod_municipio', 'cod_poblado', 'id_doc', 'direccion', 'fechaNacimientoUsu', 'correo_sena'];
			$allKeysExist = true;
		
			foreach ($requiredKeys as $key) {
				if (!array_key_exists($key, $_POST)) {
					$allKeysExist = false;
					break;
				}
			}
		
			if ($allKeysExist) {
				// Asignar valores de $_POST a variables
				$nombre = $_POST['nombre']; 
				$id_doc = $_POST['id_doc']; 
				$nombre_dos = $_POST['nombre_dos'];
				$clave = $_POST['clave'];
				$apellido = $_POST['apellido'];
				$estadoUsu = $_POST['estadoUsu'];
				$apellido_dos = $_POST['apellido_dos'];
				$numeroDocumento = $_POST['numeroDocumento'];
				$correoUsu = $_POST['correoUsu'];
				$celular = $_POST['celular'];
				$cod_dpto = $_POST['cod_dpto'];
				$cod_municipio = $_POST['cod_municipio'];
				$cod_poblado = $_POST['cod_poblado'];
				$direccion = $_POST['direccion'];
				$fechaNacimientoUsu = $_POST['fechaNacimientoUsu'];
				$correo_sena = $_POST['correo_sena'];
		
				// Consulta SQL para actualizar el perfil del usuario
				$query = "UPDATE userprofile SET 
					nombre = '" . $nombre . "',
					nombre_dos = '" . $nombre_dos . "',
					apellido = '" . $apellido . "',
					id_doc = '" . $id_doc . "',
					numeroiden = '" . $numeroDocumento . "',
					correo = '" . $correoUsu . "',
					clave = '" . $clave . "',
					id_estado = '" . $estadoUsu . "',
					apellido_dos = '" . $apellido_dos . "',
					celular = '" . $celular . "',
					cod_dpto = '" . $cod_dpto . "',
					cod_municipio = '" . $cod_municipio . "',
					cod_poblado = '" . $cod_poblado . "',
					direccion = '" . $direccion . "',
					fechaNacimientoUsu = '" . $fechaNacimientoUsu . "',
					correo_sena = '" . $correo_sena . "'
					WHERE id_userprofile = '" . $_SESSION['id_userprofile'] . "'";
		
				// Ejecutar la consulta y verificar el resultado
				if (mysqli_query($conn, $query)) {
					mysqli_commit($conn);
					$jTableResult['msj'] = "Registro actualizado con éxito.";
					$jTableResult['rstl'] = "1";
				} else {
					mysqli_rollback($conn);
					$jTableResult['msj'] = "Error al actualizar el registro: " . mysqli_error($conn);
					$jTableResult['rstl'] = "0";
				}
			} else {
				// Si faltan claves requeridas, maneja el error
				$jTableResult['msj'] = "Error: Campos del formulario incompletos.";
				$jTableResult['rstl'] = "0";
			}
		
			// Devuelve el resultado como JSON
			echo json_encode($jTableResult);
		break;

		case 'salir':	
			$jTableResult = array();
				unset($_SESSION['usuLog']);
				unset($_SESSION['id_userprofile']);
				unset($_SESSION['id_rol']);
				unset($_SESSION['correo']);
				session_destroy();						
			print json_encode($jTableResult);
		break;
		case 'PublicarNoticia': 
			$jTableResult = array();
			$jTableResult['rstl']="";
			$jTableResult['msj']="";
				$query="INSERT INTO detallesolicitud SET
						
						
						id_tiposolicitud = 4,
					
						titulo = '".$_POST['titulo']."',
						
						id_imagen = '".$_POST['id_imagen']."',
						fecha_inicio = '".$_POST['id_fecha_inicio']."',
						fecha_fin = '".$_POST['id_fecha_fin']."',
						url = '".$_POST['id_url']."',
						
						
						descripcion = '".$_POST['descripcion']."';";
						
				if($result= mysqli_query($conn,$query)){
						mysqli_commit($conn);
						$jTableResult['msj']= "Registro guardado con exito.";
						$jTableResult['rstl']= "1"; }
				else{
						mysqli_rollback($conn);
						$jTableResult['msj']= "Error al guardar.";				
						$jTableResult['rstl']= "0";  }
			print json_encode($jTableResult);
		break;
// Restablecer contraseña
		case 'RestablecerContraseña':
				$jTableResult = array();
				$jTableResult['rstl'] = "";
				$jTableResult['msj'] = "";

				if (!isset($_POST['numeroiden']) || !isset($_POST['correo'])) {
					$jTableResult['msj'] = "Todos los campos son obligatorios.";
					$jTableResult['rstl'] = "0";
					print json_encode($jTableResult);
					exit;
				}

				$identificacion = $_POST['numeroiden'];
				$correo = $_POST['correo'];

			// Verificar si existe un usuario con los datos proporcionados
				$query = "SELECT id_userprofile FROM userprofile WHERE numeroiden = ? AND correo = ?";
					if ($stmt = mysqli_prepare($conn, $query)) {
						mysqli_stmt_bind_param($stmt, "ss", $identificacion, $correo);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_store_result($stmt);

						if (mysqli_stmt_num_rows($stmt) > 0) {
							// Usuario encontrado, generar una nueva contraseña
							$nueva_contraseña = generarClave();

							// Actualizar la contraseña en la base de datos
							$updateQuery = "UPDATE userprofile SET clave = ? WHERE numeroiden = ? AND correo = ?";
							if ($stmt_update = mysqli_prepare($conn, $updateQuery)) {
								// Cifrar la nueva contraseña antes de guardarla
								$hashedPassword = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
								mysqli_stmt_bind_param($stmt_update, "sss", $hashedPassword, $identificacion, $correo);

								if (mysqli_stmt_execute($stmt_update)) {
									// Enviar el correo con la nueva contraseña
									enviarCorreo($correo, "Su nueva contraseña es: " . $nueva_contraseña);

									$jTableResult['msj'] = "Se ha enviado un correo electrónico con instrucciones para restablecer la contraseña.";
									$jTableResult['rstl'] = "1";
								} else {
									$jTableResult['msj'] = "Error al actualizar la contraseña.";
									$jTableResult['rstl'] = "0";
								}

								mysqli_stmt_close($stmt_update);
							} else {
								$jTableResult['msj'] = "Error al preparar la consulta de actualización.";
								$jTableResult['rstl'] = "0";
							}
						} else {
							// No se encontró el usuario con los datos proporcionados
							$jTableResult['msj'] = "No se encontró un usuario con los datos proporcionados.";
							$jTableResult['rstl'] = "0";
						}

						mysqli_stmt_close($stmt);
					} else {
						$jTableResult['msj'] = "Error al preparar la consulta de selección.";
						$jTableResult['rstl'] = "0";
					}

					print json_encode($jTableResult);
					exit;
		break;
		}

mysqli_close($conn);