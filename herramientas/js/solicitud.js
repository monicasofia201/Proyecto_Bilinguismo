$(document).ready(function(){
    $.post("../../include/select.php", {
        action: 'crgrTipoRol' 
    },
    function(data) {
        $("#id_rol").html(data.lisTiposR);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    }); 
    $.post("../../include/cntrlSoli.php", {
        action: 'crgrTipoSolicitud' 
    },
    function(data) {
        $("#id_tiposolicitud").html(data.lisTiposS);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $.post("../../include/cntrlSoli.php", {
        action: 'MisSoli_sin'
    }, function(data) {
        if (data.rs === "1") {
            $("#sin_contenido").html(
                `<h1 class="title">Tus Solicitudes Pendientes</h1>` +
                data.tabla
            );
        } else {
            // mirar actualizar perfil
            $("#sin_contenido").html(`
                <h4 class="title">No hay Solicitudes Pendientes</h4>
            `);
        }
    }, 'json');
    $.post("../../include/select.php", {
        action: 'crgrResponsable',
        id_solicitud: idSolicitud 
    },
    function(data) {
        $("#id_responsable").html(data.listResponsable);
        },
        'json'
        ).fail(function(xhr, status, error) {
            console.error(error);
    });
    $.post("../../include/cntrlSoli.php", {
        action: 'MisSoli'
    }, function(data) {
        if (data.rs == "1") {
            $("#contenido").html(
                `<h1 class="title">Tus Solicitudes</h1>` + data.tabla
            );
        } else if (data.rs === "2") {
            $("#contenido").html(`
                <h4 class="title">Tu Solicitud aun no tiene Asignada una Respuesta.</h4>
            `);
        } else {
            // mirar actualizar perfil
            $("#contenido").html(`
                <h4 class="title">No has realizado ninguna Solicitud</h4>
                <div class="col-sm-2">
                    <h3>Creala...</h3>
                </div>
            `);
        }
    }, 'json');
});

$(document).on("click", "#btnEditarSoli", function() {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud); // Puedes eliminar esto después de verificar que el ID se captura correctamente

    // Enviar una solicitud AJAX al servidor para obtener los datos de la solicitud
    $.post("../../include/cntrlSoli.php", {
        action: 'ListarSolicitud',
        id_solicitud: idSolicitud
    }, function(data) {
        if (data.rstl == "1") {
            // Vaciar el contenedor de campos editables
            $('#editableFields').empty();

            // Generar inputs editables para los campos no vacíos y que no contienen "id"
            for (var key in data.solicitud) {
                if (data.solicitud.hasOwnProperty(key) && !key.includes('id') && data.solicitud[key] !== null && data.solicitud[key] !== "") {
                    var inputHtml = `
                        <div class="form-group">
                            <label ">${capitalizeFirstLetter(key)}</label>
                            <input type="text" class="form-control" id="${key}" name="${key}" value="${data.solicitud[key]}">
                        </div>
                    `;
                    $('#editableFields').append(inputHtml);
                }
            }
            
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.msj
            });
        }

        $(document).on("click", "#btnGuardarCambios", function() {
            var idSolicitud = $(this).data('id');
            // Crear un objeto para los datos de la solicitud
            var updatedData = {
                action: 'actualizarSolicitud',
                id_solicitud: idSolicitud
            };
            // Recoger los valores de los campos editables
            $('#editableFields input').each(function() {
                var key = $(this).attr('id');
                updatedData[key] = $(this).val();
            });
            // Enviar una solicitud AJAX al servidor para actualizar los datos de la solicitud
            $.post("../../include/cntrlSoli.php", updatedData, function(data) {
                if (data.rstl == "1") {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.msj,
                        showConfirmButton: false,
                        timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.msj
                    });
                }
            }, 'json');
        });
    }, 'json');
});
$(document).on("click", "#btnEliminarSoli", function() {
    var idSolicitud = $(this).closest('tr').find('td:first').text();
    // console.log("ID de la solicitud a eliminar: " + idSolicitud); 

    if (confirm("¿Estás seguro de que deseas Cancelar esta solicitud?")) {
        // Enviar una solicitud AJAX al servidor para eliminar la solicitud
        $.post("../../include/cntrlSoli.php", {
            action: 'eliminarSolicitud',
            id_solicitud: idSolicitud
        }, function(data) {
            if (data.rstl == "1") {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.msj,
                    showConfirmButton: false,
                    timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                })
                // Eliminar la fila de la tabla correspondiente a la solicitud eliminada
                $(`#btnEliminarSoli[data-id='${idSolicitud}']`).closest('tr').remove();
                location.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msj
                });

            }
        }, 'json');
    }
});
// Función para capitalizar la primera letra del nombre del campo
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
$(document).on("click", "#btnAceptarSoli", function() {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    if ($("#detalle_respuesta").val()==""){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text:'Debe Digitar Mensaje de Confirmacion...'
        });
    }
    else
    {
        confirm("Estas seguro de responder esta solicitud?")
        $.post("../../include/cntrlSoli.php", {
            action: 'aceptarSolicitud',
            id_solicitud: idSolicitud,
            detalle_respuesta:$("#detalle_respuesta").val()
        }, function(data) {
            if (data.rstl == "1") {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.msj,
                    showConfirmButton: false,
                    timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msj
                });
            }
        }, 'json');
    }
    
});
$(document).on("click", "#btnAceptarSoliOf", function() {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    if ($("#detalle_respuesta").val()==""){
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text:'Debe Digitar Mensaje de Confirmacion...'
        });
    }
    else
    {
        confirm("Estas seguro de Terminar esta Oferta?")
        $.post("../../include/cntrlSoli.php", {
            action: 'aceptarSolicitudOf',
            ficha:$("#ficha").val(),
            id_solicitud: idSolicitud,
            detalle_respuesta:$("#detalle_respuesta").val()
        }, function(data) {
            if (data.rstl == "1") {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.msj,
                    showConfirmButton: false,
                    timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msj
                });
            }
        }, 'json');
    }
    
});
$(document).on("click", ".btn-danger[data-bs-target='#cancelSolicitudModal']", function() {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud guardado: " + idSolicitud);
    localStorage.setItem('idSolicitud', idSolicitud);
});

