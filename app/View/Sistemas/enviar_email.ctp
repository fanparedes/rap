<style>
	.acciones{width:110px;}
</style>
<br/>
<div class="row-fluid">
	<div class="span11 menu-home">
			<div class="pull-right">
				<ul id="menu-full">
					<li id="prin">
						<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-plus"></i></div><div class="li-menu-texto">Nuevo</div>', array('controller' => 'sistemas', 'action' => 'add_sede'), array('escape' => false)); ?>
					</li>
				</ul>
			</div>
		</div>
	<div class="span10 offset1">
		<h3>Listado de Sedes</h3>
	</div>
</div>
<div class="row-fluid">
	
	<div class="span6 offset1">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Sedes</th>
					<th width="35%">Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($sede as $sedes):?>
				<tr>
					<td><?php echo $sedes['Sede']['nombre_sede']; ?></td>
					<td><?php echo $this->Html->link('<i class="icon-edit"></i> Editar ', array('controller' => 'sistemas', 'action' => 'edit_sede', $sedes['Sede']['codigo_sede']), array('escape' => false, 'class' => 'btn')); ?>
						<?php echo $this->Html->link('<i class="icon-times"></i> Eliminar  ', array('controller' => 'sistemas', 'action' => 'delete_sede', $sedes['Sede']['codigo_sede']), array('escape' => false, 'class' => 'btn btn-danger'), sprintf('¿Estas seguro de eliminar # %s, esto podría crear inconsistencias en la base de datos?', ($sedes['Sede']['nombre_sede']))); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>	
	</div>	
</div>
<br/>
