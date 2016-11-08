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
style="margin-left: -65px;"
</script>
<br/>
<div class="span3" style="float:right;margin-right: 51px;">
	<?php echo $this->Form->create('Postulaciones', array('action' => 'buscador','style'=>"margin-left: -65px;", 'accept-charset'=>"ISO-8859-1")) ;?>
	<?php echo $this->Form->input('buscar', array('size' => 20,'class' => 'country','data-provide'=>'typeahead','placeholder' => 'Ingrese búsqueda','label' => false)) ;?>
	<span style="float: right; margin-top: -40px; margin-left: 0px; margin-right: 50px;">
		<a href=#" class="btn buscador"> 
			<i class="icon-binoculars"></i>&nbsp;Buscar
		</a>
	</span>
	<?php echo $this->Form->end()?>
</div>

<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Postulaciones</h3>
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
							<td><?php echo  $postulacion['Estado'];?></td>
							<td class="acciones">
								<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones', $postulacion['Postulacion']['codigo'])); ?>" class="btn"> 
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
