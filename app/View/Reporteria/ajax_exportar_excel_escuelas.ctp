<?php

       header('Content-Type: application/force-download');
       header('Content-Disposition: attachment; filename=ReportePostulacionesPorEscuelas.xls');
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
							<H4>Resumen de Postulaciones por ESCUELAS</h4>	
						</div>
						<br>
						<div class="span3" style="width: 100%;"><h6 class="pull-right fechaEtapa2">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6></div>		

						<div class="span12">
								<?php if ($totales) { ?>
									<table  class="table table-striped table-bordered" border="1" style="color:1 px solid black" id="tabla_datos">
										<tbody>
											<tr>
												<th>ESCUELA</th>
												<th>CARRERA</th>
												<?php 
												if ($si_en_revision) { ?>
													<th> <?php echo htmlentities('EN REVISIÓN');?></th>
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
													<th>TOTAL GENERAL</th>
											</tr>

											<?php 
											$tot_revision = 0;
											$tot_hab = 0;
											$tot_nohab = 0;
											$tot_firma = 0;
											$tot_csa = 0;
											$tot = 0;
											foreach($totales as $key_escuela => $total) { 
												$cont_carreras = 0;
												$tot_revision_1 = 0;
												$tot_hab_1 = 0;
												$tot_nohab_1 = 0;
												$tot_firma_1 = 0;
												$tot_csa_1 = 0;
												$tot_1 = 0;
												
												foreach($total as $key_carrera =>  $carrera) { 
													$t = 0;
													if ($cont_carreras < $total['total_carreras']) {
														$sum_en_revision = $carrera['en_revision'];
														$sum_habilitado_sin_firma = $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']);
														$sum_habilitado_firma = $carrera['firma'] - $carrera['csa'];
														$sum_habilitado_csa = $carrera['csa'];
														$no_habilitado = $carrera['no_habilitado'];

														$sum_total_carrera = $sum_en_revision+$sum_habilitado_sin_firma+$sum_habilitado_firma+$sum_habilitado_csa+$no_habilitado;
													if($sum_total_carrera>0){

													?>
														<tr>
															<td>
																<?php echo htmlentities($total['nombre_escuela']); ?>
															</td>
															<td>
															<?php echo htmlentities($carrera['nombre_carrera']); ?>
															</td>
												<?php 
												if ($si_en_revision) { ?>
																<td class="<?php echo $key_carrera; ?>">
																<?php echo $carrera['en_revision']; 
																$t = $t + $carrera['en_revision'];
																?>
																</td>
												<?php } ?>
												<?php 
												if ($si_habilitado_sin_firma) { ?>

																<td class="<?php echo $key_carrera; ?>">
																<?php echo $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']); 
																$t = $t + $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']);?>
																</td>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>

																<td class="<?php echo $key_carrera; ?>">
																<?php echo $carrera['firma'] - $carrera['csa']; 
																$t = $t + $carrera['firma'] - $carrera['csa'];?>
																</td>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>

																<td class="<?php echo $key_carrera; ?>">
																<?php echo $carrera['csa'];
																$t = $t + $carrera['csa']; ?>
																</td>
												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>

															<td class="<?php echo $key_carrera; ?>">
															<?php echo $carrera['no_habilitado']; 
															$t = $t + $carrera['no_habilitado']?>
															</td>
												<?php } ?>
	
															<td style="font-weight: bold; font-size: 18px;" class="<?php echo 'total_'.$key_carrera; ?>">
															<?php echo $t; ?>
															</td>
														</tr>
														<?php
													}
															$tot = $tot + $carrera['en_revision'] + $carrera['habilitado'] + $carrera['no_habilitado'];
															$tot_revision = $tot_revision + $carrera['en_revision'];
															$tot_hab = $tot_hab + $carrera['habilitado'];
															$tot_nohab = $tot_nohab + $carrera['no_habilitado'];
															$tot_firma = $tot_firma + ($carrera['firma'] - $carrera['csa']);
															$tot_csa = $tot_csa + $carrera['csa'];
															//
															$tot_1 = $tot_1 + $carrera['en_revision'] + $carrera['habilitado'] + $carrera['no_habilitado'];
															$tot_revision_1 = $tot_revision_1 + $carrera['en_revision'];
															$tot_hab_1 = $tot_hab_1 + $carrera['habilitado'];
															$tot_nohab_1 = $tot_nohab_1 + $carrera['no_habilitado'];
															$tot_firma_1 = $tot_firma_1 + ($carrera['firma'] - $carrera['csa']);
															$tot_csa_1 = $tot_csa_1 + $carrera['csa'];
															$cont_carreras++;
														?>

													<?php } ?>
												<?php } ?>
														
											<?php } ?>



<?php $t=0; ?>
											<tr style="font-size: 18px;">
												<th colspan="2">TOTAL GENERAL</th>
												<?php 
												if ($si_en_revision) { ?>

												<th class="totales_f"><?php echo $tot_revision; 
												$t = $t + $tot_revision;?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_sin_firma) { ?>

												<th class="totales_f"><?php echo $tot_hab - ($tot_firma + $tot_csa); 
												$t = $t + $tot_hab - ($tot_firma + $tot_csa);?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>

												<th class="totales_f"><?php echo $tot_firma; 
												$t = $t + $tot_firma;?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>

												<th class="totales_f"><?php echo $tot_csa; 
												$t = $t + $tot_csa;?></th>
												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>

												<th class="totales_f"><?php echo $tot_nohab;
												$t = $t + $tot_nohab; ?></th>
												<?php } ?>
										
												<th class="total_total"><?php echo $t; ?></th>
											</tr>
									   </tbody>
									</table>

								<?php }  else { ?>
								<h4>NO HAY DATOS PARA GENERAR TABLA</h4>
								<?php } ?>
						</div>														
					</div>
					<br>
					<H4 style="margin-top:50px;"><span id="img-check-licencia" align="left"></span>&nbsp;<?php echo htmlentities('Distribución Total de postulaciones "En Revisión"'); ?>:</h4>
					<div class="row-fluid">										
						<div class="span6">
								<table class="table table-striped table-bordered" border="1" style="color:1 px solid black">
									<tbody>
										<tr>
											<th>ESCUELA</th>
											<th>POSTULANTES</th>
										</tr>

										<?php 
										$tot = 0;
										foreach($totales_1 as $total) { ?>
										<tr>
											<td>
											<?php echo htmlentities($total['nombre_escuela']); ?>
											</td>
											<td>
											<?php echo $total['en_revision']; 
											$tot = $tot + $total['en_revision'];
											?>
											</td>
										</tr>
										<?php } ?>
										<tr>
											<th>TOTAL</th>
											<th><?php echo $tot; ?></th>
										</tr>
								   </tbody>
								</table>
						</div>
					</div>

				</div>
			</div>
	</div>
</div>