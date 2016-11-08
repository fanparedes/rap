<style>
	.acciones{width:110px;}
h1{
font-size: 20px;
color: #111;
}
.content{
	width: 80%;
	margin: 0 auto;
	margin-top: 50px;
}
</style>
<script>
$(document).ready(function() {
	$( document ).tooltip();
	$('#AdministrativosBuscar').val('');
	$('a.btn.buscador').click(function(){
	if($('#AdministrativosBuscar').val() == '')
	{
		alert('Debe ingresar al menos un caracter');
		return false;
	}else{
		$('#AdministrativosBuscadorForm').submit();
	}
})



$('input.country').typeahead({
	name: 'data[Administrativos][buscar]',
    remote : webroot +'administrativos/ajax_autocompletar/%QUERY'
});


})

</script>
<br/>
<?php $perfil = $this->Session->read('UserLogued.Administrativo.perfil'); 
switch ($perfil) {
    case 0:
        $redireccion = 'administrativos';
        break;
    case 1:
        $redireccion = 'administrativos';
        break;
    case 2:
        $redireccion = 'orientadores';
        break;
	case 3:
        $redireccion = 'evaluadores';
        break;
}
?>

<div class="row-fluid">
	<div class="span8 offset1">
		<h3>Se han encontrado <?php echo $catidad_result; ?> resultados:</h3><br>
	</div>
	<div class="span2">
		<?php if ($vista_buscador == 'PREPOSTULACIONES'){ echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon icon-arrow-circle-left"></i></div> <div class="li-menu-texto">  Volver</div>', array('controller' => 'Coordinadores', 'action' => 'listadoPrepostulaciones'), array('escape' => false)); }?>
		<?php if ($vista_buscador == 'POSTULANTES'){ echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon icon-arrow-circle-left"></i></div> <div class="li-menu-texto">  Volver</div>', array('controller' => 'Administrativos', 'action' => 'postulantes'), array('escape' => false)); }?>
		<?php if ($vista_buscador == 'TODOS' || $vista_buscador == 'TODOS2'){ echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon icon-arrow-circle-left"></i></div> <div class="li-menu-texto">  Volver</div>', array('controller' => 'Administrativos', 'action' => 'listadoPostulacionesNuevas'), array('escape' => false)); }?>
		<?php if ($vista_buscador == 'RAP'){ echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon icon-arrow-circle-left"></i></div> <div class="li-menu-texto">  Volver</div>', array('controller' => 'Administrativos', 'action' => 'listadopostulaciones'), array('escape' => false)); }?>
	</div>
</div>
</form>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-12">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>					
						<th>#</th>
						<?php if ($vista_buscador !== 'POSTULANTES'): ?>
							<th>Id</th>
						<?php endif; ?>
						<th>Nombre</th>
						<th>A.Paterno</th>
						<th>A.Materno</th>
						<th>RUT</th>
						<th>Fecha Nac.</th>
						<th>Email</th>
						<?php if ($vista_buscador == 'POSTULANTES'): ?>
							<th style="width: 70px;">Acciones</th>
						<?php endif; ?>
						<?php if ($vista_buscador !== 'POSTULANTES'): ?>
							<th>Tipo</th>					
						<th>Carrera</th>					
						<th>Estado</th>					
						<th>Creada</th>					
						<th>Modificada</th>					
						<th style="width: 70px;">Acciones</th>
						<?PHP ENDIF; ?>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($postulaciones)):
					
					foreach($postulaciones as $k => $postulacion):?>
					<tr>
						<td><?php echo ($k+1); ?></td>
						<?php if ($vista_buscador !== 'POSTULANTES'): ?>
						<td>
							<?php 
								if (isset($postulacion['Prepostulacion']['id_correlativo'])){ 
									echo ($postulacion['Prepostulacion']['id_correlativo']); 
								} 
								elseif (isset($postulacion['Postulacion'])){
									echo ($postulacion['Postulacion']['id_correlativo']); 
								} 
							?>
						</td>
						<?php endif; ?>
						<td><?php echo strtoupper($postulacion['Postulante']['nombre']); ?></td>
						<td><?php if (isset($postulacion['Postulante']['apellidop'])) { echo strtoupper($postulacion['Postulante']['apellidop']);} ?></td>
						<td><?php if (isset($postulacion['Postulante']['apellidom'])) { echo strtoupper($postulacion['Postulante']['apellidom']);} ?></td>
						<td><?php if (isset($postulacion['Postulante']['rut'])) {echo number_format(substr($postulacion['Postulante']['rut'],0,-1 ),0,"",".").'-'.substr($postulacion['Postulante']['rut'],strlen($postulacion['Postulante']['rut'])-1,1);} ?></td>
						<td><?php echo date('d-m-Y',strtotime($postulacion['Postulante']['fecha_nacimiento'])); ?></td>
						<td><?php echo $postulacion['Postulante']['email']; ?></td>
						<?php if ($vista_buscador == 'POSTULANTES'): ?>
							<td style="text-align: center;">
								<a href="<?php echo $this->Html->url(array('controller'=>'Administrativos', 'action'=>'updateData',$postulacion['Postulante']['codigo'])) ; ?>" class="btn btn-small" title="Editar Datos"> 
									<i class="icon-file-text"></i>
								</a>
						
									<a data-activo="<?php //echo($postulante['Postulante']['activo']);?>" data-codigo="<?php echo $postulacion['Postulante']['codigo'];?>" href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'borrarPostulante', $postulacion['Postulante']['codigo'])); ?>" class="btn btn-small btn-danger borrarPostulante" title="Borrar Postulante"> 
										<i class="fa icon-trash-o"></i>
									</a>

			
							</td>
						<?php endif; ?>
						<?php if ($vista_buscador !== 'POSTULANTES'): ?>
						<td>
							<?php if (isset($postulacion['Prepostulacion']['destino'])) { 
									$destino = $postulacion['Prepostulacion']['destino'];								
								}
								else{
									$destino = $postulacion['Postulacion']['tipo'];
								}
								switch ($destino){
										case 'AH':
											echo "ESCUELAS";
											break;
										case 'AV':
											echo "ARTICULACIÓN";
											break;
										case 'RAP':
											echo "RAP";
											break;
									}
							?>
						</td>
						
						<td><?php if (isset($postulacion['Carrera']['nombre'])) { echo $postulacion['Carrera']['nombre']; } else { echo 'Carrera Obsoleta'; } ?></td>

						<td>
							<?php 
								//PREPOSTULACIONES
								/*if (isset($postulacion['Prepostulacion'])){
									if ($postulacion['Prepostulacion']['revision'] == null){
										echo 'PENDIENTE DE REVISAR';							
									}elseif ( ($postulacion['Prepostulacion']['revision'] == 1) && ($postulacion['Prepostulacion']['destino'] == null) ){
										echo 'DOCUMENTOS CON OBSERVACIONES';							
									}elseif (($postulacion['Prepostulacion']['revision'] == 2) || ($postulacion['Prepostulacion']['revision'] == 1)){ //YA SON POSTULACIÓN	
											if ($postulacion['Postulacion']['revision'] == null){
												echo 'EN REVISIÓN';
											}elseif (($postulacion['Postulacion']['revision'] == 1) && ($postulacion['Postulacion']['habilitado'] == 1)){
												echo '<span class="verde">HABILITADO</span>';
											}elseif (($postulacion['Postulacion']['revision'] == 1) && ($postulacion['Postulacion']['habilitado'] == 0)){
												echo '<span class="rojo">NO HABILITADO</span>';
											}
									}
								}else{
										if ($postulacion['Postulacion']['tipo'] <> 'RAP'){									
											if ($postulacion['Postulacion']['revision'] == null){
													echo 'EN REVISIÓN';
												}elseif (($postulacion['Postulacion']['revision'] == 1) && ($postulacion['Postulacion']['habilitado'] == 1)){
													echo '<span class="verde">HABILITADO</span>';
												}elseif (($postulacion['Postulacion']['revision'] == 1) && ($postulacion['Postulacion']['habilitado'] == 0)){
													echo '<span class="rojo">NO HABILITADO</span>';
												}
											}
										else{
											echo $postulacion['Postulacion']['estado'];
										}
								}*/
							?>

							<?php 								
										if($postulacion['Prepostulacion']['destino'] !== null){//Si la postulación ya fue derivada por el coordinador	
											switch ($postulacion['Prepostulacion']['destino']) {
												case 'RAP':
												//var_dump($postulacion); die;
													if (isset($postulacion['EstadoPostulacion'])){
														$maximo = max($postulacion['EstadoPostulacion']);
														$estado = '';
														$paso   = '';
														
														$resp   = false;
														$resp   = in_array(7,$postulacion['EstadoPostulacion']); //Verifico si está rechazada
														
														if($resp){
																$estado = '<span class="rojo">POSTULACIÓN RECHAZADA</span>';
																$paso   = '';
														}
														else{
																switch($maximo){
																	case 1:
																		$estado = 'FORMULARIO DE POSTULACIÓN';
																		$paso   = 'paso 1';
																	break;
																	case 2:
																		$estado = 'DOCUMENTACIÓN RECIBIDA EN REVISIÓN';
																		$paso   = 'paso 2';
																	break;
																	case 3:
																		$estado = 'CV RAP';
																		$paso   = 'paso 3';
																	break;
																	case 4:
																		$estado = 'CV RAP COMPLETADO';
																		$paso   = 'paso 4';
																	break;
																	case 5:
																		$estado = 'CV RAP Y AUTOEVALUACIÓN EN REVISIÓN';
																		$paso   = 'paso 5';
																	break;
																	case 6:
																		$estado = 'EVIDENCIAS PREVIAS - ENTREVISTA';
																		$paso   = 'paso 6';
																	break;
																	case 7:
																		$estado = '<span class="rojo">NO HABILITADO</span>';
																		$paso   = '';
																	break;
																	case 8:
																		$estado = 'INFORME DE EVIDENCIAS FINALES';
																		$paso   = 'paso 7';
																	break;
																	case 9:
																		$estado = '<span class="verde">HABILITADO</span>';
																		$paso   = '';
																	break;
																}
														}
														echo $estado;	
													}
													else{ //NO ESTÁ EL ESTADO DE RAP POR TANTO SOLO PUEDE ESTAR EN OBSERVACIONES
														echo 'PENDIENTE DE RELLENAR EL FORMULARIO DE POSTULACIÓN';
													}
													break;
												case 'AH':
													if (isset($postulacion['Postulacion']['habilitado'])){																									
														if($postulacion['Postulacion']['habilitado'] == '1'){
																if (($postulacion['Postulacion']['firma'] == '1') && ($postulacion['Postulacion']['csa'] == '1') ){
																	$estado = '<span class="verde">INGRESADO EN CSA</span>';	
																}
																elseif (($postulacion['Postulacion']['firma'] == '1') && ($postulacion['Postulacion']['csa'] !== '1') ){
																	$estado = '<span class="verde">HABILITADO CON FIRMA</span>';														
																}
																else{
																	$estado = '<span class="verde">HABILITADO</span>';
																}					
															}
														
															elseif ($postulacion['Postulacion']['habilitado'] == '0') {
																$estado = '<span class="rojo">NO HABILITADO</span>';
															}
															else {
																$estado = 'EN REVISIÓN 2';
															}
													}
													else{
														$estado = 'EN REVISIÓN 3';
													}
													echo $estado;
													break;
												case 'AV':
													$estado = '';
													if (isset($postulacion['Postulacion']['habilitado'])){	
													if($postulacion['Postulacion']['habilitado'] == '1'){
															if (($postulacion['Postulacion']['firma'] == '1') && ($postulacion['Postulacion']['csa'] == '1') ){
																$estado = '<span class="verde">INGRESADO EN CSA</span>';	
															}
															elseif (($postulacion['Postulacion']['firma'] == '1') && ($postulacion['Postulacion']['csa'] !== '1') ){
																$estado = '<span class="verde">HABILITADO CON FIRMA</span>';														
															}
															else{
																$estado = '<span class="verde">HABILITADO</span>';														
															}
														}
														elseif ($postulacion['Postulacion']['habilitado'] == '0') {
															$estado = '<span class="rojo">NO HABILITADO</span>';
														}
														else {
															$estado = 'EN REVISIÓN';
														}
													}
													else{
														$estado = 'EN REVISIÓN';
													}	
												echo ''.$estado;
												break;
											}									
										}
										else{
											if($postulacion['Prepostulacion']['revision']==0){
													echo 'PENDIENTE DE REVISAR';
											}
											else{
													echo 'DOCUMENTOS CON OBSERVACIONES';
											}
										}
									?>
						</td>
						<td>
							<?php if (isset ($postulacion['Prepostulacion']['created'])): echo $this->Ingenia->formatearFecha($postulacion['Prepostulacion']['created']); ?>
							<?php elseif ((isset ($postulacion['Postulacion']['created']))): echo $this->Ingenia->formatearFecha($postulacion['Postulacion']['created']); endif; ?>
						</td>
						<td>
							<?php if ((isset ($postulacion['Prepostulacion']['modified']))): echo $this->Ingenia->formatearFecha($postulacion['Prepostulacion']['modified']); ?>
							<?php elseif ((isset ($postulacion['Postulacion']['modified']))): echo $this->Ingenia->formatearFecha($postulacion['Postulacion']['modified']); endif; ?>
						</td>
						<td style="text-align: center;">	
							<?php if (isset($vista_buscador) && ($vista_buscador == 'RAP')): ?>						
														<?php  if (($postulacion['Postulacion']['codigo']!== null) && ($postulacion['Postulacion']['estado'] !== 'FORMULARIO PENDIENTE DE COMPLETAR')): ?>
															<a href="<?php echo $this->Html->url(array('controller'=>$redireccion,'action'=>'postulaciones', $postulacion['Postulacion']['codigo'])); ?>" target="_blank" class="btn" title="Ver Postulación"> 
																<i class="icon-search"></i>
															</a>
														<?php endif; ?>					
							<?php endif; ?>					
							<?php if (isset($tipo_buscador) && ($tipo_buscador == 'RAP')): ?>						
								<?php  if ($postulacion['Postulacion']['codigo']!== null): ?>
									<a href="<?php echo $this->Html->url(array('controller'=>$redireccion,'action'=>'postulaciones', $postulacion['Postulacion']['codigo'])); ?>" class="btn" target="_blank" title="Ver Postulación"> 
										<i class="icon-search"></i>
									</a>
								<?php endif; ?>
							
							<?php elseif (isset($vista_buscador) && ($vista_buscador == 'PREPOSTULACIONES')): ?>
									<a href="<?php echo $this->Html->url(array('controller'=>'coordinadores','action'=>'verPrepostulacion', $postulacion['Prepostulacion']['codigo'])); ?>" class="btn" target="_blank" title="Ver Postulación">
										<i class="icon-search"></i>
									</a>					
							
							<?php elseif (isset($vista_buscador) && ($vista_buscador == 'TODOS')): 					
							 ?>
							 		<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'verAdmision', $postulacion['Postulacion']['codigo'])); ?>" class="btn" target="_blank" title="Ver Admisión">
										<i class="icon-search"></i>
									</a>
							 <?php elseif (isset($vista_buscador) && ($vista_buscador == 'TODOS2')): 			
							?>
									<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'verAdmision', $postulacion['Postulacion']['codigo'])); ?>" class="btn" target="_blank" title="Ver Admisión">
										<i class="icon-search"></i>
									</a>
							 <?php elseif (isset($vista_buscador) && ($vista_buscador == 'AH')): 
							 
							 ?>							
							 		<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'verAdmision', $postulacion['Postulacion']['codigo'])); ?>" class="btn" target="_blank" title="Ver Admisión">
										<i class="icon-search"></i>
									</a>
							 <?php 
							 elseif ( (isset($vista_buscador)) && ($vista_buscador == 'AV')): 
								?>
									<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'verAdmision', $postulacion['Postulacion']['codigo'])); ?>" class="btn" target="_blank" title="Ver Admisión">
										<i class="icon-search"></i>
									</a>
								<?php					
							 
							 endif; 
							 ?>						 
						</td>
						<?php endif; //FIN DE POSTULANTE VISTA?>
					</tr>
					<?php endforeach; else: ?>
						<tr><td colspan="6" style="text-align: center">No existen postulaciones</td></tr>	
					<?php endif; ?>
				</tbody>
			</table>
		</div>	
	</div>
</div>
<br/>

<div id="modalborrarPostulante" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<div class="row-fluid">
			<h3 style="text-align: center;">¿Está seguro que desea ELIMINAR esta Postulación?</h3>
			<br>
			<p><strong><i class="icon-warning"></i> Si acepta se borrarán TODOS los datos asociados al postulante y a la postulación.</strong></p>
		</div>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'borrarPostulante'))?>">	
	  	<?php echo $this->Form->input('postulante', array ('type' => 'hidden', 'value' => '')); ?>
	  	<?php echo $this->Form->input('activo', array ('type' => 'hidden', 'value' => '')); ?>
		<div class="modal-body">	  		
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>
<script>
$( "#loader" ).hide();
$( "#AdministrativosBuscar" ).keyup(function() {
	$("#loader").show().delay(680).fadeOut();  
});
$('.borrarPostulante').on('click',function(){
	var codigo = $(this).data('codigo');
	var activo = $(this).data('activo');
	$('#postulante').val(codigo);
	$('#activo').val(activo);
	$('#modalborrarPostulante').modal('show');
	return false;
});
</script>