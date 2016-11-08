<?php

       header('Content-Type: application/force-download');
       header('Content-Disposition: attachment; filename=ReportePostulaciones.xls');
	   header("Content-Type: application/ms-excel; charset=ISO-8859-1");
       header('Content-Transfer-Encoding: binary');
?>
<style type="text/css" media="all">
/* <![CDATA[ */
td{
	width: 338px;
}
table{
	width:405px;
}
#tabla2 li{
	display:inline-block;
	padding-left: 12px;
	width: 405px;

}
#tabla1 li{
	display:inline-block;
	padding-left: 12px;
	width: 405px;

}
</style>

<div class="container-fluid">
  <div class="row-fluid">
		<div class="col-xs-12">
		<div   id="lista_prepostulaciones">
		<br>
			<table class="table table-bordered table-hover" border="1" style="color:1 px solid black">
				<thead>
					<tr>
						<th width="30">#</th>
						<th width="8%;">Id</th>
						<th style="width:120px;">Creada</th>
						<th width="10%;">RUT</th>
						<th width="10%;">Nombre</th>					
						<th width="9%;">A. Paterno</th>
						<th width="9%;">A. Materno</th>
						<th width="9%;">Email</th>
						<th width="20%;">Carrera</th>
						<th>Tel&eacute;fono</th>
						<th>Tipo</th>
						<th>Estado</th>	
						<th style="width:125px;">Mod.</th>	
						<th>N&deg;</th>
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
							<td><?php echo utf8_decode($prepostulacion['Postulante']['nombre']);?></td>
							<td><?php echo utf8_decode($prepostulacion['Postulante']['apellidop']);?></td>
							<td><?php echo utf8_decode($prepostulacion['Postulante']['apellidom']);?></td>
							<td><?php echo $prepostulacion['Postulante']['email'];?></td>
							<td><?php echo utf8_decode($prepostulacion['Carrera']['nombre']);?></td>
							<td><?php echo $prepostulacion['Postulante']['telefonomovil'];?></td>
							<td>
								<?php 
									if ($prepostulacion['Prepostulacion']['destino'] == 'AH'){
										echo 'ESCUELAS';							
										}
									elseif ($prepostulacion['Prepostulacion']['destino'] == 'AV'){
										echo 'ARTICULACI&Oacute;N';							
										}
									else{
										echo $prepostulacion['Prepostulacion']['destino'];
									}
								?>
							</td>
							<td>			
									<?php 								
										if($prepostulacion['Prepostulacion']['destino'] !== null){//Si la postulaci&Oacute;n ya fue derivada por el coordinador	
											switch ($prepostulacion['Prepostulacion']['destino']) {
												case 'RAP':
													if (isset($prepostulacion['EstadoPostulacion'])){
														$maximo = max($prepostulacion['EstadoPostulacion']);
														$estado = '';
														$paso   = '';
														
														$resp   = false;
														$resp   = in_array(7,$prepostulacion['EstadoPostulacion']); //Verifico si está rechazada
														
														if($resp){
																$estado = '<span class="rojo">Postulaci&Oacute;n rechazada</span>';
																$paso   = '';
														}
														else{
																switch($maximo){
																	case 1:
																		$estado = 'FORMULARIO DE POSTULACI&Oacute;N';
																		$paso   = 'paso 1';
																	break;
																	case 2:
																		$estado = 'DOCUMENTACI&Oacute;N RECIBIDA EN REVISI&Oacute;N';
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
																		$estado = 'CV RAP Y AUTOEVALUACI&Oacute;N EN REVISI&Oacute;N';
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
														echo 'PENDIENTE DE RELLENAR EL FORMULARIO DE POSTULACI&Oacute;N';
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
																$estado = 'EN REVISI&Oacute;N';
															}
													}
													else{
														$estado = 'EN REVISI&Oacute;N';
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
															$estado = 'EN REVISI&Oacute;N';
														}
													}
													else{
														$estado = 'EN REVISI&Oacute;N';
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
						
						</tr>
					<?php endforeach ;?>
					<?php else :?>
						<tr><td colspan="8" style="text-align: center">No existen prepostulaciones</td></tr>	
					<?php endif; ?>
				</tbody>
			</table>
			</div>
		</div>	
	</div>
</div>