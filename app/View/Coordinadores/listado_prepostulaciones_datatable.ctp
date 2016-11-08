<?php
	echo $this->Html->css('jquery.dataTables.min.css');
	echo $this->Html->script('jquery.dataTables.min.js'); 
?>

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
<br/>
<div class="row-fluid">	
	<div class="span3 offset1">
		<h3>Listado de Postulaciones</h3>
	</div>
	<div class="span7">
		
	</div>
</div>
<div class="container-fluid">
  <div class="row-fluid">
		<div class="col-xs-12">
		<div   id="lista_prepostulaciones">
		<br>
			<table class="table table-striped display table-bordered table_escuela" data-order='[[ 0, "asc" ]]'>
				<thead>
					<tr>
						<th width="30">#</th>
						<th width="8%;">Id</th>
						<th style="width:100px;">Creada</th>
						<th width="10%;">RUT</th>
						<th width="10%;">Nombre</th>					
						<th width="9%;">A. Paterno</th>
						<th width="9%;">A. Materno</th>
						<th width="9%;">Email</th>

						<th width="20%;">Carrera</th>
						<th>Tel.</th>
						<th>Tipo</th>
						<th>Estado</th>	
						<th style="width:85px;">Mod.</th>	
						<th>Nº</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					
					if(!empty($prepostulaciones)):  $aux=0;
					
					foreach($prepostulaciones as $k => $prepostulacion):


					?>
						
						<tr>
							<?php if($this->params['paging']['Prepostulacion']['page'] == 1){ $variable = (0);
							}
							else{
								$variable = $this->params['paging']['Prepostulacion']['page']-1;	
							} ;?>
							<td><?php echo ($k +1 )+($variable*20);?></td>
							<?php $codigo_postulante = $prepostulacion['Prepostulacion']['postulante_codigo']; ?>
							<td><?php if (isset($prepostulacion['Prepostulacion']['id_correlativo'])){ echo $prepostulacion['Prepostulacion']['id_correlativo']; } ?></td>
							<td><?php echo ($this->Ingenia->formatearFecha($prepostulacion['Prepostulacion']['created']));?></td>
							<td><?php echo $prepostulacion['Postulante']['rut'];?></td>
							<td><?php echo $prepostulacion['Postulante']['nombre'];?></td>
							<td><?php echo $prepostulacion['Postulante']['apellidop'];?></td>
							<td><?php echo $prepostulacion['Postulante']['apellidom'];?></td>
							<td><?php echo $prepostulacion['Postulante']['email'];?></td>
							<td><?php echo $prepostulacion['Carrera']['nombre'];?></td>
							<td><?php echo $prepostulacion['Postulante']['telefonomovil'];?></td>
							<td>
								<?php 
									if ($prepostulacion['Prepostulacion']['destino'] == 'AH'){
										echo 'ESCUELAS';							
										}
									elseif ($prepostulacion['Prepostulacion']['destino'] == 'AV'){
										echo 'ARTICULACIÓN';							
										}
									else{
										echo $prepostulacion['Prepostulacion']['destino'];
									}
								?>
							</td>
							<td>			
									<?php 								
										if($prepostulacion['Prepostulacion']['destino'] !== null){//Si la postulación ya fue derivada por el coordinador	
											switch ($prepostulacion['Prepostulacion']['destino']) {
												case 'RAP':
													if (isset($prepostulacion['EstadoPostulacion'])){
														$maximo = max($prepostulacion['EstadoPostulacion']);
														$estado = '';
														$paso   = '';
														
														$resp   = false;
														$resp   = in_array(7,$prepostulacion['EstadoPostulacion']); //Verifico si está rechazada
														
														if($resp){
																$estado = '<span class="rojo">Postulación rechazada</span>';
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
													if (isset($prepostulacion['Postulacion']['habilitado'])){																									
														if($prepostulacion['Postulacion']['habilitado'] == '1'){
																if (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] == '1') ){
																	$estado = '<span class="verde">INGRESADO EN CSA</span>';	
																}
																elseif (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] !== '1') ){
																	$estado = '<span class="verde">HABILITADO CON FIRMA</span>';														
																}
																else{
																	$estado = '<span class="verde">HABILITADO</span>';
																}					
															}
														
															elseif ($prepostulacion['Postulacion']['habilitado'] == '0') {
																$estado = '<span class="rojo">NO HABILITADO</span>';
															}
															else {
																$estado = 'EN REVISIÓN';
															}
													}
													else{
														$estado = 'EN REVISIÓN';
													}
													echo $estado;
													break;
												case 'AV':
													$estado = '';
													if (isset($prepostulacion['Postulacion']['habilitado'])){	
													if($prepostulacion['Postulacion']['habilitado'] == '1'){
															if (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] == '1') ){
																$estado = '<span class="verde">INGRESADO EN CSA</span>';	
															}
															elseif (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] !== '1') ){
																$estado = '<span class="verde">HABILITADO CON FIRMA</span>';														
															}
															else{
																$estado = '<span class="verde">HABILITADO</span>';														
															}
														}
														elseif ($prepostulacion['Postulacion']['habilitado'] == '0') {
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
											if($prepostulacion['Prepostulacion']['revision']==0){
													echo 'PENDIENTE DE REVISAR';
											}
											else{
													echo 'DOCUMENTOS CON OBSERVACIONES';
											}
										}
									?>
							</td>			
							<td>
								<?php 
										echo ($this->Ingenia->formatearFecha($prepostulacion['Prepostulacion']['modified']));
									
								 ?>
							</td>						
							<td>
							<?php foreach ($cantidades as $cantidad){
								
								if ($cantidad['Prepostulacion']['postulante_codigo'] == $codigo_postulante){
									echo $cantidad['Prepostulacion']['total'];								
								} 
							}
							?>
							</td>						
							
							<td class="acciones">
								<a href="<?php echo $this->Html->url(array('controller'=>'coordinadores','action'=>'verPrepostulacion', $prepostulacion['Prepostulacion']['codigo'])); ?>" class="btn" target="_blank"> 
									<i class="icon-file-text"></i>&nbsp;Ver 
								</a>
							</td>
						
						</tr>
						
					<?php endforeach ;?>
					<?php else :?>
						<tr><td colspan="8" style="text-align: center">No existen prepostulaciones</td></tr>	
					<?php endif; ?>
				</tbody>
			</table>
			</div>
			
			<!-- Descargar Informe -->
			<div class="row-fluid">
				<div class="span12">
					<span class="pull-right">
					<a type="button" class="btn btn-success excel"><i class="icon icon-download"></i> Exportar Excel</a>
					</span>
				</div>
			</div>
		</div>	
	</div>
</div>
<script>
	$('.table_escuela').DataTable();
	$('.excel').click(function(){
		window.location.href =  webroot +'coordinadores/ajax_exportar_excel_postulaciones';
	})
</script>