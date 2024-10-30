<?php
// Incluir el archivo de configuración de conexión a la base de datos
require_once('../../calendario/action/conexao.php');

// Establecer el tipo de contenido a HTML con el charset especificado en la configuración
header('Content-Type: text/html; charset='.$charset);

// Iniciar la sesión con el nombre de sesión configurado
session_name($session_name);
session_start();
if (!isset($_SESSION['id_userprofile'])) {
    header('Location: ../../index.php');
exit;
}

// Verificar si se ha enviado el ID del programa de formación

    $id_user = $_SESSION['id_userprofile'];
    date_default_timezone_set('America/Bogota');

    $database = new Database();
    $db = $database->conectar();

    $sql = "SELECT id_evento, titulo, descricao, inicio, termino, cor, fk_id_destinatario, fk_id_remetente, status, id_programaformacion
            FROM eventos as e
            LEFT JOIN convites as c ON e.id_evento = c.fk_id_evento
            WHERE fk_id_usuario = :id_user";

    $req = $db->prepare($sql);
    $req->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $req->execute();
    $events = $req->fetchAll(PDO::FETCH_ASSOC); // Asegura que solo se devuelvan índices asociativos

    // Depuración: Imprimir los eventos obtenidos
    

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php
        include_once('cabecera.php');
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Calendario </title>
    <!-- FullCalendar -->
    <link href='../../herramientas/css/fullcalendar.css' rel='stylesheet' />
    <link href='../../herramientas/css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    <!-- Custom CSS Calendario -->
    <link href='../../herramientas/css/calendar.css' rel='stylesheet' />
</head>

<body>
    <div class="">

        <script>
        function goBack() {
            window.history.back();
        }
        </script>

        <div class="cabecera_menu">
            <!-- INICIO CALENDARIO -->
            <div class="row">

                <div class="col-lg-12 text-center">
                    <p class="lead"></p>
                    <div id="calendar" class="col-centered">
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- Valida data dos Modals -->
            <script type="text/javascript">
            function validaForm(erro) {
                if (erro.inicio.value > erro.termino.value) {
                    alert('Data de Inicio deve ser menor ou igual a de termino.');
                    return false;
                } else if (erro.inicio.value == erro.termino.value) {
                    alert('Defina um horario de inicio e termino.(24h)');
                    return false;
                }
            }
            </script>


            <!-- Modal Adicionar Evento -->
            <?php include_once('../../calendario/modal/modalAdd.php'); ?>


            <!-- Modal Editar/Mostrar/Deletar Evento -->
            <?php include_once('../../calendario/modal/modalEdit.php'); ?>


            <!-- jQuery Version 1.11.1 -->
            <script src="../../herramientas/js/jquery.js"></script>

            <!-- Bootstrap Core JavaScript -->
            <script src="../../herramientas/js/bootstrap.min.js"></script>

            <!-- FullCalendar -->
            <script src='../../herramientas/js/moment.min.js'></script>
            <script src='../../herramientas/js/fullcalendar.min.js'></script>
            <script src='../../herramientas/js/pt-br.js'></script>
            <?php include_once('calendario.php'); ?>

        </div>
    </div>


</body>

<style>
#calendar-container {
    width: 100%;
    /* Para asegurar que el contenedor ocupe todo el espacio de la columna */
    height: 300px;
    /* Ajusta la altura para que sea más pequeño */
    overflow: hidden;
    /* Para que cualquier contenido que sobre salga se oculte */
}

#calendar {
    max-width: 300px;
    /* Ajusta el ancho máximo del calendario */
    height: 100%;
    /* Hace que el calendario ocupe toda la altura del contenedor */
    margin: 0 auto;
    /* Centra el calendario dentro del contenedor */
}


.fc-day-grid-event .fc-content {
    font-size: 0.8em;
    /* Reduce el tamaño del texto para que se ajuste al espacio pequeño */
}
</style>

</html>