$(document).on("click", "#publicar_noti", function (event) {
    if ($("#titulo1").val() == "") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe ingresar el Titulo'
        });
        $("#titulo1").focus();
    } else {
        if ($("#imagen").val() == "") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe ingresar la Imagen'
            });
            $("#imagen").focus();
        } else {
            var formData = new FormData();
            formData.append("action", "guardarNoticia");
            formData.append("titulo", $("#titulo1").val());
            formData.append("descripcion", $("#descripcion_Publi").val());
            formData.append("fecha_fin", $("#fecha_fin").val());
            formData.append("imagen", $("#imagen")[0].files[0]);
            formData.append("fecha_inicio", $("#id_fecha_mostrada").val());
            formData.append("id_url", $("#id_url").val());
            $.ajax({
                url: "../../include/cntrlNoti.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.rstl == "1") {
                        $.post("../../include/cntrlSoli.php", {
                            action: 'Solicitud'
                        }, function (data) {
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
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.ms
                                });
                            }
                        }, 'json');
                    } else {
                        alert(data.msj);
                    }
                },
                dataType: 'json'
            });
        }
    }
});
function crearPf(){
    $(document).on("click", "#publicar_noti2", function () {
        // Validación de campos requeridos
        if ($("#titulo1").val() === "") {
            alert('Debe ingresar Titulo a la Oferta');
            $("#titulo1").focus();
            return;
        } 
        if ($("#imagen").val() === "") {
            alert('Debe ingresar la Imagen');
            $("#imagen").focus();
            return;
        }
        if ($("#nombre").val() === "") {
            alert('Debe Digitar un Nombre de Curso');
            $("#nombre").focus();
            return;
        }

        // Confirmación
        if (!confirm("¿Está seguro de Ofertar este Curso de " + $("#nombre").val() + "?")) {
            return;
        }

        // Creación del FormData y agregar los datos
        var formData = new FormData();
        formData.append("action", "registroCursoNew");

        // Capturar el valor y el nombre del programa de formación seleccionado
        var selectedOption = $("#id_programaformacionorigi").find("option:selected");
        formData.append("id_programaformacion", selectedOption.val()); // ID del programa de formación
        formData.append("nombre_programa", selectedOption.text()); // Nombre del programa de formación

        // Agregar otros campos al FormData
        formData.append("fecha_inicio", $("#fecha_inicio").val());
        formData.append("fecha_cierre", $("#fecha_cierre").val());
        formData.append("modalidad", $("#id_modalidad_label").text().trim()); // Modalidad desde el label
        formData.append("nivel_formacion", $("#nivel_formacion_label").text().trim()); // Nivel de formación desde el label
        formData.append("tipo_formacion", $("#tipo_formacion_label").text().trim()); // Tipo de formación desde el label
        formData.append("horas_curso", $("#horas_curso_label").text().trim()); // Horas del curso desde el label
        formData.append("titulo", $("#titulo1").val());
        formData.append("descripcion", $("#descripcion_Publi").val());
        formData.append("fecha_mostrada", $("#id_fecha_mostrada").val());
        formData.append("imagen", $("#imagen")[0].files[0]);
        formData.append("fecha_fin", $("#id_fecha_fin").val());
        formData.append("url", $("#id_url").val());

        // Enviar los datos mediante AJAX
        $.ajax({
            url: "../../include/cntrlNoti.php",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
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
            }
        });
    });
}



    $(document).on("click", "#hideRevista", function () {
        $("#revista").hide();
    });
$(document).on("click", "#showRevista", function () {
        $("#revista").show();
    });
