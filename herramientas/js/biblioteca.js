$(document).ready(function() {
    $('#guardar_seccion').click(function() {
        $.post("../../include/cntrlBiblioteca.php", {
            action: 'NewSeccion',
            id_idioma: $("#id_idioma").val(),
            nombre: $("#nombre").val(),
            descripcion: $("#descripcion").val()
        },
        function(data) {
            if(data.rst == "1"){    
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
        'json'
        ).fail(function(xhr, status, error) {
            console.error(error);
        });
    });

    $.post("../../include/select.php", {
        action: 'Cgridioma' 
    },
    function(data) {
        $("#id_idioma").html(data.idioma);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    $.post("../../include/select.php", {
        action: 'CgrSeccion' 
    },
    function(data) {
        $("#secciones").html(data.seccion);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });

    $.post("../../include/select.php", {
        action: 'CgrSeccionL' 
    },
    function(data) {
        $("#fk_seccion").html(data.seccionL);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });
    
    $.post("../../include/select.php", {
        action: 'CgrLibros' 
    },
    function(data) {
        $("#libros").html(data.libro);
    },
    'json'
    ).fail(function(xhr, status, error) {
        console.error(error);
    });

    // Guardar libro
    $(document).on("click", "#guardar_libro", function() {
        var formData = new FormData();
        formData.append('action', 'NewBook');
        formData.append('titulo', $("input[name='titulo']").val());
        formData.append('prologo', $("input[name='prologo']").val());
        formData.append('autor', $("input[name='autor']").val());
        formData.append('descripcion_autor', $("input[name='descripcion_autor']").val());
        formData.append('anio_publicacion', $("input[name='anio_publicacion']").val());
        formData.append('fk_seccion', $("#fk_seccion").val());
        
        var fileInput = document.querySelector('input[name="archivo_pdf"]');
        if (fileInput && fileInput.files.length > 0) {
            formData.append('archivo_pdf', fileInput.files[0]);
        }
    
        $.ajax({
            url: '../../include/cntrlBiblioteca.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
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
                    $('#addBookForm')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.ms
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    
});
