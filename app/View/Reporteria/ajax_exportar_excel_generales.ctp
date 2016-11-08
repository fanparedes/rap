<?php

       header('Content-Type: application/force-download');
       header('Content-Disposition: attachment; filename=ReporteGeneral.xls');
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
							<H4>Resumen General de Postulaciones</h4>	
						</div>
						<div class="span3" style="width: 100%;"><h6 class="pull-right fechaEtapa2">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6></div>		

						<div class="span12">
								<table class="table table-striped table-bordered" border="1" style="color:1 px solid black">
									<tbody>
										<tr>
											<th>TIPO <?php echo utf8_decode('POSTULACIÓN'); ?></th>
											<th>ESTADO</th>

											<?php
											foreach($totales['AH'] as $escuela) { ?>

											<th><?php echo utf8_decode($escuela['nombre_escuela']); ?></th>

											<?php } ?>

											<?php if($si_tipo_otros){ ?>
											<th>OTROS</th>
											<?php } ?>
											<th>TOTAL GENERAL</th>
										</tr>


										<?php
										$sum_general = array();
										if ($si_tipo_escuelas) { ?>
											<tr>

												<td rowspan= '5' >
													ESCUELAS
												</td>
													<?php
													if ($si_en_revision) { ?>										
														<td>
															<?php echo utf8_decode('En Revisión'); ?>
														</td>

														<?php 
														$suma_escuelas = array();
														$suma = 0;
														$c = 0;
														foreach($totales['AH'] as $key => $escuela) { 
															$sum_general[$c] = $escuela['en_revision'];
														?>
															<td><?php echo $escuela['en_revision']; 
															$escuelas[$key] = $escuelas[$key] + $escuela['en_revision'];
															$suma = $suma + $escuela['en_revision'];
															?></td>
														<?php 
															$c++;
														}
														$sum_general[$c] = 0;
														$sum_general[$c+1] = $suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php } ?>
											</tr>
											<tr>
												<?php										
												if ($si_habilitado) { ?>										
													<td>
														Habilitados
													</td>

													<?php 
													$suma = 0;
													$c = 0;
													foreach($totales['AH'] as $key => $escuela) { 
														$sum_general[$c] = $sum_general[$c]+ $escuela['habilitado'];
														?>
														<td><?php echo $escuela['habilitado']; 
														$escuelas[$key] = $escuelas[$key] + $escuela['habilitado'];
														$suma = $suma + $escuela['habilitado'];
														?></td>
													<?php 
														$c++;
													} 
													$sum_general[$c+1] = $sum_general[$c+1]+$suma;
													?>
													<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
													<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
												<?php } ?>
											</tr>
											<tr>
										<?php										
										if ($si_habilitado_firma) { ?>										
											<td>
												Habilitados Firma
											</td>

											<?php 
											$suma = 0;
											$c = 0;
											foreach($totales['AH'] as $key => $escuela) { 
												$sum_general[$c] = $sum_general[$c]+ $escuela['firma'];
												?>
												<td><?php echo $escuela['firma']; 
												$escuelas[$key] = $escuelas[$key] + $escuela['firma'];
												$suma = $suma + $escuela['firma'];
												?></td>
											<?php 
												$c++;
											} 
											$sum_general[$c+1] = $sum_general[$c+1]+$suma;
											?>
											<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
											<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
										<?php } ?>
									</tr>

									<tr>
										<?php										
										if ($si_habilitado_csa) { ?>										
											<td>
												Habilitados CSA
											</td>

											<?php 
											$suma = 0;
											$c = 0;
											foreach($totales['AH'] as $key => $escuela) { 
												$sum_general[$c] = $sum_general[$c]+ $escuela['csa'];
												?>
												<td><?php echo $escuela['csa']; 
												$escuelas[$key] = $escuelas[$key] + $escuela['csa'];
												$suma = $suma + $escuela['csa'];
												?></td>
											<?php 
												$c++;
											} 
											$sum_general[$c+1] = $sum_general[$c+1]+$suma;
											?>
											<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
											<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
										<?php } ?>
									</tr>
											<tr>
												<?php										
												if ($si_no_habilitado) { ?>										

													<td>
														No Habilitados
													</td>

													<?php 
													$suma = 0;
													$c = 0;
													foreach($totales['AH'] as $key => $escuela) { 
														$sum_general[$c] = $sum_general[$c]+ $escuela['no_habilitado'];
														?>
														<td><?php echo $escuela['no_habilitado']; 
														$escuelas[$key] = $escuelas[$key] + $escuela['no_habilitado'];
														$suma = $suma + $escuela['no_habilitado'];
														?></td>
													<?php 
														$c++;

													} 
													$sum_general[$c+1] = $sum_general[$c+1]+$suma;
													?>
													<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
													<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
												<?php } ?>
											</tr>


											<tr>
												<th colspan='2'>TOTAL ESCUELAS</th>

												<?php
												$suma = 0;
												$c = 0;
												foreach($totales['AH'] as $key => $escuela) { 
													$sum_general[$c] = $sum_general[$c]+ $escuelas[$key];
												?>

												<th><?php echo $escuelas[$key]; 
												$suma = $suma + $escuelas[$key];?></th>

												<?php 
													$c++;
												} 

												$sum_general[$c+1] = $sum_general[$c+1]+$suma;
												?>

												<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
												<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
											</tr>
										<?php } ?>
