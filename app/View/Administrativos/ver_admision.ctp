<?php echo $this->Form->create('', array('controller' => 'Administrativos', 'action' => 'responderPostulante', 'type' => 'file')); ?>
<style>
.resumen {
    border: 1px solid #E2E2E2;
    margin-left: 1%;
    padding-top: 10px;
    width: 97.8%;
}
.modal-footer{
	background-color:white;
}
.modal-header{
	border-bottom: 0px;
}

.accordion-heading a{
	font-size: 20px;
	font-weight: 700;
	letter-spacing: 1;
}

.accordion-heading a:hover{
	text-decoration: none;
}

p#competencias{
	font-size: 15px;
	font-weight: 400;
}

#comp{
	font-size: 20px;
	margin: 10px;
}

b.resp{
	margin-left: 30px;
	font-weight: 200;
}
	.accordion-inner h6{
		color:#002d56;
	}
	
	p.respuesta{
		margin-top: -10px;
		margin-left: 10px;
	}
	.accordion {
	    margin-left: 1%;
	    padding-top: 10px;
	    width: 97.8%;
	}
	.accordion-group{
		webkit-border-radius:0px !important;
		-moz-border-radius: 0px !important;
		border-radius:0px !important;
	}
	.botonera{
		margin-left: 1%;
	    padding-top: 10px;
	    width: 97.8%;
	}
	.ponderacion{
		padding-right:20px;
	}
	.ponderacion .center{text-align:center;}

</style>

<div id="modalMensaje" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align: center;">Decisión sobre el postulante</h3>
	</div>	
	  	<div class="modal-body">
	  		<!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
	  		<div class="row-fluid">
				<input type="hidden" name="data[Administrativo][codigo_postulacion]" value="<?php echo $this->request->params['pass'][0]; ?>" />
	  			<div>
				<div class="col-md-12">
				<label class="control-label" for="textarea-rechazo"> Escriba de manera clara la decisión tomada:</label>
	  			<?php  echo $this->Form->input(
								'motivos', 
								array(									          
									'empty' => '',
									'type' => 'textarea',
									'label' => false,
									'div' => array('class' => 'control-group'),
									'style' => 'width:97%'
								)
							); 
				?>
				</div>
				</div>
	  		</div>
	  		<div class="row-fluid">
				<div class="col-md-6">
					<label class="control-label" for="textarea-rechazo"> Habilitar:</label>
					<?php echo $this->Form->input(
								'habilitar', 
								array(
									'options'  => array('1' => 'Habilitar', '0' => 'No Habilitar'),         
									'empty'    => '',
									'label'    => false,
									'class'    => 'select_hi',
									'required' => true
								)
							);
				?>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row-fluid archivo" id="col_adjunto" style="display:none;">
					<div class="span12">
						<div class="row-fluid">
							<div class="span4">
								<label class="control-label" for="textarea-rechazo"> Nombre:</label>
								<?php 
									echo $this->Form->input('archivo', array('label' => false, 'type' => 'text','class' => 'nombreAnexo','required', 'id' => 'Archivo', 'name' => 'data[Administrativo][archivos][archivo][nombre]', 'style' => 'width:155px!important'));
								?>
							</div>
							<div class="span5">
								<label class="control-label" for="textarea-rechazo"> Archivo Adjunto:</label>
								<?php 
									echo $this->Form->input('archivo', array('label' => false, 'type' => 'file','class' => 'archivoAnexo','required', 'name' => 'data[Administrativo][archivos][archivo][fichero]'));
								?>
							</div>
						</div>
					</div>				
			</div>
			<div class="row-fluid" id="nuevosArchivos">
			
			</div>
			<div class="row-fluid">
				<div class="span12 botones">
					<a class="btn btn-danger btn-small pull-right" id="botonBorrarArchivo" style="display:none"><i class="icon icon-trash-o"> </i> Borrar Archivo</a>&nbsp;
					<a class="btn btn-warning btn-small pull-right" id="botonAgregarArchivo" ><i class="icon icon-plus"> </i> Agregar Archivo</a>				
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" id="btn-acepta">Aceptar</button>
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		</div>	
</div>

