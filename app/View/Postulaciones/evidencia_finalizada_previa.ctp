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
    .modal{
		height: 330px;
	}   
        
</style>

<div class="modal-header">
	<div class="row-fluid">
		<div class="span9">
			<h3>Evidencia Preliminar Finalizada</h3>
		</div>
		<div class="span1 offset2" id="elimina_popupevidencia">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
	</div>
</div>
<form action="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'edit_evidencia',$datos['id'])); ?>" method="POST" class="form-horizontal" id="FormFormularioAdjuntarDocumentos" enctype="multipart/form-data">
<div class="modal-body">
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="institucion">Nombre</label>
				<div class="controls">
					<input type="hidden" class="input-xlarge" id="id" required name="data[Evidencia][id]" value="<?php echo $datos['id'] ?>" ">
					<input type="hidden" class="input-xlarge" id="institucion" required name="data[Evidencia][postulacion_codigo]" value="<?php echo $datos['postulacion_codigo'] ?>" ">
			  		<input type="text" class="input-xlarge" id="institucion" disabled="disabled" required name="data[Evidencia][nombre]" maxlength="50" onkeyup="patron(this);" value="<?php echo $datos['nombre'] ;?>">
			  	</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="institucion">Justificación</label>
				<div class="controls">
					<textarea rows="3"  class="input-xlarge" type="textarea" id="relacion"  disabled="disabled" name="data[Evidencia][relacion]" value="<?php echo $datos['relacion'] ;?>"style="height: 156px;" ></textarea>
			  	</div>
			</div>
		</div>
	</div>
</div>
</form>
<script>
$(function(){
		$('#relacion').html('<?php echo $datos['relacion'] ;?>');
});
    </script>
