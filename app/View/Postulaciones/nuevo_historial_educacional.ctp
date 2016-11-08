<style  type="text/css" media="screen">
	.controls{
		margin-left:80px !important;
	}
	.control-label{
		width:60px !important;
	}
        
        #institucion, #observacion{
            text-transform:uppercase;            
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
			<h3>Nuevo Historial Educacional</h3>
		</div>
		<div class="span1 offset2">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
	</div>
</div>
<form action="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'nuevoHistorialEducacional',$codigo_postulacion)); ?>" method="POST" class="form-horizontal">
<div class="modal-body">
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="institucion">Institución</label>
				<div class="controls">
			  		<input type="text" class="input-xlarge" id="institucion" required name="data[HistorialEducacional][institucion]" onkeyup="patron(this);">
			  	</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="ensenanza">Enseñanza</label>
				<div class="controls">
			  		<select id="ensenanza"  class="input-xlarge" required name="data[HistorialEducacional][ensenanza]">
			  			<option></option>
			  			<?php foreach($tipos_educacion as $tipo): ?>
			  				<option value="<?php echo $tipo['TipoEducacion']['codigo']?>"><?php echo $tipo['TipoEducacion']['nombre']; ?></option>
			  			<?php endforeach;?>
			  		</select>
			  	</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="observacion">Observación</label>
				<div class="controls">
			  		<textarea rows="3"  class="input-xlarge" type="textarea" id="observacion" required name="data[HistorialEducacional][observacion]" onkeyup="patron(this);"></textarea>
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
