$(document).ready(function(){  
    $.post("../../include/cntrlInstru.php", {
        action: 'instructorCreado' 
    },
    function(data) {
        if(data.rstl=="1"){	
            $("#id_cardInstru").html(data.tarjeta); } 
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