<br/>
<div class="row-fluid resumen ">
	<div class="span12">
		<div class="row-fluid">
			<div class="span6">
				<div class="row-fluid">
					<div class="span3 offset1">
						<p style="margin-top:10px;">Nombre:</p>
					</div>
					<div class="span7">
						<h4><?php echo $postulacion['Postulante']['nombre'].' '.$postulacion['Postulante']['apellidop'].' '.$postulacion['Postulante']['apellidom'].' '; ?></h4>
					</div>
				</div>	
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px;">Correo:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo $postulacion['Postulante']['email']; ?></h4>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px;">Carrera a la que postula:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo $postulacion['Carrera']['nombre'];  ?></h4>
					</div>
				</div>
			</div>
			<div class="span6">
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px; ">Rut:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo substr($postulacion['Postulante']['rut'], 0,8).'-'.substr($postulacion['Postulante']['rut'], -1, 1); ?></h4>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px; ">Teléfono:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo $postulacion['Postulante']['telefonomovil']; ?></h4>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px; ">Estado</p>
					</div>
					<div class="span7">
						<h4><?php 
						if ($postulacion['Postulacion']['revision'] == 1){ echo '';
							if ($postulacion['Postulacion']['habilitado'] == 1) {
								if((isset($archivo_resp))&&(!empty($archivo_resp))){
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
						} ?></h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br/>
<?php $estadoActual = 5; ?>
<div class="row-fluid">
	<div class="span7" style="padding-left:12px;"><h3>Postulación <?php if (isset($postulacion['Postulacion']['id_correlativo'])){ echo ('('.$postulacion['Postulacion']['id_correlativo'].')'); } ?></h3></div>
</div>
<div class="row-fluid">
	<div class="<?php echo empty($ponderacion)? 'span12':'span7';?>">
		<?php if(!empty($postulacion)):?>
		<div class="accordion" id="accordion2">
		  <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseUno">
		       Formulario de Postulación 
				<?php if (isset($estados_postulacion[8])) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i> '.$this->Ingenia->formatearFecha($estados_postulacion[8]['EstadoPostulacion']['modified']).'</h6></span>'; }
					else {
				if (isset($estados_postulacion[0])) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i> '.$this->Ingenia->formatearFecha($estados_postulacion[0]['EstadoPostulacion']['modified']).'</h6></span>'; }} ?>
		     </a> 
		    </div>
		    <div id="collapseUno" class="accordion-body collapse in">
		      <div class="accordion-inner">
		      	<div class="row-fluid">
		        	<div class="span4">
				        <h6>¿Posee Licencia de Enseñanza Media?</h6>
				        <i class="fa icon-caret-right">Sí</i>
		       		 </div>
		        	<div class="span4">
			        <p><h6>Ciudad de Residencia</h6></p>
			       <i class="fa icon-caret-right"> <?php echo $postulacion['Ciudad']['nombre']; ?></i>
			        </div>
		        	<div class="span4">
			        <p><h6>Carrera a postular</h6></p>
			       	<i class="fa icon-caret-right"> <?php echo $postulacion['Carrera']['nombre']; ?> </i>
			          </div>
			    </div>
		      </div>
		      </div>
		  </div>
		  <?php if(1 == 1):?>
		  <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseDos">
		        Documentación
		      </a>
		    </div>
		    <div id="collapseDos" class="accordion-body collapse">
		      <div class="accordion-inner">
				<div class="row-fluid">
					<div class="span3">Archivo:</div>
					<div class="span3">Nombre:</div>
					<div class="span3">Estado:</div>
					<div class="span3">Tipo:</div>
				
				</div>
		       	<div class="row-fluid">
		                <div class="span3">
		                    <label for="form-field-input-carga-licencia" class="control-label" >Licencia de enseñanza media</label>
		                </div>
	                	<div class="span3">
	                		<?php if(!empty($licencia)): ?>
	                			<label>
	                				<i class="fa icon-caret-right"><?php echo $this->html->link($licencia['ArchivoPostulante']['nombre'], 	                		
	                					array('controller'=>'cargas', 'action' => 'descargarArchivo', $licencia['ArchivoPostulante']['codigo']),
										array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
						)?> </i>
								</label>		
                			<?php endif; ?>
						</div>
							<div class="span3 icono-licencia">
							<?php if ($licencia['ArchivoPostulante']['valido']==null) {
								echo '<i class="icono icon-circle icono"></i>';
									}
								elseif (($licencia['ArchivoPostulante']['valido']==0)){
									echo '<i class="icon-times rojo icono"></i>';						
								}
								else {
									echo '<i class="icono icon-check-circle verde"></i>';							
								}
							?>
						</div>
						
						<div class="span3">
							<?php  if (($licencia['ArchivoPostulante']['tipo']=='CEDULA') || ($licencia['ArchivoPostulante']['tipo']=='LICENCIA')) {
										echo 'GENÉRICA';
									}
									else {
										echo '';
									}
								?>
						</div>
		      </div>
		      <div class="row-fluid">
					<div class="span3">
						<label for="form-field-input-carga-ci" class="control-label">Fotocopia Carnet Identidad</label>
					</div>
					<div class="span3">
						<?php if(!empty($cedulaIdentidad)): ?>
							<label> 
								<i class="fa icon-caret-right"><?php echo $this->html->link($cedulaIdentidad['ArchivoPostulante']['nombre'], 	                		
											array('controller'=>'cargas', 'action' => 'descargarArchivo', $cedulaIdentidad['ArchivoPostulante']['codigo']),
											array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
							)?> </i>
							</label>		
						
					</div>
					<div class="span3 icono-cedula">
						<?php if ($cedulaIdentidad['ArchivoPostulante']['valido']==null) {
							echo '<i class="icon-circle icono"></i>';
								}
							elseif (($cedulaIdentidad['ArchivoPostulante']['valido']==0)){
								echo '<i class="icon-times rojo icono "></i>';						
							}
							else {
								echo '<i class="icon-check-circle verde icono"></i>';							
							}
						?>
						<?php endif; ?>
					</div>
					<div class="span3">
								<?php  if (($cedulaIdentidad['ArchivoPostulante']['tipo']=='CEDULA') || ($cedulaIdentidad['ArchivoPostulante']['tipo']=='LICENCIA')) {
											echo 'GENÉRICA';
										}
										else {
											echo '';
										}
									?>						
					</div>
		      </div>
			  <?php if (isset($anexos)): ?>
				<?php foreach ($anexos as $k => $anexo): ?>
					<div class="row-fluid">
						<div class="span3">
							<?php 
							if($anexo['ArchivoPrepostulacion']['tipo'] !== 'Convenio'){
								echo $anexo['ArchivoPrepostulacion']['nombre']; 
							}			
							?>
						</div>
						<div class="span3">
							<label> 
								<i class="fa icon-caret-right"><?php echo $this->html->link($anexo['ArchivoPrepostulacion']['nombre_fisico'], 	                		
											array('controller'=>'cargas', 'action' => 'descargar_anexo', $anexo['ArchivoPrepostulacion']['codigo']),
											array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Archivo Anexo', 'data-placement' => "right")
							)?> </i>
							</label>
						</div>
						<div class="span3">
							<?php echo '<i class="icon-check-circle verde icono"></i>'; ?>
						</div>
						<div class="span3">
							<?php echo mb_strtoupper($anexo['ArchivoPrepostulacion']['tipo']); ?>						
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		    </div>
		  </div>
		  
		  </div>
		  <?php endif;?>
		  <?php endif;?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTres">
							Respuesta
						</a>
					</div>
					<div id="collapseTres" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="row-fluid">
							<!-- -->	
								
									<style>
									.subtitle{
										color:#6E6E6E;
										border-bottom:1px solid #6e6e6e;	
										margin-bottom:30px;
									}
									.resumen{
										border: 1px solid #E2E2E2;
										margin-left: 1%;
										width: 100%;
										padding: 25px;
									}
									</style>
									<?php $usuario = $this->Session->read('UserLogued');?>
									<div class="row">
										<div class="span10 offset1" style="margin-left:2%!important;">
											<div class="row">
												<h3 style='margin-left:41px;'>Estado de admisión:</h3>
											</div>
											<div class="clearfix"></div>
											<div class="row resumen">
												<div class="span2">
													Código:<br><br>
													Carrera a la que postula:<br><br>
													Estado: <br>
												</div>
												<div class="span5">
													<b><?php if (isset($postulacion['Postulacion']['id_correlativo'])): echo $postulacion['Postulacion']['id_correlativo'].'<br><br>'; endif; ?></b>
													<b><?php echo $postulacion['Carrera']['nombre']; ?></b><br><br>
													<?php 
													if($postulacion['Postulacion']['habilitado'] == '1'){
														echo '<span class="verde">HABILITADO</span>';					
													}
													elseif ($postulacion['Postulacion']['habilitado'] == '0') {
														echo '<span class="rojo">INHABILITADO</span>';
													}
													else {
														echo '<b>Tu documentación será revisada por un administrativo de Duoc UC</b>';
													}
													?>
												</div>		
										
											</div>
											<div class="clearfix"><br></div>
												<div class="row resumen">
															<h4>Estimado Postulante:</h4>
															<?php if (($postulacion['Postulacion']['habilitado'] == '1') && (!empty($archivo_firma))): ?>
																	<p>Su postulación ha sido recibida. Pronto recibirá una notificación vía mail del correocsa@duoc.cl con la información de la carrera, jornada y sede a la cual usted postuló, recibiendo entre una a tres notificaciones según el número de sedes ingresadas por usted. Debe tener presente que los tiempos de respuesta de estas sedes pueden ser diferentes y son informados en cada notificación.</p>						
																	<br>
																	<?php echo $this->html->link('<i class="icon-download">  </i>Descargar Acta de Trayectoria Formativa', 	                		
																							array('controller'=>'cargas', 'action' => 'descargarDocumento', $archivo_firma['Cargas']['codigo']),
																							array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Acta de Trayectoria Formativa', 'data-placement' => "right", 'escape' => false)
																			);	
																	?>
																	<br>
															<?php endif; ?>	
															<br>
															<?php if (($postulacion['Postulacion']['habilitado'] == '1') && ((empty($archivo_firma)))): ?>
																	<p>Ud. ha sido habilitado. Para revisar el detalle de las asignaturas aprobadas, descargue el archivo adjunto, complételo, fírmelo,adjúntelo a través del botón “Seleccionar archivo” y presione “Enviar”.</p>
																	<p><?php if (isset($postulacion['Postulacion']['motivos'])) : echo 'Motivo: '.$postulacion['Postulacion']['motivos']; endif; ?></p>
																	<?php 
																	if ((!empty($archivo_resp)) && ((empty($archivo_firma)))): ?>
																		<?php foreach ($archivo_resp as $archivo): ?>
																		<label> 
																				<?php echo $this->html->link('<i class="icon-download">  </i>Descargar '.$archivo['Cargas']['nombre_fisico_archivo'], 	                		
																							array('controller'=>'cargas', 'action' => 'descargarDocumento', $archivo['Cargas']['codigo']),
																							array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar '.$archivo['Cargas']['nombre_fisico_archivo'], 'data-placement' => "right", 'escape' => false)
																			)?>
																		</label>
																		<?php endforeach; ?>
																		<br><br>					
																	<?php endif; ?>

																	<?php 
																		if (empty($archivo_firma)){
																			echo '<p>Adjuntar acta de trayectoria formativa:</p>';												
																			//echo $this->Form->input('archivo',array( 'type' => 'file', 'required' => true, 'label' => false, 'disabled' => true));
																			echo '<br>';
																			echo $this->Form->input('postulacion', array('type'=>'hidden', 'value' => $postulacion['Postulacion']['codigo']));
																			echo $this->Form->input('postulante', array('type'=>'hidden', 'value' => $postulacion['Postulante']['codigo']));																							
																			echo $this->Form->end(); 
																		}
																		else{

																			echo $this->html->link('<i class="icon-download">  </i>Descargar Acta de Trayectoria Formativa', 	                		
																							array('controller'=>'cargas', 'action' => 'descargarDocumento', $archivo_firma['Cargas']['codigo']),
																							array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Acta de Trayectoria Formativa', 'data-placement' => "right", 'escape' => false)
																			);						
																		}
																?>								
															<?php endif; ?>
															<?php if ($postulacion['Postulacion']['habilitado'] == '0'): ?>
																<p>Tu documentación ha sido revisada, sin embargo no cumples los requisitos para ingresar por una de las vías de admisión.</p>
																<p><?php if (isset($postulacion['Postulacion']['motivos'])) : echo 'Motivo: '.$postulacion['Postulacion']['motivos']; endif; ?></p>
															<?php endif; ?>	
															<?php if ($postulacion['Postulacion']['habilitado'] == null): ?>
																<p>Tu documentación está siendo revisada por un administrativo de Duoc UC.</p>
																<p>Por favor, ingresa periódicamente a la plataforma para revisar tu estado.</p>
															<?php endif; ?>
															<p><br></p><p></p>			
															<p>Atentamente:</p>
															<p>Administrativo de Duoc UC</p>
															<?php echo $this->Html->Image('logo-duoc.png') ?>
															<br>
														</div>
											<br><br><br>
										</div>
									</div>
							<!-- -->
							</div>
						</div>
					</div>			  
				</div>
			<?php if (!empty($acta_firmada)) :?>
			 <div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseCuatro">
						Acta de Trayectoria Formativa
					</a>
				</div>
				
				<div id="collapseCuatro" class="accordion-body collapse">
					<div class="accordion-inner">
						<div class="row-fluid">
							<?php
							echo $this->html->link('<i class="icon-download">  </i>Descargar archivo de firma', 	                		
											array('controller'=>'cargas', 'action' => 'descargarDocumento', $acta_firmada['Cargas']['codigo']),
											array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento de Firma', 'data-placement' => "right", 'escape' => false)
							);	
							?>							
						</div>
					</div>
				</div>			  
			</div>
			<?php endif;?>
		  </div>
		  </div>
		  </div>
		 </form>
	</div>
	</div>
</div>
<div class="row-fluid botonera">
	<div class="span11" align="right">	
		<?php $usuario = $this->Session->read('UserLogued');  if ($usuario['Administrativo']['perfil'] !== '4'): ?>
				<?php if ($postulacion['Postulacion']['revision'] == 0): ?><a class="btn btn-success" id="enviarMensaje"><i class="fa icon-envelope-o"></i> Enviar Mensaje al Postulante</a><?php endif; ?>
			<?php endif; ?>
			<a href="javascript:window.close();" class="btn btn-warning" ><i class="icon-chevron-circle-left"></i> Volver</a>		
	</div>
</div>
<script>
	$('#enviarMensaje').on('click',function(){
		$('#modalMensaje').modal('show')
		return false;
	});
	$('#btn-aceptar').on('click',function(){
		$('#AdministrativoResponderPostulanteForm').submit();
		return false;
	});
	$('.botones').hide();
	$('.select_hi').change(function(){		
		if($(this).val() == 0){
			$('#col_adjunto').hide(300);
			$('.botones').hide(300);
			$('.archivo').hide(300);
			$('.archivo_administrativo').removeAttr('required');
			$('.archivo input').each(function(){
				($(this).attr('required', false));
			})	
		}
		else{
			$('#col_adjunto').show(300); 
			$('.archivo').show(300); 
			$('.botones').show(300); 
			$('.archivo_administrativo').attr('required',true);
			$('.archivo input').removeAttr('required', true);
			$('.archivo input').each(function(){
				($(this).attr('required', true));
			})	
			
		}
	});	
</script>
<script>
var anexos = 1;
$('#botonAgregarArchivo').click(function(){				
			anexos++;
			//$('input[name=ticketID]').attr("disabled",true);			
            if(anexos > 1){ $('#botonBorrarArchivo').show(); }
			//ESTABLECEMOS UN LÍMITE MÁXIMO DE ARCHIVOS A SUBIR EN UNA CARGA
			if(anexos > 4) {
				$('#botonAgregarArchivo').hide();							
			}
            var clone = $("#col_adjunto").clone(true);
			clone.find('.nombreAnexo').attr('id', 'Archivo'+anexos);
			clone.attr('id', 'col_adjunto'+anexos);

            clone.find('.nombreAnexo').val('');
            clone.find('.nombreAnexo').attr('name', 'data[Administrativo][archivos][archivo'+anexos+'][nombre]');
            clone.find('.archivoAnexo').attr('name', 'data[Administrativo][archivos][archivo'+anexos+'][fichero]');
            clone.find('.archivoAnexo').attr('id', 'AdministrativoArchivo'+anexos);
			
			clone.show();
			clone.appendTo("#nuevosArchivos");
});

		$('#botonBorrarArchivo').click(function() {		
			$( "#col_adjunto"+anexos+'').remove();	
			console.log(anexos);
			anexos--;
			if (anexos == 1) { 			
				$('#botonBorrarArchivo').hide(); 
			}
			if ((anexos<5) && (anexos > 1)){
				$('#botonAgregarArchivo').show();
			}
		});  
		$('.archivoAnexo').change(function(){			
			if (renta($(this).attr("id")) == false){
				$(this).val('');
			}
		});	       
</script>
<script>

	function renta(id){		
		if (navigator.userAgent.indexOf("MSIE")>0){
	     	var file = $("#"+id).files;
	    }else{
	    	var file = $("#"+id)[0].files[0]; 	
	    }
		
        var fileName      = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize      = file.size;
        var size          = fileSize/1024;
        var ext           = true;
        var peso          = true;
        
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	return false;
        }
        //if(size>30000){
        if(size>5120){
        	alert('El archivo supera el peso establecido (máximo 5MB por archivo).');
        	return false;
        }
	}
	
	function isImage(extension){		
	    switch(extension.toLowerCase()){
	        case 'jpg': case 'jpeg': case 'pdf': case 'doc': case 'docx': case 'png': case 'rar': case 'zip': 
	            return true;
	        break;
	        default:
	            return false;
	        break;
	    }

	}

</script>
<br><br><br>