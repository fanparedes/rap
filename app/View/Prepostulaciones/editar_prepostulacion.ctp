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
.contenedor_nuevo_anexo{
    height:0px;
    overflow:hidden;
    visibility:hidden;
}
.nombreAnexo{
    max-width:100%;
}
.radio-button{
    text-align:center;
}
.edit_file{
    max-width:20px;
    cursor:pointer;
}
.edit_file_cancel{
    max-width:20px;
    margin-left:12px;
    cursor:pointer;
}
.edit_file2{
    max-width:20px;
    cursor:pointer;
}
.edit_file_cancel2{
    max-width:20px;
    margin-left:12px;
    cursor:pointer;
}
#img-error-ci img{
    margin-right:3px;
}
.span_aviso{
    float:left;
    color:red;
    font-size:16px;
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
.warning-mensaje{
    background-color:#FF9797;
    color:white;
    font-size:14px;
}
#img_warning{
    max-width:27px;
    margin-right: 8px;
}
.contenedor_img_warning{
	float:left;
	width:auto;
	min-height:38px;
}
</style>

<?php 
$enabled_inputs  = '';
$enabled_botones = '';

foreach($prepostulacion as $prepos):
    
    if($prepos['Prepostulacion']['ultima_accion'] == 'enviado'){
        $enabled_inputs  = "disabled='disabled'";
        $enabled_botones = 'hide';
    }
    elseif($prepos['Prepostulacion']['ultima_accion'] == 'revisado'){ 
        $enabled_inputs = '';
    }
    else{ //guardado
        $enabled_inputs = '';
    }
    
endforeach;
?>


<?php                                
$array_radio_nuevo_anexo = '';
$array_radio_nuevo_anexo = array(
    'type'        => 'radio',
    'before'      => '<label class="control-label"></label>',
    'name'        => 'data[Archivos][anexo][tipo]',
    'legend'      => false,
    'class'       => 'tipoArchivo',
    'required'    => true,
    'value'       => 'Académico',
    'beforeInput' => '<div class="input-prepend">',
    'afterInput'  => '<div class="input-prepend">',
    'options'     => array(
            'Academico' => 'IES',
            'Laboral'   => 'RAP',
            'Convenio'  => 'Convenio',

    ),
    'value'       => 'Academico'
);
?>

<!-- Visibility Hidden -->
<div class="contenedor_nuevo_anexo">
    
    <div id="fila-archivo-nuevo-anexo" class="row margen contenedor_anexo">
            <div class="span2">
                <div class="input text">
                    <input type="text" name="data[Archivos][anexo1][nombre]" required="required" class="nombreAnexo" id="PrepostulacionAnexo1" placeholder="P.Ej Vida Laboral">
                </div>                            
            </div>
            <div class="span3" id="anexo">
                <div class="input file">
                    <input type="file" name="data[Archivos][anexo]" required="required" class="archivoAnexo" id="PrepostulacionAnexo">
                </div>
                <input type="hidden" name="data[Archivos][anexo][tipo_archivo]" class="tipoArchivoAnexo" value="anexo" id="PrepostulacionAnexo2">
                <input type="hidden" name="data[Archivos][anexo][estado]" class="anexoEstado" value="por_validar" id="PrepostulacionAnexoEstado">
                <input type="hidden" name="data[Archivos][anexo][anexo_id]" class="anexoId" value="nuevo" id="PrepostulacionAnexoId">
                <input type="hidden" name="data[Archivos][anexo][valor_anexo_value]" class="valorAnexoValue" value="1" id="PrepostulacionAnexoValue">
            </div>
            <div class="span3 radio-button" id="anexo">

                <?php echo $this->Form->input('radio',$array_radio_nuevo_anexo); ?>

            </div>

            <div class="span2">
                    <div id="info-licencia">
                        <div id="img-error-licencia" class="" align="left">
                            <img src="<?php echo $this->webroot;?>/img/test-fail-icon.png" title="No hay archivo" alt="">Archivo invalido, por favor cambiar
                        </div>
                        <div id="img-check-licencia" class="hide" align="left">
                            <img src="<?php echo $this->webroot;?>/img/test-pass-icon.png" title="Archivo Permitido" alt="">Archivo correcto (será evaluado)
                        </div>
						<div id="img-convenio" class="hide" align="left">
							<img src="<?php echo $this->webroot;?>/img/test-pass-icon.png" title="No se requiere archivo" alt=""> No se requiere archivo
						</div>                               
                    </div>
            </div>
            <div class="span1">

            </div>

    </div>
    
</div>
<!-- Visibility Hidden -->


<?php $usuario = $this->Session->read('UserLogued');?>

<?php echo $this->Form->create('Prepostulacion', array('type' => 'file','name' => 'formEditarPrePostulacion')); ?>

