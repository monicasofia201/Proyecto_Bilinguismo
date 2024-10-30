$(document).ready(function() { 
    clear();
        $.post("include/select.php", {
            action: 'crgrDepto' 
        },
        function(data) {
            $("#cod_dpto").html(data.listDepto);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
            });
        
        
            $.post("include/select.php", {
                action: 'crgrTiposDoc' 
            },
            function(data) {
                $("#id_tpdoc").html(data.lisTiposD);
                $("#id_tpdoc_rep").html(data.lisTiposD);
            },
            'json'
            ).fail(function(xhr, status, error) {
                console.error(error);
            });
            
            $.post("include/cntrlNoti.php", {
                action: 'noticiaCreado2' 
            },
            function(data) {
                if(data.rstl=="1"){	
                    $("#noticia_creada2").html(data.noticia); } 
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
            $.post("include/select.php", {
                action: 'CrgrTipoGenero' 
            },
            function(data) {
                $("#id_genero").html(data.lisTiposG);
                $("#id_genero_rep").html(data.lisTiposG);
                },
                'json'
                ).fail(function(xhr, status, error) {
                    console.error(error);
                });
                
                $.post("include/select.php", {
                    action: 'crgrPoblacion' 
                },
                function(data) {
                    $("#cod_poblacion").html(data.listPoblacion);
                    },
                    'json'
                    ).fail(function(xhr, status, error) {
                        console.error(error);
                    });

				$(document).on("click", "#btnEntrar", function () {
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
					} else if ($("#correo").val() == "") {
						// Validación de correo electrónico vacío
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Debe ingresar el correo de usuario...',
						});
						$("#correo").focus();
					} else if (!validateEmail($("#correo").val())) {
						// Validación de formato de correo electrónico
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Formato de correo electrónico inválido. Por favor, ingrese un correo válido.',
						});
						$("#correo").focus();
					} else {
						// Envío de datos al servidor
						$.post("include/ctrlIndex2.php", {
							action: 'confirmar',
							numeroiden: $("#numeroiden").val(),
							clave: $("#clave").val(),
							correo: $("#correo").val()
						}, function (data) {
							if (data.validacion == "no") {
								// Manejo de errores del servidor
								Swal.fire({
									icon: 'error',
									title: 'Error',
									text: 'Credenciales de acceso no existen',
								});
								clear();
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
										title: '¡Bienvenido!',
										text: 'Acceso autorizado. Redirigiendo...',
										showConfirmButton: false,
										timer: 1500 // Tiempo en milisegundos (1.5 segundos)
									});
									setTimeout(function () {
										location.href = "archivos/vista/inicio.php";
									}, 1500); // Redirige después de mostrar la alerta
								}
							}
						}, 'json');
					}
				});
				
				// Función para validar el formato de correo electrónico
				function validateEmail(email) {
					const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
					return re.test(email);
				}
				
					
			
			
			$(document).on("change", "#cod_dpto",
			function (){
					$.post("include/select.php", {
					action:'crgrMuni',
					cod_dpto:$("#cod_dpto").val()
					}, function(data){ 
						$("#cod_municipio").html(data.listMuni);
					}, 'json');	
				});	
			$(document).on("change", "#cod_municipio",function (){
				$.post("include/select.php", {
				action:'crgrPoblados',
				cod_municipio:$("#cod_municipio").val()
				}, function(data){ 
					$("#cod_poblado").html(data.listPoblado);
				}, 'json');	
			});	

			function clear() {
				$("#correo").val("");                    
                $("#id_tpdoc").val("0");
                $("#numeroiden_registro").val("");
                $("#nameusu").val("");
                $("#apellidoUsu").val("");
                $("#numeroiden_OC").val("");
                $("#numeroiden").val("");
                $("#id_genero").val(""); 
                $("#celular").val("");
                $("#correo_OC").val("");
                $("#cod_dpto").val("0");
                $("#cod_municipio").val("0");
                $("#cod_poblacion").val("0");
                $("#cod_poblado").val("0");
                $("#correo_registro").val("");
                $("#clave_registro").val("");
                $("#clave").val("");

			}
				
				$(document).on("click", "#btnCerrar",function (){
					
					clear();
				});
				




        //GUARDAR - USUARIO 

        $(document).on("click", "#btnGuardar", function () {
            // Función para validar el correo electrónico
            function validarCorreo(correo) {
                const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return regexCorreo.test(correo);
            }

            function validarClave(clave) {
                // Expresión regular para validar que haya al menos 6 letras y un número
                const regexClave = /^(?=.*[A-Za-z]{6,})(?=.*\d)[A-Za-z\d]{7,20}$/;
                return regexClave.test(clave);
            }

        // Función para validar el número de documento
            function validarNumeroDocumento(numero) {
                return numero.length >= 8 && numero.length <= 15;
            }


            if ($("#edadUsu").val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar la fecha de nacimiento',
                }).then(() => {
                    $("#fechaNacimientoUsu").focus();
                });
            } else if ($("#id_tpdoc").val() == "0") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un tipo de documento',
                }).then(() => {
                    $("#id_tpdoc").focus();
                });
            } else if ($("#numeroiden_registro").val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar el número de identificación',
                }).then(() => {
                    $("#numeroiden_registro").focus();
                });
            } else if (!validarNumeroDocumento($("#numeroiden_registro").val())) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El número de documento debe tener al menos 8 caracteres',
                }).then(() => {
                    $("#numeroiden_registro").focus();
                });
            } else if ($("#nameusu").val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar el nombre',
                }).then(() => {
                    $("#nameusu").focus();
                });
            } else if ($("#apellidoUsu").val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar el primer apellido',
                }).then(() => {
                    $("#apellidoUsu").focus();
                });
            } else if ($("#apellidoUsu_dos").val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar el segundo apellido',
                }).then(() => {
                    $("#apellidoUsu_dos").focus();
                });
            } else if ($("#cod_dpto").val() == "0") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un departamento',
                }).then(() => {
                    $("#cod_dpto").focus();
                });
            } else if ($("#correo_registro").val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar el correo electrónico',
                }).then(() => {
                    $("#correo_registro").focus();
                });
            } else if (!validarCorreo($("#correo_registro").val())) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El formato del correo electrónico es inválido',
                }).then(() => {
                    $("#correo_registro").focus();
                });
            } else if ($("#celular").val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar el número de celular',
                }).then(() => {
                    $("#celular").focus();
                });
            } else if ($("#id_genero").val() == "0") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un género',
                }).then(() => {
                    $("#id_genero").focus();
                });
            } else if ($("#cod_municipio").val() == "0") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un municipio',
                }).then(() => {
                    $("#cod_municipio").focus();
                });
            } else if ($("#cod_poblado").val() == "0") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar un poblado',
                }).then(() => {
                    $("#cod_poblado").focus();
                });
            } else if ($("#clave_registro").val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar la clave',
                }).then(() => {
                    $("#clave_registro").focus();
                });
            } else if (!validarClave($("#clave_registro").val())) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La clave debe tener al menos 6 letras y un número',
                }).then(() => {
                    $("#clave_registro").focus();
                });

            } else {
                $.post("include/ctrlIndex2.php", {
                    action: 'registroUsuNew',
                    id_tpdoc: $("#id_tpdoc").val(),
                    numeroiden_registro: $("#numeroiden_registro").val(),
                    nameusu: $("#nameusu").val(),
                    apellidoUsu: $("#apellidoUsu").val(),
                    apellidoUsu_dos: $("#apellidoUsu_dos").val(),
                    cod_dpto: $("#cod_dpto").val(),
                    correo_registro: $("#correo_registro").val(),
                    celular: $("#celular").val(),
                    id_genero: $("#id_genero").val(),
                    cod_municipio: $("#cod_municipio").val(),
                    cod_poblacion: $("#cod_poblacion").val(),
                    cod_poblado: $("#cod_poblado").val(),
                    clave_registro: $("#clave_registro").val()
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
                }, 'json').fail(function (jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la solicitud'
                    });
                });


                
                
            }
        });
        				
					$(document).on("click", "#btnVolver",function (){
						// alert('clear entrando');
						clear();
                    });

    document.addEventListener('DOMContentLoaded', function() {
            function FormEmpresa() {
                const checkbox = document.getElementById('addNewForm');
                const formEmpresa = document.getElementById('formEmpresa');
                if (checkbox.checked) {
					$("#formRegisUsu").hide();
                    formEmpresa.style.display = 'block';
                } else {
                    formEmpresa.style.display = 'none';
					$("#formRegisUsu").show();
                }
            }
            const checkbox = document.getElementById('addNewForm');
            checkbox.addEventListener('click', FormEmpresa);
        }
	);

	
