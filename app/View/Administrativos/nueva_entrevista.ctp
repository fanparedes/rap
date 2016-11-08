<div class="row-fluid hora_agregar">
	<div class="span4">
		<a id="agregar_hora" class="btn btn-primary "><i class="icon-plus-circle"></i> Agregar Hora</a>
	</div>
	<div class="span8" style="text-align: right; padding-top:5px;">
	  	<font style="font-size: 18px;">Fecha seleccionada: <?php echo date('d-m-Y',strtotime($fecha)); ?></font>
	</div>
</div>
<?php 
	$fecha2 =date("d-m-Y",strtotime($fecha)); 
	$fecha2 = str_replace("-", "/", $fecha2);
?>
<script>
	 $("#agregar_hora").popover({
        placement: 'right',
        html: 'true',
        title : '<strong style="font-size:20px;">Nueva hora</strong>'+
                '<button type="button" class="close" onclick="$(&quot;#agregar_hora&quot;).popover(&quot;hide&quot;);">&times;</button>',
        content :
        '<h3><?php echo "FECHA: ".$fecha2; ?></h3><form onsubmit="return validarForm(this);" class="form-horizontal" action="<?php echo $this->html->url(array("action" => "guardarHora" ));?>" method="POST" ><input type="hidden" value="<?php echo $fecha;?>" /><input type="hidden" value="<?php echo $codigo; ?>" name="data[Horario][administrativo_codigo]"/><input type="hidden" value="<?php echo $fecha; ?>" name="data[Horario][fecha]"/><div class="control-group"><label>Desde:</label><select id="form-field-input-hora-inicio" required  type="select" name="data[Horario][hora_inicio]" class="span5"><option value="">Hora</option><?php for($i=0; $i<24; $i++):?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php endfor;?></select> <select id="form-field-input-min-inicio" required  type="select" name="data[Horario][min_inicio]" class="span6"><option value="">Minutos</option><option value="00">00</option>		<option value="15">15</option>		<option value="30">30</option>		<option value="45">45</option></select>  </div>   <div class="control-group">   	  <label>Hasta:</label>    <select id="form-field-input-hora-final" required  type="select" name="data[Horario][hora_fin]" class="span5">  	<option value="">Hora</option>		<?php for($i=0; $i<24; $i++):?>			<option value="<?php echo $i;?>"><?php echo $i;?></option>		<?php endfor;?></select>    <select id="form-field-input-min-final" required  type="select" name="data[Horario][min_fin]" class="span6">   	<option value="">Minutos</option>		<option value="00">00</option>		<option value="15">15</option>		<option value="30">30</option>		<option value="45">45</option>    </select> </div><div class="control-group"><div id="mensajes"></div></div><div class="control-group">    <div class="controls" style="margin-left:70px;">    <input type="submit" value="Agregar Hora" class="btn btn-success" />    </div>  </div></form>'
    });
    
    function validarForm(formulario){
    	var horaInicio = $('#form-field-input-hora-inicio').val();
		var minInicio = $('#form-field-input-min-inicio').val();
		var horaFinal = $('#form-field-input-hora-final').val();
		var minFinal = $('#form-field-input-min-final').val();
		
		var hora_desde = horaInicio+':'+minInicio;
		var hora_hasta = horaFinal+':'+minFinal;
		
	
		
		
		if(parseInt(horaInicio) > parseInt(horaFinal)){
			$('#mensajes').html('<div class="alert alert-error">Error! La hora de Inicio no puede ser menor a la hora final.<a class="close" href="#" data-dismiss="alert">×</a></div>');
			return false;
		}
		
   }
</script>
<div class="hora_agregar">
	<br/>
	<?php if(!empty($horarios)): #debug($horarios);?>
		<table class="table table-bordered table-hover" style="margin-top:-2px; border-radius: 0px">
			<thead>
				<tr>
					<th>N°</th>
					<th>Desde</th>
					<th>Hasta</th>
					<th>Estado</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody class="hora_seleccionadas" >
				<?php
				$i = 1;  
				foreach($horarios as $valor):
					$desde = $valor['Horario']['hora_inicio'];
					$hasta = $valor['Horario']['hora_fin'];
            		$hora= date('H',strtotime($desde));
					$min= date('i',strtotime($desde));
				?>
				<tr>
					<td class="nro"><?php echo $i?></td>
					<td class="desde"><?php echo $hora.":".$min?></td>
					<?php 
						$hora= date('H',strtotime($hasta));
						$min= date('i',strtotime($hasta));
					?>
					<td class="hasta"><?php echo $hora.":".$min?></td>
					<td><?php echo $valor['Horario']['estado']; ?></td>
					<td class="acciones">
						<?php 
							if($valor['Horario']['estado']!='AGENDADO'){
								echo $this->Form->postLink("<i class='icon-trash-o'></i> Eliminar",
						  			array('action' => 'eliminarHora',  $valor['Horario']['codigo'], $valor['Horario']['administrativo_codigo'], $fecha),
						  			array('class' => "btn btn-danger","escape"=>false),
						  			__('¿Esta seguro que desea ELIMINAR esta Hora?')
								);	
							}else{
								echo "--";
							}
						?>
					</td>
				</tr>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
	<?php else:?>
		<div class="alert alert-info" align="center">
			<h4 >No tiene entrevista asignadas</h4>
		</div>	
	<?php endif;?>
</div>