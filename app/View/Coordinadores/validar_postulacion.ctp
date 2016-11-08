<style>
.subtitle{
	color:#6E6E6E;
	border-bottom:1px solid #6e6e6e;	
	margin-bottom:30px;
}
</style>
<?php $usuario = $this->Session->read('UserLogued');?>
<div class="row-fluid">
	<div class="span11 offset1">
		<div class="row">
			<h2>Formulario de postulación:</h2>
		</div>
		<div class="clearfix"></div>
		<div class="row-fluid"><br><br></div>
		<?php echo $this->Form->create('Prepostulacion', array( 'type' => 'file')); ?>
		<div class="row">					
						<div class="span8">
		                    <label for="form-field-input-experiencia"class="control-label" >¿Posee la licencia de Educación Media?</label>
		                </div>
		                <div class="span3">
		                    <select id="form-field-input-experiencia"  required type="select" name="data[Prepostulacion][licencia]"  class="pull-right">
		                    	<option></option>
		                    	<option value="1" <?php echo (!empty($postulacion) && $postulacion['Prepostulacion']['licencia']==1)? 'selected':'';?> >SI</option>
		                    	<option value="0" <?php echo (!empty($postulacion) && $postulacion['Prepostulacion']['licencia']==0)? 'selected':'';?> >NO</option>
		                    </select>
		                </div>
		</div>
		<div class="row">
		                <div class="span8">
		                    <label for="form-field-input-experiencia" class="control-label">¿En qué ciudad vives actualmente?</label>
		                </div>		                
						<div class="span3">
		                   <select id="form-field-input-ciudad" name="data[Prepostulacion][ciudad_codigo]" class="pull-right" required>
		                   	<option></option>
		                   	<?php foreach($ciudades as $ciudad): ?>
		                   		<option value="<?php echo $ciudad['Ciudad']['codigo']; ?>"><?php echo $ciudad['Ciudad']['nombre'];?></option>
		                   	<?php endforeach; ?>
		                   </select>
		                </div>	
		</div>
		<div class="row">
		                <div class="span8">
		                    <label for="form-field-input-experiencia" class="control-label">Carrera a postular</label>
		                </div>		                
						<div class="span3">
		                   <select id="form-field-input-carrera"required  name="data[Prepostulacion][carrera_id]" class="pull-right">
		                   	<option></option>
		                   	<?php foreach($carreras as  $carrera): ?>
		                   		<option value="<?php echo $carrera['Carrera']['codigo']; ?>" <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['carrera_codigo']) && $postulacion['Postulacion']['carrera_codigo']==$carrera['Carrera']['codigo'])? 'selected':'';?>><?php echo ($carrera['Carrera']['nombre']); ?></option>
		                   	<?php endforeach; ?>
							 </select>
		                </div>	
		</div>
		<div class="row">
			<div class="span11">
				<h4>Documentación:</h4>
				<div class="alert alert-info">
					Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.
				
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span3 subtitle">
				<h4>Tipo Documento</h4>		
			</div>
			<div class="span5 subtitle">
				<h4>Archivo</h4>		
			</div>
			<div class="span3 subtitle">
				<h4>Estado</h4>		
			</div>
		</div>
		<div class="row margen">
			<div class="span3">
				Cédula de Identidad (por ambos lados)
			</div>
			<div class="span5" id="ci">
				<?php echo $this->Form->input('cedula_identidad',array('id' => 'form-field-input-carga-ci', 'name' => 'data[Archivos][ci]', 'type' => 'file', 'label' => false, 'required' => true)); ?>
			</div>
			<div class="span3">
				<div id="info-ci">
					<div id="img-error-ci" class="" align="left"><?php echo $this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '));?> No hay archivo</div>
					<div id="img-check-ci" class="hide" align="left"><?php echo $this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'));?> Archivo Permitido</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span3">
				Licencia de enseñanza media
			</div>
			<div class="span5" id="licencia">
				<?php echo $this->Form->input('licencia',array('id' => 'form-field-input-carga-licencia', 'name' => 'data[Archivos][licencia]', 'type' => 'file', 'label' => false, 'required' => true)); ?>
			</div>
			<div class="span3">
				<div id="info-licencia">
					<div id="img-error-licencia" class="" align="left"><?php echo $this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '));?> No hay archivo</div>
					<div id="img-check-licencia" class="hide" align="left"><?php echo $this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'));?> Archivo Permitido</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span11">
				<br>
				<h4>Documentación / Antecedentes:</h4>
				<div class="alert alert-info">
					Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.
				
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span4">				
				<div class="alert alert-warning">
					<strong>Académicos<br></strong>
					<ul>
						<li>Programa de estudios</li>
						<li>Corrección de notas</li>
					</ul>
				
				</div>
			</div>
			<div class="span3">				
				<div class="alert alert-warning">
					<strong>Laborales<br></strong>
					<ul>
						<li>Acreditación de antigüedad laboral</li>
						<li><br></li>
					</ul>
				
				</div>
			</div>
			<div class="span4">				
				<div class="alert alert-warning">
					<strong>Convenio Articulación<br></strong>
					<ul>
						<li>Título de técnico medio</li>
						<li>Certificado que acredite prácticas<li>
					</ul>				
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span3 subtitle">
				<h4>Nombre</h4>		
			</div>
			<div class="span3 subtitle">
				<h4>Archivo</h4>		
			</div>
			<div class="span3 subtitle">
				<h4>Tipo</h4>		
			</div>
			<div class="span2 subtitle">
				<h4>Estado</h4>		
			</div>
		</div>
		<div id="fila-archivo" class="row margen">
			<div class="span3">
				<?php echo $this->Form->input('anexo1', array('label' => false, 'required' => true, 'name' => 'data[Archivos][anexo][nombre]', 'class' => 'nombreAnexo', 'placeholder' => 'P.Ej Vida Laboral'));?>
			</div>
			<div class="span3" id="anexo">
				<?php echo $this->Form->input('anexo',array('type' => 'file', 'required' => true, 'name' => 'data[Archivos][anexo]', 'label' => false, 'class' => 'archivoAnexo')); ?>
			</div>
			<div class="span3 radio-button" id="anexo">
			<?php echo $this->Form->input('radio', array(
					'type' => 'radio',
					'before' => '<label class="control-label"></label>',
					'name' => 'data[Archivos][anexo][tipo]',
					'legend' => false,
					'class' => 'tipoArchivo',
					'required' => true,
					'value' => 'Académico',
					'beforeInput' => '<div class="input-prepend">',
					'afterInput' => '<div class="input-prepend">',
					'options' => array(
						'Academico' => 'Académico',
						'Laboral' => 'Laboral',
						'Convenio' => 'Convenio',
						
					)
				)); ?>
			
			</div>

			<div class="span2">
				<div id="info-licencia">
					<div id="img-error-licencia" class="" align="left"><?php echo $this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '));?> No hay archivo</div>
					<div id="img-check-licencia" class="hide" align="left"><?php echo $this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'));?> Archivo Permitido</div>
				</div>
			</div>
		</div>
		<div id="nuevosAnexos" class="clearfix">
			
		</div>
		<div class="row">
			<div class="span3 offset8">
				<a class="btn btn-danger pull-right" id="borrarAnexo" style="margin-left:5px; display:none"><i class="icon-trash-o"></i> Borrar último Anexo</a> 
				<a class="btn btn-warning pull-right" id="nuevoAnexo"><i class="icon-plus"></i> Nuevo Anexo</a>				
			</div>
		</div>
		<div class="row">
			<div class="span8">
				<input type="hidden" name="data[Prepostulacion][postulante_codigo]" value="<?php echo $usuario['Postulante']['codigo']; ?>" />
				<?php echo $this->Form->submit('Enviar', array('class' => 'btn btn-success'));?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>




