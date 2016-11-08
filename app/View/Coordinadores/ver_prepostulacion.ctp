<style>
form{
    margin-bottom:20px;
}
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
    #validacionVerPrepostulacionForm{
        margin-bottom:0px!important;
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
<div id="modalValidar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<!-- <h3 style="text-align: center;">¿Está seguro que desea VALIDAR esta postulación?</h3> -->
		<h3 style="text-align: center;">¿Está seguro que desea aceptar esta postulación?</h3>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Coordinadores', 'action' => 'validarPrepostulacion', $prepostulacion['Prepostulacion']['id']))?>">	
	  	<div class="modal-body">
	  		<!--<h3 style="text-align: center;">¿Está seguro que desea ACEPTAR esta Postulación?</h3>-->
	  		<div class="row-fluid">				
				A la vista de la documentación, este postulante debe ir a:<br>
				<?php $destinos = array('RAP'=>'RAP','AH'=>'ESCUELAS','AV'=>'ARTICULACIÓN');?>
				<?php echo $this->Form->input('destinoPostulante', array('type'=>'select','id' => 'destinoPostulante', 'label'=> false, 'options'=>$destinos, 'default'=>'RAP', 'class' => 'control-group')); ?>
				<p>Validando la postulación, será el administrativo la persona que tendrá que decidir, qué hacer con el postulante. <br>
	  		</div>	  		
		</div>
		<div class="modal-footer">
			<a type="submit" class="btn btn-primary" id="btn-validar" >Aceptar</a>
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>			
		</div>
	</form>
