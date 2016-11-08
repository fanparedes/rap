<style>
	.acciones{width:110px;}
</style>
<br/>
<form method="POST" action="#">
	<input type="text" name="accounts" size="20" class="typeahead-devs" placeholder="Please Enter Day Name">
</form>
<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Listado de Postulaciones:</h3>
	</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Postulante</th>
					<th>Carrera</th>
					<th>Sede</th>
					<th>Jornada</th>
					<th>Estado</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulaciones)):  $aux=1;
				foreach($postulaciones as $k => $postulacion):?>
					<?php if($postulacion['Postulacion']['activo'] == 1 && $postulacion['Postulante']['activo'] == 1 ): ?>
						<tr>
							<td><?php echo $aux; ?></td>
							<td><?php echo strtoupper($postulacion['Postulante']['nombre']); ?></td>
							<td><?php echo $postulacion['Carrera']['nombre'];?></td>
							<td><?php echo $postulacion['Sede']['nombre_sede'];?></td>
							<td><?php echo $postulacion['Postulacion']['jornada']?></td>
							<td><?php echo $postulacion['Estado'];?></td>
							<td class="acciones">
								<a href="<?php echo $this->Html->url(array('controller'=>'evaluadores','action'=>'postulaciones', $postulacion['Postulacion']['codigo'])); ?>" class="btn"> 
									<i class="icon-file-text"></i>&nbsp;Ver Postulación
								</a>
							</td>
						</tr>
						<?php $aux++;?>
					<?php endif;?>
				<?php endforeach; else: ?>
					<tr><td colspan="6" style="text-align: center">No existen postulaciones</td></tr>	
				<?php endif; ?>
			</tbody>
		</table>		
	</div>	
</div>
<br/>
