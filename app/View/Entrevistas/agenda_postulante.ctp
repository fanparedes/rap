<?php 
	echo $this->Html->css('fullcalendar.css');
	echo $this->Html->script('fullcalendar.min.js');
	echo $this->Html->script('jquery-ui-1.10.3.custom.min.js'); 
	#debug($horarios_disponibles);
?>
<style type="text/css" media="screen">
	.mini-title{
		font-size:15px;
		color:#ccc;
	}
	.subtitle{
		color:#6E6E6E;
		border-bottom:1px solid #6E6E6E;
	}
	.formulario{
		border-bottom: 1px solid #E2E2E2;
		border-left :1px solid #E2E2E2;
		border-right: 1px solid #E2E2E2;
		width: 97.85%;
		margin-left:1% !important;
	}
	.fc-event-inner:hover{
		cursor:pointer;
	}
</style>

<?php echo $this->element('wizard-postulacion',array('cod_postulacion'=>$codigo_postulacion,'resumen'=>$resumen)); ?>
<div class="row-fluid formulario">
	<br>
	<div class="span12 ">
		<div class="row-fluid ">
			<div class="span10 offset1">
				<h3>Agendar Entrevista</h3>
			</div>	
		</div>
		<div class="row-fluid ">
			<div class="span10 offset1">
				<br>
				<?php if($resumen['estado']['codigo']==7): ?>
					<div class="row-fluid">
					  	<div class="span12">
					  		<div class="alert alert-info" align="center">
					  			<h4>Su Postulación a sido Rechazada. No puede agendar hora.</h4>
					  		</div>
					  	</div>
				  	</div>
				<?php else: ?>
					<?php if($ya_agendado): ?>
						<div class="row-fluid">
						  	<div class="span12">
						  		<div class="alert alert-info">
						  			<div class="row-fluid">
										<div class="span6">
											<div class="row-fluid">
												<div class="span4">
													<label>Orientador</label>		
												</div>
												<div class="span8">
													<strong><?php echo $datos_entrevista['Administrativo']['nombre']; ?></strong>		
												</div>
											</div>
										</div>
										<div class="span6">
											<div class="row-fluid">
												<div class="span4">
													<label>Hora de inicio</label>		
												</div>
												<div class="span8">
													<strong><?php echo date('H:i',strtotime($datos_entrevista['Horario']['hora_inicio'])); ?></strong>		
												</div>
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span6">
											<div class="row-fluid">
												<div class="span4">
													<label>Fecha</label>		
												</div>
												<div class="span8">
													<strong><?php echo date('d/m/Y',strtotime($datos_entrevista['Horario']['hora_inicio'])); ?></strong>		
												</div>
											</div>
										</div>
										<div class="span6">
											<div class="row-fluid">
												<div class="span4">
													<label>Hora de termino</label>		
												</div>
												<div class="span8">
													<strong><?php echo date('H:i',strtotime($datos_entrevista['Horario']['hora_fin'])); ?></strong>		
												</div>
											</div>
										</div>
									</div>
									<?php if($resumen['estado']['codigo']<9): ?>
										<?php if($datos_entrevista['Entrevista']['estado'] == 'REALIZADO'):  ?>
										<div class="row-fluid">
											<div class="span6 offset6" align="right">
												<h5>Entrevista Realizada</h5>
											</div>
										</div>
										<?php else :?>
										<div class="row-fluid">
											<div class="span6 offset6" align="right">
												<a href="<?php echo $this->Html->url(array('controller'=>'entrevistas','action'=>'anularHorario',$codigo_postulacion,$datos_entrevista['Horario']['codigo'])); ?>" class="btn btn-danger">Anular Entrevista</a>
											</div>
										</div>
										<?php endif; ?>
									<?php endif; ?>	
						  		</div>
						  	</div>
						</div>
					<?php else: ?>
						<div id='calendar'></div>
					<?php endif; ?>
				<?php endif; ?>
			</div>	
		</div>
		<br>
	</div>
</div>
<br>
<?php if(!$ya_agendado): ?>
	<div id="ModalAgenda" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
	<script>
		$(function() {
			//esconde star de la agenda
			//$('.fc-event-time').hide();
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
				editable: true,
				monthNames: ['Enero','Febrero','Marzo','April','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				monthNamesShort: ['Ene','Feb','Mar','Apr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
				dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
				dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
				hiddenDays: [0],
				weekends:false,
				selectable:true,
				aspectRatio: 2,
				weekMode:'variable',
				timeFormat:'dd-MM-yyyy HH:mm:ss',
				buttonText: {
	     			today:    'Hoy',
				    month:    'Mes',
				    week:     'Semana',
				    day:      'Dia'
	    		},
			
	    		events: [
	    		<?php foreach($horarios_disponibles as $k => $horario): ?>			
				     {	
			    		id: "<?php echo $horario['Horario']['codigo']; ?>",
			        	allDay: false,
						 title: " <?php echo 'HORARIO DISPONIBLE '.date('H:i',strtotime($horario['Horario']['hora_inicio'])).' Hasta '.date('H:i',strtotime($horario['Horario']['hora_fin'])); ?>",
			            //title: '\n HORARIO DISPONIBLE',
			            start: '<?php echo $horario['Horario']['hora_inicio']; ?>',
			            end: '<?php  echo $horario['Horario']['hora_fin']; ?>',
			            color: '#5bb75b',
						textColor: 'white',
						editable: false,
						
				     }
				     <?php if(count($horarios_disponibles)!=($k+1)): ?>
				     	,
				     <?php endif; ?>
				<?php endforeach; ?>    
			    ],
	    		eventClick: function(calEvent, jsEvent, view) {
					var id = calEvent.id;
					var editable = calEvent.editable;
					
					$('#ModalAgenda').load('<?php echo $this->Html->url(array('controller'=>'entrevistas','action'=>'agendarHora',$codigo_postulacion))?>'+'/'+id);
					$('#ModalAgenda').modal('show');	
			    }
			});
			//oculta el titulo de la entrevista
			$('span.fc-event-time').hide();
		});

	</script>
<?php endif; ?>

