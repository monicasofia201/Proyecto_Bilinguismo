<?php
require_once('conexao.php');

header('Content-Type: text/html; charset=' . $charset);
session_name($session_name);
session_start();

$database = new Database();
$db = $database->conectar();

if (isset($_POST['titulo']) && isset($_POST['descricao']) && isset($_POST['inicio']) && isset($_POST['termino']) && isset($_POST['cor']) && isset($_POST['id_competencia']) && isset($_POST['id_resultado_aprendizaje']) && isset($_POST['convidado'])) {

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $inicio = $_POST['inicio'];
    $termino = $_POST['termino'];
    $cor = $_POST['cor'];
    $convidado = $_POST['convidado'];
    $competencia = $_POST['id_competencia'];
    $ra = $_POST['id_resultado_aprendizaje'];
    $id_programaformacion = $_POST['id_programaformacion'];
    $ficha = $_POST['ficha'];

    if (isset($_SESSION['id_userprofile'])) {
        $id_usuario = $_SESSION['id_userprofile'];

        $inicio = date('Y-m-d H:i:s', strtotime($inicio));
        $termino = date('Y-m-d H:i:s', strtotime($termino));

        $sql = "INSERT INTO eventos(fk_id_usuario, titulo, descricao, inicio, termino, cor, id_competencia, id_resultado_aprendizaje, id_programaformacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $query = $db->prepare($sql);
            if ($query === false) {
                print_r($db->errorInfo());
                die('Error al preparar la consulta.');
            }

            $sth = $query->execute([$id_usuario, $titulo, $descricao, $inicio, $termino, $cor, $competencia, $ra, $id_programaformacion]);
            if ($sth === false) {
                print_r($query->errorInfo());
                die('Error al ejecutar la consulta.');
            }

        $ultimoEvento = "SELECT * FROM eventos ORDER BY id_evento DESC LIMIT 1";
        $req = $db->prepare($ultimoEvento);
        $req->execute();
        $linhas = $req->rowCount();
        if ($linhas == 1) {
            $dados = $req->fetch(PDO::FETCH_ASSOC);
            $id_evento = $dados['id_evento'];

            $sql2 = "INSERT INTO convites(fk_id_destinatario, fk_id_remetente, fk_id_evento, status) VALUES (?, ?, ?, null)";
            $query2 = $db->prepare($sql2);
            if ($query2 === false) {
                print_r($db->errorInfo());
                die('Error al preparar la consulta para convites.');
            }
            $query2->execute([$convidado, $id_usuario, $id_evento]);
            if ($query2 === false) {
                print_r($query2->errorInfo());
                die('Error al ejecutar la consulta para convites.');
            }
            $sql3 = "UPDATE programaformacion SET ficha = ?, fk_programado = ? , id_competencia = ?, id_resultado_aprendizaje = ? WHERE id_programaformacion = '$id_programaformacion'";
            $query3 = $db->prepare($sql3);
            if ($query3 === false) {
                print_r($db->errorInfo());
                die('Error al preparar la consulta para programaformacion.');
            }
            $query3->execute([$ficha, $convidado,$competencia, $ra]);
            if ($query3 === false) {
                print_r($query3->errorInfo());
                die('Error al ejecutar la consulta para programaformacion.');
            }
        }
    } else {
        die('Error: Variable de sesión no está definida.');
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
