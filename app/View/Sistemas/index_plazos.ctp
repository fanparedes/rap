<style>
	.acciones{width:110px;}
</style>
<br/>
<!--<div class="row-fluid">
	<div class="span11 menu-home">
			<div class="pull-right">
				<ul id="menu-full">
					<li id="prin">
						<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-plus"></i></div><div class="li-menu-texto">Nuevo</div>', array('controller' => 'sistemas', 'action' => 'add_plazo'), array('escape' => false)); ?>
					</li>
				</ul>
			</div>
		</div>
	</div>-->
<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Listado de Plazos por Etapas de la Postulación:</h3>
	</div>
</div>
<div class="row-fluid">
	
	<div class="span6 offset1">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Nº Etapa</th>
					<th>Etapa</th>
					<th>Plazo (Días)</th>
					<th width="35%">Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($plazos as $plazo):?>
				<tr>
					<td><?php echo $plazo['Plazo']['etapa_id']; ?></td>
					<td><?php echo $plazo['Plazo']['etapa']; ?></td>
					<td><?php echo $plazo['Plazo']['plazo']; ?></td>
					<td><?php echo $this->Html->link('<i class="icon-edit"></i> Editar ', array('controller' => 'sistemas', 'action' => 'edit_plazo', $plazo['Plazo']['id']), array('escape' => false, 'class' => 'btn')); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>	
</div>
<br/>
