
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
					
					<div class="row-fluid">
						<div class="col-xs-9">
							<H4>Resumen de Postulaciones por ESCUELA</h4>	
						</div>
						<div class="col-xs-3"><h6 class="pull-right fechaEtapa2">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6></div>		

						<div class="col-xs-12">
								<?php if ($totales) { ?>
									<table class="table table-striped table-bordered table-condensed" id="tabla_datos">
										<tbody>
											<tr>
												<th>ESCUELA</th>
												<th class="carrera_title">CARRERA</th>

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
													if ($cont_carreras < $total['total_carreras']) {?>
														<tr class="tr_escuela_<?php echo $total['escuela_codigo'];?>">
															<?php if($cont_carreras == 0) { 
																?>
																<th rowspan='<?php echo $total['total_carreras'] + 1; ?>'>
																	<a href="javascript:seeCarrera(<?php echo $total['escuela_codigo']; ?>)"><?php echo ($total['nombre_escuela']); ?>
																	</a>
																</th>
															<?php } ?>

															<th class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
															<?php echo ($carrera['nombre_carrera']); ?>
															</th>


												<?php 
												if ($si_form_completado) { ?>
																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['form_completado']; ?>
																</td>
												<?php } ?>

												<?php 
												if ($si_doc_recibida_revision) { ?>
																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['doc_recibida_revision']; ?>
																</td>
												<?php } ?>

												<?php 
												if ($si_cv_completado) { ?>
																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['cv_completado']; ?>
																</td>
												<?php } ?>

												<?php 
												if ($si_doc_aprobada) { ?>
																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['doc_aprobada']; ?>
																</td>
												<?php } ?>

												<?php 
												if ($si_cv_revision) { ?>
																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['cv_revision']; ?>
																</td>
												<?php } ?>

												<?php 
												if ($si_cv_aprobada) { ?>
																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['cv_aprobada']; ?>
																</td>
												<?php } ?>

												<?php 
												if ($si_entrevista) { ?>
																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['entrevista']; ?>
																</td>
												<?php } ?>

												<?php 
												if ($si_habilitado_sin_firma) { ?>

																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['habilitado'] - (($carrera['firma'] - $carrera['csa']) + $carrera['csa']); ?>
																</td>
												<?php } ?>
												<?php 
												if ($si_habilitado_firma) { ?>

																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['firma'] - $carrera['csa']; ?>
																</td>
												<?php } ?>
												<?php 
												if ($si_habilitado_csa) { ?>

																<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
																<?php echo $carrera['csa']; ?>
																</td>
												<?php } ?>
												<?php 
												if ($si_no_habilitado) { ?>

															<td class="<?php echo $key_carrera; ?> hide_carrera td_<?php echo $total['escuela_codigo'];?>">
															<?php echo $carrera['no_habilitado']; ?>
															</td>
												<?php } ?>
												<script>
											    var suma = 0;
											    $('#tabla_datos td.<?php echo $key_carrera; ?>').each(function(){ 
											     	suma += parseInt($(this).text()||0,10); //numero de la celda 
											     	//console.log($(this).text());
											    });

											    $('.total_<?php echo $key_carrera;?>').text(suma);

											    if(parseInt(suma)==0){
											     	$('.<?php echo $key_carrera; ?>').remove();
											     	$('.total_<?php echo $key_carrera;?>').remove();
											    }

												</script>
															<td style="font-weight: bold; font-size: 18px;" class="<?php echo 'total_'.$key_carrera; ?>  hide_carrera td_<?php echo $total['escuela_codigo'];?>">
															</td>
														</tr>
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
														<tr class="tr_escuela_<?php echo $total['escuela_codigo'];?>">

															<th class="carrera_title">
																	TOTAL ESCUELA
															</th>


												<?php 
												if ($si_form_completado) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_form_completado_1; ?></th>

												<?php } ?>

												<?php 
												if ($si_doc_recibida_revision) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_doc_recibida_revision_1; ?></th>

												<?php } ?>

												<?php 
												if ($si_cv_completado) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_cv_completado_1; ?></th>

												<?php } ?>

												<?php 
												if ($si_doc_aprobada) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_doc_aprobada_1; ?></th>

												<?php } ?>

												<?php 
												if ($si_cv_revision) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_cv_revision_1; ?></th>

												<?php } ?>

												<?php 
												if ($si_cv_aprobada) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_cv_aprobada_1; ?></th>

												<?php } ?>

												<?php 
												if ($si_entrevista) { ?>

															<th class="<?php echo $key_escuela; ?>"><?php echo $tot_entrevista_1; ?></th>

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
											    if(parseInt(suma)>0){
											    	$('.total_<?php echo $key_escuela;?>').text(suma);
											    }else{
											    	$(".tr_escuela_<?php echo $key_escuela;?>").hide();
											    }

												</script>
															<th class="total_<?php echo $key_escuela;?>"></th>
														</tr>

											<?php } ?>




											<tr style="font-size: 18px;">
												<th>TOTAL GENERAL</th>

												<th class="carrera_title"></th>
												<?php 
												if ($si_form_completado) { ?>

												<th class="totales_f"><?php echo $tot_form_completado; ?></th>
												<?php } ?>

												<?php 
												if ($si_doc_recibida_revision) { ?>

												<th class="totales_f"><?php echo $tot_doc_recibida_revision; ?></th>
												<?php } ?>

												<?php 
												if ($si_cv_completado) { ?>

												<th class="totales_f"><?php echo $tot_cv_completado; ?></th>
												<?php } ?>

												<?php 
												if ($si_doc_aprobada) { ?>

												<th class="totales_f"><?php echo $tot_doc_aprobada; ?></th>
												<?php } ?>

												<?php 
												if ($si_cv_revision) { ?>

												<th class="totales_f"><?php echo $tot_cv_revision; ?></th>
												<?php } ?>

												<?php 
												if ($si_cv_aprobada) { ?>

												<th class="totales_f"><?php echo $tot_cv_aprobada; ?></th>
												<?php } ?>

												<?php 
												if ($si_entrevista) { ?>

												<th class="totales_f"><?php echo $tot_entrevista; ?></th>
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


<script type="text/javascript">
// <![CDATA[
function seeCarrera(escuela_codigo){
	//$(".hide_carrera").hide();
	//$("."+escuela_codigo).show();
	$( ".td_"+escuela_codigo ).toggle(function(){
		if($(this).is(":visible")){
			$(".carrera_title").show();
		}else{
			$(".carrera_title").hide();
		}
	});

}
$(document).ready(function(){

	//ocultar carrera 

	$(".hide_carrera").hide();
	$(".carrera_title").hide();

	//$(".hide_total_carrera").attr('css', "font-weight: bold; font-size: 18px;")

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
								foreach($totales as $key => $total)
								{  
									$cont_carreras = 0;
										foreach($total as $carreras) { 
											if ($cont_carreras < $total['total_carreras']) {
												if (($carreras['form_completado'] + $carreras['doc_recibida_revision'] + $carreras['cv_completado'] + $carreras['doc_aprobada'] + $carreras['cv_revision'] + $carreras['cv_aprobada'] + $carreras['entrevista'] + $carreras['habilitado'] + $carreras['no_habilitado']) <> 0) {?>

											data.push(["<?php echo ($carreras['nombre_carrera']);?>",<?php echo $carreras['form_completado'] + $carreras['doc_recibida_revision'] + $carreras['cv_completado'] + $carreras['doc_aprobada'] + $carreras['cv_revision'] + $carreras['cv_aprobada'] + $carreras['entrevista'] + $carreras['habilitado'] + $carreras['no_habilitado'];?>]);

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
												if (($carreras['form_completado'] + $carreras['doc_recibida_revision'] + $carreras['cv_completado'] + $carreras['doc_aprobada'] + $carreras['cv_revision'] + $carreras['cv_aprobada'] + $carreras['entrevista'] + $carreras['habilitado'] + $carreras['no_habilitado']) <> 0) {?>

											data.push(["<?php echo ($carreras['nombre_carrera']);?>",<?php echo $carreras['form_completado'] + $carreras['doc_recibida_revision'] + $carreras['cv_completado'] + $carreras['doc_aprobada'] + $carreras['cv_revision'] + $carreras['cv_aprobada'] + $carreras['entrevista'] + $carreras['habilitado'] + $carreras['no_habilitado'];?>]);

											<?php } }
											?>

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
								data.push(["FORM. POST. COMPL.",<?php echo $tot_form_completado;?>]);
								data.push(["DOC. RECIBIDA EN REVISION",<?php echo $tot_doc_recibida_revision;?>]);
								data.push(["CV RAP COMPLETADO",<?php echo $tot_cv_completado;?>]);
								data.push(["DOCUMENTACION APROBADA",<?php echo $tot_doc_aprobada;?>]);
								data.push(["CV RAP Y AUTOEVALUACION COMPL. EN REVISION",<?php echo $tot_cv_revision;?>]);
								data.push(["CV RAP Y AUTOEVALUACION APROBADA",<?php echo $tot_cv_aprobada;?>]);
								data.push(["ENTREVISTA AGENDADA",<?php echo $tot_entrevista;?>]);
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
								data.push(["FORM. POST. COMPL.",<?php echo $tot_form_completado;?>]);
								data.push(["DOC. RECIBIDA EN REVISION",<?php echo $tot_doc_recibida_revision;?>]);
								data.push(["CV RAP COMPLETADO",<?php echo $tot_cv_completado;?>]);
								data.push(["DOCUMENTACION APROBADA",<?php echo $tot_doc_aprobada;?>]);
								data.push(["CV RAP Y AUTOEVALUACION COMPL. EN REVISION",<?php echo $tot_cv_revision;?>]);
								data.push(["CV RAP Y AUTOEVALUACION APROBADA",<?php echo $tot_cv_aprobada;?>]);
								data.push(["ENTREVISTA AGENDADA",<?php echo $tot_entrevista;?>]);
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