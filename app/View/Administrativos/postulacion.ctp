<?php $fecha_postulacion = $postulacion['Postulacion']['created'] ; 
		if (strtotime($fecha_postulacion) < (strtotime('2015-11-24 23:59:59'))){									
				$accion = 'descargar_archivo_antiguo';	
				$accion2 = 'descargar_archivo_antiguo';										
			} 
			else {
			$accion = 'descargarArchivo';
			$accion2 = 'descargar_anexo';									
		}
?>
<div id="modalMensaje" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'responderPostulante', $cod_postulacion))?>" enctype="multipart/form-data">
		<div class="modal-header" id="modal_header">
			<h3 style="text-align: center;" id="tituloDecision"></h3>
		</div>	
		<div class="modal-body">	  		
				<div class="row-fluid">
					<input type="hidden" name="data[Administrativo][codigo_postulacion]" value="<?php echo $this->request->params['pass'][0]; ?>" />
					<input type="hidden" name="data[Administrativo][habilitar]" value="" id="respuesta"/>
				<div>
				<div class="col-md-12">
					<label class="control-label" for="textarea-rechazo"> Escriba de manera clara la decisión tomada:</label>
						<?php  echo $this->Form->input(
										'motivos', 
										array(									          
											'empty' => '',
											'name' => "data[Administrativo][motivos]",
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
						<a class="btn btn-warning btn-small pull-right" id="botonAgregarArchivo"><i class="icon icon-plus"> </i> Agregar Archivo</a>				
					</div>
				</div>			
			</div>			
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" id="btn-acepta">Aceptar</button>
				<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>		
			</div>	
	</form>
</div>
<div id="modalDesactivar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align: center;">¿Está seguro que desea DESACTIVAR esta Postulación?</h3>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'desactivarPostulacion', $cod_postulacion))?>">	
	  	<div class="modal-body">
	  		<!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
	  		<div class="row-fluid">
	  			<label class="control-label" for="textarea-rechazo"> Motivo de la Desactivación</label>
	  			<textarea id="textarea-rechazo" required class="span12" name="data[Postulacion][motivo_desactivacion]"></textarea>
	  		</div>
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>

<div id="modalborrarPostulante" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<div class="row-fluid">
			<h3 style="text-align: center;">¿Está seguro que desea ELIMINAR esta POSTULACIÓN Y AL POSTULANTE?</h3>
			<br>
			<p><strong><i class="icon-warning"></i> Si acepta se borrarán TODOS los datos asociados al postulante y a la postulación.</strong></p>
		</div>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'borrarPostulante'))?>">	
	  	<?php echo $this->Form->input('postulante', array ('type' => 'hidden', 'value' => $postulacion['Postulacion']['postulante_codigo'])); ?>
		<div class="modal-body">	  		
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>


<div id="modalborrarPostulacion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<div class="row-fluid">
			<h3 style="text-align: center;">¿Está seguro que desea ELIMINAR esta POSTULACIÓN?</h3>
			<br>
			<p><strong><i class="icon-warning"></i> Si acepta se borrarán TODOS los datos asociados a la postulación manteniendo al postulante.</strong></p>
		</div>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'borrarPostulacion'))?>">	
	  	<?php echo $this->Form->input('postulacion', array ('type' => 'hidden', 'value' => $postulacion['Postulacion']['codigo'])); ?>
		<div class="modal-body">	  		
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>

<div id="modalrealizarEntrevista" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<div class="row-fluid">
			<h3 style="text-align: center;">¿Está seguro que desea marcar como REALIZADA la ENTREVISTA?</h3>
			<br>
			<div class="alert alert-info"><strong><i class="icon-warning"></i> Aceptando permitirá al postulante pasar al siguiente paso en su postulación.</strong></div>
		</div>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'realizarEntrevista'))?>">	
	  	<?php echo $this->Form->input('postulante', array ('type' => 'hidden', 'value' => $postulacion['Postulacion']['codigo'])); ?>
		<div class="modal-body">	  		
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			
		</div>
	</form>
</div>



<div id="modalDocumentacion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
  	<div class="modal-body">
  		<h3 style="text-align: center;">¿Está seguro que desea ACEPTAR la Documentación?</h3>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'aceptarDocumentacion', $cod_postulacion)); ?>">Aceptar</a>
	</div>