<script type="text/javascript">
	$(function(){
		
		if (navigator.userAgent.indexOf("MSIE")>0 ) {
			$('#info-licencia').hide();
			$('#info-ci').hide();
			$('#info-renta').hide();
			$('#titulo-estado').hide();
		}else{
			
			$('#form-field-input-carga-licencia').on('change',licencia);
			$('#form-field-input-carga-ci').on('change',ci);
			$('#form-field-input-carga-renta').on('change',renta);
		}
	});
	
	function licencia(){

		var ext = true;
        var peso = true;
        var size = 0;
		var file = $("#form-field-input-carga-licencia")[0].files[0];
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        size = fileSize/1024;
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	$('#licencia').html('<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />');
        	$('#form-field-input-carga-licencia').on('change',licencia);
        	$('#img-check-licencia').hide();
        	$('#img-error-licencia').show();
        	return false;
        }
        if(size>1024){
        	alert('El archivo supera el peso establecido.');
        	$('#licencia').html('<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />');
        	$('#form-field-input-carga-licencia').on('change',licencia);
        	$('#img-check-licencia').hide();
        	$('#img-error-licencia').show();
        	return false;
        }
        if(peso && ext){
        	$('#img-check-licencia').show();
        	$('#img-error-licencia').hide();
        }
	}
	
	
	function ci(){
		console.log('Entrando');
		if (navigator.userAgent.indexOf("MSIE")>0){
	     	var file = $("#form-field-input-carga-ci").files; 	
	    }else{
	    	var file = $("#form-field-input-carga-ci")[0].files[0]; 	
	    }
		
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        var size = fileSize/1024;
        var ext = true;
        var peso = true;
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	$('#ci').html('<input id="form-field-input-carga-ci" required name="data[Archivos][ci]" type="file" />');
        	$('#form-field-input-carga-ci').on('change',ci);
        	$('#img-check-ci').hide();
        	$('#img-error-ci').show();
        	return false;
        }
        if(size>1024){
        	alert('El archivo supera el peso establecido.');
        	$('#ci').html('<input id="form-field-input-carga-ci" required name="data[Archivos][ci]" type="file" />');
        	$('#form-field-input-carga-ci').on('change',ci);
        	$('#img-check-ci').hide();
        	$('#img-error-ci').show();
        	return false;
        }
        if(peso && ext){
        	$('#img-check-ci').show();
        	$('#img-error-ci').hide();
        }
	}
	function renta(){
		if (navigator.userAgent.indexOf("MSIE")>0){
	     	var file = $("#form-field-input-carga-renta").files;
	    }else{
	    	var file = $("#form-field-input-carga-renta")[0].files[0]; 	
	    }
		
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        var size = fileSize/1024;
        var ext = true;
        var peso = true;
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	$('#renta').html('<input id="form-field-input-carga-renta" required name="data[Archivos][renta]" type="file" />');
        	$('#form-field-input-carga-renta').on('change',renta);
        	$('#img-check-renta').hide();
        	$('#img-error-renta').show();
        	return false;
        }
        if(size>30000){
        	alert('El archivo supera el peso establecido.');
        	$('#renta').html('<input id="form-field-input-carga-renta" required name="data[Archivos][renta]" type="file" />');
        	$('#form-field-input-carga-renta').on('change',renta);
        	$('#img-check-renta').hide();
        	$('#img-error-renta').show();
        	return false;
        }
        if(peso && ext){
        	$('#img-check-renta').show();
        	$('#img-error-renta').hide();
        }
	}
	function isImage(extension){
	    switch(extension.toLowerCase()){
	        case 'jpg': case 'gif': case 'png': case 'jpeg': case 'pdf': case 'doc': case 'docx':
	            return true;
	        break;
	        default:
	            return false;
	        break;
	    }
	}
