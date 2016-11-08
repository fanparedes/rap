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
								<table class="table table-striped table-bordered">
									<tbody>
										<tr>
											<th>TIPO POSTULACIÓN</th>
											<th>ESTADO</th>

											<?php
											foreach($totales['AH'] as $escuela) { ?>

											<th><?php echo utf8_encode($escuela['nombre_escuela']); ?></th>

											<?php } ?>

											<th>OTROS</th>
											<th>TOTAL GENERAL</th>
										</tr>


										<?php
										$sum_general = array();
										if ($si_tipo_escuelas) { ?>
											<tr>

												<td rowspan= '3' >
													ESCUELAS
												</td>
													<?php
													if ($si_en_revision) { ?>										
														<td>
															En Revisión
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
														<td></td>
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
													<td></td>
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
													<td></td>
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

												<td></td>
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

												<td rowspan= '3' >
													ARTICULACIÓN
												</td>
													<?php
													if ($si_en_revision) { ?>										
														<td>
															En Revisión
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
														<td></td>
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
													<td></td>
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
													<td></td>
													<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
												<?php } ?>
											</tr>

											<tr>
												<th colspan='2'>TOTAL ARTICULACIÓN</th>

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

												<td></td>
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

												<td rowspan= '3' >
													RAP
												</td>
													<?php
													if ($si_en_revision) { ?>										


														<td>
															En Revisión
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
														<td></td>
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
														<td></td>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php 
														
													} 
													?>
											</tr>

											<tr>
													<?php
													if ($si_habilitado) { ?>										


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
														<td></td>
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
												<td></td>
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
														foreach($tot_prepostulaciones_documento_con_observaciones as $key => $escuela) { ?>
															<td><?php echo $escuela; 
															$suma = $suma + $escuela;
															$escuelas[$key] = $escuelas[$key] + $escuela;
															?></td>
														<?php } ?>
														<td></td>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php } ?>


											</tr>
											<tr>
													<?php
													if ($si_rev_pendiente) { ?>										

														<td>
															Pendiente de Revisión
														</td>

														<?php 
														$suma = 0;
														foreach($tot_prepostulaciones_en_revision as $key => $escuela) { ?>
															<td><?php echo $escuela; 
															$suma = $suma + $escuela;
															$escuelas[$key] = $escuelas[$key] + $escuela;
															?></td>
														<?php } ?>
														<td></td>
														<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>
													<?php } ?>
											</tr>

											<tr>
													<?php
													if ($si_sin_act_cuenta) { ?>										

														<td>
															Sin Activación de cuenta
														</td>

														<?php 
														foreach($totales['RAP'] as $key => $escuela) { ?>
															<td></td>
														<?php } ?>
														<td><?php echo $postulantes_sin_activacion_cuenta; 
														$sum_sum['0'] = $postulantes_sin_activacion_cuenta;?></td>
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
														foreach($totales['RAP'] as $key => $escuela) { ?>
															<td></td>
														<?php } ?>
														<td><?php echo $postulantes_sin_formulario_guardado; 
														$sum_sum['1'] = $postulantes_sin_formulario_guardado;?></td>
														<th style="background-color: #f9f9f9"><?php echo $postulantes_sin_formulario_guardado; ?></th>
													<?php } ?>
											</tr>	
											<tr>
												<th colspan='2' style="background-color: #f9f9f9">TOTAL OTROS</th>

												<?php
												$suma = 0;
												foreach($totales['RAP'] as $key => $escuela) { ?>

												<th style="background-color: #f9f9f9"><?php echo $escuelas[$key];
												$suma = $suma + $escuelas[$key];
												?></th>

												<?php } ?>
												<th style="background-color: #f9f9f9"><?php echo $sum_sum['1'] + $sum_sum['0']; ?></th>
												<th style="background-color: #f9f9f9"><?php echo $suma; ?></th>

											</tr>
										<?php } ?>
										<tr>
											<th colspan="2">TOTAL GENERAL</th>
											<?php
											for($i=0; $i < count($sum_general); $i++){
												echo '<td>'.$sum_general[$i].'</td>';
											}
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