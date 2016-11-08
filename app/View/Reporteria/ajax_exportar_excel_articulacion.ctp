<?php

       header('Content-Type: application/force-download');
       header('Content-Disposition: attachment; filename=ReporteArticulacionPorCarreras.xls');
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


<div class="row-fluid">
	<div class="span10 offset1">	<br>				
			<div id="collapseReporte" class="accordion-body">
				<div class="accordion-inner">
					<!-- COMIENZA EL REPORTE -->
					<div class="row-fluid">
						<div class="span9">
							<H4>Resumen de Postulaciones por CARRERAS</h4>	
						</div>
						<br>
						<div class="span3" style="width: 100%;"><h6 class="pull-right fechaEtapa2">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6></div>		

						<div class="span12">
								<?php if ($totales) { ?>
									<table  class="table table-striped table-bordered" border="1" style="color:1 px solid black" id="tabla_datos">
										<tbody>
											<tr>
												<th>CARRERA</th>
												<?php 
												if ($si_en_revision) { ?>
													<th><?php echo htmlentities('EN REVISIÓN'); ?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_sin_firma) { ?>
													<th>HABILITADAS</th>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>
													<th>HABILITADAS FIRMA</th>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>
													<th>HABILITADAS CSA</th>
												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>
													<th>NO HABILITADAS</th>
												<?php } ?>
													<th>TOTAL CARRERA</th>
											</tr>

											<?php 
											$tot_revision = 0;
											$tot_hab = 0;
											$tot_nohab = 0;
											$tot_firma = 0;
											$tot_csa = 0;
											$tot = 0;
											
											foreach($totales as $carrera) {  

												if ($carrera['nombre_carrera'] <> '0') {
													if($si_en_revision){
														$sum_si_en_revision =  $carrera['en_revision'];	
													}else{
														$sum_si_en_revision =  0;
													}
													if($si_habilitado_sin_firma){
														$sum_si_habilitado_sin_firma = $carrera['habilitado']-(($carrera['firma']-$carrera['csa'])+$carrera['csa']);
													}else{
														$sum_si_habilitado_sin_firma = 0;
													}

													if($si_habilitado_firma){
														$sum_si_habilitado_firma = $carrera['firma'] - $carrera['csa'];
													}else{
														$sum_si_habilitado_firma =0;
													}
													if($si_habilitado_csa){
														$sum_si_habilitado_csa = $carrera['csa'];	
													}else{
														$sum_si_habilitado_csa = 0;
													}
													if($si_no_habilitado){
														$sum_si_no_habilitado = $carrera['no_habilitado'];	
													}else{
														$sum_si_no_habilitado = 0;
													}
													
													$total_carreras = $sum_si_en_revision+$sum_si_habilitado_sin_firma+$sum_si_habilitado_firma+$sum_si_habilitado_csa+$sum_si_no_habilitado;

													$t = 0;
													if($total_carreras>0){
													?>
															<tr>
																<td>
																<?php echo htmlentities($carrera['nombre_carrera']); ?>
																</td>
													<?php 
													if ($si_en_revision) { ?>
																	<td>
																	<?php echo $carrera['en_revision']; 
																	$tot_revision = $tot_revision + $carrera['en_revision'];
																	$t = $t + $carrera['en_revision'];
																	?>
																	</td>
													<?php } ?>
													<?php 
													if ($si_habilitado_sin_firma) { ?>

																	<td>
																	<?php echo $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']); 
																		$tot_hab = $tot_hab + $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']);
																		$t = $t + $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']);
																	?>
																	</td>
													<?php } ?>
													<?php 
													if ($si_habilitado_firma) { ?>

																	<td>
																	<?php echo $carrera['firma'] - $carrera['csa']; 
																		$tot_nohab = $tot_nohab + $carrera['firma'] - $carrera['csa'];
																		$t = $t + $carrera['firma'] - $carrera['csa'];
																	?>
																	</td>
													<?php } ?>
													<?php 
													if ($si_habilitado_csa) { ?>

																	<td>
																	<?php echo $carrera['csa']; 
																		$tot_csa = $tot_csa + $carrera['csa'];
																		$t = $t + $carrera['csa'];
																	?>
																	</td>
													<?php } ?>
													<?php 
													if ($si_no_habilitado) { ?>

																<td>
																<?php echo $carrera['no_habilitado']; 
																	$tot_nohab = $tot_nohab + $carrera['no_habilitado'];
																	$t = $t + $carrera['no_habilitado'];
																?>
																</td>
													<?php } ?>
																<td style="font-weight: bold; font-size: 18px;"><?php echo $t; ?>
																</td>



											<?php } } ?>
											</tr>

											<?php } ?>




<tr style="font-weight: bold; font-size: 18px;">

															<th>
															TOTAL ESTADOS
															</th>
												<?php 
												if ($si_en_revision) { ?>

															<th><?php echo $tot_revision; ?></th>

												<?php } ?>
												<?php 
												if ($si_habilitado_sin_firma) { ?>

															<th><?php echo $tot_hab - ($tot_firma + $tot_csa); ?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>


															<th><?php echo $tot_firma; ?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>


															<th><?php echo $tot_csa; ?></th>

												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>

															<th><?php echo $tot_nohab; ?></th>
												<?php } ?>

															<th><?php echo $tot_nohab + $tot_hab + $tot_revision; ?></th>
														</tr>

									   </tbody>

									</table>

								<?php }  else { ?>
								<h4>NO HAY DATOS PARA GENERAR TABLA</h4>
								<?php } ?>
						</div>														
					</div>
					<br>
				</div>
			</div>
	</div>
</div>