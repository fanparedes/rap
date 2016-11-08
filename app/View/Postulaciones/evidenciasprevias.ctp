<div id="pop1" class="popbox">
    <p>Las evidencias son todos aquellos eventos que puedes hacer visibles y declarables con el objeto de demostrar empíricamente el desempeño de tu competencia en un determinado ámbito del quehacer. Estas evidencias pueden ser certificaciones laborales y académicas: Informe de prácticas, de empleadores, de experiencia laboral, calificaciones de otras instituciones educativas, muestras de trabajos gráficos, escritos, orales y audiovisuales, constancias por parte de clientes, proyectos colaborativos, reconocimientos otorgados, constancias de servicio profesional voluntario, etc.</p>
</div>

<style type="text/css" media="all">
/* <![CDATA[ */
.popbox {
    display: none;
    position: absolute;
    z-index: 99999;
    width: 400px;
    padding: 10px;
    background: #EEEFEB;
    color: #000000;
    border: 1px solid #4D4F53;
    margin: 0px;
    -webkit-box-shadow: 0px 0px 5px 0px rgba(164, 164, 164, 1);
    box-shadow: 0px 0px 5px 0px rgba(164, 164, 164, 1);
}
.popbox h2
{
    background-color: #4D4F53;
    color:  #E3E5DD;
    font-size: 14px;
    display: block;
    width: 100%;
    margin: -10px 0px 8px -10px;
    padding: 5px 10px;
}
td.acciones {
    width: 164px;
}
td.texto{
	font-size:12px;
	width: 134px;
}

/* ]]> */
</style>
<script>
$(function() {
    var moveLeft = 0;
    var moveDown = 0;
    $('a.popper').hover(function(e) {
   
        var target = '#' + ($(this).attr('data-popbox'));
         
        $(target).show();
        moveLeft = $(this).outerWidth();
        moveDown = ($(target).outerHeight() / 2);
    }, function() {
        var target = '#' + ($(this).attr('data-popbox'));
        $(target).hide();
    });
 
    $('a.popper').mousemove(function(e) {
        var target = '#' + ($(this).attr('data-popbox'));
         
        leftD = e.pageX + parseInt(moveLeft);
        maxRight = leftD + $(target).outerWidth();
        windowLeft = $(window).width() - 40;
        windowRight = 0;
        maxLeft = e.pageX - (parseInt(moveLeft) + $(target).outerWidth() + 20);
         
        if(maxRight > windowLeft && maxLeft > windowRight)
        {
            leftD = maxLeft;
        }
     
        topD = e.pageY - parseInt(moveDown);
        maxBottom = parseInt(e.pageY + parseInt(moveDown) + 20);
        windowBottom = parseInt(parseInt($(document).scrollTop()) + parseInt($(window).height()));
        maxTop = topD;
        windowTop = parseInt($(document).scrollTop());
        if(maxBottom > windowBottom)
        {
            topD = windowBottom - $(target).outerHeight() - 20;
        } else if(maxTop < windowTop){
            topD = windowTop + 20;
        }
     
        $(target).css('top', topD).css('left', leftD);
     
     
    });
 
});
</script>

<div id="modalvalidar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align: center;">¿Está seguro que desea VALIDAR sus Evidencias?</h3>
	
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'postulaciones', 'action' => 'validar_evidencias', $codigo_postulacion))?>">	
	  	<div class="modal-body">
			<div class="row-fluid">
				<div class="span11 offset1">
					<p>Al validar sus evidencias, podrá acceder al paso 6 para agendar entrevista con el orientador.</p>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>
<!---Ventana para aviso de advertencia de evidencias previas-->
<div id="modaladvertencia" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align: center;">Debes completar al menos una evidencia previa</h3>
	
	</div>	
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
	</div>
</div>

