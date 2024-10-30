<?php
require_once('config.php');
header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
include_once('parametros_index.php');
require_once('consultas.php');
session_name($session_name);
session_start();
$ObjConsultas = new ayudas();
include('conex.php');
$conn=Conectarse();
switch ($_REQUEST['action']) 
	{
		case 'arregloRoles':
			$jTableResult = array();
				$jTableResult['tabs']=NULL;
				$jTableResult['tabs']="<div class='container-fluid'>
											<div class='row'>";
												$query="SELECT idMenu, iconoInicialMenu, nombreMenu, backgroundMenu FROM menu ORDER BY ordenMenu;";
												$resultadoMenu = mysqli_query($conn, $query);
												while($registroMenu = mysqli_fetch_array($resultadoMenu)){
												$jTableResult['tabs'].="
												<div class='col-sm-16'>
													<div class='info-box'>
														<button type='button' 
															id='btnSubMenu' 
															name='btnSubMenu' 
															style='cursor:pointer;' 
															class='".$registroMenu['backgroundMenu']."' 
															data-toggle='modal'
															data-idmenu='".$registroMenu['idMenu']."'
															data-nombre_menu='".$registroMenu['nombreMenu']."'																
															data-target='#editarPermisos'																
															title='".$registroMenu['nombreMenu']."' >
															<h5 class='modal-title'>
																<i class='".$registroMenu['iconoInicialMenu']."'>
																</i>
															</h5>
														</button>
														<div class='info-box-content'>
															<span class='info-box-text'>".$registroMenu['nombreMenu']."</span>
														</div>
													</div>
												</div>"; 
					}$jTableResult['tabs'].="</div>";
				$jTableResult['tabs'].="</div>";
			print json_encode($jTableResult);
		break;
		case 'arregloSbMn':
			$jTableResult = array();
			$jTableResult['tabsSm']="";
			// $_SESSION['idMenuPermiso']=$_POST['idMenuPermiso'];
			$jTableResult['tabsSm'].="<div class='col-lg-16'for='mySwitch' >
										<div class='form-group mx-auto form-check'>
											<label class='form-check-label' for='mySwitch'>Permiso Menu</label>
										</div>	
									  </div>";
				$query="SELECT idSubMenu, nombreSubMenu	FROM subMenu
				WHERE idMenuFK='".$_POST['idMenuPermiso']."' ORDER BY ordenSubMenu";
				$resultadoSubMenu = mysqli_query($conn, $query);
				while($registroSubMenu = mysqli_fetch_array($resultadoSubMenu)){   
					$estadoSBM=$ObjConsultas->buscarEstadoMenu($conn, $_POST['idRol'], $_POST['idMenuPermiso'], $registroSubMenu['idSubMenu']); 
					if($estadoSBM==1){  $varChecked="checked"; }
					if($estadoSBM==0){  $varChecked="";        }
				  $jTableResult['tabsSm'].="<div class='col-lg-16' class='form-check form-switch' >
												<div class='form-group form-check form-switch'>
													<input type='text' class='form-control-sm border-2' id='".$registroSubMenu['nombreSubMenu']."' name='".$registroSubMenu['nombreSubMenu']."' value='".$registroSubMenu['nombreSubMenu']."' style='cursor:crosshair;' readonly >
													<input type='checkbox' id='idCheck' name='idCheck' class='form-check-input' role='switch' style='cursor:pointer;' value='".$registroSubMenu['idSubMenu']."' $varChecked >
												</div>
											</div>";
					}//https://uniwebsidad.com/libros/css/capitulo-13/personalizar-el-cursor
					//<input type='checkbox' id='id".$registroSubMenu['idSubMenu']."' name='id".$registroSubMenu['idSubMenu']."' class='form-check-input' role='switch' style='cursor:pointer;'>
			print json_encode($jTableResult);
		break;
		case 'arregloGenerarPermiso':
			$jTableResult = array();
				$jTableResult['estado']=NULL;
				$query="SELECT prms.idPermiso 
				FROM   permisos prms
				WHERE  prms.idRolFk = '".$_POST['idRolPermiso']."'
				AND prms.idMenuFk = '".$_POST['idMenuPermiso']."'
				AND prms.idSubMenuFK = '".$_POST['idSBM']."';";
				$regis = mysqli_query($conn, $query);
				$numero=mysqli_num_rows($regis);
				if($numero>0)		
					{
						$query="DELETE FROM permisos WHERE idRolFk ='".$_POST['idRolPermiso']."' AND idMenuFk ='".$_POST['idMenuPermiso']."' AND idSubMenuFK ='".$_POST['idSBM']."'; "; 
						if($resultado = mysqli_query($conn, $query)){ mysqli_commit($conn);	$jTableResult['estado']="suspendido";	}
					}				
				else 
					{
						$query="INSERT INTO permisos SET idRolFk ='".$_POST['idRolPermiso']."', idMenuFk ='".$_POST['idMenuPermiso']."', idSubMenuFK ='".$_POST['idSBM']."'; ";
						if($result= mysqli_query($conn,$query))	{  	mysqli_commit($conn); $jTableResult['estado']="bien";	} else {  $jTableResult['estado']="error";	}						
					}				
			print json_encode($jTableResult);
		break;		
		case 'arregloMenu':
			$jTableResult = array();
				$jTableResult['menu']="";
				$query="SELECT mn.idMenu, mn.iconoInicialMenu, mn.backgroundMenu, mn.nombreMenu, mn.ordenMenu
				FROM menu mn";
				$arreglis = mysqli_query($conn, $query);
				while($registro = mysqli_fetch_array($arreglis)){
					$estadoMn=$ObjConsultas->buscarMenu($conn, $_SESSION['id_rol_fk'], $registro['idMenu']);
					if($estadoMn==1)
					{  						
						$jTableResult['menu'].="
							<li class='nav-item has-treeview active'>
								<a href='#' class='nav-link bg-warning active'>
									<i class='".$registro['iconoInicialMenu']."' aria-hidden='true'></i>
									<p>&nbsp;&nbsp;&nbsp;".$registro['nombreMenu']."
										<i class='right fa fa-sort'></i>
									</p>
								</a>";
							$querySM="SELECT subMenu.idSubMenu, subMenu.nombreSubMenu, archivoSubMenu, iconoInicialSubMenu 
							FROM subMenu WHERE subMenu.idMenuFK = '".$registro['idMenu']."' ";
							$arreglo=mysqli_query($conn, $querySM);
							while($result=mysqli_fetch_array($arreglo)){
									$jTableResult['menu'].="
										<ul class='nav nav-treeview'>
											<li class='nav-item'>
												<a href=".$result['archivoSubMenu']." class='nav-link'>
													<i class='".$result['iconoInicialSubMenu']."' aria-hidden='true'></i>
													<p>&nbsp;&nbsp;".$result['nombreSubMenu']."</p>
												</a>
											</li>
										</ul>";
									}
						$jTableResult['menu'].="</li>";
					}
				} 		
			print json_encode($jTableResult);
		break;
		case 'inicioSesion':
			$jTableResult = array();
				$query="SELECT prsn.id_persona, prsn.fecha_Registro_Usu, prsn.numero_identificacion, prsn.nombre, prsn.apellido, prsn.telefono, prsn.email, prsn.estado, prsn.id_rol_fk, rl.nombre_rol
				FROM persona prsn INNER JOIN rol rl ON prsn.id_rol_fk = rl.Id_rol
				WHERE prsn.numero_identificacion='".trim($_POST['inputusuario'])."' AND prsn.clave='".md5(trim($_POST['inputclave']))."';";
				$regis = mysqli_query($conn, $query);
				$numero = mysqli_num_rows($regis);
				if($numero==0)
					{
						$query="SELECT prsn.estado FROM persona prsn WHERE prsn.numero_identificacion='".$_POST['inputusuario']."' AND prsn.clave='".$_POST['inputclave']."';";
						$regis = mysqli_query($conn, $query);
						$numero = mysqli_num_rows($regis);
						if($numero==1){
							while($registro=mysqli_fetch_array($regis)){
								if($registro['estado']=="0"){
									$jTableResult['msj_DelSistema']="USUARIO SIN PERMISOS DE ENTRADA.";
									$jTableResult['Resultado']= "0";
								}}}	
						if($numero==0){	
								$jTableResult['msj_DelSistema']= "USUARIO NO EXISTE.";								
								$jTableResult['Resultado']= "0";
							}	
					}
				else if($numero==1)
					{
						while($registro = mysqli_fetch_array($regis))
							{	
								if($registro['estado']=="0"){
									$jTableResult['msj_DelSistema']="USUARIO SIN PERMISOS DE ENTRADA.";
									$jTableResult['Resultado']= "0";
								}else{	
									$_SESSION['id_Usu'] = $registro['id_persona'];
									$_SESSION['nombre_Usu']	= $registro['nombre'];
									$_SESSION['apellido_Usu'] = $registro['apellido'];
									$_SESSION['telefono_Usu'] = $registro['telefono'];
									$_SESSION['usuario_Logeado'] = $registro['nombre'];
									$_SESSION['usuario_Logeado'] = $_SESSION['usuario_Logeado'];
									$_SESSION['email_Usu'] = $registro['email'];
									$_SESSION['estado_Usu'] = $registro['estado'];
									$_SESSION['id_rol_fk'] = $registro['id_rol_fk'];
									$_SESSION['nombre_rol'] = $registro['nombre_rol'];	
									$jTableResult['Resultado'] = "1";
								}								
							}
					}
			print json_encode($jTableResult);
		break;		
		case 'buscarId_Usu':
			$jTableResult = array();
			$jTableResult['id_persona']="";
			$jTableResult['numero_identificacion']="";
			$jTableResult['nombre']="";
			$jTableResult['apellido']="";
			$jTableResult['telefono']="";
			$jTableResult['email']="";
			$jTableResult['estado']="";
			$jTableResult['Id_rol_fk']="";
			$jTableResult['nombre_rol']="";
			$jTableResult['id_area_FK']="";
			$jTableResult['areas']="";
				$query = " SELECT  prsn.id_persona,  prsn.fecha_Registro_Usu,  prsn.numero_identificacion,  prsn.nombre,
				prsn.apellido,  prsn.telefono,  prsn.email,  prsn.clave,  prsn.estado,  prsn.id_rol_fk,
				rl.nombre_rol
				FROM    persona prsn INNER JOIN rol rl ON prsn.id_rol_fk = rl.Id_rol 
				WHERE prsn.id_persona='".$_SESSION['id_Usu']."';"; 
				exit();
				$resultado = mysqli_query($conn, $query);
				while($registro = mysqli_fetch_array($resultado))
					{
						$jTableResult['id_persona']=$registro['id_persona'];
						$jTableResult['numero_identificacion']=$registro['numero_identificacion'];
						$jTableResult['nombre']=$registro['nombre'];
						$jTableResult['apellido']=$registro['apellido'];
						$jTableResult['telefono']=$registro['telefono'];
						$jTableResult['email']=$registro['email'];
						$jTableResult['estado']=$registro['estado'];
						$jTableResult['Id_rol_fk']=$registro['id_rol_fk'];
						$jTableResult['nombre_rol']=$registro['nombre_rol'];
						$jTableResult['id_area_FK']=$registro['id_area_FK'];
					}
				$jTableResult['areas']="<option value='0'>:.</option>";
				$query = " select id_area, nombre_area FROM area";	
				$resultado = mysqli_query($conn, $query);
				while($registro = mysqli_fetch_array($resultado))
					{
						if($jTableResult['id_area_FK']==$registro['id_area']){
							$jTableResult['areas'].="<option value='".$registro['id_area']."' selected >".$registro['nombre_area']."</option>";
						}else{
							$jTableResult['areas'].="<option value='".$registro['id_area']."'>".$registro['nombre_area']."</option>";							
						}
					}					
			print json_encode($jTableResult);
		break;		
		case 'actualizarUsuLog':
			$jTableResult = array(); 
				$query ="SELECT id_persona, numero_identificacion FROM persona WHERE email='".$_POST['correo_update']."' AND id_persona!='".$_POST['id_persona']."';"; 
				$resultado = mysqli_query($conn, $query);
				$numero = mysqli_num_rows($resultado);
				if($numero>0)
					{
						$jTableResult['msj_DelSistema']= "CORREO ELECTRONICO YA EXISTE.";
						$jTableResult['Resultado']= "0";
					}
				else 
					{	$_POST['clave_update'];
						if($_POST['clave_update']==""){	 $varClave_update = "";	}else{  $varClave_update = " clave = ".$_POST['clave_update']." ,";	}
						$query="UPDATE persona SET						
						nombre='".$_POST['nombre_update']."',	
						apellido='".$_POST['apellido_update']."',	
						telefono='".$_POST['telefono_update']."',	
						email='".$_POST['correo_update']."',
						".$varClave_update."
						id_area_FK='".$_POST['id_area_FK_update']."' 
						WHERE id_persona='".$_POST['id_persona']."';";
						if($resultado = mysqli_query($conn, $query))
							{
								$jTableResult['msj_delSistema']= "Actualizacion realizada con exito.";
								$jTableResult['Resultado']= "1";
							}
						else
							{
								$jTableResult['msj_delSistema']= "Intenta Nuevamente. Se presento un problema.";
								$jTableResult['Resultado']= "0";
							}
					}	
			print json_encode($jTableResult);
		break;		
		case 'asignarNuevaClave':
			$jTableResult = array();
				$query ="SELECT id_persona, numero_identificacion, nombre, apellido, estado, email 
				FROM persona WHERE numero_identificacion='".$_POST['inputUsuario']."';";
				$resultado = mysqli_query($conn, $query);
				$numero = mysqli_num_rows($resultado);
				if($numero==0)
					{
						$jTableResult['msj_DelSistema']= "Usuario no existe.";
						$jTableResult['Resultado']= "0";
					}
				else
					{				
						$num1=rand(1, 10);
						$var_NewClave = generaCodigo($num1); //md5('$Newpassword')
						while($row = mysqli_fetch_array($resultado))
						{
							$var_Usu=$row['nombre']." ".$row['apellido']." ";
							$varCorreoDestino=$row['email'];
							$var_Contenido = crearMsj($var_Usu, $var_NewClave);
							$enviando = enviarEmail($var_Contenido, $varCorreoDestino, $var_Usu);							
							// $jTableResult['Resultado'] = "1";
						}
						echo "\nContenido: ".$var_Contenido;
						exit();
					}
			print json_encode($jTableResult);
		break;		
		case 'registrarUsuario':
				try{
					$jTableResult = array();
						if(($_POST['identificacion_Registro']==NULL) 
							or ($_POST['nombre_Registro']==NULL) 
							or ($_POST['apellido_Registro']==NULL) 
							or ($_POST['correo_Registro']==NULL) 
							or ($_POST['telefono_Registro'] ==NULL) 
							or ($_POST['clave_Registro']==NULL))
							{
								$jTableResult['msj_DelSistema']= "UN CAMPO O VARIOS CAMPOS";
								$jTableResult['msj_DelSistema'].= "<br>";
								$jTableResult['msj_DelSistema'].= "ESTAN SIN DILIGENCIAR.";
								$jTableResult['Resultado']= "0";						
							}
						else
							{
								$query ="SELECT id_persona FROM persona WHERE numero_identificacion='".$_POST['identificacion_Registro']."'; ";
								$resultado = mysqli_query($conn, $query);
								$numero = mysqli_num_rows($resultado);
								if($numero>0)
									{
										$jTableResult['msj_DelSistema']= "EL NUMERO DE CEDULA YA EXISTE.";
										$jTableResult['Resultado']= "0";
									}
								else
									{
										$query ="SELECT id_persona FROM persona WHERE email='".$_POST['correo_Registro']."'";
										$resultado = mysqli_query($conn, $query);
										$numero = mysqli_num_rows($resultado);
										if($numero>0)
											{
												$jTableResult['msj_DelSistema']= "CORREO ELECTRONICO YA EXISTE.";
												$jTableResult['Resultado']= "0";
											}
										else 
											{
												mysqli_autocommit($conn, TRUE);
												mysqli_begin_transaction($conn);
												// SET fecha_Registro_Usu = now(), 
												$query="INSERT INTO persona 
												SET fecha_Registro_Usu ='".$_POST['fecha_Registro']."', 
												numero_identificacion ='".trim($_POST['identificacion_Registro'])."',
												nombre ='".trim($_POST['nombre_Registro'])."', 
												apellido ='".trim($_POST['apellido_Registro'])."',
												telefono ='".trim($_POST['telefono_Registro'])."',
												email ='".trim($_POST['correo_Registro'])."',
												estado ='".$var_Estado."',
												id_rol_fk ='".$varRol."',
												clave ='".md5(trim($_POST['clave_Registro']))."';";
												// $result = mysqli_query($conn,$query);
												// echo "<pre>";
												// print_r($query);
												// echo "</pre>";
												// exit();
												if($result= mysqli_query($conn,$query))
													{
														mysqli_commit($conn);
														$jTableResult['msj_DelSistema']= "REGISTRO REALIZADO CON EXITO.";
														$jTableResult['msj_DelSistema'].= " <br> ";
														$jTableResult['msj_DelSistema'].= "TU USUARIO DEBE SER ACTIVADO";
														$jTableResult['msj_DelSistema'].= " <br> ";
														$jTableResult['msj_DelSistema'].= "PARA PODER ACCEDER.";
														$jTableResult['Resultado']= "1";	
													}
												else
													{
														// echo "<pre>";
														// print_r($query);
														// echo "</pre>";
														// exit();
														// echo "error: ".mysqli_error($conn);
														mysqli_rollback($conn);
														$jTableResult['msj_DelSistema']= "SEPRESENTO UN ERROR. INTENTA NUEVAMENTE. O COMUNICA A LOS ENCARGADOS.";
														$jTableResult['Resultado']= "0";
													}
											}
									}
							}
					}catch(Exception $ex) {
						$jTableResult = array();
						$jTableResult['msj_DelSistema'] = "ERROR";
						$jTableResult['Resultado'] = $ex->getMensaje();
						print json_encode($jTableResult);
					}				
			print json_encode($jTableResult);
		break;
		case 'salir':
			unset($_SESSION['id_Usu']);
			unset($_SESSION['nombre_Usu']);
			unset($_SESSION['apellido_Usu']);
			unset($_SESSION['telefono_Usu']);
			unset($_SESSION['email_Usu']);
			unset($_SESSION['estado_Usu']);
			unset($_SESSION['id_rol_fk']);
			unset($_SESSION['usuario_Logeado']);			
			unset($_SESSION['nombre_rol']);			
			unset($_SESSION['nombre_area']);
			unset($_SESSION['almacen']);			
			unset($_SESSION['id_rol_fk']);			
			unset($_SESSION['idAnimalChekeo']);
			unset($_SESSION['idAnimalCheKeo']);
			session_destroy();
			header('Location: ../index.php');
		break;
	}		
mysqli_close($conn);
?> 