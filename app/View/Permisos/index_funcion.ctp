<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-plus"></i></div><div class="li-menu-texto">Nuevo</div>', array('controller' => 'permisos', 'action' => 'add_funcion'), array('escape' => false)); ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
		<h3>Tipos de permisos disponibles:</h3>
		<div class="row-fluid">
			<div class="span12">
				<table cellpadding="0" cellspacing="0" class="table table-bordered table-hover">
				<thead>
						<th style="width:4%">ID</th>						
						<th>Controlador</th>
						<th class="actions" style="width:30%">Funcion</th>
						<th class="actions" style="width:30%">Nombre</th>
						<th style="width:10%">Icono</th>
						<th style="width:20%">Funciones</th>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($funciones as $funcion):
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
						?>
					<tr>
						<td><?php echo $funcion['Funcion']['id']; ?>&nbsp;</td>	
						<td class="capitalizar"><?php echo $funcion['Funcion']['controlador']; ?>&nbsp;</td>						
						<td class="capitalizar"><?php echo $funcion['Funcion']['funcion']; ?>&nbsp;</td>						
						<td class="capitalizar"><?php echo $funcion['Funcion']['friendly']; ?>&nbsp;</td>	
						<td class="celda-iconos"><div class='<?php echo $funcion['Funcion']['clase']; ?>'></div></td>								
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-edit"></i> Editar ', array('controller' => 'permisos', 'action' => 'edit_funcion', $funcion['Funcion']['id']), array('escape' => false, 'class' => 'btn')); ?>
							<?php echo $this->Html->link('<i class="icon-times"></i> Eliminar  ', array('controller' => 'permisos', 'action' => 'delete_funcion', $funcion['Funcion']['id']), array('escape' => false, 'class' => 'btn btn-danger'), sprintf('¿Estas seguro de eliminar # %s?', $funcion['Funcion']['controlador'].' '.$funcion['Funcion']['funcion'])); ?>
						</td>
					</tr>
				</tbody>
					<?php endforeach; ?>
				</table>
			</div>
		</div>	
	</div>
</div>
