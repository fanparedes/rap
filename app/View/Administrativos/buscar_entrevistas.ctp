<br/>
<?php if(!empty($horarios)): //debug($horarios);?>
	<div class="row-fluid">
		<div class="span12">
			<div class="datagrid">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>N°</th>
							<th>Desde</th>
							<th>Hasta</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1; foreach($horarios as $valor):
							$desde = $valor['Horario']['hora_inicio'];
							$hasta = $valor['Horario']['hora_fin'];
						
		                		$hora= date('H',strtotime($desde));
								$min= date('i',strtotime($desde));
							
						?>
						
						<tr>
							<td><?php echo $i?></td>
							<td><?php echo $hora.":".$min?></td>
							<?php 
								$hora= date('H',strtotime($hasta));
								$min= date('i',strtotime($hasta));
							?>
							<td><?php echo $hora.":".$min?></td>
							<td>Eliminar</td>
						</tr>
						<?php $i++; endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php else:?>
	<h1>Este día no tiene entrevista asignadas</h1>	
<?php endif;?>