<?php
			foreach($escuelas as $key => $esc) {
				$escuelas[$key] = 0;
			}
			?>

										<?php
										if ($si_tipo_articulacion) { ?>

											<tr>

												<td rowspan= '5' >
													<?php echo utf8_decode('ARTICULACIÓN'); ?>
												</td>
													<?php
													if ($si_en_revision) { ?>										
														<td>
															<?php echo utf8_decode('En Revisión'); ?>
														</td>

														<?php 
														$suma = 0;
														$c = 0;
														foreach($totales['AV'] as $key => $escuela) { 
															$sum_general[$c] = $sum_general[$c]+ $escuela['en_revision'];
														?>
															<td><?php echo $escuela['en_revision']; 
															$escuelas[$key] = $escuelas[$key] + $escuela['en_revision'];
															$suma = $suma + $escuela['en_revision'];
															?></td>
														<?php 
														$c++;
														} 
														$sum_general[$c+1] = $sum_general[$c+1]+$suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php } ?>
											</tr>
											<tr>
												<?php										
												if ($si_habilitado) { ?>										

													<td>
														Habilitados
													</td>

													<?php 
													$suma = 0;
													$c = 0;
													foreach($totales['AV'] as $key => $escuela) { 
														$sum_general[$c] = $sum_general[$c]+ $escuela['habilitado'];
														?>
														<td><?php echo $escuela['habilitado']; 
														$escuelas[$key] = $escuelas[$key] + $escuela['habilitado'];
														$suma = $suma + $escuela['habilitado'];
														?></td>
													<?php
														$c++;
													 } 
													 $sum_general[$c+1] = $sum_general[$c+1]+$suma;
													 ?>
													<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
													<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
												<?php } ?>
											</tr>

											<tr>
										<?php										
										if ($si_habilitado_firma) { ?>										

												<td>
													Habilitados Firma
												</td>

												<?php 
												$suma = 0;
												$c = 0;
												foreach($totales['AV'] as $key => $escuela) { 
													$sum_general[$c] = $sum_general[$c]+ $escuela['firma'];
													?>
													<td><?php echo $escuela['firma']; 
													$escuelas[$key] = $escuelas[$key] + $escuela['firma'];
													$suma = $suma + $escuela['firma'];
													?></td>
												<?php
													$c++;
												 } 
												 $sum_general[$c+1] = $sum_general[$c+1]+$suma;
												 ?>
												<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
												<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
											<?php } ?>
										</tr>
										<tr>
											<?php										
											if ($si_habilitado_csa) { ?>										

												<td>
													Habilitados CSA
												</td>

												<?php 
												$suma = 0;
												$c = 0;
												foreach($totales['AV'] as $key => $escuela) { 
													$sum_general[$c] = $sum_general[$c]+ $escuela['csa'];
													?>
													<td><?php echo $escuela['csa']; 
													$escuelas[$key] = $escuelas[$key] + $escuela['csa'];
													$suma = $suma + $escuela['csa'];
													?></td>
												<?php
													$c++;
												 } 
												 $sum_general[$c+1] = $sum_general[$c+1]+$suma;
												 ?>
												<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
												<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
											<?php } ?>
										</tr>

											<tr>
												<?php										
												if ($si_no_habilitado) { ?>										

													<td>
														No Habilitados
													</td>

													<?php 
													$suma = 0;
													$c = 0;
													foreach($totales['AV'] as $key => $escuela) { 
														$sum_general[$c] = $sum_general[$c]+ $escuela['no_habilitado'];
														?>
														<td><?php echo $escuela['no_habilitado']; 
														$escuelas[$key] = $escuelas[$key] + $escuela['no_habilitado'];
														$suma = $suma + $escuela['no_habilitado'];
														?></td>
													<?php 
														$c++;
													} 

													$sum_general[$c+1] = $sum_general[$c+1]+$suma;
													?>
													<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
													<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
												<?php } ?>
											</tr>

											<tr>
												<th colspan='2'><?php echo utf8_decode('TOTAL ARTICULACIÓN') ?></th>

												<?php
												$suma = 0;
												$c = 0;
												foreach($totales['AV'] as $key => $escuela) { 
													$sum_general[$c] = $sum_general[$c]+ $escuelas[$key];
												?>

												<th><?php echo $escuelas[$key]; 
												$suma = $suma + $escuelas[$key];
												?></th>

												<?php 
													$c++;
												} 
												$sum_general[$c+1] = $sum_general[$c+1]+$suma;
												?>

												<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
												<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
											</tr>
										<?php } ?>