<!-- MODAL -->
<div id="modalValidar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align:center;" id="titulo_modal">¿Está seguro que desea enviar esta postulación?</h3>
	</div>
        <div class="modal-body">
                <!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
                <div class="row-fluid">				
                    <p id="descrip_modal">Al enviar esta postulación llegará inmediatamente al coordinador, ¿Desea confirmaro?</p>
                </div>
                <div id="carga_postulacion"  style="margin: 0px auto; width: 25%;">
                    <?php echo $this->Html->image('loader.gif'); ?>
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
			<h2>Editar Formulario de Postulación:</h2>
		</div>
		<div class="clearfix"></div>
		
                <input type='hidden' name='data[Prepostulacion][licencia]' value='1' id="form-field-input-experiencia"/>

                
                <?php 
                $warning_aviso = 'hide';

                if($prepostulacion[0]['Prepostulacion']['ultima_accion'] == 'enviado'){
                    $warning_aviso = 'hide';
                }
                elseif($prepostulacion[0]['Prepostulacion']['ultima_accion'] == 'revisado'){
                    $warning_aviso = '';
                }
                else{ //guardado
                    $warning_aviso = 'hide';
                }
                ?>
                <div class="row <?php echo $warning_aviso; ?>">
                    <div class="span11">
                        <div class="alert alert warning-mensaje">
							<div class="contenedor_img_warning">
								<img src="<?php echo $this->webroot.'images/warning.png'; ?>" id="img_warning"/>
							</div>
                            El coordinador ha invalidado esta postulación, puedes editar mas abajo y enviar la solicitud nuevamente. <br>
							<b> Motivo: <?php echo $prepostulacion[0]['Prepostulacion']['motivos']; ?> </b>
                        </div>
						
                    </div>
                </div>
                
                
                
		<div class="row">
		                <div class="span8">
		                    <label for="form-field-input-experiencia" class="control-label">¿En qué ciudad vives actualmente?</label>
		                </div>		                
                                <div class="span3">
		                   <select id="form-field-input-ciudad" name="data[Prepostulacion][ciudad_codigo]" class="pull-right" required <?php echo $enabled_inputs; ?>>
		                   	<option></option>
		                   	<?php 
                                        foreach($ciudades as $ciudad):
                                            
                                            $selected = "";
                                            foreach ($prepostulacion as $prepos):
                                            
                                            if($prepos['Prepostulacion']['ciudad_codigo'] == $ciudad['Ciudad']['codigo']){
                                                $selected = "selected";
                                                break;
                                            }
                                            else{
                                                $selected = "";
                                            }
                                            endforeach;
                                        ?>
		                   		<option value="<?php echo $ciudad['Ciudad']['codigo']; ?>" <?php echo $selected; ?>><?php echo $ciudad['Ciudad']['nombre'];?></option>
		                   	<?php 
                                             
                                        endforeach; 
                                        ?>
		                   </select>
		                </div>	
		</div>
                
                
                
                <!-- Escuelas -->
                <div class="row">
                        <div class="span8">
                            <label for="form-field-input-escuelas" class="control-label">Escuela a postular</label>
                        </div>		                
                        <div class="span3">
                           <select id="form-field-input-escuela" required  name="data[Prepostulacion][escuela_id]" class="pull-right" <?php echo $enabled_inputs; ?>>
                                <option></option>
                                <?php 
                                foreach($escuelas as  $escuela): 
                                    
                                    $selected = "";
                                    if($prepostulacion[0]['Prepostulacion']['escuela_id'] == $escuela['Escuela']['id']){
                                        $selected = "selected";
                                    }
                                    else{
                                        $selected = "";
                                    }
                                ?>
                                    <option value="<?php echo $escuela['Escuela']['id']; ?>" <?php echo $selected; ?>><?php echo ($escuela['Escuela']['nombre']); ?></option>
                                <?php 
                                endforeach; 
                                ?>
                            </select>
                        </div>	
		</div>
                <!-- Carreras -->
		<div class="row">
                        <div class="span8">
                            <label for="form-field-input-experiencia" class="control-label">Carrera a postular</label>
                        </div>		                
                        <div class="span3">
                            <select id="form-field-input-carrera"required  name="data[Prepostulacion][carrera_id]" class="pull-right" <?php echo $enabled_inputs; ?>>
                                <option></option>
                                <?php 
                                foreach($carreras as  $carrera): 

                                    $selected = "";
                                    if($prepostulacion[0]['Prepostulacion']['carrera_id']  == $carrera['Carrera']['codigo']){
                                        $selected = "selected";
                                    }
                                    else{
                                        $selected = "";
                                    }
                                ?>
                                    <option value="<?php echo $carrera['Carrera']['codigo']; ?>" <?php echo $selected; ?>><?php echo ($carrera['Carrera']['nombre']); ?></option>
                                <?php 
                                endforeach; 
                                ?>
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
                            <select id="form-field-input-sedes" required  name="data[Prepostulacion][sede_id]" class="pull-right" <?php echo $enabled_inputs; ?>>
                                <option></option>
                                <?php 
                                foreach($sedes as  $sede): 

                                    $selected = "";
                                    if($prepostulacion[0]['Prepostulacion']['sede_id']  == $sede['Sede']['codigo_sede']){
                                        $selected = "selected";
                                    }
                                    else{
                                        $selected = "";
                                    }
                                ?>
                                    <option value="<?php echo $sede['Sede']['codigo_sede']; ?>" <?php echo $selected; ?>><?php echo ($sede['Sede']['nombre_sede']); ?></option>
                                <?php 
                                endforeach; 
                                ?>
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
			<div class="span4 subtitle">
				<h4>Archivo</h4>		
			</div>
			<div class="span3 subtitle">
				<h4>Estado</h4>		
			</div>
                        <div class="span1 subtitle">
				<h4>Edición</h4>		
			</div>
		</div>
		<div class="row margen">
			<div class="span3">
					Cédula de Identidad (por ambos lados)
			</div>
			<div class="span4" id="ci">
                                <?php
                                foreach($archivos_postulante as $archPost):
                                    if($archPost['ArchivoPostulante']['tipo'] == 'CEDULA'){
                                        
                                        if($archPost['ArchivoPostulante']['valido'] == null){
                                            
                                            echo "<label class='nombreArchivo_lbl'>".$this->html->link($archPost['ArchivoPostulante']['nombre'], 	                		
                                                array('controller'=>'cargas', 'action' => 'descargarArchivo', $archPost['ArchivoPostulante']['codigo']),
                                                array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                            )."</label>";   
                                            echo $this->Form->input('cedula_identidad',array('type' => 'file', 'id' => 'generic_file', 'class' => 'hide generic_file', 'name' => 'data[Archivos][ci]', 'label' => false));
                                            
                                            echo $this->Form->input('ci_estado',array('id' => 'estado', 'value' => 'ok', 'name' => 'data[Archivos][ci][estado]', 'type' => 'hidden'));
                                            echo $this->Form->input('ci_id',array('id' => 'form-field-input-carga-ci', 'value' => $archPost['ArchivoPostulante']['id'] , 'name' => 'data[Archivos][ci][id]', 'type' => 'hidden'));
                                            echo $this->Form->input('cedula_tipoarchivo', array('type' => 'hidden', 'name' => 'data[Archivos][ci][tipo_archivo]', 'class' => 'tipoArchivoCI','value' => 'ci'));
                                            break;
                                        }
                                        else if($archPost['ArchivoPostulante']['valido'] == '0'){
                                            
                                            echo $this->Form->input('cedula_identidad',array('type' => 'file' , 'id' => 'generic_file', 'class' => 'generic_file' ,'name' => 'data[Archivos][ci]', 'label' => false, 'required' => true));
                                           
                                            echo $this->Form->input('cedula_estado',array('type' => 'hidden','id' => 'estado', 'value' => 'por_validar', 'name' => 'data[Archivos][ci][estado]'));
                                            echo $this->Form->input('cedula_id',array('type' => 'hidden','id' => 'form-field-input-carga-ci', 'value' => $archPost['ArchivoPostulante']['id'] , 'name' => 'data[Archivos][ci][id]'));
                                            echo $this->Form->input('cedula_tipoarchivo', array('type' => 'hidden', 'name' => 'data[Archivos][ci][tipo_archivo]', 'class' => 'tipoArchivoCI','value' => 'ci'));
                                            break;
                                        }
                                        else{
                                            
                                            echo "<label class='nombreArchivo_lbl'>".$this->html->link($archPost['ArchivoPostulante']['nombre'], 	                		
                                                array('controller'=>'cargas', 'action' => 'descargarArchivo', $archPost['ArchivoPostulante']['codigo']),
                                                array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                            )."</label>"; 
                                            echo $this->Form->input('cedula_identidad',array('type' => 'file', 'id' => 'generic_file', 'class' => 'hide generic_file', 'name' => 'data[Archivos][ci]', 'label' => false));
                                            
                                            echo $this->Form->input('ci_estado',array('id' => 'estado', 'value' => 'ok', 'name' => 'data[Archivos][ci][estado]', 'type' => 'hidden'));
                                            echo $this->Form->input('ci_id',array('id' => 'form-field-input-carga-ci', 'value' => $archPost['ArchivoPostulante']['id'] , 'name' => 'data[Archivos][ci][id]', 'type' => 'hidden'));
                                            echo $this->Form->input('cedula_tipoarchivo', array('type' => 'hidden', 'name' => 'data[Archivos][ci][tipo_archivo]', 'class' => 'tipoArchivoCI','value' => 'ci'));
                                            break;
                                        }
                                    }
                                endforeach;
                                ?>
			</div>
			<div class="span3">
                                <div id="info-ci">
                                    
                                    <?php 
                                    foreach($archivos_postulante as $archPost):
                                    
                                        if($archPost['ArchivoPostulante']['tipo'] == 'CEDULA'){

                                            if($archPost['ArchivoPostulante']['valido'] == null){
                                                echo "<div id='img-error-ci' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))."Archivo invalido, por favor cambiar</div>";
												echo "<div id='img-check-ci' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo correcto (será evaluado)</div>";
                                                break;
                                            }
                                            else if($archPost['ArchivoPostulante']['valido'] == '0'){
                                                echo "<div id='img-error-ci' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))."Archivo invalido, por favor cambiar</div>";
                                                echo "<div id='img-check-ci' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo correcto (será evaluado)</div>";
                                                break;
                                            }
                                            else{
                                                echo "<div id='img-check-ci' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo validado</div>";
                                                echo "<div id='img-error-ci' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))."Archivo invalido, por favor cambiar</div>";
                                                break;
                                            }
                                        }
                                    endforeach;
                                    ?>
                                    
                                </div>
			</div>
                        <div class="span1">
                                <?php 
                                foreach($archivos_postulante as $archPost):
                                    if($archPost['ArchivoPostulante']['tipo'] == 'CEDULA'){

                                        if($archPost['ArchivoPostulante']['valido'] == null){
                                            //aca
                                            $imagen  = $this->webroot.'images/edit.png';
                                            $imagen2 = $this->webroot.'images/edit_cancel.png';

                                            echo "<img class='edit_file2 ".$enabled_botones."' src='".$imagen."' title='Editar' />";
                                            echo "<img class='edit_file_cancel2 hide' src='".$imagen2."' title='Cancelar Edición' />";
                                        }
                                        else if($archPost['ArchivoPostulante']['valido'] == '0'){


                                        }
                                        else{ 
                                            //guardado
                                            $imagen  = $this->webroot.'images/edit.png';
                                            $imagen2 = $this->webroot.'images/edit_cancel.png';

                                            //echo "<img class='edit_file2 ".$enabled_botones."' src='".$imagen."' title='Editar' />";
                                            //echo "<img class='edit_file_cancel2 hide' src='".$imagen2."' title='Cancelar Edición' />";

                                        }
                                    }
                                endforeach;
                                ?>
                        </div>
		</div>
		<div class="row">
			<div class="span3">
				Licencia de Enseñanza Media
			</div>
			<div class="span4" id="licencia">
                            
                                <?php
                                foreach($archivos_postulante as $archPost):
                                    if($archPost['ArchivoPostulante']['tipo'] == 'LICENCIA'){
                                        
                                        if($archPost['ArchivoPostulante']['valido'] == null){
                                            
                                            echo "<label class='nombreArchivo_lbl'>".$this->html->link($archPost['ArchivoPostulante']['nombre'], 	                		
                                                array('controller'=>'cargas', 'action' => 'descargarArchivo', $archPost['ArchivoPostulante']['codigo']),
                                                array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                            )."</label>";
                                            echo $this->Form->input('licencia',array('type' => 'file','id' => 'generic_file', 'class'=>'hide generic_file', 'name' => 'data[Archivos][licencia]', 'label' => false));
                                            
                                            echo $this->Form->input('licencia_estado',array('id' => 'estado', 'value' => 'ok', 'name' => 'data[Archivos][licencia][estado]', 'type' => 'hidden'));
                                            echo $this->Form->input('licencia_id',array('id' => 'form-field-input-carga-ci', 'value' => $archPost['ArchivoPostulante']['id'] , 'name' => 'data[Archivos][licencia][id]', 'type' => 'hidden'));
                                            echo $this->Form->input('licencia_tipoarchivo', array('type' => 'hidden', 'name' => 'data[Archivos][licencia][tipo_archivo]', 'class' => 'tipoArchivoLicencia','value' => 'licencia'));
                                        }
                                        else if($archPost['ArchivoPostulante']['valido'] == '0'){
                                            echo $this->Form->input('licencia',array('type' => 'file','id' => 'generic_file','class' => 'generic_file','name' => 'data[Archivos][licencia]', 'label' => false, 'required' => true));
                                            echo $this->Form->input('licencia_estado',array('id' => 'estado', 'value' => 'por_validar', 'name' => 'data[Archivos][licencia][estado]', 'type' => 'hidden'));
                                            echo $this->Form->input('licencia_id',array('id' => 'form-field-input-carga-ci', 'value' => $archPost['ArchivoPostulante']['id'] , 'name' => 'data[Archivos][licencia][id]', 'type' => 'hidden'));
                                            echo $this->Form->input('licencia_tipoarchivo', array('type' => 'hidden', 'name' => 'data[Archivos][licencia][tipo_archivo]', 'class' => 'tipoArchivoLicencia','value' => 'licencia'));
                                        }
                                        else{
                                            
                                            echo "<label class='nombreArchivo_lbl'>".$this->html->link($archPost['ArchivoPostulante']['nombre'], 	                		
                                                array('controller'=>'cargas', 'action' => 'descargarArchivo', $archPost['ArchivoPostulante']['codigo']),
                                                array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                            )."</label>";
                                            echo $this->Form->input('licencia',array('type' => 'file','class'=>'hide generic_file' , 'id' => 'generic_file','name' => 'data[Archivos][licencia]', 'label' => false));
                                            
                                            echo $this->Form->input('licencia_estado',array('id' => 'estado', 'value' => 'ok', 'name' => 'data[Archivos][licencia][estado]', 'type' => 'hidden'));
                                            echo $this->Form->input('licencia_id',array('id' => 'form-field-input-carga-ci', 'value' => $archPost['ArchivoPostulante']['id'] , 'name' => 'data[Archivos][licencia][id]', 'type' => 'hidden'));
                                            echo $this->Form->input('licencia_tipoarchivo', array('type' => 'hidden', 'name' => 'data[Archivos][licencia][tipo_archivo]', 'class' => 'tipoArchivoLicencia','value' => 'licencia'));
                                        }
                                    }
                                endforeach;
                                ?>

			</div>
			<div class="span3">
				<div id="info-licencia">
                                    
                                        <?php 
                                        foreach($archivos_postulante as $archPost):

                                            if($archPost['ArchivoPostulante']['tipo'] == 'LICENCIA'){
                                                if($archPost['ArchivoPostulante']['valido'] == null){
                                                    echo "<div id='img-error-ci' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))."Archivo invalido, por favor cambiar</div>";
                                                    echo "<div id='img-check-ci' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo correcto (será evaluado)</div>";
                                                }
                                                else if($archPost['ArchivoPostulante']['valido'] == '0'){
                                                    echo "<div id='img-error-ci' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))."Archivo invalido, por favor cambiar</div>";
                                                    echo "<div id='img-check-ci' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo correcto (será evaluado)</div>";
                                                }
                                                else{
                                                    echo "<div id='img-error-ci' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))."Archivo invalido, por favor cambiar</div>";
                                                    echo "<div id='img-check-ci' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo validado</div>";
                                                }
                                            }
                                        endforeach;
                                        ?>
                                    
				</div>
			</div>
                        <div class="span1">
                                <?php 
                                foreach($archivos_postulante as $archPost):

                                    if($archPost['ArchivoPostulante']['tipo'] == 'LICENCIA'){
                                        
                                        if($archPost['ArchivoPostulante']['valido'] == null){
                                            //aca
                                            $imagen  = $this->webroot.'images/edit.png';
                                            $imagen2 = $this->webroot.'images/edit_cancel.png';

                                            echo "<img class='edit_file2 ".$enabled_botones."' src='".$imagen."' title='Editar' />";
                                            echo "<img class='edit_file_cancel2 hide' src='".$imagen2."' title='Cancelar Edición' />";
                                        }
                                        else if($archPost['ArchivoPostulante']['valido'] == '0'){


                                        }
                                        else{
                                            //Guardado
                                            $imagen  = $this->webroot.'images/edit.png';
                                            $imagen2 = $this->webroot.'images/edit_cancel.png';

                                            //echo "<img class='edit_file2 ".$enabled_botones."' src='".$imagen."' title='Editar' />";
                                            //echo "<img class='edit_file_cancel2 hide' src='".$imagen2."' title='Cancelar Edición' />";

                                        }
                                        
                                    }
                                endforeach;
                                ?>
                        </div>
		</div>
		<div class="row">
			<div class="span11">
				<br>
				<h4>Documentación / Antecedentes:</h4>
				<div class="alert alert-info">
				   Para continuar el proceso, favor infórmate de los documentos que requieres para habilitarte en al menos una de estas vías de admisión. Posteriormente deberás adjuntar y clasificar los documentos según el tipo de admisión que corresponda (“Tipo”: IES, RAP y/o Convenio de Articulación). Puedes adjuntar documentos para más de una vía de admisión. <br>
				   <b> Es necesario que adjuntes TODOS los documentos señalados en la(s) vía(s) de admisión que escojas. </b>
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
						<li>Malla Curricular<br> </li>
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
		</div>
		<div class="row">
			<div class="span2 subtitle">
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
                        <div class="span1 subtitle">
				<h4>Edición</h4>		
			</div>
		</div>
                
                <?php 
                
                $count = 1;
                if(count($archivos_anexos)>0){
				foreach($archivos_anexos as $archAnexos): 
                ?>
                    <div id="fila-archivo" class="row margen contenedor_anexo">
                            <div class="span2">
                                
                                    <?php									
                                    if($archAnexos['ArchivoPrepostulacion']['valido'] == null){
                                        //echo $this->Form->input('anexo1', array('label' => false, 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][nombre]', 'class' => 'nombreAnexo', 'value' => $archAnexos['ArchivoPrepostulacion']['nombre']));
										$style = '';
										if (($archAnexos['ArchivoPrepostulacion']['tipo']) == ('Convenio')){$style = 'display:none;';}
                                        echo $this->Form->input('anexo1', array('label' => false, 'disabled' => 'disabled', 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][nombre]', 'class' => 'nombreAnexo', 'value' => $archAnexos['ArchivoPrepostulacion']['nombre'], 'style' => $style ));
                                    }
                                    else if($archAnexos['ArchivoPrepostulacion']['valido'] == '0'){
                                        echo $this->Form->input('anexo1', array('label' => false, 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][nombre]', 'class' => 'nombreAnexo', 'value' => $archAnexos['ArchivoPrepostulacion']['nombre']));
                                    }
                                    else{
										if ($archAnexos['ArchivoPrepostulacion']['tipo'] == 'Convenio'){
											 echo $this->Form->input('anexo1', array('label' => false, 'disabled' => 'disabled', 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][nombre]', 'class' => 'nombreAnexo', 'value' => $archAnexos['ArchivoPrepostulacion']['nombre'], 'style' => 'display:none'));
										}
										else{
											echo $this->Form->input('anexo1', array('label' => false, 'disabled' => 'disabled', 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][nombre]', 'class' => 'nombreAnexo', 'value' => $archAnexos['ArchivoPrepostulacion']['nombre']));
										}
                                    }
                                    ?>
                            </div>
                            <div class="span3" id="anexo">
                                    <?php
                                    if($archAnexos['ArchivoPrepostulacion']['valido'] == null){
                                        /*
                                        echo $this->Form->input('anexo',array('type' => 'file', 'required' => true, 'name' => 'data[Archivos][anexo'.$count.']', 'label' => false, 'class' => 'archivoAnexo')); 
                                        echo $this->Form->input('anexo2', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][tipo_archivo]', 'class' => 'tipoArchivoAnexo','value' => 'anexo'));
                                        echo $this->Form->input('anexo_estado', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][estado]', 'class' => 'tipoArchivoAnexo','value' => 'por_validar'));
                                        echo $this->Form->input('anexo_id', array('type' => 'hidden', 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][anexo_id]', 'class' => 'anexoId','value' => $archAnexos['ArchivoPrepostulacion']['id']));
                                        */
                                        
                                        echo "<label class='nombreAnexo_lbl'>".$this->html->link($archAnexos['ArchivoPrepostulacion']['nombre_fisico'], 	                		
                                            array('controller'=>'cargas', 'action' => 'descargar_anexo', $archAnexos['ArchivoPrepostulacion']['codigo']),
                                            array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                        )."</label>";
                                        echo $this->Form->input('anexo',array('type' => 'file', 'name' => 'data[Archivos][anexo'.$count.']', 'label' => false, 'class' => 'archivoAnexo hide','value' => $archAnexos['ArchivoPrepostulacion']['nombre_fisico'])); 
                                        
                                        echo $this->Form->input('anexo', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][tipo_archivo]', 'class' => 'tipoArchivoAnexo','value' => 'anexo'));
                                        echo $this->Form->input('anexo_estado', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][estado]', 'class' => 'anexoEstado','value' => 'ok'));
                                        echo $this->Form->input('anexo_id', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][anexo_id]', 'class' => 'anexoId','value' => $archAnexos['ArchivoPrepostulacion']['id']));
                                    
                                        echo $this->Form->input('anexo_value', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][valor_anexo_value]', 'class' => 'valorAnexoValue','value' => $count));
                                    }
                                         
                                    else if($archAnexos['ArchivoPrepostulacion']['valido'] == '0'){
                                        
                                        echo $this->Form->input('anexo2', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][tipo_archivo]', 'class' => 'tipoArchivoAnexo','value' => 'anexo'));
                                        echo $this->Form->input('anexo_estado', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][estado]', 'class' => 'anexoEstado','value' => 'por_validar'));
                                        echo $this->Form->input('anexo_id', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][anexo_id]', 'class' => 'anexoId','value' => $archAnexos['ArchivoPrepostulacion']['id']));                                        
                                        echo $this->Form->input('anexo_value', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][valor_anexo_value]', 'class' => 'valorAnexoValue','value' => $count));
										echo $this->Form->input('anexo',array('type' => 'file', 'required' => true, 'name' => 'data[Archivos][anexo'.$count.'][archivo]', 'label' => false, 'class' => 'archivoAnexo')); 
                                    }
                                    else{
                                        echo "<label class='nombreAnexo_lbl'>".$this->html->link($archAnexos['ArchivoPrepostulacion']['nombre_fisico'], 	                		
                                            array('controller'=>'cargas', 'action' => 'descargar_anexo', $archAnexos['ArchivoPrepostulacion']['codigo']),
                                            array('class' => 'tool',  "data-toggle" => "tooltip", 'title' => 'Descargar Documento', 'data-placement' => "right")
                                        )."</label>";
                                        echo $this->Form->input('anexo',array('type' => 'file', 'name' => 'data[Archivos][anexo'.$count.']', 'label' => false, 'class' => 'archivoAnexo hide','value' => $archAnexos['ArchivoPrepostulacion']['nombre_fisico'])); 
                                        
                                        echo $this->Form->input('anexo', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][tipo_archivo]', 'class' => 'tipoArchivoAnexo','value' => 'anexo'));
                                        echo $this->Form->input('anexo_estado', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][estado]', 'class' => 'anexoEstado','value' => 'ok'));
                                        echo $this->Form->input('anexo_id', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][anexo_id]', 'class' => 'anexoId','value' => $archAnexos['ArchivoPrepostulacion']['id']));
                                        
                                        echo $this->Form->input('anexo_value', array('type' => 'hidden', 'name' => 'data[Archivos][anexo'.$count.'][valor_anexo_value]', 'class' => 'valorAnexoValue','value' => $count));
                                    }
                                    ?>
                            </div>
                            <div class="span3 radio-button" id="anexo">
                                
                                <?php 
                                
                                $array_radio = array(
                                        'type'        => 'radio',
                                        'before'      => '<label class="control-label"></label>',
                                        'name'        => 'data[Archivos][anexo'.$count.'][tipo]',
                                        'legend'      => false,
                                        'class'       => 'tipoArchivo',
                                        'required'    => true,
                                        'anexo'       => $count,
                                        'value'       => 'Académico',
                                        'beforeInput' => '<div class="input-prepend">',
                                        'afterInput'  => '<div class="input-prepend">',
                                        'options'     => array(
                                                'Academico' => 'IES',
                                                'Laboral'   => 'RAP',
                                                'Convenio'  => 'Convenio',

                                        )
                                );
                                        
                                //_______________________________________________________________
                                $value_radio = '';
                                if($archAnexos['ArchivoPrepostulacion']['tipo'] == 'Academico'){
                                    //$value_radio = 'Academico';
                                    $array_radio['value'] = 'Academico';
                                }
                                elseif($archAnexos['ArchivoPrepostulacion']['tipo'] == 'Laboral'){
                                    //$value_radio = 'Laboral';
                                    $array_radio['value'] = 'Laboral';
                                }
                                else{
                                    //$value_radio = 'Convenio';
                                    $array_radio['value'] = 'Convenio';
                                }
                                //_______________________________________________________________
                                
                                $disabled_radio = '';
                                if($archAnexos['ArchivoPrepostulacion']['valido'] == null){
                                    //nada...
                                    $array_radio['disabled'] = 'disabled';
                                }
                                else if($archAnexos['ArchivoPrepostulacion']['valido'] == '0'){
                                    
                                }
                                else{
                                    $array_radio['disabled'] = 'disabled';
                                }
                                
                                echo $this->Form->input('radio',$array_radio); 
                                ?>

                            </div>

                            <div class="span2">
                                    <div id="info-licencia">
                                            <?php
                                            if(($archAnexos['ArchivoPrepostulacion']['tipo'] == 'Convenio')){                                               
                                                echo "<div id='img-error-licencia' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo'))." Archivo invalido, por favor cambiar</div>";
												echo "<div id='img-convenio' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' No hay archivo'))." No se requiere el archivo</div>";
											} 
											else{											
												if(($archAnexos['ArchivoPrepostulacion']['valido'] == null)){
													echo "<div id='img-check-licencia' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo válido(pendiente de evaluar)</div>";
													echo "<div id='img-error-licencia' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo'))." Archivo invalido, por favor cambiar</div>";
													echo "<div id='img-convenio' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' No hay archivo'))." No se requiere el archivo</div>";
												} 
												if(($archAnexos['ArchivoPrepostulacion']['valido'] == '0')){
													echo "<div id='img-check-licencia' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo válido(pendiente de evaluar)</div>";
													echo "<div id='img-error-licencia' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo'))." Archivo invalido, por favor cambiar</div>";
													echo "<div id='img-convenio' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' No hay archivo'))." No se requiere el archivo</div>";
												} 
												if(($archAnexos['ArchivoPrepostulacion']['valido'] == '1')){
													echo "<div id='img-check-licencia' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Validado'))." Archivo validado</div>";                                                
												}
											}								
                                            ?>
                                    </div>
                            </div>
                            <div class="span1">
                                <?php 
                                if($archAnexos['ArchivoPrepostulacion']['valido'] == null){
                                    //aca
                                    $imagen  = $this->webroot.'images/edit.png';
                                    $imagen2 = $this->webroot.'images/edit_cancel.png';
                                    
                                    echo "<img class='edit_file ".$enabled_botones."' src='".$imagen."' title='Editar' />";
                                    echo "<img class='edit_file_cancel hide' src='".$imagen2."' title='Cancelar Edición' />";
                                }
                                else if($archAnexos['ArchivoPrepostulacion']['valido'] == '0'){
                                    
                                }
                                else{
                                    //Guardado
                                    $imagen  = $this->webroot.'images/edit.png';
                                    $imagen2 = $this->webroot.'images/edit_cancel.png';
                                    
                                    //echo "<img class='edit_file ".$enabled_botones."' src='".$imagen."' title='Editar' />";
                                    //echo "<img class='edit_file_cancel hide' src='".$imagen2."' title='Cancelar Edición' />";
                                }
                                ?>
                            </div>
                    </div>
                    <?php 
                    $count++;
                    endforeach;
					}
					else{
						echo "<span style='font-weight:bold;'>Postulación de tipo convenio</span>";
					}					
                    ?>                
                    <div id="nuevosAnexos" class="clearfix">

                    </div>
                    <div class="row">
                            <div class="span3 offset8">
                                    <a class="btn btn-danger pull-right" id="borrarAnexo" style="margin-left:5px; display:none"><i class="icon-trash-o"></i> Borrar último Anexo</a> 
                                    <a class="btn btn-warning pull-right <?php echo $enabled_botones; ?>" id="nuevoAnexo"><i class="icon-plus"></i> Nuevo Documento</a>			
                            </div>
                    </div>
                    <div class="row">
                        <div class="span8">
                            <input type="hidden" id="postulante_codigo" name="data[Prepostulacion][postulante_codigo]" value="<?php echo $usuario['Postulante']['codigo']; ?>" />
                            <input type="hidden" id="accion_prepostulacion" name="data[Prepostulacion][guardado]" value="1" />
                            <input type="hidden" id="ultima_accion_prepostulacion" name="data[Prepostulacion][ultima_accion]" value="enviado" />

                            <?php 
                            $span_aviso = 'hide';
                            
                            foreach($prepostulacion as $prepos):
                                if($prepos['Prepostulacion']['ultima_accion'] == 'enviado'){ 
                                    $span_aviso = '';
                                ?>
                                    
                                <?php 
                                }
                                elseif($prepos['Prepostulacion']['ultima_accion'] == 'revisado'){ 
                                    $span_aviso = 'hide';
                                ?>
                                    <?php //echo $this->Form->button('<i class="icon-envelope"></i> Enviar', array('type'=> 'submit', 'class' => 'btn btn-success letra','id'=>'enviarPrepostulacion'),array('escape' => false));?>
                                    <?php //echo $this->Form->button('<i class="icon-save"></i> Guardar', array('type'=> 'submit', 'class' => 'btn btn-primary letra','id' => 'guardarPrepostulacion'),array('escape' => false));?>
                            
                                    <?php echo $this->Form->button('<i class="icon-envelope"></i>Enviar',array('type'=> 'button','class'=>'btn btn-success letra','id' =>'enviarPopup','data-toggle' => 'tooltip','title'=>'Enviar postulación a coordinador','data-placement' => 'left'), array('escape' => false));?>
                                    <?php echo $this->Form->button('<i class="icon-save"></i>Guardar',array('type'=> 'button','class'=>'btn btn-primary letra','id'=>'guardarPopup','data-toggle' => 'tooltip','title' => 'Guardar postulación (no se envía a coordinador)','data-placement' => 'right'),array('escape' => false));?>
                                
                                <?php
                                }
                                else{ //guardado
                                    $span_aviso = 'hide';
                                ?>
                                    <?php //echo $this->Form->button('<i class="icon-envelope"></i> Enviar', array('type'=> 'submit', 'class' => 'btn btn-success letra','id'=>'enviarPrepostulacion'),array('escape' => false));?>
                                    <?php //echo $this->Form->button('<i class="icon-save"></i> Guardar', array('type'=> 'submit', 'class' => 'btn btn-primary letra','id' => 'guardarPrepostulacion'),array('escape' => false));?>
                            
                                    <?php echo $this->Form->button('<i class="icon-envelope"></i>Enviar',array('type'=> 'button','class'=>'btn btn-success letra','id' =>'enviarPopup','data-toggle' => 'tooltip','title'=>'Enviar postulación a coordinador','data-placement' => 'left'), array('escape' => false));?>
                                    <?php echo $this->Form->button('<i class="icon-save"></i>Guardar',array('type'=> 'button','class'=>'btn btn-primary letra','id'=>'guardarPopup','data-toggle' => 'tooltip','title' => 'Guardar postulación (no se envía a coordinador)','data-placement' => 'right'),array('escape' => false));?>
                            
                                <?php
                                }
                            endforeach;
                            ?>   
                        </div>
                    </div>
                
                <div class="row">
                    <span class="span_aviso <?php echo $span_aviso; ?>">*Esta solicitud será revisada por el coordinador, por favor espere su respuesta</span>
                </div>
	</div>
</div>
<?php echo $this->Form->end(); ?>

<script type="text/javascript">
    
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
			radioConvenio();
        });
		
			//Esta función ocultará los campos de nombre y archivo si cuando carga es un convenio deshabilitado
		function radioConvenio(){
			$('input:radio:checked:not(:disabled)').each(function() {
			  var valor = $(this).val();
			  if (valor == 'Convenio'){
				$(this).closest('.row').find('.nombreAnexo').val('');
				$(this).closest('.row').find('.nombreAnexo').hide();
				$(this).closest('.row').find('.nombreAnexo').attr('required', false);
				$(this).closest('.row').find('.archivoAnexo').val('');
				$(this).closest('.row').find('.archivoAnexo').hide();
				$(this).closest('.row').find('.archivoAnexo').attr('required', false);	
			  }
			  
			});
			
			
		}
		
		
	 function anexo2(obj){             
            var ext            = true;
            var peso           = true;
            var size           = 0;
            var file           = $(obj)[0].files[0];
            var fileName       = file.name;
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
        	alert('El archivo supera el peso establecido (máximo permitido 5MB).');
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
		

	$(function(){
		
            if (navigator.userAgent.indexOf("MSIE")>0 ) {
                $('#info-licencia').hide();
                $('#info-ci').hide();
                $('#info-renta').hide();
                $('#titulo-estado').hide();
            }else{
                //$('#form-field-input-carga-licencia').on('change',licencia);
                //$('#form-field-input-carga-ci').on('change',ci);
                
                $(".generic_file").change(function(){
                    validar_generic(this);
                });
                
                
                $('#form-field-input-carga-renta').on('change',renta);

                $('.archivoAnexo').change(function(){					
                    anexo2(this);
                });
            }
	});
        
        function validar_generic(obj){//asd
            
            var ext            = true;
            var peso           = true;
            var size           = 0;
            var file           = $(obj)[0].files[0];
            var obj            = $(obj);
            var fileName       = file.name;
            var fileExtension  = fileName.substring(fileName.lastIndexOf('.') + 1);
            var fileSize       = file.size;
            size               = fileSize/1024;

            if(!isImage(fileExtension)){
                    alert('No se acepta ese formato. Intente con los formatos señalados en el recuadro informativo.');
                    //$('#licencia').html('<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />');
                    //$('#form-field-input-carga-licencia').on('change',licencia);
                    $(obj).parent("div").parent("div").parent("div").find('#img-check-ci').hide();
                    $(obj).parent("div").parent("div").parent("div").find('#img-error-ci').show();
                    $(obj).val('');
                    return false;
            }
            if(size>5120){
                    alert('El archivo supera el peso establecido.');
                    //$('#licencia').html('<input id="form-field-input-carga-licencia" required name="data[Archivos][licencia]" type="file" />');
                    //$('#form-field-input-carga-licencia').on('change',licencia);
                    $(obj).parent("div").parent("div").parent("div").find('#img-check-ci').hide();
                    $(obj).parent("div").parent("div").parent("div").find('#img-error-ci').show();
                    $(obj).val('');
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
        if(size>5120){
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
	        case 'jpg': case 'jpeg': case 'pdf': case 'doc': case 'docx':  case 'png': case 'rar':  case 'zip':
	            return true;
	        break;
	        default:
	            return false;
	        break;
	    }

	}
</script>
<script>
	//var anexos = 1;
    var anexos = 0;
	var radio = [" ", " ", " ", " "];	
	var anexos_iniciales = -1;
	 $(".contenedor_anexo").each(function(){                
				anexos_iniciales++;
            });

	$('#nuevoAnexo').click(function(){            
            contador_anexos = 0;
			anexos = 0;
            $(".contenedor_anexo").each(function(){                
				anexos++;
            });
			if(anexos > 18) {
				$('#nuevoAnexo').hide();
				$('#modalMaximo').modal('show'); ;				
			}			
			anexos++;
			$('#borrarAnexo').show();

            var clone = $("#fila-archivo-nuevo-anexo").clone(true);

            clone.find('.nombreAnexo').attr('id', 'PrepostulacionAnexo'+anexos);

            clone.find('.nombreAnexo').val('');
            clone.find('.nombreAnexo').attr('name', 'data[Archivos][anexo'+anexos+'][nombre]');
            clone.find('.archivoAnexo').attr('name', 'data[Archivos][anexo'+anexos+']');

            clone.find('.tipoArchivoAnexo').attr('name','data[Archivos][anexo'+anexos+'][tipo_archivo]');
            
            //______________________________________asd______________________________________
            clone.find('.valorAnexoValue').val(anexos);
            clone.find('.anexoEstado').attr('name','data[Archivos][anexo'+anexos+'][estado]');
            clone.find('.anexoId').attr('name','data[Archivos][anexo'+anexos+'][anexo_id]');
            clone.find('.valorAnexoValue').attr('name','data[Archivos][anexo'+anexos+'][valor_anexo_value]');
            //______________________________________asd______________________________________

            clone.find('#PrepostulacionRadioLaboral').attr('id', 'PrepostulacionRadioLaboral'+anexos+'');
            clone.find('#PrepostulacionRadioAcademico').attr('id', 'PrepostulacionRadioAcademico'+anexos+'');
            clone.find('#PrepostulacionRadioConvenio').attr('id', 'PrepostulacionRadioConvenio'+anexos+'');
            clone.find("label[for='PrepostulacionRadioAcademico']").attr('for', 'PrepostulacionRadioAcademico'+anexos+'');
            clone.find("label[for='PrepostulacionRadioConvenio']").attr('for', 'PrepostulacionRadioConvenio'+anexos+'');
            clone.find("label[for='PrepostulacionRadioLaboral']").attr('for', 'PrepostulacionRadioLaboral'+anexos+'');
            clone.find(".tipoArchivo").attr('name', 'data[Archivos][anexo'+anexos+'][tipo]');
            clone.find('.tipoArchivo').prop('checked',false); //Lo desactivo

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
		if (anexos_iniciales+1 == anexos) { 
			$('#borrarAnexo').hide(); 
		}
		if ((anexos < 21)){
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
			$("#accion_prepostulacion").val('2');
			$("#ultima_accion_prepostulacion").val('guardado');			
			if( comprobarCamposRequired() == true){
                $("#cierra_modal").attr("disabled", "disabled");
            }else{
                $(".cerrar").click();
            }
        });
        
        $('#enviarPrepostulacion').on('click',function(e){
            $("#accion_prepostulacion").val('1');
            $("#ultima_accion_prepostulacion").val('enviado');
			if( comprobarCamposRequired() == true){
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
        
        
              
        $('.edit_file').on('click',function(e){
			anexo = ($(this).closest('.row').find('input[type="radio"]:checked').attr("anexo"));
			radio[anexo-1] = $(this).closest('.row').find('input[type="radio"]:checked').val();
			//BOTON EDITAR
            if($(this).parent('div').parent('div').find('.nombreAnexo').is(':disabled')){
                if ($(this).closest('.row').find('input[type="radio"]:checked').val() == 'Convenio'){
					$(this).closest('.row').find('.nombreAnexo').hide('visibility','hidden');				
					$(this).closest('.row').find('.nombreAnexo').prop('required',false);
					$(this).closest('.row').find('.archivoAnexo').prop('required',false);
					$(this).closest('.row').find('.archivoAnexo').css('visibility','hidden');				
				}
                $(this).parent('div').parent('div').find('.nombreAnexo').prop("disabled", false);
                $(this).parent('div').parent('div').find('.nombreAnexo_lbl').addClass('hide');
                $(this).parent('div').parent('div').find('.archivoAnexo').removeClass('hide');
                $(this).parent('div').parent('div').find('.tipoArchivo').prop("disabled", false); 
                $(this).parent('div').parent('div').find('.anexoEstado').val("por_validar");               
                $(this).parent('div').find('.edit_file_cancel').show();
				$(this).hide();
            }		            
        });
        
        $('.edit_file_cancel').on('click',function(e){            
            if(!$(this).parent('div').parent('div').find('.nombreAnexo').is(':disabled')){
				anexo = ($(this).closest('.row').find('input[type="radio"]:checked').attr("anexo"));	
                $(this).closest('.row').find('input[type="radio"][value='+radio[anexo-1]+']').prop('checked', true);
                $(this).parent('div').parent('div').find('.nombreAnexo').prop("disabled", true);                
                $(this).parent('div').parent('div').find('.nombreAnexo').show();

                $(this).parent('div').parent('div').find('.tipoArchivo').prop("disabled", true); 
                $(this).parent('div').parent('div').find('.anexoEstado').val("ok"); 
				$(this).parent('div').parent('div').find('.nombreAnexo').removeClass("hide");
				$(this).closest('.row').find('.nombreAnexo').css('visibility','visible');
                $(this).parent('div').parent('div').find('.nombreAnexo_lbl').removeClass('hide');
                $(this).parent('div').parent('div').find('.archivoAnexo').addClass('hide');
				$(this).closest('.row').find('#img-check-licencia').css('visibility','visible');
				$(this).closest('.row').find('#img-check-licencia').css('display','block');
				$(this).closest('.row').find('#img-error-licencia').css('visibility','hidden');			
				
				if (radio[anexo-1] !== 'Convenio'){
					$(this).closest('.row').find('#img-convenio').hide();
					
					
				}
				else {
					console.log('entro');
					$(this).closest('.row').find('#img-convenio').show();
					$(this).closest('.row').find('#img-error-licencia').hide();
					$(this).closest('.row').find('.nombreAnexo').hide();
					$(this).closest('.row').find('.nombreAnexo').prop('required',false);
				}
				$(this).closest('.row').find('.edit_file').show();
                $(this).hide(); 
            }            
        });
        
        //Documentos ci - li
        $('.edit_file2').on('click',function(e){
            
            if($(this).parent('div').parent('div').find('#generic_file').css('display') == 'none'){
                
                $(this).parent('div').parent('div').find('.nombreArchivo_lbl').hide();
                $(this).parent('div').parent('div').find('#generic_file').show();
                $(this).parent('div').parent('div').find('#estado').val('por_validar');
                
                $(this).parent('div').find('.edit_file_cancel2').show();
            }
            
        });
        
        $('.edit_file_cancel2').on('click',function(e){           
            if((($(this).closest('.row').find('#generic_file').css('display') == 'inline')) || (($(this).closest('.row').find('#generic_file').css('display') == 'inline-block'))){  						
                $(this).parent('div').parent('div').find('.nombreArchivo_lbl').show();
                $(this).parent('div').parent('div').find('#generic_file').hide();
                $(this).parent('div').parent('div').find('#estado').val('ok');                
                $(this).hide();
            }            
        });
        
        
        
        $("#form-field-input-escuela").change(function(){
          
            if($(this).val() != ''){
                $("#form-field-input-sedes").html("<option></option>");
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
		
		
		$('.tipoArchivo').change(function(){	
			var obj = $(this);
			
			if($(this).val() == 'Convenio'){				
				$(this).closest('.row').find('.nombreAnexo').css('visibility','hidden');
				$(this).closest('.row').find('.nombreAnexo').prop('required',false);
				$(this).closest('.row').find('.archivoAnexo').prop('required',false);
				$(this).closest('.row').find('.archivoAnexo').css('visibility','hidden');
				$(this).closest('.row').find('#img-error-licencia').css('visibility','hidden');
				$(this).closest('.row').find('#img-error-licencia').css('display','none');
				$(this).closest('.row').find('#img-check-licencia').css('visibility','hidden');
				$(this).closest('.row').find('#img-check-licencia').css('display','none');
				$(this).closest('.row').find('#img-convenio').css('display','block');
				$(this).closest('.row').find('#img-convenio').css('visibility','visible');				
				$(this).closest('.row').find('.nombreAnexo').prop('required',false);
				$(this).closest('.row').find('.archivoAnexo').prop('required',false);					
			}
			else{			
				$(this).parent('div').parent('div').parent('div').find('.nombreAnexo').css('visibility','visible');
				$(this).parent('div').parent('div').parent('div').find('.archivoAnexo').css('visibility','visible'); 
				$(this).closest('.row').find('#img-error-licencia').css('visibility','visible');
				$(this).closest('.row').find('#img-check-licencia').css('visibility','visible');
				$(this).closest('.row').find('#img-check-licencia').hide();
				$(this).closest('.row').find('#img-convenio').css('display','none');
				$(this).closest('.row').find('#img-error-licencia').css('display','block');
				if ($(this).closest('.row').find('.archivoAnexo').val() !== ''){
					$(this).closest('.row').find('#img-check-licencia').css('visibility','visible');
					$(this).closest('.row').find('#img-check-licencia').css('display','block');
					$(this).closest('.row').find('#img-error-licencia').css('display','none');
					
					$(this).closest('.row').find('.nombreAnexo').prop('required',true);
					$(this).closest('.row').find('.archivoAnexo').prop('required',true);
				}
				$(this).closest('.row').find('.nombreAnexo').prop('required',true);
				$(this).closest('.row').find('.nombreAnexo').css('display','block');
				$(this).closest('.row').find('.archivoAnexo').prop('required',true);				
				$(this).closest('.row').find('.archivoAnexo').show();				
				if (anexos < 19) {
					$('#nuevoAnexo').show();
				}
			}			
		});
		
		$('label').click(function(){			
			return false;			
		});
		
		
		
</script>