// Restablecer contraseña 

    // Acción al hacer clic en el botón de recordar clave
    $(document).on("click", "#btnRecordar", function() {
        function validarCorreo(correo) {
            const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regexCorreo.test(correo);
        }
        // Validar que todos los campos estén completados
        if ($("#numeroiden_OC").val() === "" || $("#correo_OC").val() === "") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Todos los campos son obligatorios. Por favor, complete todos los campos.',
            }).then(() => {
                $("#numeroiden_OC").focus();
            });
            return;
        } else if (!validarCorreo($("#correo_OC").val())) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El formato del correo electrónico es inválido',
            }).then(() => {
                $("#correo_OC").focus();
            });
        } else {
            // Agregar console.log para verificar que se ingresa en el bloque correcto
            console.log("Enviando datos al controlador");
    
            // Enviar solicitud AJAX para restablecer la clave
            $.post("include/ctrlIndex2.php", {
                action: 'RestablecerContraseña',
                numeroiden: $("#numeroiden_OC").val(),
                correo: $("#correo_OC").val()
            }, function(data) {
                console.log("Respuesta del servidor:", data);
                if (data.rstl == "1") {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Se ha restablecido la contraseña con éxito. Verifique su correo electrónico.',
                        showConfirmButton: false,
                        timer: 1500 // Tiempo en milisegundos (1.5 segundos)
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al restablecer la contraseña: ' + data.msj
                    });
                }
            }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al comunicarse con el servidor. Por favor, inténtelo de nuevo más tarde.'
                });
            });
        }
    });
    




/* "Recordar clave - checkbox"  */

    $(document).on("change", "#exampleSwitch", function() {
        if ($(this).is(":checked")) {
            console.log('Switch activado');
            // Aquí puedes agregar acciones adicionales cuando el switch está activado
        } else {
            console.log('Switch desactivado');
            // Aquí puedes agregar acciones adicionales cuando el switch está desactivado
        }
    });


});

