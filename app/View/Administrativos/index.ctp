<br/>
<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Postulaciones Pendientes Para Revisión</h3>
	</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Postulante</th>
					<th>Carrera</th>
					<th>Sede</th>
					<th>Jornada</th>
					<th>Estado</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulaciones)):
					foreach($postulaciones as $postulacion):?>
				<tr>
					<td><?php echo $postulacion['i']['nombre_postulante']; ?></td>
					<td><?php echo $postulacion['h']['nombre_carrera']?></td>
					<td><?php echo $postulacion['d']['nombre_sede']?></td>
					<td><?php echo $postulacion['c']['jornada']?></td>
					<td><?php echo $postulacion['Estado']['nombre_estado']; ?></td>
					<td>
						<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones', $postulacion['b']['postulacion_codigo'])); ?>" class="btn"> 
							<i class="icon-file-text"></i>&nbsp;Ver Postulación
						</a>
					</td>
				</tr>
				<?php endforeach; else:?>
				<tr>
					<td colspan="6" style="text-align: center;">No hay postulaciones pendientes</td>
				</tr>	
				<?php endif; ?>
			</tbody>
		</table>
	</div>	
</div>
<br/>