<?php
			foreach($escuelas as $key => $esc) {
				$escuelas[$key] = 0;
			}
			?>

										<?php
										if ($si_tipo_rap) { ?>

											<tr>

												<td rowspan= '5' >
													RAP
												</td>
													<?php
													if ($si_en_revision) { ?>										


														<td>
															<?php echo utf8_decode('En Revisión'); ?>
														</td>

														<?php 
														$suma = 0;
														$c = 0;
														foreach($totales['RAP'] as $key => $escuela) { 
															$sum_general[$c] = $sum_general[$c]+ $escuela['en_revision'];
														?>
															<td><?php echo $escuela['en_revision']; 
															$escuelas[$key] = $escuelas[$key] + $escuela['en_revision'];
															$suma = $suma + $escuela['en_revision'];
															?></td>
														<?php 
															$c++;
														} 
														$sum_general[$c+1] = $sum_general[$c+1]+$suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php 
														
													} 
													?>
											</tr>
											<tr>
													<?php
													if ($si_habilitado) { ?>										

														<td>
															Habilitados
														</td>

														<?php 
														$suma = 0;
														$c = 0;
														foreach($totales['RAP'] as $key => $escuela) { 
															$sum_general[$c] = $sum_general[$c]+ $escuela['habilitado'];
														?>
															<td><?php echo $escuela['habilitado']; 
															$escuelas[$key] = $escuelas[$key] + $escuela['habilitado'];
															$suma = $suma + $escuela['habilitado'];
															?></td>
														<?php 
															$c++;
														} 
														$sum_general[$c+1] = $sum_general[$c+1]+$suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php 
														
													} 
													?>
											</tr>
											<tr>
													<?php
													if ($si_habilitado_firma) { ?>										

														<td>
															Habilitados Firma
														</td>

														<?php 
														$suma = 0;
														$c = 0;
														foreach($totales['RAP'] as $key => $escuela) { 
															$sum_general[$c] = $sum_general[$c]+ $escuela['firma'];
														?>
															<td><?php echo $escuela['firma']; 
															$escuelas[$key] = $escuelas[$key] + $escuela['firma'];
															$suma = $suma + $escuela['firma'];
															?></td>
														<?php 
															$c++;
														} 
														$sum_general[$c+1] = $sum_general[$c+1]+$suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php 
														
													} 
													?>
											</tr>
											<tr>
													<?php
													if ($si_habilitado_csa) { ?>										

														<td>
															Habilitados CSA
														</td>

														<?php 
														$suma = 0;
														$c = 0;
														foreach($totales['RAP'] as $key => $escuela) { 
															$sum_general[$c] = $sum_general[$c]+ $escuela['csa'];
														?>
															<td><?php echo $escuela['csa']; 
															$escuelas[$key] = $escuelas[$key] + $escuela['csa'];
															$suma = $suma + $escuela['csa'];
															?></td>
														<?php 
															$c++;
														} 
														$sum_general[$c+1] = $sum_general[$c+1]+$suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php 
														
													} 
													?>
											</tr>
											<tr>
													<?php
													if ($si_no_habilitado) { ?>										


														<td>
															No Habilitados
														</td>

														<?php 
														$suma = 0;
														$c = 0;
														foreach($totales['RAP'] as $key => $escuela) { 
															$sum_general[$c] = $sum_general[$c]+ $escuela['no_habilitado'];
														?>
															<td><?php echo $escuela['no_habilitado']; 
															$escuelas[$key] = $escuelas[$key] + $escuela['no_habilitado'];
															$suma = $suma + $escuela['no_habilitado'];
															?></td>
														<?php 
															$c++;
														} 
														$sum_general[$c+1] = $sum_general[$c+1]+$suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php } ?>
											</tr>	
											<tr>
												<th colspan='2'>TOTAL RAP</th>

												<?php
												$suma = 0;
												$c = 0;
												foreach($totales['RAP'] as $key => $escuela) { 
													$sum_general[$c] = $sum_general[$c]+ $escuelas[$key];
													?>

												<th><?php echo $escuelas[$key];
												$suma = $suma + $escuelas[$key];
												?></th>

												<?php 
													$c++;
												} 
												$sum_general[$c+1] = $sum_general[$c+1]+$suma;
												?>
												<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
												<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>

											</tr>
										<?php } ?>