</div>
<div id="modalCvRap" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
  	<div class="modal-body">
  		<h3 style="text-align: center;">¿Está seguro que desea ACEPTAR el CvRap y la Autoevaluación?</h3>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'aceptarCvrapAE', $cod_postulacion))?>">Aceptar</a>
	</div>
</div>


<div id="modalBorrarTodo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
  	<div class="modal-body">
  		<h3 style="text-align: center;">¿Está seguro que desea BORRAR todo el Postulante?</h3>
		<br>
		<p><strong><i class="icon-warning"></i> Si acepta se borrarán TODOS los datos de postulante y postulación.</strong></p>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'aceptarDocumentacion', $cod_postulacion)); ?>">Aceptar</a>
	</div>
</div>

<div id="modalDesactivarEtapa" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">		
  	<div class="modal-body">
  		<h3 style="text-align: center;">
			<?php 
				if (($postulacion['Estado']['codigo'] == 8) && (!empty($evidencia_datos2)))	{
					echo '¿Está seguro que desea INVALIDAR las Evidencias finales?';
				}
				if (($postulacion['Estado']['codigo'] == 8) && (empty($evidencia_datos2)))	{
					echo '¿Está seguro que desea ELIMINAR la entrevista agendada del postulante?';
				}
				if (($postulacion['Estado']['codigo'] == 6) && (!empty($evidencia_datos)))	{
					echo '¿Está seguro que desea INVALIDAR las Evidencias Previas?';
				}
				if (($postulacion['Estado']['codigo'] == 6) && (empty($evidencia_datos)))	{
					echo '¿Está seguro que desea INVALIDAR EL CV.RAP y la AUTOEVALUACIÓN?';				
				}
				if ($postulacion['Estado']['codigo'] == 5)	{
					echo '¿Está seguro que desea BORRAR los datos de Autoevaluación del postulante?';				
				}
				if ($postulacion['Estado']['codigo'] == 4)	{
					echo '¿Está seguro que desea CANCELAR el CV.RAP del Postulante?';	
				}
				if ($postulacion['Estado']['codigo'] == 3)	{
					echo '¿Está seguro que desea INVALIDAR la documentación del postulante?';	
				}				
				if ($postulacion['Estado']['codigo'] == 2)	{
					echo '¿Está seguro que desea BORRAR la documentación del postulante?';	
				}
				if ($postulacion['Estado']['codigo'] == 1)	{
					echo '¿Está seguro que desea BORRAR esta postulación?';	
				}

			?>
		</h3>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'desactivaretapa', $cod_postulacion))?>">Aceptar</a>
	</div>
</div>

<div id="modalFinalizar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">		
  	<div class="modal-body">
  		<h3 style="text-align: center;">¿Está seguro que desea FINALIZAR la Postulación?</h3>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'finalizarPostulacion', $cod_postulacion))?>">Aceptar</a>
	</div>
</div>
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
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px;">Sede:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo $postulacion['Sede']['nombre_sede']; ?></h4>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px;">Jornada:</p>
					</div>
					<div class="span7 ">
						<h4><?php echo $postulacion['Postulacion']['jornada']; ?></h4>
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
						<p style="margin-top:10px; ">Estado:</p>
					</div>
					<div class="span7">
						<h4><?php 	$estado = $estadoActual['Estado']['codigo'];
								$estado_nombre = $estadoActual['Estado']['nombre'];
								
								if ((($estado)=='6') AND ((!empty($evidencia_datos)))) {
									echo 'EVIDENCIAS PREVIAS VALIDADAS';								
								}
								if ((($estado)=='6') AND ((empty($evidencia_datos)))) {
									echo 'CV RAP Y AUTOEVALUACIÓN APROBADO';								
								}
								if ((($estado)=='8') AND ((!empty($evidencia_datos2)))) {
									echo 'EVIDENCIAS FINALES VALIDADAS';								
								}
								if ((($estado)=='8') AND ((empty($evidencia_datos2)))) {									
									if ((isset($entrevista))&&($entrevista['Entrevista']['estado'] == 'REALIZADO')){
										echo 'ENTREVISTA REALIZADA';							
									}
									else {
										echo 'ENTREVISTA AGENDADA';
									}
								}								
								if (($estado!='6') AND ($estado!='8')) {
									echo $estado_nombre;								
								}				
						
						
						?></h4>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<br/>
