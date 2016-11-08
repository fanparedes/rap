<style>
.subtitle{
	color:#6E6E6E;
	border-bottom:1px solid #6e6e6e;	
	margin-bottom:30px;
}
</style>
<!-- MODAL SUBIR ACTA DE TRAYECTORIA FORMATIVA -->
<div id="modalEnviar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align:center;" id="titulo_modal">¿Está seguro que desea enviar esta postulación?</h3>
	</div>
        <div class="modal-body">
                <?php echo $this->Form->create('Postulacione', array( 'action' => 'subirFirma', 'type' => 'file')); ?>
                <div class="row-fluid">				
                    <p id="descrip_modal">Al enviar esta postulación no podrá modificar la documentación adjunta ¿Está seguro?</p>
                </div>  		
        </div>
        <div class="modal-footer">
                <?php echo $this->Form->button('<i class="icon-envelope"></i> Enviar', array('type'=> 'submit','class'=>'btn btn-success letra','id' =>'enviarPrepostulacion'), array('escape' => false));?>                
                <a data-dismiss="modal" aria-hidden="true" class="btn cerrar" id="cierra_modal" href="#">Cerrar</a>			
        </div>	
</div>
<!-- MODAL -->
<?php $usuario = $this->Session->read('UserLogued');?>
<div class="row">
	<div class="span10 offset1">
		<div class="row">
			<h3>Estado de admisión:</h3>
		</div>
		<div class="clearfix"></div>
		<div class="row resumen">
			<div class="span2">
				Código:<br><br>
				Carrera a la que postula:<br><br>
				Estado: <br>
			</div>
			<div class="span5">
				<b><?php if (isset($postulacion['Postulacion']['id_correlativo'])): echo $postulacion['Postulacion']['id_correlativo'].'<br><br>'; else: echo '<br><br>'; endif; ?></b>
				<b><?php echo $postulacion['Carrera']['nombre']; ?></b><br><br>
				<?php if (($postulacion['Postulacion']['habilitado'] == '1') && ($postulacion['Postulacion']['firma'] == '1')) {
					echo '<span class="verde">HABILITADO CON FIRMA</span>';					
				}
				elseif (($postulacion['Postulacion']['habilitado'] == '1') && ($postulacion['Postulacion']['firma'] == null)) {
					echo '<span class="verde">HABILITADO</span>';		
				}
				elseif ($postulacion['Postulacion']['habilitado'] == '0') {
					echo '<span class="rojo">NO HABILITADO</span>';
				}
				else {
					echo '<b>Tu documentación será revisada por un administrativo de Duoc UC</b>';
				}
				?>
			</div>		
	
		</div>
		<div class="clearfix"><br></div>
		<div class="row resumen">
			<h4>Estimado Postulante:</h4>
			<?php if (($postulacion['Postulacion']['habilitado'] == '1') && (!empty($archivo_firma))): ?>
					<p>Su postulación ha sido recibida. Pronto recibirá una notificación vía mail del correocsa@duoc.cl con la información de la carrera, jornada y sede a la cual usted postuló, recibiendo entre una a tres notificaciones según el número de sedes ingresadas por usted. Debe tener presente que los tiempos de respuesta de estas sedes pueden ser diferentes y son informados en cada notificación.</p>						
					<br>
					<?php echo $this->html->link('<i class="icon-download">  </i>Descargar Acta de Trayectoria Formativa', 	                		
											array('controller'=>'cargas', 'action' => 'descargarDocumento', $archivo_firma['Cargas']['codigo']),
											array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Acta de Trayectoria Formativa', 'data-placement' => "right", 'escape' => false)
							);	
					?>
					<br>
			<?php endif; ?>	
			<br>
			<?php if (($postulacion['Postulacion']['habilitado'] == '1') && ((empty($archivo_firma)))): ?>
					<p>Ud. ha sido habilitado. Para revisar el detalle de las asignaturas aprobadas, descargue el archivo adjunto, complételo, fírmelo,adjúntelo a través del botón “Seleccionar archivo” y presione “Enviar”.</p>
					<p><?php if (isset($postulacion['Postulacion']['motivos'])) : echo 'Motivo: '.$postulacion['Postulacion']['motivos']; endif; ?></p>
					<?php 
					if ((!empty($archivo)) && ((empty($archivo_firma)))): ?>
						<?php foreach ($archivo as $fichero) : ?>
						<label> 
								<?php echo $this->html->link('<i class="icon-download">  </i>Descargar '.$fichero['Cargas']['nombre_fisico_archivo'].'', 	                		
											array('controller'=>'cargas', 'action' => 'descargarDocumento', $fichero['Cargas']['codigo']),
											array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar '.$fichero['Cargas']['nombre_fisico_archivo'].'', 'data-placement' => "right", 'escape' => false)
							)?>
						</label>
						<?php endforeach;?>
						<br><br>					
					<?php endif; ?>

					<?php 
						if (empty($archivo_firma)){
							echo '<p>Adjuntar acta de trayectoria formativa:</p>';												
							echo $this->Form->input('archivo',array( 'type' => 'file', 'required' => true, 'label' => false));
							echo $this->Form->input('postulacion', array('type'=>'hidden', 'value' => $postulacion['Postulacion']['codigo']));
							echo $this->Form->input('postulante', array('type'=>'hidden', 'value' => $postulacion['Postulante']['codigo']));
							echo '<a href="#" class="btn btn-success" id="boton-enviar">Enviar</a>';					
							echo $this->Form->end(); 
						}
						else{
							echo $this->html->link('<i class="icon-download">  </i>Descargar Acta de Trayectoria Formativa', 	                		
											array('controller'=>'cargas', 'action' => 'descargarDocumento', $archivo_firma['Cargas']['codigo']),
											array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Acta de Trayectoria Formativa', 'data-placement' => "right", 'escape' => false)
							);						
						}
				?>								
			<?php endif; ?>
			<?php if ($postulacion['Postulacion']['habilitado'] == '0'): ?>
				<p>Tu documentación ha sido revisada, sin embargo no cumples los requisitos para ingresar por una de las vías de admisión.</p>
				<p><?php if (isset($postulacion['Postulacion']['motivos'])) : echo 'Motivo: '.$postulacion['Postulacion']['motivos']; endif; ?></p>
			<?php endif; ?>	
			<?php if ($postulacion['Postulacion']['habilitado'] == null): ?>
				<p>Tu documentación está siendo revisada por un administrativo de Duoc UC.</p>
				<p>Por favor, ingresa periódicamente a la plataforma para revisar tu estado.</p>
			<?php endif; ?>
			<p><br></p><p></p>			
			<p>Atentamente:</p>
			<p>Administrativo de Duoc UC</p>
			<?php echo $this->Html->Image('logo-duoc.png') ?>
			<br>
		</div>
		<br><br><br>
		</div>
	</div>
</div>
<script>
$( "#boton-enviar" ).click(function() {
	if ($("#PostulacioneArchivo").val() !== '') {
		$('#modalEnviar').modal('show'); 
	}
	else{
		alert('Adjunte el archivo de Trayectoria Formativa');
	}
});

$( "#PostulacioneArchivo" ).change(function() {
  if (archivo() == false){
	$( "#PostulacioneArchivo" ).val('');
  }
});

function archivo(){
		if (navigator.userAgent.indexOf("MSIE")>0){
	     	var file = $("#PostulacioneArchivo").files;
	    }else{
	    	var file = $("#PostulacioneArchivo")[0].files[0]; 	
	    }		
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        var size = fileSize/1024;
        var ext = true;
        var peso = true;
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	return false;
        }
        if(size>5120){
			alert('El archivo supera el peso establecido.');
        	return false;
        }
	}
	
	function isImage(extension){

	    switch(extension.toLowerCase()){
	        case 'jpg': case 'jpeg': case 'pdf': case 'doc': case 'docx':  case 'png': case 'rar':  case 'zip':
	            return true;
	        break;
	        default:
	            return false;
	        break;
	    }

	}
</script>