function cargarJornadas() {
    $.post("../../include/select.php", {
        action: 'crgrTiposJornada'
    },
    function(data) {
        $("#id_jornada").html(data.lisTiposJ);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $(document).ready(function(){  
        $.post("../../include/select.php", {
            action: 'CrgrArea' 
        },
        function(data) {
            $("#id_area").html(data.listArea);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        });
    });
}

// Tipo SOlicitud
function MostrarTipo_soli() {
    var radios = document.getElementsByName("tipo_solicitud");
    var tipo_soliDiv = document.getElementById("tipo_soli");
    tipo_soliDiv.innerHTML = "";
    // Recorre todos los botones de radio para encontrar el seleccionado
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var SelectipoSoli = radios[i].value;
            switch (SelectipoSoli) {
                case "1":
                    tipo_soliDiv.innerHTML = ` 
                        <div class="form-container">
                            <h5><strong>Ubicacion Sugerida Para Solicitud</strong></h5>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <h6 class="modal-title">Departamento</h6>
                                    <select class="form-control modal-textbox" id="cod_dpto" name="cod_dpto"
                                        title='Departamento' style='cursor:pointer;'></select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <h6 class="modal-title">Municipio</h6>
                                    <select class="form-control modal-textbox" id="cod_municipio" name="cod_municipio"
                                        title='Municipio' style='cursor:pointer;'></select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <h6 class="modal-title">Vereda</h6>
                                    <select class="form-control modal-textbox" id="cod_poblado" name="cod_poblado"
                                        title='vereeda-poblado' style='cursor:pointer;'></select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label  class="col-form-label">Descripcion</label>
                                    <input type="text" class="form-control" id="descripcion_local" name="descripcion_local" placeholder="Agregue una Descripcion clara para su Solicitud">
                                </div>
                            </div>
                            <br>
                            <br>
                            <label class="modal-title" >Programa de Formacion de Interes</label><br>
                            <select  class="form-control modal-textbox"  id="id_programaformacion"></select>
                            <br>
                            <div id="datospf"></div>
                            <label class="modal-title">Jornada de Interes</label><br>
                            <select class="form-control modal-textbox"  id="id_jornada"></select>
                            <br>
                            <label class="modal-title">¿Requieres Cargar tus Usuarios interesados?</label>
                            <br>
                            <label class="modal-title" for="archivo">Cargar Archivo solicitud</label>
                            <input type="file" id="archivo" name="archivo" accept=".pdf">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close-button" data-bs-dismiss="modal" name="btnVolver"
                                id="btnVolver">Salir</button>
                            <input class="create-button" type="submit" name="btnEnviar" id="btnEnviar" value="Enviar">
                        </div>
                    `;
                    Cargar();
                break;
                case "2":
                    tipo_soliDiv.innerHTML = `
                        <div class="form-container">
                            <div class="card text-center shadow-sm">
                                <div class="card-body">
                                    <h5 class="title">Consulta Certificados del SENA</h5>
                                    <p class="card-text">¿Necesitas verificar o consultar tu certificado del SENA? Puedes hacerlo de manera rápida y sencilla a través del siguiente enlace.</p>
                                    <a href="https://certificados.sena.edu.co/CertificadoDigital/com.sena.consultacer" target="_blank" class="create-button">Consultar Certificado</a>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case "3":
                    tipo_soliDiv.innerHTML = `
                        <div class="form-container">
                            <div class="col-sm-12" >
                                <h6 class="modal-title">Programa de Formacion<h6>
                                <select class="form-control modal-textbox" id="id_programaformacion3" title='' style='cursor:pointer;'required >
                                </select>
                                <h6 class="modal-title" >Ficha</h6><br>
                                <input class="col-form-label" type="number" name="ficha" id="ficha" ><br>
                                <h6 class="modal-title">Tipo de Asesoramiento<h6>
                                <select class="form-control modal-textbox" name="tipo_asesoramiento" id="tipo_asesoramiento">
                                    <option value="">Selecciona el tipo de asesoramiento</option>
                                    <option value="Técnico">Asesoramiento Técnico</option>
                                    <option value="Administrativo">Asesoramiento Administrativo</option>
                                    <option value="Académico">Asesoramiento Académico</option>
                                    <option value="Otro">Otro tipo de asesoramiento</option>
                                </select><br>
                                <h6 class="modal-title">Descripcion sobre Consulta...</h6><br>
                                <input  class="col-form-label" type="text" name='descripcion_local' id='descripcion_local' ><br>
                                <label class="modal-title" >¿Requieres Cargar Algun Archivo?</label>
                                <br>
                                <label for="archivo">Cargar Archivo solicitud (solo archivos de tipo pdf)</label>
                                <input type="file" id="archivo" name="archivo" accept=".pdf">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close-button" data-bs-dismiss="modal" name="btnVolver"
                                id="btnVolver">Salir</button>
                            <input class="create-button" type="submit" name="btnEnviar" id="btnEnviar" value="Enviar">
                        </div>
                    `;
                    Cargar();
                break;
                case "4":
                        tipo_soliDiv.innerHTML = `
                            <div class="form-container">
                                <div class="col-sm-12">
                                    <h1 class="text-center">Crear publicación</h1>
                                    <form class="card p-3 shadow-lg border-3 text-bg-light" action=""
                                        method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="id_nombre" class="form-label">Título:</label>
                                            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="titulo"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha a Mostrar</label>
                                            <input class="form-control" type="date" id="id_fecha_mostrada"  required>
                                        </div>
                                        <div class="mb-3" id="control">
                                            <label class="form-label" >Descripción</label>
                                            <textarea rows="10" class="form-control" id="descripcion_local" name="descripcion_local" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="imagen" class="form-label">Adjuntar Imagen:</label>
                                            <input type="file" class="form-control" id="imagen" name="imagen" required></input>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categoria" class="form-label">Categoria</label>
                                            <select class="form-control" id="categoria" name="categoria">
                                            
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" id="publicar_noti">Publicar
                                            </button>
                                            <a type="submit" type="submit" name="" id="" class="btn btn-sm btn-outline-danger" href=""
                                                role="button">Cancelar</a>
                                        </div>
                                    </form>
                                </div>
                                <div id="librerias">
                                    <script>
                                    $(document).ready(function() {
                                        $('#cuerpo').summernote({
                                            height: 100
                                        });
                                    });
                                    </script>
                                    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
                                    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
                                </div>  
                            </div>
                        `;
                break;
                case "5":
                    tipo_soliDiv.innerHTML = `
                            <div class="form-container">
                                        <h6 class="modal-title">Programa de Formacion<h6>
                                        <select class="form-control modal-textbox" id="id_programaformacion" name="id_programaformacion"  title='' style='cursor:pointer;' >
                                        </select>
                                        <h6 class="modal-title" for="descripcion">Ficha</h6><br>
                                        <input class="col-form-label" type="number" name="ficha" id="ficha" ><br>
                                <h6 class="modal-title">Sobre que Competencia tienes Dudas?</h6><br>
                                        <select class="form-control modal-textbox" id="id_competencia"  title='' style='cursor:pointer;' >
                                        </select><br>
                                <h6 class="modal-title">Resultado de Aprendizaje<h6><br>
                                <select class="form-control modal-textbox" id="id_resultado_aprendizaje"  title='' style='cursor:pointer;' >
                                        </select><br>
                                        <h6 class="modal-title">Descripcion sobre Consulta...</h6><br>
                                <input  class="col-form-label" type="text" name='descripcion_local' id='descripcion_local' ><br>
                                <label class="modal-title" >¿Requieres Cargar Algun Archivo?</label>
                                <br>
                                <label class="modal-title" for="archivo">Formato Admitido (pdf)</label>
                                <input type="file" id="archivo" name="archivo" accept=".pdf">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="close-button" data-bs-dismiss="modal" name="btnVolver"
                                    id="btnVolver">Salir</button>
                                <input class="create-button" type="submit" name="btnEnviar" id="btnEnviar" value="Enviar">
                            </div>
                    `;
                    Cargar();
                break;
                case "10":
                        tipo_soliDiv.innerHTML = `
                            <div class="form-container">
                                <label>Seleccione su Jornada de trabajo:</label><br>
                                <select class="form-control modal-textbox" id="id_jornada"></select>
                                <br>
                                    <div class="container mt-4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="addSocial" class="col-form-label">Desea agregar sus redes sociales?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="addSocial" id="addSocialYes" value="yes" onclick="toggleSocialLinks()">
                                                        <label class="form-check-label" for="addSocialYes">Sí</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="addSocial" id="addSocialNo" value="no" onclick="toggleSocialLinks()">
                                                        <label class="form-check-label" for="addSocialNo">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="socialLinks" class="mt-3" style="display:none;">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label  class="col-form-label">Canal de YouTube:</label>
                                                    <input type="url" class="form-control" id="youtube" name="youtube" placeholder="Enlace a YouTube">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Instagram:</label>
                                                    <input type="url" class="form-control" id="instagram" name="instagram" placeholder="Enlace a Instagram">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Facebook:</label>
                                                    <input type="url" class="form-control" id="facebook" name="facebook" placeholder="Enlace a Facebook">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">x-(Twitter):</label>
                                                    <input type="url" class="form-control" id="twitter" name="twitter" placeholder="Enlace a X">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="container mt-4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="addPhoto" class="col-form-label">¿Desea agregar foto de perfil?</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="addPhoto" id="addPhotoYes" value="yes" onclick="toggleProfilePhotoInput()">
                                                    <label class="form-check-label" for="addPhotoYes">Sí</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="addPhoto" id="addPhotoNo" value="no" onclick="toggleProfilePhotoInput()">
                                                    <label class="form-check-label" for="addPhotoNo">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="profilePhotoInput" class="mt-3" style="display:none;">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="profilePhoto" class="col-form-label">Subir Foto de Perfil:</label>
                                                <input type="file" class="form-control" id="profilePhoto" name="profilePhoto" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="close-button" data-bs-dismiss="modal" name="btnVolver"
                                    id="btnVolver">Salir</button>
                                <input class="create-button" type="submit" name="btnEnviar" id="btnEnviar" value="Enviar">
                            </div>
                        `;
                        cargarJornadas();
                break;
            }
        }
    }
}
function toggleSocialLinks() {
    const yesOption = document.getElementById('addSocialYes');
    const socialLinks = document.getElementById('socialLinks');
    if (yesOption.checked) {
        socialLinks.style.display = 'block';
    } else {
        socialLinks.style.display = 'none';
    }
}
function toggleProfilePhotoInput() {
    const yesOption = document.getElementById('addPhotoYes');
    const photoInput = document.getElementById('profilePhotoInput');
    if (yesOption.checked) {
        photoInput.style.display = 'block';
    } else {
        photoInput.style.display = 'none';
    }
}
$(document).on("click", "#btnNewSoli",function (){
    //alert('Dentro del boton Guardar Registro Usuario.');
        if ($("input[name='tipo_rol']:checked").length === 0){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El tipo de solicitud debe tener un Usuario asignado'
            });
        }
        else
        {
            if($("#nombre").val()=="" ){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe Seleccionar un tu nuevo tipo de Solicitud'
                });
            $("#nombre").focus();
            }
            else
            {
                $.post("../../include/cntrlSoli.php", {
                    action:'TipoSoliNew',
                    id_rol:$("input[name='tipo_rol']:checked").val(),
                    nombre:$("#nombre").val()
                    }, function(data){
                        if(data.rst=="1"){ 
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: data.ms,
                                showConfirmButton: false,
                                timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                            }).then(() => {
                                location.reload();
                            });
                        } else{
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.ms
                                }); }
                    }, 'json');
                }
            }
        }
);
$(document).on("click", "#btnEnviar", function() {
    if ($("input[name='tipo_solicitud']:checked").length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe Elegir un Tipo de Solicitud'
        });
    } else {
        var formData = new FormData();
        formData.append('action', 'RegistroSoliNew');
        formData.append('id_tiposolicitud', $("input[name='tipo_solicitud']:checked").val());
        formData.append('nombre', $("#titulo").val());
        formData.append('id_competencia', $("#id_competencia").val());
        formData.append('id_resultado_aprendizaje', $("#id_resultado_aprendizaje").val());
        formData.append('fecha_inicio', $("#fecha_inicio").val());
        formData.append('fecha_inicio', $("#id_fecha_mostrada").val());
        formData.append('fecha_fin', $("#fecha_fin").val());
        formData.append('id_jornada', $("#id_jornada").val());
        formData.append('twitter', $("#twitter").val());
        formData.append('youtube', $("#youtube").val());
        formData.append('facebook', $("#facebook").val());
        formData.append('instagram', $("#instagram").val());
        formData.append('imagen', $("#imagen").val());
        formData.append('ficha', $("#ficha").val());
        formData.append('descripcion', $("#descripcion_local").val());
        formData.append('cod_municipio', $("#cod_municipio").val());
        formData.append('cod_poblado', $("#cod_poblado").val());
        formData.append('cod_dpto', $("#cod_dpto").val());
        formData.append('id_programaformacion', $("#id_programaformacion").val());
        formData.append('id_nivel_formacion', $("#id_nivel_formacion").val());
        var fileInput = document.getElementById('archivo');
        if (fileInput && fileInput.files.length > 0) {
            formData.append('archivo', fileInput.files[0]);
        }
        console.log(formData);
        $.ajax({
            url: '../../include/cntrlSoli.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.rst == "1") {
                    $.post("../../include/cntrlSoli.php", {
                        action: 'Solicitud'
                    }, function(data) {
                        if (data.rst == "1") {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: data.ms,
                                showConfirmButton: false,
                                timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                            }).then(() => {
                                location.reload();
                            });
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.ms
                            });
                        }
                    }, 'json');
                } else {
                    Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.ms
                });
                }
            },
            dataType: 'json'
        });
    }
});
$(document).on("click", "#btn_Buscar",function ()	{
    $("#sin_contenido").hide();
    $("#oferta_curso").hide();
    $("#contenido").hide();
    $.post("../../include/cntrlSoli.php", {
        action:'buscarSolicitud',
        dato_txt:$("#dato_txt").val()
    }, function(data){
        if(data.rstl=='1'){ $("#solisB").html(data.listaSoli); }
            else { $("#solisB").html(data.listaSoli); }
        }, 'json');	
    }
);
let selectedFilters = [];
$(document).on("click", "#btn_Filtro", function () {
    $("#sin_contenido").hide();
    $("#oferta_curso").hide();
    $("#contenido").hide();

    // Capturar valores seleccionados de los checkboxes
    selectedFilters = [];
    $("#filterOptions input[type='checkbox']:checked").each(function () {
        selectedFilters.push($(this).val());
    });

    $.post("../../include/cntrlSoli.php", {
        action: 'FiltroSolicitud',
        dato_filtro: selectedFilters.join(',')
    }, function (data) {
        if (data.rstl == '1') {
            $("#solisF").html(data.listaSoli);
        } else {
            $("#solisF").html(data.listaSoli);
        }
    }, 'json');
});

$(document).on("click", "#ExportarSoli", function () {
    // Verificar si hay filtros seleccionados
    let url = "../../include/cntrlSoli.php?action=exportarSolicitud";
    if (selectedFilters.length > 0) {
        url += "&dato_filtro=" + encodeURIComponent(selectedFilters.join(','));
    }

    // Realizar la solicitud AJAX para generar el archivo Excel
    $.ajax({
        url: url,
        method: 'GET',
        xhrFields: {
            responseType: 'blob' // Para manejar la respuesta como un archivo
        },
        success: function (data) {
            // Crear un enlace temporal para descargar el archivo
            let a = document.createElement('a');
            let url = window.URL.createObjectURL(data);
            a.href = url;
            a.download = "solicitudes_" + new Date().toISOString().slice(0, 19).replace(/:/g, "-") + ".xls";
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url); // Liberar la memoria
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error al descargar el archivo: ' + textStatus);
        }
    });
});
function AsignacionesCargar(idSolicitud){
    
    $(document).ready(function(){  
        $.post("../../include/select.php", {
            action: 'crgrResponsable',
            id_solicitud: idSolicitud 
        },
        function(data) {
            $("#id_responsable").html(data.listResponsable);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        });
    });
    $(document).ready(function(){  
        $.post("../../include/select.php", {
            action: 'crgrEstado' 
        },
        function(data) {
            $("#id_estado").html(data.listEstado);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        });
    });
}
$(document).on("click", "#btnGuardarCambios2", function() {
    var idSolicitud = $(this).data('id');
    $.post("../../include/cntrlSoli.php", {
        action: 'actualizarSolicitud',
        id_solicitud: idSolicitud,
        id_responsable:$("#id_responsable").val()
    }, function(data) {
        if (data.rstl == "1") {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.msj,
                showConfirmButton: false,
                timer: 1500 // Tiempo en milisegundos (1.5 segundos)
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.msj
            });
        }
    }, 'json');
});
$(document).on("click", "#btn_asign",function ()	{
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action:'ListarSolicitud_asignacion',
        id_solicitud: idSolicitud
    }, function(data){
        if(data.rst=='1'){
            $("#form_asignaciones").html(data.ListAsign);
            AsignacionesCargar(idSolicitud)
        }
            else { Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });}
        }, 'json');	
    }
);
$(document).ready(function () {
    // Mostrar u ocultar las opciones de filtro al hacer clic en el botón
    $('#filterButton').on('click', function () {
        $('#filterOptions').toggle();
    });

    // Aplicar los filtros seleccionados al hacer clic en "Aplicar filtros"
    $('#applyFilters').on('click', function () {
        var selectedFilters = [];
        $('#filterOptions input[type="checkbox"]:checked').each(function () {
            selectedFilters.push($(this).val());
        });

        // Aquí puedes enviar los filtros seleccionados al servidor o aplicarlos localmente
        console.log('Filtros seleccionados:', selectedFilters);

        // Cerrar el menú de opciones después de aplicar los filtros
        $('#filterOptions').hide();
    });
});
function Cargar() {
    $(document).on("change", "#id_programaformacion", function() {
        $.post("../../include/select.php", {
            action: 'crgrprogramaFormacion2',
            id_programaformacion: $("#id_programaformacion").val()
        },
        function(data) {
            $("#datospf").html(data.lisDPF);
        },
        'json'
        ).fail(function(xhr, status, error) {
            console.error(error);
        });
    });
    $.post("../../include/select.php", {
        action: 'crgrTiposModalidad'
    },
    function(data) {
        $("#id_modalidad").html(data.lisTiposM);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $.post("../../include/select.php", {
        action: 'crgrprogramaFormacion'
    },
    function(data) {
        $("#id_programaformacion").html(data.lisTiposPF);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $.post("../../include/select.php", {
        action: 'crgrprogramaFormacion3'
    },
    function(data) {
        $("#id_programaformacion3").html(data.lisTiposPF);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $.post("../../include/select.php", {
        action: 'crgrTiposNivelFormacion'
    },
    function(data) {
        $("#id_nivel_formacion").html(data.lisTiposNF);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $.post("../../include/select.php", {
        action: 'crgrTiposJornada'
    },
    function(data) {
        $("#id_jornada").html(data.lisTiposJ);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $(document).ready(function(){  
        $.post("../../include/select.php", {
            action: 'CrgrEmpresa' 
        },
        function(data) {
            $("#id_empresa").html(data.listEmpresa);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        });
    });
    $(document).ready(function(){  
        $.post("../../include/select.php", {
            action: 'CrgrArea' 
        },
        function(data) {
            $("#id_area").html(data.listArea);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        });
    });
    $(document).on("change", "#id_empresa",
        function (){
                $.post("../../include/select.php", {
                action:'crgrSolicitante',
                id_empresa:$("#id_empresa").val()
                }, function(data){ 
                    $("#solicitante").html(data.listSolicitante);
                }, 'json');	
    });	
    $(document).on("change", "#cod_dpto",
        function (){
                $.post("../../include/select.php", {
                action:'crgrMuni',
                cod_dpto:$("#cod_dpto").val()
                }, function(data){ 
                    $("#cod_municipio").html(data.listMuni);
                }, 'json');	
    });	
    $(document).on("change", "#cod_municipio",function (){
        $.post("../../include/select.php", {
            action:'crgrPoblados',
            cod_municipio:$("#cod_municipio").val()
        }, function(data){ 
                $("#cod_poblado").html(data.listPoblado);
            }, 'json');	
    });	
    $(document).ready(function(){  
        $.post("../../include/select.php", {
            action: 'crgrDepto' 
        },
        function(data) {
            $("#cod_dpto").html(data.listDepto);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        });
    });
    $(document).ready(function(){  
        $.post("../../include/select.php", {
            action: 'CrgrCompetencia' 
        },
        function(data) {
            $("#id_competencia").html(data.listCmpt);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        });
    });
    $(document).on("change", "#id_competencia",function (){ 
        $.post("../../include/select.php", {
            action: 'CrgrRA' ,
            id_competencia:$("#id_competencia").val()
        },
        function(data) {
            $("#id_resultado_aprendizaje").html(data.listRa);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        });
    });
}
$(document).on("click", "#detalleSolicitud", function() {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action: 'detalleSolicitud',
        id_solicitud: idSolicitud
    }, function(data) {
        if (data.rst == '1') {
            $("#datalleSolicitudF").html(data.ListDetalle);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
        }
    }, 'json');
});
$(document).on("click", "#btn_pf", function () {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action: 'ListarSolicitud_pf',
        id_solicitud: idSolicitud
    }, function(data) {
        if (data.rst == '1') {
            $("#form_pf").html(data.ListPf);
            AsignacionesCargar(idSolicitud);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
        }
    }, 'json');	
});

$(document).on("click", "#detalleOferta", function () {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action: 'ListarOferta',
        id_solicitud: idSolicitud
    }, function (data) {
        if (data.rst == '1') {
            $("#form_Of").html(data.ListOf);
            AsignacionesCargar(idSolicitud)
            $(document).on("click", "#subirNoti2", function () {
                $.post("../../include/cntrlNoti.php", {
                    action: 'SubirContenido',
                    id_solicitud: idSolicitud
                }, function (data) {
                    if (data.rst == '1') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.ms,
                            showConfirmButton: false,
                            timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.ms
                        });
                    }
                }, 'json');
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
        }
    }, 'json');
});

$(document).on("click", "#BtnAsesoramientoA",function ()	{
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action:'ListarSoliAsesoramiento',
        id_solicitud: idSolicitud
    }, function(data){
        if(data.rst=='1'){
            $("#form_AA").html(data.ListAA);
            AsignacionesCargar(idSolicitud)
        }
            else { Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            }); }
        }, 'json');	
    }
);
            $(document).on("click", "#subirNoti2",function ()	{
                var idSolicitud = $(this).data('id');
                $.post("../../include/cntrlNoti.php", {
                    action:'SubirContenido',
                    id_solicitud: idSolicitud
                }, function(data){
                    if(data.rst=='1'){
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.ms,
                            showConfirmButton: false,
                            timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                        }).then(() => {
                            location.reload();
                        });
                    }
                        else { Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.ms
                        }); }
                    }, 'json');	
                }
            );
            $(document).on("click", "#subirNoti3",function ()	{
                var idSolicitud = $(this).data('id');
                $.post("../../include/cntrlNoti.php", {
                    action:'SubirContenidoOf',
                    id_solicitud: idSolicitud
                }, function(data){
                    if(data.rst=='1'){
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.ms,
                            showConfirmButton: false,
                            timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                        }).then(() => {
                            location.reload();
                        });
                    }
                        else { Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.ms
                        }); }
                    }, 'json');	
                }
            );
            $(document).on("click", "#ModalCursoOf",function ()	{
                var idSolicitud = $(this).data('id');
                $.post("../../include/cntrlSoli.php", {
                    action:'OfertarCursoModal',
                    id_solicitud: idSolicitud
                }, function(data){
                    if(data.rst=='1'){
                        $("#modalOf").html(data.modal);
                    }
                        else { Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.msj
                        }); }
                    }, 'json');	
                }
            );
$(document).on("click", "#btn_subir",function ()	{
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action: 'noticiaCreado' ,
        id_solicitud: idSolicitud
        
    },
    function(data) {
        if(data.rstl=="1"){	
            $("#noticia_creada").html(data.noticia);
            $(document).on("click", "#subirNoti",function ()	{
                console.log("ID de la solicitud: " + idSolicitud);
                $.post("../../include/cntrlNoti.php", {
                    action:'SubirContenidoN',
                    id_solicitud: idSolicitud
                }, function(data){
                    if(data.rst=='1'){
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.ms,
                            showConfirmButton: false,
                            timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                        }).then(() => {
                            location.reload();
                        });
                    }
                        else { Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.ms
                        }); }
                    }, 'json');	
                }
            );
        } 
            else{	
                alert(data.msj);
            }
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
});
$(document).on("click", "#modalCancel", function() {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action: 'Cancel',
        id_solicitud: idSolicitud
    }, function(data) {
        if (data.rst == '1') {
            $("#cancel").html(data.cancel);
            $('#btnCancelarSoli').on("click", function() {
                if ($("#detalle_cancel").val()==""){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe Digitar Mensaje de Confirmacion Para su Cancelacion...'
                    });
                    focus('#detalle_cancel');
                }else{
                        console.log("ID de la solicitud a cancelar: " + idSolicitud);
                        $.post("../../include/cntrlSoli.php", {
                            action: 'denegarSolicitud',
                            id_solicitud: idSolicitud,
                            detalle_cancel:$("#detalle_cancel").val()
                        }, function(data) {
                            if (data.rstl == "1") {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: data.msj,
                                    showConfirmButton: false,
                                    timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                                }).then(() => {
                                    location.reload();
                                });// Recargar la página para ver los cambios
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.msj
                                });
                            }
                        }, 'json');
                }
            });
        } else {
            // DUIDADO CON EL ,SJ O MS
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
        }
    }, 'json');
});
$(document).on("click", "#instructorProto", function() {
    var idSolicitud = $(this).data('id');
    $.post("../../include/cntrlSoli.php", {
        action: 'instructorProto' ,
        id_solicitud: idSolicitud
    },
    function(data) {
        if(data.rstl=="1"){	
            $("#Tjinstructor").html(data.tarjeta); } 
            else{	
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msj
                });
            }
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
});
$(document).on("click", "#CrearInstru", function() {
    var idSolicitud = $(this).data('id');
    $.post("../../include/cntrlSoli.php", {
        action: 'CrearInstru',
        id_solicitud: idSolicitud
    },
    function(data) {
        if(data.rstl=="1"){	
            Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.msj,
                    showConfirmButton: false,
                    timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                }).then(() => {
                    location.reload();
                });
            } 
            else{	
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msj
                });
            }
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
});
$(document).on("click", "#Ecompetencia",function ()	{
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action:'ListarEcompetencia',
        id_solicitud: idSolicitud
    }, function(data){
        if(data.rst=='1'){
            AsignacionesCargar(idSolicitud)
            $("#Ecompetencia_form").html(data.ListEc);
        }
            else { Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            }); }
        }, 'json');	
    }
);

