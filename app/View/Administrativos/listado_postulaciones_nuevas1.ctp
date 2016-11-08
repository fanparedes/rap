<?php 
	//Esto es necesario para que el buscador envíe a las escuelas o a las verticales la búsqueda o a todas en el caso de SU
	$tipoAdministrativo = $this->Session->read('UserLogued');
	$perfil = $tipoAdministrativo['Administrativo']['perfil'];
	$tipo2 = $tipoAdministrativo['Administrativo']['tipo'];
	if ($perfil == 0){
		$busqueda = 'TODOS';	
	}
	if (($perfil == 1) && ($tipo2 == 'AH')){
		$busqueda = 'AH';
	}
	if (($perfil == 1) && ($tipo2 == 'AV')){
		$busqueda = 'AV';
	}	
 ?>
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
		limit  : 10,
		name   : 'data[Administrativos][buscar]',
		remote : webroot +'administrativos/ajax_autocompletar/<?php echo $busqueda; ?>/%QUERY',
	});
	
	
});

</script>
<div class="row-fluid">	
	<div class="span6 offset1">
		<?php if (!isset($tipo)): ?>
			<h3>Listado de Postulaciones de Escuelas y Articulación</h3>
		<?php endif; ?>
		<?php if (isset($tipo) && ($tipo == 'AH')): ?>
			<h3>Listado de Postulaciones de Escuelas</h3>
		<?php endif; ?>
		<?php if (isset($tipo) && ($tipo == 'AV')): ?>
			<h3>Listado de Postulaciones de Articulación</h3>
		<?php endif; ?>
	</div>
	<div class="span4">
			<!-- BUSCADOR -->
				<?php echo $this->Form->create('Administrativos', array('action' => 'buscador','style' => 'float:right;','inputDefaults' => array(
					'div' => false,
					'label' => false,
					'wrapInput' => false,
					'accept-charset' => 'utf-8'
				),
				'class' => 'form-inline buscador')) ;?>
				<span><?php echo '<img src="'.$this->webroot.'img/loader2.gif" width="16" id="loader" style="display:none;">'; ?></span>
				<?php echo $this->Form->input('buscar', array('size' => 20,'class' => 'country','data-provide'=>'typeahead','placeholder' => 'Ingrese búsqueda','label' => false, 'style' => '-webkit-border-radius: 20px;		-moz-border-radius: 20px;		border-radius: 20px;')) ;?>
					<?php echo $this->Form->input('tipo', array('type' => 'hidden', 'value' => $busqueda));?>
					<button class="btn redondos btn-warning"><i class="icon-search"></i>&nbsp;</button>
				</span>
			<!--fIN BUSCADOR -->
			<?php echo $this->Form->end() ;?>
			
	</div>
</div>

<div class="row-fluid">	
	<div class="span7 offset1">

	</div>
	<div class="span3"> 
		<?php 
		if(isset($this->params['pass'][0]) && ($this->params['pass'][0] == 'resueltos')){
			echo $this->Html->link('<i class="icon-edit"></i>  Ver Por Resolver', array('controller' => 'Administrativos', 'action' => 'listadoPostulacionesNuevas'), array('style' => 'margin-top:15px;', 'class' => 'btn btn-warning pull-right', 'escape' => false));
		}
		else{
			echo $this->Html->link('<i class="icon-check"></i>  Ver Resueltos', array('controller' => 'Administrativos', 'action' => 'listadoPostulacionesNuevas', 'resueltos'), array('style' => 'margin-top:15px;', 'class' => 'btn btn-warning pull-right', 'escape' => false));					
		}				
		?>
	</div>
