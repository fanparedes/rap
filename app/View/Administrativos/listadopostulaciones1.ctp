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
	});
	
	
	$('input.country').typeahead({
		limit: 10,
		name: 'data[Administrativos][buscar]',
		remote : webroot +'administrativos/ajax_autocompletar/RAP/%QUERY',
	});
	
	
});

</script>
<br/>
<?php if($alerta==true): ?>
<div class="row-fluid">
	<div class="span10 offset1 alert alert-warning alerta">
		<div style="padding:15px; display:block; width:40px; float:left;"><i class="icon-warning" style="font-size:30px; margin-right:25px;"></i></div><span class="texto-alerta"> Existen postulaciones que requieren de acciones por parte del administrativo. <?php echo $this->Html->link('Ir al listado de alertas',array('controller' => 'administrativos', 'action' => 'listadoalertas')); ?></span>
	</div>
</div>
<?php endif; ?>

<div class="row-fluid">	
	<div class="span3 offset1">
		<h3>Listado de Postulaciones</h3>
	</div>
	<div class="span7"><span class="pull-right">
		<!-- BUSCADOR -->
			<?php echo $this->Form->create('Administrativos', array('action' => 'buscador', 	'inputDefaults' => array(
				'div' => false,
				'label' => false,
				'wrapInput' => false,
				'accept-charset' => 'utf-8'
			),
			'class' => 'form-inline buscador')) ;?>
			<span><?php echo '<img src="'.$this->webroot.'img/loader2.gif" width="16" id="loader">'; ?></span>
				<?php echo $this->Form->input('buscar', array('size' => 20,'class' => 'country','data-provide'=>'typeahead','placeholder' => 'Ingrese búsqueda','label' => false, 'style' => '-webkit-border-radius: 20px;		-moz-border-radius: 20px;		border-radius: 20px;')) ;?>
				<?php echo $this->Form->input('tipo', array('type' => 'hidden', 'value' => 'RAP'));?>
				<button class="btn redondos btn-warning"><i class="icon-search"></i>&nbsp;</button>
			</span>
		<!--fIN BUSCADOR -->
		<?php $this->Form->end() ;?>
	</div>
</div>
<div class="row-fluid">
	<div class="span3 offset1">
		<?php echo $this->Element('filtro-estado');?><?php echo $this->Form->end()?>	
	</div>
