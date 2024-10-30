<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="calendario/action/eventoEdit.php" onsubmit="return validaForm(this);">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Evento</h4>
				
			  </div>
			  <div class="modal-body">
				
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
						  <option value="">Escolher</option>
						  <option style="color:#0071c5" value="#0071c5">&#9724; Azul Escuro</option>
						  <option style="color:#40E0D0" value="#40E0D0">&#9724; Turquesa</option>
						  <option style="color:#008000" value="#008000">&#9724; Verde</option>						  
						  <option style="color:#FFD700" value="#FFD700">&#9724; Amarillo</option>
						  <option style="color:#FF8C00" value="#FF8C00">&#9724; Naranja</option>
						  <option style="color:#FF0000" value="#FF0000">&#9724; Verde</option>
						  <option style="color:#000" value="#000">&#9724; Preto</option>
						  
						</select>
					</div>
				  </div>

				  <div class="form-group">
					<label for="convidado" class="col-sm-2 control-label">Asignado</label>
					<div class="col-sm-10">
					  <select name="convidado" class="form-control" id="convidado" disabled>
					  <option value="">Ninguno</option>
					  <?php
							if (isset($_POST['Event'])) {
								$event = $_POST['Event'];
								$id = $event['id'];
							}
							$sql6 = "SELECT u.nombre, u.id_userprofile FROM convites c
							JOIN 
								userprofile u ON c.fk_id_destinatario = u.id_userprofile
							WHERE c.fk_id_evento = '$id'";
							$req = $db->prepare($sql6);
							$req->execute();
							$linhas = $req->rowCount();
							
								while ($dados = $req->fetch(PDO::FETCH_ASSOC)) {
									$id_usuario = $dados['id_userprofile'];
									$nome_usuario = $dados['nombre'];
									echo " <option value=\"$id_usuario\">$nome_usuario</option>";
								}
							
						?>				  
						</select>
					</div>
				  </div>

				  <div class="form-group">
					<label for="remetente" class="col-sm-2 control-label">Remitente</label>
					<div class="col-sm-10">
					  <select name="remetente" class="form-control" id="remetente" disabled>
					  <option value="">Ninguno</option>
					  <?php
							if (isset($_POST['Event'])) {
								$event = $_POST['Event'];
								$id = $event['id'];
							}
							$sql5 = "SELECT u.nombre, u.id_userprofile FROM convites c
							JOIN 
								userprofile u ON c.fk_id_destinatario = u.id_userprofile
							WHERE c.fk_id_evento = '$id'";
							$req = $db->prepare($sql5);
							$req->execute();
							$linhas = $req->rowCount();
						
								while ($dados = $req->fetch(PDO::FETCH_ASSOC)) {
									$id_usuario = $dados['id_userprofile'];
									$nome_usuario = $dados['nombre'];
									echo " <option value=\"$id_usuario\">$nome_usuario</option>";
								}
							
						?>				  
						</select>
					</div>
				  </div>

				  <div class="form-group">
					<label for="status" class="col-sm-2 control-label">Estado</label>
					<div class="col-sm-10">
						<select name="status" class="form-control" id="status" disabled>
						<option value="">Pendiente</option>
						<option value="1">Aprovado</option>
						<option value="0">Rechazado</option>		  
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
                    
                    <!-- Deletar Evento -->
				    <div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10">
						  <div class="checkbox">
							<label class="text-danger"><input type="checkbox"  name="delete"> Eliminar Programacion</label>
						  </div>
						</div>
					</div>
				  
				  <input type="hidden" name="id_evento" class="form-control" id="id_evento">
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary">Aceptar</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>