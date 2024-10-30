<?php
// https://getbootstrap.com/docs/5.0/components/modal/
include_once('conex.php');
header('Content-Type: text/html; charset='.$charset);
header('Cache-Control: no-cache, must-revalidate');
session_name($session_name);
session_start();
$conn = Conectarse();

switch ($_REQUEST['action']) {
    case 'instructorCreado':
        $jTableResult = array();
        $jTableResult['rstl'] = "";
        $jTableResult['msj'] = "";
        $jTableResult['tarjeta'] = "";
        
        $query = "SELECT  
                userprofile.nombre, 
                userprofile.nombre_dos, 
                userprofile.apellido,
                userprofile.imagen,
                r.nombre AS nombre_rol
            FROM 
                userprofile
            JOIN
                rol r ON userprofile.id_rol = r.id_rol
            WHERE 
                userprofile.id_rol = 2"; 
        
        $result = mysqli_query($conn, $query);
        
// Verificar si se encontraron resultados
    if (mysqli_num_rows($result) > 0) {
        while($registro = mysqli_fetch_array($result)) {
            $jTableResult['msj'] = "Instructor Creado con Éxito.";
            $jTableResult['rstl'] = "1";
                    // Verificar si la imagen existe y no está vacía
                    $rutaImagen = 'uploads/' . htmlspecialchars($registro['imagen']);
                    $imagenHTML = "";

                    if (!empty($registro['imagen']) && file_exists($rutaImagen)) {
                        $imagenHTML = "
                        <center>
                        <img src='$rutaImagen' alt='Foto de perfil' class='profile-photo'>
                        </center>";
                    } else {
                        $imagenHTML = "
                        <center>
                        <img src='../../imagenes/user1.png' alt='Foto de perfil' class='profile-photo'>
                        </center>";
                    }

                    $jTableResult['tarjeta'] .= "
                    <div class='row'>
                        <div class='col-sm-5'>
                            <ul class='navbar-nav'>
                                 <style>
                    .card {
                        background-color: #f2f2f2;
                        border-radius: 10px;
                        padding: 15px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        margin: 10px 0;
                        transition: transform 0.3s ease;
                    }

                    .card:hover {
                        transform: scale(1.05);
                    }

                    .card-avatar {
                        text-align: center;
                        margin: 20px auto;
                    }

                    .profile-photo {
                        width: 70px;
                        height: 70px;
                        border-radius: 50%;
                    }

                    .card-title {
                        text-align: center;
                        font-weight: bold;
                        margin-top: 10px;
                    }

                    .card-subtitle {
                        text-align: center;
                        color: #888;
                        margin-bottom: 10px;
                    }

                    .card-social {
                        display: flex;
                        justify-content: center;
                        padding: 0;
                        list-style: none;
                    }

                    .card-social__item {
                        margin: 0 10px;
                    }

                    .card-social__item svg {
                        width: 24px;
                        height: 24px;
                        fill: #555;
                        transition: fill 0.3s ease;
                    }

                    .card-social__item svg:hover {
                        fill: #000;
                    }

                    /* Media Queries para dispositivos móviles */
                    @media (max-width: 768px) {
                        .card {
                            padding: 10px;
                        }

                        .profile-photo {
                            width: 60px;
                            height: 60px;
                        }

                        .card-title {
                            font-size: 1rem;
                        }

                        .card-subtitle {
                            font-size: 0.875rem;
                        }
                    }

                    @media (max-width: 480px) {
                        .card {
                            padding: 8px;
                            margin: 8px 0;
                        }

                        .profile-photo {
                            width: 50px;
                            height: 50px;
                        }

                        .card-title {
                            font-size: 0.875rem;
                        }

                        .card-subtitle {
                            font-size: 0.75rem;
                        }

                        .card-social__item svg {
                            width: 20px;
                            height: 20px;
                        }
                    }
                </style>
                                <div class='card'>
                                    <div class='card-info'>
                                        <a class='card-avatar' href=''>
                                            $imagenHTML
                                        </a>  
                                        <div class='card-title'><p class='heading'>" . htmlspecialchars($registro["nombre"]) . " " . htmlspecialchars($registro["apellido"]) . "</p></div>
                                        <div class='card-subtitle'><p class='follow'>" . htmlspecialchars($registro["nombre_rol"]) . "</p></div>
                                    </div>
                                    <div class='back'>
                                        <ul class='card-social'>
                                            <li class='card-social__item'>
                                                <svg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                                                    <path d='M14 9h3l-.375 3H14v9h-3.89v-9H8V9h2.11V6.984c0-1.312.327-2.304.984-2.976C11.75 3.336 12.844 3 14.375 3H17v3h-1.594c-.594 0-.976.094-1.148.281-.172.188-.258.5-.258.938V9z'></path>
                                                </svg>
                                            </li>
                                            <li class='card-social__item'>
                                                <svg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                                                    <path d='M20.875 7.5v.563c0 3.28-1.18 6.257-3.54 8.93C14.978 19.663 11.845 21 7.938 21c-2.5 0-4.812-.687-6.937-2.063.5.063.86.094 1.078.094 2.094 0 3.969-.656 5.625-1.968a4.563 4.563 0 0 1-2.625-.915 4.294 4.294 0 0 1-1.594-2.226c.375.062.657.094.844.094.313 0 .719-.063 1.219-.188-1.031-.219-1.899-.742-2.602-1.57a4.32 4.32 0 0 1-1.054-2.883c.687.328 1.375.516 2.062.516C2.61 9.016 1.938 7.75 1.938 6.094c0-.782.203-1.531.609-2.25 2.406 2.969 5.515 4.547 9.328 4.734-.063-.219-.094-.562-.094-1.031 0-1.281.438-2.36 1.313-3.234C13.969 3.437 15.047 3 16.328 3s2.375.484 3.281 1.453c.938-.156 1.907-.531 2.907-1.125-.313 1.094-.985 1.938-2.016 2.531.969-.093 1.844-.328 2.625-.703-.563.875-1.312 1.656-2.25 2.344z'></path>
                                                </svg>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>";
                }
            } else {
                mysqli_rollback($conn);
                $jTableResult['msj'] = "Error al Crear el Instructor.";
                $jTableResult['rstl'] = "0";
            }
            print json_encode($jTableResult);
    break;
    }

mysqli_close($conn);
?>