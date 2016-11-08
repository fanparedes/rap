<style>
	.acciones{width:110px;}
</style>
<div id="modalActivar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">		
		<h3 style="text-align: center;">¿Está seguro de querer cambiar el estado del cargo?</h3>
	</div>
	<?php echo $this->Form->create('Sistema', array('action' => 'desactivar_cargo')); ?>
	<?php echo $this->Form->hidden('activo' ,array('value' => ''))?>
		<div class="modal-footer " style="text-align:center">
			<button type="submit" class="btn btn-success"><i class="icon-check"></i> Si</button>
			<a data-dismiss="modal" aria-hidden="true" class="btn btn-danger" href="#"><i class="icon-times"></i> No</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
<br/>
<div class="row-fluid">
	<div class="span11 menu-home">
			<div class="pull-right">
				<ul id="menu-full">
					<li id="prin">
						<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-plus"></i></div><div class="li-menu-texto">Nuevo</div>', array('controller' => 'sistemas', 'action' => 'add_cargo'), array('escape' => false)); ?>
					</li>
				</ul>
			</div>
		</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Listado de Cargos</h3>
	</div>
</div>
<div class="row-fluid">
	
	<div class="span5 offset1">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="2%">Activo</th>
					<th>Cargo</th>
					<th width="28%">Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cargos as $cargo):?>
				<tr>
					<td class="centrado">
						<?php 
								if ($cargo['TipoCargo']['activo'] == 1){
									echo '<i class="icon-circle activar" style="color:#31B404;cursor:pointer"  data-id="'.$cargo['TipoCargo']['codigo'].'"></i>';								
								}
								else{
									echo '<i class="icon-circle activar" style="color:#red;cursor:pointer" id="activar" data-id="'.$cargo['TipoCargo']['codigo'].'"></i>';								
								}					
						?>
					</td>
					<td><?php echo $cargo['TipoCargo']['nombre']; ?></td>
					<td class="centrado"><?php echo $this->Html->link('<i class="icon-edit"></i> Editar ', array('controller' => 'sistemas', 'action' => 'edit_cargo', $cargo['TipoCargo']['codigo']), array('escape' => false, 'class' => 'btn')); ?>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
				echo $this->Paginator->numbers(array(
					'before' => '<div class="pagination"><ul>',
					'separator' => '',
					'currentClass' => 'active',
					'tag' => 'li',
					'after' => '</ul></div>'));
			?>
	</div>	
</div>
<br/><br/>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function(){
	 var accion = $('#SistemaDesactivarCargoForm').attr('action');	 
	 $('.activar').click(function(){
		var id = $(this).data('id');	
		$('#SistemaActivo').val(id);		
		$('#modalActivar').modal('show');		
		})	 
})

</script>