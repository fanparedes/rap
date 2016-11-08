
<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-plus"></i></div><div class="li-menu-texto">Nuevo</div>', array('controller' => 'permisos', 'action' => 'add_usuario'), array('escape' => false)); ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
		<h3>Listado de Usuarios</h3>
		<div class="row-fluid">
			<div class="span12">
				<table cellpadding="0" cellspacing="0" class="table table-bordered table-hover">
				<thead>
						<th style="width:10%">ID</th>
						<th>Nombre</th>
						<th>Perfil</th>
						<th>Username</th>
						<th>Email</th>
						<th>Tipo</th>
						<th class="actions" style="width:30%">Funciones</th>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($administrativos as $usuario):
					?>
					<tr>
						<td><?php echo $usuario['Administrativo']['codigo']; ?>&nbsp;</td>						
						<td class="capitalizar"><?php echo $usuario['Administrativo']['nombre']; ?>&nbsp;</td>		
						<td class="capitalizar"><?php echo $perfiles[$usuario['Administrativo']['perfil']]; ?>&nbsp;</td>					
						<td><?php echo $usuario['Administrativo']['username']; ?>&nbsp;</td>					
						<td><?php echo $usuario['Administrativo']['email']; ?>&nbsp;</td>					
						<td><?php if ($usuario['Administrativo']['tipo'] == 'AH'): echo 'ESCUELAS'; elseif ($usuario['Administrativo']['tipo'] == 'AV'): echo 'ARTICULACIÓN'; else: echo  $usuario['Administrativo']['tipo']; endif;  ?>&nbsp;</td>					 
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-edit"></i> Editar ', array('controller' => 'permisos', 'action' => 'edit_usuario', $usuario['Administrativo']['codigo']), array('escape' => false, 'class' => 'btn')); ?>
							<?php echo $this->Html->link('<i class="icon-times"></i> Eliminar  ', array('controller' => 'permisos', 'action' => 'delete_usuario', $usuario['Administrativo']['codigo']), array('escape' => false, 'class' => 'btn btn-danger'), sprintf('¿Estas seguro de eliminar # %s?', $usuario['Administrativo']['nombre'])); ?>
						</td>
					</tr>
				</tbody>
					<?php endforeach; ?>
				</table>
			</div>
		</div>	
	</div>
</div>