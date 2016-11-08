<script>
$(document).ready(function() {
	$( document ).tooltip();
});
</script>
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
<div id="modalNota" class="modal hide fade postit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<a data-dismiss="modal" aria-hidden="true" href="#"><img src="<?php echo $this->webroot.'img/pushpin.png';?>" width="32" class="pull-right"></a>
		<h3 style="text-align: center;">Nota</h3>
	</div>	
	<form method="POST" id="formnota" action="#">	
	  	<div class="modal-body">
	  		<div class="row-fluid">
				<?php echo $this->Form->hidden('id', array('name' => 'data[Alerta][nota]','value' => '')) ;?>
					<?php echo $this->Form->input('nota', array('label' => false,'name' => 'data[Alerta][nota]','type' => 'textarea','class' => 'span12 postit textarea', 'maxlength' => 350,'style' => 'text-transform: uppercase;')) ;?>
	  		</div>
	  		
		</div>
		<div class="modal-footer postit" style="text-align:left">			
			<button type="submit" class="btn btn-success" id="btn-success">Guardar</button>
			<button class="btn btn-danger" id="borrar-nota">Borrar</button>
		</div>
	</form>
	<script type="text/javascript">
	// <![CDATA[
		$('button#borrar-nota.btn.btn-danger').on('click',function(){
		var id = $('#formnota input#id').val();
		
		 window.location.href = webroot + 'administrativos/borrarNota/' + id;
		 return false;
		});
		$('button#btn-success.btn.btn-success').on('click',function(){
		var id		= $('#formnota input#id').val(),
			texto	= $('#formnota textarea#nota.span12.postit.textarea').val();
		
		 window.location.href = webroot + 'administrativos/guardarNota/' + id +'/'+ texto;
		 return false;
		});
	// ]]>
	</script>
</div>
<!-- MODAL DE RECORDAR AVISO -->
<div id="modalRecordar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">		
		<h3 style="text-align: center;">Recordar alerta:</h3>
	</div>	
	<form method="POST" id="formrecordar" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'recordarAlerta') )?>">	
	  	<div class="modal-body">		
	  		<div class="row-fluid">
				<div class="span12">
						<div class="alert alert-info" style="margin-top:10px!important;">Esta alerta no volverá a aparecer hasta que pase el plazo indicado en el siguiente formulario.</div>	
				</div>
			</div>
	  		<div class="row-fluid">
				<div class="span2 offset2">					
					<label style="float:left!important">Días: </label>
				</div>
				<div class="span5">
					<select name="data[Alerta][plazo]">
						  <?php 
							for ($i = 1; $i <= 30; $i++) {
								echo '<option value="'.$i.'">'.$i.'</option>';
							};
						  ?>
					 </select>
				</div>				  
	  		</div>
	  		
		</div>
		<div class="modal-footer " style="text-align:center">			
			<button type="submit" class="btn btn-info" id="btn-danger">Confirmar</button>
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		</div>
	</form>
</div>
<!-- MODAL DE BORRAR AVISO -->
<div id="modalBorrar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">		
		<h3 style="text-align: center;">¿Está seguro de que desea borrar el aviso?</h3>
	</div>	
	<form method="POST" id="formborrar" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'borrarAlerta') )?>">	
	  	<div class="modal-body">		
	  		<div class="row-fluid">
				<div class="span12">
						<div class="alert alert-danger" style="margin-top:10px!important;"><i class="icon-warning"></i> Esta alerta no volverá a aparecer en el sistema.</div>	
				</div>
			</div> 
		</div>
		<div class="modal-footer " style="text-align:center">			
			<button type="submit" class="btn btn-danger" id="btn-danger">Borrar</button>
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		</div>
	</form>
</div>
<br/>
<div class="row-fluid">	
	<div class="span3 offset1">
		<h3>Listado de alertas:</h3>
	</div>
	<div class="span7">		
	</div>
</div>
<div class="row-fluid">
	<div class="span3 offset1">
		<?php echo $this->Element('filtro-alertas');?><?php echo $this->Form->end()?>	
	</div>
