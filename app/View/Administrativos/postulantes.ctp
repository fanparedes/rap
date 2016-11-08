<?php
	echo $this->Html->css('select2.min.css');
	echo $this->Html->script('select2.full.min.js'); 
?>
<style>
	#accion{
		width: 252px;
	}
</style>
<script>
$(document).ready(function() {
	$( document ).tooltip();
	/*$('#AdministrativosBuscar').val('');
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
});*/
	$('#cbBuscar').change( function() {
	 	$("#buscador_form").attr("action", webroot + "administracion/administrativos/buscador").submit();
	});
	$('#cbBuscar').attr('disabled', false).select2({
			  placeholder: "Buscador Nombre | Email | RUT ",
			  allowClear: true,
			  "language": {
			       "noResults": function(){
			           return "No existe información!";
			       }
			   }
			});
	url = webroot + 'administrativos/ajax_autocompletar/POSTULANTE';
	$.ajax({
        url: url,
        type: "POST",
        data: '',
        success: function(json) {
			for(var i in json.result) {
				//console.log(json.result[i]);
				$('#cbBuscar').append("<option value='"+json.value[i]+"'>"+json.result[i]+"</option>");
			}
            
        } // <-- add this
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
	<div class="span3 offset1">
		<h3>Postulantes</h3>
	</div>
</div>
<div class="container-fluid">
	<div class="row-fluid pull-right">
		<div class="span3 offset1">
			
		</div>
		<div class="span7">
			<div class="span8">
				
			</div>
			<div class="span4">
				<form action="" method="POST" id="buscador_form">
					<input type="hidden" name="data[Administrativos][tipo]" value="POSTULANTES">
					<select id="cbBuscar" name="data[Administrativos][buscar]" disabled="disabled" class="form-control">
						<option value="">Cargando...</option>
					</select>
				</form>
			</div>
		</div>
	</div>
	<div class="row-fluid pull-right">
		<div class="span7 offset4">
			<div class="span8">
				
			</div>
			<div class="span4"> 
				<?php if (!isset($this->params['pass'][0])): ?><a href="<?php echo $this->webroot.'administracion/administrativos/postulantes/1';?>"><button clasS="btn btn-warning btn-small pull-right" style="margin-top:23px; margin-left:7px;">No activos</button></a><?php endif; ?>
				<?php if (isset($this->params['pass'][0]) && ($this->params['pass'][0]==1)): ?><a href="<?php echo $this->webroot.'administracion/administrativos/postulantes/';?>"><button clasS="btn btn-warning btn-small pull-right" style="margin-top:23px; margin-left:7px;">Todos</button></a><?php endif; ?>
				<?php echo $this->Form->end()?>
			</div>
		</div>
	</div>
</div>
<?php if (isset($this->params['named']['sort'])) {$orden = $this->params['named']['sort'];}
				else {$orden=null;}
?>
<br>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-12">
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
						<th id="accion" style="width: 80px;">Acción</th>
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
							<a href="<?php echo $this->Html->url(array('controller'=>'Administrativos', 'action'=>'updateData',$postulante['Postulante']['codigo'])) ; ?>" class="btn btn-small" title="Editar Datos"> 
								<i class="icon-file-text"></i>
							</a>
					
								<a data-activo="<?php //echo($postulante['Postulante']['activo']);?>" data-codigo="<?php echo $postulante['Postulante']['codigo'];?>" href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'borrarPostulante', $postulante['Postulante']['codigo'])); ?>" class="btn btn-small btn-danger borrarPostulante" title="Borrar Postulante"> 
									<i class="fa icon-trash-o"></i>
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
