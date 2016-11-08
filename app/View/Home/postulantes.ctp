<!-- MODAL BORRAR -->
<div id="modalborrarPostulante" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<div class="row-fluid">
			<h3 style="text-align: center;">¿Está seguro que desea ELIMINAR esta Postulación?</h3>						
		</div>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'prepostulaciones', 'action' => 'borrarPostulacion'))?>">	
	  	<?php echo $this->Form->input('postulacion', array ('type' => 'hidden', 'value' => '')); ?>	
	  	<?php echo $this->Form->input('postulante', array ('type' => 'hidden', 'value' => ''.$postulante['Postulante']['codigo'].'')); ?>	
		<div class="modal-body">	
			<p><strong><i class="icon-warning"></i> Si acepta se borrarán TODOS los datos asociados a esta postulación.</strong></p>  		
		</div>
		<div class="modal-footer">			
			<button type="submit" class="btn btn-danger" id="btn-rechazo" >BORRAR</button>
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		</div>
	</form>
</div>
<!-- FIN DE MODAL -->
<div class="row-fluid">
	  <div class="span10 offset1">
			<h1>Mis Postulaciones <div class="pull-right"><a class="btn btn-success" href="<?php echo $this->Html->url(array('controller'=>'prepostulaciones','action'=>'nuevaPrepostulacion')); ?>"><i class="icon icon-plus"></i> Nueva Postulación</a></div></h1>				
			<br><br>
	  </div>
</div>
<div class="row-fluid">
	<div class="span12">
		<?php if(empty($prepostulaciones)): ?>
			<div class="row-fluid">
				<div class="span10 offset1 nueva-postulacion">
					<div class="row-fluid">
						<div class="span3 offset5">
							<p>No tienes postulaciones</p>							
						</div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="row-fluid">
				<div class="span10 offset1 nueva-postulacion">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Fecha Creación</th>
								<th>Carrera</th>								
								<th>Fecha Modificación</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($prepostulaciones as $k => $prepostulacion): ?>
							<?php if (isset($prepostulacion['Prepostulacion'])) : $variable = 'Prepostulacion'; else: $variable = 'Postulacion'; endif; ?>
							<tr>
								<td><?php if (isset($prepostulacion['Prepostulacion']['id_correlativo'])){ echo $prepostulacion['Prepostulacion']['id_correlativo'];} elseif ((isset($prepostulacion['Postulacion']['id_correlativo']))){ echo $prepostulacion['Postulacion']['id_correlativo'];} ?></td>
								<td><?php echo $this->Ingenia->formatearFecha($prepostulacion[$variable]['created']);?></td>
								<td><?php echo $prepostulacion['Carrera']['nombre']; ?></td>								
								<td><?php echo $this->Ingenia->formatearFecha($prepostulacion[$variable]['modified']);?></td>
								<td>
									<?php echo $prepostulacion['Estado']; ?>
								</td>
								<td>
								<?php 
									$aux = 1;
									if($variable == 'Postulacion'){										
										$tipo = $prepostulacion['Postulacion']['tipo'];
										switch($tipo){
											case 'RAP':
												echo '<a class="btn btn-default btn-xs" href="'.$this->Html->url(array('controller'=>'postulaciones','action'=>'enrutarRap', $prepostulacion['Postulacion']['codigo'])).'"><i class="icon icon-edit"></i> Ver</a>';																				
												break;
											case 'AH':
												echo '<a class="btn btn-default btn-xs" href="'.$this->Html->url(array('controller'=>'postulaciones','action'=>'verAdmision', $prepostulacion['Postulacion']['codigo'])).'"><i class="icon icon-edit"></i> Ver</a>';												
												break;
											case 'AV':
												echo '<a class="btn btn-default btn-xs" href="'.$this->Html->url(array('controller'=>'postulaciones','action'=>'verAdmision', $prepostulacion['Postulacion']['codigo'])).'"><i class="icon icon-edit"></i> Ver</a>';												
												break;
										}
										
									}
									if($variable == 'Prepostulacion'){
										$revision = $prepostulacion['Prepostulacion']['revision'];
										switch($revision){
											case 1:
												echo '<a class="btn btn-default btn-xs" href="'.$this->Html->url(array('controller'=>'prepostulaciones','action'=>'editarPrepostulacion', $prepostulacion['Prepostulacion']['codigo'])).'"><i class="icon icon-edit"></i> Ver</a>';												
												break;
											case null:
												echo '<a class="btn btn-default btn-xs" href="'.$this->Html->url(array('controller'=>'prepostulaciones','action'=>'editarPrepostulacion', $prepostulacion['Prepostulacion']['codigo'])).'"><i class="icon icon-edit"></i> Ver</a>';												
												break;
										}
									} 
									if($variable == 'Prepostulacion'){
										echo '&nbsp;<a class="btn btn-danger btn-xs borrarPostulacion" href="#" data-id="'.$prepostulacion['Prepostulacion']['id'].'"><i class="icon icon-times"></i> Borrar</a>';	
									}
									else {										
										if (($prepostulacion['Postulacion']['habilitado'] !== '1') && ($prepostulacion['Postulacion']['habilitado'] !== '0')){
											echo '&nbsp;<a class="btn btn-danger btn-xs borrarPostulacion" href="#" data-id="'.$prepostulacion['Postulacion']['codigo'].'"><i class="icon icon-times"></i> Borrar</a>';											
										}
									}									
								?>
								</td>								
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<!---<a href="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'completarPostulacion')); ?>" class="btn btn-primary">Completar Postulación</a>-->

<script>
	function eliminar_prepost(id){		
		if(confirm("¿Está segura(o)? Esta información no se podrá recuperar") == true){
			alert("You pressed OK!");
		}else{
			alert("You pressed Cancel!");
		}
	}
	function eliminar_post(id){		
		if(confirm("¿Está segura(o)? Esta información no se podrá recuperar") == true){
			alert("You pressed OK!");
		}else{
			alert("You pressed Cancel!");
		}
	}
</script>
<script>
	$('.borrarPostulacion').on('click',function(){
		var codigo = $(this).data('id');			
		$('#postulacion').val(codigo);
		$('#modalborrarPostulante').modal('show');
		return false;
	});
</script>