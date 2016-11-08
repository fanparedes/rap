<style  type="text/css" media="screen">
	.controls{
		margin-left:120px !important;
	}
	.control-label{
		width:100px !important;
	}
        #lugar_trabajo, #cargo, #actividades {
            text-transform: uppercase;
        }
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
			<h2>Nuevo Historial Laboral</h2>
		</div>
		<div class="span1 offset2">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
	</div>
</div>
<form action="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'nuevoHistorialLaboral',$codigo_postulacion)); ?>" method="POST" class="form-horizontal">
<div class="modal-body">
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="lugar_trabajo">Lugar de Trabajo</label>
				<div class="controls">
			  		<input type="text"  class="input-xlarge" id="lugar_trabajo" required name="data[LaboralPostulacion][lugar_trabajo]">
			  </div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="periodo_tiempo">Periodo de tiempo</label>
				<div class="controls">
			  		<select id="periodo_tiempo" class="input-xlarge" required name="data[LaboralPostulacion][periodo]">
			  			<option></option>
		  				<option value="1">1 año</option>
		  				<option value="2">2 años</option>
		  				<option value="3">3 años</option>
		  				<option value="4">4 años</option>
		  				<option value="5">5 o más años</option>
			  		</select>
			  	</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="cargo">Cargo</label>
				<div class="controls">
			  		<input type="text" id="cargo" class="input-xlarge" required name="data[LaboralPostulacion][cargo]" onkeyup="patron(this);">
			  </div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="actividades">Actividades</label>
				<div class="controls">
			  		<textarea rows="3" class="input-xlarge" type="textarea" id="actividades" required name="data[LaboralPostulacion][actividades]"></textarea>
			  	</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset8">
			<button type="submit" class="btn btn-warning"><i class="icon icon-plus-circle"></i>&nbsp;Guardar</button>
		</div>
	</div>
</div>
</form>