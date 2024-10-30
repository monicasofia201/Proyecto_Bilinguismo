<?php
//https://getbootstrap.com/docs/5.0/components/modal/
include_once('conex.php');
header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn=Conectarse();
switch ($_REQUEST['action']) 
{
    case 'crgrDepto':
        $jTableResult = array();                
        $jTableResult['listDepto']="";
        $jTableResult['listDepto']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT cod_dpto, nombre_dpto FROM departamentos";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listDepto'].="<option value='".$registro['cod_dpto']."'>".$registro['nombre_dpto']."</option>";
        }       
        print json_encode($jTableResult);
    break;
    case 'crgrTiposDoc':
        $jTableResult = array();                
        $jTableResult['lisTiposD']="";
        $jTableResult['lisTiposD']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_doc, nombre FROM tipodocumento";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposD'].="<option value='".$registro['id_doc']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'CrgrTipoGenero':
        $jTableResult = array();                
        $jTableResult['lisTiposG']="";
        $jTableResult['lisTiposG']="<option value='0' selected >seleccione:.</option>";
        $query = "SELECT id_genero, nombre FROM genero ";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado)) {
            // Agregar los radio buttons al resultado
            $jTableResult['lisTiposG'] .= "<option value='".$registro['id_genero']."'>".$registro['nombre']."</option>";
        }
        // Devolver el resultado como JSON
        print json_encode($jTableResult);
    break;
    case 'crgrMuni':
        $jTableResult = array();                
        $jTableResult['listMuni']="";
        $jTableResult['listMuni']="<option value='0' selected >seleccione.</option>";
        $query="SELECT cod_municipio, nombre_municipio FROM municipios WHERE cod_dpto='".$_POST['cod_dpto']."'";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listMuni'].="<option value='".$registro['cod_municipio']."'>".$registro['nombre_municipio']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrPoblados':
        $jTableResult = array();                
        $jTableResult['listPoblado']="";
        $jTableResult['listPoblado']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT cod_poblado, nombre_poblado FROM poblados WHERE cod_municipio='".$_POST['cod_municipio']."'";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listPoblado'].="<option value='".$registro['cod_poblado']."'>".$registro['nombre_poblado']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrPoblacion':
        $jTableResult = array();                
        $jTableResult['listPoblacion']="";
        $jTableResult['listPoblacion']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT cod_poblacion, nombre_poblacion FROM poblacion";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listPoblacion'].="<option value='".$registro['cod_poblacion']."'>".$registro['nombre_poblacion']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'CrgrEmpresa':
        $jTableResult = array();                
        $jTableResult['listEmpresa']="";
        $jTableResult['listEmpresa']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_empresa, nombre FROM empresa ";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listEmpresa'].="<option value='".$registro['id_empresa']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break; 
    case 'CrgrArea':
        $jTableResult = array();                
        $jTableResult['listArea']="";
        $jTableResult['listArea']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_area, nombre FROM area ";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listArea'].="<option value='".$registro['id_area']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break; 
    case 'CrgrCompetencia':
        $jTableResult = array();                
        $jTableResult['listCmpt']="";
        $jTableResult['listCmpt']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_competencia, nombre FROM competencia ";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listCmpt'].="<option value='".$registro['id_competencia']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break; 
    case 'CrgrRA':
        $jTableResult = array();                
        $jTableResult['listRa']="";
        $jTableResult['listRa']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_resultado_aprendizaje, nombre FROM resultadosaprendizaje WHERE id_competencia= '".$_POST['id_competencia']."' ";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listRa'].="<option value='".$registro['id_resultado_aprendizaje']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break; 
    case 'CrgrTipoEmpresa':
        $jTableResult = array();                
        $jTableResult['listEmpresa']="";
        $jTableResult['listEmpresa']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT nombre, id_tipoempresa FROM tipoempresa ";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listEmpresa'].="<option value='".$registro['id_tipoempresa']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrSolicitante':
        $jTableResult = array();                
        $jTableResult['listSolicitante']="";
        $jTableResult['listSolicitante']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_userprofile, nombre, nombre_dos, apellido FROM userprofile WHERE id_empresa='".$_POST['id_empresa']."'";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listSolicitante'].="<option value='".$registro['id_userprofile']."'>".$registro['nombre']."".$registro['nombre_dos']."".$registro['apellido']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrResponsable':
        $jTableResult = array();                
        $jTableResult['listResponsable']="";
        $jTableResult['listResponsable']="<option value='0' selected >seleccione:.</option>";
        $id_solicitud = $_POST['id_solicitud'];
        $query="SELECT  userprofile.id_userprofile, userprofile.nombre, userprofile.nombre_dos, userprofile.apellido 
        FROM userprofile 
        WHERE userprofile.id_rol = '2'; ";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listResponsable'].="<option value='".$registro['id_userprofile']."'>".$registro['nombre']."".$registro['nombre_dos']."".$registro['apellido']."</option>";
        }
        print json_encode($jTableResult);
    break; 
    case 'crgrEstado':
        $jTableResult = array();                
        $jTableResult['listEstado']="";

        $query="SELECT id_estado, nombre FROM estado";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listEstado'].="<option value='".$registro['id_estado']."'>".$registro['nombre']."</option>";
        }       
        print json_encode($jTableResult);
    break;
    case 'crgrTiposJornada':
        $jTableResult = array();                
        $jTableResult['lisTiposJ']="";
        $jTableResult['lisTiposJ']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_jornada, nombre FROM jornada";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposJ'].="<option value='".$registro['id_jornada']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrTiposModalidad':
        $jTableResult = array();                
        $jTableResult['lisTiposM']="";
        $jTableResult['lisTiposM']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_modalidad, nombre FROM modalidad";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposM'].="<option value='".$registro['id_modalidad']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrTiposNivelFormacion':
        $jTableResult = array();                
        $jTableResult['lisTiposNF']="";
        $jTableResult['lisTiposNF']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_nivel_formacion, nombre FROM nivelformacion";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposNF'].="<option value='".$registro['id_nivel_formacion']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrRevista':
        $jTableResult = array();                
        $jTableResult['Revista'] = '';
        
        // Consulta para obtener las revistas
        $query = "SELECT id_mcer, nombre FROM mcer";
        $resultado = mysqli_query($conn, $query);
        
        $revistas = [];
        while ($registro = mysqli_fetch_assoc($resultado)) {
            $revistas[] = $registro;
        }
        
        // Si hay más de una revista, construir el carrusel
        if (count($revistas) > 1) {
            $jTableResult['Revista'] .= '
            <div id="revistaCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">';
            
            foreach ($revistas as $index => $revista) {
                $jTableResult['Revista'] .= '
                <div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">
                    <center>
                        <embed src="../../include/' . $revista['nombre'] . '" type="application/pdf" width="90%" height="500px" />
                    </center>
                    <button type="button" id="EliminarRevista" class="close-button" data-id="' . $revista['id_mcer'] . '" data-bs-dismiss="modal">Cancelar Revista</button>
                </div>';
            }
            
            $jTableResult['Revista'] .= '
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#revistaCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#revistaCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>';
        } else if (count($revistas) === 1) {
            // Si solo hay una revista, mostrarla sin carrusel
            $revista = $revistas[0];
            $jTableResult['Revista'] .= '
            <center>
                <embed src="../../include/' . $revista['nombre'] . '" type="application/pdf" width="90%" height="500px" />
                <button type="button" id="EliminarRevista" class="close-button" data-id="' . $revista['id_mcer'] . '" data-bs-dismiss="modal">Cancelar Revista</button>
            </center>';
        } else {
            // Si no hay revistas
            $jTableResult['Revista'] .= '<p>No hay revistas disponibles en este momento.</p>';
        }
        
        print json_encode($jTableResult);
    break;
    
    
    case 'crgrprogramaFormacion':
        $jTableResult = array();                
        $jTableResult['lisTiposPF']="";
        $jTableResult['lisTiposPF']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_programaformacion, nombre FROM programaformacion WHERE config = 1";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposPF'].="<option value='".$registro['id_programaformacion']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrprogramaFormacion4':
        $jTableResult = array();                
        $jTableResult['lisTiposPF']="";
        $jTableResult['lisTiposPF']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_programaformacion, nombre FROM programaformacion WHERE id_estado=7";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposPF'].="<option value='".$registro['id_programaformacion']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrprogramaFormacion3':
        $jTableResult = array();                
        $jTableResult['lisTiposPF']="";
        $jTableResult['lisTiposPF']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_programaformacion, nombre FROM programaformacion WHERE config = 2";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposPF'].="<option value='".$registro['id_programaformacion']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'crgrprogramaFormacion2':
        $jTableResult = array();                
        $jTableResult['lisDPF']="";
        $id_programaformacion=  $_POST['id_programaformacion'];
        $query="SELECT nombre, horas_curso, tipo_formacion, nivel_formacion, modalidad 
        FROM programaformacion 
        WHERE id_programaformacion='$id_programaformacion' AND config = 1";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisDPF'].='
            <div class="course-data-field">
                <label class="modal-title" for="id_modalidad">Modalidad</label>
                <label class="form-control" id="id_modalidad_label">'.$registro['modalidad'].'</label>
                <input type="hidden" id="id_modalidad" name="modalidad" value="'.$registro['modalidad'].'">
            </div>
            <div class="course-data-field">
                <label class="modal-title" for="id_jornada">Tipo de Formación</label>
                <label class="form-control" id="tipo_formacion_label">'.$registro['tipo_formacion'].'</label>
                <input type="hidden" id="tipo_formacion" name="tipo_formacion" value="'.$registro['tipo_formacion'].'">
            </div>
            <div class="course-data-field">
                <label class="modal-title" for="id_jornada">Nivel Formación</label>
                <label class="form-control" id="nivel_formacion_label">'.$registro['nivel_formacion'].'</label>
                <input type="hidden" id="nivel_formacion" name="nivel_formacion" value="'.$registro['nivel_formacion'].'">
            </div>
            <div class="course-data-field">
                <label class="modal-title" for="id_modalidad">Horas Totales del Curso</label>
                <label class="form-control" id="horas_curso_label">'.$registro['horas_curso'].'</label>
                <input type="hidden" id="horas_curso" name="horas_curso" value="'.$registro['horas_curso'].'">
            </div>';

        }
        print json_encode($jTableResult);
    break;
    case 'CrgrFichas':
        $jTableResult = array();                
        $jTableResult['listFicha']="";
        $id_programaformacion=  $_POST['id_programaformacion'];
        $query="SELECT id_programaformacion, ficha 
        FROM programaformacion 
        WHERE id_programaformacion='$id_programaformacion' AND id_estado = 7";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['listFicha'].='
            <div class="course-data-field">
                <label class="modal-title" >Ficha</label>
                <label class="form-control" id="ficha">'.$registro['ficha'].'</label>
                <input type="hidden" id="ficha" name="ficha" value="'.$registro['ficha'].'">
            </div>';

        }
        print json_encode($jTableResult);
    break;
    case 'Cgridioma':
        $jTableResult = array();                
        $jTableResult['idioma']="";
        $jTableResult['idioma']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_idioma, nombre FROM idiomas";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['idioma'].="<option value='".$registro['id_idioma']."'>".$registro['nombre']."</option>";
        }
        print json_encode($jTableResult);
    break;
    case 'CgrSeccion':
        $jTableResult = array();                
        $jTableResult['seccion']="";
        $query="SELECT i.nombre AS nombre_idioma, s.nombre AS nombre_Seccion, s.descripcion FROM secciones s JOIN idiomas i ON s.id_idioma = i.id_idioma";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['seccion'].='
                            <div class="info-cards2">
                                <h2 class="subtitle2">'.$registro['nombre_Seccion'].'</h2>
                                <div class="info-card2">
                                    <div class="icon-circle2">
                                        <i class="fa-solid fa-comment"></i>
                                    </div>
                                    <h3>Descripcion de la Seccion</h3>
                                    <div class="description2">
                                        <p class="descripcion">'.$registro['descripcion'].'</p>
                                    </div>
                                </div>
                            </div>
                    <div class="divider"></div>';
        }
        print json_encode($jTableResult);
    break;
    case 'CgrLibros':
        $jTableResult = array();                
        $jTableResult['libro']="";
        $query="SELECT l.anio_publicacion,l.titulo, l.autor, l.archivo_pdf, l.descripcion_autor, l.prologo, s.nombre,l.archivo_pdf FROM libros l JOIN secciones s ON l.fk_seccion  = s.id_seccion";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['libro'].='
                            <div class="info-cards3">
                                <h2 class="subtitle3">'.$registro['titulo'].'</h2>
                                <div class="info-card3">
                                    <div class="icon-circle3">
                                        <i class="fa-solid fa-comment"></i>
                                    </div>
                                    <h3>Libro</h3>
                                    <embed src="../../include/'.$registro['archivo_pdf'].'" type="application/pdf" width="50%" height="200px" />
                                    <h3>Descripcion de la Seccion</h3>
                                    <div class="description3">
                                        <p class="descripcion">'.$registro['prologo'].'</p>
                                    </div>
                                </div>
                            </div>
                    <div class="divider"></div>';
        }
        print json_encode($jTableResult);
    break;
    case 'CgrSeccionL':
        $jTableResult = array();                
        $jTableResult['seccionL'] = '';
        $jTableResult['seccionL']="<option value='0' selected >seleccione:.</option>";
        $query = "SELECT id_seccion, nombre FROM secciones";
        $resultado = mysqli_query($conn, $query);
        while ($registro = mysqli_fetch_array($resultado)) {
            $jTableResult['seccionL'] .= '<option value="'.$registro['id_seccion'].'">'.$registro['nombre'].'</option>';
        }
        print json_encode($jTableResult);
    break;
    case 'crgrTipoCategoria':
        $jTableResult = array();                
        $jTableResult['lisTiposC']="";
        $jTableResult['lisTiposC']="<option value='0' selected >seleccione:.</option>";
        $query="SELECT id_categoria, nombre FROM categoria";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposC'].="<option value='".$registro['id_categoria']."'>".$registro['nombre']."</option>";
        }       
        print json_encode($jTableResult);
    break;
    // REVISAR CAMILO EL ROL
    case 'crgrTipoRol2':
        $jTableResult = array();                
        $jTableResult['lisTiposR']="";
        $query="SELECT id_rol, nombre FROM rol";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado))
        {
            $jTableResult['lisTiposR'].="<option value='".$registro['id_rol']."'>".$registro['nombre']."</option>";
        }       
        print json_encode($jTableResult);
    break;
    case 'crgrTipoRol':
        $jTableResult = array();                
        $jTableResult['lisTiposR']="";
        $query = "SELECT id_rol, nombre FROM rol ";
        $resultado = mysqli_query($conn, $query);
        while($registro = mysqli_fetch_array($resultado)) {
            // Agregar los radio buttons al resultado
            $jTableResult['lisTiposR'] .= "<input type='radio' name='tipo_rol' value='" . $registro['id_rol'] . "'>" . $registro['nombre'] . "<br>";
        }
        // Devolver el resultado como JSON
        print json_encode($jTableResult);
    break;


    // este modal esta en inicio
    case 'MisNoti':
        $jTableResult = array();
        $jTableResult['rs'] = "";
        $jTableResult['Ms'] = "";
        $jTableResult['tabla'] = ""; 
        // Iniciar la construcción de la tabla
        $jTableResult['tabla'] .= '
            <div class="">
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
                                            <th>Fecha Publicada</th>
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
                                    tiposolicitud.nombre AS nombre_tipo, userprofile.nombre AS nombre_autor, detallesolicitud.fecha_inicio
                                FROM solicitud
                                LEFT JOIN estado ON solicitud.id_estado = estado.id_estado 
                                LEFT JOIN userprofile ON solicitud.id_userprofile = userprofile.id_userprofile
                                LEFT JOIN detallesolicitud ON solicitud.id_detallesolicitud = detallesolicitud.id_detallesolicitud
                                LEFT JOIN tiposolicitud ON detallesolicitud.id_tiposolicitud = tiposolicitud.id_tiposolicitud
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
                                                                                        <td>" . $registro['fecha_inicio'] . "</td>
                                                                                        <td>";
                                                            if ($_SESSION['id_rol'] == 3) {
                                                                $jTableResult['tabla'] .= '<button id="noticiaful" class="cards__button btn btn-link p-0" data-toggle="modal" data-target="#myModal" data-id="' . $registro['id_solicitud'] . '">Ver Soli</button>';
                                                            } elseif ($registro['id_estado'] == 4){
                                                                $jTableResult['tabla'].='<button id="noticiaful" class="cards__button btn btn-link p-0" data-toggle="modal" data-target="#myModal" data-id="' . $registro['id_solicitud'] . '">ver Soli</button>';
                                                            }
                                                            else {
                                                                $jTableResult['tabla'] .= '
                                                                                            <button id="noticiaful" class="cards__button btn btn-link p-0" data-toggle="modal" data-target="#myModal" data-id="' . $registro['id_solicitud'] . '">Cancelar</button>';
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
    
}
mysqli_close($conn);
?>