<style>

</style>
<div class="row-fluid">
	<div class="span7" style="padding-left:12px;"><h3>Postulación</h3></div>
	<?php if(!empty($ponderacion)):?>
		<div class="span5"><h3>Ponderación Asignaturas</h3></div>
	<?php endif;?>
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
					<div class="span6">
				        <h6>¿Posee al menos un año de experiencia Laboral?</h6>
				        <i class="fa icon-caret-right">
				        <?php if($postulacion['Postulacion']['actividad_laboral'] == 1): ?>
			        	Si<?php else:?>No <?php endif;?> </i>
		        	</div>
		        	<div class="span6">
				        <h6>¿Posee Licencia de Enseñanza Media?</h6>
				        <i class="fa icon-caret-right">
				        <?php if($postulacion['Postulacion']['licencia_educacion_media'] == 1): ?>
				      Si<?php else:?>No <?php endif;?></i>
		       		 </div>
		       	</div>
		       	<div class="row-fluid">	 
		        	<div class="span6">
			        <p><h6>Ciudad de Residencia</h6></p>
			       <i class="fa icon-caret-right"> <?php echo $postulacion['Ciudades']['nombre']; ?></i>
			        </div>
		        	<div class="span6">
			        <p><h6>Carrera a postular</h6></p>
			       	<i class="fa icon-caret-right"> <?php echo $postulacion['Carrera']['nombre']; ?> </i>
			          </div>
			     </div>
			     <div class="row-fluid">     
		        	<div class="span6">
			          <p><h6>Sede</h6></p>
			       <i class="fa icon-caret-right"> <?php echo $postulacion['Sede']['nombre_sede']; ?></i>
			       </div>
		        	<div class="span6">
		        		<p><h6>Jornada</h6></p>
			         <i class="fa icon-caret-right"> <?php echo $postulacion['Postulacion']['jornada']; ?></i>
			         </div>
			       </div>  
			       <div class="row-fluid">     
		        		<div class="span6">   
			          <p><h6>Empresa</h6></p>
			          <i class="fa icon-caret-right capitalizar"> <?php echo $postulacion['Postulacion']['empresa']; ?></i>
			         </div>
			         	<div class="span6">   
			         		<p><h6>Tipo Cargo</h6></p>
			         		<i class="fa icon-caret-right"> <?php echo $postulacion['Cargos']['nombre']; ?></i>
			          	</div>
			       </div>  
			       <div class="row-fluid">     
			          <div class="span6">
			          <p><h6>Cargo</h6></p>
			        <i class="fa icon-caret-right capitalizar"> <?php echo $postulacion['Postulacion']['cargo']; ?></i>
			         </div>	
			           <div class="span6">
			          <p><h6>Medio De Información</h6></p>
			         <i class="fa icon-caret-right"> <?php echo $postulacion['Medios']['nombre']; ?></i>
		      		</div>
		      	</div>
		      </div>
		      </div>
		  </div>
		  <?php if(!empty($documentos)):?>
		  <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseDos">
		        Documentación <?php if (isset($estados_postulacion[1])) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i> '.$this->Ingenia->formatearFecha($estados_postulacion[1]['EstadoPostulacion']['modified']).'</span></h6>'; } ?>
		      </a>
		    </div>
		    <div id="collapseDos" class="accordion-body collapse">
		      <div class="accordion-inner">
		       	<div class="row-fluid">
		                <div class="span3">
		                    <label for="form-field-input-carga-licencia" class="control-label" >Licencia de enseñanza media.</label>
		                </div>
	                	<div class="span6">
	                		<?php if(!empty($documentos['licencia'])): ?>
	                			<label>
	                				<i class="fa icon-caret-right"> <?php echo $this->html->link($documentos['licencia']['nombre_fisico'],
	                					array('controller'=>'cargas', 'action' => $accion, $documentos['licencia']['codigo']),
										array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
										)?> </i>
								</label>		
                			<?php endif; ?>
		            </div>
		      </div>
		      <div class="row-fluid">
                <div class="span3">
                    <label for="form-field-input-carga-ci" class="control-label" >Fotocopia Carnet Identidad</label>
                </div>
            	<div class="span6">
            		<?php if(!empty($documentos['cedula'])): ?>
            			<label> 
            				<i class="fa icon-caret-right"> <?php echo $this->html->link($documentos['cedula']['nombre_fisico'],             		
            					array('controller'=>'cargas', 'action' => $accion, $documentos['cedula']['codigo']),
								array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
								)?> </i>
        				</label>		
        			<?php endif; ?>
                </div>
		      </div>
			  <?php if((isset($anexos)) && (!empty($anexos))){?>
				<?php foreach ($anexos as $anexo): ?>
					  
					  <div class="row-fluid">
						<div class="span3">
							<label for="form-field-input-carga-renta" class="control-label" ><?php echo $anexo['ArchivoPrepostulacion']['tipo']; ?></label>
						</div>
						<div class="span6">							
								<label> 
									<i class="fa icon-caret-right"> <?php echo $this->html->link($anexo['ArchivoPrepostulacion']['nombre_fisico'],
										array('controller'=>'cargas', 'action' => $accion2, $anexo['ArchivoPrepostulacion']['codigo']),
										array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Anexo', 'data-placement' => "right")
										)?></i>
								</label>
						</div>	
					  </div>					
					<?php endforeach; ?>
				<?php 
				} 
				else{
					echo "<span style='font-weight:bold;'>Postulación de tipo convenio</span>";
				}
				?>
		    </div>
		  </div>
		  </div>
		  <?php endif;?>
		  <?php if(!empty($historial_educacional)):?>
		   <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTres">
		        CV Rap <?php if (isset($estados_postulacion[3])) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i>  '.$this->Ingenia->formatearFecha($estados_postulacion[3]['EstadoPostulacion']['modified']).'</h6></span>'; } ?>
		      </a>
		    </div>
		    <div id="collapseTres" class="accordion-body collapse">
		      <div class="accordion-inner">
		        <div class="span12">
		        	<?php if(!empty($historial_educacional)): ?>
		        	<div class="row-fluid">
	                		<div class="span6">
	                			<h4 class="pull-left">Historial Educacional</h4>
	                		</div>
	                	</div>
	                    <table class="table table-bordered table-hover">
	                    	<thead>
	                    		<tr>
	                    			<th class="th-numero">Nº</th>
	                    			<th class="th-institucion">Institución</th>
	                    			<th>Enseñanza</th>
	                    			<th>Observación</th>
	                    		</tr>
	                    	</thead>
	                    	<tbody class="capitalizar">
	                    		
	                    		<?php foreach($historial_educacional as $k => $historial):?>
	                    			<tr>
	                    				<td><?php echo $k+1; ?></td>
	                    				<td ><?php echo $historial['EducacionPostulacion']['institucion']?></td>
	                    				<td><?php echo $historial['tiposEducacion']['nombre']; ?></td>
	                    				<td><?php echo $historial['EducacionPostulacion']['observaciones']; ?></td>
	                    			</tr>
	                    		<?php endforeach; ?>
	                    	</tbody>
	                    </table>
	                   <?php endif; ?> 
	                   <?php if(!empty($capacitaciones)): ?>
	                    <div class="row-fluid">
	                		<div class="span6">
	                			<h4 class="pull-left"> Capacitaciones</h4>
	                		</div>
	                	</div>
	                    <table class="table table-bordered  table-hover">
	                    	<thead>
	                    		<tr>
	                    			<th class="th-numero">Nº</th>
	                    			<th  class="th-institucion">Institución</th>
	                    			<th>Nombre Curso</th>
	                    			<th>Observación</th>
	                    		</tr>
	                    	</thead>
	                    	<tbody class="capitalizar">
	             
	                    		<?php $k=0;foreach($capacitaciones as $k => $capacitacion):?>
	                    			<tr>
	                    				<td><?php echo $k+1; ?></td>
	                    				<td><?php echo $capacitacion['CapacitacionPostulacion']['institucion']?></td>
	                    				<td><?php echo $capacitacion['CapacitacionPostulacion']['nombre_curso']; ?></td>
	                    				<td><?php echo $capacitacion['CapacitacionPostulacion']['observaciones']; ?></td>
	                    			</tr>
	                    		<?php endforeach; ?>
	                    	</tbody>
	                    </table>
	                    <?php endif; ?>
		        		<?php if(!empty($historial_laboral)): ?>
		        		<div class="row-fluid">
	                		<div class="span6">
	                			<h4 class="pull-left">Historial Laboral</h4>
	                		</div>
	                	</div>
	                    <table class="table table-bordered  table-hover">
	                    	<thead>
	                    		<tr>
	                    			<th class="th-numero">Nº</th>
	                    			<th  class="th-lugar">Lugar</th>
	                    			<th>Periodo</th>
	                    			<th  class="th-cargo">Cargo</th>
	                    			<th>Actividades</th>
	                    		</tr>
	                    	</thead>
	                    	<tbody class="capitalizar">
	                    		<?php $k=0;$historial=null;foreach($historial_laboral as $k => $historial):?>
	                    			<tr>
	                    				<td><?php echo $k+1;?></td>
	                    				<td><?php echo $historial['LaboralPostulacion']['lugar_trabajo']?></td>
	                    				<td><?php echo $historial['LaboralPostulacion']['periodo']; ?></td>
	                    				<td><?php echo $historial['LaboralPostulacion']['cargo']; ?></td>
	                    				<td><?php echo $historial['LaboralPostulacion']['actividades']; ?></td>
	                    			</tr>
	                    		<?php endforeach; ?>
	                    	</tbody>
	                    </table>
	                    <?php endif; ?>
					
		        	<div class="row-fluid">
	                		<div class="span6">
	                			<h4 class="pull-left">Observaciones CV Rap</h4>
								
	                		</div>
	                </div>
					<div class="row-fluid">
						<div class="span6">
							<p>
								<?php if (!empty($observaciones)) {
									echo $observaciones;
									}
									else {
										echo 'EL POSTULANTE NO HA REALIZADO NINGUNA OBSERVACI&Oacute;N';
									}
							?></p>
						</div>
					</div>
		        </div>
		      </div>
		    </div>
		  </div>
		  <?php endif;?>
		  <?php if(!empty($competencias)):?>
		  <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseCuatro">
		       Autoevaluación <?php if (isset($estados_postulacion[4])) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i>  '.$this->Ingenia->formatearFecha($estados_postulacion[4]['EstadoPostulacion']['modified']).'</h6></span>'; } ?>
		      </a>
		    </div>
		    <div id="collapseCuatro" class="accordion-body collapse">
		      <div class="accordion-inner">
		      	<div class="row-fluid">
			      	<div class="span6">
		            	<h4 class="">Competencias</h4>
		            </div>
	           	</div>
	            <div class="row-fluid">
					<div class="span3"><i class="fa icon-caret-right"></i> 1: No sé hacerlo.</div>
					<div class="span9"><i class="fa icon-caret-right"></i> 3: Lo realizo de manera autónoma.</div>
				</div>
				<div class="row-fluid">
					<div class="span3"><i class="fa icon-caret-right"></i> 2: Lo realizo con ayuda.</div>
					<div class="span9"><i class="fa icon-caret-right"></i> 4: Lo realizo de manera autónoma, e incluso podría formar a otra persona.</div>
				</div>
		       	<div class="row-fluid">
					<div class="span12">
						<dl>		
				       	<?php foreach ($competencias as $key => $valor): $aux = 1; $aux1= 1;?>
				      	 	<dt><?php echo $key+1; echo "- ".$valor['Compentencia']['nombre_competencia'];?><?php if ($valor['Compentencia']['troncal'] == 1): echo ' <span class="label label-warning">General</span>'; else: echo ' <span class="label label-info">Específica</span>'; endif; ?> </dt>
							<?php foreach($autoevaluacion as $k => $val): ?>
							<?php if($valor['Compentencia']['codigo_competencia'] == $val['UnidadCompetencia']['codigo_competencia']):?>
							<dd><?php echo $key+1; echo ".".$aux1."- ".$val['UnidadCompetencia']['nombre_unidad_comp']; ?></dd>
							<b class="resp"><i class="fa icon-caret-right"></i> <?php echo $val['AutoEvaluacion']['indicador']?></b>
							<?php  $aux1++; else: $aux1 = 1;?>
							<?php endif; ?>
							<?php endforeach;?>
							   
						<?php $aux++; endforeach;    ?>
						</dl>	
					</div>
				</div>	
		      </div>
		    </div>
		  </div>
		  <?php endif; ?>
		  
		  <?php if((!empty($evidencia_datos)) && ($postulacion['Estado']['codigo'] >= 6)):?>		
		  <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseCinco">
		       Evidencias Previas <?php if (isset($evidencia_datos[0]['fecha_validacion']) != null) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i>  '.$this->Ingenia->formatearFecha($evidencia_datos[0]['fecha_validacion']).'</h6></span>'; } ?>
		      </a>
		    </div>
		    <div id="collapseCinco" class="accordion-body collapse">
		      <div class="accordion-inner">
		      	<?php foreach ($evidencia_datos as $evidencia): ?>

			   <div class="row-fluid">
					<?php if(isset($evidencia) && ! empty($evidencia['competencia'])) :?>
					<h5>Unidad de competencia: <span class="capitalizar"><?php echo $evidencia['competencia'];?></span></h5>	
					<?php else :?>
						<h5>Unidad de competencia: <span class="capitalizar">Sin Unidad de competencia</span></h5>	
					<?php endif ;?>
					<div class="row-fluid">
						<div class="span2"> Nombre evidencia:</div>
						
						<div class="span10"><i class="fa icon-caret-right"></i> <?php echo($evidencia['nombre_evidencia']);?></div>
					</div>
					
					<div class="row-fluid">
						<div class="span2"> Justificación:</div>
						<div class="span10"><i class="fa icon-caret-right"></i> <?php echo strip_tags($evidencia['relacion']);?></div>
					</div>
					<div class="row-fluid">
						<div class="span2"> Archivo:</div>
						<div class="span10"><i class="fa icon-caret-right"></i>			
							<?php  
							$nombre = $evidencia['imagen']['ArchivoEvidencia']['nombre_fisico'];
							echo $this->Html->link($nombre,array('controller' => 'postulaciones', 'action' => 'descargarEvidencia', $evidencia['imagen']['ArchivoEvidencia']['codigo'])); 
							?>
						</div>
					</div>
					<hr>
				</div>
				<?php endforeach;?>
		      </div>
		    </div>
		  </div>
		  <?php endif; ?>
		  
		  <!-- SE MOSTRARÁ LA ENTREVISTA SI ESTÁ AGENDADA -->
		  <?php if((!empty($horario)) && ($postulacion['Estado']['codigo'] >= 8) && (isset($entrevista)) ):?>		
		  <div class="accordion-group">
			<div class="accordion-heading">
				  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseEntrevista">
				   Entrevista <?php if (isset($entrevista)) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i>  '.$this->Ingenia->formatearFecha($entrevista['Entrevista']['created']).'</h6></span>'; } ?>
					
				  </a>
				</div>
				<div id="collapseEntrevista" class="accordion-body collapse">
				  <div class="accordion-inner">
						<div class="row-fluid">
							<div class="span3">
								<label for="form-field-input-carga-ci" class="control-label" >Orientador</label>
							</div>
							<div class="span6">
									<i class="fa icon-caret-right"></i> <?php echo $administrativo['Administrativo']['nombre']; ?>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span3">
								<label for="form-field-input-carga-ci" class="control-label" >Hora Inicio</label>
							</div>
							<div class="span6">
									<i class="fa icon-caret-right"></i> <?php echo $this->Ingenia->formatearFecha($horario['Horario']['hora_inicio']); ?>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span3">
								<label for="form-field-input-carga-ci" class="control-label" >Hora Fin</label>
							</div>
							<div class="span6">
									<i class="fa icon-caret-right"></i> <?php echo $this->Ingenia->formatearFecha($horario['Horario']['hora_fin']); ?>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span3">
								<label for="form-field-input-carga-ci" class="control-label" >Estado</label>
							</div>
							<div class="span6">
									<i class="fa icon-caret-right"></i> <?php echo $horario['Horario']['estado'] ; ?>
							</div>
						</div>
				</div>
			</div>
		  </div>
		  <?php endif; ?>  
		
		  <?php if((!empty($evidencia_datos2)) && ($postulacion['Estado']['codigo'] >= 8)):?>		
		  <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSeis">
		       Evidencias Finales <?php if (isset($evidencia_datos2[0]['fecha_validacion']) != null) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i>  '.$this->Ingenia->formatearFecha($evidencia_datos2[0]['fecha_validacion']).'</h6></span>'; } ?>
		      </a>
		    </div>
		    <div id="collapseSeis" class="accordion-body collapse">
		      <div class="accordion-inner">
		      	<?php foreach ($evidencia_datos2 as $evidencia): ?>

			   <div class="row-fluid">
					<?php if(isset($evidencia) && ! empty($evidencia['competencia'])) :?>
					<h5>Unidad de competencia: <span class="capitalizar"><?php echo $evidencia['competencia']?></span></h5>	
					<?php else :?>
						<h5>Unidad de competencia: <span class="capitalizar">Sin Unidad de competencia</span></h5>	
					<?php endif ;?>
					<div class="row-fluid">
						<div class="span2"> Nombre evidencia:</div>
						
						<div class="span10"><i class="fa icon-caret-right"></i> <?php echo($evidencia['nombre_evidencia']);?></div>
					</div>
					
					<div class="row-fluid">
						<div class="span2"> Justificación:</div>
						<div class="span10"><i class="fa icon-caret-right"></i> <?php echo strip_tags($evidencia['relacion']);?></div>
					</div>
					<div class="row-fluid">
						<div class="span2"> Archivo:</div>
						<div class="span10"><i class="fa icon-caret-right"></i>			
							<?php  
							$nombre = $evidencia['imagen']['ArchivoEvidencia']['nombre_fisico'];
							echo $this->Html->link($nombre,array('controller' => 'postulaciones', 'action' => 'descargarEvidencia', $evidencia['imagen']['ArchivoEvidencia']['codigo'])); 
							?>
						</div>
					</div>
					<hr>
				</div>
				<?php endforeach;?>
		      </div>
		    </div>
		  </div>
		  <?php endif; ?>	


		  <?php if((!empty($archivo_firma))):?>		
		  <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSiete">
		       Acta de Trayectoria Formativa <?php echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i>  '.$this->Ingenia->formatearFecha($postulacion['Postulacion']['modified']).'</h6></span>';  ?>
		      </a>
		    </div>
		    <div id="collapseSiete" class="accordion-body collapse">
		      <div class="accordion-inner">
			   <div class="row-fluid">
				<?php
					echo $this->html->link('<i class="icon-download">  </i>Descargar Acta de Trayectoria Formativa', 	                		
											array('controller'=>'cargas', 'action' => 'descargarDocumento', $archivo_firma['Cargas']['codigo']),
											array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Acta de Trayectoria Formativa', 'data-placement' => "right", 'escape' => false)
							);		
				?>
				</div>				
		      </div>
		    </div>
		  </div>
		  <?php endif; ?>
		  
		  
		  
		  
		</div>	
	<?php endif;?>		
	</div>
	<?php if(!empty($ponderacion)):?>
		<div class="span5 ponderacion">
			<table class="table table-bordered table-hover">
				<tr>
					<th>#</th>
					<th>Sigla</th>
					<th>Asignatura</th>
					<th>Ponderación</th>
				</tr>
				<?php $aux = 1; foreach($ponderacion as $sigla => $asignatura): ?>
					<tr>
						<td><?php echo $aux;?></td>
						<td><?php echo $asignatura['sigla'];?></td>
						<td><?php echo $asignatura['asignatura']?></td>
						<td class="center"><?php echo $asignatura['porcentaje'].'%'?></td>
					</tr>
				<?php $aux++; endforeach;?>
			</table>
		</div>
	<?php endif;?>
