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
	<div id="collapseReporte" class="accordion-body">
		<div class="accordion-inner">
			<!-- COMIENZA EL REPORTE -->
			<div class="row-fluid">
				<div class="col-xs-9">
							<H4>Resumen de Postulaciones por ESCUELA</h4>	
						</div>
						<div class="col-xs-3" style="width: 100%;"><h6 class="pull-right fechaEtapa2">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6></div>		

						<div class="col-xs-12">
								<?php if ($totales) {

											$tot_revision = 0;
											$tot_hab = 0;
											$tot_nohab = 0;
											$tot_firma = 0;
											$tot_csa = 0;
											$tot = 0;
											foreach($totales as $key_escuela => $total) {
												?>
												<h4><?php echo $total['nombre_escuela']; ?></h4>
												<table class="table table-striped display table-bordered table_escuela" data-order='[[ 6, "asc" ], [ 0, "asc" ]]'>
												<thead>
													<tr>
														<th>CARRERA</th>
														<?php 
														if ($si_en_revision) { ?>
															<th>EN REVISIÓN</th>
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
													</thead>
													<tbody
												<?php
												$cont_carreras = 0;
												$tot_revision_1 = 0;
												$tot_hab_1 = 0;
												$tot_nohab_1 = 0;
												$tot_firma_1 = 0;
												$tot_csa_1 = 0;
												$tot_1 = 0;
												foreach($total as $key_carrera =>  $carrera) { 
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
															<td class="title_<?php echo $key_carrera; ?>">
															<?php echo ($carrera['nombre_carrera']); ?>
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
												
															<td style="font-weight: bold; font-size: 18px;" class="<?php echo 'total_'.$key_carrera; ?>">
																<?php echo $sum_total_carrera; ?>
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
    $('th.<?php echo $key_escuela; ?>').each(function(){ //filas con clase 'dato', especifica una clase, asi no tomas el nombre de las columnas
     suma += parseInt($(this).text()||0,10); //numero de la celda 


    })
    $('.total_<?php echo $key_escuela;?>').text(suma);

												</script>
															<th class="<?php echo 'total_'.$key_escuela; ?>"></th>
														</tr>
												</tbody>
												</table>

											<?php } ?>
											<hr>
											<table class="table table-striped table-bordered">
												<thead>
													<tr>
														<th></th>
														<?php 
														if ($si_en_revision) { ?>
															<th>EN REVISIÓN</th>
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
													</thead>
													<tbody
											<tr style="font-size: 18px;">
												<th >TOTAL GENERAL</th>
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
    $('th.totales_f').each(function(){ //filas con clase 'dato', especifica una clase, asi no tomas el nombre de las columnas
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
					<br>
					<hr>
					<H4 style="margin-top:50px;"><span id="img-check-licencia" align="left"></span>&nbsp;Distribución Total de Postulaciones "En Revisión":</h4>
					<div class="row-fluid">										
						<div class="span6">
								<table class="table table-striped table-bordered">
									<tbody>
										<tr>
											<th>ESCUELA</th>
											<th>POSTULANTES</th>
										</tr>

										<?php 
										$tot = 0;
										foreach($totales_1 as $total) { 
											
										?>
										<tr>
											<td>
											<?php
											if(isset($escuelas_rs[$total['key_escuela']]) && $escuelas_rs[$total['key_escuela']]=='1'){
												echo '<strong>'.($total['nombre_escuela']).'</strong>'; 
											}else{
												echo ($total['nombre_escuela']); 
											}

											?>
											</td>
											<td>
											<?php 
											if(isset($escuelas_rs[$total['key_escuela']]) && $escuelas_rs[$total['key_escuela']]=='1'){
												echo '<strong>'.($total['en_revision']).'</strong>'; 
											}else{
												echo ($total['en_revision']); 
											}

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
						<div class="span5 offset1">
							<?php if ($tot <> 0) { ?>
								<?php if($si_torta) { ?>
									<div id="torta1"></div>
								<?php } ?>
								<?php if($si_barra) { ?>
									<div id="barra1"></div>
								<?php } ?>
							<?php } else { ?>
							<h4>NO HAY DATOS PARA GENERAR GRÁFICOS</h4>
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

//ordernar columnas
function orderEscuela(column, order){

    var formulario = $('#EscuelasEscuelasForm').serialize();
	var desde		= $('#EscuelasFechaDesde').val();
	var hasta		= $('#EscuelasFechaHasta').val();
	
	var split_desde = desde.split('/');
	var split_hasta = hasta.split('/');
	var date_desde = new Date(split_desde[2], (split_desde[1] - 1), split_desde[0]); //Y M D 
	var timestamp_desde = date_desde.getTime();
	var date_hasta = new Date(split_hasta[2], (split_hasta[1] - 1), split_hasta[0]); //Y M D 
	var timestamp_hasta = date_hasta.getTime();
	if(timestamp_desde >= timestamp_hasta)
	{
		
		$('#EscuelasFechaDesde').val('');
		$('#EscuelasFechaHasta').val('');
		alert('La fecha de inicio debe ser menor a la actual');
		return false;
	}else{
	
		if($('#EscuelasFechaDesde').val() == '' || $('#EscuelasFechaHasta').val() == '' )
		{
		
			alert('Debe seleccionar las fechas de inicio y fin');
			return false;
		}
		if($('input.tortas').is(':checked') == false && $('input.barras').is(':checked') == false)
		{
			alert('Debe seleccionar un tipo de grafico');
			return false;
		}		
		url = webroot + 'administracion/reporteria/ajax_escuelas_order';
	    $('#divEscuela').html('');
	    $.ajax({
	        url: url,
	        type: "POST",
	        data: formulario+ '&order=' + order+ '&column=' + column,
	        success: function(result) {
	            $('#divEscuela').html(result);
	        } // <-- add this
	    });
	}
}
// <![CDATA[
$(document).ready(function(){

	///grafico de tortas para postulaciones por escuela "en revision"
	$('#torta1').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico Total de Postulaciones "En Revisión"',
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
								<?php foreach($totales_1 as $key => $total)
								{ ?>
								data.push(["<?php echo ($totales_1[$key]['nombre_escuela']);?>",<?php echo $totales_1[$key]['en_revision'];?>]);
								<?php } ?>
								return data;
							})()
						}]
	});


	$('#barra1').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico Total de Postulaciones "En Revisión"',
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
								<?php foreach($totales_1 as $key => $total)
								{ ?>
								data.push(["<?php echo ($totales_1[$key]['nombre_escuela']);?>",<?php echo $totales_1[$key]['en_revision'];?>]);
								<?php } ?>
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
								foreach($totales as $key => $total)
								{  
									$cont_carreras = 0;
										foreach($total as $carreras) { 
											if ($cont_carreras < $total['total_carreras']) {
												if (($carreras['en_revision'] + $carreras['habilitado'] + $carreras['no_habilitado']) <> 0) {?>

											data.push(["<?php echo ($carreras['nombre_carrera']);?>",<?php echo $carreras['en_revision'] + $carreras['habilitado'] + $carreras['no_habilitado'];?>]);

											<?php } }
											?>

										<?php 
										$cont_carreras++;
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
								foreach($totales as $key => $total)
								{  
									$cont_carreras = 0;
										foreach($total as $carreras) { 
											if ($cont_carreras < $total['total_carreras']) {

												if (($carreras['en_revision'] + $carreras['habilitado'] + $carreras['no_habilitado']) <> 0) {?>

											data.push(["<?php echo ($carreras['nombre_carrera']);?>",<?php echo $carreras['en_revision'] + $carreras['habilitado'] + $carreras['no_habilitado'];?>]);

											<?php }
											} ?>

										<?php 
										$cont_carreras++;
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