<?php
	echo $this->Html->css('fullcalendar.css');
	echo $this->Html->script('fullcalendar.min.js');
	echo $this->Html->script('jquery-ui-1.10.3.custom.min.js');
	
?>

<script>

	$(document).ready(function() {
		var colores = Array('#f4543c','#00c0ef','#0073b7','#00b29e','#ba79cb','#ec3b83','#ffa812','#00a65a','#6c541e','#701c1c','#f4543c','#00c0ef','#0073b7','#00b29e','#ba79cb','#ec3b83','#ffa812','#00a65a','#6c541e','#701c1c','#f4543c','#00c0ef','#0073b7','#00b29e','#ba79cb','#ec3b83','#ffa812','#00a65a','#6c541e','#701c1c');
		var url = $("#Url").val();
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var calendar = $('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			selectable: false,
			selectHelper: false,
			editable: false,
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
			hiddenDays: [0],
			weekends: false,
			buttonText: {
     			today:    'Hoy',
			    month:    'Mes',
			    week:     'Semana',
			    day:      'Dia'
    		},
    		weekMode : 'liquid',
    		eventClick: function(calEvent, jsEvent, view) {
				
    			var html = "";
    			var urlPdf = "<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulacionArchivo')); ?>"+'/'+calEvent.postulacion;
    			if(calEvent.estado =='AGENDADO' && (calEvent.postulante != '' || calEvent.postulacion != '')){
    				html = '<li>Postulante: <strong> '+calEvent.postulante+' </strong></li>';
    				html += '<li><a href="'+urlPdf+'" > Descargar PDF</a></li>';
    			}
				
				$('.'+calEvent.id).popover({
					placement: (parseInt(calEvent.start.getDay())>3)? 'left':'right',
			        html: 'true',
			        title:'',
			        content :'<ul><li> Orientador: <strong>'+calEvent.description+'</strong></li><li> Estado: <strong>'+calEvent.estado+'</strong></li>'+html+'</ul>'
				});
				$(this).popover({
					placement: (parseInt(calEvent.start.getDay())> 3 )? 'left':'right',
					html: 'true',
					content :'<ul><li> Estado: <strong>'+calEvent.estado+'</strong></li>'+html+'</ul>'
				});
				 
				 $(this).click(function (e) {
					 e.stopPropagation();
				 });
				 
				$(document).click(function (e) {
					
					if (($('.'+calEvent.id).has(e.target).length == 0) || $(e.target).is('.close')) {
						$('div.popover').hide();
					}
				});
		    },
    		events: [
		    	<?php $numero_orientador = ""; 
		    		foreach($orientadores as $k => $orientador): ?>
		    		<?php foreach($orientador['Horarios'] as $horario): ?>			
					     {
							
				    		id: "<?php echo $horario['Horario']['codigo']; ?>",
				        	allDay: false,
				            title: " <?php echo ' Desde '.date('H:i',strtotime($horario['Horario']['hora_inicio'])).' Hasta '.date('H:i',strtotime($horario['Horario']['hora_fin'])).'\n Estado: '.$horario['Horario']['estado'].'\n'.$orientador['Administrativo']['nombre']; ?>",
				            start: '<?php echo $horario['Horario']['hora_inicio']; ?>',
				            end: '<?php echo $horario['Horario']['hora_fin']; ?>',
				            className: Array("todos horario-<?php echo $orientador['Administrativo']['codigo']; ?> carrera-<?php echo $orientador['Carrera']['codigo']?>","<?php echo $horario['Horario']['codigo']; ?>","<?php echo $horario['Horario']['estado'].'-'.$orientador['Carrera']['codigo']; ?>"),
				            color: colores["<?php echo $orientador['Administrativo']['codigo']; ?>"],
							estado:'<?php echo $horario['Horario']['estado']; ?>',
							textColor: 'white',
							description:'<?php echo $orientador['Administrativo']['nombre']; ?>',
							editable: false,
							postulante: "<?php echo $horario['Postulante']['nombre']; ?>",
							postulacion: "<?php echo $horario['Entrevista']['postulacion_codigo']; ?>",
					     },
				     <?php endforeach;  $numero_orientador = $orientador['Administrativo']['codigo'] ;?>
				<?php endforeach; ?> 
		    ],
		});
	});

</script>
<style type="text/css">
	#check span{
		border-radius:100px;
		-moz-border-radius: 100px;
	}
    #calendar{
    	width:100% !important;
    	max-width:200%;
    }
    .popover{
    	width:200px;
    }
    .fc-event-time{
    	display:none;
    }
    .fc-event:hover{
    	cursor:pointer;
    }
	
	.listado_opciones li{
		display:inline-block;
		
	}
</style>
<input type="hidden" value="<?php echo $this->Html->url(array("controller" => "Entrevistas","action" => "guardarEntrevista"));?>" id="Url" />

<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span10 offset1">
				<h1>Agenda de Orientadores</h1>
			</div>	
		</div>
		<br>
		<div class="row-fluid">
			<div class="span8 offset1">
				<div id='calendar'></div>
			</div>	
			
			
			<div class="span5" style="width: 280px; float: right; margin-left: -5px;">
				<div class="span3 offset1" id="check">
				<h5 style="width: 219px;">Filtro por Carreras</h5>
					<p><?php echo $this->Form->input('carrera', array('type' => 'select', 'options' => $carreras,'div' => false,'label' => false,'empty' => 'TODAS LAS CARRERAS')) ;?><?php echo $this->Html->image('loader2.gif', array('width'=>'16', 'id'=>'loader' ,'style'=>'margin-left: -24px;margin-top: -73px;')) ;?></p>
				</div>
				<div class="span3 offset1" id="listaorientadores" style="margin-top: 90px; margin-left: -63px; width: 215px;">
				
				</div>	
			</div>
		</div>
		
	</div>
