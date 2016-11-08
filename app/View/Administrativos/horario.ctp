<style>
	.acciones{
		width:115px;
	}	
</style>

<br/>
<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Orientadores</h3>
	</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Nombre Operativo</th>
					<th>Carrera</th>
					<th>Proximo Horario Disponible</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php //print_r($encuestadores); ?>
				<?php if(!empty($orientadores)):?>
				<?php foreach($orientadores as $orientador):?>
				<tr>
					<td><?php echo $orientador['Administrativo']['nombre']?></td>
					<td><?php echo $orientador['Carrera']['nombre']; ?></td>
					<td><?php 
					if($orientador['ProximoHorario'][0]['hora_inicio'] != ''):
						echo date('d/m/Y H:i',strtotime($orientador['ProximoHorario'][0]['hora_inicio']));
					else:
						echo " No tiene horas asignadas";
					endif;
						?></td>
					<td class="acciones">
						<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'orientador', $orientador['Administrativo']['codigo'])); ?>" class="btn"> 
							<i class="icon-clock-o"></i> Configurar Hora</a></td>
				</tr>
				<?php endforeach; ?>
				<?php endif;?>
			</tbody>
		</table>
	</div>
</div>	