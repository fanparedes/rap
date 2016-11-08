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
	
	$(".btnBuscar").click(function(event){
		event.preventDefault();
		var AdministrativosBuscar = $("#AdministrativosBuscar");

		if(AdministrativosBuscar!=''){
			$('#AdministrativosBuscadorForm').submit();
			$('#AdministrativosBuscadorForm').attr('action', 'http://uri-for-button1.com');
		}else{

		}
	});
	
	$('input.country').typeahead({
		limit: 10,
		name: 'data[Administrativos][buscar]',
		remote : webroot +'administrativos/ajax_autocompletar/PREPOSTULACIONES/%QUERY',
	});
});
</script>
<br/>
<div class="row-fluid">	
	<div class="span3 offset1">
		<h3>Listado de Postulaciones</h3>
	</div>
	<div class="span7">
		<span class="pull-right">
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
				<?php echo $this->Form->input('tipo', array('type' => 'hidden', 'value' => 'PREPOSTULACIONES'));?>
			<button class="btn redondos btn-warning btnBuscar" type="button"><i class="icon-search"></i>&nbsp;</button>
		</span>
		<!--fIN BUSCADOR -->
		<?php $this->Form->end() ;?>
	</div>

<div class="row-fluid">	
	<div class="span7 offset1">

	</div>
	<div class="span10"> 
		<select id="buscar_estado" class="pull-right">
			<option value="todos">Mostrar Todos</option>
			<option value="pro_rap"
			<?php
			if ($buscar_estado == "pro_rap") {
					echo 'selected = "selected"';
				} ?> >Proceso Rap</option>
			<option value="in_csa"
			<?php
			if ($buscar_estado == "in_csa") {
					echo 'selected = "selected"';
				} ?>>Ingresado en CSA</option>
			<option value="pen_rev"
			<?php
			if ($buscar_estado == "pen_rev") {
					echo 'selected = "selected"';
				} ?>>Pendiente Revisar</option>
			<option value="doc_obs"
			<?php
			if ($buscar_estado == "doc_obs") {
					echo 'selected = "selected"';
				} ?>>Documento con Observaciones</option>
			<option value="en_rev"
			<?php
			if ($buscar_estado == "en_rev") {
					echo 'selected = "selected"';
				} ?>>En Revisión</option>
			<option value="hab"
			<?php
			if ($buscar_estado == "hab") {
					echo 'selected = "selected"';
				} ?>>Habilitado</option>
			<option value="hab_firma"
			<?php
			if ($buscar_estado == "hab_firma") {
					echo 'selected = "selected"';
				} ?>>Habilitado con Firma</option>
			<option value="no_hab"
			<?php
			if ($buscar_estado == "no_hab") {
					echo 'selected = "selected"';
				} ?>>No Habilitado</option>
			<option value="post_rech"
			<?php
			if ($buscar_estado == "post_rech") {
					echo 'selected = "selected"';
				} ?>>Postulación Rechazada</option>

		</select>
	</div>
</div>	