<?php
			foreach($escuelas as $key => $esc) {
				$escuelas[$key] = 0;
			}
			?>




										<?php
										$sum_sum['0'] = 0;
										$sum_sum['1'] = 0;
										if ($si_tipo_otros) { ?>
											<tr>

												<td rowspan= '4' >
													OTROS
												</td>

													<?php
													if ($si_doc_con_obs) { ?>										


														<td>
															Documento con Observaciones
														</td>

														<?php 
														$suma = 0;
														$c = 0;
														foreach($tot_prepostulaciones_documento_con_observaciones as $key => $escuela) { 
															$sum_general[$c] = $sum_general[$c]+ $escuela;
														?>
															<td><?php echo $escuela; 
															$suma = $suma + $escuela;
															$escuelas[$key] = $escuelas[$key] + $escuela;
															?></td>
														<?php 
															$c++;
														} 
															$sum_general[$c+1] = $sum_general[$c+1]+$suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php } ?>


											</tr>
											<tr>
													<?php
													if ($si_rev_pendiente) { ?>										

														<td>
															<?php echo utf8_decode('Pendiente de Revisión'); ?>
														</td>

														<?php 
														$suma = 0;
														$c = 0;
														foreach($tot_prepostulaciones_en_revision as $key => $escuela) { 
															$sum_general[$c] = $sum_general[$c]+ $escuela;
														?>
															<td><?php echo $escuela; 
															$suma = $suma + $escuela;
															$escuelas[$key] = $escuelas[$key] + $escuela;
															?></td>
														<?php 
															$c++;
														} 
															$sum_general[$c+1] = $sum_general[$c+1]+$suma;
														?>
														<?php
												if($si_tipo_otros){
													echo '<td></td>';
												}
												?>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php } ?>
											</tr>

											<tr>
													<?php
													if ($si_sin_act_cuenta) { ?>										

														<td>
															<?php echo utf8_decode('Sin Activación de cuenta'); ?>
														</td>

														<?php 
														$c = 0;
														foreach($totales['RAP'] as $key => $escuela) { ?>
															<td></td>
														<?php 
															$c++;
														} 
														?>
														<td>
														<?php 
														echo $postulantes_sin_activacion_cuenta;
														$sum_general[$c] = $sum_general[$c]+ $postulantes_sin_activacion_cuenta; 
														$sum_sum[0] = $postulantes_sin_activacion_cuenta;
														$sum_general[$c+1] = $sum_general[$c+1]+$postulantes_sin_activacion_cuenta;
														?></td>
														<th style="background-color: #f9f9f9"><?php echo $postulantes_sin_activacion_cuenta; ?></th>
													<?php } ?>
											</tr>	
											<tr>
													<?php
													if ($si_sin_form_guardado) { ?>										

														<td>
															Sin Formulario guardado
														</td>

														<?php 
														$c = 0;
														foreach($totales['RAP'] as $key => $escuela) { ?>
															<td></td>
														<?php 
															$c++;
														} 
														?>
														<td>
														<?php 
														echo $postulantes_sin_formulario_guardado; 
														$sum_general[$c] = $sum_general[$c]+ $postulantes_sin_formulario_guardado; 
														$sum_sum[1] = $postulantes_sin_formulario_guardado;
														$sum_general[$c+1] = $sum_general[$c+1]+$postulantes_sin_formulario_guardado;
														?></td>
														<th style="background-color: #f9f9f9"><?php echo $postulantes_sin_formulario_guardado; ?></th>
													<?php } ?>
											</tr>	
											<tr>
												<th colspan='2' style="background-color: #f9f9f9">TOTAL OTROS</th>

												<?php
												$suma = 0;
												$c = 0;
												foreach($totales['RAP'] as $key => $escuela) { 
													$sum_general[$c] = $sum_general[$c] + $escuelas[$key];
													?>
												<th style="background-color: #f9f9f9"><?php echo $escuelas[$key];
													$suma = $suma + $escuelas[$key];
												?></th>

												<?php 
													$c++;
												}
												$sum_general[$c] = $sum_general[$c]+$sum_sum[1] + $sum_sum[0]; 
												$sum_general[$c+1] = $sum_general[$c+1]+$suma; 
												?>
												<th style="background-color: #f9f9f9"><?php echo $sum_sum[1] + $sum_sum[0]; ?></th>
												<th style="background-color: #f9f9f9"><?php echo $suma+$sum_sum[1] + $sum_sum[0]; ?></th>

											</tr>
										<?php } ?>
										<tr>
											<th colspan="2">TOTAL GENERAL</th>
											<?php
											$totales_generales = 0;
											for($i=0; $i < (count($sum_general)-1); $i++){
												$totales_generales = $totales_generales+($sum_general[$i]/2);
												if($i==(count($sum_general)-2)){
													if($si_tipo_otros){
														echo '<td><strong>'.($sum_general[$i]/2).'</strong></td>';
													}
												}else{
													echo '<td><strong>'.($sum_general[$i]/2).'</strong></td>';
												}
											}
												echo '<td><strong>'.$totales_generales.'</strong></td>';
											?>
										</tr>
								   </tbody>
								</table>
								<br>
							
						</div>														
					</div>
				</div>
			</div>
	</div>
</div>