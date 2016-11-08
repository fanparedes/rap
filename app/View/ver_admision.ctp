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

</style>

<div id="modalMensaje" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align: center;">Decisión sobre el postulante</h3>
	</div>	
	  	<div class="modal-body">
	  		<!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
	  		<div class="row-fluid">
				<input type="hidden" name="data[Administrativo][codigo_postulacion]" value="<?php echo $this->request->params['pass'][0]; ?>" />
	  			<div><!-- <div class="col-md-12"><label class="control-label" for="textarea-rechazo"> Escriba de manera clara la decisión tomada:</label>  -->
	  			<?php /* echo $this->Form->input(
								'motivos', 
								array(									          
									'empty' => '',
									'type' => 'textarea',
									'label' => false,
									'div' => array('class' => 'control-group'),
									'style' => 'width:97%'
								)
							); */
				?>
				</div>
	  		</div>
	  		<div class="row-fluid">
				<div class="col-md-6">
					<label class="control-label" for="textarea-rechazo"> Habilitar:</label>
					<?php echo $this->Form->input(
								'habilitar', 
								array(
									'options' => array('1' => 'Habilitar', '0' => 'Inhabilitar'),           
									'empty' => '',
									'label' => false,
									'class'=> '',
									'required' => true
								)
							);
				?>
				</div>
				<div class="col-md-6">
					<label class="control-label" for="textarea-rechazo"> Archivo Adjunto:</label>
					<?php echo $this->Form->input('archivo', array('label' => false, 'type' => 'file'));
					?>
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
						<p style="margin-top:10px;">Postulante</p>
					</div>
					<div class="span7">
						<h4><?php echo $postulacion['Postulante']['nombre']; ?></h4>
					</div>
				</div>	
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px;">Carrera a la que postula</p>
					</div>
					<div class="span7 ">
						<h4><?php echo $postulacion['Carrera']['nombre']; ?></h4>
					</div>
				</div>
			</div>
			<div class="span6">
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px; ">Teléfono / email</p>
					</div>
					<div class="span7 ">
						<h4><?php echo $postulacion['Postulante']['telefonomovil'].' / '.$postulacion['Postulante']['email']; ?></h4>
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
								echo '<span class="verde">HABILITADO</span>';
							}
							else {
								echo '<span class="rojo">INHABILITADO</span>';
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
<style>
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
<?php $estadoActual = 5; ?>
<div class="row-fluid">
	<div class="span7" style="padding-left:12px;"><h3>Postulación</h3></div>
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
							<?php echo $anexo['ArchivoPrepostulacion']['nombre']; ?>
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
							<h5>Respuesta:</h5>
							<?php if (isset($postulacion['Postulacion']['motivos'])){
								echo $postulacion['Postulacion']['motivos'];
							}
							?>
							<h5>Archivo adjunto:</h5>
							<?php 
                                                        if(count($archivo_resp) && file_exists(WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$archivo_resp['Cargas']['codigo'].'.'.$archivo_resp['Cargas']['extension_archivo'])){
                                                            //echo $archivo_resp['Cargas']['codigo'].'.'.$archivo_resp['Cargas']['extension_archivo'];
                                                        
                                                            echo $this->html->link($archivo_resp['Cargas']['codigo'].'.'.$archivo_resp['Cargas']['extension_archivo'], 	                		
                                                                array('controller'=>'cargas', 'action' => 'descargar_archivo_postulaciones',$archivo_resp['Cargas']['codigo']),
                                                                array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                                            );
                                                        }
                                                        else{
                                                            echo 'No existe archivo';
                                                        }
 
                                                        
                                                        ?>
						</div>
					</div>			  
				</div>
		 
		  </div>
		  </div>
		  </div>
		 </form>
	</div>
	</div>
</div>
<div class="row-fluid botonera">
	<div class="span11" align="right">	
		<?php $usuario = $this->Session->read('UserLogued'); echo var_dump($usuario); if ($usuario['Administrativo']['perfil'] !== 4): ?>
			<?php if ($postulacion['Postulacion']['revision'] == 0): ?><a class="btn btn-success" id="enviarMensaje"><i class="fa icon-envelope-o"></i> Enviar Mensaje al Postulante</a><?php endif; ?>
			<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'listadoPostulacionesNuevas')); ?>" class="btn btn-warning" ><i class="icon-chevron-circle-left"></i> Volver</a>
		<?php endif; ?>
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
</script><br><br><br>