</div>

<script type="text/javascript">
$('#loader').hide();
	$('#carrera').change(function(){
		var valor = $(this).val();
		$('#loader').show().delay(599).fadeOut();
		if(valor == '')
		{
			valor == 0;
		}
		if(valor == 0)
		{
			$('.todos').show();
			$('#listaorientadores').hide();
			//maneja los eventos intactos mientras se va al anterior mes
			$('span.fc-button-prev').click(function(){
			 $('.todos').show();
			});
			//maneja los eventos intactos mientras se va al siguiente mes
			$('span.fc-button-next').click(function(){
			 $('.todos').show();
			});
			
			$('span.fc-button-month').click(function(){
			 $('.todos').show();
			});
			
			$('span.fc-button-agendaWeek').click(function(){
			 $('.todos').show();
			});
			
			$('span.fc-button-agendaDay').click(function(){
			 $('.todos').show();
			});
			
		}else{
			$.ajax({
				url: webroot +'administrativos/ajax_orientadores/'+ valor,
				type: 'POST',
				async: false,
				success: function(respuesta)
				{
					if(respuesta)
					{
						$('#listaorientadores').show();
						$('#listaorientadores').html(respuesta);
						$('.todos').hide();
						$('.carrera-'+valor).show();
						//maneja los eventos intactos mientras se va al anterior mes
						$('span.fc-button-prev').click(function(){
							validaciones(valor);
							 $('#listaorientadores p .orientador').each(function(index,elemento){
								if($(this).is(':checked') == true)
								{
								  $('.horario-'+ elemento.value).show();
								  return false;
								}
								if($(this).is(':checked') == false)
								{
								  $('.horario-'+ elemento.value).hide();
								  return false;
								}
							});
						  validaciones(valor);
						});
						//maneja los eventos intactos mientras se va al siguiente mes
						$('span.fc-button-next').click(function(){
							validaciones(valor);
							 $('#listaorientadores p .orientador').each(function(index,elemento){
								if($(this).is(':checked') == true)
								{
								  $('.horario-'+ elemento.value).show();
								  return false;
								}
								if($(this).is(':checked') == false)
								{
								  $('.horario-'+ elemento.value).hide();
								  return false;
								}
							});
							validaciones(valor);
						});
						
						/// aca lista de mes
						$('span.fc-button-month').click(function(){
							validaciones(valor);
							 $('#listaorientadores p .orientador').each(function(index,elemento){
								if($(this).is(':checked') == true)
								{
								  $('.horario-'+ elemento.value).show();
								  return false;
								}
								if($(this).is(':checked') == false)
								{
								  $('.horario-'+ elemento.value).hide();
								  return false;
								}
							});
							 validaciones(valor);
						});
						
						/// aca lista de semana
						$('span.fc-button-agendaWeek').click(function(){
							validaciones(valor);
							 $('#listaorientadores p .orientador').each(function(index,elemento){
								if($(this).is(':checked') == true)
								{
								  $('.horario-'+ elemento.value).show();
								  return false;
								}
								if($(this).is(':checked') == false)
								{
								  $('.horario-'+ elemento.value).hide();
								  return false;
								}
							});
							validaciones(valor); 
						});
						
						//aca lista por dia
						$('span.fc-button-agendaDay').click(function(){
							validaciones(valor);
							 $('#listaorientadores p .orientador').each(function(index,elemento){
								if($(this).is(':checked') == true)
								{
								  $('.horario-'+ elemento.value).show();
								  return false;
								}
								if($(this).is(':checked') == false)
								{
								  $('.horario-'+ elemento.value).hide();
								  return false;
								}
							});
							validaciones(valor);
						});
						
						//dia de hoy
						$('span.fc-button-today').click(function(){
							validaciones(valor);
							 $('#listaorientadores p .orientador').each(function(index,elemento){
								if($(this).is(':checked') == true)
								{
								  $('.horario-'+ elemento.value).show();
								  return false;
								}
								if($(this).is(':checked') == false)
								{
								  $('.horario-'+ elemento.value).hide();
								  return false;
								}
							});
							validaciones(valor);
						});
						
					}
					return false;
					
				}
			});
		}
	
	})
function validaciones(valor)
{
	
	$('.todos').hide();
	$('.carrera-'+valor).show();
	
	 
	if($('#check-DISPONIBLE').is(':checked') == true)
	{
	
	$('.DISPONIBLE-'+valor).show();
	
	}else{
	$('.DISPONIBLE-'+valor).hide();
	
	}
	
	if($('#check-REALIZADO').is(':checked') == true)
	{
	$('.REALIZADO-'+valor).show();
	
	}else{
	$('.REALIZADO-'+valor).hide();
	
	}
	
	if( $('#check-AGENDADO').is(':checked') == true)
	{
	$('.AGENDADO-'+valor).show();
	
	
	}else{
	$('.AGENDADO-'+valor).hide();
	
	}
	
}

	
</script>
