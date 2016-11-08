<?php
	echo $this->Html->css('fullcalendar.css');
	echo $this->Html->script('fullcalendar.min.js');
	echo $this->Html->script('jquery-ui-1.10.3.custom.min.js');
	
?>
<div class="modal fade" id="myModal">
<?php echo $this->Form->create('Orientadores', array('action' => 'estado')) ;?>
<?php echo $this->Form->hidden('codigo',array('value' => $this->Session->read('UserLogued.Administrativo.codigo'))) ;?>
<?php echo $this->Form->hidden('codigo_postulacion') ;?>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Entrevista realizada</h4>
      </div>
      <div class="modal-body">
		
        <p>¿Desea confirmar que la entrevista ha sido realizada?</p>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" id="realizaentrevista">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
       
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <?php echo $this->Form->end() ;?>
</div><!-- /.modal -->

<script>

	$(document).ready(function() {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		$('.modal').hide();
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
			eventRender: function (calEvent, jsEvent, view) {
				
						$('.'+calEvent.id).on('click', function(){
						  
							var html = "";
							var urlPdf = "<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulacionArchivo')); ?>"+'/'+calEvent.postulacion;
							var num_cod_postulacion = calEvent.postulacion;
							var cod_postulacion = num_cod_postulacion.toString();
							if($(this))
							{
								 if(calEvent.estado =='AGENDADO' && (calEvent.postulante != '' || calEvent.postulacion != ''))
								 {
									 html = '<li>Postulante: <strong> '+calEvent.postulante+' </strong></li>';
									 html += '<li><a href="'+urlPdf+'" > Descargar PDF</a></li><li><a href="#" id="myModal" onclick="modal()">Entrevista realizada</a></li>';
									 $('#OrientadoresCodigoPostulacion').val(cod_postulacion);
								 }
							}
								
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



$('#realizaentrevista').click(function(){
		$('#OrientadoresEstadoForm').submit();
	})
//levanta el modal

function modal()
{
	$('#myModal').modal('show');  	
}



function eventos(calEvent)
{
	
	
	
	
}

 

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
				<div class="row-fluid">
					<div class="span10 offset2">
						<p><input type="checkbox" checked="checked" onclick="javascript:seleccionarHorarios('REALIZADO')" class="" id="check-REALIZADO" style="display:inline-block" /> <label style="display:inline-block" for="check-AGENDADO">Realizados</label></p> 
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