$(document).ready(function() {
    $.post("../../include/select.php", {
        action: 'crgrTipoCategoria' 
    },
    function(data) {
        $("#id_categoria").html(data.lisTiposC);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $.post("../../include/cntrlNoti.php", {
        action: 'noticiaCreado' 
    },
    function(data) {
        if(data.rstl=="1"){	
            $("#noticia_creada").html(data.noticia); } 
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
        $("#revista").hide();
    });
// ALERTAS SM

function cargarcosas(){
    $(document).on("change", "#id_programaformacionorigi", function() {
        $.post("../../include/select.php", {
            action: 'crgrprogramaFormacion2',
            id_programaformacion: $("#id_programaformacionorigi").val()
        },
        function(data) {
            $("#datos_pf").html(data.lisDPF);
        },
        'json'
        ).fail(function(xhr, status, error) {
            console.error(error);
        });
    });
    
    // Esta es la llamada separada para cargar los tipos de programas de formación en el select
    $.post("../../include/select.php", {
        action: 'crgrprogramaFormacion'
    },
    function(data) {
        $("#id_programaformacionorigi").html(data.lisTiposPF);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    
}
$(document).on("click", "#noticiaful", function () {
    var idSolicitud = $(this).data('id');
    $.post("../../include/cntrlNoti.php", {
        action: 'ListarNoticia',
        id_solicitud: idSolicitud
    },
    function(data) {
        if(data.rst=="1"){	
            $("#noticiaFull").html(data.ListNoti); } 
            else{	
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.ms
                });
            }
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
});
$(document).on("click", "#MisSoliActivate", function () {
    $.post("../../include/select.php", {
        action: 'MisNoti' 
    },
    function(data) {
        if(data.rs=="1"){	
            $("#MisSoliForm").html(data.tabla); } 
            else{	
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.ms
                });
            }
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
});
$(document).on("click", "#detalle_oferta", function () {  
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlNoti.php", {
        action: 'ListarSolicitud_of' ,
        id_solicitud: idSolicitud
    },
    function(data) {
        if (data.rst=="1"){
            $("#formdetalle_oferta").html(data.Listof);
            
            $(document).on("click", "#ofertarme", function() {
                var formData = new FormData($('#postulacionForm')[0]);
                formData.append('action', 'Postular');
                formData.append('id_solicitud', idSolicitud);
                formData.append("detalle", $("#detalle").val());
                $.ajax({
                    url: "../../include/cntrlNoti.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(data) {
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
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });
        }
        else{
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
        }
        
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
});
function MostrarTipo_Categoria() {
    var select = document.getElementById("id_categoria");
    var tipo_soliDiv = document.getElementById("tipo_cate");
    tipo_soliDiv.innerHTML = "";
    var SelectipoSoli = select.value;
    switch (SelectipoSoli) {
        case "1":
            tipo_soliDiv.innerHTML = `
                <div class="mb-3">
                    <button type="button" class="create-button" id="publicar_noti">Publicar</button>
                    <a type="button" class="close-button" href="" role="button">Cancelar</a>
                </div>
            `;
            crearPf()
            break;

        case "3":
            tipo_soliDiv.innerHTML = `
            <style>

                .course-data-container {
                    display: flex;
                    flex-wrap: wrap;
                
                    width: 100%; /* Ajusta el ancho según sea necesario */
                    max-width: 400px; /* Ajusta el ancho máximo según sea necesario */
                    height: auto; /* Ajusta la altura según sea necesario */
                    max-height: 80vh; /* Ajusta la altura máxima según sea necesario */
                    overflow-y: auto; /* Permite el desplazamiento si el contenido es demasiado alto */
                }

                .course-data-field,
                .mb-3 {
                    width: 100%;
                    margin-bottom: 15px;
                }

                .create-button, .close-button {
                    display: inline-block;
                    margin-right: 10px;
                }

                .create-button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    text-align: center;
                }

                .close-button {
                    background-color: #f44336;
                    color: white;
                    
                    text-decoration: none;
                    border-radius: 5px;
                    text-align: center;
                }
                                
                            </style>

                            <hr>
                        <div class="form ">
                    <div class="course-data-container">
                        <h2>DATOS DE CURSO</h2>
                        <div class="course-data-field">
                            <label class="modal-title" for="nombre">ProgramaFormacion</label>
                            <select id="id_programaformacionorigi"> </select>
                        </div>
                        <div class="course-data-field">
                            <label class="modal-title" for="fecha_inicio">Fecha inicio</label>
                            <input class="form-control" type="date" id="fecha_inicio" />
                        </div>
                        <div class="course-data-field">
                            <label class="modal-title" for="fecha_cierre">Fecha cierre</label>
                            <input class="form-control" type="date" id="fecha_cierre" />
                        </div>
                        <div id="datos_pf"></div>
                        <div class="mb-3">
                            <label class="modal-title" for="id_fecha_mostrada">Fecha Fin Evento</label>
                            <input class="form-control" type="date" id="id_fecha_mostrada" name="fecha_inicio">
                        </div>
                        <div class="modal-footer">
                            <a type="button" class="create-button" id="publicar_noti2">Publicar</a>
                            <a type="button" class="close-button" role="button">Cancelar</a>
                        </div>
                    </div>
                </div>

            `;
            cargarcosas()
            crearPf()
            break;
        // Agrega más casos según sea necesario para otras categorías
    }
}

function cargarDatos(){
    $.post("../../include/select.php", {
        action: 'CrgrArea' 
    },
        function(data) {
            $("#id_area").html(data.listArea);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
        }
    );
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
        action: 'crgrEstado'
    }, function (data) {
        var opcionesEstado = data.listEstado;
        $("#id_estado").html(opcionesEstado);
    }, 'json').fail(function (xhr, status, error) {
        console.error(error);
    });
}