<!---Fin Ventana para aviso de advertencia de evidencias previas-->
<?php 
	$urlPreguntasBasicas = $this->Html->url(array('controller'=>'postulaciones','action'=>'completarPostulacion',$codigo_postulacion));
	$urlCvRap = $this->Html->url(array('controller'=>'postulaciones','action'=>'CvRap',$codigo_postulacion));
	$urlCompetencias = $this->Html->url(array('controller'=>'postulaciones','action'=>'competencias',$codigo_postulacion));
	#debug($postulacion);
	$archivos=false;
	if(!empty($licencia_educacion_media) || !empty($fotocopia_carnet) || !empty($declaracion_renta)){
		$archivos = true;
	}	
?>
<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->
<style type="text/css" media="screen">
	.mini-title{
		font-size:15px;
		color:#ccc;
	}	
	#msj-obligatorio{
		font-size: 12px;
		font-style:italic;
	}
	.formulario{
		border-bottom: 1px solid #E2E2E2;
		border-left :1px solid #E2E2E2;
		border-right: 1px solid #E2E2E2;
		width: 98%;
		margin-left:1% !important;
	}
	.subtitle{
		color:#6E6E6E;
		border-bottom:1px solid #6E6E6E;
	}
	.link-pass:hover{
		cursor:pointer
	}
</style>
<?php
	#debug($postulacion);
	if(!empty($postulacion)){
		echo $this->element('wizard-postulacion',array('cod_postulacion'=>$codigo_postulacion,'resumen'=>$resumen));	
	}