</div>
<div class="row-fluid botonera">
	<div class="span12" align="right">
		<a class="btn" id="desactivarPostulacion"><i class="fa icon-ban"></i> Desactivar Postulación</a>
		<?php if(($estadoActual['Estado']['codigo'] > 0) && (($estadoActual['Estado']['codigo'] < 7)) || (($estadoActual['Estado']['codigo'] == 8)))  : ?>
			<a class="btn" id="desactivarEtapa" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'desactivaretapa', $cod_postulacion))?>"><i class="fa icon-mail-reply"></i> Desactivar Última Etapa</a>
		<?php endif; ?>
		<?php if($estadoActual['Estado']['codigo'] == 2): ?>
			<a id="aceptarDocumentacion" class="btn btn-success" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'aceptarDocumentacion', $cod_postulacion))?>"><i class="fa icon-check  "></i>Aceptar Documentación</a>		
		<?php elseif($estadoActual['Estado']['codigo'] == 5): ?>
			<a id="aceptarCvRap" class="btn btn-success"><i class="fa icon-caret-right"></i> Aceptar CvRap y Autoevaluación</a>
		<?php elseif(($estadoActual['Estado']['codigo'] == 8) && ($entrevista['Entrevista']['estado']=='ACTIVO') ): ?>
		<a id="realizarEntrevista" class="btn btn-success"><i class="fa icon-pencil"></i> Entrevista Realizada</a>
		<?php elseif(($estadoActual['Estado']['codigo'] == 8) && (!empty($evidencia_datos2))) : ?>
			<a id="finalizarPostulacion" class="btn btn-success"><i class="fa icon-check"></i> Finalizar Postulación</a>
  		<?php endif; ?>
  		<?php if($estadoActual['Estado']['codigo'] != 7 && $estadoActual['Estado']['codigo'] != 9): ?>
			<a id="cancelarPostulacion" class="btn btn-danger"><i class="fa icon-ban"></i>Inhabilitar Postulación</a>
		<?php endif; ?>
  		<a class="btn btn-info" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'postulacionArchivo', $cod_postulacion))?>"><i class="icon-file"></i> Generar PDF</a>
  		
		<a class="btn btn-danger" id="borrarPostulante"><i class="fa icon-trash-o"></i> Borrar Postulante</a>
		<a class="btn btn-danger" id="borrarPostulacion"><i class="fa icon-trash-o"></i> Borrar Postulación</a>
		<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones')); ?>" class="btn btn-warning" ><i class="icon-chevron-circle-left"></i> Volver</a>
	</div>