</script>
<script>
	var anexos = 1;
	
	$('#nuevoAnexo').click(function() {
		anexos++;
		if (anexos > 1) { $('#borrarAnexo').show(); }
        var clone = $("#fila-archivo").clone(true);
        clone.find('.nombreAnexo').attr('id', 'PrepostulacionAnexo'+anexos);
        clone.find('.nombreAnexo').attr('name', 'data[Archivos][anexo'+anexos+'][nombre]');
        clone.find('.archivoAnexo').attr('name', 'data[Archivos][anexo'+anexos+']');
        clone.find('#PrepostulacionRadioLaboral').attr('id', 'PrepostulacionRadioLaboral'+anexos+'');
        clone.find('#PrepostulacionRadioAcademico').attr('id', 'PrepostulacionRadioAcademico'+anexos+'');
        clone.find('#PrepostulacionRadioConvenio').attr('id', 'PrepostulacionRadioConvenio'+anexos+'');
        clone.find("label[for='PrepostulacionRadioAcademico']").attr('for', 'PrepostulacionRadioAcademico'+anexos+'');
        clone.find("label[for='PrepostulacionRadioConvenio']").attr('for', 'PrepostulacionRadioConvenio'+anexos+'');
        clone.find("label[for='PrepostulacionRadioLaboral']").attr('for', 'PrepostulacionRadioLaboral'+anexos+'');
        clone.find(".tipoArchivo").attr('name', 'data[Archivos][anexo'+anexos+'][tipo]');
        clone.show();
        clone.attr('id', 'fila-archivo'+anexos+'');
        clone.attr('id', 'fila-archivo'+anexos+'');
        clone.appendTo("#nuevosAnexos");		
	});
	
	$('#borrarAnexo').click(function() {
		
		$( "#fila-archivo"+anexos+'').remove();	
		anexos--;
		if (anexos == 1) { $('#borrarAnexo').hide(); }		
		
	});
	
</script>