</div>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-12">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						
						<?php if (isset($this->params['named']['sort'])) {$orden = $this->params['named']['sort'];}
								else {$orden=null;}							
						?>
						<th width="17%;">
							<?php 
								echo $this->Paginator->sort('nombre', 'Nombre');						
								if ($orden == 'nombre'){
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
							<?php 
								echo $this->Paginator->sort('carrera', 'Carrera');						
								if ($orden == 'carrera'){
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
							<?php 
								echo $this->Paginator->sort('sede', 'Sede');						
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
						<th width="24%">
							<?php 
								echo $this->Paginator->sort('mensaje', 'Mensaje');						
								if ($orden == 'mensaje'){
									if ($this->params['named']['direction'] == 'asc') {
										echo ' <i class="icon-caret-down flechita"></i>';
									}
									else {
										echo ' <i class="icon-caret-up flechita"></i>';
									}
								}
							?>
						</th>
						<th   width="7%"> 
							<?php 
								echo $this->Paginator->sort('nota', 'Nota');						
								if ($orden == 'nota'){
									if ($this->params['named']['direction'] == 'asc') {
										echo ' <i class="icon-caret-down flechita"></i>';
									}
									else {
										echo ' <i class="icon-caret-up flechita"></i>';
									}
								}
							?>
						</th>					
						<th width="25%">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($alertas)):  $aux=0;
					
					foreach($alertas as $k => $alerta): /*pr($alerta);*/?>
						<tr>
							<td>
								<?php echo $alerta['Postulantes']['nombre'];?>
								<?php echo substr($alerta['Alerta']['codigo_postulacion'],16,100);?>
							</td>
							<td>
								<?php echo $alerta['Carrera']['nombre'];?>
							</td>
							<td>
								<?php echo $alerta['Sede']['nombre_sede'];?>
							</td>
							<td>
								<?php echo $alerta['Alerta']['mensaje'];?>
							</td>
							<td class="nota celda-iconos">	
								<?php if ( isset($alerta[0]['Alerta__nota']) && $alerta[0]['Alerta__nota']): ?>
									<a href="#modalNota" id="nota" class="nota2" data-nota="<?php echo $alerta[0]['Alerta__nota'];?>" data-id="<?php echo $alerta['Alerta']['id'];?>"><img src="<?php echo ''.$this->webroot.'/img/postit.png';?>" alt="Nota" title="Nota" width="16" height="16"></a>
								<?php endif; ?>
								<?php if (!empty($alerta['Alerta']['nota'])): ?>
									<a href="#modalNota" id="nota" class="nota2" data-nota="<?php echo $alerta['Alerta']['nota'];?>" data-id="<?php echo $alerta['Alerta']['id'];?>"><img src="<?php echo ''.$this->webroot.'/img/postit.png';?>" alt="Nota" title="Nota" width="16" height="16"></a>
								<?php endif; ?>
							</td	>
							<td class="acciones">
								<?php if  (strpos($alerta['Alerta']['codigo_postulacion'], '-')===false) {
									echo $this->Html->link('<i class="icon-file-text"></i>  Ver postulación',array('controller' => 'administrativos', 'action' => 'postulaciones', $alerta['Alerta']['codigo_postulacion']),array('class'=>'btn btn-small', 'escape'=> false)); 
									}
									else {
										$codigo_postulante = substr($alerta['Alerta']['codigo_postulacion'],0,15);
										echo $this->Html->link('<i class="icon-user"></i>  Ver postulante',array('controller' => 'administrativos', 'action' => 'updateData', $codigo_postulante),array('class'=>'btn btn-small', 'escape'=> false)); 
									}
								?>
								<?php if (isset($alerta[0]) &&  empty($alerta[0]['Alerta__nota'])): ?>
									<?php echo $this->Html->link('<i class="icon-file-text"></i>  Nota','#modalNota',array('class'=>'btn btn-small nota2', 'escape'=> false, 'data-id' => $alerta['Alerta']['id'])); ?>
								<?php endif; ?>
								<?php if (! isset($alerta[0]) && empty($alerta['Alerta']['nota'])): ?>
									<?php echo $this->Html->link('<i class="icon-file-text"></i>  Nota','#modalNota',array('class'=>'btn btn-small nota2', 'escape'=> false, 'data-id' => $alerta['Alerta']['id'])); ?>
								<?php endif; ?>
								
								<?php echo $this->Html->link('<i class="icon-clock-o"></i>  Recordar','#modalRecordar',array('class'=>'btn btn-small recordar', 'escape'=> false, 'data-id' => $alerta['Alerta']['id'])); ?>
								<?php echo $this->Html->link('<i class="icon-trash-o"></i>  Borrar','#modalBorrar',array('class'=>'btn btn-danger btn-small borrar', 'escape'=> false, 'data-id' => $alerta['Alerta']['id'])); ?>
							</td>			

						</tr>
						
					<?php endforeach ;?>
					<?php else :?>
						<tr><td colspan="8" style="text-align: center">No existen alertas actualmente</td></tr>	
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
<script>
	$( document ).ready(function() {
    var accion_original = $('#formnota').attr('action');
    var accion_recordar = $('#formrecordar').attr('action');
    var accion_borrar = $('#formborrar').attr('action');
	
	
	$('.nota2').on('click',function(){
			var nota = $(this).data('nota');
			var id = $(this).data('id');
			var accion = accion_original+'/'+id;
			$('#nota').val(nota).trigger("input");
			$('#formnota').get(0).setAttribute('action', accion);
			$('#modalNota').modal('show');
			$('#formnota input#id').val(id);
			return false;
		});		
	
	$('.recordar').on('click',function(){			
			var id = $(this).data('id');
			var accion2 = accion_recordar +'/'+ id;			
			$('#formrecordar').get(0).setAttribute('action', accion2);
			$('#modalRecordar').modal('show');
			return false;
		});
		
	$('.borrar').on('click',function(){			
			var id = $(this).data('id');
			var accion3 = accion_borrar +'/'+ id;			
			$('#formborrar').get(0).setAttribute('action', accion3);
			$('#modalBorrar').modal('show');
			return false;
		});	
		

	});
	

	
	
</script>

<script>
$('#filtro').change( function() {
 var valor = $(this).val();
 window.location.href = webroot + 'administrativos/listadoalertas/' + valor;
});
</script>