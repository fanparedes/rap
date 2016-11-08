<style>
.subtitle{
	color:#6E6E6E;
	border-bottom:1px solid #6e6e6e;	
	margin-bottom:30px;
}
.btn-success{
    float:left;
}
#guardarPrepostulacion{
    float:left;
    max-width:84px;
    margin-left:7px;
}
#guardarPopup{
    max-width:84px;
    margin-left:7px;
}
#guardarPopup i{
    margin-right:4px;
}
#enviarPopup i{
    margin-right:4px;
}
#nuevoAnexo{
	margin-top:0px;
}
</style>
<?php $usuario = $this->Session->read('UserLogued');?>


<?php echo $this->Form->create('Prepostulacion', array('type' =>'file','name' => 'formPrePostulacion')); ?>

<!-- MODAL --> 
<div id="modalValidar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align:center;" id="titulo_modal">¿Está seguro que desea enviar esta postulación?</h3>
	</div>
        <div class="modal-body">
                <!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
                <div class="row-fluid">				
                    <p id="descrip_modal">Al enviar esta postulación llegará inmediatamente al coordinador, ¿Desea confirmarlo?</p>
                    <div id="carga_postulacion" style="margin: 0px auto; width: 25%;">
                        <?php echo $this->Html->image('loader.gif'); ?>
                    </div>
                </div>	  		
        </div>
        <div class="modal-footer">
                <?php echo $this->Form->button('<i class="icon-envelope"></i> Enviar', array('type'=> 'submit','class'=>'btn btn-success letra','id' =>'enviarPrepostulacion'), array('escape' => false));?>
                <?php echo $this->Form->button('<i class="icon-save"></i> Guardar', array('type'=> 'submit','class'=>'btn btn-primary letra','id'=>'guardarPrepostulacion'),array('escape' => false));?>
                <a data-dismiss="modal" aria-hidden="true" class="btn cerrar" id="cierra_modal" href="#">Cerrar</a>			
        </div>
	
</div>
<!-- MODAL -->

<!-- MODAL MÁXIMO DE ARCHIVOS --> 
<div id="modalMaximo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align:center;" id="titulo_modal">Ha llegado al límite de archivos</h3>
	</div>
        <div class="modal-body">
                <!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
                <div class="row-fluid">				
                    <p id="descrip_modal">Si desea subir más archivos, ud. tiene la posibilidad de subirlos en un archivo comprimido RAR o ZIP de hasta 5MB.</p>
                </div>	  		
        </div>
        <div class="modal-footer">
                <a data-dismiss="modal" aria-hidden="true" class="btn cerrar" href="#">Aceptar</a>			
        </div>
	
</div>
<!-- MODAL -->

