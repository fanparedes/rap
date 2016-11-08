<?php
	echo $this->Html->css('fullcalendar.css');
	echo $this->Html->script('fullcalendar.min.js');
	echo $this->Html->script('jquery-ui-1.10.3.custom.min.js');
	
?> 
<script>

	$(document).ready(function() {
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
			        content :'<ul><li> Estado: <strong>'+calEvent.estado+'</strong></li>'+html+'</ul>'
				});
		    },
    		events: [
		    	<?php 
					foreach($horarios as $k => $horario): ?>
				     {	
			    		id: "<?php echo $horario['Horario']['codigo']; ?>",
			        	allDay: false,
			            title: " <?php echo ' Desde '.date('H:i',strtotime($horario['Horario']['hora_inicio'])).' Hasta '.date('H:i',strtotime($horario['Horario']['hora_fin'])).'\n'.$horario['Horario']['estado']; ?>",
			            start: '<?php echo $horario['Horario']['hora_inicio']; ?>',
			            end: '<?php echo $horario['Horario']['hora_fin']; ?>',
			            className: Array("horario-<?php echo $k; ?>","<?php echo $horario['Horario']['codigo']; ?>","<?php echo $horario['Horario']['estado']; ?>"),
			            color: "<?php echo ($horario['Horario']['estado'] == "DISPONIBLE")?  '#00a65a':'#701c1c'; ?>",
						textColor: 'white',
						estado:'<?php echo $horario['Horario']['estado']; ?>',
						editable: false,
						postulacion:'<?php echo (isset($horario['Entrevista']['postulacion_codigo']))? $horario['Entrevista']['postulacion_codigo']:'' ; ?>',
						postulante:'<?php echo (isset($horario['Postulante']['nombre']))? $horario['Postulante']['nombre'] : '' ; ?>',
				     },
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
</style>
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span10 offset1">
				<h1> Horarios Asignados</h1>
			</div>	
		</div>
		<br>
		<div class="row-fluid">
			<div class="span8 offset1">
				<div id='calendar'></div>
			</div>	
			<div class="span2">
				<div class="row-fluid">
					<div class="span10 offset2">
						<p><input type="checkbox" checked="checked" onclick="javascript:seleccionarHorarios('DISPONIBLE')" id="check-DISPONIBLE" style="display:inline-block"/> <label style="display:inline-block" for="check-DISPONIBLE">Disponibles</label></p>		
					</div>
				</div>
				<div class="row-fluid">
					<div class="span10 offset2">
						<p><input type="checkbox" checked="checked" onclick="javascript:seleccionarHorarios('AGENDADO')" class="" id="check-AGENDADO" style="display:inline-block" /> <label style="display:inline-block" for="check-AGENDADO">Agendados</label></p> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function seleccionarHorarios(estado){
		if($('#check-'+estado).is(':checked')){
			$('.'+estado).show();
		}else{
			$('.'+estado).hide();
		}
	}
</script>

