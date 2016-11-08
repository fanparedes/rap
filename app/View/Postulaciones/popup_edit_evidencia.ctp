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
$(window).load(
		$('#relacion').html('<?php echo htmlspecialchars(strip_tags($datos['relacion'])) ;?>')
		);

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
			<h3>Editar Evidencia Previa</h3>
		</div>
		<div class="span1 offset2" id="elimina_popupevidencia">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
	</div>
</div>
<form action="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'edit_evidencia',$datos['id'])); ?>" method="POST" class="form-horizontal" id="FormFormularioAdjuntarDocumentos" enctype="multipart/form-data">
<div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1">
			<div class="panel-group" id="accordion">
				  <div class="panel panel-default" style="margin-left:15px;">
					<div class="panel-heading" style="text-align:left; float:left; margin-right:15px;">
					  <p class="panel-title" >
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						 <i class="icon-question-circle"></i> Ayuda
						</a>
					  </p>
					</div>
					<div id="collapseOne" class="panel-collapse collapse">
					 <div class="panel-body" style="padding-right:15px; text-align: justify;">							
							<p>Paso 1: Selección de las evidencias

De acuerdo a la información que completaste en tu CV y autoevaluación, reflexiona acerca de lo que has aprendido en el mundo laboral, capacitaciones o desde el autoaprendizaje y selecciona aquellas evidencias que consideres más representativas y pertinentes en relación a las competencias que deseas validar, puedes referirte a una o varias, lo cual implica que puede repetirse la misma evidencia para mostrar tu dominio en las distintas unidades de competencia.

Cada evidencia debe documentarse.</p>
							<p>Paso 2: Justificación de las evidencias

A continuación, debes explicar cómo se relaciona la evidencia con la unidad de competencia, mencionando en qué consiste la evidencia y qué tareas desempeñaste a partir de esta evidencia.</p>
					  </div>
					</div>
				  </div>
			</div>
		</div>
	</div>	
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="institucion">Nombre</label>
				<div class="controls">
					<input type="hidden" class="input-xlarge" id="id" required name="data[Evidencia][id]" value="<?php echo $datos['id'] ?>" ">
					<input type="hidden" class="input-xlarge" id="institucion" required name="data[Evidencia][postulacion_codigo]" value="<?php echo $datos['postulacion_codigo'] ?>" ">
			  		<input type="text" class="input-xlarge" id="institucion" required name="data[Evidencia][nombre]" maxlength="50" onkeyup="patron(this);" value="<?php echo ($datos['nombre']) ;?>">
			  	</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<label class="control-label" for="institucion">Justificación</label>
				<div class="controls">
		
					<textarea rows="3"  class="input-xlarge" type="textarea" id="relacion" required name="data[Evidencia][relacion]" value="<?php echo $datos['relacion'] ;?>" onkeyup="patron(this);"></textarea>
			  	</div>
			</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="control-group">
				<?php if(isset($datos['imagen']) && ! empty($datos['imagen'])) :?>
				<div id="archivo_actual">
					<label class="control-label" for="institucion">Archivo Actual</label>
					<div class="controls">
						<span  class="span10">
							<?php echo $datos['imagen'] ;?>
						</span>
							<span  class="span12" style="width: 30px;float:right;">
								<input type="hidden" class="input-xlarge" id="codigo" required name="data[Evidencia][codigo]" value="<?php echo $datos['codigo_imagen'] ?>" ">
							<a href="#" data-id="<?php echo $datos['id_evidencia_archivo']?>" class="borraImagen"><i class="icon-times"></i></a>
						</span>	
					</div>
				</div>
				<div id="archivo">
					<label class="control-label" for="institucion">Archivo</label>
					<div class="controls">
						<input type="file" class="input-xlarge requerido" id="form-field-input-carga-evidencia" required name="data[Evidencia][archivo]" onkeyup="patron(this);">
					</div>	
				</div>
				<script type="text/javascript">
				// <![CDATA[
				$('div#archivo').hide();
				$('input.requerido').removeAttr('required');
				// ]]>
				</script>
				<?php else :?>
				<div id="archivo">
					<label class="control-label" for="institucion">Archivo</label>
					<div class="controls">
						<input type="file" class="input-xlarge" id="form-field-input-carga-evidencia" required name="data[Evidencia][archivo]" onkeyup="patron(this);">
					</div>	
				</div>
				<script type="text/javascript">
				// <![CDATA[
				$('div#archivo').show();
				// ]]>
				</script>
				<?php endif ;?>
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
<script type="text/javascript">
	$(function(){
		$('#archivo').removeAttr('required');
		$('a.borraImagen').click(function(){
			var id_imagen = $(this).data('id');
			$.ajax(
			{
					url : webroot +'postulaciones/ajax_elimina_imagen/'+id_imagen,
					async :false,
					success: function(respuesta)
					{
						if(respuesta == 'OK')
						{
							alert('Archivo eliminado');
						}
					}
			});
			$('div#archivo_actual').fadeOut();
			$('div#archivo').fadeIn();
			$('#elimina_popupevidencia').fadeOut();
			$('input.requerido').attr('required',true);
		});
		
		if (navigator.userAgent.indexOf("MSIE")>0 ) {
			$('#info-evidencia').hide();
		
		}else{
			$('#form-field-input-carga-evidencia').on('change',licencia);
			
		}
	});
	
	function licencia(){
		var ext = true;
        var peso = true;
        var size = 0;
		var file = $("#form-field-input-carga-evidencia")[0].files[0];
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        size = fileSize/1024;
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	$(this).val('');
        	$('#form-field-input-carga-evidencia').on('change',licencia);
        	return false;
        }
        if(size>50000){
        	alert('El archivo supera el peso establecido.');
        	$(this).val('');
        	$('#form-field-input-carga-evidencia').on('change',licencia);
        	return false;
        }
        if(peso && ext){

        }
	}	
	
	
	function isImage(extension){
	    switch(extension.toLowerCase()){
	         case 'jpg': case 'gif': case 'png': case 'jpeg':case 'rar': case 'zip': case 'pdf': case 'doc': case 'docx': case 'xls': case 'xlsx': case 'odt': case 'ods':
	            return true;
	        break;
	        default:
	            return false;
	        break;
	    }
	}
</script>
