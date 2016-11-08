<style  type="text/css" media="screen">
	.controls{
		margin-left:120px !important;
	}
	.control-label{
		width:100px !important;
	}
        #institucion, #nombre-curso, #observacion { text-transform: uppercase;}
</style>
<script>
function patron(campo){

	if (campo.value.trim().length>0){
		if (campo.value.substr(0,1).trim().length>0){
			campo.pattern="[a-zA-Z 0-9ñÑáéíóúÁÉÍÓÚüÜ]+";}
		else{
		campo.pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ]+";}
	}else{
		campo.pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ]+";
	}
}
</script>
<div class="modal-header">
	<div class="row-fluid">
		<div class="span9">
			<h3>Nueva Capacitación</h3>
		</div>
		<div class="span1 offset2">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
	</div>
</div>
<form action="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'nuevaCapacitacion',$codigo_postulacion)); ?>" method="POST" class="form-horizontal">
<div class="modal-body">
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="institucion">Institución</label>
				<div class="controls">
			  		<input type="text" class="input-xlarge" id="institucion" required name="data[Capacitacion][institucion]" onkeyup="patron(this);">
			  	</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="nombre-curso">Nombre del curso</label>
				<div class="controls">
			  		<input type="text" class="input-xlarge" id="nombre-curso" required name="data[Capacitacion][nombre_curso]" onkeyup="patron(this);">
			  	</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="observacion">Observación</label>
				<div class="controls">
			  		<textarea rows="3"  class="input-xlarge" type="textarea" id="observacion" required name="data[Capacitacion][observaciones]" onkeyup="patron(this);"></textarea>
			  	</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset8">
			<button type="submit" class="btn btn-warning"> <i class="icon icon-save"></i>&nbsp;Guardar</button>
		</div>
	</div>
</div>
</form>
