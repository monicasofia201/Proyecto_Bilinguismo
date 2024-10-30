$(document).ready(function(){ 
    $.post("../../include/cntrlGprogramaFormacion.php", {
        action: 'MisPf_sin'
    }, function(data) {
        if (data.rs === "1") {
            $("#sin_contenido").html(
                `<h1 class="title">Tus Programa de Formacion en Curso</h1>` +
                data.tabla
            );
        } else {
            // mirar actualizar perfil
            $("#sin_contenido").html(`
                <h4 class="title">No hay Programas en Curso</h4>
            `);
        }
    }, 'json');
});
$(document).on("click", "#ExportarProgramacion", function () {
    window.location.href = "../../include/cntrlGprogramaformacion.php?action=exportarProgramacion";
});
$(document).on("click", "#btn_ListPf", function () { 
    var id_programaformacion = $(this).data('id');
    $.post("../../include/cntrlGprogramaFormacion.php", {
        action: 'Listar_pf',
        id_programaformacion: id_programaformacion

    }, function(data) {
        if (data.rst === "1") {
            $("#List_Gestion").html(
                data.ListPf
            );
        } else { Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.ms
        }); }
    }, 'json');
});
$(document).on("click", "#calendario", function() {
    var id_programaformacion = $(this).data('id');
    console.log(id_programaformacion);
    $.ajax({
        type: "POST",
        url: "../../include/programar.php",
        data: { id_programaformacion: id_programaformacion },
        dataType: 'json',
        success: function(response) {
            console.log("ID Programa Formación enviado correctamente: " + id_programaformacion);
        },
        error: function(xhr, status, error) {
            console.error("Error al enviar el ID Programa Formación: " + error);
        }
    });
});
$(document).on("click", "#create", function () { 
    // Crear una nueva sección de formulario
    var newFormSection = `
        <div class="form-section">
            <h6>Nombre Completo</h6>
            <input type='text'><br>
            <h6>Numero de identificacion</h6>
            <input type='number'>
            <h6>Correo Electronico</h6>
            <input type='text'><br>
            <hr>
        </div>
    `;
    // Añadir la nueva sección de formulario al contenedor
    $('#form_container').append(newFormSection);
});
$(document).on("click", "#btnGuardarCambios3",function ()	{
    var id_programaformacion = $(this).data('id');
    console.log(id_programaformacion);
    $.post("../../include/cntrlGprogramaFormacion.php", {
        action:'aceptarSolicitudPf',
        id_programaformacion: id_programaformacion
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