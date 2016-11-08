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
color: #111;
}
.content{
	width: 80%;
	margin: 0 auto;
	margin-top: 50px;
}
</style>

<br/>
<div class="row-fluid">
			<div class="span10 offset1">
				<h3>Reportes Generales:</h3>
				<br>			
			</div>
</div>
<?php echo $this->Form->create('Escuelas', array('div' => false));?>
<div class="row-fluid">
			<div class="span4 offset1">
				<div id="fechas" class="form-inline">
					<span>
						<label for="" class="fecha-label"> Desde:</label>
						<?php echo $this->Form->input('fecha_desde', array('readonly'=>'readonly','required' => 'required','class' => 'span2 datepicker','label' => false,'div' => false,'data-date-format' =>'mm/dd/yy','style'=>"width: 100px;",'value' => '')) ;?>
					</span>
					<span style="margin-left: 20px;">
						<label for="" class="fecha-label"> Hasta:</label>
						<?php echo $this->Form->input('fecha_hasta', array('readonly'=>'readonly','required' => 'required','class' => 'span2 datepicker2','label' => false,'div' => false,'data-date-format' =>'mm/dd/yy','style'=>"width: 100px;",'value' => '')) ;?>
					</span>	
				</div>			
			</div>
			
			<div class="span5 offset1">
				<span><?php echo $this->Html->image('loader2.gif', array('width'=>'16', 'id'=>'loader' ,'style'=>'margin-left: 241px;')) ;?></span>	
				<a class="btn btn-warning pull-right actualiza" onclick="validarFechas();"><i class="icon icon-refresh"></i> Actualizar</a>
				<!-- <a class="btn btn-default pull-right actualiza" onclick="validarFechasHistorico();" style="margin-right:5px;"><i class="icon icon-clock-o" ></i> Histórico</a> -->
			</div>
