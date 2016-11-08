<style>
	#accion{
		width: 252px;
	}
</style>
<script>
$(document).ready(function() {
	$('#AdministrativosBuscar').val('');
	$('a.btn.buscador').click(function(){
	if($('#AdministrativosBuscar').val() == '')
	{
		alert('Debe ingresar al menos un caracter');
		return false;
	}else{
		$('#AdministrativosBuscadorForm').submit();
	}
})



$('input.country').typeahead({
	name: 'data[Administrativos][buscar]',
    remote : webroot +'administrativos/ajax_autocompletar/%QUERY'
});


})
</script>
<div id="modalborrarPostulante" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<div class="row-fluid">
			<h3 style="text-align: center;">¿Está seguro que desea ELIMINAR esta Postulación?</h3>
			<br>
			<p><strong><i class="icon-warning"></i> Si acepta se borrarán TODOS los datos asociados al postulante y a la postulación.</strong></p>
		</div>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'borrarPostulante'))?>">	
	  	<?php echo $this->Form->input('postulante', array ('type' => 'hidden', 'value' => '')); ?>
	  	<?php echo $this->Form->input('activo', array ('type' => 'hidden', 'value' => '')); ?>
		<div class="modal-body">	  		
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>
<br/>
<div class="row-fluid">
	<div class="span4 offset1">
		<h3>Postulantes</h3>
	</div>
	<div class="span6">
		<?php if (!isset($this->params['pass'][0])): ?><span><a href="<?php echo $this->webroot.'administracion/administrativos/postulantes/1';?>"><button clasS="btn btn-warning btn-small pull-right" style="margin-top:23px; margin-left:7px;">No activos</button></a></span><?php endif; ?>
		<?php if (isset($this->params['pass'][0]) && ($this->params['pass'][0]==1)): ?><span><a href="<?php echo $this->webroot.'administracion/administrativos/postulantes/';?>"><button clasS="btn btn-warning btn-small pull-right" style="margin-top:23px; margin-left:7px;">Todos</button></a></span><?php endif; ?>
	
	<span class="pull-right">
	<!-- BUSCADOR -->
	<?php echo $this->Form->create('Administrativos', array('action' => 'buscador', 	'inputDefaults' => array(
		'div' => false,
		'label' => false,
		'wrapInput' => false
	),
	'class' => 'form-inline buscador')) ;?>
	<span><?php echo '<img src="'.$this->webroot.'img/loader2.gif" width="16" id="loader">'; ?></span>
	<?php echo $this->Form->input('buscar', array('size' => 20,'class' => 'country','data-provide'=>'typeahead','placeholder' => 'Ingrese búsqueda','label' => false, 'style' => '-webkit-border-radius: 20px;
-moz-border-radius: 20px;
border-radius: 20px;')) ;?>
		<button class="btn redondos btn-warning"><i class="icon-search"></i>&nbsp;</button>
	  	<?php echo $this->Form->input('tipo', array ('type' => 'hidden', 'value' => 'POSTULANTES')); ?>
	</span>
	<?php echo $this->Form->end()?>
	<!--fIN BUSCADOR -->
</div>
</div>
<?php if (isset($this->params['named']['sort'])) {$orden = $this->params['named']['sort'];}
				else {$orden=null;}
?>
<div class="row-fluid">
	<div class="span10 offset1">
		<br>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th style="width:25px;"><a href="<?php echo $this->webroot.'administracion/administrativos/postulantes';?>">#</a>
					</th>
					<th width="150"><?php echo $this->Paginator->sort('nombre', 'Nombre'); 
						if ($orden == 'nombre'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'nombre') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?></th>
						<th width="85"><?php echo $this->Paginator->sort('apellidop', 'A.Paterno'); 
						if ($orden == 'apellidop'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'apellidop') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?></th>
						<th width="85"><?php echo $this->Paginator->sort('apellidom', 'A.Materno'); 
						if ($orden == 'apellidom'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'apellidom') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?></th>
					<th width="60"><?php echo $this->Paginator->sort('rut', 'Rut'); 
						if ($orden == 'rut'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'rut') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?></th>
					<th width="125"><?php echo $this->Paginator->sort('fecha_nacimiento', 'F.nacimiento'); 
						if ($orden == 'fecha_nacimiento'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'fecha_nacimiento') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?></th>
					<th><?php echo $this->Paginator->sort('email', 'Email'); 
						if ($orden == 'email'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'email') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?></th>
						<th style="width:69px;"><?php echo $this->Paginator->sort('fecha_activado', 'Fecha Act.'); 
						if ($orden == 'fecha_activado'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'fecha_activado') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?></th>
						<th>Extranjero</th>
					<th id="accion">Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulantes)):
				foreach($postulantes as $k => $postulante):?>
				<tr>
					<td><?php echo ($k+1); ?></td>
					<td><?php echo strtoupper($postulante['Postulante']['nombre']); ?></td>
					<td><?php echo strtoupper($postulante['Postulante']['apellidop']); ?></td>
					<td><?php echo strtoupper($postulante['Postulante']['apellidom']); ?></td>
					<td><?php echo number_format(substr($postulante['Postulante']['rut'],0,-1 ),0,"",".").'-'.substr($postulante['Postulante']['rut'],strlen($postulante['Postulante']['rut'])-1,1); ?></td>
					<td><?php echo date('d-m-Y',strtotime($postulante['Postulante']['fecha_nacimiento'])); ?></td>
					<td><?php echo $postulante['Postulante']['email']; ?></td>
					<td>
						<?php if (isset($postulante['Postulante']['fecha_activado'])){
							$fecha = $this->Ingenia->formatearFecha($postulante['Postulante']['fecha_activado']);
							echo substr($fecha,0,10);
						} ?>
					</td>
					<td align="center">
						<?php if ($postulante['Postulante']['extranjero'] == 1): echo '<i class="icon-check" style="margin-left:40%"></i>'; endif; ?>
					</td>
					<td class="acciones" align="center">
						<a href="<?php echo $this->Html->url(array('controller'=>'Administrativos', 'action'=>'updateData',$postulante['Postulante']['codigo'])) ; ?>" class="btn btn-small"> 
							<i class="icon-file-text"></i>&nbsp;Datos
						</a>
				
							<a data-activo="<?php //echo($postulante['Postulante']['activo']);?>" data-codigo="<?php echo $postulante['Postulante']['codigo'];?>" href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'borrarPostulante', $postulante['Postulante']['codigo'])); ?>" class="btn btn-small btn-danger borrarPostulante"> 
								<i class="fa icon-trash-o"></i>&nbsp;Borrar
							</a>
	
					</td>
				</tr>
				<?php endforeach; else: ?>
					<tr><td colspan="8" style="text-align: center">No existen postulaciones</td></tr>	
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
<script>
$( "#loader" ).hide();
$( "#AdministrativosBuscar" ).keyup(function() {
	$("#loader").show().delay(680).fadeOut();  
});
</script>
<script>
	$('.borrarPostulante').on('click',function(){
		var codigo = $(this).data('codigo');
		var activo = $(this).data('activo');
		$('#postulante').val(codigo);
		$('#activo').val(activo);
		$('#modalborrarPostulante').modal('show');
		return false;
	});
</script>
