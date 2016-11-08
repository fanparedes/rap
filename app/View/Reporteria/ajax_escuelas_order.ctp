<div class="row-fluid">
	<div class="span9">
		<H4>Resumen de Postulaciones por ESCUELA</h4>	
	</div>
	<div class="span3" style="width: 100%;"><h6 class="pull-right fechaEtapa2">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6></div>		

	<div class="span12">
			<?php if ($totales) { ?>
				<table class="table table-striped table-bordered" id="tabla_datos">
					<tbody>
						<tr>
							<th>ESCUELA</th>
							<th>
							<a href="javascript:orderEscuela('carrera', 'desc');" class="carrera" >CARRERA <i class="icon-caret-down flechita"></i></a>
							<a href="javascript:orderEscuela('carrera', 'asc');" class="carrera" style="display: none;" >CARRERA <i class="icon-caret-down flechita2"></i></a>
							</th>
							<?php 
							if ($si_en_revision) { ?>
								<th>
							<?php if($column_rs=='en_revision' && $order_rs=='asc'){ ?>
								<a href="javascript:orderEscuela('en_revision', 'desc');" class="en_revision">EN REVISIÓN <i class="icon-caret-down flechita2"></i></a>
							<?php }elseif ($column_rs=='en_revision' && $order_rs=='desc'){ ?>
								<a href="javascript:orderEscuela('en_revision', 'asc');" class="en_revision" style="display: none;" >EN REVISIÓN <i class="icon-caret-up flechita"></i></a></th>
							<?php }
							} ?>
							<?php 
							if ($si_habilitado_sin_firma) { ?>
								<th><a href="javascript:orderEscuela('habilitado_sin_firma', 'desc');" class="habilitado_sin_firma" >HABILITADAS <i class="icon-caret-down flechita2"></i></a>
								<a href="javascript:orderEscuela('habilitado_sin_firma', 'asc');" class="habilitado_sin_firma" style="display: none;" >HABILITADAS <i class="icon-caret-up flechita"></i></a>
								</th>
							<?php } ?>
							<?php 
							if ($si_habilitado_firma) { ?>
								<th><a href="javascript:orderEscuela('habilitado_firma', 'desc');" class="habilitado_firma" >HABILITADAS FIRMA <i class="icon-caret-down flechita2"></i></a>
								<a href="javascript:orderEscuela('habilitado_firma', 'asc');" class="habilitado_firma" style="display: none;" >HABILITADAS FIRMA <i class="icon-caret-up flechita"></i></a>
								</th>
							<?php } ?>
							<?php 
							if ($si_habilitado_csa) { ?>
								<th><a href="javascript:orderEscuela('habilitado_csa', 'desc');" class="habilitado_csa" >HABILITADAS CSA <i class="icon-caret-down flechita2"></i></a>
								<a href="javascript:orderEscuela('habilitado_csa', 'asc');" class="habilitado_csa" style="display: none;" >HABILITADAS CSA <i class="icon-caret-up flechita"></i></a>
								</th>
							<?php } ?>
							<?php 
							if ($si_no_habilitado) { ?>
								<th><a href="javascript:orderEscuela('no_habilitado', 'desc');" class="no_habilitado" >NO HABILITADAS <i class="icon-caret-down flechita2"></i></a>
								<a href="javascript:orderEscuela('no_habilitado', 'asc');" class="no_habilitado" style="display: none;" >NO HABILITADAS <i class="icon-caret-up flechita"></i></a>
								</th>
							<?php } ?>
								<th><a href="javascript:orderEscuela('total', 'desc');" class="total" >TOTAL GENERAL <i class="icon-caret-down flechita2"></i></a>
								<a href="javascript:orderEscuela('total', 'asc');" class="total" style="display: none;" >TOTAL GENERAL <i class="icon-caret-up flechita"></i></a>
								</th>
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
								if ($cont_carreras < $total['total_carreras']) {?>
									<tr>
										<?php if($cont_carreras == 0) { 
											?>
											<td rowspan='<?php echo $total['total_carreras'] + 1; ?>'>
											<?php echo utf8_encode($total['nombre_escuela']); ?>
											</td>
										<?php } ?>

										<td>
										<?php echo utf8_encode($carrera['nombre_carrera']); ?>
										</td>
							<?php 
							if ($si_en_revision) { ?>
											<td class="<?php echo $key_carrera; ?>">
											<?php echo $carrera['en_revision']; ?>
											</td>
							<?php } ?>
							<?php 
							if ($si_habilitado_sin_firma) { ?>

											<td class="<?php echo $key_carrera; ?>">
											<?php echo $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']); ?>
											</td>
							<?php } ?>
							<?php 
							if ($si_habilitado_firma) { ?>

											<td class="<?php echo $key_carrera; ?>">
											<?php echo $carrera['firma'] - $carrera['csa']; ?>
											</td>
							<?php } ?>
							<?php 
							if ($si_habilitado_csa) { ?>

											<td class="<?php echo $key_carrera; ?>">
											<?php echo $carrera['csa']; ?>
											</td>
							<?php } ?>
							<?php 
							if ($si_no_habilitado) { ?>

										<td class="<?php echo $key_carrera; ?>">
										<?php echo $carrera['no_habilitado']; ?>
										</td>
							<?php } ?>
							<script>
							    var suma = 0;
							    $('#tabla_datos td.<?php echo $key_carrera; ?>').each(function(){ 
							     suma += parseInt($(this).text()||0,10); 
							    })
							    $('.total_<?php echo $key_carrera;?>').text(suma);
							</script>
										<td style="font-weight: bold; font-size: 18px;" class="<?php echo 'total_'.$key_carrera; ?>">
										</td>
									</tr>
									<?php
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
									<tr style="font-weight: bold; font-size: 18px;">

										<th>
										TOTAL ESCUELA
										</th>
							<?php 
							if ($si_en_revision) { ?>

										<th class="<?php echo $key_escuela; ?>"><?php echo $tot_revision_1; ?></th>

							<?php } ?>
							<?php 
							if ($si_habilitado_sin_firma) { ?>

										<th class="<?php echo $key_escuela; ?>"><?php echo $tot_hab_1 - ($tot_firma_1 + $tot_csa_1); ?></th>
							<?php } ?>
							<?php 
							if ($si_habilitado_firma) { ?>


										<th class="<?php echo $key_escuela; ?>"><?php echo $tot_firma_1; ?></th>
							<?php } ?>
							<?php 
							if ($si_habilitado_csa) { ?>


										<th class="<?php echo $key_escuela; ?>"><?php echo $tot_csa_1; ?></th>

							<?php } ?>
							<?php 
							if ($si_no_habilitado) { ?>

										<th class="<?php echo $key_escuela; ?>"><?php echo $tot_nohab_1; ?></th>
							<?php } ?>
							<script>
							    var suma = 0;
							    $('#tabla_datos th.<?php echo $key_escuela; ?>').each(function(){ //filas con clase 'dato', especifica una clase, asi no tomas el nombre de las columnas
							     suma += parseInt($(this).text()||0,10); //numero de la celda 


							    })
							    $('.total_<?php echo $key_escuela;?>').text(suma);

							</script>
										<th class="<?php echo 'total_'.$key_escuela; ?>"></th>
									</tr>

						<?php } ?>




						<tr style="font-size: 18px;">
							<th colspan="2">TOTAL GENERAL</th>
							<?php 
							if ($si_en_revision) { ?>

							<th class="totales_f"><?php echo $tot_revision; ?></th>
							<?php } ?>
							<?php 
							if ($si_habilitado_sin_firma) { ?>

							<th class="totales_f"><?php echo $tot_hab - ($tot_firma + $tot_csa); ?></th>
							<?php } ?>
							<?php 
							if ($si_habilitado_firma) { ?>

							<th class="totales_f"><?php echo $tot_firma; ?></th>
							<?php } ?>
							<?php 
							if ($si_habilitado_csa) { ?>

							<th class="totales_f"><?php echo $tot_csa; ?></th>
							<?php } ?>
							<?php 
							if ($si_no_habilitado) { ?>

							<th class="totales_f"><?php echo $tot_nohab; ?></th>
							<?php } ?>
							<script>
							    var suma = 0;
							    $('#tabla_datos th.totales_f').each(function(){ //filas con clase 'dato', especifica una clase, asi no tomas el nombre de las columnas
							     suma += parseInt($(this).text()||0,10); //numero de la celda 


							    })
							    $('.total_total').text(suma);
							</script>											
							<th class="total_total"></th>
						</tr>
				   </tbody>
				</table>

			<?php }  else { ?>
			<h4>NO HAY DATOS PARA GENERAR TABLA</h4>
			<?php } ?>
	</div>														
</div>