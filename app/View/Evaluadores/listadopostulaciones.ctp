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



</style>
<script>
$(document).ready(function() {
	
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
	limit: 10,
	name: 'data[Administrativos][buscar]',
    remote : webroot +'administrativos/ajax_autocompletar/%QUERY'
});

})

</script>
<br/>


<div class="row-fluid">
	<div class="span6 offset1">
		<h3>Listado de Postulaciones</h3>
	</div>
	<div class="span4"><span class="pull-right">
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
	</span>
	<!--fIN BUSCADOR -->
</div>
</div>
<div class="row-fluid">
	<div class="span3 offset1">
		<?php echo $this->Element('filtro-estado');?><?php echo $this->Form->end()?>	
	</div>
</div>
<?php if(isset($this->params) && ! empty($this->params['pass'][0])) :?>
<div class="row-fluid">
	<div class="span10 offset1">
	<br>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="15">#</th>
					<?php if (isset($this->params['named']['sort'])) {$orden = $this->params['named']['sort'];}
							else {$orden=null;}
					?>
					<th width="30%;">
						<?php echo $this->Paginator->sort('nombre_postulante', 'Nombre'); 
						if ($orden == 'nombre_postulante'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?>
					
					</th>					
					<th>
					<?php echo $this->Paginator->sort('carrera', 'Carrera');						
						if ($orden == 'carrera') {
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?>	
					</th>
					<th><?php echo $this->Paginator->sort('sede', 'Sede');						
						if ($orden == 'sede'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?>
					</th>
					<th style="width: 64px;"><?php echo $this->Paginator->sort('jornada', 'Jornada');						
						if ($orden == 'jornada'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?></th>
					<th><?php echo $this->Paginator->sort('estado', 'Estado');						
						if ($orden == 'estado'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?></th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulaciones)):  $aux=1;
				foreach($postulaciones as $k => $postulacion):?>
					<tr>
						<?php if($this->params['paging']['EstadoPostulacion']['page'] == 1){ $variable = (0);
						}
						else{
							$variable = $this->params['paging']['EstadoPostulacion']['page']-1;	
						} ;?>
						<td><?php echo ($k +1 )+($variable*20);?></td>
						<td><?php echo strtoupper($postulacion['Postulantes']['nombre']); ?></td>
						<td><?php echo $postulacion['Carrera']['nombre'];?></td>
						<td><?php echo $postulacion['Sede']['nombre_sede'];?></td>
						<td><?php echo $postulacion['Postulaciones']['jornada']?></td>
						<td><?php
						$estado = $postulacion[0]['maximo'];
							
							$evidencias_previas = $postulacion[0]['evidencia'];
							$evidencias_finales = $postulacion[0]['evidenciafinal'];
							switch ($estado) {
								case 1:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								case 2:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								case 3:
									echo $estados[$estado-1]['Estado']['nombre']; 
									break;
								case 4:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								case 5:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								case 6:
									if ($evidencia = 1 ){echo "EVIDENCIAS PREVIAS VALIDADAS";}
									if ($evidencia <> 1 ){echo $estados[$estado-1]['Estado']['nombre'];}
									break;
								case 7:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;		
								case 8:
									if ($evidencias_finales == null) { echo "ENTREVISTA"; } 
									if ($evidencias_finales =='0') { echo "EVIDENCIAS FINALES VALIDADAS"; }
									if ($evidencias_finales == '1') { echo "ENTREVISTA AGENDADA"; }
									break;
								case 9:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								
							}
						
							?>
						</td>
							<?php  if(($postulacion['Postulacion']['activo'] == 1) && ($postulacion[0]['maximo'] > 6)) :?>
							<td class="acciones">
								<a href="<?php echo $this->Html->url(array('controller'=>'evaluadores','action'=>'postulaciones', $postulacion['EstadoPostulacion']['postulacion_codigo'])); ?>" class="btn"> 
									<i class="icon-file-text"></i>&nbsp;Ver Postulación
								</a>
							</td>
							<?php else : ?>
							<td class="acciones">
							
							</td>
							<?php endif ;?>
						
						
					</tr>
				<?php endforeach ;?>
				<?php else :?>
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
<?php else :?>
<div class="row-fluid">
	<div class="span10 offset1">
	<br>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="15">#</th>
					<?php if (isset($this->params['named']['sort'])) {$orden = $this->params['named']['sort'];}
							else {$orden=null;}
					?>
					<th width="30%;">
						<?php echo $this->Paginator->sort('nombre_postulante', 'Nombre'); 
						if ($orden == 'nombre_postulante'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?>
					
					
					</th>					
					<th>
					<?php echo $this->Paginator->sort('carrera', 'Carrera');						
						if ($orden == 'carrera') {
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
					?>	
					</th>
					<th><?php echo $this->Paginator->sort('sede', 'Sede');						
						if ($orden == 'sede'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?>
					</th>
					<th style="width: 64px;"><?php echo $this->Paginator->sort('jornada', 'Jornada');						
						if ($orden == 'jornada'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?></th>
					<th><?php echo $this->Paginator->sort('estado', 'Estado');						
						if ($orden == 'estado'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?></th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulaciones)):  $aux=1 ;
				foreach($postulaciones as $k => $postulacion):?>
					<tr>
						<?php if($this->params['paging']['Postulacion']['page'] == 1){ $variable = (0);
						}
						else{
							$variable = $this->params['paging']['Postulacion']['page']-1;	
						} ;?>
						<td><?php echo ($k +1 )+($variable*20);?></td>
						<td><?php echo strtoupper($postulacion['Postulante']['nombre']); ?></td>
						<td><?php echo $postulacion['Carrera']['nombre'];?></td>
						<td><?php echo $postulacion['Sede']['nombre_sede'];?></td>
						<td><?php echo $postulacion['Postulacion']['jornada']?></td>
						<td><?php
						
							$estado = $postulacion[0]['maximo'];
							
							$evidencias_previas = $postulacion[0]['evidencia'];
							$evidencias_finales = $postulacion[0]['evidenciafinal'];
							switch ($estado) {
								case 1:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								case 2:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								case 3:
									echo $estados[$estado-1]['Estado']['nombre']; 
									break;
								case 4:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								case 5:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								case 6:
									if ($evidencia = 1 ){echo "EVIDENCIAS PREVIAS VALIDADAS";}
									if ($evidencia <> 1 ){echo $estados[$estado-1]['Estado']['nombre'];}
									break;
								case 7:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;		
								case 8:
									if ($evidencias_finales == null) { echo "ENTREVISTA"; } 
									if ($evidencias_finales =='0') { echo "EVIDENCIAS FINALES VALIDADAS"; }
									if ($evidencias_finales == '1') { echo "ENTREVISTA AGENDADA"; }
									break;
								case 9:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								
							}
						
							?>
						</td>
						<?php  if(($postulacion['Postulacion']['activo'] == 1) && ($postulacion[0]['maximo'] > 6)) :?>
							<td class="acciones">
								<a href="<?php echo $this->Html->url(array('controller'=>'evaluadores','action'=>'postulaciones', $postulacion['Postulacion']['codigo'])); ?>" class="btn"> 
									<i class="icon-file-text"></i>&nbsp;Ver Postulación
								</a>
							</td>
						<?php else : ?>
						<td class="acciones">
						
						</td>
						<?php endif ;?>
					</tr>
				<?php endforeach ;?>
				<?php else :?>
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
<?php endif ;?>
<br/>
<script>
$( "#loader" ).hide();
$( "#AdministrativosBuscar" ).keyup(function() {
	$("#loader").show().delay(680).fadeOut();  
});
$('#AdministrativosFiltro').change( function() {
 var valor = $(this).val();
 window.location.href = webroot + 'evaluadores/listadopostulaciones/' + valor;
});
</script>