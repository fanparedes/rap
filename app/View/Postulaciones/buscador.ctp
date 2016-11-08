<style>
	.acciones{width:110px;}
h1{
font-size: 20px;
color: #111;
}
.content{
	width: 80%;
	margin: 0 auto;
	margin-top: 50px;
}

.typeahead-devs, .tt-hint,.country {
 	border: 2px solid #CCCCCC;
    border-radius: 8px 8px 8px 8px;
    font-size: 24px;
    height: 45px;
    line-height: 30px;
    outline: medium none;
    padding: 8px 12px;
    width: 200px;
}

.tt-dropdown-menu {
  width: 200px;
  margin-top: 5px;
  padding: 8px 12px;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 8px 8px 8px 8px;
  font-size: 18px;
  color: #111;
  background-color: #F1F1F1;
}
</style>
<script>
$(document).ready(function() {
	
	$('a.btn.buscador').click(function(){
	if($('#PostulacionesBuscar').val() == '')
	{
		alert('Debe ingresar al menos un caracter');
		return false;
	}else{
		$('#PostulacionesBuscadorForm').submit();
	}
})



$('input.country').typeahead({
	name: 'data[Postulaciones][buscar]',
    remote : webroot +'postulaciones/ajax_autocompletar/%QUERY'
});


})

</script>
<br/>
<div class="span3" style="float:right;margin-right: 51px;">
	<?php echo $this->Form->create('Postulaciones', array('action' => 'buscador','style'=>"margin-left: -65px;")) ;?>
	<?php echo $this->Form->input('buscar', array('size' => 20,'class' => 'country','data-provide'=>'typeahead','placeholder' => 'Ingrese datos','label' => false)) ;?>
	<span style="float: right; margin-top: -40px; margin-left: 0px; margin-right: 50px;">
		<a href=#" class="btn buscador"> 
			<i class="icon-binoculars"></i>&nbsp;Buscar
		</a>
	</span>
	<?php echo $this->Form->end()?>
</div>

<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Cantidad de resultados de busqueda Postulaciones :&nbsp; <?php echo $catidad_result?></h3>
	</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Postulante</th>
					<th>RUT</th>
					<th>Fecha de Nacimiento</th>
					<th>Email</th>
					<th id="accion">Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulantes)):
				foreach($postulantes as $k => $postulante):?>
				<tr>
					<td><?php echo ($k+1); ?></td>
					<td><?php echo strtoupper($postulante['Postulante']['nombre']); ?></td>
					<td><?php echo number_format(substr($postulante['Postulante']['rut'],0,-1 ),0,"",".").'-'.substr($postulante['Postulante']['rut'],strlen($postulante['Postulante']['rut'])-1,1); ?></td>
					<td><?php echo date('d-m-Y',strtotime($postulante['Postulante']['fecha_nacimiento'])); ?></td>
					<td><?php echo $postulante['Postulante']['email']; ?></td>
					<td class="acciones" align="center">
						<a href="<?php echo $this->Html->url(array('controller'=>'Administrativos', 'action'=>'updateData',$postulante['Postulante']['codigo'])) ; ?>" class="btn"> 
							<i class="icon-file-text"></i>&nbsp;Actualizar Datos
						</a>
						<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones', $postulante['Postulacion']['codigo'])); ?>" class="btn"> 
							<i class="icon-search">&nbsp;Ver Postulación</i>
						</a>
					</td>
				</tr>
				<?php endforeach; else: ?>
					<tr><td colspan="6" style="text-align: center">No existen postulaciones</td></tr>	
				<?php endif; ?>
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
<br/>