</div>
<div class="container-fluid">
  <div class="row-fluid">
		<div class="col-xs-12">
		<div   id="lista_prepostulaciones">
		<br>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="30">#</th>
						<?php if (isset($this->params['named']['sort'])) {$orden = $this->params['named']['sort'];}
								else {$orden=null;}
						?>
						<th  width="8%;">
							<?php echo $this->Paginator->sort('correlativo', 'Id'); 
							if ($orden == 'nombre_postulante'){
								if ($this->params['named']['direction'] == 'asc') {
									echo ' <i class="icon-caret-down flechita"></i>';
								}
								else {
									echo ' <i class="icon-caret-up flechita"></i>';
								}
							}
							if (($orden !== 'nombre_postulante') || ($orden == null)){
									echo ' <i class="icon-caret-down flechita2"></i>';
							}
							?>
						</th>
						<th style="width:100px;"><?php echo $this->Paginator->sort('fecha_creacion', 'Creada');						
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
						<th width="10%;">
							<?php echo $this->Paginator->sort('rut', 'RUT'); 
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
							?>
						</th>
						<th width="10%;">
							<?php echo $this->Paginator->sort('nombre', 'Nombre'); 
							if ($orden == 'nombre_postulante'){
								if ($this->params['named']['direction'] == 'asc') {
									echo ' <i class="icon-caret-down flechita"></i>';
								}
								else {
									echo ' <i class="icon-caret-up flechita"></i>';
								}
							}
							if (($orden !== 'nombre_postulante') || ($orden == null)){
									echo ' <i class="icon-caret-down flechita2"></i>';
							}
							?>
						</th>					
						<th width="9%;">					
							<?php echo $this->Paginator->sort('apellidop', 'A. Paterno'); 
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
						<th width="9%;">					
							<?php echo $this->Paginator->sort('apellidom', 'A. Materno'); 
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
						<th width="9%;">					
							<?php echo $this->Paginator->sort('email', 'Email'); 
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
						<th><?php echo $this->Paginator->sort('telefonomovil', 'Tel.');						
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
						<th><?php echo $this->Paginator->sort('tipo', 'Tipo');						
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
						<th>Estado
						</th>	
						<th style="width:85px;"><?php echo $this->Paginator->sort('fecha_modificacion', 'Mod.');						
							if ($orden == 'fecha_modificacion'){
								if ($this->params['named']['direction'] == 'asc') {
									echo ' <i class="icon-caret-down flechita"></i>';
								}
								else {
									echo ' <i class="icon-caret-up flechita"></i>';
								}
							}
							if (($orden !== 'fecha_modificacion') || ($orden == null)){
									echo ' <i class="icon-caret-down flechita2"></i>';
							}
							?>
							</th>	
							<th>Nº</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					
					if(!empty($prepostulaciones)):  $aux=0;
					
					foreach($prepostulaciones as $k => $prepostulacion):


					?>
						
						<tr>
							<?php if($this->params['paging']['Prepostulacion']['page'] == 1){ $variable = (0);
							}
							else{
								$variable = $this->params['paging']['Prepostulacion']['page']-1;	
							} ;?>
							<td><?php echo ($k +1 )+($variable*20);?></td>
							<?php $codigo_postulante = $prepostulacion['Prepostulacion']['postulante_codigo']; ?>
							<td><?php if (isset($prepostulacion['Prepostulacion']['id_correlativo'])){ echo $prepostulacion['Prepostulacion']['id_correlativo']; } ?></td>
							<td><?php echo ($this->Ingenia->formatearFecha($prepostulacion['Prepostulacion']['created']));?></td>
							<td><?php echo $prepostulacion['Postulante']['rut'];?></td>
							<td><?php echo $prepostulacion['Postulante']['nombre'];?></td>
							<td><?php echo $prepostulacion['Postulante']['apellidop'];?></td>
							<td><?php echo $prepostulacion['Postulante']['apellidom'];?></td>
							<td><?php echo $prepostulacion['Postulante']['email'];?></td>
							<td><?php echo $prepostulacion['Carrera']['nombre'];?></td>
							<td><?php echo $prepostulacion['Postulante']['telefonomovil'];?></td>
							<td>
								<?php 
									if ($prepostulacion['Prepostulacion']['destino'] == 'AH'){
										echo 'ESCUELAS';							
										}
									elseif ($prepostulacion['Prepostulacion']['destino'] == 'AV'){
										echo 'ARTICULACIÓN';							
										}
									else{
										echo $prepostulacion['Prepostulacion']['destino'];
									}
								?>
							</td>
							<td>			
									<?php 								
										if($prepostulacion['Prepostulacion']['destino'] !== null){//Si la postulación ya fue derivada por el coordinador	
											switch ($prepostulacion['Prepostulacion']['destino']) {
												case 'RAP':
													if (isset($prepostulacion['EstadoPostulacion'])){
														$maximo = max($prepostulacion['EstadoPostulacion']);
														$estado = '';
														$paso   = '';
														
														$resp   = false;
														$resp   = in_array(7,$prepostulacion['EstadoPostulacion']); //Verifico si está rechazada
														
														if($resp){
																$estado = '<span class="rojo">Postulación rechazada</span>';
																$paso   = '';
														}
														else{
																switch($maximo){
																	case 1:
																		$estado = 'FORMULARIO DE POSTULACIÓN';
																		$paso   = 'paso 1';
																	break;
																	case 2:
																		$estado = 'DOCUMENTACIÓN RECIBIDA EN REVISIÓN';
																		$paso   = 'paso 2';
																	break;
																	case 3:
																		$estado = 'CV RAP';
																		$paso   = 'paso 3';
																	break;
																	case 4:
																		$estado = 'CV RAP COMPLETADO';
																		$paso   = 'paso 4';
																	break;
																	case 5:
																		$estado = 'CV RAP Y AUTOEVALUACIÓN EN REVISIÓN';
																		$paso   = 'paso 5';
																	break;
																	case 6:
																		$estado = 'EVIDENCIAS PREVIAS - ENTREVISTA';
																		$paso   = 'paso 6';
																	break;
																	case 7:
																		$estado = '<span class="rojo">NO HABILITADO</span>';
																		$paso   = '';
																	break;
																	case 8:
																		$estado = 'INFORME DE EVIDENCIAS FINALES';
																		$paso   = 'paso 7';
																	break;
																	case 9:
																		$estado = '<span class="verde">HABILITADO</span>';
																		$paso   = '';
																	break;
																}
														}
														echo $estado;	
													}
													else{ //NO ESTÁ EL ESTADO DE RAP POR TANTO SOLO PUEDE ESTAR EN OBSERVACIONES
														echo 'PENDIENTE DE RELLENAR EL FORMULARIO DE POSTULACIÓN';
													}
													break;
												case 'AH':
													if (isset($prepostulacion['Postulacion']['habilitado'])){																									
														if($prepostulacion['Postulacion']['habilitado'] == '1'){
																if (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] == '1') ){
																	$estado = '<span class="verde">INGRESADO EN CSA</span>';	
																}
																elseif (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] !== '1') ){
																	$estado = '<span class="verde">HABILITADO CON FIRMA</span>';														
																}
																else{
																	$estado = '<span class="verde">HABILITADO</span>';
																}					
															}
														
															elseif ($prepostulacion['Postulacion']['habilitado'] == '0') {
																$estado = '<span class="rojo">NO HABILITADO</span>';
															}
															else {
																$estado = 'EN REVISIÓN';
															}
													}
													else{
														$estado = 'EN REVISIÓN';
													}
													echo $estado;
													break;
												case 'AV':
													$estado = '';
													if (isset($prepostulacion['Postulacion']['habilitado'])){	
													if($prepostulacion['Postulacion']['habilitado'] == '1'){
															if (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] == '1') ){
																$estado = '<span class="verde">INGRESADO EN CSA</span>';	
															}
															elseif (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] !== '1') ){
																$estado = '<span class="verde">HABILITADO CON FIRMA</span>';														
															}
															else{
																$estado = '<span class="verde">HABILITADO</span>';														
															}
														}
														elseif ($prepostulacion['Postulacion']['habilitado'] == '0') {
															$estado = '<span class="rojo">NO HABILITADO</span>';
														}
														else {
															$estado = 'EN REVISIÓN';
														}
													}
													else{
														$estado = 'EN REVISIÓN';
													}	
												echo ''.$estado;
													break;
											}									
										}
										else{
											if($prepostulacion['Prepostulacion']['revision']==0){
													echo 'PENDIENTE DE REVISAR';
											}
											else{
													echo 'DOCUMENTOS CON OBSERVACIONES';
											}
										}
									?>
							</td>			
							<td>
								<?php 
										echo ($this->Ingenia->formatearFecha($prepostulacion['Prepostulacion']['modified']));
									
								 ?>
							</td>						
							<td>
							<?php foreach ($cantidades as $cantidad){
								
								if ($cantidad['Prepostulacion']['postulante_codigo'] == $codigo_postulante){
									echo $cantidad['Prepostulacion']['total'];								
								} 
							}
							?>
							</td>						
							
							<td class="acciones">
								<a href="<?php echo $this->Html->url(array('controller'=>'coordinadores','action'=>'verPrepostulacion', $prepostulacion['Prepostulacion']['codigo'])); ?>" class="btn" target="_blank"> 
									<i class="icon-file-text"></i>&nbsp;Ver 
								</a>
							</td>
						
						</tr>
						
					<?php endforeach ;?>
					<?php else :?>
						<tr><td colspan="8" style="text-align: center">No existen prepostulaciones</td></tr>	
					<?php endif; ?>
				</tbody>
			</table>
			</div>
			<?php
			if (!isset($busqueda)) {
						echo $this->Paginator->numbers(array(
							'before' => '<div class="pagination"><ul>',
							'separator' => '',
							'currentClass' => 'active',
							'tag' => 'li',
							'after' => '</ul></div>'));
			} else {
			?>	




	            <div>
	                <div class="paginate_listado">                   
	                </div>
	            </div>

		        <script type="text/javascript">
		        $(function() {
		            $(".paginate_listado").paginate({
		                count       : <?php echo $cant_pag; ?>,
		                start       : 1,
		                display     : 10,
		                border                  : true,
		                border_color            : '#fff',
		                text_color              : '#fff',
		                background_color        : 'black',  
		                border_hover_color      : '#ccc',
		                text_hover_color        : '#000',
		                background_hover_color  : '#fff', 
		                images                  : false,
		                mouse                   : 'press',
		                onChange                : function(page){


		                                            paginate(page);


		                                          }
		            });


		        });

				  function paginate(page) {
				            url = webroot + 'administracion/coordinadores/paginacion';
				            var valor = $('#buscar_estado').val();
				            var pagina = page;
				            $('#esperando').modal();
				            $.ajax({
				                url: url,
				                type: "POST",
				                data: { pagina: pagina, busqueda: valor},
				                success: function(result) {
				                    $('#lista_prepostulaciones').html(result);
				                } // <-- add this
				            });   
				  }
				  </script>


			<?php } ?>
			<!-- Descargar Informe -->
			<div class="row-fluid">
				<div class="span12">
					<span class="pull-right">
					<a type="button" class="btn btn-success excel"><i class="icon icon-download"></i> Exportar Excel</a>
					</span>
				</div>
			</div>
		</div>	
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

$('#buscar_estado').change(function(){
	var estado_buscar = $('#buscar_estado').val();
	var items = "pro_rap,in_csa,pen_rev,doc_obs,en_rev,hab,hab_firma,post_rech, todos";
	var items_rs = items.split(",");
	var url = jQuery(location).attr('href');
	var redirection = true;
	for(var i=0;i<items_rs.length;i++){
		var url_rs = url.split(items_rs[i]);
		//console.log(url_rs);
		if(parseInt(url_rs.length)==1){
			//alert(url_rs.length);
		}else{
			//console.log(url_rs);
			window.location =  url_rs[0] + estado_buscar+ url_rs[1];
			redirection = false;
		}
    }
    if(redirection){
    	window.location =  window.location +'/'+ estado_buscar;
    }

})

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


$(function() {
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  });

//exportar excel
$('.excel').click(function(){
	window.location.href =  webroot +'coordinadores/ajax_exportar_excel_postulaciones';
})

</script>


<div class="modal hide" id="esperando" data-backdrop="static" data-keyboard="false">
        <div class="modal-header">
            <h1>Procesando...</h1>
        </div>
        <div class="modal-body">
            <div class="progress progress-striped active">
                <div class="bar" style="width: 100%;"></div>
            </div>
        </div>
</div>