</div>
<script>
	$('#cancelarPostulacion').on('click',function(){
		$('#modalMensaje').modal('show');
		$('.archivo_administrativo').prop('required',false);		
		$('#col_adjunto').hide();		
		$('.archivo').hide();		
		$('.botones').hide();		
		$('#respuesta').val('0');	
		$('.archivo input').each(function(){
			($(this).attr('required', false));
		})		
		$('#tituloDecision').html('Inhabilitar Postulación');		
		return false;
	});
	$('#finalizarPostulacion').on('click',function(){
		$('#modalMensaje').modal('show');
		$('.archivo_administrativo').prop('required',true);	
		$('#col_adjunto').show();	
		$('#respuesta').val('1');	
		$('.archivo').show();		
		$('.botones').show();	
		$('.archivo input').each(function(){
			($(this).attr('required', true));
		})	
		$('#tituloDecision').html('Habilitar Postulación');
		return false;
	});
	
	$('#aceptarDocumentacion').on('click',function(){
		$('#modalDocumentacion').modal('show');
		return false;
	});
	$('#aceptarCvRap').on('click',function(){
		$('#modalCvRap').modal('show');
		return false;
	});
	$('#desactivarPostulacion').on('click',function(){
		$('#modalDesactivar').modal('show');
		return false;
	});
	$('#borrarPostulante').on('click',function(){
		$('#modalborrarPostulante').modal('show');
		return false;
	});
	$('#borrarPostulacion').on('click',function(){
		$('#modalborrarPostulacion').modal('show');
		return false;
	});
	$('#desactivarEtapa').on('click',function(){
		$('#modalDesactivarEtapa').modal('show');
		return false;
	});
	$('#realizarEntrevista').on('click',function(){
		$('#modalrealizarEntrevista').modal('show');
		return false;
	});	
	$('#borrarTodo').on('click',function(){
		$('#modalBorrarTodo').modal('show');
		return false;
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
<br/>