</div>

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
					<th style="width:100px;"><?php echo $this->Paginator->sort('fecha_creacion', 'Cre');						
						if($orden == 'fecha_creacion'){
							if($this->params['named']['direction'] == 'asc'){
								echo ' <i class="icon-caret-down flechita"></i>';
							}
							else{
								echo ' <i class="icon-caret-up flechita"></i>';
							}
						}
						if(($orden !== 'fecha_creacion') || ($orden == null)){
								echo ' <i class="icon-caret-down flechita2"></i>';
						}
						?>
					</th>
					<th  width="8%;">
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
					<th  width="10%;">
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
					<th  width="10%;">
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
					<th style="width:25%;">
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
					
					<th style="width:105px;"><?php echo $this->Paginator->sort('fecha', 'Mod');						
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
					<th>
							<?php echo $this->Paginator->sort('tipo', 'Tipo');						
							if ($orden == 'tipo'){
								if ($this->params['named']['direction'] == 'asc') {
									echo ' <i class="icon-caret-down flechita"></i>';
								}
								else {
									echo ' <i class="icon-caret-up flechita"></i>';
								}
							}
							if (($orden !== 'tipo') || ($orden == null)){
									echo ' <i class="icon-caret-down flechita2"></i>';
							}
							?>
						</th>
					<th style="width:11%;">Estado</th>
					<th style="width:65px;">Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($postulaciones)):  $aux=0;
				
				foreach($postulaciones as $k => $postulacion):?>
					
					<tr>
						<?php if($this->params['paging']['Postulacion']['page'] == 1){ $variable = (0);
						}
						else{
							$variable = $this->params['paging']['Postulacion']['page']-1;	
						} ;?>
						<td><?php echo ($k +1 )+($variable*20);?></td>
						<td><?php if ($postulacion['Postulacion']['id_correlativo']){ echo ($postulacion['Postulacion']['id_correlativo']);  } ?> </td>
						<td>
							<?php 
								$fecha = $this->Ingenia->formatearFecha($postulacion['Postulacion']['created']);
								echo ($fecha);							
							?>						
						</td>
						<td><?php echo strtoupper($postulacion['Postulante']['nombre']); ?></td>
						<td><?php echo strtoupper($postulacion['Postulante']['apellidop']); ?></td>
						<td><?php echo strtoupper($postulacion['Postulante']['apellidom']); ?></td>
						<td><?php echo $postulacion['Carrera']['nombre'];?></td>
						<td>
							<?php 	echo $this->Ingenia->formatearFecha($postulacion['Postulacion']['modified']);
								
							?>						
						</td>
						<td>
							<?php 
							if ($postulacion['Postulacion']['tipo'] == 'AH'){
								echo 'ESCUELAS';							
								}
							elseif ($postulacion['Postulacion']['tipo'] == 'AV'){
								echo 'ARTICULACIÓN';							
								}
							else{
								echo $postulacion['Postulacion']['tipo'];
							}
							?>
						
						</td>
						<td>
							<?php 
									if ($postulacion['Postulacion']['revision'] == 1){ echo '';
										if ($postulacion['Postulacion']['habilitado'] == 1) {
												if(($postulacion['Postulacion']['firma'] == 1)){
														echo '<span class="verde">HABILITADO CON FIRMA</span>';
													}
												else{
													echo '<span class="verde">HABILITADO</span>';
												}
												}
										else {
											echo '<span class="rojo">NO HABILITADO</span>';
										}
										}
										else{ 
											echo 'EN REVISIÓN';
										}
										?>					
						</td>
						<td class="acciones">
                                                    <?php
                                                    $usuario_logueado     = $this->Session->read('UserLogued');
                                                    $admin_carrera_codigo = $usuario_logueado['Administrativo']['carrera_codigo'];
                                                    $admin_tipo_usuario   = $usuario_logueado['Administrativo']['tipo']; //No se utiliza ya que se esta validando en controlador...
                   
                                                    if($admin_tipo_usuario != null){//Si no es SuperUsuario...
                                                        
														if($tipo_admin == 'AV'){ //Si es vertical, puede ver todo...
															echo ''.$this->Form->postLink('<i class="icon-file-text"></i>&nbsp;Ver',
																				array('action' => 'verAdmision', $postulacion['Postulacion']['codigo']),
																				array('class' => 'btn', 'escape' => false));
														}
														else{ //Horizontal
															

																echo ''.$this->Form->postLink('<i class="icon-file-text"></i>&nbsp;Ver',
																						array('action' => 'verAdmision', $postulacion['Postulacion']['codigo']),
																						array('class' => 'btn', 'escape' => false));
															
														}
                                                    }
                                                    else if($admin_tipo_usuario == null){//Si es SuperUsuario...
                                                        echo ''.$this->Form->postLink('<i class="icon-file-text"></i>&nbsp;Ver',
                                                                                    array('action' => 'verAdmision', $postulacion['Postulacion']['codigo']),
                                                                                    array('class' => 'btn', 'escape' => false));
                                                    }
                                                    ?>		
						</td>

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
