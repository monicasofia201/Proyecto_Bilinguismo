<?php
include 'parametros_index.php';

?>
<head>
</head>
<div class="card-body">
	<div class="row">
		<div class="col-sm-2" >
			<input type='text' name='dato_txt' id='dato_txt' title='Dato a buscar' placeholder='Dato a buscar' class="form-control mb-2 mr-sm-2 mb-sm-0" >
		</div>
		<div class="col-sm-2" >
			<button type="button" name='btn_Buscar' id='btn_Buscar' <?php echo $var_class_button_warnigB; ?>  >
			<i class="fa fa-search-plus" aria-hidden="true"></i></button>
		</div>
		<div class="col-sm-2" >
			<button type="button" name='noticiaModal' id='noticiaModal' data-bs-toggle="modal" data-bs-target="#staticBackdrop" <?php echo $var_class_button_warnigN; ?>  >
			<i class="fa fa-plus" aria-hidden="true"></i></button>
		</div>		
	</div>
</div>