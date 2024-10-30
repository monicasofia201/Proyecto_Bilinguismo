<?php
// Incluir el archivo de configuración de conexión a la base de datos
include_once('../../include/conex.php');

// Establecer el tipo de contenido a HTML con el charset especificado en la configuración
header('Content-Type: text/html; charset='.$charset);

// Iniciar la sesión con el nombre de sesión configurado
session_name($session_name);
session_start();

// Verificar si existe una sesión activa con el id_userprofile
if (isset($_SESSION['id_userprofile'])){
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Incluir enlaces a los archivos CSS y otros metadatos necesarios -->
    <?php include_once('cabecera.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Administrador</title>
    <script src="../../herramientas/js/biblioteca.js"></script>
    <link rel="stylesheet" href="../../herramientas/css/style.css">

    <title>Libros</title>
</head>

<body>
    <div class="layout">
        <!-- Menú de navegación -->
        <aside class="layout__aside">
            <div class="aside__user-info">
                <?php
                    // Incluir el menú de navegación
                    include_once('menu.php');
                    ?>
            </div>
        </aside>
        <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f7f7f7;
            margin: 0;
        }
        .password-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .password-container h2 {
            margin-bottom: 20px;
        }
        .password-input-container {
            position: relative;
            margin-bottom: 20px;
        }
        .password-input-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        .password-input-container input {
            width: 100%;
            padding: 10px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .password-input-container input:focus {
            border-color: #007bff;
            outline: none;
        }
        .submit-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
        <div class="container is-fluid mb-6"> <button type="button" class="btn nav-link nav-item-hover fixed-top-right"
                onclick="goBack()">
                <div class="password-container">
                    <h2>Cambiar Contraseña</h2>
                    <div class="password-input-container">
                        <i class="fas fa-lock"></i>
                        <input type="contraseña" id="contraseña" placeholder="Nueva Contraseña">
                    </div>
                    <button type="button" class="submit-button" id="contraNew">Guardar</button>
                </div>
        </div>
        <script>
            function validarClave(clave) {
		// Expresión regular para validar que haya al menos 6 letras y un número
                    const regexClave = /^(?=.*[A-Za-z]{6,})(?=.*\d)[A-Za-z\d]{7,20}$/;
                    return regexClave.test(clave);
                }
            $(document).on("click", "#contraNew", function () {
                if (!validarClave($("#contraseña").val())) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La clave debe tener al menos 6 letras y un número',
                    }).then(() => {
                        $("#contraseña").focus();
                    });
                } else {
                    // Envío de datos al servidor
                    $.post("../../include/ctrlIndex2.php", {
                        action: 'actualizarContraseña',
                        nuevaContraseña: $("#contraseña").val()
                    }, function (data) {
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
                                // Contraseña actualizada con éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: 'Contraseña actualizada correctamente',
                                    showConfirmButton: false,
                                    timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                                });
                                setTimeout(function () {
                                    location.href = "inicio.php";
                                }, 1500); // Redirige después de mostrar la alerta
                            }
                        }
                    }, 'json');
                }
            });

        </script>
</body>

</html>
<?php
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
} else {
    header("Location: ../../index.php");
}
?>