</div>
<!-- Si se activa el -1 se muestra otra tabla con los datos de los candidatos que no han sido activados en el sistema -->
<?php if(isset($this->params) && ! empty($this->params['pass'][0])  && ($this->params['pass'][0] !== '-1')) :?>
<div class="row-fluid">
	<div class="span10 offset1">
	<br>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="30"><a href="<?php echo $this->webroot.'administracion/administrativos/listadopostulaciones';?>">#</a></th>
					<?php if (isset($this->params['named']['sort'])){$orden = $this->params['named']['sort'];}
							else {$orden=null;}
					?>
					<th  width="8%;">
						<?php echo $this->Paginator->sort('correlativo', 'Id'); 
						if ($orden == 'correlativo'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?>
					</th>
					<th  width="7%;">
						<?php echo $this->Paginator->sort('nombre', 'Nombre'); 
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
						?>
					</th>
					<th  width="7%;">
						<?php echo $this->Paginator->sort('apellidop', 'A.Paterno'); 
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
						?>
					</th>
					<th  width="7%;">
						<?php echo $this->Paginator->sort('apellidom', 'A.Materno'); 
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
						?>
					</th>					
					<th width="20%;">
					<?php echo $this->Paginator->sort('carrera', 'Carrera');						
						if ($orden == 'carrera') {
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'carrera') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
					?>	
					</th>
					<th width="10%;><?php echo $this->Paginator->sort('sede', 'Sede');						
						if ($orden == 'sede'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'sede') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
					</th>
					
					<th style="width: 100px;"><?php echo $this->Paginator->sort('jornada', 'Jornada');						
						if ($orden == 'jornada'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'jornada') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
						</th>
						
						<th><?php echo $this->Paginator->sort('estado', 'Estado');						
						if ($orden == 'estado'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'estado') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
						</th>
						
						<th style="width:121px;"><?php echo $this->Paginator->sort('fecha', 'Modificada');						
						if($orden == 'fecha'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if(($orden !== 'fecha') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
						</th>
						
						<th style="width:55px;"><?php echo $this->Paginator->sort('fecha_creacion', 'Creada');						
						if($orden == 'fecha_creacion'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if(($orden !== 'fecha_creacion') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
						</th>
						
					
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulaciones)):  $aux=0;
				
				foreach($postulaciones as $k => $postulacion):?>
					
					<tr>
						<?php if($this->params['paging']['EstadoPostulacion']['page'] == 1){ $variable = (0);
						}
						else{
							$variable = $this->params['paging']['EstadoPostulacion']['page']-1;	
						} ;?>
						<td><?php echo ($k +1 )+($variable*20);?></td>
						<td><?php if (isset($postulacion['Postulaciones']['id_correlativo'])){ echo ($postulacion['Postulaciones']['id_correlativo']);  }?></td>
						<td><?php echo strtoupper($postulacion['Postulantes']['nombre']); ?></td>
						<td><?php echo strtoupper($postulacion['Postulantes']['apellidop']); ?></td>
						<td><?php echo strtoupper($postulacion['Postulantes']['apellidom']); ?></td>
						<td><?php echo $postulacion['Carrera']['nombre'];?></td>
						<td><?php echo $postulacion['Sede']['nombre_sede'];?></td>
						<td><?php echo $postulacion['Postulaciones']['jornada']?></td>
						<td><?php
							
							$estado = $postulacion[0]['maximo'];							
							$evidencias_previas = $postulacion[0]['evidencia'];
							$evidencias_finales = $postulacion[0]['evidenciafinal'];
							switch ($estado) {
								case null:
									echo 'PENDIENTE DE COMPLETAR POSTULACIÓN RAP';
									break;
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
									if ($evidencias_previas == 1 ){echo "EVIDENCIAS PREVIAS VALIDADAS";}
									else { echo "CV RAP Y AUTOEVALUACIÓN APROBADO";}
									
									break;
								case 7:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;		
								case 8:
									if ($evidencias_finales == null) { echo "ENTREVISTA"; } 
									if ($evidencias_finales =='0') { echo "EVIDENCIAS FINALES VALIDADAS"; }
									if ($evidencias_finales == '1') { echo "ENTREVISTA"; }
									break;
								case 9:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								
							}
						
							?>
						</td>
						<td>
							<?php 
								$fecha = $this->Ingenia->formatearFecha($postulacion[0]['fechita']);
								echo substr($fecha, 0,10);							
							?>						
						</td>
						<td>
							<?php 
								$fecha = $this->Ingenia->formatearFecha($postulacion[0]['fecha_creacion']);
								echo substr($fecha, 0,10);							
							?>						
						</td>						
						<?php  if($postulacion['Postulantes']['activo'] == 1) :?>
						<td class="acciones">
							<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones', $postulacion['EstadoPostulacion']['postulacion_codigo'])); ?>" class="btn"> 
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

<!-- Si se activa el -1 se muestra otra tabla con los datos de los candidatos que no han sido activados en el sistema -->
<?php elseif(isset($this->params) && ! empty($this->params['pass'][0]) && ($this->params['pass'][0] == '-1')) : ?>

<div class="row-fluid">
	<div class="span10 offset1">
	<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th style="width:25px;">#</th>
					<th>Nombre</th>
					<th width="60">RUT</th>
					<th width="120">Fecha de Nacimiento</th>
					<th>Email</th>
					<th>Teléfono</th>
					<th id="accion" width="200">Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulantesNoActivos)):
				foreach($postulantesNoActivos as $k => $postulante):?>
				<tr>
					<td><?php echo ($k+1); ?></td>
					<td><?php echo strtoupper($postulante['Postulante']['nombre']); ?></td>
					<td><?php echo number_format(substr($postulante['Postulante']['rut'],0,-1 ),0,"",".").'-'.substr($postulante['Postulante']['rut'],strlen($postulante['Postulante']['rut'])-1,1); ?></td>
					<td><?php echo date('d-m-Y',strtotime($postulante['Postulante']['fecha_nacimiento'])); ?></td>
					<td><?php echo $postulante['Postulante']['email']; ?></td>
					<td><?php echo $postulante['Postulante']['telefonomovil']; ?></td>
					<td class="acciones" align="center">
						<a href="<?php echo $this->Html->url(array('controller'=>'Administrativos', 'action'=>'updateData',$postulante['Postulante']['codigo'])) ; ?>" class="btn"> 
							<i class="icon-file-text"></i>&nbsp;Actualizar Datos
						</a>
						<?php if (isset($postulante['Postulacion']['codigo'])): ?>
							<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones', $postulante['Postulacion']['codigo'])); ?>" class="btn"> 
								<i class="icon-search"></i>&nbsp;Ver Postulación
							</a>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; else: ?>
					<tr><td colspan="8" style="text-align: center">No existen postulaciones</td></tr>	
				<?php endif; ?>
			</tbody>
		</table>
	
	
	
	
	</div>
</div>



<!-- En el caso de que no se envien parámetros a la vista por tanto sin filtro -->
<?php else :?>
<div class="row-fluid">
	<div class="span10 offset1">
	<br>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="30"><a href="<?php echo $this->webroot.'administracion/administrativos/listadopostulaciones';?>">#</a></th>
					<?php if (isset($this->params['named']['sort'])) {$orden = $this->params['named']['sort'];}
							else {$orden=null;}
					?>
					<th  width="8%;">
						<?php echo $this->Paginator->sort('correlativo', 'Id'); 
						if ($orden == 'correlativo'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						?>
					</th>
					<th width="15%;">
						<?php echo $this->Paginator->sort('nombre', 'Nombre'); 
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
						?>
					
					
					</th>
					<th width="10%;">
						<?php echo $this->Paginator->sort('apellidop', 'A.Paterno'); 
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
						?>		
					</th>
					<th width="10%;">
						<?php echo $this->Paginator->sort('apellidom', 'A.Materno'); 
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
						?>		
					</th>					
					<th style="width:20%;">
					<?php echo $this->Paginator->sort('carrera', 'Carrera');						
						if ($orden == 'carrera') {
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'carrera') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
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
						if (($orden !== 'sede') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
					</th>
					<th style="width:9%;"><?php echo $this->Paginator->sort('jornada', 'Jornada');						
						if ($orden == 'jornada'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'jornada') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?></th>
						<th>
							<?php echo $this->Paginator->sort('estado', 'Estado');						
							if ($orden == 'estado'){
								if ($this->params['named']['direction'] == 'asc') {
									echo ' <i class="icon-caret-down flechita"></i>';
								}
								else {
									echo ' <i class="icon-caret-up flechita"></i>';
								}
							}
							if (($orden !== 'estado') || ($orden == null)){
									echo ' <i class="icon-caret-down flechita2"></i>';
							}
							?>
						</th>						
						<th style="width:11%;"><?php echo $this->Paginator->sort('fecha', 'Mod.');						
						if ($orden == 'fecha'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'fecha') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
						</th>
						
						<th style="width:75px;"><?php echo $this->Paginator->sort('fecha_creacion', 'Creada');						
						if ($orden == 'fecha_creacion'){
							if ($this->params['named']['direction'] == 'asc') {
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else {
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if (($orden !== 'fecha_creacion') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
						</th>
						
						
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
			<?php  ?>
				<?php
				foreach($postulaciones as $k => $postulacion):?>
				
					<tr>
						<?php if($this->params['paging']['Postulacion']['page'] == 1){ $variable = (0);
						}
						else{
							$variable = $this->params['paging']['Postulacion']['page']-1;	
						} ;?>
						<td><?php echo ($k +1 )+($variable*20);?></td>
						<td><?php if (isset($postulacion['Postulacion']['id_correlativo'])){ echo ($postulacion['Postulacion']['id_correlativo']);  }?></td>
						<td><?php echo strtoupper($postulacion['Postulante']['nombre']); ?></td>
						<td><?php echo strtoupper($postulacion['Postulante']['apellidop']); ?></td>
						<td><?php echo strtoupper($postulacion['Postulante']['apellidom']); ?></td>
						<td><?php echo $postulacion['Carrera']['nombre'];?></td>
						<td><?php echo $postulacion['Sede']['nombre_sede'];?></td>
						<td><?php echo $postulacion['Postulacion']['jornada']?></td>
						<td><?php
						
							$estado = $postulacion[0]['maximo'];
							
							$evidencias_previas = $postulacion[0]['evidencia'];
							$evidencias_finales = $postulacion[0]['evidenciafinal'];
							switch ($estado) {
								case null:
									echo 'PENDIENTE DE COMPLETAR POSTULACIÓN RAP';
									break;
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
									if ($evidencias_previas	== 1){ echo "EVIDENCIAS PREVIAS VALIDADAS"; }
									else { echo "CV RAP Y AUTOEVALUACION APROBADO";}
									break;
								case 7:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;		
								case 8:
									
									if ($evidencias_finales == null) { echo "ENTREVISTA"; } 
									if ($evidencias_finales =='0') { echo "EVIDENCIAS FINALES VALIDADAS"; }
									if ($evidencias_finales == '1') { echo "ENTREVISTA"; } 
									break;
								case 9:
									echo $estados[$estado-1]['Estado']['nombre'];
									break;
								
							}
						
							?>
						</td>
						<td>
							<?php 
								$fecha = $this->Ingenia->formatearFecha($postulacion[0]['fechita']);
								echo substr($fecha, 0,10);							
							?>
						
						</td>	
						<td>
							<?php 
								$fecha = $this->Ingenia->formatearFecha($postulacion[0]['fecha_creacion']);
								echo substr($fecha, 0,10);							
							?>
						
						</td>							
						<?php  if($postulacion['Postulacion']['activo'] == 1) :?>
						<td class="acciones">
							<?php if ($estado !== null): //ESTO ES PORQUE HAY UN ESTADO PREVIO AL PASO 1 AHORA EN RAP?>
								<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones', $postulacion['Postulacion']['codigo'])); ?>" class="btn"> 
									<i class="icon-file-text"></i>&nbsp;Ver Postulación
								</a>
							<?php endif; ?>
						</td>
						<?php else : ?>
							<td class="acciones">
								
							</td>
						<?php endif ;?>
					</tr>
				<?php endforeach ;?>
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
$("#loader").hide();
var busqueda = 1;
$("#AdministrativosBuscar").keyup(function(){
	busqueda = 1;
	mostrar();	
});
$('#filtro').change( function() {
 var valor = $(this).val();
 window.location.href = webroot + 'administrativos/listadopostulaciones/' + valor;
});


function mostrar(){
$("#loader").show();
	if($('.tt-dropdown-menu').is(':hidden') && ((busqueda < 30)) ) {
		busqueda++;		
		setTimeout(mostrar, 400);
	}
	else {			
		esconder();	
	}
	
}

function esconder() {
     $("#loader").hide();	
}

</script>