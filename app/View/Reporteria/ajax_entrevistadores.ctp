<style type="text/css" media="all">
/* <![CDATA[ */
.table{
	text-align: right;
}
td{
	width: 338px;
}
/* ]]> */
</style>
<!--Para uso de ajax-->
<script type="text/javascript">
// <![CDATA[
$(document).ready(function(){
	
	///grafico de tortas 1
	$('#container1').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico total de entrevistas agendadas por carrera',
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
								width:'50px',
								fontSize:'8px',
								textTransform:'Uppercase'
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'Entrevistas',
					data: (function() {
								// generate an array of random data
								var data = [];
								<?php for($i = 0 ;$i<count($carrera_grafico);$i++)
								{ ?>
								data.push(["<?php echo $carrera_grafico[$i]['Carrera']['nombre'];?>",<?php echo $carrera_grafico[$i]['Carrera']['cantidad'];?>]);
								<?php } ?>
								return data;
							})()
						}]
	});
	
	///grafico de tortas 2
	  $('#container2').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Gráfico total de entrevistas agendadas por orientador',
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
								width:'50px',
								fontSize:'8px',
								textTransform:'Uppercase'
							}
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'Entrevistas',
					 data:(function() {
								// generate an array of random data
								var data = [];
								<?php for($i = 0 ;$i<count($orientador_grafico);$i++)
								{ ?>
								data.push(["<?php echo $orientador_grafico[$i]['Orientador']['nombre'];?>",<?php echo $orientador_grafico[$i]['Orientador']['cantidad'];?>]);
								<?php } ?>
								return data;
							})()
						}]
	});
	  
	  ///grafico de barras 1
	  $('#container3').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico total de entrevistas agendadas por carrera',
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
				useHTML : false,
				style:  {
						 color: '#333333',
						 fontWeight: 'bold',
						 fontFamily: 'Open Sans',
						 fontSize: '8px',
						 letterSpacing: '0px',
						 
					 }
				
            }
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Cantidad (unidades)'
            },
			style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '8px',
						letterSpacing: '0px'
					}
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de entrevistas: <b>{point.y:.1f} unidades</b>'
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
								<?php for($i = 0 ;$i<count($carrera_grafico);$i++)
								{ ?>
								data.push(["<?php echo $carrera_grafico[$i]['Carrera']['nombre'];?>",<?php echo $carrera_grafico[$i]['Carrera']['cantidad'];?>]);
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
	    
	///grafico de barras 2	
	$('#container4').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Gráfico total de entrevistas agendadas por orientador',
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
						letterSpacing: '0px',
						textTransform: 'Uppercase'
					}
            }
        },
        yAxis: {
           allowDecimals: false,
            title: {
                text: 'Cantidad (unidades)'
            },
			style:  {
						color: '#333333',
						fontWeight: 'bold',
						fontFamily: 'Open Sans',
						fontSize: '8px',
						letterSpacing: '0px'
					}
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de entrevistas: <b>{point.y:.1f} unidades</b>'
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
            data:(function() {
								// generate an array of random data
								var data = [];
								<?php for($i = 0 ;$i<count($orientador_grafico);$i++)
								{ ?>
								data.push(["<?php echo $orientador_grafico[$i]['Orientador']['nombre'];?>",<?php echo $orientador_grafico[$i]['Orientador']['cantidad'];?>]);
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

<div class="row-fluid">	
	<div class="span5 offset1">
		<h3 style="margin:0!important;">Reporte</h3>
		<p>Total de entrevistas agendadas: <?php echo $totales ;?></p>
		<p>Dias seleccionados: <?php echo $dias_diferencia ;?></p><br>
		<!--<h5><span id="img-check-licencia" align="left"><a href="#" id="dialog1"><img src="<?php echo $this->webroot;?>img/test-info-icon.png" alt="Texto proporcionado por Duoc" style="width: 20px; height: 20px;"></a></span>&nbsp;Total de entrevistas agendadas por carrera:</h5>-->
		<h5><span id="img-check-licencia" align="left"></span>&nbsp;Total de entrevistas agendadas por carrera:</h5>
		<?php if (! empty($carrera_tabla)) :?>
		   <table class="table table-striped table-bordered">
			   <tbody><?php $rellenarUltimaFila=0; ?>
					<?php foreach($carrera_tabla as $key => $carrera) :?>					
					<tr id ="<?php echo $key ;?>">
						<?php foreach($carrera as $llave =>  $carrera_lista) :?>
							<?php if ($llave % 2 ==0) :?>
								<td width="40%"><?php echo /*utf8_encode*/($carrera_lista['Carrera']['nombre']);?></td>
								<td width="10%"><?php echo /*utf8_encode*/($carrera_lista['Carrera']['cantidad']);?></td>
								<?php $rellenarUltimaFila=1; ?>
							<?php else :?>
								<td width="40%"> <?php echo /*utf8_encode*/($carrera_lista['Carrera']['nombre']);?></td>
								<td width="10%"><?php echo /*utf8_encode*/($carrera_lista['Carrera']['cantidad']) ;?></td>
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
	<div class="span5">
		<h6 class="pull-right fechaEtapa" style="margin-top:15px!important;">Rango de fechas seleccionados &nbsp;<i class="fa icon-calendar"></i> <?php echo $desde ;?> | <?php echo $hasta ;?></h6><br><br>		
		<?php if(isset($barras ) && $barras == 1 && ! empty($carrera_tabla)) :?>
		<div id="container3"></div>
		<?php else :?>
	
		<?php endif ; ?>
		<?php if(isset($tortas ) && $tortas == 1 && ! empty($carrera_tabla)) :?>
		<div id="container1"></div>
		<?php else :?>
		
		<?php endif ; ?>	
	</div>	
</div>
<hr>
<div class="row-fluid" style="margin-top:20px;">
	<div class="span5 offset1"><br />
		<!--<h5><span id="img-check-licencia" align="left"><a href="#"  id="dialog2"><img src="<?php echo $this->webroot;?>img/test-info-icon.png" alt="Texto proporcionado por Duoc" style="width: 20px; height: 20px;"></a></span>&nbsp;Total de entrevistas agendadas por orientador :</h5>-->
		<h5><span id="img-check-licencia" align="left"></span>&nbsp;Total de entrevistas agendadas por orientador :</h5>
			<?php if (! empty($orientador_tabla)) :?>
			<table class="table table-striped table-bordered">
				<tbody><?php $aux=0; ?>
				<?php foreach($orientador_tabla as $key2 => $orientador) :?>
				
					<?php foreach($orientador as  $orientador_lista) :?>
						<?php $aux++; ?>
						<tr>
							<td><?php echo /*utf8_encode*/(mb_strtoupper($orientador_lista['Orientador']['nombre'])) ;?></td>
							<td><?php echo /*utf8_encode*/($orientador_lista['Orientador']['cantidad']);?></td>
						</tr>					
					 <?php endforeach ;?>
				
				<?php endforeach ;?>
			   </tbody>
			</table>
			<?php else :?>
			<p>Sin resultados</p>
		<?php endif ;?>	
	</div>
	<div class="span5">
		<?php if(isset($barras) && $barras == 1 && ! empty($orientador_tabla)) :?>
		<div id="container4"></div>
		<?php else :?>		
		<?php endif ; ?>
		<?php if(isset($tortas) && $tortas == 1 && ! empty($orientador_tabla)) :?>
		<div id="container2"></div>
		<?php else :?>
		
		<?php endif ; ?>
	</div>

</div>
<br><br>
  <script>
//  $(document).ready(function(){
//		$('div#texto1').hide();
//		$('div#texto2').hide();
//		 $('#dialog1').click(function(event){
//			event.preventDefault();
//			$('#texto1').dialog();
//			});
//		 
//		  $('#dialog2').click(function(event){
//			event.preventDefault();
//			$('#texto2').dialog();
//			});
//	
//	})
  </script>
<!--<div id="texto1" title="Basic dialog">
  <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
<div id="texto2" title="Basic dialog">
  <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>-->
