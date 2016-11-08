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
/* ]]> */
</style>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function(){
	///grafico de tortas para postulaciones por carrera
	$('#torta1').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico Total de postulaciones activas por carrera',
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
								<?php for($i = 0 ;$i<count($grafico_postulacion_carrera);$i++)
								{ ?>
								data.push(["<?php echo $grafico_postulacion_carrera[$i]['Carrera']['carrera'];?>",<?php echo $grafico_postulacion_carrera[$i]['Carrera']['cantidad'];?>]);
								<?php } ?>
								return data;
							})()
						}]
	});
	
	//grafico tortas de postulaciones por sede
	$('#torta2').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico Total de postulaciones activas por sedes',
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
								fontSize: '8px',
								width: '50px'
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
								<?php for($i = 0 ;$i<count($grafico_postulaciones_sede);$i++)
								{ ?>
								data.push(["<?php echo $grafico_postulaciones_sede[$i]['Sede']['sede'];?>",<?php echo $grafico_postulaciones_sede[$i]['Sede']['cantidad'];?>]);
								<?php } ?>
								return data;
							})()
						}]
	});
	//grafico tortas de totales
	$('#torta3').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico Resumen de postulaciones',
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
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'Totales',
					data: (function() {
								// generate an array of random data
								var data = [];
								<?php for($i = 0 ;$i<count($totales);$i++)
								{ ?>
								data.push(["<?php echo $totales[$i]['nombre'];?>",<?php echo $totales[$i]['totales'];?>]);
								<?php } ?>
								return data;
							})()
						}]
	});
	//grafico tortas de postulaciones de sedes por carrera

	//grafico de barras pertenece a los totales
	$('#barra3').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico Resumen de postulaciones',
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
						fontSize: '9px',
						letterSpacing: '0px'
					}
            }
        },
        yAxis: {
           allowDecimals: false,
            title: {
                text: 'Cantidad (unidades)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Totales: <b>{point.y:.1f} unidades</b>'
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
            name: 'Totales',
            data: (function() {
								// generate an array of random data
								var data = [];
								<?php for($i = 0 ;$i<count($totales);$i++)
								{ ?>
								data.push(["<?php echo $totales[$i]['nombre'];?>",<?php echo $totales[$i]['totales'];?>]);
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
	
	//grafico de barras pertenece a las postulaciones por carreras
	$('#barra1').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico Total de postulaciones activas por carrera',
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
                text: 'Cantidad (unidades)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de postulaciones: <b>{point.y:.1f} unidades</b>'
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
								<?php for($i = 0 ;$i<count($grafico_postulacion_carrera);$i++)
								{ ?>
								data.push(["<?php echo $grafico_postulacion_carrera[$i]['Carrera']['carrera'];?>",<?php echo $grafico_postulacion_carrera[$i]['Carrera']['cantidad'];?>]);
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
	
	//grafico de barras pertenece a las postulaciones por sedes
	$('#barra2').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico Total de postulaciones activas por sedes',
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
                text: 'Cantidad (unidades)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de postulaciones: <b>{point.y:.1f} unidades</b>'
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
								<?php for($i = 0 ;$i<count($grafico_postulaciones_sede);$i++)
								{ ?>
								data.push(["<?php echo $grafico_postulaciones_sede[$i]['Sede']['sede'];?>",<?php echo $grafico_postulaciones_sede[$i]['Sede']['cantidad'];?>]);
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
	
	
	
	
})
// ]]>
</script>
<script type="text/javascript">
// <![CDATA[
$('#barra4').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico desglose total de postulaciones activas de sedes por carreras',
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
                text: 'Cantidad (unidades)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de postulaciones: <b>{point.y:.1f} unidades</b>'
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
            name: 'ENTREVISTAS',
            data: (function() {
								// generate an array of random data
								var data = [];
								<?php for($i = 0 ;$i<count($grafico_postulaciones_sede);$i++)
								{ ?>
								data.push(["<?php echo $grafico_postulaciones_sede[$i]['Sede']['sede'];?>",<?php echo $grafico_postulaciones_sede[$i]['Sede']['cantidad'];?>]);
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

	$('#torta4').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico desglose total de postulaciones activas de sedes por carreras',
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
								<?php for($i = 0 ;$i<count($grafico_postulaciones_sede);$i++)
								{ ?>
								data.push(["<?php echo $grafico_postulaciones_sede[$i]['Sede']['sede'];?>",<?php echo $grafico_postulaciones_sede[$i]['Sede']['cantidad'];?>]);
								<?php } ?>
								return data;
							})()
						}]
	});

// ]]>
</script>
<script type="text/javascript">
// <![CDATA[
$('#barra5').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico desglose total de postulaciones activas de carreras por sedes',
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
                text: 'Cantidad (unidades)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de postulaciones: <b>{point.y:.1f} unidades</b>'
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
            name: 'ENTREVISTAS',
            data: (function() {
								// generate an array of random data
								var data = [];
								<?php for($i = 0 ;$i<count($grafico_postulacion_carrera);$i++)
								{ ?>
								data.push(["<?php echo $grafico_postulacion_carrera[$i]['Carrera']['carrera'];?>",<?php echo $grafico_postulacion_carrera[$i]['Carrera']['cantidad'];?>]);
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

	$('#torta5').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico desglose total de postulaciones activas de carreras por sedes',
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
								fontSize: '8px',
								width: '60px'
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
								<?php for($i = 0 ;$i<count($grafico_postulacion_carrera);$i++)
								{ ?>
								data.push(["<?php echo $grafico_postulacion_carrera[$i]['Carrera']['carrera'];?>",<?php echo $grafico_postulacion_carrera[$i]['Carrera']['cantidad'];?>]);
								<?php } ?>
								return data;
							})()
						}]
	});

