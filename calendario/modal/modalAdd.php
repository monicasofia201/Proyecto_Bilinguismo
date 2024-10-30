<div class="modal fade" id="ModalAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cancelSolicitudLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="myModalLabel">Programar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="../../calendario/action/eventoAdd.php" onsubmit="return validaForm(this);">
                    <script>
                        $(document).ready(function(){  
                            $.post("../../include/select.php", {
                                action: 'crgrprogramaFormacion4' 
                            },
                            function(data) {
                                $("#id_programaformacion").html(data.lisTiposPF);
                                },
                                'json'
                            ).fail(function(xhr, status, error) {
                                console.error(error);
                            });
                        });
                        $(document).on("change", "#id_programaformacion", function() { 
                            $.post("../../include/select.php", {
                                action: 'CrgrFichas',
                                id_programaformacion: $("#id_programaformacion").val()
                            },
                            function(data) {
                                $("#ficha").html(data.listFicha);
                                },
                                'json'
                            ).fail(function(xhr, status, error) {
                                console.error(error);
                            });
                        });
                        $(document).ready(function(){  
                            $.post("../../include/select.php", {
                                action: 'CrgrCompetencia' 
                            },
                            function(data) {
                                $("#id_competencia").html(data.listCmpt);
                                },
                                'json'
                            ).fail(function(xhr, status, error) {
                                console.error(error);
                            });
                        });

                        $(document).on("change", "#id_competencia", function() { 
                            $.post("../../include/select.php", {
                                action: 'CrgrRA',
                                id_competencia: $("#id_competencia").val()
                            },
                            function(data) {
                                $("#id_resultado_aprendizaje").html(data.listRa);
                                },
                                'json'
                            ).fail(function(xhr, status, error) {
                                console.error(error);
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label for="titulo" class="col-sm-2 control-label">Titulo</label>
                        <div class="col-sm-10">
                            <input type="text" name="titulo" class="form-control" id="titulo" placeholder="Titulo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descricao" class="col-sm-2 control-label">Descripcion</label>
                        <div class="col-sm-10">
                            <textarea type="text" name="descricao" class="form-control" id="descricao" placeholder="Descripcion"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cor" class="col-sm-2 control-label">Color</label>
                        <div class="col-sm-10">
                            <select name="cor" class="form-control" id="cor">
                                <option value="">Seleccione</option>
                                <option style="color:#0071c5" value="#0071c5">&#9724; Azul Escuro</option>
                                <option style="color:#40E0D0" value="#40E0D0">&#9724; Turquesa</option>
                                <option style="color:#008000" value="#008000">&#9724; Verde</option>                          
                                <option style="color:#FFD700" value="#FFD700">&#9724; Amarillo</option>
                                <option style="color:#FF8C00" value="#FF8C00">&#9724; Naranja</option>
                                <option style="color:#FF0000" value="#FF0000">&#9724; Vermelo</option>
                                <option style="color:#000" value="#000">&#9724; rojo</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="convidado" class="col-sm-2 control-label">Asignar</label>
                        <div class="col-sm-10">
                            <select name="convidado" class="form-control" id="convidado">
                                <option value="">seleccione...</option>
                                <?php
                                    $sql2 = "SELECT nombre, id_userprofile, apellido, numeroiden FROM userprofile WHERE id_userprofile != $id_user AND id_rol = 2";
                                    $req = $db->prepare($sql2);
                                    $req->execute();
                                    $linhas = $req->rowCount();
                                    while ($dados = $req->fetch(PDO::FETCH_ASSOC)) {
                                        $id_usuario = $dados['id_userprofile'];
                                        $nome_usuario = $dados['nombre'] . " " . $dados['apellido'];
                                        echo "<option value=\"$id_usuario\">$nome_usuario</option>";
                                    }
                                ?>                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Programa de Formacion</label>
                        <div class="col-sm-10">
                            <select name="id_programaformacion" class="form-control" id="id_programaformacion" required>
                                <option value="">seleccione...</option>
                            </select>
                        </div>
                    </div>
                    <div id="ficha" class="form-group">
                    </div>
                    <div class="form-group">
                        <label for="id_competencia" class="col-sm-2 control-label">Competencia</label>
                        <div class="col-sm-10">
                            <select name="id_competencia" class="form-control" id="id_competencia">
                                <option value="">seleccione...</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_resultado_aprendizaje" class="col-sm-2 control-label">Resultado de Aprendizaje</label>
                        <div class="col-sm-10">
                            <select name="id_resultado_aprendizaje" class="form-control" id="id_resultado_aprendizaje">
                                <option value="">seleccione...</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inicio" class="col-sm-2 control-label">Fecha Inicio</label>
                        <div class="col-sm-10">
                            <input type="text" name="inicio" class="form-control" id="inicio" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="termino" class="col-sm-2 control-label">Fecha Termino</label>
                        <div class="col-sm-10">
                            <input type="text" name="termino" class="form-control" id="termino" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="close-button btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="create-button btn btn-primary">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
