<?php  
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT");  
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");  
?>
<style>
.acciones{width:110px;}
h1{
font-size: 20px;
color: #00aff0;
margin-left: 28px;
width: 200px;
}
.datepicker, .datepicker2{
font-size:12px!important;
}
</style>

<br/>
<!---inicio-->
<?php echo $this->Form->create('Reporteria', array('div' => false));?>
<div class="row-fluid">
			<div class="span6 offset1">
				<h3>Reportes de entrevistas:</h3>
				<br>			
			</div>
</div>
<div class="row-fluid">
			<div class="span5 offset1">
				<div id="fechas" class="form-inline">
					<span>
						<label for="" class="fecha-label"> Desde:</label>
						<?php echo $this->Form->input('fecha_desde', array('readonly'=>'readonly','required' => 'required','class' => 'span2 datepicker','label' => false,'div' => false,'data-date-format' =>'mm/dd/yy')) ;?>
					</span>
					<span style="margin-left: 20px;">
						<label for="" class="fecha-label"> Hasta:</label>
						<?php echo $this->Form->input('fecha_hasta', array('readonly'=>'readonly','required' => 'required','class' => 'span2 datepicker2','label' => false,'div' => false,'data-date-format' =>'mm/dd/yy')) ;?>
					</span>	
				</div>			
			</div>
			<div class="span5">
				<span><?php echo $this->Html->image('loader2.gif', array('width'=>'16', 'id'=>'loader' ,'style'=>'margin-left: 241px;')) ;?></span>	
				<a class="btn btn-warning pull-right actualiza" onclick="validarFechas();"><i class="icon icon-refresh"></i> Actualizar</a>
				<a class="btn btn-default pull-right actualiza" onclick="validarFechasHistorico();" style="margin-right:5px;"><i class="icon icon-clock-o" ></i> Histórico</a>
				
			</div>
