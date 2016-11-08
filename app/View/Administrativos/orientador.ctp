<?php 
	echo $this->Html->css('fullcalendar.css');
	echo $this->Html->script('fullcalendar.min.js');
	echo $this->Html->script('jquery-ui-1.10.3.custom.min.js');
?>

<style>
	.fc-header-title h2{
		font-size: 20px;
	}
	.acciones{width:90px;}
	.nro{width:40px;}
</style>
<br/>
<div class="row-fluid">
	<div class="span8 offset1">
		<h3>Orientador: <?php echo $orientador['Administrativo']['nombre'];?></h3>
	</div>
	<div class="span2" style="margin-top: 10px; text-align:right;">
		<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'horario')); ?>" class="btn btn-warning">
			<i class="icon-chevron-circle-left"></i> Volver
		</a>
	</div>
</div>
<br />
<div class="row-fluid">
	<div class="span4 offset1">
		<div>
			<div id='calendar'></div>
		</div>
	</div>
	<div class="span6 hora_agregar">
		<div id="agregarHora"></div>
	</div>
</div>

<script>
	$(document).ready(function() {
    	var urlboton = "<?php echo $this->html->url(array('action' => 'nuevaEntrevista'));?>";
    	var codigo = "<?php echo $orientador['Administrativo']['codigo'];?>";
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var calendar = $('#calendar').fullCalendar({	
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month'
			},
			selectable: true,
			selectHelper: true,
			select: function(start, end, allDay, event) {
				var check = $.fullCalendar.formatDate(start,'yyyy-MM-dd');
				var today = $.fullCalendar.formatDate(new Date(),'yyyy-MM-dd');
				var nuevaFecha = $.fullCalendar.formatDate( start, "yyyy-MM-dd" );
				$('#agregarHora').html('<div align="center"><?php echo $this->Html->image('loader.gif'); ?></div>');
					$.post(urlboton, { dia: nuevaFecha, codigo: codigo, start: start})
					.done(function(data){
						if(data!=''){
							$('#agregarHora').html(data);	
						}else{
							$('#agregarHora').html("");
						}
					});
				if(check < today)
				{
					$('.hora_agregar').hide();
					 alert('No puede seleccionar fechas menores a la de hoy');
					return false;
					
				}
				else
				{
					$('.hora_agregar').show();
					$('#agregarHora').html('<div align="center"><?php echo $this->Html->image('loader.gif'); ?></div>');
					$.post(urlboton, { dia: nuevaFecha, codigo: codigo, start: start})
					.done(function(data){
						if(data!=''){
							$('#agregarHora').html(data);	
						}else{
							$('#agregarHora').html("");
						}
					});
				}
				
			},
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
			hiddenDays: [0],
			buttonText: {
     			today:    'Hoy',
			    month:    'Mes'
    		},
    		height: 250,
    		weekends:false,
    		weekMode : 'liquid',
			events: [
	    		<?php foreach($horarios_orientador as $k => $horario): ?>			
				     {	
			    		id: "<?php echo $horario['Horario']['codigo']; ?>",
			        	allDay: true,
			            title: '<?php echo ' Desde '.date('H:i',strtotime($horario['Horario']['hora_inicio'])).' Hasta '.date('H:i',strtotime($horario['Horario']['hora_fin'])).'\n'.$horario['Horario']['estado']; ?>',
			            start: '<?php  echo $horario['Horario']['hora_inicio']; ?>',
			            end: '<?php echo $horario['Horario']['hora_fin']; ?>',
			            color: '#5bb75b',
						textColor: 'white',
						editable: false,
						className: Array("horario-<?php echo $k; ?>","<?php echo $horario['Horario']['codigo']; ?>"),
						textColor: 'white',
						estado:'<?php echo $horario['Horario']['estado']; ?>',
				     }
				     <?php if(count($horarios_orientador)!=($k+1)): ?>
				     	,
				     <?php endif; ?>
				<?php endforeach; ?>    
			    ],
		});
});
/*
 * events: [
	    		<?php //foreach($horarios_orientador as $k => $horario): ?>			
				     {	
			    		id: "<?php //echo $horario['Horario']['codigo']; ?>",
			        	allDay: true,
			            title: '',
			            start: '<?php // echo $horario['Horario']['hora_inicio']; ?>',
			            end: '<?php //echo $horario['Horario']['hora_fin']; ?>',
			            color: '#5bb75b',
						textColor: 'white',
						editable: false,
				     }
				     <?php // if(count($horarios_orientador)!=($k+1)): ?>
				     	,
				     <?php //endif; ?>
				<?php //endforeach; ?>    
			    ],*/
			

</script>

<div id="resutlados">
	
</div>

	
	<?php
  	 if(isset($fecha1)):
		@list($year, $month, $day) = explode("-", $fecha1);
		$codigo = $orientador['Administrativo']['codigo'];
		echo "<script> horario($year, $month, $day, $codigo)</script>";
  
   endif;
   ?>
	

