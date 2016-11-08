<?php 
	echo $this->Html->css('fullcalendar.css');
	echo $this->Html->script('fullcalendar.min.js');
	echo $this->Html->script('jquery-ui-1.10.3.custom.min.js');
?>
<script>

	$(document).ready(function() {
	
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
			/*selectable: true,
			selectHelper: true,
			select: function(start, end, allDay) {
				var title = prompt('Nombre del evento:');
				if (title) {
					calendar.fullCalendar('renderEvent',
						{
							title: title,
							start: start,
							end: end,
							allDay: allDay
						},
						true // make the event "stick"
					);
				}
				calendar.fullCalendar('unselect');
			},*/
			editable: true,
			monthNames: ['Enero','Febrero','Marzo','April','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Apr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
			hiddenDays: [0],
			buttonText: {
     			today:    'Hoy',
			    month:    'Mes',
			    week:     'Semana',
			    day:      'Dia'
    		},
    		events: [
    		<?php /* foreach($fechasDisponible as $key => $value):?>
		        {
		      		
		        	id: "<?php echo $value['fechas']['id']; ?>",
		        	allDay: false,
		            title: 'Disponible',
		            start: '<?php echo $value['fechas']['hora']; ?>',
			 		end: '<?php echo $value['fechas']['hora_fin']; ?>',
		           
		        },
		     <?php endforeach; */?> 
		     {
		    		id: "1",
		        	allDay: false,
		            title: 'Disponible',
		            start: '2013-12-21T13:15:30Z',
		            end: '2013-12-21T14:15:30Z',
		            color: 'yellow',   // an option!
    				textColor: 'black',
    				editable: false,
		     },
		     {
		            id: "2",
		        	allDay: false,
		            title: 'Disponible',
		            start: '2013-12-23T13:15:30Z',
		     },
		     { 
		            id: "3",
		        	allDay: false,
		            title: 'Disponible',
		            start: '2013-12-24T13:15:30Z',
			},		    
		    ],
    		eventClick: function(calEvent, jsEvent, view) {
    			divEvent = $(this);
				var id = calEvent.id;
				var editable = calEvent.editable;
				if(editable != false){
					$('#ModalAgenda').modal('show');
					$('#linkAgendar').attr('href', url+'/'+id);
				}				
		
		    }
		});
		
		
		
		
	});

</script>
<input type="hidden" value="<?php echo $this->Html->url(array("controller" => "Entrevistas","action" => "guardarEntrevista"));?>" id="Url" />	
<div class="container">
	<div id="ModalAgenda" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		
		<div class="modal-header" id="modal_header">
			Modal header
		</div>
		
	  	<div class="modal-body">
	  		<h3 style="text-align: center;">¿Esta seguro que desea tomar esta hora?</h3>
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
   			<a id="linkAgendar" href="#" class="btn btn-primary">Aceptar</a>
		</div>
	</div>
	<div class="span10">
		<br/>
		<div id='calendar'></div>
	</div>
</div>