</div>
<div class="row-fluid">
			<div class="span10 offset1">
					<br>
					<div class="accordion" id="accordion2">
					  <div class="accordion-group">
						<div class="accordion-heading">
						  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" id="enlace1">
							Carreras<i id="icono1" class="icon-chevron-circle-down pull-right"></i>
						  </a>
						</div>
						<div id="collapseOne" class="accordion-body collapse">
						  <div class="accordion-inner">
							<div class="multiple"><span id="select_all">todos </span>| <span id="deselect_all">ninguno</span></div>
							<div class="row-fluid">
								<br>
								<?php foreach($carreras as $key => $carrera) : ?>
								<div class="span2">
									<?php echo $this->Form->input('Carrera.'.$key.'.orientador', array('value' => $carrera['Carrera']['codigo'],'class' => 'carreras','label' => array('text' => strtoupper($carrera['Carrera']['nombre'])),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<?php endforeach ;?>
								
							</div>
						  </div>
						</div>
					  </div>
					  
					   <div class="accordion-group">
						<div class="accordion-heading">
						  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" id="enlace2">
							Orientadores <i  id="icono2" class="icon-chevron-circle-down pull-right"></i>
						  </a>
						</div>
						<div id="collapseTwo" class="accordion-body collapse">
						  <div class="accordion-inner">
							<div class="multiple"><span id="select_all2">todos </span>| <span id="deselect_all2">ninguno</span></div>
							<div class="row-fluid">
								<br>
								<?php foreach($orientadores as $key => $datos) :?>
								<div class="span2">
									<?php echo $this->Form->input('Orientador.'.$key.'.orientador', array('class' => 'orientador','value' => $datos['Administrativo']['codigo'],'label' => array('text' => strtoupper($datos['Administrativo']['nombre'])),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<?php endforeach ;?>								
							</div>
						  </div>
						</div>
					  </div>
					<div class="accordion-group">
						<div class="accordion-heading">
						  <a id="enlace3" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
							Gráficos <i id="icono3" class="icon-chevron-circle-down pull-right"></i>
						  </a>
						</div>
						<div id="collapseThree" class="accordion-body collapse">
						  <div class="accordion-inner">
							<div class="multiple"></div>
							<div class="row-fluid">							
									<div class="span3">
											<label class="checkbox inline">
												Barras												
											</label>
											<?php echo $this->Form->input('barras', array('class' => 'barras','id' => 'inlineCheckbox1','type' =>'checkbox','checked' => false, 'label' => false,'div' => false)) ;?>
										
									</div>
									<div class="span3">										
											<label class="checkbox inline">Tortas </label>
												<?php echo $this->Form->input('tortas', array('class' => 'tortas','id' => 'inlineCheckbox1','type' =>'checkbox','checked' => 'checked', 'label' => false,'div' => false)) ;?>
									</div>
							</div>
						  </div>
						</div>
					  </div>			
		</div>		
	</div>
</div>
<?php echo $this->Form->end();?>
<div class="reporte"></div>
<div class="row-fluid reporteriaexcel">
	<div class="span10 offset1" style="margin-left: 99px;">
		<ul class="inline pull-right">
			<li>
				<a type="button" class="btn btn-primary detalle"><i class="icon icon-eye"></i> Ver Detalle</a>
				<a type="button" class="btn btn-success excel"><i class="icon icon-download"></i> Exportar Excel</a>
			</li>
		</ul>
	</div>
</div>
<!--fin reportes--->
<script type="text/javascript">
// <![CDATA[
$('#loader').hide();
$('.datepicker').val('');
$('.datepicker2').val('');
/* Este script sirve para señalar todos los checkbox de una tacada, o ninguno */
$('#select_all').click(function() {
    $('.carreras:checkbox').prop('checked', true);
});

$('#deselect_all').click(function() {
	$(".carreras:checkbox").prop('checked', false);
});


$('#select_all2').click(function() {
    $('.orientador:checkbox').prop('checked', true);
});

$('#deselect_all2').click(function() {
	$(".orientador:checkbox").prop('checked', false);
});


///escondemos boton de exportar a excel
$('.reporteriaexcel').hide();
//checkbox de graficos
$('input.barras').click(function(){
	if($(this).is(':checked'))
	{
		$('input.tortas').attr('checked', false);
	}
})

$('input.tortas').click(function(){
	if($(this).is(':checked'))
	{
		$('input.barras').attr('checked', false);
	}
})

//hover para insertar pointer en los input de fechas
$('#ReporteriaFechaDesde').hover(function() {
$(this).css('cursor','pointer');
});
$('#ReporteriaFechaHasta').hover(function() {
$(this).css('cursor','pointer');
});


function validarFechas()
{
	
	var formulario	= $('#ReporteriaEntrevistadoresForm').serialize();
	var desde		= $('#ReporteriaFechaDesde').val();
	var hasta		= $('#ReporteriaFechaHasta').val();
	
	var split_desde = desde.split('/');
	var split_hasta = hasta.split('/');
	var date_desde = new Date(split_desde[2], (split_desde[1] - 1), split_desde[0]); //Y M D 
	var timestamp_desde = date_desde.getTime();
	var date_hasta = new Date(split_hasta[2], (split_hasta[1] - 1), split_hasta[0]); //Y M D 
	var timestamp_hasta = date_hasta.getTime();
	
	if(timestamp_desde >= timestamp_hasta)
	{
		$('#ReporteriaFechaDesde').val('');
		$('#ReporteriaFechaHasta').val('');
		alert('La fecha de inicio debe ser menor a la actual');
		return false;
	}else{
		
		if($('#ReporteriaFechaDesde').val() == '' || $('#ReporteriaFechaHasta').val() == '' )
		{
		
			alert('Debe seleccionar las fechas de inicio y fin');
			return false;
		}
		
		if($('input.tortas').is(':checked') == false && $('input.barras').is(':checked') == false)
		{
			
			alert('Debe seleccionar un tipo de grafico');
			return false;
		}
		$('#loader').show().delay(699).fadeOut();
		
		$.ajax({
				url: webroot +'reporteria/ajax_entrevistadores',
				type: 'POST',
				async: true,
				data: formulario,
				success: function(respuesta)
				{
					$(window).scrollTop(400);
					$('div.reporte').html(respuesta).fadeIn(1000);
					$('.reporteriaexcel').fadeIn(1000);
				}
				
		});	
	}
	
}

function validarFechasHistorico()
{
		$('#ReporteriaFechaDesde').val('01/03/2014');
		var hoy = new Date();
		var dia = hoy.getDate();
		var mes = hoy.getMonth() + 1;
		var ano = hoy.getFullYear();
		fecha = dia+'/'+mes+'/'+ano; 
		$('#ReporteriaFechaHasta').val(fecha);
		validarFechas();
}



//$.datepicker.setDefaults($.datepicker.regional["es"]);
$('.datepicker').datepicker({
	dateFormat: 'dd/mm/yy',
	maxDate: 0,
	buttonImageOnly: true
});
$('.datepicker2').datepicker({
	dateFormat: 'dd/mm/yy',
	maxDate: 0,
	buttonImageOnly: true
});


///para exportar a excel
	$('.excel').click(function(){
	var formulario = $('#ReporteriaEntrevistadoresForm').serialize();
		window.location.href =  webroot +'reporteria/ajax_exporta_excel';
	})
	
///para ver el detalle del informe
	$('.detalle').click(function(){
	var formulario = $('#ReporteriaEntrevistadoresForm').serialize();
		window.location.href =  webroot +'administracion/reporteria/verDetalleOrientadores';
	})
	
// ]]>
</script>
