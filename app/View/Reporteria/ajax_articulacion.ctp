
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
							<H4>Resumen de Postulaciones por CARRERA</h4>	
						</div>
						<div class="span3" style="width: 100%;"><h6 class="pull-right fechaEtapa2">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6></div>		

						<div class="span12">
								<?php if ($totales) { ?>
									<table class="table table-striped table-bordered" id="tabla_datos">
										<tbody>
											<tr>
												<th>CARRERA</th>
												<?php 
												if ($si_en_revision) { ?>
													<th>EN REVISIÓN</th>
												<?php } ?>
												<?php 
												if ($si_habilitado_sin_firma) { ?>
													<th>HABILITADOS</th>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>
													<th>HABILITADAS FIRMA</th>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>
													<th>HABILITADOS CSA</th>
												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>
													<th>NO HABILITADOS</th>
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
													//validar si las columnas tienen valor y filtro para sumar
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
																<?php echo $carrera['nombre_carrera']; ?>
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

															<th style="background-color: #f9f9f9">
															TOTAL ESTADOS
															</th>
												<?php 
												if ($si_en_revision) { ?>

															<th style="background-color: #f9f9f9"><?php echo $tot_revision; ?></th>

												<?php } ?>
												<?php 
												if ($si_habilitado_sin_firma) { ?>

															<th style="background-color: #f9f9f9"><?php echo $tot_hab - ($tot_firma + $tot_csa); ?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>


															<th style="background-color: #f9f9f9"><?php echo $tot_firma; ?></th>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>


															<th style="background-color: #f9f9f9"><?php echo $tot_csa; ?></th>

												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>

															<th style="background-color: #f9f9f9"><?php echo $tot_nohab; ?></th>
												<?php } ?>

															<th style="background-color: #f9f9f9"><?php echo $tot_nohab + $tot_hab + $tot_revision; ?></th>
														</tr>

									   </tbody>
									</table>

								<?php }  else { ?>
								<h4>NO HAY DATOS PARA GENERAR TABLA</h4>
								<?php } ?>
						</div>														
					</div>
					<br>
					<hr>
					<div class="row-fluid">										
										<H4><span id="img-check-licencia" align="left"></span>&nbsp;Distribución Total de Postulaciones por Carreras:</h4>

						<div class="span9 offset1">
							<?php if ($totales) { ?>
								<?php if($si_torta) { ?>
									<div id="torta2"></div>
								<?php } ?>
								<?php if($si_barra) { ?>
									<div id="barra2"></div>
								<?php } ?>

							<?php } else { ?>
							<h4>NO HAY DATOS PARA GENERAR GRÁFICOS</h4>
							<?php } ?>
						</div>
					</div>


					<br>
					<hr>
					<div class="row-fluid">										
										<H4><span id="img-check-licencia" align="left"></span>&nbsp;Distribución Total de Postulaciones por Estados:</h4>

						<div class="span9 offset1">
							<?php if ($totales) { ?>
								<?php if($si_torta) { ?>
									<div id="torta3"></div>
								<?php } ?>
								<?php if($si_barra) { ?>
									<div id="barra3"></div>
								<?php } ?>

							<?php } else { ?>
							<h4>NO HAY DATOS PARA GENERAR GRÁFICOS</h4>
							<?php } ?>
						</div>
					</div>


				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function(){
	///grafico de tortas para postulaciones por escuela "en revision"

	$('#torta2').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico Total de Postulaciones por Carreras',
					style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '13px',
						letterSpacing: '0px'
					}
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
								width: '50px',
								fontSize: '8px'
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'Postulaciones',
					data: (function() {
								// generate an array of random data
								var data = [];

								<?php 
										foreach($totales as $carrera) { 
												if (($carrera['en_revision'] + $carrera['habilitado'] + $carrera['no_habilitado']) <> 0) { ?>

											data.push(["<?php echo ($carrera['nombre_carrera']);?>",<?php echo $carrera['en_revision'] + $carrera['habilitado'] + $carrera['no_habilitado'];?>]);


										<?php 
												} 
									}?>
								return data;
							})()
						}]
	});	


	$('#barra2').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico Total de Postulaciones por Carreras',
			style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '13px',
						letterSpacing: '0px'
					}
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -90,
                style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '8px',
						letterSpacing: '0px'
					}
            }
        },
        yAxis: {
           allowDecimals: false,
            title: {
                text: 'Postulaciones'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de Postulaciones: <b>{point.y:.1f} unidades</b>'
        },
		colors: [
        '#4572A7', 
        '#AA4643', 
        '#89A54E', 
        '#80699B', 
        '#3D96AE', 
        '#DB843D', 
        '#92A8CD', 
        '#A47D7C', 
        '#B5CA92'
        ],
		 plotOptions: {
            column: {
                colorByPoint: true
            },
			 series: {
                borderWidth: 2,
                borderColor: 'black'
            }
        },
         series: [{
            name: 'Postulaciones',
            data: (function() {
								// generate an array of random data
								var data = [];
								<?php 
										foreach($totales as $carrera) { 
												if (($carrera['en_revision'] + $carrera['habilitado'] + $carrera['no_habilitado']) <> 0) { ?>

											data.push(["<?php echo ($carrera['nombre_carrera']);?>",<?php echo $carrera['en_revision'] + $carrera['habilitado'] + $carrera['no_habilitado'];?>]);


										<?php 
												} 
									}?>
								return data;
							})(),
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                x: 4,
                y: 10,
              style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '13px',
						letterSpacing: '0px'
					}
            }
        }]
    });


	$('#torta3').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico Total de Postulaciones por Estados',
					style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '13px',
						letterSpacing: '0px'
					}
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
								width: '50px',
								fontSize: '8px'
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'Postulaciones',
					data: (function() {
								// generate an array of random data
								var data = [];
								data.push(["En Revisión",<?php echo $tot_revision;?>]);
								data.push(["Habilitados",<?php echo $tot_hab - ($tot_firma + $tot_csa);?>]);
								data.push(["Habilitados Firma",<?php echo $tot_firma;?>]);
								data.push(["Habilitados CSA",<?php echo $tot_csa;?>]);
								data.push(["No Habilitados",<?php echo $tot_nohab;?>]);
								return data;
							})()
						}]
	});	
	
	$('#barra3').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico Total de Postulaciones por Estados',
			style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '13px',
						letterSpacing: '0px'
					}
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -90,
                style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '8px',
						letterSpacing: '0px'
					}
            }
        },
        yAxis: {
           allowDecimals: false,
            title: {
                text: 'Postulaciones'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de Postulaciones: <b>{point.y:.1f} unidades</b>'
        },
		colors: [
        '#4572A7', 
        '#AA4643', 
        '#89A54E', 
        '#80699B', 
        '#3D96AE', 
        '#DB843D', 
        '#92A8CD', 
        '#A47D7C', 
        '#B5CA92'
        ],
		 plotOptions: {
            column: {
                colorByPoint: true
            },
			 series: {
                borderWidth: 2,
                borderColor: 'black'
            }
        },
         series: [{
            name: 'Postulaciones',
            data: (function() {
								// generate an array of random data
								var data = [];
								data.push(["En Revisión",<?php echo $tot_revision;?>]);
								data.push(["Habilitados",<?php echo $tot_hab - ($tot_firma + $tot_csa);?>]);
								data.push(["Habilitados Firma",<?php echo $tot_firma;?>]);
								data.push(["Habilitados CSA",<?php echo $tot_csa;?>]);
								data.push(["No Habilitados",<?php echo $tot_nohab;?>]);
								return data;
							})(),
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                x: 4,
                y: 10,
              style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '13px',
						letterSpacing: '0px'
					}
            }
        }]
    });

})
// ]]>
</script>