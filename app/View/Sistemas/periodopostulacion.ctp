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
<!-- MODAL DE ELIMINAR PERIODO -->
<div id="modalEliminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">		
		<h3 style="text-align: center;">¿Está seguro de querer eliminar el periodo de postulación?</h3>
	</div>	
	<form method="POST" id="formrecordar" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'recordarAlerta') )?>">	
	  	<div class="modal-body">		
	  		<div class="row-fluid">
				<div class="span12">
						<div class="alert alert-info" style="margin-top:10px!important;">Si se elimina el periodo, se borrarán todos los emails que estuviesen pendientes de enviar en el plazo que ahora se procede a eliminar. Tendrá que generar un nuevo periodo para que los plazos y las alertas se vuelvan a gestionar.</div>	
				</div>
			</div> 		
		</div>
		<div class="modal-footer " style="text-align:center">			
			<?php echo $this->Html->link(
				'<i class="icon icon-times"></i> Eliminar',
				array('action' => 'eliminarperiodo'),
				array('class' => 'btn btn-danger', 'escape'=>false, 'id' => 'elidminar')				
			);?>
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		</div>
	</form>
</div>
<br/>
<div class="row-fluid">	
	<div class="span5 offset1">
		<h3>Plazos de postulación al portal RAP:</h3>
	</div>
	<div class="span2">		
	</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1">
	
			<?php if (empty($periodo)):?>
			<div class="alert alert-info">
				<p>El periodo de postulación tiene una fecha de inicio y una fecha de finalización. A la hora de seleccionar estos días tenga en cuenta:</p>
				<ul style="list-style:disc">
					<li>La fecha de finalización no puede ser menor a la de inicio.</li>
					<li>
						El periodo de postulación acaba a las 00:00 del día elegido. Quiere decir que si el plazo que se quiere escoger va desde el día 1 hasta el dia 5. El plazo concluirá el día 4 a las 23:59:59.
					</li>
					<li>Pulse en el cuadro de fecha de inicio o de fin para que se despliegue el calendario.</li>
				</ul>
			</div>
			<br>
			<h5> Actualmente no hay periodo de postulación completado. Rellene el periodo de inicio y fin en el siguiente formulario:</h5>
			<?php 
				echo $this->Form->create('Periodo', array(
						'inputDefaults' => array(
							'div' => false,
							'label' => false,
							'wrapInput' => false
						),
						'class' => 'well form-inline'
					));
				echo $this->Form->input('nombre', array(
					'label' => 'Nombre ',
					'required' => true,
					'style' => 'margin-left:10px; margin-right:5px;',
					'placeholder' => 'Ej. Primer Semetreste 2013'
				));
				echo $this->Form->input('fecha_inicio', array(
					'type'=>'text',
					'class'=>'span2',
					'style' => 'margin-left:10px; margin-right:5px;',
					'required' => true,
					'label' => 'Fecha de inicio ',
					'readonly'=>'readonly'
				));
				echo $this->Form->input('fecha_fin', array(
					'type'=>'text',
					'class'=>'span2',
					'style' => 'margin-left:10px; margin-right:5px;',
					'required' => true,
					'readonly'=>'readonly',
					'label' => 'Fecha de finalización '	
				));
			
			?>
			
			<button type="button" class="btn btn-success" onclick="validaFecha();" style="margin-left:5%">Guardar</button>	
			<?php else: ?>

			<h4>Periodo de postulación actual:</h4>
			<p>El período de postulación, marca los plazos a los postulantes para que puedan inscribirse.</p><br>
			<p><strong>Periodo:</strong> <?php echo $periodo['Periodo']['nombre']; ?></p>
			<p><strong>Fecha de inicio:</strong>
			<?php
				$fecha1 = date_create($periodo['Periodo']['fecha_inicio']);
				echo date_format($fecha1, 'd-m-Y');
			?></p>
			<p><strong>Fecha de finalización:</strong>
			<?php 
				$fecha2 = date_create($periodo['Periodo']['fecha_fin']);
				echo date_format($fecha2, 'd-m-Y');
			?>
			</p><br><br>
			<p>
			<button id="eliminar" class="btn btn-danger"><i class="icon icon-times"></i> Eliminar</button>
			</p>
			<br><br>
			<?php endif; ?>
	</div>	<br><br><br><br>
</div><br>
<script type="text/javascript">
// <![CDATA[

$('#eliminar').on('click',function(){
	$('#modalEliminar').modal('show');
	return false;
});



$('#PeriodoFechaInicio').val('');
$('#PeriodoFechaFin').val('');


$('#PeriodoFechaInicio').hover(function() {
$(this).css('cursor','pointer');
});
$('#PeriodoFechaFin').hover(function() {
$(this).css('cursor','pointer');
});



$('#PeriodoFechaInicio').datepicker({
	dateFormat: 'dd/mm/yy',
	buttonImageOnly: true
});

$('#PeriodoFechaFin').datepicker({
	dateFormat: 'dd/mm/yy',
	buttonImageOnly: true
});


function validaFecha()
{
	var desde		= $('#PeriodoFechaInicio').val();
	var hasta		= $('#PeriodoFechaFin').val();
	
	var split_desde = desde.split('/');
	var split_hasta = hasta.split('/');
	var date_desde = new Date(split_desde[2], split_desde[1] - 1, split_desde[0]); //Y M D 
	var timestamp_desde = date_desde.getTime();
	var date_hasta = new Date(split_hasta[2], split_hasta[1] - 1, split_hasta[0]); //Y M D 
	var timestamp_hasta = date_hasta.getTime();
	
	if(timestamp_desde >= timestamp_hasta)
	{
		
		alert('La fecha de inicio del período de postulación ha de ser menor que el de finalización.');
		$('#PeriodoFechaInicio').val('');
		$('#PeriodoFechaFin').val('');
		return false;
		//event.stopImmediatePropagation();
	}else{
		$('#PeriodoPeriodopostulacionForm').submit();
	}
}
// ]]>
</script>
