$(document).ready(function(){ 
    $.post("../../include/cntrlSoli.php", {
        action: 'asignaciones'
    }, function(data) {
        if (data.rs === "1") {
            $("#tablecontenido").html(
                `<h1 class="title">Tus Solicitudes Asignadas</h1>` +
                data.tabla
            );
        } else {
            // mirar actualizar perfil
            $("#tablecontenido").html(`
                <h4 class="title">No hay Solicitudes Asignadas</h4>
            `);
        }
    }, 'json');
});

$(document).on("click", "#btn_Inicurso", function () {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action: 'IniCursoAsign',
        id_jornada: $("#id_jornada").val(),
        matriculados: $("#matriculados").val(),
        ficha: $("#ficha").val(),
        id_solicitud: idSolicitud
    }, function (data) {
        if (data.rstl == "1") {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Registro guardado con éxito',
                showConfirmButton: false,
                timer: 1500 // Tiempo en milisegundos (1.5 segundos)
            }).then(() => {
                location.reload();
            });
            clear()
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.msj
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
            cargarMetadatos();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
        }
    }, 'json');	
});
$(document).on("click", "#btn_asign", function() {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    
    $.post("../../include/cntrlSoli.php", {
        action: 'Asignado_Crear',
        id_solicitud: idSolicitud
    }, function(data) {
        if (data.rst == '1') {
            $("#asignado").html(data.Asignform);
            cargarMetadatos();

            
            
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
        }
    }, 'json');
});
$(document).on("click", "#btn_curso", function() {
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud); // Verifica que el ID sea el correcto
    
    if ($("#ficha2").val() == "") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe ingresar la ficha del curso'
        });
        $("#ficha2").focus();
    } else {
        var confirmacion = confirm("Esta seguro de Crear este Curso de " + $("#nombre_programa2").text().trim());
        if (confirmacion) {
            var formData = new FormData();
            formData.append("action", "registroCursoNew");
            formData.append("fecha_inicio", $("#fecha_inicio2").val());
            formData.append("nombre_programa", $("#nombre_programa2").text().trim());
            formData.append("fecha_cierre", $("#fecha_cierre2").val());
            formData.append("id_jornada", $("#id_jornada").val());
            formData.append("modalidad", $("#id_modalidad_label2").text().trim());
            formData.append("nivel_formacion", $("#nivel_formacion_label2").text().trim());
            formData.append("tipo_formacion", $("#tipo_formacion_label2").text().trim());
            formData.append("horas_curso", $("#horas_curso_label2").text().trim());
            formData.append("ficha", $("#ficha2").val());
            formData.append("id_solicitud", idSolicitud);

            $.ajax({
                url: "../../include/cntrlSoli.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    if (data.rstl == "1") {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.msj,
                            showConfirmButton: false,
                            timer: 1500
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
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error en la solicitud:', textStatus, errorThrown);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al intentar crear el curso.'
                    });
                }
            });
        }
    }
});

function cargarMetadatos() {
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
$(document).on("click", "#btn_LEc", function() {
    var idSolicitud = $(this).data('id');
    $.post("../../include/cntrlSoli.php", {
        action: 'asignacion_Listar_Ec',
        id_solicitud: idSolicitud
    }, function(data) {
        if (data.rst == "1") {
            $("#form_Ec").html(data.ListEc);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
            
        }
    }, 'json');
});
$(document).on("click", "#btn_LRa", function() {
    var idSolicitud = $(this).data('id');
    $.post("../../include/cntrlSoli.php", {
        action: 'asignacion_Listar_Ra',
        id_solicitud: idSolicitud
    }, function(data) {
        if (data.rst == "1") {
            $("#form_Ra").html(data.ListRa);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            });
            
        }
    }, 'json');
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
                if ($("#Denegacion").val()==""){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe Digitar Mensaje de Confirmacion Para su Cancelacion...'
                    });
                    focus('#Denegacion');
                }else{
                    
                        var idSolicitud = $(this).data('id');
                        console.log("ID de la solicitud a cancelar: " + idSolicitud);
                        $.post("../../include/cntrlSoli.php", {
                            action: 'denegarSolicitud',
                            id_solicitud: idSolicitud
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
                                }); // Recargar la página para ver los cambios
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
            alert(data.ms);
        }
    }, 'json');
});