</div>
<div class="row-fluid">
			<div class="span10 offset1">
					<br>
					<div class="accordion" id="accordion2">

					<div class="accordion-group">
						<div class="accordion-heading">
						  <a id="enlace2" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
							Tipos de Admisión <i id="icono2" class="icon-chevron-circle-down pull-right"></i>
						  </a>
						</div>
						<div id="collapseTwo" class="accordion-body collapse">
						  <div class="accordion-inner">
							<div class="multiple"><span id="select_all2">todos </span>| <span id="deselect_all2">ninguno</span></div>
							<div class="row-fluid">
								<br>
								<div class="span2">
									<?php echo $this->Form->input('tipo_articulacion', array('value' => 0,'class' => 'tipos tipo_articulacion','label' => array('text' => 'ARTICULACIÓN'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('tipo_escuelas', array('value' => 0,'class' => 'tipos tipo_escuelas','label' => array('text' => 'ESCUELAS'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('tipo_rap', array('value' => 1,'class' => 'tipos tipo_rap','label' => array('text' => 'RAP'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('tipo_otros', array('value' => 1,'class' => 'tipos otros','label' => array('text' => 'OTROS'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
							</div>
						  </div>
						</div>
					  </div>



					  <div class="accordion-group">
						<div class="accordion-heading">
						  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" id="enlace1">
							Escuelas<i id="icono1" class="icon-chevron-circle-down pull-right"></i>
						  </a>
						</div>
						<div id="collapseOne" class="accordion-body collapse">
						  <div class="accordion-inner">
							<div class="multiple"><span id="select_all">todos </span>| <span id="deselect_all">ninguno</span></div>
							<div class="row-fluid">
								<br>
								<?php foreach($escuelas as $key => $escuela) : ?>
								<div class="span2">
									<?php echo $this->Form->input('Escuela.'.$key, array('value' => $key,'class' => 'escuelas','label' => array('text' => strtoupper($escuela)),'type' => 'checkbox','checked' => 'checked')); ?>
									</div>
								<?php endforeach ;?>
								
							</div>
						  </div>
						</div>
					  </div>

										
					<div class="accordion-group">
						<div class="accordion-heading">
						  <a id="enlace3" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
							Estados <i id="icono3" class="icon-chevron-circle-down pull-right"></i>
						  </a>
						</div>
						<div id="collapseThree" class="accordion-body collapse">
						  <div class="accordion-inner">
							<div class="multiple"><span id="select_all3">todos </span>| <span id="deselect_all3">ninguno</span></div>
							<div class="row-fluid">
								<br>
								<div class="span2">
									<?php echo $this->Form->input('en_revision', array('value' => 0,'class' => 'opciones en_revision','label' => array('text' => 'EN REVISIÓN'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('no_habilitado', array('value' => 0,'class' => 'opciones no_habilitado','label' => array('text' => 'NO HABILITADOS'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('Habilitado', array('value' => 1,'class' => 'opciones habilitado','label' => array('text' => 'HABILITADOS'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('habilitado_firma', array('value' => 1,'class' => 'opciones habilitado_firma','label' => array('text' => 'HABILITADOS FIRMA'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('habilitado_csa', array('value' => 1,'class' => 'opciones habilitado_csa','label' => array('text' => 'HABILITADOS CSA'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('doc_con_obs', array('value' => 1,'class' => 'opciones doc_con_obs','label' => array('text' => 'DOC. CON OBS.'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('rev_pendiente', array('value' => 1,'class' => 'opciones rev_pendiente','label' => array('text' => 'REVISIÓN PENDIENTE'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('sin_act_cuenta', array('value' => 1,'class' => 'opciones sin_act_cuenta','label' => array('text' => 'SIN ACTIV. CUENTA'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
								<div class="span2">
									<?php echo $this->Form->input('sin_form_guardado', array('value' => 1,'class' => 'opciones sin_form_guardado','label' => array('text' => 'SIN FORM. GUARDADO'),'type' => 'checkbox','checked' => 'checked')); ?>
								</div>
							</div>
						  </div>
						</div>
					  </div>
			</div>					
			</div>
</div>
<?php echo $this->Form->end();?>
<div class="reporte container-fluid"></div>
<div class="row-fluid reporteriaexcel">
	<div class="span10 offset1" style="margin-left: 99px;">
		<ul class="inline pull-right" style="margin-bottom:10px">
			<li>
				<a type="button" class="btn btn-primary subir" href="#arriba"><i class="icon icon-arrow-up"></i> Subir</a>
				<a type="button" class="btn btn-success excel"><i class="icon icon-download"></i> Exportar Excel</a>
			</li>
		</ul>
	</div>
</div>			


<script>
$('.datepicker').val('');
$('.datepicker2').val('');
///escondemos boton de exportar a excel
$('.reporteriaexcel').hide();


$( "#enlace1" ).click(function() { 
	if ($( "#icono1" ).hasClass( 'icon-chevron-circle-down')) {		
		$("#icono1" ).removeClass('icon-chevron-circle-down');
		$("#icono1").toggleClass('icon-chevron-circle-up');
  }
  else {
  		$("#icono1" ).removeClass('icon-chevron-circle-up');
		$("#icono1").toggleClass('icon-chevron-circle-down');
  }  
});

$( "#enlace2" ).click(function() { 
	if ($( "#icono2" ).hasClass( 'icon-chevron-circle-down')) {
		$("#icono2" ).removeClass('icon-chevron-circle-down');
		$("#icono2").toggleClass('icon-chevron-circle-up');
  }
  else {
  		$("#icono2" ).removeClass('icon-chevron-circle-up');
		$("#icono2").toggleClass('icon-chevron-circle-down');
  }  
});

$( "#enlace3" ).click(function() { 
	if ($( "#icono3" ).hasClass( 'icon-chevron-circle-down')) {
		$("#icono3" ).removeClass('icon-chevron-circle-down');
		$("#icono3").toggleClass('icon-chevron-circle-up');
  }
  else {
  		$("#icono3" ).removeClass('icon-chevron-circle-up');
		$("#icono3").toggleClass('icon-chevron-circle-down');
  }  
});

$( "#enlace4" ).click(function() { 
	if ($( "#icono4" ).hasClass( 'icon-chevron-circle-down')) {
		$("#icono4" ).removeClass('icon-chevron-circle-down');
		$("#icono4").toggleClass('icon-chevron-circle-up');
  }
  else {
  		$("#icono4" ).removeClass('icon-chevron-circle-up');
		$("#icono4").toggleClass('icon-chevron-circle-down');
  }  
});

function validarFechasHistorico()
{
		$('#EscuelasFechaDesde').val('01/03/2014');
		var hoy = new Date();
		var dia = hoy.getDate();
		var mes = hoy.getMonth() + 1;
		var ano = hoy.getFullYear();		
		fecha = dia+'/'+mes+'/'+ano; 
		$('#EscuelasFechaHasta').val(fecha);
		validarFechas();
}

function validarFechas()
{
	var formulario = $('#EscuelasGeneralesForm').serialize();
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
		$(".actualiza").attr('disabled', true);
		$('div.reporte').html('<center><img src="'+<?php echo $this->webroot; ?>+'/images/cargando.gif"></center>');
		//$("#loader").show().delay(5000).fadeOut();
		$.ajax({
				url: webroot +'reporteria/ajax_generales',
				type: 'POST',
				async: true,
				data: formulario,
				success: function(respuesta)
				{
					$(window).scrollTop(500);
					$('div.reporte').html(respuesta).fadeIn(1000);
					$('.reporteriaexcel').fadeIn(1000);
					$(".actualiza").attr('disabled', false);
				}
			  });
	}
	
}



</script>
<script>

$('input.otros').click(function(){
	if($(this).is(':checked'))
	{
		$('.doc_con_obs:checkbox').prop('checked', true);
		$('.rev_pendiente:checkbox').prop('checked', true);
		$('.sin_act_cuenta:checkbox').prop('checked', true);
		$('.sin_form_guardado:checkbox').prop('checked', true);
		$('.doc_con_obs:checkbox').prop('disabled', false);
		$('.rev_pendiente:checkbox').prop('disabled', false);
		$('.sin_act_cuenta:checkbox').prop('disabled', false);
		$('.sin_form_guardado:checkbox').prop('disabled', false);
	} else {
		$('.doc_con_obs:checkbox').prop('checked', false);
		$('.rev_pendiente:checkbox').prop('checked', false);
		$('.sin_act_cuenta:checkbox').prop('checked', false);
		$('.sin_form_guardado:checkbox').prop('checked', false);
		$('.doc_con_obs:checkbox').prop('disabled', true);
		$('.rev_pendiente:checkbox').prop('disabled', true);
		$('.sin_act_cuenta:checkbox').prop('disabled', true);
		$('.sin_form_guardado:checkbox').prop('disabled', true);
	}
})


$('input.tipo_articulacion').click(function(){
	if($(this).is(':checked'))
	{
		$('.en_revision:checkbox').prop('checked', true);
		$('.no_habilitado:checkbox').prop('checked', true);
		$('.habilitado:checkbox').prop('checked', true);
		$('.en_revision:checkbox').prop('disabled', false);
		$('.no_habilitado:checkbox').prop('disabled', false);
		$('.habilitado:checkbox').prop('disabled', false);
	} else {
		if($('input.tipo_escuelas').is(':checked'))
		{
			$('.en_revision:checkbox').prop('checked', true);
			$('.no_habilitado:checkbox').prop('checked', true);
			$('.habilitado:checkbox').prop('checked', true);
			$('.en_revision:checkbox').prop('disabled', false);
			$('.no_habilitado:checkbox').prop('disabled', false);
			$('.habilitado:checkbox').prop('disabled', false);
		} else {
			$('.en_revision:checkbox').prop('checked', false);
			$('.no_habilitado:checkbox').prop('checked', false);
			$('.habilitado:checkbox').prop('checked', false);
			$('.en_revision:checkbox').prop('disabled', true);
			$('.no_habilitado:checkbox').prop('disabled', true);
			$('.habilitado:checkbox').prop('disabled', true);
		}

		if($('input.tipo_rap').is(':checked'))
		{
			$('.en_revision:checkbox').prop('checked', true);
			$('.en_revision:checkbox').prop('disabled', false);
		} else  {
			$('.en_revision:checkbox').prop('checked', false);
			$('.en_revision:checkbox').prop('disabled', true);
		}

	}
})



$('input.tipo_escuelas').click(function(){
	if($(this).is(':checked'))
	{
		$('.en_revision:checkbox').prop('checked', true);
		$('.no_habilitado:checkbox').prop('checked', true);
		$('.habilitado:checkbox').prop('checked', true);
		$('.en_revision:checkbox').prop('disabled', false);
		$('.no_habilitado:checkbox').prop('disabled', false);
		$('.habilitado:checkbox').prop('disabled', false);
	} else {
		if($('input.tipo_articulacion').is(':checked'))
		{
			$('.en_revision:checkbox').prop('checked', true);
			$('.no_habilitado:checkbox').prop('checked', true);
			$('.habilitado:checkbox').prop('checked', true);
			$('.en_revision:checkbox').prop('disabled', false);
			$('.no_habilitado:checkbox').prop('disabled', false);
			$('.habilitado:checkbox').prop('disabled', false);
		} else {
			$('.en_revision:checkbox').prop('checked', false);
			$('.no_habilitado:checkbox').prop('checked', false);
			$('.habilitado:checkbox').prop('checked', false);
			$('.en_revision:checkbox').prop('disabled', true);
			$('.no_habilitado:checkbox').prop('disabled', true);
			$('.habilitado:checkbox').prop('disabled', true);
		}

		if($('input.tipo_rap').is(':checked'))
		{
			$('.en_revision:checkbox').prop('checked', true);
			$('.en_revision:checkbox').prop('disabled', false);
		} else  {
			$('.en_revision:checkbox').prop('checked', false);
			$('.en_revision:checkbox').prop('disabled', true);
		}

	}
})



$('input.tipo_rap').click(function(){
	if($(this).is(':checked'))
	{
		$('.en_revision:checkbox').prop('checked', true);
		$('.en_revision:checkbox').prop('disabled', false);
	} else {
		$('.en_revision:checkbox').prop('checked', false);
		$('.en_revision:checkbox').prop('disabled', true);

		if($('input.tipo_articulacion').is(':checked'))
		{
			$('.en_revision:checkbox').prop('checked', true);
			$('.en_revision:checkbox').prop('disabled', false);
		} else {
			$('.en_revision:checkbox').prop('checked', false);
			$('.en_revision:checkbox').prop('disabled', true);
		}

		if($('input.tipo_escuelas').is(':checked'))
		{
			$('.en_revision:checkbox').prop('checked', true);
			$('.en_revision:checkbox').prop('disabled', false);
		} else {
			$('.en_revision:checkbox').prop('checked', false);
			$('.en_revision:checkbox').prop('disabled', true);
		}


	}
})


$( "#loader" ).hide();
/* Este script sirve para señalar todos los checkbox de una tacada, o ninguno */
$('#select_all').click(function() {
    $('.escuelas:checkbox').prop('checked', true);
});

$('#deselect_all').click(function() {
	$(".escuelas:checkbox").prop('checked', false);
});


$('#select_all3').click(function() {
    $('.opciones:checkbox').prop('checked', true);
});

$('#deselect_all3').click(function() {
	$(".opciones:checkbox").prop('checked', false);
});

$('#select_all2').click(function() {
    $('.tipos:checkbox').prop('checked', true);
    $(".opciones:checkbox").prop('checked', true);
    $(".opciones:checkbox").prop('disabled', false);
});

$('#deselect_all2').click(function() {
	$(".tipos:checkbox").prop('checked', false);
	$(".opciones:checkbox").prop('checked', false);
	$(".opciones:checkbox").prop('disabled', true);
});

</script>
<script>
	$('#EscuelasFechaDesde').hover(function() {
	$(this).css('cursor','pointer');
	});
	$('#EscuelasFechaHasta').hover(function() {
	$(this).css('cursor','pointer');
	});

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
		window.location.href =  webroot +'reporteria/ajax_exportar_excel_generales';
	})
	
	$('.detalle').click(function(){
	var formulario = $('#ReporteriaEntrevistadoresForm').serialize();
		window.location.href =  webroot +'administracion/reporteria/verDetalleEscuelas';
	})
</script>