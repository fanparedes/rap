                                                                                                                                                <?php 
	$urlPreguntasBasicas = $this->Html->url(array('controller'=>'postulaciones','action'=>'completarPostulacion',$codigo_postulacion));
	$urlCvRap = $this->Html->url(array('controller'=>'postulaciones','action'=>'CvRap',$codigo_postulacion));
	$urlCompetencias = $this->Html->url(array('controller'=>'postulaciones','action'=>'competencias',$codigo_postulacion));
	#debug($postulacion);
	$archivos=false;
	if(!empty($documentos)){
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
			  	<h3>Adjuntar Documentación </h3>
			</div>
		</div>
		<?php if(!$archivos): ?>
			<div class="row-fluid">
	        	<div class="span6 offset1">
					<label id="msj-obligatorio" class="label-warning label"> Es obligatorio adjuntar los 3 archivos solicitados</label>  
				</div>
	        </div>
        <?php endif; ?>
        <div class="clearfix">&nbsp;</div>
        <div class="row-fluid">
            <div class="span10 offset1 alert alert-info">
                <h4>Atención</h4>
                <ul>
                	<li>archivos permitidos: </li>
                		<ul>
                			<li>PDF</li>
                			<li>WORD (doc - docx)</li>
                			<li>IMAGENES (jpg - gif - png - jpeg)</li>
                		</ul>
                	<li>Peso máximo de cada archivo: <strong>5 MB</strong>.</li>		
                	<li><strong>Si no ingresas la documentación completa, tu cuenta se desactivará en un plazo de 6 meses.</strong></li>
                	<li>Para acceder a tu licencia de enseñanza media, haz <strong><a target="_blank" href="https://www.ayudamineduc.cl/">click aquí</a>.</strong></li>
                	
                </ul>
            </div>
        </div>
		<div class="clearfix">&nbsp;</div>
		<div class="row-fluid">
			<div class="span10 offset1"><?php #debug($user); ?>
			  	<form action="<?php echo $this->Html->url(array('controller'=>'cargas','action'=>'cargarDocumentos')); ?>" method="POST" id="FormFormularioAdjuntarDocumentos" enctype="multipart/form-data">
		            <input type="hidden" name="data[Postulacion][postulante_codigo]" value="<?php echo $codigo_postulacion; ?>" />
		            <div class="row-fluid">
		                <div class="span3 subtitle">
		                    <h4>Tipo Documento</h4>
		                </div>
		                <div class="span6 subtitle">
		                    <h4>Documento</h4>
		                </div>
		                <div class="span3 subtitle " id="titulo-estado">
		                    <h4>Estado</h4>
		                </div>
		            </div>
		            <div class="clearfix">&nbsp;</div>
		            <div class="row-fluid">
		                <div class="span3">
		                    <label for="form-field-input-carga-licencia" class="control-label" >Licencia de enseñanza media.</label>
		                </div>
	                	<div class="span6">
	                		<?php if(!empty($documentos['licencia'])): ?>
	                			<label>
									
								
	                				<?php echo $this->html->link($documentos['licencia']['nombre'], 
	                		
	                					array('controller'=>'cargas', 'action' => 'descargarArchivo', $documentos['licencia']['codigo']),
										array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
										)?>
                			<?php else: ?>
                				<div id="licencia">
			                    	<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />
			                    </div>
            				<?php endif; ?>
		                </div>
		                <div class="span3" id="info-licencia">
						 	<div id="img-check-licencia" align="left" class="<?php echo (!empty($documentos['licencia']))? '': 'hide'; ?>"><?php echo $this->Html->image('test-pass-icon.png'); ?> Archivo permitido</div>
		            		<div id="img-error-licencia" align="left" class="<?php echo (empty($documentos['licencia']))? '': 'hide'; ?>"><?php echo $this->Html->image('test-fail-icon.png'); ?> No hay archivo</div>
						</div>
		            </div>
		            <div class="clearfix">&nbsp;</div>
		             <div class="row-fluid">
		                <div class="span3">
		                    <label for="form-field-input-carga-ci" class="control-label" >Fotocopia Carnet Identidad (por ambos lados)</label>
		                </div>
	                	<div class="span6">
	                		<?php if(!empty($documentos['cedula'])): ?>
	                			<label> 
	                				<?php echo $this->html->link($documentos['cedula']['nombre'], 
	                		
	                					array('controller'=>'cargas', 'action' => 'descargarArchivo', $documentos['cedula']['codigo']),
										array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
										)?>
                			<?php else: ?>
                				<div id="ci">
			                    	<input id="form-field-input-carga-ci" required type="file" name="data[Archivos][ci]" />
			                	</div>
            				<?php endif; ?>
	                		
		                </div>
		                <div class="span3" id="info-ci">
						 	<div id="img-check-ci" align="left" class="<?php echo (!empty($documentos['licencia']['nombre']))? '': 'hide'; ?>"><?php echo $this->Html->image('test-pass-icon.png'); ?> Archivo permitido</div>
		            		<div id="img-error-ci" align="left" class="<?php echo (empty($documentos['licencia']['nombre']))? '': 'hide'; ?>"><?php echo $this->Html->image('test-fail-icon.png'); ?> No hay archivo</div>
						</div>
		            </div>
		            <div class="clearfix">&nbsp;</div>
					
		                <?php if (isset($anexos)){ ?>
							<?php if(count($anexos)>0){ ?>
						<?php foreach ($anexos as $k => $anexo) :?>
						<div class="row-fluid">
							<div class="span3">
								 <label for="form-field-anexo-<?php echo $k+1; ?>" class="control-label" ><?php echo $anexo['ArchivoPrepostulacion']['tipo'];?></label>
							</div>
							<div id="anexo<?php echo $k+1;?>" class="span6">
								<label>
									<?php		
										if ($anexo['ArchivoPrepostulacion']['tipo'] !== 'Convenio'){
											echo $this->html->link($anexo['ArchivoPrepostulacion']['nombre'], 	                		
												array('controller'=>'cargas', 'action' => 'descargar_anexo', $anexo['ArchivoPrepostulacion']['codigo']),
												array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Anexo', 'data-placement' => "right")
												);
										}
									?>
								</label>
							</div>
							<div class="span3">								
								<div id="img-check-anexo<?php echo $k+1;?>" align="left" class="<?php echo (!empty($anexo['ArchivoPrepostulacion']['nombre']))? '': 'hide'; ?> check_anexos_"><?php echo $this->Html->image('test-pass-icon.png'); ?> Archivo permitido</div>
								<div id="img-error-anexo<?php echo $k+1;?>" align="left" class="<?php echo (empty($anexo['ArchivoPrepostulacion']['nombre']))? '': 'hide'; ?> check_anexos_"><?php echo $this->Html->image('test-fail-icon.png'); ?> No hay archivo</div>								
							</div>

		            </div>
					<?php endforeach; ?>
					<?php 
						}
						else{
							echo "<span style='font-weight:bold;'>Postulación de tipo convenio</span>";
						}
					}
					else{
						echo "<span style='font-weight:bold;'>Postulación de tipo convenio</span>";
					}
					?>
					
		            <div class="clearfix">&nbsp;</div>
		            <?php if($resumen['estado']['codigo'] < 2): ?>
			            <div class="row-fluid">
			            	<div class="span6 offset6">
			            		<div class="control-submit">
									<button style="margin-top:10px;"id="form-input-submit-enviar" type="submit"  class="btn btn-primary pull-right"> <i class="icon-file"></i> Cargar Documentación</button>  								
								</div>								
							</div>
			            </div>
		            <?php endif; ?>
		            <div class="clearfix"></div>
		        </form>
			</div>
		</div>
	</div>
</div>
<br>
<script type="text/javascript">
	$(function(){
		if (navigator.userAgent.indexOf("MSIE")>0 ) {
			$('#info-licencia').hide();
			$('#info-ci').hide();
			$('#info-renta').hide();
			$('#titulo-estado').hide();
		}else{
			$('#form-field-input-carga-licencia').on('change',licencia);
			$('#form-field-input-carga-ci').on('change',ci);
			$('#form-field-input-carga-renta').on('change',renta);
		}
	});
	
	function licencia(){
		var ext = true;
        var peso = true;
        var size = 0;
		var file = $("#form-field-input-carga-licencia")[0].files[0];
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        size = fileSize/1024;
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	$('#licencia').html('<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />');
        	$('#form-field-input-carga-licencia').on('change',licencia);
        	$('#img-check-licencia').hide();
        	$('#img-error-licencia').show();
        	return false;
        }
        if(size>30000){
        	alert('El archivo supera el peso establecido.');
        	$('#licencia').html('<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />');
        	$('#form-field-input-carga-licencia').on('change',licencia);
        	$('#img-check-licencia').hide();
        	$('#img-error-licencia').show();
        	return false;
        }
        if(peso && ext){
        	$('#img-check-licencia').show();
        	$('#img-error-licencia').hide();
        }
	}
	
	
	function ci(){
		if (navigator.userAgent.indexOf("MSIE")>0){
	     	var file = $("#form-field-input-carga-ci").files; 	
	    }else{
	    	var file = $("#form-field-input-carga-ci")[0].files[0]; 	
	    }
		
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        var size = fileSize/1024;
        var ext = true;
        var peso = true;
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	$('#ci').html('<input id="form-field-input-carga-ci" required name="data[Archivos][ci]" type="file" />');
        	$('#form-field-input-carga-ci').on('change',ci);
        	$('#img-check-ci').hide();
        	$('#img-error-ci').show();
        	return false;
        }
        if(size>30000){
        	alert('El archivo supera el peso establecido.');
        	$('#ci').html('<input id="form-field-input-carga-ci" required name="data[Archivos][ci]" type="file" />');
        	$('#form-field-input-carga-ci').on('change',ci);
        	$('#img-check-ci').hide();
        	$('#img-error-ci').show();
        	return false;
        }
        if(peso && ext){
        	$('#img-check-ci').show();
        	$('#img-error-ci').hide();
        }
	}
	function renta(){
		if (navigator.userAgent.indexOf("MSIE")>0){
	     	var file = $("#form-field-input-carga-renta").files;
	    }else{
	    	var file = $("#form-field-input-carga-renta")[0].files[0]; 	
	    }
		
		var fileName = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize = file.size;
        var size = fileSize/1024;
        var ext = true;
        var peso = true;
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	$('#renta').html('<input id="form-field-input-carga-renta" required name="data[Archivos][renta]" type="file" />');
        	$('#form-field-input-carga-renta').on('change',renta);
        	$('#img-check-renta').hide();
        	$('#img-error-renta').show();
        	return false;
        }
        if(size>30000){
        	alert('El archivo supera el peso establecido.');
        	$('#renta').html('<input id="form-field-input-carga-renta" required name="data[Archivos][renta]" type="file" />');
        	$('#form-field-input-carga-renta').on('change',renta);
        	$('#img-check-renta').hide();
        	$('#img-error-renta').show();
        	return false;
        }
        if(peso && ext){
        	$('#img-check-renta').show();
        	$('#img-error-renta').hide();
        }
	}
	function isImage(extension){
	    switch(extension.toLowerCase()){
	        case 'jpg': case 'gif': case 'png': case 'jpeg': case 'pdf': case 'doc': case 'docx':
	            return true;
	        break;
	        default:
	            return false;
	        break;
	    }
	}
	
	$(window).load(function(){
		
		
		if(navigator.userAgent.indexOf("MSIE")>0 ){
			$('#img-check-licencia').hide();
			$('#img-check-ci').hide();
			$('.check_anexos_').hide();
		}
		
		
	});
</script>