<div class="row-fluid">
	<div class="span11 offset1">
		<div class="row">
			<h2>Formulario de Postulación:</h2>
		</div>
		<div class="clearfix"></div>
		<div class="row-fluid"><br><br></div>
                
		<input type='hidden' name='data[Prepostulacion][licencia]' value='1' required id="form-field-input-experiencia"/>
                
		<div class="row">
                        <div class="span8">
                            <label for="form-field-input-experiencia" class="control-label">¿En qué ciudad vives actualmente?</label>
                        </div>		                
                        <div class="span3">
                           <select id="form-field-input-ciudad" name="data[Prepostulacion][ciudad_codigo]" class="pull-right" required>
                                <option></option>
                                <?php foreach($ciudades as $ciudad): ?>
                                        <option value="<?php echo $ciudad['Ciudad']['codigo']; ?>"><?php echo $ciudad['Ciudad']['nombre'];?></option>
                                <?php endforeach; ?>
                           </select>
                        </div>	
		</div>
                
                
                <!-- Escuelas -->
                <div class="row">
                        <div class="span8">
                            <label for="form-field-input-escuelas" class="control-label">Escuela a postular</label>
                        </div>		                
                        <div class="span3">
                           <select id="form-field-input-escuela" required  name="data[Prepostulacion][escuela_id]" class="pull-right">
                                <option></option>
                                <?php foreach($escuelas as  $escuela): ?>
                                        <option value="<?php echo $escuela['Escuela']['id']; ?>" <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['escuela_id']) && $postulacion['Postulacion']['escuela_id']==$escuela['Escuela']['id'])? 'selected':'';?>><?php echo ($escuela['Escuela']['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>	
		</div>
                <!-- Carreras -->
		<div class="row">
                        <div class="span8">
                            <label for="form-field-input-carrera" class="control-label">Carrera a postular</label>
                        </div>		                
                        <div class="span3" id="contenedor_carreras">
                            <select id="form-field-input-carrera" required  name="data[Prepostulacion][carrera_id]" class="pull-right">
                                <option></option>
                            </select>
                            <div class="pull-right hide" id="cargador_carreras"><?php echo $this->Html->image('loader.gif'); ?></div>
                        </div>	
		</div>
                <!-- Sedes -->
                <div class="row">
                        <div class="span8">
                            <label for="form-field-input-sede" class="control-label">Sede a postular</label>
                        </div>	                
                        <div class="span3" id="contenedor_sedes">
                            <select id="form-field-input-sedes" required  name="data[Prepostulacion][sede_id]" class="pull-right">
                                <option></option>
                            </select>
                            <div class="pull-right hide" id="cargador_sedes"><?php echo $this->Html->image('loader.gif'); ?></div>
                        </div>	
		</div>
                
                
                
		<div class="row">
			<div class="span11">
                            <h4>Documentación obligatoria:</h4>
                            <div class="alert alert-info">
                               Para continuar el proceso, debes adjuntar obligatoriamente los documentos solicitados.
                            </div> 
							<div class="alert alert-info">
								 <h4>Atención</h4>
								<ul>
									<li>archivos permitidos: </li>
										<ul>
											<li>PDF</li>
											<li>COMPRIMIDOS (rar - zip)</li>
											<li>WORD (doc - docx)</li>
											<li>IMAGENES (jpg - png - jpeg)</li>
										</ul>
									<li>Peso máximo de cada archivo: <strong>5 MB</strong>.</li>		
									<li><strong>Si no ingresas la documentación completa, tu cuenta se desactivará en un plazo de 6 meses.</strong></li>
									<li>Para acceder a tu licencia de enseñanza media, haz <strong><a target="_blank" href="https://www.ayudamineduc.cl/">click aquí</a>.</strong></li>
									
								</ul>
                            </div>
			</div>
		</div>
		<div class="row">
			<div class="span3 subtitle">
				<h4>Tipo Documento</h4>		
			</div>
			<div class="span5 subtitle">
				<h4>Archivo</h4>		
			</div>
			<div class="span3 subtitle">
				<h4>Estado</h4>		
			</div>
		</div>
		<div class="row margen">
			<div class="span3">
				Cédula de Identidad (por ambos lados)
			</div>
			<div class="span5" id="ci">
                            
                                <?php 
                                if(count($cedula_archivo)>0){
                                    if($cedula_archivo['ArchivoPostulante']['nombre_fisico'] != '' && file_exists(WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$cedula_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$cedula_archivo['ArchivoPostulante']['extension']) && ($cedula_archivo['ArchivoPostulante']['valido'] == '1' || $cedula_archivo['ArchivoPostulante']['valido'] == null)){
                                        
                                        $nombre_archivo = $cedula_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$cedula_archivo['ArchivoPostulante']['extension'];
                                        echo "<label class='nombreArchivo_lbl'>".$this->html->link($nombre_archivo, 	                		
                                            array('controller'=>'cargas', 'action' => 'descargarArchivo',$cedula_archivo['ArchivoPostulante']['codigo']),
                                            array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                        )."</label>";
                                        
                                        echo $this->Form->input('cedula_estado', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][ci][estado]', 'class' => 'cedulaEstado','value' => 'ok'));
                                    }
                                    else{
                                       echo $this->Form->input('cedula_identidad',array('type' => 'file','id' => 'form-field-input-carga-ci', 'name' => 'data[Archivos][ci]', 'label' => false, 'required' => true));
                                       echo $this->Form->input('cedula_estado', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][ci][estado]', 'class' => 'cedulaEstado','value' => 'por_validar'));
                                       
                                    }
                                }
                                else{
                                    echo $this->Form->input('cedula_identidad',array('type' => 'file','id' => 'form-field-input-carga-ci', 'name' => 'data[Archivos][ci]', 'label' => false, 'required' => true));
                                    echo $this->Form->input('cedula_estado', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][ci][estado]', 'class' => 'cedulaEstado','value' => 'por_validar'));
                                }
                                ?>
			</div>
			<div class="span3">
				<div id="info-ci">
                                    
                                        <?php 
                                        if(count($cedula_archivo)>0){
                                            if($cedula_archivo['ArchivoPostulante']['nombre_fisico'] != '' && file_exists(WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$cedula_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$cedula_archivo['ArchivoPostulante']['extension'])){
                                                
                                                if($cedula_archivo['ArchivoPostulante']['valido'] == null){
                                                    echo "<div id='img-error-ci' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." No hay archivo</div>";
                                                    echo "<div id='img-check-ci' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Correcto (será evaluado)</div>";
                                                }
                                                elseif($cedula_archivo['ArchivoPostulante']['valido'] == '0'){
                                                    echo "<div id='img-error-ci' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." Archivo Invalido, por favor cambiar</div>";
                                                    echo "<div id='img-check-ci' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Correcto (será evaluado)</div>";
                                                }
                                                else{
                                                    echo "<div id='img-error-ci' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." No hay archivo</div>";
                                                    echo "<div id='img-check-ci' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Validado</div>";
                                                }
                                                
                                            }
                                            else{
                                                echo "<div id='img-error-ci' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." No hay archivo</div>";
                                                echo "<div id='img-check-ci' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Permitido</div>";

                                            }
                                        }
                                        else{
                                            echo "<div id='img-error-ci' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." No hay archivo</div>";
                                            echo "<div id='img-check-ci' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Permitido</div>";
                                        }
                                        ?>
                                    
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span3">
				Licencia de Enseñanza Media
			</div>
			<div class="span5" id="licencia">
                            
                                <?php
                                if(count($licencia_archivo)>0){
                                    if($licencia_archivo['ArchivoPostulante']['nombre_fisico'] != '' && file_exists(WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$licencia_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$licencia_archivo['ArchivoPostulante']['extension']) && ($licencia_archivo['ArchivoPostulante']['valido'] == '1' || $licencia_archivo['ArchivoPostulante']['valido'] == null)){
                                        $nombre_archivo = $licencia_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$licencia_archivo['ArchivoPostulante']['extension'];
                                        echo "<label class='nombreArchivo_lbl'>".$this->html->link($nombre_archivo, 	                		
                                            array('controller'=>'cargas', 'action' => 'descargarArchivo',$licencia_archivo['ArchivoPostulante']['codigo']),
                                            array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                        )."</label>";
                                        echo $this->Form->input('licencia_estado', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][licencia][estado]', 'class' => 'cedulaEstado','value' => 'ok'));
                                    }
                                    else{
                                       echo $this->Form->input('licencia',array('type' => 'file','id' => 'form-field-input-carga-licencia', 'name' => 'data[Archivos][licencia]', 'label' => false, 'required' => true));
                                       echo $this->Form->input('licencia_estado', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][licencia][estado]', 'class' => 'licenciaEstado','value' => 'por_validar'));
                                       
                                    }
                                }
                                else{
                                    echo $this->Form->input('licencia',array('type' => 'file','id' => 'form-field-input-carga-licencia', 'name' => 'data[Archivos][licencia]', 'label' => false, 'required' => true));
                                    echo $this->Form->input('licencia_estado', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][licencia][estado]', 'class' => 'licenciaEstado','value' => 'por_validar'));
                                       
                                }
                                ?>
                            
			</div>
			<div class="span3">
				<div id="info-licencia" class="info-licencia">
                                    
                                        <?php 
                                        if(count($licencia_archivo)>0){
                                            if($licencia_archivo['ArchivoPostulante']['nombre_fisico'] != '' && file_exists(WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$licencia_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$licencia_archivo['ArchivoPostulante']['extension'])){
                                                
                                                if($licencia_archivo['ArchivoPostulante']['valido'] == null){
                                                    echo "<div id='img-error-licencia' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo'))." No hay archivo</div>";
                                                    echo "<div id='img-check-licencia' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Correcto (será evaluado)</div>";
                                                }
                                                elseif($licencia_archivo['ArchivoPostulante']['valido'] == '0'){
                                                    echo "<div id='img-error-licencia' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo'))." Archivo Invalido, por favor cambiar</div>";
                                                    echo "<div id='img-check-licencia' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Correcto (será evaluado)</div>";
                                                }
                                                else{
                                                    echo "<div id='img-error-licencia' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo'))." No hay archivo</div>";
                                                    echo "<div id='img-check-licencia' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Validado</div>";
                                                }

                                            }
                                            else{
                                                echo "<div id='img-error-licencia' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo'))." No hay archivo</div>";
                                                echo "<div id='img-check-licencia' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Permitido</div>";

                                            }
                                        }
                                        else{
                                            echo "<div id='img-error-licencia' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo'))." No hay archivo</div>";
                                            echo "<div id='img-check-licencia' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Permitido</div>";
                                        }
                                        ?>
                                    
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span11">
				<br>
				<h4>Documentación / Antecedentes:</h4>
				<div class="alert alert-info">
					Para continuar el proceso, favor infórmate de los documentos que requieres para habilitarte en al menos una de estas vías de admisión. Posteriormente deberás adjuntar y clasificar los documentos según el tipo de admisión que corresponda (“Tipo”: IES, RAP y/o Convenio de Articulación). Puedes adjuntar documentos para más de una vía de admisión.	<br>
					<b>Es necesario que adjuntes TODOS los documentos señalados en la(s) vía(s) de admisión que escojas.</b>					
				</div>
			</div>
		</div>
		
		
		<div class="row">
                    <div class="span11">
			<div class="span4">				
				<div class="alert alert-warning">
					<!-- <strong>Académicos<br></strong> -->
					<strong>Otras Instituciones de Educación Superior (IES)<br></strong>
					<ul>
						<li>Programa de estudios</li>
						<li>Concentración de notas</li>
						<li>Malla Curricular<br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
					</ul>
				
				</div>
			</div>
			<div class="span4">				
				<div class="alert alert-warning">
					<!-- <strong>Laborales<br></strong> -->
					<strong>Reconocimiento de Aprendizajes Previos (RAP)<br></strong>
					<ul>
						<li>Acreditación de antigüedad laboral</li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
						<li><br></li>
					</ul>
				
				</div>
			</div>
			<div class="span4">				
				<div class="alert alert-warning">
					<strong>Convenio Articulación<br></strong>
					<ul>
						Válido sólo para los siguientes colegio y liceos:<br />
                            -   Colegio Cardenal Raúl Silva Henríquez<br />
                            -   Colegio Cardenal Carlos Oviedo Cavada<br />
                            -   Colegio Juan Luis Undurraga Aninat<br />
                            -   Colegio San Alberto Hurtado<br />
                            -   Colegio Cardenal Juan Francisco Fresno<br />
                            -   Liceo Polivalente Moderno Cardenal Caro<br />
                            -   Colegio Técnico Profesional Vicente Valdés<br />
                            -   Liceo Politécnico Andes<br />
						<br><br>
					</ul>				
				</div>
			</div>
                    </div>
		</div> <!-- cambio -->
		
		
		<div class="row">
			<div class="span3 subtitle">
				<h4>Nombre</h4>		
			</div>
			<div class="span3 subtitle">
				<h4>Archivo</h4>		
			</div>
			<div class="span3 subtitle">
				<h4>Tipo</h4>		
			</div>
			<div class="span2 subtitle">
				<h4>Estado</h4>		
			</div>
		</div>
		<div id="fila-archivo" class="row margen contenedor_anexo">
			<div class="span3">
				<?php echo $this->Form->input('anexo1', array('label' => false, 'required' => true, 'name' => 'data[Archivos][anexo][nombre]', 'class' => 'nombreAnexo', 'placeholder' => 'P.Ej Vida Laboral'));?>
			</div>
			<div class="span3" id="anexo">
				<?php echo $this->Form->input('anexo',array('type' => 'file', 'required' => true, 'name' => 'data[Archivos][anexo]', 'label' => false, 'class' => 'archivoAnexo', 'onchange' => 'anexo(this)')); ?>
				<?php echo $this->Form->input('anexo2', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][anexo][tipo_archivo]', 'class' => 'tipoArchivoAnexo','value' => 'anexo'));?>
				<?php echo $this->Form->input('anexo3', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][anexo][value_anexo]', 'class' => 'valueArchivoAnexo','value' => 1));?>
				
				<?php echo $this->Form->input('valor_anexo_none', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][anexo][valor_anexo_none]', 'class' => 'valor_anexo_none','value' => 1));?>
			</div>
			<div class="span3 radio-button" id="anexo">
			<?php echo $this->Form->input('radio', array(
					'type'        => 'radio',
					'before'      => '<label class="control-label"></label>',
					'name'        => 'data[Archivos][anexo][tipo]',
					'legend'      => false,
					'class'       => 'tipoArchivo_radio',
					'required'    => true,
					'value'       => 'Académico',
					'beforeInput' => '<div class="input-prepend">',
					'afterInput'  => '<div class="input-prepend">',
					'options' => array(
						'Academico' => 'IES',
						'Laboral'   => 'RAP',
						'Convenio'  => 'Convenio',
						
					)
				)); ?>
			
			</div>

			<div class="span2">
				<div id="info-licencia" class="info-licencia">
					<div id="img-convenio" align="left" style="display:none"><?php echo $this->Html->Image('test-pass-icon.png', array('title' => ' No se requiere archivo'));?> No se requiere archivo</div>
					<div id="img-error-licencia" class="" align="left"><?php echo $this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '));?> No hay archivo</div>
					<div id="img-check-licencia" class="hide" align="left"><?php echo $this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'));?> Archivo Permitido</div>
				</div>
			</div>
		</div>
		<div id="nuevosAnexos" class="clearfix">
			
		</div>
		<div class="row">
			<div class="span3 offset8">
				<a class="btn btn-danger pull-right" id="borrarAnexo" style="margin-left:5px; display:none"><i class="icon-trash-o"></i> Borrar último Archivo</a> 
				<a class="btn btn-warning pull-right" id="nuevoAnexo"><i class="icon-plus"></i> Nuevo Documento</a>				
			</div>
		</div>
		<div class="row">
			<div class="span8">
				<input type="hidden" name="data[Prepostulacion][postulante_codigo]" value="<?php echo $usuario['Postulante']['codigo']; ?>" />
				<input type="hidden" id="accion_prepostulacion" name="data[Prepostulacion][guardado]" value="1" />
				<input type="hidden" id="ultima_accion_prepostulacion" name="data[Prepostulacion][ultima_accion]" value="enviado" />
                                
				<?php echo $this->Form->button('<i class="icon-envelope"></i>Enviar',array('type'=> 'button','class'=>'btn btn-success letra','id' =>'enviarPopup','data-toggle' => 'tooltip','title'=>'Enviar postulación a coordinador','data-placement' => 'left'), array('escape' => false));?>
				<?php echo $this->Form->button('<i class="icon-save"></i>Guardar',array('type'=> 'button','class'=>'btn btn-primary letra','id'=>'guardarPopup','data-toggle' => 'tooltip','title' => 'Guardar postulación (no se envía a coordinador)','data-placement' => 'right'),array('escape' => false));?>
				
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>


<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<script type="text/javascript">


        $(document).ready(function(){
		
            $('#guardarPopup').tooltip(); 
			$('#enviarPopup').tooltip();
			
			if(navigator.userAgent.indexOf("MSIE")>0){
				$('#info-licencia').hide();
				$('.info-licencia').hide();
				$('#info-ci').hide();
				$('#info-renta').hide();
				$('#titulo-estado').hide();
			}else{
				
					$('#form-field-input-carga-licencia').on('change',licencia);
					$('#form-field-input-carga-ci').on('change',ci);

					$('#form-field-input-carga-renta').on('change',renta);

					/*
					$('.archivoAnexo').change(function(){
						anexo(this);
					});
					*/

			}
		
        });
		
		
        
        function anexo(obj){
			

			if(navigator.userAgent.indexOf("MSIE")>0){
                var file = $(obj).files; 			
			}else{
				var file = $(obj)[0].files[0]; 	
			}

            var ext            = true;
            var peso           = true;
            var size           = 0;
            var fileName       = file.name; 
			

			//var fileExtension  = fileName.split('.').pop();
            var fileExtension  = fileName.substring(fileName.lastIndexOf('.') + 1);
            var fileSize       = file.size;
            size               = fileSize/1024;
			
            if(!isImage(fileExtension)){
				alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
				$(obj).parent("div").parent("div").parent(".contenedor_anexo").find('#img-check-licencia').hide();
				$(obj).parent("div").parent("div").parent(".contenedor_anexo").find("#img-error-licencia").show();
					$(obj).val('');
					
				return false;
            }
            if(size>5120){
				alert('El archivo supera el peso establecido.');
				$(obj).parent("div").parent("div").parent(".contenedor_anexo").find('#img-check-licencia').hide();
				$(obj).parent("div").parent("div").parent(".contenedor_anexo").find("#img-error-licencia").show();
					$(obj).val('');
				return false;
            }
            if(peso && ext){
                
                $(obj).parent("div").parent("div").parent(".contenedor_anexo").find('#img-check-licencia').show();
				$(obj).parent("div").parent("div").parent(".contenedor_anexo").find("#img-error-licencia").hide();
            }
        
        }
	
	function licencia(){

			if(navigator.userAgent.indexOf("MSIE")>0){
                var file = $("#form-field-input-carga-licencia").files; 	
			}else{
				var file = $("#form-field-input-carga-licencia")[0].files[0]; 	
			}
			
            var ext            = true;
            var peso           = true;
            var size           = 0;
            var obj            = $("#form-field-input-carga-licencia");
            var fileName       = file.name;
            var fileExtension  = fileName.substring(fileName.lastIndexOf('.') + 1);
            var fileSize       = file.size;
            size               = fileSize/1024;

            if(!isImage(fileExtension)){
                    alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
                    $('#licencia').html('<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />');
                    $('#form-field-input-carga-licencia').on('change',licencia);
                    $('#img-check-licencia').hide();
                    $('#img-error-licencia').show();
                    return false;
            }
            if(size>5120){
                    alert('El archivo supera el peso establecido.');
                    $('#licencia').html('<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />');
                    $('#form-field-input-carga-licencia').on('change',licencia);
                    $('#img-check-licencia').hide();
                    $('#img-error-licencia').show();
                    return false;
            }
            if(peso && ext){
                    $(obj).parent("div").parent("div").parent("div").find('#img-check-licencia').show();
                    $(obj).parent("div").parent("div").parent("div").find('#img-error-licencia').hide();
            }
	}
	
	
	function ci(){
	
            	
            if(navigator.userAgent.indexOf("MSIE")>0){
                var file = $("#form-field-input-carga-ci").files; 	
			}else{
				var file = $("#form-field-input-carga-ci")[0].files[0]; 	
			}
		
            var obj      = $("#form-field-input-carga-ci");
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
            if(size>5120){
                    alert('El archivo supera el peso establecido.');
                    $('#ci').html('<input id="form-field-input-carga-ci" required name="data[Archivos][ci]" type="file" />');
                    $('#form-field-input-carga-ci').on('change',ci);
                    $('#img-check-ci').hide();
                    $('#img-error-ci').show();
                    return false;
            }
            if(peso && ext){
                    $(obj).parent("div").parent("div").parent("div").find('#img-check-ci').show();
                    $(obj).parent("div").parent("div").parent("div").find('#img-error-ci').hide();
            }
	}
        
        
	function renta(){
		if (navigator.userAgent.indexOf("MSIE")>0){
	     	var file = $("#form-field-input-carga-renta").files;
	    }else{
	    	var file = $("#form-field-input-carga-renta")[0].files[0]; 	
	    }
		
        var fileName      = file.name;
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        var fileSize      = file.size;
        var size          = fileSize/1024;
        var ext           = true;
        var peso          = true;
        
        if(!isImage(fileExtension)){
        	alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
        	$('#renta').html('<input id="form-field-input-carga-renta" required name="data[Archivos][renta]" type="file" />');
        	$('#form-field-input-carga-renta').on('change',renta);
        	$('#img-check-renta').hide();
        	$('#img-error-renta').show();
        	return false;
        }
        //if(size>30000){
        if(size>5120){
        	alert('El archivo supera el peso establecido (máximo 5MB por archivo).');
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
	        case 'jpg': case 'jpeg': case 'pdf': case 'doc': case 'docx': case 'png': case 'rar': case 'zip': 
	            return true;
	        break;
	        default:
	            return false;
	        break;
	    }

	}
</script>
<script>
	var anexos = 1;
	
	$('#nuevoAnexo').click(function(){			
            anexos++;	
			$('input[name=ticketID]').attr("disabled",true);			
            if(anexos > 1){ $('#borrarAnexo').show(); }
			//ESTABLECEMOS UN LÍMITE MÁXIMO DE ARCHIVOS A SUBIR EN UNA CARGA
			if(anexos > 19) {
				$('#nuevoAnexo').hide();
				$('#modalMaximo').modal('show'); ;				
			}
            var clone = $("#fila-archivo").clone(true);
            clone.find('.nombreAnexo').attr('id', 'PrepostulacionAnexo'+anexos);

            clone.find('.nombreAnexo').val('');
            clone.find('.nombreAnexo').attr('name', 'data[Archivos][anexo'+anexos+'][nombre]');
            clone.find('.archivoAnexo').attr('name', 'data[Archivos][anexo'+anexos+']');

            clone.find('.tipoArchivoAnexo').attr('name','data[Archivos][anexo'+anexos+'][tipo_archivo]');

            clone.find('.valueArchivoAnexo').attr('name','data[Archivos][anexo'+anexos+'][value_anexo]');
            clone.find('.valueArchivoAnexo').val(anexos);
            clone.find('.archivoAnexo').val('');

            clone.find('#PrepostulacionRadioLaboral').attr('id', 'PrepostulacionRadioLaboral'+anexos+'');
            clone.find('#PrepostulacionRadioAcademico').attr('id', 'PrepostulacionRadioAcademico'+anexos+'');
            clone.find('#PrepostulacionRadioConvenio').attr('id', 'PrepostulacionRadioConvenio'+anexos+'');
            clone.find("label[for='PrepostulacionRadioAcademico']").attr('for', 'PrepostulacionRadioAcademico'+anexos+'');
            clone.find("label[for='PrepostulacionRadioConvenio']").attr('for', 'PrepostulacionRadioConvenio'+anexos+'');
            clone.find("label[for='PrepostulacionRadioLaboral']").attr('for', 'PrepostulacionRadioLaboral'+anexos+'');
			
            clone.find(".tipoArchivo_radio").attr('name', 'data[Archivos][anexo'+anexos+'][tipo]');
            clone.find('.tipoArchivo_radio').prop('checked',false); //Lo desactivo
			
			clone.find(".valor_anexo_none").attr('name', 'data[Archivos][anexo'+anexos+'][valor_anexo_none]');

            clone.find("#img-check-licencia").hide();
            clone.find("#img-error-licencia").show();

            clone.show();
            clone.attr('id', 'fila-archivo'+anexos+'');
            clone.attr('id', 'fila-archivo'+anexos+'');		
            clone.appendTo("#nuevosAnexos");	
			$('.info-licencia').last().find('#img-error-licencia').hide();
			$('.info-licencia').last().find('#img-convenio').hide();
			
				
			
			
	});	

		$('#borrarAnexo').click(function() {		
			$( "#fila-archivo"+anexos+'').remove();	
			anexos--;
			if (anexos == 1) { 			
				$('#borrarAnexo').hide(); 
			}
			if ((anexos<19) && (anexos > 1)){
				$('#nuevoAnexo').show();
			}
		}); 
        
        function comprobarCamposRequired(){
           var correcto=true;
           var campos=$('input[type="text"]:required');
           var select=$('select:required');
           $(campos).each(function() {
              if($(this).val()==''){
                 correcto=false;
              }
           });
           $(select).each(function() {
              if($(this).val()==''){
                 correcto=false;
              }
           });
           return correcto;
        }
        
        
        $('#guardarPrepostulacion').on('click',function(e){
            if( comprobarCamposRequired() == true){
                $("#accion_prepostulacion").val('2');
                $("#ultima_accion_prepostulacion").val('guardado');
                $("#cierra_modal").attr("disabled", "disabled");
            }else{
                $(".cerrar").click();
            }
        });
        
        $('#enviarPrepostulacion').on('click',function(e){
            if( comprobarCamposRequired() == true){
                $("#accion_prepostulacion").val('1');
                $("#ultima_accion_prepostulacion").val('enviado');
                $("#cierra_modal").attr("disabled", "disabled");
            }else {
                $(".cerrar").click();
            }
        });
        
        
        
        $("#enviarPopup").click(function(){
            $("#titulo_modal").html('¿Está seguro que desea ENVIAR esta postulación?');
            $("#descrip_modal").html('Al enviar esta postulación no podrá modificar la documentación adjunta, ¿Desea confirmarlo?');
            $("#guardarPrepostulacion").hide();
            $("#enviarPrepostulacion").show();

            $("#cierra_modal").removeAttr("disabled");   
            $("#descrip_modal").show();
            $("#carga_postulacion").hide();
            $("#enviarPrepostulacion").click(function(){
                $("#titulo_modal").html('Se está procesando esta postulación');
                $("#descrip_modal").html('Espere un momento...');
                $("#carga_postulacion").show();
                $("#enviarPrepostulacion").hide();
                
            });
        
            $('#modalValidar').modal('show');
            return false;
        });
		
        $("#guardarPopup").click(function(){
            $("#titulo_modal").html('¿Está seguro que desea GUARDAR esta postulación?');
            $("#descrip_modal").html('Al guardar esta postulación puede editarla cuantas veces estime conveniente antes de enviarla, ¿Desea confirmarlo?');
            $("#enviarPrepostulacion").hide();
            $("#guardarPrepostulacion").show();

            $("#cierra_modal").removeAttr("disabled");   
            $("#descrip_modal").show();
            $("#carga_postulacion").hide();
            $("#guardarPrepostulacion").click(function(){
                $("#titulo_modal").html('Se está procesando esta postulación');
                $("#descrip_modal").html('Espere un momento...');
                $("#carga_postulacion").show();
                $("#guardarPrepostulacion").hide();
                
            });
        
            $('#modalValidar').modal('show');
            return false;
        });        

        $("#form-field-input-escuela").change(function(){
          
            if($(this).val() != ''){                
				$("#form-field-input-sedes").html("<option selected='selected'></option>");
                $("#form-field-input-carrera").hide();
                $("#cargador_carreras").show();
                
                $.ajax({
                    url      : "<?php echo $this->Html->url(array('controller'=>'prepostulaciones','action'=>'get_carreras')); ?>",
                    type     : "post",
                    async    : true,
                    dataType : "html",
                    data     : {'escuela_id' : $(this).val() },
                    success  : function(resp){
                        $("#cargador_carreras").hide();
                        $("#form-field-input-carrera").show(300,function(){
                            $("#form-field-input-carrera").html(resp);
                        });
                    }
                });                
            }          
        });
        
        $("#form-field-input-carrera").change(function(){          
            if($(this).val() != ''){                
                $("#form-field-input-sedes").hide();
                $("#cargador_sedes").show();                
                $.ajax({
                    url      : "<?php echo $this->Html->url(array('controller'=>'prepostulaciones','action'=>'get_sedes')); ?>",
                    type     : "post",
                    async    : true,
                    dataType : "html",
                    data     : {'carrera_id' : $(this).val() },
                    success  : function(resp){
                        //alert(resp);
                        $("#cargador_sedes").hide();
                        $("#form-field-input-sedes").show(300,function(){
							$("#form-field-input-sedes").html(resp);
                        });
                    }
                });                
            }          
        });
		
		$('.tipoArchivo_radio').change(function(){	
			var obj = $(this);
			
			if($(this).val() == 'Convenio'){				
				$(this).closest('.row').find('.nombreAnexo').css('visibility','hidden');				
				$(this).closest('.row').find('.nombreAnexo').prop('required',false);
				$(this).closest('.row').find('.archivoAnexo').prop('required',false);
				$(this).closest('.row').find('.archivoAnexo').css('visibility','hidden');
				$(this).closest('.row').find('#img-error-licencia').css('visibility','visible');
				$(this).closest('.row').find('#img-error-licencia').css('visibility','hidden');
				$(this).closest('.row').find('#img-check-licencia').css('visibility','hidden');
				$(this).closest('.row').find('#img-convenio').css('display','block');
				
				$(this).closest('.row').find('.nombreAnexo').prop('required',false);
				$(this).closest('.row').find('.archivoAnexo').prop('required',false);
				$(this).closest('.row').find('.archivoAnexo').val('');
				$(this).closest('.row').find('.nombreAnexo').val('');
			}
			else{	
				$(this).parent('div').parent('div').parent('div').find('.nombreAnexo').css('visibility','visible');
				$(this).parent('div').parent('div').parent('div').find('.archivoAnexo').css('visibility','visible'); 
				$(this).closest('.row').find('#img-error-licencia').css('visibility','visible');
				$(this).closest('.row').find('#img-error-licencia').css('display','');
			    $(this).closest('.row').find('#img-check-licencia').css('visibility','visible');
			    $(this).closest('.row').find('#img-check-licencia').css('display','none');
				$(this).closest('.row').find('#img-convenio').css('display','none');		
				if ($(this).closest('.row').find('.archivoAnexo').val() !== ''){
					$(this).closest('.row').find('#img-check-licencia').css('visibility','visible');
					$(this).closest('.row').find('#img-check-licencia').css('display','block');
					$(this).closest('.row').find('#img-error-licencia').css('display','none');
					$(this).closest('.row').find('.nombreAnexo').prop('required',true);
					$(this).closest('.row').find('.archivoAnexo').prop('required',true);
				}				
				$(this).closest('.row').find('.nombreAnexo').prop('required',true);
				$(this).closest('.row').find('.archivoAnexo').prop('required',true);
				
				if (anexos < 19) {
					$('#nuevoAnexo').show();
				}
			}
			
		});
		

	
</script>