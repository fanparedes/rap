<?php

       header('Content-Type: application/force-download');
       header('Content-Disposition: attachment; filename=ReporteRapPorEscuelas.xls');
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
												if ($si_form_completado) { ?>
													<th>FORM. POST. COMPL.</th>
												<?php } ?>

												<?php 
												if ($si_doc_recibida_revision) { ?>
													<th>DOC. RECIBIDA EN REVISION</th>
												<?php } ?>

												<?php 
												if ($si_cv_completado) { ?>
													<th>CV RAP COMPLETADO</th>
												<?php } ?>

												<?php 
												if ($si_doc_aprobada) { ?>
													<th>DOCUMENTACION APROBADA</th>
												<?php } ?>

												<?php 
												if ($si_cv_revision) { ?>
													<th>CV RAP Y AUTOEVALUACION COMPL. EN REVISION</th>
												<?php } ?>

												<?php 
												if ($si_cv_aprobada) { ?>
													<th>CV RAP Y AUTOEVALUACION APROBADA</th>
												<?php } ?>

												<?php 
												if ($si_entrevista) { ?>
													<th>ENTREVISTA AGENDADA</th>
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
											$tot_form_completado = 0;
											$tot_doc_recibida_revision = 0;
											$tot_cv_completado = 0;
											$tot_doc_aprobada = 0;
											$tot_cv_revision = 0;
											$tot_cv_aprobada = 0;
											$tot_entrevista = 0;											

											$tot_hab = 0;
											$tot_nohab = 0;
											$tot_firma = 0;
											$tot_csa = 0;
											$tot = 0;
											foreach($totales as $key_escuela => $total) { 
												$cont_carreras = 0;

												$tot_form_completado_1 = 0;
												$tot_doc_recibida_revision_1 = 0;
												$tot_cv_completado_1 = 0;
												$tot_doc_aprobada_1 = 0;
												$tot_cv_revision_1 = 0;
												$tot_cv_aprobada_1 = 0;
												$tot_entrevista_1 = 0;	

												$tot_hab_1 = 0;
												$tot_nohab_1 = 0;
												$tot_firma_1 = 0;
												$tot_csa_1 = 0;
												$tot_1 = 0;
												foreach($total as $key_carrera =>  $carrera) { 
													$t= 0;
													$tot_general = 0;
													if ($cont_carreras < $total['total_carreras']) {?>
															<!-- inicio de la suma -->
															<?php 
															if ($si_form_completado) {
																$tot_general = $tot_general + $carrera['form_completado'];
															} 
															if ($si_doc_recibida_revision) { 
																$tot_general = $tot_general + $carrera['doc_recibida_revision'];}
															if ($si_cv_completado) { 
																$tot_general = $tot_general + $carrera['cv_completado'];}
															if ($si_doc_aprobada){
																$tot_general = $tot_general + $carrera['doc_aprobada'];
															}
															if ($si_cv_revision){
																$tot_general = $tot_general + $carrera['cv_revision'];
															}
															if ($si_cv_aprobada) {
																$tot_general = $tot_general + $carrera['cv_aprobada'];
															}
															if ($si_entrevista) { 
																$tot_general = $tot_general + $carrera['entrevista'];
															} 
															if ($si_habilitado_sin_firma) { 
																$tot_general = $tot_general + $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']);
															} 
															if ($si_habilitado_firma) { 
																$tot_general = $tot_general + $carrera['firma'] - $carrera['csa'];
															}
															if ($si_habilitado_csa) {
																$tot_general = $tot_general + $carrera['csa'];
															}
															if ($si_no_habilitado) {
																$tot_general = $tot_general + $carrera['no_habilitado'];
															}
															if($tot_general>0){
															?>
															<tr>
															<!-- fin de la suma -->
															<td>
																<?php echo utf8_decode($total['nombre_escuela']); ?>
																</td>
															<td>
															<?php echo utf8_decode($carrera['nombre_carrera']).' total:'.$t; ?>
															</td>


															<?php 
															if ($si_form_completado) { ?>
																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['form_completado']; 
																			$t = $t + $carrera['form_completado'];
																			?>
																			</td>
															<?php } ?>

															<?php 
															if ($si_doc_recibida_revision) { ?>
																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['doc_recibida_revision']; 
																			$t = $t + $carrera['doc_recibida_revision'];
																			?>
																			</td>
															<?php } ?>

															<?php 
															if ($si_cv_completado) { ?>
																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['cv_completado']; 
																			$t = $t + $carrera['cv_completado'];
																			?>
																			</td>
															<?php } ?>

															<?php 
															if ($si_doc_aprobada) { ?>
																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['doc_aprobada']; 
																			$t = $t + $carrera['doc_aprobada'];
																			?>
																			</td>
															<?php } ?>

															<?php 
															if ($si_cv_revision) { ?>
																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['cv_revision']; 
																			$t = $t + $carrera['cv_revision'];
																			?>
																			</td>
															<?php } ?>

															<?php 
															if ($si_cv_aprobada) { ?>
																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['cv_aprobada']; 
																			$t = $t + $carrera['cv_aprobada'];
																			?>
																			</td>
															<?php } ?>

															<?php 
															if ($si_entrevista) { ?>
																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['entrevista']; 
																			$t = $t + $carrera['entrevista'];
																			?>
																			</td>
															<?php } ?>

															<?php 
															if ($si_habilitado_sin_firma) { ?>

																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']); 
																			$t = $t + $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']);
																			?>
																			</td>
															<?php } ?>
															<?php 
															if ($si_habilitado_firma) { ?>

																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['firma'] - $carrera['csa']; 
																			$t = $t + $carrera['firma'] - $carrera['csa'];
																			?>
																			</td>
															<?php } ?>
															<?php 
															if ($si_habilitado_csa) { ?>

																			<td class="<?php echo $key_carrera; ?>">
																			<?php echo $carrera['csa']; 
																			$t = $t + $carrera['csa'];
																			?>
																			</td>
															<?php } ?>
															<?php 
															if ($si_no_habilitado) { ?>

																		<td class="<?php echo $key_carrera; ?>">
																		<?php echo $carrera['no_habilitado']; 
																		$t = $t + $carrera['no_habilitado'];
																		?>
																		</td>
															<?php } ?>

															<td style="font-weight: bold; font-size: 18px;" class="<?php echo 'total_'.$key_carrera; ?>">
															<?php echo $t; ?>
															</td>
															</tr>
															<?php } ?>
														
														<?php
															$tot = $tot + $carrera['form_completado'] + $carrera['doc_recibida_revision'] + $carrera['cv_completado'] + $carrera['doc_aprobada'] + $carrera['cv_revision'] + $carrera['cv_aprobada'] + $carrera['entrevista'] + $carrera['habilitado'] + $carrera['no_habilitado'];



															$tot_form_completado = $tot_form_completado + $carrera['form_completado'];
															$tot_doc_recibida_revision = $tot_doc_recibida_revision + $carrera['doc_recibida_revision'];
															$tot_cv_completado = $tot_cv_completado + $carrera['cv_completado'];
															$tot_doc_aprobada = $tot_doc_aprobada + $carrera['doc_aprobada'];
															$tot_cv_revision = $tot_cv_revision + $carrera['cv_revision'];
															$tot_cv_aprobada = $tot_cv_aprobada + $carrera['cv_aprobada'];
															$tot_entrevista = $tot_entrevista + $carrera['entrevista'];	


															$tot_hab = $tot_hab + $carrera['habilitado'];
															$tot_nohab = $tot_nohab + $carrera['no_habilitado'];
															$tot_firma = $tot_firma + ($carrera['firma'] - $carrera['csa']);
															$tot_csa = $tot_csa + $carrera['csa'];
															//
															$tot_1 = $tot_1 + $carrera['form_completado'] + $carrera['doc_recibida_revision'] + $carrera['cv_completado'] + $carrera['doc_aprobada'] + $carrera['cv_revision'] + $carrera['cv_aprobada'] + $carrera['entrevista'] + $carrera['habilitado'] + $carrera['no_habilitado'];

															$tot_form_completado_1 = $tot_form_completado_1 + $carrera['form_completado'];
															$tot_doc_recibida_revision_1 = $tot_doc_recibida_revision_1 + $carrera['doc_recibida_revision'];
															$tot_cv_completado_1 = $tot_cv_completado_1 + $carrera['cv_completado'];
															$tot_doc_aprobada_1 = $tot_doc_aprobada_1 + $carrera['doc_aprobada'];
															$tot_cv_revision_1 = $tot_cv_revision_1 + $carrera['cv_revision'];
															$tot_cv_aprobada_1 = $tot_cv_aprobada_1 + $carrera['cv_aprobada'];
															$tot_entrevista_1 = $tot_entrevista_1 + $carrera['entrevista'];	



															$tot_hab_1 = $tot_hab_1 + $carrera['habilitado'];
															$tot_nohab_1 = $tot_nohab_1 + $carrera['no_habilitado'];
															$tot_firma_1 = $tot_firma_1 + ($carrera['firma'] - $carrera['csa']);
															$tot_csa_1 = $tot_csa_1 + $carrera['csa'];
															$cont_carreras++;
														?>

													<?php } ?>
												<?php } ?>

												<!-- inicio de suma -->
												<?php 
												$subtotal_gene = 0;
												if ($si_form_completado) {
													$subtotal_gene = $subtotal_gene + $tot_form_completado_1; 
												} 
												if ($si_doc_recibida_revision) { 
													$subtotal_gene = $subtotal_gene + $tot_doc_recibida_revision_1; 
												}
												if ($si_cv_completado) {
													$subtotal_gene = $subtotal_gene + $tot_cv_completado_1;
												} 
												if ($si_doc_aprobada) {
													$subtotal_gene = $subtotal_gene + $tot_doc_aprobada_1;
												}
												if ($si_cv_revision) {
													$subtotal_gene = $subtotal_gene + $tot_cv_revision_1;
												} 
												if ($si_cv_aprobada) {
													$subtotal_gene = $subtotal_gene + $tot_cv_aprobada_1;
												}
												if ($si_entrevista) {
													$subtotal_gene = $subtotal_gene + $tot_entrevista_1; 
												}
												if ($si_habilitado_sin_firma) {
													$subtotal_gene = $subtotal_gene + $tot_hab_1 - ($tot_firma_1 + $tot_csa_1);
												} 
												if ($si_habilitado_firma) {
													$subtotal_gene = $subtotal_gene + $tot_firma_1;
												}
												if ($si_habilitado_csa) {
													$subtotal_gene = $subtotal_gene + $tot_csa_1; 
												} 
												if ($si_no_habilitado) {
													$subtotal_gene = $subtotal_gene + $tot_nohab_1;
												}
												if($subtotal_gene>0){
												?>
											<!-- termino de suma -->
														<tr style="font-weight: bold; font-size: 18px;">

															<th colspan="2">
															TOTAL ESCUELA
															</th>
												<?php 
												$t = 0;
												if ($si_form_completado) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_form_completado_1; 
															$t = $t + $tot_form_completado_1; 
															?></th>

												<?php } ?>

												<?php 
												if ($si_doc_recibida_revision) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_doc_recibida_revision_1; 
																$t = $t + $tot_doc_recibida_revision_1; 
															?></th>

												<?php } ?>

												<?php 
												if ($si_cv_completado) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_cv_completado_1; 
																$t = $t + $tot_cv_completado_1; 
															?></th>

												<?php } ?>

												<?php 
												if ($si_doc_aprobada) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_doc_aprobada_1; 
																$t = $t + $tot_doc_aprobada_1; 
															?></th>

												<?php } ?>

												<?php 
												if ($si_cv_revision) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_cv_revision_1; 
																$t = $t + $tot_cv_revision_1; 
															?></th>

												<?php } ?>

												<?php 
												if ($si_cv_aprobada) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_cv_aprobada_1; 
																$t = $t + $tot_cv_aprobada_1; 
															?></th>

												<?php } ?>

												<?php 
												if ($si_entrevista) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_entrevista_1; 
																$t = $t + $tot_entrevista_1; 
															?></th>

												<?php } ?>





												<?php 
												if ($si_habilitado_sin_firma) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_hab_1 - ($tot_firma_1 + $tot_csa_1); 
																$t = $t + $tot_hab_1 - ($tot_firma_1 + $tot_csa_1); 
															?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>


															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_firma_1;
																$t = $t + $tot_firma_1; 
															 ?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>


															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_csa_1; 
																$t = $t + $tot_csa_1; 
															?></th>

												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_nohab_1;
																$t = $t + $tot_nohab_1; 
															 ?></th>
												<?php } ?>

															<th class="<?php echo 'total_'.$key_escuela; ?>">
															<?php echo $t; ?>
															</th>
														</tr>

											<?php }
											}
											 ?>



