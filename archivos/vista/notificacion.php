<?php
    if(!isset($_SESSION)){
        session_start();
    }

    $id_user = $_SESSION['id_userprofile'];

    require_once('../../calendario/action/conexao.php');

    date_default_timezone_set('America/Sao_Paulo');
    $database = new Database();
    $db = $database->conectar();

    $numNotificacao=0;

    $sql = "SELECT * FROM convites as c
    LEFT JOIN userprofile as u ON c.fk_id_remetente = u.id_userprofile
    LEFT JOIN eventos as e ON c.fk_id_evento = e.id_evento
    WHERE fk_id_destinatario=$id_user AND status IS null";
    $req = $db->prepare($sql);
    $req->execute();
    $numNotificacao = $req->rowCount();
?>
<head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

</head>
<style>
    .create-button {
    background-color: #4CAF50;
    color: white;
}

    .close-button {
        background-color: #f44336;
        color: white;
    }
</style>
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-bell "></i><span class="badge badge-danger"><?php echo "$numNotificacao"; ?> </span> <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu" style="width:280px">
        <?php
            while ($dados = $req->fetch(PDO::FETCH_ASSOC)) {
                $id_convite = $dados['id_convite'];
                $nome_usuario = $dados['nombre'];
                $id_usuario2 = $dados['id_userprofile'];
                $nome_evento = $dados['titulo'];
                $descricao_evento = $dados['descricao'];
                $data_inicio = $dados['inicio'];
                $data_termino = $dados['termino'];
                $cor_evento = $dados['cor'];

                $data_inicio = date('d/m/Y H:i:s', strtotime($data_inicio));
                echo "
                <div class=\"alert alert-info\" role=\"alert\">
                <form class=\"form-horizontal\" method=\"POST\" action=\"aceita.php\">
                
                        <i class=\"fa fa-bell fa-fw\"></i> 
                        $nome_usuario, Te asigno esta tarea $nome_evento inicia el dia: $data_inicio, Aceptas?
                        <br>
                        <input type=\"hidden\" name=\"id_convite\" class=\"form-control\" id=\"id_convite\" value=\"$id_convite\">
                        <input type=\"hidden\" name=\"id_usuario2\" class=\"form-control\" id=\"id_usuario2\" value=\"$id_usuario2\">
                        <input type=\"hidden\" name=\"titulo\" class=\"form-control\" id=\"titulo\" value=\"$nome_evento\">
                        <input type=\"hidden\" name=\"descricao\" class=\"form-control\" id=\"descricao\" value=\"$descricao_evento\">
                        <input type=\"hidden\" name=\"inicio\" class=\"form-control\" id=\"inicio\" value=\"$data_inicio\">
                        <input type=\"hidden\" name=\"termino\" class=\"form-control\" id=\"termino\" value=\"$data_termino\">
                        <input type=\"hidden\" name=\"cor\" class=\"form-control\" id=\"cor\" value=\"$cor_evento\">
                        <button type=\"submit\" class=\"create-button\">Aceptar</button>
                        </form>
                        <form class=\"form-vertical\" method=\"POST\" action=\"recusa.php\">
                        <div class=\"buttonAlign\">
                        <input type=\"hidden\" name=\"id_convite\" class=\"form-control\" id=\"id_convite\" value=\"$id_convite\">
                        <button type=\"submit\" class=\"Close-button\">Cancelar</button>
                        </div>
                        </form>
                </div>";
            }
        ?>
    </ul>
</li>