?>
<div class="row-fluid formulario">
	<div class="span12 ">
		<div class="row-fluid">
			<div class="span8 offset1">
			  	<h3>Informe previo de Evidencias </h3>
			</div>
		</div>
        <div class="clearfix">&nbsp;</div>
        <div class="row-fluid">
			<div class="span6 offset1 alert alert-info">
				<h4>Instrucciones: </h4><br>
				<p>El informe de <a href="#" class="popper" data-popbox="pop1" style="text-decoration:none;font-style: oblique;"><b>evidencias&nbsp;&nbsp;</b></a>de aprendizaje previos es una modalidad de evaluación que permite valorar tus esfuerzos y el proceso de aprendizaje de competencias asociadas a la carrera que deseas cursar. Implica recopilar, seleccionar, organizar y reflexionar sobre evidencias que muestren tu desempeño en el área.</p>
				<p>Este informe de evidencias es preliminar porque tienes la posibilidad de revisarlo, editarlo y resolver tus dudas en la entrevista con el orientador antes de que completes la versión final.</p>
				<p>Debes elaborarlo en relación a las competencias que seleccionaste y a las unidades de competencia en que te autoevaluaste.</p>
				<p>Para descargar la ficha de recogida de información sobre funciones desempe&ntilde;adas por el trabajador, debes<br>  hacer clic 
				<?php 
					echo $this->html->link('AQUÍ', 
					array('controller'=>'cargas', 'action' => 'descargarTrabajador'),
					array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
					);
				?>
				</p>
			</div>
            <div class="span4 alert alert-warning">
                <h4>Atención</h4>
                <ul>
                	<li>archivos permitidos: </li>
                		<ul>
                			<li>PDF</li>
                			<li>WORD (doc - docx - odt)</li>
                			<li>EXCEL (xls - xlsx - ods)</li>
                			<li>IMAGENES (jpg - gif - png - jpeg)</li>
                			<li>COMPRIMIDOS (rar - zip)</li>
                		</ul>
                	<li>Peso máximo de cada archivo: <strong>5 MB</strong>.</li>		
                	<li><strong>Si no ingresas la documentación completa, tu cuenta se desactivará en un plazo de 6 meses.</strong></li>
                	
                </ul>
            </div>
        </div>
		<div class="clearfix">&nbsp;</div>
		<div class="row-fluid">
			<div class="span10 offset1"><h4>Competencias elegidas:</h4>
				<div class="row-fluid">
					<div class="span12">
						<?php if(!empty($postulacion)):?>
							<?php if(!empty($competencias)):?>
							<div class="accordion" id="accordion2">	 
								<?php $contador = 0; ?>
								
								<?php foreach ($competencias as $index=>$competencia) : ?>
									<?php $contador = $contador + 1; ?><?php $contador2 = 0; ?>									
									<div class="accordion-group contenedor" >											
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $index; ?>">
												<div class="accordion-heading">
													<table width="100%">
														<tbody>
															<tr>
															<td width="40%"><?php echo $contador.' - '.$competencia['Nombre']; ?></td>
															<?php if (empty($datos_evidencia) && ($validado  !== true)): ?>
																	<td><p style="text-align:right"><a class="btn btn-success  btn-nuevo" id="nuevo-evidencia" href="#modal" data-toggle="modal" onclick="nuevoModal('<?php echo $competencia['Código']; ?>');"><i class="icon icon-plus-circle"> &nbsp;</i>Añadir Evidencias</a></p></td>
																<?php endif;?> 
															<?php foreach ($datos_evidencia as $dato): ?>
																<?php if ($dato['ArchivoEvidencia']['cod_unidad_competencia'] == $competencia['Código']): ?>
																	<td width="15%"><?php echo $dato['ArchivoEvidencia']['nombre']; ?></td>
																	<td width="15%"><?php echo $dato['ArchivoEvidencia']['relacion_evidencia']; ?></td>
																	<td width="15%"><?php echo $this->html->link($dato['ArchivoEvidencia']['nombre_fisico'],array('controller'=>'Postulaciones', 'action' => 'descargarEvidencia', $dato['ArchivoEvidencia']['codigo']),array('class' => 'tool descargas',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right"))?></td>
																	<td width="15%" class="acciones">
																		<?php if ($validado <> true): ?> 
																			<p style="text-align:right">
																				<a href="#modaleditar" data-toggle="modal" class="btn btn-info btn-small editar-evidencia" id="editar-evidencia" onclick="(editarEvidencia('<?php echo $dato['ArchivoEvidencia']['id_evidencia']; ?>','<?php echo $codigo_postulacion ;?>'))"><i class="icon-edit">&nbsp;</i>Editar</a>
																				<a href="<?php echo $this->Html->url(array('controller' => 'postulaciones','action'=>'delete_evidencia',$codigo_postulacion, $dato['ArchivoEvidencia']['id'], $dato['ArchivoEvidencia']['id_evidencia'])); ?>" class="btn btn-danger btn-small" style="margin-left: 6px;">
																					<i class="icon-trash-o">&nbsp;</i>
																						Eliminar
																				</a>
																			</p>
																		<?php endif; ?>
																	</td>
																<?php else: ?>																
																		<?php if (!in_array($competencia['Código'],$evidenciasExistentes) || (empty($evidenciasExistentes))): ?>
																			<td width="60%">
																			<?php $contador2++;?>
																			<?php if (($contador2 == 1) && ($validado !== true)): ?>
																				<p style="text-align:right"><a class="btn btn-success  btn-nuevo" id="nuevo-evidencia" href="#modal" data-toggle="modal" onclick="nuevoModal('<?php echo $competencia['Código']; ?>');"><i class="icon icon-plus-circle"> &nbsp;</i>Añadir Evidencias</a></p>
																			<?php endif; ?>
																			</td>																																	
																		<?php endif; ?>																																		
																	
																<?php endif; ?>																
															<?php endforeach; ?>
															<tr>
														</tbody>
													</table>
											</a>
										</div>
										<!--Comienzo de acordion-->
										<div id="collapse<?php echo $index; ?>" class="accordion-body collapse">
											<div class="accordion-inner">
												<!---Comienzo caja-->												
													<table class="table table-bordered table-hover">
																<thead>
																	<tr>														
																	</tr>
																</thead>
																<tbody>																	
																		<?php $asignaturas = $competencia['Asignaturas'];
																		foreach ($asignaturas as $i=> $asignatura) : $contador; ?>
																			<tr class="cajas">
																			<td style="width: 334px;" class="texto">
																			<?php
																				
																				echo $contador;
																				echo '.';
																				echo $i+'1'.' - '.$asignatura['UnidadCompetencia']['nombre_unidad_comp'];?>
																			</td>
																			
																			<?php if( empty($asignatura['EvidenciaPrevia'])) :?>
																				
																			<?php else :?>
																			
																			<td  id="nombre" data-id="<?php echo $asignatura['EvidenciaPrevia']['EvidenciasPrevias']['nombre_evidencia'] ;?>" class="texto"><?php echo $asignatura['EvidenciaPrevia']['EvidenciasPrevias']['nombre_evidencia'] ;?></td>
																			<td  id="justificacion" data-texto="<?php echo $asignatura['EvidenciaPrevia']['EvidenciasPrevias']['relacion_evidencia'] ;?>"class="texto"><?php echo $this->Text->truncate($asignatura['EvidenciaPrevia']['EvidenciasPrevias']['relacion_evidencia'],90) ;?></td>
																			<td  id="imagen" data-img="<?php echo $asignatura['DocEvidencia']['ArchivoEvidencia']['nombre_fisico'] ;?>" class="texto"><?php echo $this->html->link($asignatura['DocEvidencia']['ArchivoEvidencia']['nombre_fisico'],array('controller'=>'Postulaciones', 'action' => 'descargarEvidencia', $asignatura['DocEvidencia']['ArchivoEvidencia']['codigo']),array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right"))?></td>
																			
																			<?php endif ;?>
																			<td class="acciones" <?php if(empty($asignatura['DocEvidencia']['ArchivoEvidencia'])) :?> colspan="5" <?php endif; ?>>
																			<?php if($validado == false): ?>
																				<?php if(isset($asignatura['DocEvidencia']['ArchivoEvidencia'])) :?>
																					<div>
																						<a href="<?php echo $this->Html->url(array('controller' => 'postulaciones','action'=>'delete_evidencia',$asignatura['DocEvidencia']['ArchivoEvidencia']['postulacion_codigo'],$asignatura['DocEvidencia']['ArchivoEvidencia']['id'],$asignatura['DocEvidencia']['ArchivoEvidencia']['id_evidencia'])); ?>" class="btn btn-danger btn-small pull-right" style="margin-left: 6px;">
																							<i class="icon-trash-o">&nbsp;</i>
																							Eliminar
																						</a>
																						<a href="#modaleditar" data-toggle="modal" class="btn btn-info pull-right btn-small editar-evidencia" id="editar-evidencia" onclick="(editarEvidencia('<?php echo $asignatura['DocEvidencia']['ArchivoEvidencia']['id_evidencia']; ?>','<?php echo $codigo_postulacion ;?>'))"><i class="icon-edit">&nbsp;</i>Editar</a>
																					</div>
																				<?php else :?>
																					
																				<?php endif ;?>
																			<?php endif ;?>
																			<?php if($validado == true && isset($asignatura['DocEvidencia'])): ?>
																			<p style="text-align:center;"><a href="#modalfinal" data-toggle="modal" class="btn btn-info btn-small final-evidencia" id="final-evidencia" onclick="(evidenciaFinalPrevia('<?php echo $asignatura['DocEvidencia']['ArchivoEvidencia']['id_evidencia']; ?>','<?php echo $codigo_postulacion ;?>'))"><i class="icon-eye">&nbsp;</i>Ver Evidencia</a></p>
																			<?php else :?>
																			<!--<a class="btn btn-success pull-right btn-nuevo" id="nuevo-evidencia" href="#modal" data-toggle="modal" onclick="nuevoModal('<?php echo $asignatura['UnidadCompetencia']['codigo_unidad_comp'] ;?>');"><i class="icon icon-plus-circle"></i> Añadir Evidencias</a>-->
																			<?php endif;?>
																			</td>
																			</tr>
																	<?php endforeach; ?>
																	
																</tbody>
															</table>														
														<!---fin de caja-->
											</div>
										</div>
									<!-- Fin de fila del acordeon -->
									</div>
								<?php endforeach;?>
							</div>
							<?php endif; ?>
						<?php endif; ?>	
					</div>
				</div>		
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="row-fluid">
			
			<div class="span11">
				<?php if ($validado == false): ?>
				<span >				
					<a class="btn btn-warning pull-right btn-nuevo"  style="margin-right:10px;" id="validarDocumentacion" href="#"><i class="icon icon-check"></i> Validar Evidencias Previas</a>				
				</span>
				<?php endif; ?>	
				
				<?php if ($validado == true): ?>
					<span id="img-check-licencia" align="right" class="pull-right"><img src="<?php echo $this->webroot;?>img/test-pass-icon.png" alt=""> Evidencias validadas</span>
				<?php endif; ?>				
			</div>
		</div>
	
		<br />
	
		
	</div>
</div>
<br>
<div class="modal hide fade" id="modal"></div>
<div class="modal hide fade" id="modalfinal"></div>
<div class="modal hide fade" id="modaleditar" data-backdrop="static" data-keyboard="false"></div>
<script type="text/javascript">
///para eliminar evidencias
$('.btn-danger').click(function(ev){
	var r = confirm('¿Está seguro de eliminar la evidencia seleccionada?');
		if (r == false) {
			ev.stopPropagation();
			return false;
		} 
})

	$(function(){
		if (navigator.userAgent.indexOf("MSIE")>0 ) {
			$('#info-licencia').hide();
			$('#info-ci').hide();
			$('#info-renta').hide();
			$('#titulo-estado').hide();
		}else{
			$('#form-field-input-carga-renta').on('change',renta); //CUANDO SE MODIFICA UN ARCHIVO
		}
	});
	
	//funcion para validar todas las evidencias
	
	$('a#validarDocumentacion').click(function(){
		var botones =0;
		$('.accordion-group').each(function(index, elemento){
						
			//var boton = $(elemento).find('td.acciones').find('a#nuevo-evidencia');
			var boton = $(elemento).find('td.acciones').find('a#editar-evidencia');
			var largo = boton.length;
     
			if(largo == 1)
			{
			  botones = 1;
			}
		});
		if(botones == 1)
		{
		   $('#modalvalidar').modal('show'); return false; 
		}else{
		 
			$('#modaladvertencia').modal('show'); return false;
		}
	});
	

	function editarEvidencia(id,codigo)
	{
		//para editar la evidencia
		var ruta = webroot + 'postulaciones/popup_edit_evidencia/'+id+'/'+codigo;
		$('#modaleditar').html('CARGANDO...');
		$('#modaleditar').load(ruta);
	}
	
	function evidenciaFinalPrevia(id,codigo)
	{
		//console.log(id+'/'+codigo);
		//para editar la evidencia
		//var ruta = webroot + 'postulaciones/evidencia_finalizada_previa/'+id+'/'+codigo;
		$('#modalfinal').html('CARGANDO...');
		$('#modalfinal').load("<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'evidencia_previa_validada',$codigo_postulacion)); ?>"+'/'+id);
		//$('#modalfinal').load(ruta);
	}
	

	
	function renta(){
		if (navigator.userAgent.indexOf("MSIE")>0){
	     	var file = $("#form-field-input-carga-evidencia").files;
	    }else{
	    	var file = $("#form-field-input-carga-evidencia")[0].files[0]; 	
	    }
		
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        var size = fileSize/1024;
        var ext = true;
        var peso = true;
        if(!isImage(fileExtension)){        	
        	$(this).hide();
        	return false;
        }
        if(size>50000){
        	alert('El archivo supera el peso establecido.');
        	$('input[type=file]').val("");
    	return false;
        }
        if(peso && ext){
			return true;
        }
	}
	
	
	function isImage(extension){
	    switch(extension.toLowerCase()){
	        case 'jpg': case 'gif': case 'png': case 'jpeg': case 'pdf': case 'doc': case 'docx': case 'xls': case 'xlsx': case 'odt': case 'ods':
	            return true;
	        break;
	        default:
	            return false;
	        break;
	    }
	}
	
	
	///lebanta el modal
	function nuevoModal(codigo_unidad_comp)
	{
		$('#modal').html('CARGANDO...');
		$('#modal').load("<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'evidencia',$codigo_postulacion)); ?>"+'/'+codigo_unidad_comp);		
	}
</script>
<script>
$(".btn-danger").on("click", function(event) {
    event.stopPropagation();
});

$(".descargas").on("click", function(event) {
    event.stopPropagation();
});
</script>
