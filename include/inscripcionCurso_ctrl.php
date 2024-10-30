<?php
header('Cache-Control: no-cache, must-revalidate'); 
date_default_timezone_set('America/Bogota');
header('Content-Type: application/json');
ini_set('display_startup_errors', true);
ini_set('display_errors', true);
session_start();
include('../../include/conex.php');
$conn=Conectarse();
$fecha = date("Y-m-d");
switch ($_REQUEST['action']) 
	{    
		case 'cargarFichas':
			$jTableResult = array();
				$jTableResult['listaFicha'] = "<option	value='0'>Seleccione...</option>";
				$query = "SELECT id_ficha, numero_ficha, nombre_ProgramaFicha, id_Area_FK FROM ficha;";
				$resultado = mysqli_query($conn, $query);
				while ($registro = mysqli_fetch_array($resultado)) {
					$jTableResult['listaFicha'] .= "<option value='".$registro['id_ficha']."'>".$registro['numero_ficha']." ".$registro['nombre_ProgramaFicha']."</option>";
				}
			print json_encode($jTableResult);
		break;
		
		case 'cargarResultados':
			$jTableResult = array();
				$jTableResult['listaResultados'] = "<option	value='0'>Seleccione...</option>";
				$query = "SELECT Id_resultado_aprendizaje, nombre_resultado_aprendizaje FROM resultado_aprendizaje WHERE Id_competencia_FK='".$_POST['idCompetencia']."';";
				$resultado = mysqli_query($conn, $query);
				while ($registro = mysqli_fetch_array($resultado)) {
					$jTableResult['listaResultados'] .= "<option value='".$registro['Id_resultado_aprendizaje']."'>".$registro['nombre_resultado_aprendizaje']."</option>";
				}
			print json_encode($jTableResult);
		break;	
		
		case 'cargarCompetencia':  
			$jTableResult = array();
				$jTableResult['listaCompetencia']="<option value='0'>Seleccione...</option>";
				$query = "SELECT id_competencia, nombre_competencia FROM competencia WHERE id_ficha_FK='".$_POST['numeroFicha']."';";
				$resultado = mysqli_query($conn, $query);
				while ($registro = mysqli_fetch_array($resultado)) {
					$jTableResult['listaCompetencia'].= "<option value='".$registro['id_competencia']."'>".$registro['nombre_competencia']."</option>";
				}
			print json_encode($jTableResult);
		break;
		
		case 'cargarCurso':
			$jTableResult = array();
			$jTableResult['listaDependenciaNew'] = "<option	value='0'>Seleccione...</option>";
			$query = "SELECT id_dependencia, nombre_dependencia FROM dependencia;";
			$resultado = mysqli_query($conn, $query);
			while ($registro = mysqli_fetch_array($resultado)) {
				$jTableResult['listaDependenciaNew'] .= "<option value='" . $registro['Id_dependencia'] . "'>" . $registro['nombre_dependencia'] . "</option>";
			}

			$jTableResult['listaPersonaNew'] = "<option
					value='0'>Seleccione...</option>";
			$query = "SELECT id_persona, nombre, apellido FROM persona WHERE id_rol='4';";
			$resultado = mysqli_query($conn, $query);
			while ($registro = mysqli_fetch_array($resultado)) {
				$jTableResult['listaPersonaNew'].= "<option value='".$registro['id_persona']."'>".$registro['nombre']." ".$registro['apellido']."</option>";
			}
			
			print json_encode($jTableResult);
		break;
		
		case 'guardarInscripcion':
			$jTableResult = array();
			if(($_POST['idNumeroFicha']=="0")OR ($_POST['idCompetenciasFicha']=="0") OR ($_POST['fecha_Curso']=="0") OR ($_POST['idResultadoCompetencia']=="0") OR ($_POST['identAprendiz']=="") OR ($_POST['nombresAprendiz']=="") OR ($_POST['observacion_Curso']=="") )  
				{					
					$jTableResult['msj']= "FALTAN DATOS POR SELECCIONAR";							
				}
				else
				{
					$query = "INSERT INTO inscripcion_curso SET 
					fecha_Curso='" . $_POST['fecha_Curso'] . "',
					id_numero_ficha_Curso_FK='" . $_POST['idNumeroFicha'] . "', 
					id_competencia_FK='" . $_POST['idCompetenciasFicha'] . "',
					Id_resultado_aprendizaje_FK='" . $_POST['idResultadoCompetencia'] . "',
					identAprendiz='" . $_POST['identAprendiz'] . "',
					nombresAprendiz='" . $_POST['nombresAprendiz'] . "',
					observacion_Curso='" . $_POST['observacion_Curso'] . "';"; 
					if ($result = mysqli_query($conn, $query)) {
						$jTableResult['mensaje'] = "Registro con exito.";
						$jTableResult['result'] = "1";
					 } else {
						$jTableResult['mensaje'] = "Se presento un error.";
						$jTableResult['result'] = "0";
					 }
				}
			print json_encode($jTableResult);
		break;
		
		case 'buscarCurso':
			$jTableResult = array();
				$jTableResult['datosCurso']="";
				$jTableResult['listaCurso']="";
				$var_id_Curso="";
				$var_dato = "%".$_POST['dato_txt']."%";
				$query = "SELECT id_Curso, fecha_Curso, id_numero_ficha_Curso_FK, id_competencia_FK, Id_resultado_aprendizaje_FK, identAprendiz, nombresAprendiz, observacion_Curso
				FROM inscripcion_curso WHERE nombresAprendiz LIKE '$var_dato' ORDER BY nombresAprendiz ASC; ";
				$resultado = mysqli_query($conn, $query);
				$numero = mysqli_num_rows($resultado);
				if($numero==0)
					{
						$jTableResult['msj_DelSistema']= "NO SE ENCONTRARON CONINCIDENCIAS.";
						$jTableResult['Resultado']= "0";
					}
				else
					{	
						$cont=1; $fila=0;
						$jTableResult['datosCurso']="<tr bgcolor='#39A900' >
							<th scope='row'>#</th>
							<th th scope='row' >NOMBRE</th>
							<th th scope='row'>Op</th></fieldset>
						</tr>";
						while($registro = mysqli_fetch_array($resultado))
							{
								if($fila==1){ $fila=0; $color='#ffffff'; }
								else        { $fila=1; $color='#F9F6F0'; }
								$jTableResult['datosCurso'].="
								<tr bgcolor='$color'><td>".$cont."</td>
								<td>".$registro['nombresAprendiz']."</td>
								<td>
									<button 
										id='btnEditCurso'
										style='background-color:#f44336;font-size:8px;border-radius:22%;'										
										class='btn btn-success'
										data-toggle='modal' 
										data-target='#editarCurso'
										data-id_Curso='".$registro['id_Curso']."'
										data-nombresAprendices='".$registro['nombresAprendiz']."'
										title='Gestionar Inscripcion ".$registro['nombresAprendiz']."'>
										<span class='glyphicon glyphicon-user' aria-hidden='true'></span>   
									</button>
								</td></tr>";
								$cont=$cont+1;
								$jTableResult['cantidad']=$numero;
							}
					}
			print json_encode($jTableResult);
		break;}  
mysqli_close($conn);

?>