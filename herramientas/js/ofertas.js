// alertas ofertas
$(document).ready(function(){ 
    $.post("../../include/cntrlSoli.php", {
        action: 'MisOfertas'
    }, function(data) {
        if (data.rs === "1") {
            $("#oferta_curso").html(
                `<h1 class="title">Tus Cursos Ofertados</h1>` +
                data.tabla
            );
        } else {
            // mirar actualizar perfil
            $("#oferta_curso").hide();
            
        }
    }, 'json');
    $.post("../../include/cntrlSoli.php", {
        action: 'AlertaOferta'
    }, function(data) {
        if (data.rst === "1") {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Hay demasiados usuarios Interesados en tu oferta'  + data.ms + 'Vamos a Revisarla!!!',
                showConfirmButton: false,
                timer: 1500 // Tiempo en milisegundos (1.5 segundos)
            });
        } else if (data.rst === "2") {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Hay una Empresa Interesada en tu oferta' + data.ms ,
                    showConfirmButton: false,
                    timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                });
            }
    }, 'json');
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

}
$(document).on("click", "#btn_pf",function ()	{
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action:'ListarSolicitud_pf',
        id_solicitud: idSolicitud
    }, function(data){
        if(data.rst=='1'){
            $("#form_pf").html(data.ListPf);
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
$(document).on("click", "#detalleOferta",function ()	{
    var idSolicitud = $(this).data('id');
    console.log("ID de la solicitud: " + idSolicitud);
    $.post("../../include/cntrlSoli.php", {
        action:'ListarOferta',
        id_solicitud: idSolicitud
    }, function(data){
        if(data.rst=='1'){
            $("#form_Of").html(data.ListOf);
            AsignacionesCargar(idSolicitud)
            $(document).on("click", "#subirNoti2",function ()	{
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
        }
            else { Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.ms
            }); }
        }, 'json');	
    }
);
