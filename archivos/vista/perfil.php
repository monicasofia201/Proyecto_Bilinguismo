<?php
// Incluir el archivo de conexión a la base de datos y otras configuraciones necesarias
include_once('../../include/conex.php');
header('Content-Type: text/html; charset=' . $charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn = Conectarse();

// Verificar si hay una sesión activa
if (isset($_SESSION['id_userprofile'])) {
    // Consulta para obtener los datos del usuario usando una declaración preparada
    $query = "SELECT * FROM userprofile WHERE id_userprofile = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['id_userprofile']);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se ejecutó la consulta correctamente
    if ($resultado && $resultado->num_rows > 0) {
        // Recuperar los datos del usuario
        $fila = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Incluir enlaces a los archivos CSS y otros metadatos necesarios -->
    <?php include_once('cabecera.php'); ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario </title>
    <link rel="stylesheet" href="../../herramientas/css/perfil.css">
    <link rel="stylesheet" href="../../herramientas/css/style.css">

</head>

<script>
$(document).on("click", "#btnActualizarContraseña", function() {
    if ($("#numeroiden").val() == "") {
        // Validación de número de identificación vacío
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe ingresar número de identificación...',
        });
        $("#numeroiden").focus();
    } else if (!(/^\d{8,}$/.test($("#numeroiden").val()))) {
        // Validación de formato de número de identificación (al menos 8 dígitos)
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El número de identificación debe contener al menos 8 dígitos numéricos.',
        });
        $("#numeroiden").focus();
    } else if ($("#contraseña").val() == "") {
        // Validación de número de identificación vacío
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe ingresar número de identificación...',
        });
        $("#contraseña").focus();
    } else if (!(/^\d{8,}$/.test($("#numeroiden").val()))) {
        // Validación de formato de número de identificación (al menos 8 dígitos)
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La contraseña debe contener al menos 6 Caracteres.',
        });
        $("#contrasña").focus();
    } else {
        // Envío de datos al servidor
        $.post("../../include/ctrlIndex2.php", {
            action: 'confirmarCcontraseña',
            numeroiden: $("#numeroiden").val(),
            contraseña: $("#contraseña").val()
        }, function(data) {
            if (data.validacion == "no") {
                // Manejo de errores del servidor
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Credenciales de acceso no existen',
                });
                limpiar();
            } else {
                if (data.estado == "I") {
                    // Usuario inactivo
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'El usuario existe pero está inactivo',
                    });
                    limpiar();
                } else if (data.estado == "A") {
                    // Acceso autorizado
                    Swal.fire({
                        icon: 'success',
                        title: 'Correcto',
                        text: ' Redirigiendo...',
                        showConfirmButton: false,
                        timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                    });
                    setTimeout(function() {
                        location.href = "Ccontraseña.php";
                    }, 1500); // Redirige después de mostrar la alerta
                }
            }
        }, 'json');
    }
});
</script>