// ]]>
</script>

<div class="row-fluid">
	<div class="span10 offset1">	<br>				
			<div id="collapseReporte" class="accordion-body">
				<div class="accordion-inner">
					<!-- COMIENZA EL REPORTE -->
					<div class="row-fluid">
						<div class="span9">
							<H4>Resumen de Postulaciones</h4>	
						</div>
						<div class="span3" style="width: 903px;"><h6 class="pull-right fechaEtapa2">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6></div>																
					</div>
					<br>
					<div class="row-fluid">
						<div class="span3">
							<div class="form-inline"><label><i class="icon-stop"></i> <strong>Activos:</strong></label><?php echo $totales[0]['totales'] ;?></div><br>
							<div class="form-inline"><label><i class="icon-stop"></i>  <strong>Finalizadas:</strong></label> <?php echo $totales[1]['totales'] ;?></div>
						</div>
						<div class="span3">
							<div class="form-inline"><label><i class="icon-stop"></i> <strong>Rechazados:</strong></label><?php echo $totales[2]['totales'] ;?></div><br>
							<div class="form-inline"><label><i class="icon-stop"></i> <strong>Deserciones:</strong></label> <?php echo $desertor ;?></div>
						</div>
						<div class="span5 offset1">
							<?php if(isset($barras) && $barras == 1 && ! empty($totales)) :?>
							<div id="barra3"></div>
							<?php else :?>
						
							<?php endif ; ?>
							<?php if(isset($tortas) && $tortas == 1 && ! empty($totales)) :?>
							<div id="torta3"></div>
							<?php else :?>
							
							<?php endif ; ?>	
						</div>
					</div>
					<hr>
					<div class="row-fluid">
						<H4 style="margin-top:50px;"><span id="img-check-licencia" align="left"></span>&nbsp;Total de postulaciones activas por sede:</h4>
						<!--<H4 style="margin-top:50px;"><span id="img-check-licencia" align="left"><a href="#" id="dialog1"><img src="<?php echo $this->webroot;?>img/test-info-icon.png" alt="Texto proporcionado por Duoc" style="width: 20px; height: 20px;"></a></span>&nbsp;Total de postulaciones activas por sede:</h4>-->
					</div>
					<div class="row-fluid">										
						<div class="span6">
							<?php $rellenarUltimaFila=0; ?>
							<?php if (! empty($total_sedes)) :?>
								<table class="table table-striped table-bordered">
									<tbody>
										 <?php foreach($total_sedes as $key => $sede) :?>
										 <tr id ="<?php echo $key ;?>">
											 <?php foreach($sede as $clave => $sede_lista) :?>
											 <?php if ($clave% 2 ==0) :?>
												 <td><?php echo /*utf8_encode*/($sede_lista['Sede']['sede']) ;?></td>
												 <td><?php echo /*utf8_encode*/($sede_lista['Sede']['cantidad']) ;?></td>
												 <?php $rellenarUltimaFila=1; ?>
											 <?php else :?>
												
												 <td><?php echo /*utf8_encode*/($sede_lista['Sede']['sede']) ;?></td>
												 <td><?php echo /*utf8_encode*/($sede_lista['Sede']['cantidad']) ;?></td>
												  <?php $rellenarUltimaFila=0; ?>
												 
											 <?php endif ;?>
											  <?php endforeach ;?>
											   <?php if($rellenarUltimaFila) :?>
											 <td>&nbsp;</td><td>&nbsp;</td>
											 <?php endif ;?>
										 </tr>
										 <?php endforeach ;?>
								   </tbody>
								</table>
								 <?php else :?>
							 <p>Sin resultados</p>
							 <?php endif ;?>									
						</div>
						<div class="span5 offset1">
							<?php if(isset($barras ) && $barras == 1 && ! empty($grafico_postulaciones_sede)) :?>
							<div id="barra2"></div>
							<?php else :?>
						
							<?php endif ; ?>
							<?php if(isset($tortas ) && $tortas == 1 && ! empty($grafico_postulaciones_sede)) :?>
							<div id="torta2"></div>
							<?php else :?>
							
							<?php endif ; ?>	
						</div>
					</div>
					<hr>
					<!--<H4 style="margin-top:50px;"><span id="img-check-licencia" align="left"><a href="#" id="dialog2"><img src="<?php echo $this->webroot;?>img/test-info-icon.png" alt="Texto proporcionado por Duoc" style="width: 20px; height: 20px;"></a></span>&nbsp;Total de postulaciones activas por carrera:</h4>-->	
					<H4 style="margin-top:50px;"><span id="img-check-licencia" align="left"></span>&nbsp;Total de postulaciones activas por carrera:</h4>
					<div class="row-fluid">										
						<div class="span6"><br>
						
							<?php $rellenarUltimaFila=0; ?>
						
							 <?php if (! empty($total_carreras)) :?>
								<table class="table table-striped table-bordered">
									<tbody><?php $par = 0; ?>
										 <?php foreach($total_carreras as $key2 => $carrera) :?>					
										 <tr>
											 <?php foreach($carrera as $llave =>  $carrera_lista) : ?>
												 <?php if ($llave% 2 == 0) :?>
												 	
													 <td width="40%" ><?php echo /*utf8_encode*/($carrera_lista['Carrera']['carrera']);?></td>
													 <td width="10%"><?php echo /*utf8_encode*/($carrera_lista['Carrera']['cantidad']);?></td>
													 
													 <?php $rellenarUltimaFila=1;?>
													 
												 <?php else :?>
													
													 <td width="40%"><?php echo /*utf8_encode*/($carrera_lista['Carrera']['carrera']);?></td>
													 <td width="10%"><?php echo /*utf8_encode*/($carrera_lista['Carrera']['cantidad']);?></td>
													
													<?php $rellenarUltimaFila=0 ;?>
													 
												 <?php endif ;?>
												 
											 <?php endforeach ;?>
											 
											 <?php if($rellenarUltimaFila) :?>
											 <td>&nbsp;</td><td>&nbsp;</td>
											 <?php endif ;?>
										 </tr>
										 <?php endforeach ;?>
								   </tbody>
								</table>
								 <?php else :?>
							 <p>Sin resultados</p>
							 <?php endif ;?>
						</div>
						<div class="span5 offset1">
							<?php if(isset($barras) && $barras == 1 && ! empty($grafico_postulacion_carrera)) :?>
							<div id="barra1"></div>
							<?php else :?>
						
							<?php endif ; ?>
							<?php if(isset($tortas) && $tortas == 1 && ! empty($grafico_postulacion_carrera)) :?>
							<div id="torta1"></div>
							<?php else :?>
							
							<?php endif ; ?>
						</div>
					</div>
					<hr>
					<div class="row-fluid">
						
						<H4 style="margin-top:50px;"><span id="img-check-licencia" align="left"></span>&nbsp;Desglose total de postulaciones activas de carreras por sedes:</h4>
						<!--<H4 style="margin-top:50px;"><span id="img-check-licencia" align="left"><a href="#" id="dialog3"><img src="<?php echo $this->webroot;?>img/test-info-icon.png" alt="Texto proporcionado por Duoc" style="width: 20px; height: 20px;"></a></span>&nbsp;Desglose total de postulaciones activas de carreras por sedes:</h4>-->
						<ul id="tabla2">
						<?php if (! empty($estadistica_por_carrera)) :?>
							 <?php foreach($estadistica_por_carrera as $key1 => $carrera_datos) :?>
							 <li>
									<div><b><h5><?php echo /*utf8_encode*/($carrera_datos['Carrera']['carrera']) ;?>: <?php echo $carrera_datos['Carrera']['cantidad'] ;?></h5></div></b>
									<table class="table table-striped table-bordered">
									<tbody>
										 <?php foreach($carrera_datos['Sede'] as $key2 => $sede) :?>
										 <tr id ="<?php echo $key2 ;?>">
											 <?php if ($key2% 2 ==0) :?>
												 <td><?php echo /*utf8_encode*/($sede['sede']);?></td>
												 <td><?php echo /*utf8_encode*/($sede['cantidad']);?></td>
											 <?php else :?>
												 <td><?php echo /*utf8_encode*/($sede['sede']);?></td>
												 <td><?php echo /*utf8_encode*/($sede['cantidad']);?></td>
											 <?php endif ;?>
										 </tr>
										 <?php endforeach ;?>
								   </tbody>
								</table>									
							 </li>
							 <?php endforeach ;?>
						<?php else :?>
							<p>Sin resultados</p>
						<?php endif ;?>
					</ul>
					<div class="span5 offset1" style="float: left; margin-left: 37px;">
						<?php if(isset($barras) && $barras == 1 && ! empty($estadistica_por_carrera)) :?>
						<div id="barra5"></div>
						<?php else :?>
					
						<?php endif ; ?>
						<?php if(isset($tortas) && $tortas == 1 && ! empty($estadistica_por_carrera)) :?>
						<div id="torta5"></div>
						<?php else :?>
						
						<?php endif ; ?>
					</div>
						
					</div>
					
					<hr>
					<div class="row-fluid">
						<!--<H4 style="margin-top:40px;"><span id="img-check-licencia" align="left"><a href="#" id="dialog4"><img src="<?php echo $this->webroot;?>img/test-info-icon.png" alt="Texto proporcionado por Duoc" style="width: 20px; height: 20px;"></a></span>&nbsp;Desglose total de postulaciones activas de sedes por carreras:</h4>-->
						<H4 style="margin-top:40px;"><span id="img-check-licencia" align="left"></span>&nbsp;Desglose total de postulaciones activas de sedes por carreras:</h4>
					</div>
					<ul id="tabla1">
					<?php if (! empty($estadisticas_por_sede)) :?>
							 <?php foreach($estadisticas_por_sede as $llave => $sede_datos) :?>
							<li>
									<div><b><h5><?php echo /*utf8_encode*/($sede_datos['Sede']['sede']) ;?>: <?php echo $sede_datos['Sede']['cantidad'] ;?></div></h5></b>
									<table class="table table-striped table-bordered">
									<tbody>
										 <?php foreach($sede_datos['Carrera'] as $key2 => $carrera) :?>
										 <tr id ="<?php echo $key2 ;?>">
											 <?php if ($key2% 2 ==0) :?>
												 <td><?php echo /*utf8_encode*/($carrera['carrera']);?></td>
												 <td><?php echo /*utf8_encode*/($carrera['cantidad']);?></td>
											 <?php else :?>
												 <td><?php echo /*utf8_encode*/($carrera['carrera']);?></td>
												 <td><?php echo /*utf8_encode*/($carrera['cantidad']);?></td>
											 <?php endif ;?>
										 </tr>
										 <?php endforeach ;?>
								   </tbody>
								</table>									
							</li>
							 <?php endforeach ;?>
						<?php else :?>
							<p>Sin resultados</p>
						<?php endif ;?>
						
						</ul>
					<!-- FIN DEL REPORTE -->
					<div class="span5 offset1" style="float: left; margin-left: 37px;">
						<?php if(isset($barras) && $barras == 1 && ! empty($estadisticas_por_sede)) :?>
						<div id="barra4"></div>
						<?php else :?>
					
						<?php endif ; ?>
						<?php if(isset($tortas) && $tortas == 1 && ! empty($estadisticas_por_sede)) :?>
						<div id="torta4"></div>
						<?php else :?>
						
						<?php endif ; ?>
					</div>
				</div>
			</div>
	</div>
</div>

  <script>
//  $(document).ready(function(){
//		$('div#texto1').hide();
//		$('div#texto2').hide();
//		$('div#texto3').hide();
//		$('div#texto4').hide();
//		 $('#dialog1').click(function(event){
//			event.preventDefault();
//			$('#texto1').dialog();
//			});
//		 
//		  $('#dialog2').click(function(event){
//			event.preventDefault();
//			$('#texto2').dialog();
//			});
//		   $('#dialog3').click(function(event){
//			event.preventDefault();
//			$('#texto3').dialog();
//			});
//		    $('#dialog4').click(function(event){
//			event.preventDefault();
//			$('#texto4').dialog();
//			});
//	
//	})
  </script>
<!--<div id="texto1" title="Basic dialog">
  <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
<div id="texto2" title="Basic dialog">
  <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
<div id="texto3" title="Basic dialog">
  <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
<div id="texto4" title="Basic dialog">
  <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>-->
