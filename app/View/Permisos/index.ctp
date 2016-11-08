<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-plus"></i></div><div class="li-menu-texto">Nuevo</div>', array('controller' => 'permisos', 'action' => 'add'), array('escape' => false)); ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
		<h3>Perfiles</h3>
		<div class="row-fluid">
			<div class="span12">
				<table cellpadding="0" cellspacing="0" class="table table-bordered table-hover">
				<thead>
						<th style="width:10%">ID</th>
						<th>Nombre</th>
						<th class="actions" style="width:30%">Funciones</th>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($perfiles as $Perfil):
					?>
					<tr>
						<td><?php echo $Perfil['Perfil']['id']; ?>&nbsp;</td>						
						<td class="capitalizar"><?php echo $Perfil['Perfil']['perfil']; ?>&nbsp;</td>						
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-edit"></i> Editar ', array('controller' => 'permisos', 'action' => 'edit', $Perfil['Perfil']['id']), array('escape' => false, 'class' => 'btn')); ?>
							<?php echo $this->Html->link('<i class="icon-times"></i> Eliminar  ', array('controller' => 'permisos', 'action' => 'delete', $Perfil['Perfil']['id']), array('escape' => false, 'class' => 'btn btn-danger'), sprintf('¿Estas seguro de eliminar # %s?', $Perfil['Perfil']['perfil'])); ?>
						</td>
					</tr>
				</tbody>
					<?php endforeach; ?>
				</table>
			</div>
		</div>	
	</div>
</div>