</div>
<div id="modalInvalidar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<!-- <h3 style="text-align: center;">¿Está segura(o) que desea INVALIDAR esta postulación?</h3> -->
		<h3 style="text-align: center;">¿Está segura(o) que desea RECHAZAR esta postulación?</h3> 
	</div>	

        <div class="modal-body">
                <!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
                <div class="row-fluid">
                        <label class="control-label" for="textarea-rechazo"> Motivos del rechazo</label>
                        <textarea id="textarea-rechazo" required class="span12" maxlength="200"></textarea>
                </div>
        </div>
        <div class="modal-footer">
                <a type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</a>
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
						<h4><?php echo $prepostulacion['Postulante']['nombre'].' '.$prepostulacion['Postulante']['apellidop'].' '.' '.$prepostulacion['Postulante']['apellidom'].' '; ?></h4>
					</div>
				</div>	
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px;">Correo:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo $prepostulacion['Postulante']['email']; ?></h4>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px;">Carrera a la que postula:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo ($prepostulacion['Carrera']['nombre']); ?></h4>
					</div>
				</div>
			</div>
			<div class="span6">
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px; ">Rut:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo substr($prepostulacion['Postulante']['rut'], 0,8).'-'.substr($prepostulacion['Postulante']['rut'], -1, 1); ?></h4>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px; ">Teléfono</p>
					</div>
					<div class="span7">
						<h4>
							<?php echo $prepostulacion['Postulante']['telefonomovil']; ?>
						</h4>
					</div>
				</div>
				<?php 
				if($prepostulacion['Prepostulacion']['revision'] == 1 || $prepostulacion['Prepostulacion']['revision'] == 2){
				?>
					<div class="row-fluid">
						<div class="span3 offset1 ">
							<p style="margin-top:10px; ">Estado</p>
						</div>
						<div class="span7">
							<h4>	
						<?php 
						/*
							if ((isset($postulacion) && (!empty($postulacion)))){ //La prepostulación ha pasado a ser postulación
								if ($postulacion['Postulacion']['tipo'] <> 'RAP'){ 
									if ($postulacion['Postulacion']['habilitado'] == '0'){
										echo '<span class="rojo">NO HABILITADO</span>';
									}
									elseif ($postulacion['Postulacion']['habilitado'] == '1'){
										if (($postulacion['Postulacion']['csa'] == '1')){
											echo '<span class="verde">INGRESADO EN CSA</span>';	
										}
										elseif  (($postulacion['Postulacion']['firma'] == '1')){
											echo '<span class="verde">HABILITADO CON FIRMA</span>';	
										}
										else{
											echo '<span class="verde">HABILITADO</span>';
										}										
									}
									elseif ($postulacion['Postulacion']['habilitado'] == null){
										echo 'Pendiente de revisión';
									}		
								}
								else{
									switch($maximo['maximo']){
														case 1:
															$estado = 'Formulario de postulación';															
														break;
														case 2:
															$estado = 'Documentación';															
														break;
														case 3:
															$estado = 'CV Rap';															
														break;
														case 4:
															$estado = 'Autoevaluación';															
														break;
														case 5:
															$estado = 'CV RAP Y Autoevaluación completa en revisión';
															
														break;
														case 6:
															$estado = 'Agendar entrevista';															
														break;
														case 7:
															$estado = '<span class="rojo">No habilitado</span>';															
														break;
														case 8:
															$estado = 'Informe de evidencias final';															
														break;
														case 9:
															$estado = '<span class="verde">Habilitado</span>';															
														break;
													}
									if (isset($estado)): echo 'RAP: '.($estado);
									else: echo 'RAP: Pendiente de rellenar el formulario de postulación'; endif;
								}
							}
							else { // Es solamente prepostulación								
								if($prepostulacion['Prepostulacion']['revision'] == 1): echo 'Pendiente de revisar por el alumno'; else: echo 'Pendiente de Revisar'; endif; 
							}*/

							if($prepostulacion['Prepostulacion']['destino'] !== null){//Si la postulación ya fue derivada por el coordinador	
								switch ($prepostulacion['Prepostulacion']['destino']) {
									case 'RAP':
									//var_dump($prepostulacion); die;
										if (isset($prepostulacion[0]['EstadoPostulacion'])){
											$maximo = max($prepostulacion[0]['EstadoPostulacion']);
											$estado = '';
											$paso   = '';
											
											$resp   = false;
											$resp   = in_array(7,$prepostulacion[0]['EstadoPostulacion']); //Verifico si está rechazada
											
											if($resp){
													$estado = '<span class="rojo">POSTULACIÓN RECHAZADA</span>';
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
										if (isset($postulacion['Postulacion']['habilitado'])){																									
											if($postulacion['Postulacion']['habilitado'] == '1'){
													if (($postulacion['Postulacion']['firma'] == '1') && ($postulacion['Postulacion']['csa'] == '1') ){
														$estado = '<span class="verde">INGRESADO EN CSA</span>';	
													}
													elseif (($postulacion['Postulacion']['firma'] == '1') && ($postulacion['Postulacion']['csa'] !== '1') ){
														$estado = '<span class="verde">HABILITADO CON FIRMA</span>';														
													}
													else{
														$estado = '<span class="verde">HABILITADO</span>';
													}					
												}
											
												elseif ($postulacion['Postulacion']['habilitado'] == '0') {
													$estado = '<span class="rojo">NO HABILITADO</span>';
												}
												else {
													$estado = 'EN REVISIÓN 2';
												}
										}
										else{
											$estado = 'EN REVISIÓN 3';
										}
										echo $estado;
										break;
									case 'AV':
										$estado = '';
										if (isset($postulacion['Postulacion']['habilitado'])){	
										if($postulacion['Postulacion']['habilitado'] == '1'){
												if (($postulacion['Postulacion']['firma'] == '1') && ($postulacion['Postulacion']['csa'] == '1') ){
													$estado = '<span class="verde">INGRESADO EN CSA</span>';	
												}
												elseif (($postulacion['Postulacion']['firma'] == '1') && ($postulacion['Postulacion']['csa'] !== '1') ){
													$estado = '<span class="verde">HABILITADO CON FIRMA</span>';														
												}
												else{
													$estado = '<span class="verde">HABILITADO</span>';														
												}
											}
											elseif ($postulacion['Postulacion']['habilitado'] == '0') {
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
								if($postulacion['Prepostulacion']['revision']==0){
										echo 'PENDIENTE DE REVISAR';
								}
								else{
										echo 'DOCUMENTOS CON OBSERVACIONES';
								}
							}

							?>							
						</h4>
							</h4>
						</div>
					</div>
				<?php 
				}
				?>
			</div>
		</div>
	</div>
</div>
<br/>
<style>

</style>
<?php $estadoActual = 5; ?>
<div class="row-fluid">
	<div class="span8" style="padding-left:12px;">
		<h3>Postulación <?php if (isset($prepostulacion['Prepostulacion']['id_correlativo'])): echo '('.$prepostulacion['Prepostulacion']['id_correlativo'].')'; endif; ?></h3>
	</div>
	<div class="span3 pull-right">
		<h3>
		<?php if ((isset($postulacion['Postulacion'])) && ($postulacion['Postulacion']['firma'] == '1')):?>
			<?php echo $this->Form->create('Coordinadore', array(
				'inputDefaults' => array(
					'div' => false,
					'label' => false,
					'wrapInput' => false
				),
				'action' => 'csa',
				'class' => 'form-inline',			
			)); ?>			
				<?php 
				$checked = $postulacion['Postulacion']['csa'];			
				if($checked == '1'){ $checked = true;} else {$checked = false;}
				
				echo $this->Form->input('multiple', array(
					'type'=> 'checkbox',
					'class' => 'input-small',
					'label' => ' Postulación Ingresada a CSA',
					'checked' => $checked,
				)); ?>
				<?php echo $this->Form->input('postulacion', array('type'=> 'hidden', 'value' => $prepostulacion['Prepostulacion']['codigo_postulacion'])); ?>
				<?php echo $this->Form->input('prepostulacion', array('type'=> 'hidden', 'value' => $prepostulacion['Prepostulacion']['codigo'])); ?>
				<a class="btn btn-small btn-success" type="submit" id="guardarCSA">Guardar</a>
				</h3>
			<?php echo $this->Form->end(); ?>	
		<?php endif; ?>
	</div>
</div>
<div class="row-fluid">
	<div class="<?php echo empty($ponderacion)? 'span12':'span7';?>">
            <?php if(!empty($prepostulacion)):?>
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
			       <i class="fa icon-caret-right"> <?php echo $prepostulacion['Ciudad']['nombre']; ?></i>
			        </div>
		        	<div class="span4">
			        <p><h6>Carrera a postular</h6></p>
			       	<i class="fa icon-caret-right"> <?php echo $prepostulacion['Carrera']['nombre']; ?> </i>
			          </div>
			    </div>
		      </div>
		      </div>
		  </div>
                <?php if(1 == 1):?>
                <div class="accordion-group">
                     <?php echo $this->Form->create('validacion'); ?>
                    
		    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseDos">
                            Documentación Genérica
                        </a>
		    </div>
		    <div id="collapseDos" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <div class="row-fluid">
                                    <div class="span3">Archivo:</div>
                                    <div class="span3">Nombre:</div>
                                    <div class="span3">Estado:</div>
                                    <div class="span3">Acciones:</div>
                            </div>
                            
                            <div class="row-fluid">
                               
                                <div class="span3">
                                    <label for="form-field-input-carga-ci" class="control-label">Fotocopia Carnet Identidad</label>
                                </div>
                                <div class="span3">
                                <?php if(!empty($cedulaIdentidad)): ?>
                                    <label> 
                                            <i class="fa icon-caret-right">
                                                <?php echo $this->html->link($cedulaIdentidad['ArchivoPostulante']['nombre'], 	                		
                                                    array('controller'=>'cargas', 'action' => 'descargarArchivo', $cedulaIdentidad['ArchivoPostulante']['codigo']),
                                                    array('class' => 'tool',  "download" => $cedulaIdentidad['ArchivoPostulante']['nombre'],"data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                                )?> 
                                            </i>
                                    </label>		

                                </div>
                                <div class="span3 icono-cedula">
                                    <?php 
                                    //valido_new campo estado solo para coordinador
                                    if($cedulaIdentidad['ArchivoPostulante']['valido_new']==null) {
                                            echo '<i class="icon-circle icono"></i>';
                                    }
                                    elseif (($cedulaIdentidad['ArchivoPostulante']['valido_new']==0)){
                                            echo '<i class="icon-times rojo icono "></i>';						
                                    }
                                    else {
                                            echo '<i class="icon-check-circle verde icono"></i>';							
                                    }
                                    ?>
                                    <?php endif; ?>
                                </div>
                                <div class="span3">
									<?php if ((!isset($postulacion) && (empty($postulacion)))): ?>
										<?php if ($cedulaIdentidad['ArchivoPostulante']['valido_new'] == null) : ?>
												<a id="validarCedula" href="" class="btn btn-success btn-mini"><i class="icon-thumbs-o-up"></i> Aceptar</a>
												<a id="invalidarCedula" href="" class="btn btn-danger btn-mini"><i class="icon-thumbs-o-down"></i> Rechazar</a>
										<?php endif; ?>
										<?php if ($cedulaIdentidad['ArchivoPostulante']['valido_new'] !== null) : ?>
												<a id="validarCedula" href="" class="btn btn-success btn-mini" <?php if ($cedulaIdentidad['ArchivoPostulante']['valido_new']==1): ?> style="display:none;" <?php endif;?>><i class="icon-thumbs-o-up"></i> Aceptar</a>
												<a id="invalidarCedula" href="" class="btn btn-danger btn-mini" <?php if ($cedulaIdentidad['ArchivoPostulante']['valido_new']==0): ?> style="display:none;" <?php endif;?>><i class="icon-thumbs-o-down"></i> Rechazar</a>
										<?php endif; ?>
                                    <?php endif; ?>        
									<?php 
									$valor_ = '';
									
									if($cedulaIdentidad['ArchivoPostulante']['valido_new'] != null){
										$valor_ = $cedulaIdentidad['ArchivoPostulante']['valido_new'];
									}
									else{
										$valor_ = 'null';
									}
									?>
                                    <?php echo $this->Form->input('cedula', array('type' => 'hidden', 'name' => 'data[validacion][cedula]', 'class' => 'validacion', 'value'=> $valor_)); ?>							
                                
                                </div>
                            </div>
                            
                            <!-- -->
                            <div class="row-fluid">
                                    <div class="span3">
                                        <label for="form-field-input-carga-licencia" class="control-label" >Licencia de enseñanza media</label>
                                    </div>
                                    <div class="span3">
                                            <?php if(!empty($licencia)): ?>
                                                    <label>
                                                        <i class="fa icon-caret-right"> 
                                                            <?php 
                                                            echo $this->html->link($licencia['ArchivoPostulante']['nombre'], 	                		
                                                                array('controller'=>'cargas', 'action' => 'descargarArchivo', $licencia['ArchivoPostulante']['codigo']),
                                                                array('class' => 'tool', "download" => $licencia['ArchivoPostulante']['nombre'], "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                                                )
                                                            ?> 
                                                        </i>
                                                    </label>		
                                            <?php endif; ?>
                                    </div>
                                    <div class="span3 icono-licencia">
                                        <?php if ($licencia['ArchivoPostulante']['valido_new']==null){
                                                echo '<i class="icon-circle icono"></i>';
                                                        }
                                                elseif (($licencia['ArchivoPostulante']['valido_new']==0)){
                                                        echo '<i class="icon-times rojo icono"></i>';						
                                                }
                                                else {
                                                        echo '<i class="icono icon-check-circle verde"></i>';							
                                                }
                                        ?>
                                    </div>
                                    <?php //echo $this->Form->create('validacion'); asd ?>
                                    <div class="span3">
                                            <?php if ((!isset($postulacion) && (empty($postulacion)))): ?>
												<?php if ($licencia['ArchivoPostulante']['valido_new']==null){ ?>
														<a id="validarLicencia" href="" class="btn btn-success btn-mini"><i class="icon-thumbs-o-up"></i> Aceptar</a>
														<a id="invalidarLicencia" href="" class="btn btn-danger btn-mini"><i class="icon-thumbs-o-down"></i> Rechazar</a>
												<?php } else{ ?>

												<a id="validarLicencia" href="" class="btn btn-success btn-mini" <?php if ($licencia['ArchivoPostulante']['valido_new']==1): ?> style="display:none" <?php endif;?>><i class="icon-thumbs-o-up"></i> Aceptar</a>


												<a id="invalidarLicencia" href="" class="btn btn-danger btn-mini" <?php if ($licencia['ArchivoPostulante']['valido_new']==0): ?> style="display:none" <?php endif;?> ><i class="icon-thumbs-o-down"></i> Rechazar</a>
											
                                            <?php } ?>
											<?php endif; ?>
											<?php 
											$valor_ = '';
									
											if($licencia['ArchivoPostulante']['valido_new'] != null){
												$valor_ = $licencia['ArchivoPostulante']['valido_new'];
											}
											else{
												$valor_ = 'null';
											}
											?>
											
                                            <?php echo $this->Form->input('licencia', array('type' => 'hidden', 'class' => 'validacion', 'value'=> $valor_)); ?>
                                    </div>
                            </div>
                            <!-- -->
                            
                            
                        </div>
                    </div>
                </div>
                <!-- --> 
                
            <div class="accordion-group">
		    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseTres">
                            Documentación Anexa
                        </a>
		    </div>

		    <div id="collapseTres" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <div class="row-fluid">
                                    <div class="span3">Nombre:</div>
                                    <div class="span3">Archivo:</div>
                                    <div class="span3">Estado:</div>
                                    <div class="span3">Acciones:</div>
                            </div>
                            
                            <?php
							if(count($anexos)>0){
                            foreach($anexos as $row){
                            ?>
                                <div class="row-fluid">
                                    <div class="span3">
                                            <label for="form-field-input-carga-licencia" class="control-label">
												<?php 
												if ($row['ArchivoPrepostulacion']['tipo']!== 'Convenio'){
													echo $row['ArchivoPrepostulacion']['nombre'].' ('.$row['ArchivoPrepostulacion']['tipo'].')'; 
													$nombre_descarga = $row['ArchivoPrepostulacion']['nombre'].' ('.$row['ArchivoPrepostulacion']['tipo'].')'; 
													}
												else{
													echo $row['ArchivoPrepostulacion']['tipo'];
													$nombre_descarga = $row['ArchivoPrepostulacion']['tipo'];
												}
												?>
											
											</label>
                                    </div>
                                    <div class="span3">
                                            
                                            <?php if(!empty($row['ArchivoPrepostulacion']['nombre_fisico'])): ?>
                                                    <label>
                                                        <i class="fa icon-caret-right"> 
                                                            <?php 
                                                            echo $this->html->link($row['ArchivoPrepostulacion']['nombre_fisico'], 	                		
                                                                array('controller'=>'cargas', 'action' => 'descargar_anexo', $row['ArchivoPrepostulacion']['codigo']),
                                                                array('class' => 'tool', "download" => $nombre_descarga , "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                                            )
                                                            ?> 
                                                        </i>
                                                    </label>		
                                            <?php endif; ?>
                                        
                                    </div>
                                    <div class="span3">
                                            
                                        <?php 
                                        if($row['ArchivoPrepostulacion']['valido_new']==null){
                                                echo '<i class="icon-circle icono"></i>';
                                        }
                                        elseif(($row['ArchivoPrepostulacion']['valido_new']==0)){
                                                echo '<i class="icon-times rojo icono "></i>';						
                                        }
                                        else{
                                                echo '<i class="icon-check-circle verde icono"></i>';							
                                        }
                                        ?>
                                        
                                    </div>
                                    <div class="span3">
										<?php if ((!isset($postulacion) && (empty($postulacion)))): ?> 
                                            
                                        <?php 
                                        $valor_anexo = '';
                                        
                                        if($row['ArchivoPrepostulacion']['valido_new'] == null): 
                                        ?>
                                            <a id="validarAnexo" href="javascript:void(0)" class="btn btn-success btn-mini validarAnexo"><i class="icon-thumbs-o-up"></i> Aceptar </a>
                                            <a id="invalidarAnexo" href="javascript:void(0)" class="btn btn-danger btn-mini invalidarAnexo"><i class="icon-thumbs-o-down"></i> Rechazar </a>
                                        <?php 
                                        endif; 
                                        ?>
                                        <?php 
                                        if($row['ArchivoPrepostulacion']['valido_new'] !== null):
                                            
                                                $valor_anexo = $row['ArchivoPrepostulacion']['valido_new'];
                                        ?>
                                                
                                                <a id="validarAnexo" href="javascript:void(0)" class="btn btn-success btn-mini validarAnexo" <?php if ($row['ArchivoPrepostulacion']['valido_new']==1): ?> style="display:none;" <?php endif;?>><i class="icon-thumbs-o-up"></i> Aceptar</a>
                                                <a id="invalidarAnexo" href="javascript:void(0)" class="btn btn-danger btn-mini invalidarAnexo" <?php if ($row['ArchivoPrepostulacion']['valido_new']==0): ?> style="display:none;" <?php endif;?>><i class="icon-thumbs-o-down"></i> Rechazar</a>
                                        <?php 
                                        endif; 
                                        ?>
                                                
                                        
                                        
										<?php 
										$valor_ = '';
								
										if($row['ArchivoPrepostulacion']['valido_new'] != null){
											$valor_ = $row['ArchivoPrepostulacion']['valido_new'];
										}
										else{
											$valor_ = 'null';
										}
										?>
										
										<?php 
										
                                        echo $this->Form->input($row['ArchivoPrepostulacion']['codigo'], array('type' => 'hidden', 'class' => 'validacion_anexo','value'=> $valor_)); ?>					
                                      
										<?php endif; ?>
                                    </div>
                                </div>
                            <?php     
                            }
							}
							else{
								echo "<span style='font-weight:bold;'>Postulación de tipo convenio</span>";
							}
                            ?>
                        </div>
		    </div>
		    </div>
			<?php if (isset($postulacion) && (!empty($postulacion))): // SI YA ESTÁ EVALUADO APARECERÁN LOS DATOS AQUÍ ?>
		    <div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseCuatro">
						Respuesta
					</a>
				</div>
				<div id="collapseCuatro" class="accordion-body collapse">
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
																		<?php foreach ($archivo_resp as $archivo):?>
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
			<?php endif; //DE RESPUESTA ?>
                <?php endif;?>
                <?php endif;?>
           	
			<?php if (!empty($acta_firmada)) : //ACTA FIRMADA?>
			 <div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseCinco">
						Acta de Trayectoria Formativa
					</a>
				</div>
				
				<div id="collapseCinco" class="accordion-body collapse">
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
            </div><?php echo $this->Form->input('accion', array('type' => 'hidden', 'id' => "accionFormulario", 'class' => '', 'value'=> 'guardar')); ?>
        </div>
		<?php echo $this->Form->input('destino', array('type' => 'hidden', 'id' => "destino", 'class' => '', 'value'=> '')); ?>
</div>
    
    <?php echo $this->Form->input('motivos', array('type' => 'hidden', 'id' => "motivosInvalidacion", 'class' => '', 'value'=> '')); ?>
    </form>
    </div>
    </div>
</div>
<?php if (!isset($postulacion) && (empty($postulacion))): // SI LA PREPOSTULACIÓN HA PASADO A POSTULACIÓN LOS BOTONES NO TIENEN PORQUÉ APARECER ?>
<div class="row-fluid botonera">
	<div class="span11" align="right">		
		<a class="btn btn-success" id="aceptarPrepostulacion"><i class="fa icon-check-square-o"></i> Aceptar Postulación</a>
		<a class="btn btn-danger" id="invalidarPrepostulacion"><i class="fa icon-reply"></i> Rechazar Postulación</a>
		<a class="btn btn-primary" id="guardarPrepostulacion"><i class="fa icon-floppy-o"></i> Guardar Postulación</a>
		<a href="javascript:window.close();" class="btn btn-warning" ><i class="icon-chevron-circle-left"></i> Volver</a>
	</div>
</div>
<?php endif; ?>
<script>
	$('#aceptarPrepostulacion').on('click',function(){
		$('#modalValidar').modal('show');
		return false;
	});	
	$('#invalidarPrepostulacion').on('click',function(){
		$('#modalInvalidar').modal('show')
		return false;
	});
</script><br><br><br>
<script>
	$('#invalidarLicencia').on('click',function(){
		$('input#validacionLicencia').val('0').change();
		$('#validarLicencia').show();
		$('#invalidarLicencia').hide();
                
                if($(this).parent('div').parent('div').find('.icono').attr('class') == 'icon-circle icono'){
                    $(this).parent('div').parent('div').find('.icono').removeClass('icon-circle').addClass('icon-times rojo icono');
                }
                else{
                    $(this).parent('div').parent('div').find('.icono').removeClass('icon-check-circle verde').addClass('icon-times rojo icono');
                }
                
		$(this).parent('div').find('#validacionLicencia').val('0');
                return false;
	});
	$('#validarLicencia').on('click',function(){
		$('input#validacionLicencia').val('1').change();
		$('#validarLicencia').hide();
		$('#invalidarLicencia').show();
                
                $(this).parent('div').parent('div').find('.icono').removeClass('icon-times rojo').addClass('icon-check-circle verde');
                
                $(this).parent('div').find('#validacionLicencia').val('1');
		return false;
	});	
	$('#validarCedula').on('click',function(){
		$('input#validacionCedula').val('1').change();		
		$('#validarCedula').hide();
		$('#invalidarCedula').show();
                
                $(this).parent('div').parent('div').find('.icono').removeClass('icon-times rojo').addClass('icon-check-circle verde');
                
                $(this).parent('div').find('#cedula').val('1');
		return false;
	});	
	$('#invalidarCedula').on('click',function(){
		$('input#validacionCedula').val('0').change();
		$('#validarCedula').show();
		$('#invalidarCedula').hide();
                
                if($(this).parent('div').parent('div').find('.icono').attr('class') == 'icon-circle icono'){
                    $(this).parent('div').parent('div').find('.icono').removeClass('icon-circle').addClass('icon-times rojo icono');
                }
                else{
                    $(this).parent('div').parent('div').find('.icono').removeClass('icon-check-circle verde').addClass('icon-times rojo icono');
                }
                
                $(this).parent('div').find('#cedula').val('0');
		return false;
	});
	
        
        //Anexo
        $('.validarAnexo').on('click',function(){
            
            $(this).parent("div").find(".validacion_anexo").val(1); //hidden
            $(this).hide();
            $(this).parent("div").find("#invalidarAnexo").show();
            
            $(this).parent('div').parent('div').find('.icono').removeClass('icon-times rojo').addClass('icon-check-circle verde');
            
            validar();
            
	});	
        
        $('.invalidarAnexo').on('click',function(){
        
            $(this).parent("div").find(".validacion_anexo").val(0); //hidden
            $(this).hide();
            $(this).parent("div").find("#validarAnexo").show();
            
            $(this).parent('div').parent('div').find('.icono').removeClass('icon-check-circle verde').addClass('icon-times rojo');
        
            validar();
        });
	
	function validar(){

		var invalidos = 0;
		var validos   = 0; 
		var nulos     = 0;
                
                $('input[type=hidden].validacion').each(function(){
                        
                        if(($(this).val()) == 1){
                            validos++;
                        }
                        if(($(this).val()) == 0){
                            invalidos++;
                        }
                        if($(this).val() == 'null' || $(this).val() == ''){
                            nulos++;
                        }
                });
                
                $('.validacion_anexo').each(function(i){
                        
                    if(($(this).val()) == 1){
                        validos++;
                    }
                    if(($(this).val()) == 0){
                        invalidos++;
                    }
                    if($(this).val() == 'null' || $(this).val() == ''){
                        nulos++;
                    }
                });
                
                if(invalidos > 0){
                    $("#aceptarPrepostulacion").hide();
                    $("#invalidarPrepostulacion").show();
                }
                else{
                    $("#aceptarPrepostulacion").show();
                    $("#invalidarPrepostulacion").show();
                }
				
				if(nulos > 0){
					$("#aceptarPrepostulacion").hide();
                    $("#invalidarPrepostulacion").hide();
				}
				$("#invalidarPrepostulacion").show();
		
	}
	
	$('.validacion').on('change',function(){
            validar();		
	});
	
	$(document).ready(function(){
            validar();
	});
</script>
<script>
	$('#btn-rechazo').on('click',function(){
		$('input#accionFormulario').val('invalidar');
		var motivos = $('#textarea-rechazo').val();
		$('input#motivosInvalidacion').val(motivos);
		$('#validacionVerPrepostulacionForm').submit();
		//console.log('Invalido Formulario');
		//window.close();
		return false;
	});

	$('#btn-validar').on('click',function(){
		var destino = $('#destinoPostulante').val();	
		$('#destino').val(destino);		
		$('#accionFormulario').val('validar');		
		$('#validacionVerPrepostulacionForm').submit();   
		return false;
	});
	
	$('#guardarPrepostulacion').on('click',function(){
                $('input#accionFormulario').val('guardar');	
                $('#validacionVerPrepostulacionForm').submit();		
                return false;
	});
</script>
<script>
	$('#guardarCSA').on('click',function(){
                $('#CoordinadoreCsaForm').submit();	                
                return false;
	});	
</script>