<body>
    <div class="layout">
        <!-- Menú de navegación -->
        <aside class="layout__aside">
            <div class="aside__user-info">
                <?php include_once('menu.php'); ?>
            </div>

        </aside>
        <!-- Contenido principal -->
        <div class="container layout__content">
            <div class="content__page">
              
                <!-- Campos del formulario -->


                <div class="contenido">
                    <!-- Tarjeta de información del usuario -->
                    <div class="card">
                        <div class="card-header">

                            <h2 class="title">Bienvenido <span><?php echo htmlspecialchars($fila['nombre']); ?>!</span>
                            </h2>

                            <?php
                                $rutaImagen = '../../include/uploads/' . htmlspecialchars($fila['imagen']);
                                if (file_exists($rutaImagen) && !empty($fila['imagen'])) {
                                    echo '<img class="circle" src="' . $rutaImagen . '" alt="Foto de Perfil">
                                    <div class="upload-container">
                                        <label for="imagen" class="upload-label">Cambia Tu Imagen
                                            <div class="image-upload">
                                                <label for="file-input">
                                                    <div class="upload-icon">
                                                        <i class="fa fa-upload"></i>
                                                    </div>
                                                </label>
                                                <input type="file" id="imagen" name="imagen" accept="image/*">
                                            </div>
                                        </label>
                                    </div>';
                                } else {
                                    echo '<div class="upload-container">
                                        <div class="circle">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <label for="imagen" class="upload-label">Sube tu imagen
                                            <div class="image-upload">
                                                <label for="file-input">
                                                    <div class="upload-icon">
                                                        <i class="fa fa-upload"></i>
                                                    </div>
                                                </label>
                                                <input type="file" id="imagen" name="imagen" accept="image/*">
                                            </div>
                                        </label>
                                    </div>';
                                }
                            ?>
                            <h3 class="card-title">Información Personal</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3">

                                <div class="col-sm-6">
                                    <label for="nombre" class="col-form-label">Nombres:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        value="<?php echo htmlspecialchars($fila['nombre']); ?>">
                                </div>
                                <div class="col-sm-6">
                                    <label for="apellido" class="col-form-label"> Apellidos:</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido"
                                        value="<?php echo htmlspecialchars($fila['apellido']); ?>">
                                </div>
                                <div class="col-sm-6">
                                    <label for="celular" class="col-form-label">Número de Celular:</label>
                                    <input type="text" class="form-control" id="celular" name="celular"
                                        value="<?php echo htmlspecialchars($fila['celular']); ?>">
                                </div>
                                <div class="col-sm-6">
                                    <label for="correo" class="col-form-label">Correo Electrónico:</label>
                                    <input type="email" class="form-control" id="correo" name="correo"
                                        value="<?php echo htmlspecialchars($fila['correo']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">

                            <div class="modal-footer"> <button type="button" class="create-button"
                                    name="btnActualizarUsuario" data-bs-toggle="modal"
                                    data-bs-target="#CambiarCModal">Cambiar contraseña</button>
                                <button type="button" class="create-button" name="btnActualizarUsuario"
                                    id="btnActualizarUsuario">Actualizar</button>
                            </div>
                        </div>

                    </div>
                </div>







            </div>

        </div>
    </div>
    </div>
    <script>
    // Manejar el clic del botón de actualizar usuario
    $(document).on("click", "#btnActualizarUsuario", function() {
        // Crear un objeto FormData
        var formData = new FormData();

        // Añadir los datos del formulario a formData
        formData.append('action', 'actualizarusuario');
        formData.append('nombre', $("#nombre").val());
        formData.append('nombre_dos', $("#nombre_dos").val());
        formData.append('apellido', $("#apellido").val());
        formData.append('apellido_dos', $("#apellido_dos").val());
        formData.append('correo', $("#correo").val());
        formData.append('numeroiden', $("#numeroiden").val());
        formData.append('celular', $("#celular").val());

        // Añadir el archivo de imagen si se ha seleccionado uno
        var imagen = $('#imagen')[0].files[0];
        if (imagen) {
            formData.append('imagen', imagen);
        }

        // Enviar solicitud AJAX con FormData
        $.ajax({
            url: "../../include/cntrlUsuarios.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                if (data.rstl == "1") {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Registro Actualizado con éxito',
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error en la solicitud: ' + textStatus);
            }
        });
    });
    </script>
</body>
<!-- MODAL CAMBIO CONTRASEÑA -->
<div class="modal fade" id="CambiarCModal" tabindex="-1" aria-labelledby="CambiarCLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editSolicitudLabel">Cambiar Contraseña</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label> Identificación:</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-card identificacion-icon"></i>
                        <input type="text" class="form-control" id="numeroiden" name="numeroiden"
                            title='Ingrese solo números' placeholder="123456789" style='cursor:pointer;'
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                </div>
                <div class="form-group">
                    <label> Contraseña: </label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock password-icon"></i>
                        <input type="password" class="form-control" id="contraseña" title='password'
                            style='cursor:pointer;'>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="close-button" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="create-button" id="btnActualizarContraseña">Guardar
                    Cambios</button>
            </div>
        </div>
    </div>
</div>

</html>

<?php
    } else {
        // Si hay un error en la consulta, imprimir el mensaje de error
        echo "Error al ejecutar la consulta: " . mysqli_error($conn);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
} else {
    // Si no hay una sesión activa, redirigir al usuario a la página de inicio de sesión
    header("Location: ../../index.php");
}
?>