<?php $t=0; ?>
											<tr style="font-size: 18px;">
												<th colspan="2">TOTAL GENERAL</th>


												<?php 
												if ($si_form_completado) { ?>

												<th class="totales_f"><?php echo $tot_form_completado; 
													$t = $t + $tot_form_completado; 
												?></th>
												<?php } ?>

												<?php 
												if ($si_doc_recibida_revision) { ?>

												<th class="totales_f"><?php echo $tot_doc_recibida_revision; 
													$t = $t + $tot_doc_recibida_revision; 
												?></th>
												<?php } ?>

												<?php 
												if ($si_cv_completado) { ?>

												<th class="totales_f"><?php echo $tot_cv_completado; 
													$t = $t + $tot_cv_completado; 
												?></th>
												<?php } ?>

												<?php 
												if ($si_doc_aprobada) { ?>

												<th class="totales_f"><?php echo $tot_doc_aprobada; 
													$t = $t + $tot_doc_aprobada; 
												?></th>
												<?php } ?>

												<?php 
												if ($si_cv_revision) { ?>

												<th class="totales_f"><?php echo $tot_cv_revision; 
													$t = $t + $tot_cv_revision; 
												?></th>
												<?php } ?>

												<?php 
												if ($si_cv_aprobada) { ?>

												<th class="totales_f"><?php echo $tot_cv_aprobada; 
													$t = $t + $tot_cv_aprobada; 
												?></th>
												<?php } ?>

												<?php 
												if ($si_entrevista) { ?>

												<th class="totales_f"><?php echo $tot_entrevista; 
													$t = $t + $tot_entrevista; 
												?></th>
												<?php } ?>


												<?php 
												if ($si_habilitado_sin_firma) { ?>

												<th class="totales_f"><?php echo $tot_hab - ($tot_firma + $tot_csa); 
													$t = $t + $tot_hab - ($tot_firma + $tot_csa); 
												?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>

												<th class="totales_f"><?php echo $tot_firma; 
													$t = $t + $tot_firma; 
												?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>

												<th class="totales_f"><?php echo $tot_csa; 
													$t = $t + $tot_csa; 
												?></th>
												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>

												<th class="totales_f"><?php echo $tot_nohab; 
													$t = $t + $tot_nohab; 
												?></th>
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


				</div>
			</div>
	</div>
</div>