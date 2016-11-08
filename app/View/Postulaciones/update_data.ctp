<?php echo $this->Html->script('jquery.rut.js'); ?>
<style type="text/css" media="screen">
	.mini-title{
		font-size:15px;
		color:#ccc;
	}	
	#msj-obligatorio{
		font-size: 12px;
		font-style:italic;
	}
	.link-pass:hover{
		cursor:pointer
	}
	#form-field-input-nombre{
		text-transform:uppercase;
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
</style>
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span5 offset2">
			  	<h1>Mis datos</h1>
			</div>
			<div class="span1" style="margin-top: 20px;">
				<a href="<?php echo $this->Html->url(array('controller'=>'home','action'=>'postulantes')); ?>">Volver</a>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="row-fluid">
			<div class="span10 offset2">
			  	<form action="<?php echo $this->Html->url(array('action'=>'updateData'))?>" method="POST" id="FormFormularioInscripcion" enctype="multipart/form-data">
			  		<input value="<?php echo $user['Postulante']['codigo']; ?>" type="hidden" name="data[Postulante][codigo]" >
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-nombre"class="control-label" >Nombre</label>
		                </div>
		                <div class="span5">
		                	<?php $nombre = isset($user['Postulante']['nombre'])? $user['Postulante']['nombre'] : ''; ?>
		                    <input value="<?php echo $nombre; ?>" id="form-field-input-nombre" class="span12"type="text" required name="data[Postulante][nombre]" placeholder="Nombre..." maxlength="50">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-nombre"class="control-label" >Apellido Paterno</label>
		                </div>
		                <div class="span5">
		                	<?php $apellido = isset($user['Postulante']['apellidop'])? $user['Postulante']['apellidop'] : ''; ?>
		                    <input value="<?php echo $user['Postulante']['apellidop']; ?>" id="form-field-input-nombre" class="span12"type="text" required name="data[Postulante][apellidop]" placeholder="Apellido Paterno" maxlength="50" required>
		                </div>		                
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-nombre"class="control-label" >Apellido Materno</label>
		                </div>
		                <div class="span5">
		                	<?php $apellido = isset($user['Postulante']['apellidom'])? $user['Postulante']['apellidom'] : ''; ?>
		                    <input value="<?php echo $user['Postulante']['apellidom']; ?>" id="form-field-input-nombre" class="span12"type="text" required name="data[Postulante][apellidom]" placeholder="Apellido Materno" maxlength="50" required>
		                </div>		                
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-rut"class="control-label">Rut</label>
		                </div>
		                <div class="span5" >
		                	<?php $rut = isset($user['Postulante']['rut'])? $user['Postulante']['rut'] : '';
							$extranjero = isset($user['Postulante']['extranjero']); ?>
		                    <input value="<?php echo $this->format->rut($rut); ?>" maxlength="12" id="form-field-input-rut" type="text" <?php if ($extranjero == 1) : echo ''; else: echo 'requierd'; endif; ?>  name="data[Postulante][rut]" placeholder="ejemplo: 11.111.111-1" class="span12 rut">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-rut"class="control-label">Extranjero</label>
		                </div>
		                <div class="span5" >
		                	<?php 
								
								if ($extranjero == 1) {
									echo 'SI';
								} 
								else {
									echo 'NO';
								}
							?>
		                    
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-email"class="control-label">Email</label>
		                </div>
		                <div class="span5">
		                	<?php $email = isset($user['Postulante']['email'])? $user['Postulante']['email'] : ''; ?>
		                    <input value="<?php echo $email; ?>" id="form-field-input-email" type="email" required  name="data[Postulante][email]" placeholder="correo@dominio.com" class="span12" maxlength="50">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-fecha-nacimiento"  class="control-label">Fecha Nacimiento</label>
		                </div>
		                <!--<div class="span5">
		                    <?php 
		                    	$date = $user['Postulante']['fecha_nacimiento'];
		                    	echo $this->Form->input('fecha_nacimiento',array(
		                    			'type'=>'date',
		                    			'div'=>false,
		                    			'name'=>'data[Postulante][fecha_nacimiento]',
		                    			'class'=>'span4',
		                    			'selected'=>$date,
		                    			'between'=>'',
		                    			'dateFormat' => 'DMY',
									    'minYear' => date('Y') - 70,
									    'maxYear' => date('Y') - 18,
		                    			'empty'=>'Seleccione',
		                    			'label'=>false,
									)
								);
								debug($date);
							?>                   
		                </div>-->
		                <div class="span5">
		                	<?php 
		                		$anho= date('Y',strtotime($user['Postulante']['fecha_nacimiento']));
								$mes= date('m',strtotime($user['Postulante']['fecha_nacimiento']));
								$dia= date('d',strtotime($user['Postulante']['fecha_nacimiento']));			
		                	?>
		                    <select id="form-field-input-dia" required  type="select" name="data[Postulante][dia]" class="span4">
		                    	<option value="">Día</option>
		                    	<?php 
		                    	
		                    	for ($i=1; $i<32 ; $i++): ?>
		                    		<option value="<?php echo $i ?>"  <?php echo ($dia == $i) ? 'selected="selected"': ''; ?> ><?php echo $i; ?></option>
		                    	<?php endfor; ?>
		                    </select>
		                    <select id="form-field-input-mes" required  type="select" name="data[Postulante][mes]" class="span4">
		                    	<option value="">Mes</option>
		                		<option value="01" <?php echo ($mes == '01')? 'selected': ''; ?>><?php echo __('Enero'); ?></option>
		                		<option value="02" <?php echo ($mes == '02')? 'selected': ''; ?>><?php echo __('Febrero'); ?></option>
		                		<option value="03" <?php echo ($mes == '03')? 'selected': ''; ?>><?php echo __('Marzo'); ?></option>
		                		<option value="04" <?php echo ($mes == '04')? 'selected': ''; ?>><?php echo __('Abril'); ?></option>
		                		<option value="05" <?php echo ($mes == '05')? 'selected': ''; ?>><?php echo __('Mayo'); ?></option>
		                		<option value="06" <?php echo ($mes == '06')? 'selected': ''; ?>><?php echo __('Junio'); ?></option>
		                		<option value="07" <?php echo ($mes == '07')? 'selected': ''; ?>><?php echo __('Julio'); ?></option>
		                		<option value="08" <?php echo ($mes == '08')? 'selected': ''; ?>><?php echo __('Agosto'); ?></option>
		                    	<option value="09" <?php echo ($mes == '09')? 'selected': ''; ?>><?php echo __('Septiembre'); ?></option>
		                    	<option value="10" <?php echo ($mes == '10')? 'selected': ''; ?>><?php echo __('Octubre'); ?></option>
		                    	<option value="11" <?php echo ($mes == '11')? 'selected': ''; ?>><?php echo __('Noviembre'); ?></option>
		                    	<option value="12" <?php echo ($mes == '12')? 'selected': ''; ?>><?php echo __('Diciembre'); ?></option>
		                    </select>
							<select id="form-field-input-anho" required  type="select" name="data[Postulante][anho]" class="span4">
		                    	<option value="">Año</option>
		                    	<?php 
		                    		$year = date('Y');
		                    		$year = (int)($year-60);
									$anho_actual = date('Y') +1;
		                    	?>
		                    	<?php for ($i=$year; $i<$anho_actual ; $i++): ?>
		                    		<option value="<?php echo $i; ?>" <?php echo (isset($anho) && $anho==$i)? 'selected': ''; ?>><?php echo $i; ?></option>
		                    	<?php endfor; ?>
		                    </select>                   
		                </div>
		            </div>
					<div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-email"class="control-label">Teléfono Móvil</label>
		                </div>
		                <div class="span5">
		                	<?php $telefono = isset($user['Postulante']['telefonomovil'])? $user['Postulante']['telefonomovil'] : ''; ?>
		                    <input maxlength="11" value="<?php echo $telefono; ?>" id="form-field-input-telefono" required  name="data[Postulante][telefonomovil]" placeholder="12345678" class="span12" required pattern="[0-9]+">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label class="control-label">Sexo</label>
		                </div>
		                <div class="span5">
		                    <input id="form-field-radio-genero-m" required <?php echo ($user['Postulante']['genero']=='M')? 'checked': ''; ?> value="M" type="radio" name="data[Postulante][genero]">&nbsp;&nbsp;&nbsp;<label for="form-field-radio-genero-m" style="display:inline-block" >Masculino</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                    <input id="form-field-radio-genero-f" required <?php echo ($user['Postulante']['genero']=='F')? 'checked': ''; ?> value="F" type="radio" name="data[Postulante][genero]">&nbsp;&nbsp;&nbsp;<label for="form-field-radio-genero-f" style="display:inline-block" >Femenino</label>
		                </div>
		            </div>

		            <div class="row-fluid link-pass">
		                <div class="span5 offset2">
		                    <a id="form-link-cambiar-pass"> Cambiar Contraseña</a>
		                </div>
		            </div>
		            <div class="row-fluid hide pass">
		                <div class="span2">
		                    <label for="form-field-input-contrasenha" class="control-label">Contraseña</label>
		                </div>
		                <div class="span5">
		                    <input id="form-field-input-contrasenha" type="password" placeholder="*********"class="span12">
		                </div>
		            </div>
		            <div class="row-fluid hide pass">
		                <div class="span2">
		                    <label for="form-field-input-re-contrasenha" class="control-label">Confirmar Contraseña</label>
		                </div>
		                <div class="span5">
		                    <input id="form-field-input-re-contrasenha" type="password" class="span12" placeholder="*********">
		                </div>
		            </div>
		            <div class="row-fluid hide img">
		            	<div class="span5 offset2">
				            <div id="img-check" align="left" class="hide"><?php echo $this->Html->image('test-pass-icon.png'); ?> Contraseñas correctas</div>
				            <div id="img-error" align="left" class="hide"><?php echo $this->Html->image('test-fail-icon.png'); ?> Verifique contraseñas</div>
		            	</div>
		            </div>
                                
                                        
                                        
                                        
                               
                               
                                        
                            
                            
                            <div class="row">
                                    <div class="span11">
                                            <h3>Documentación:</h3>
                                    </div>
                            </div>
                            <div class="row">
                                    <div class="span3 subtitle">
                                            <h5>Tipo Documento</h5>		
                                    </div>
                                    <div class="span5 subtitle">
                                            <h5>Archivo</h5>		
                                    </div>
                                    <div class="span3 subtitle">
                                            <h5>Estado</h5>		
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
                                            <div id="info-licencia">

                                                    <?php 
                                                    if(count($licencia_archivo)>0){
                                                        if($licencia_archivo['ArchivoPostulante']['nombre_fisico'] != '' && file_exists(WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$licencia_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$licencia_archivo['ArchivoPostulante']['extension'])){

                                                            if($licencia_archivo['ArchivoPostulante']['valido'] == null){
                                                                echo "<div id='img-error-licencia' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." No hay archivo</div>";
                                                                echo "<div id='img-check-licencia' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Correcto (será evaluado)</div>";
                                                            }
                                                            elseif($licencia_archivo['ArchivoPostulante']['valido'] == '0'){
                                                                echo "<div id='img-error-licencia' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." Archivo Invalido, por favor cambiar</div>";
                                                                echo "<div id='img-check-licencia' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Correcto (será evaluado)</div>";
                                                            }
                                                            else{
                                                                echo "<div id='img-error-licencia' class='hide' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." No hay archivo</div>";
                                                                echo "<div id='img-check-licencia' class='' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Validado'))." Archivo Validado</div>";
                                                            }

                                                        }
                                                        else{
                                                            echo "<div id='img-error-licencia' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." No hay archivo</div>";
                                                            echo "<div id='img-check-licencia' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Permitido</div>";

                                                        }
                                                    }
                                                    else{
                                                        echo "<div id='img-error-licencia' class='' align='left'>".$this->Html->Image('test-fail-icon.png', array('title' => ' No hay archivo o '))." No hay archivo</div>";
                                                        echo "<div id='img-check-licencia' class='hide' align='left'>".$this->Html->Image('test-pass-icon.png', array('title' => ' Archivo Permitido'))." Archivo Permitido</div>";
                                                    }
                                                    ?>

                                            </div>
                                    </div>
                            </div>         
                                        
                                        
                                     
                                        
                                   
                                        
                                        
		            <div class="row-fluid">
		            	<div class="span5 offset2">
		            		<div class="control-submit">
								<button style="margin-top:20px;"id="form-input-submit-enviar" type="submit"  class="btn btn-primary btn-block">Enviar</button>  
							</div>
						</div>
		            </div>
		            <div class="row-fluid">
		            	<div class="span5 offset2">
		            		<div class="control-submit">
								<label id="msj-obligatorio"> Todos los campos son obligatorios exceptuando que ud. sea extranjero.<br> En este caso no es necesario rellenar el RUT</label>  
							</div>
						</div>
		            </div>
		            <div class="clearfix"></div>
		        </form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
            
            
            $('#form-field-input-carga-licencia').on('change',licencia);
            $('#form-field-input-carga-ci').on('change',ci);

            function licencia(){

                var ext            = true;
                var peso           = true;
                var size           = 0;
                var file           = $("#form-field-input-carga-licencia")[0].files[0];
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
                if(size>1024){
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
                console.log('Entrando');
                if (navigator.userAgent.indexOf("MSIE")>0){
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
            
            function isImage(extension){

                switch(extension.toLowerCase()){
                    case 'jpg': case 'jpeg': case 'pdf': case 'doc': case 'docx':
                        return true;
                    break;
                    default:
                        return false;
                    break;
                }
            }
            
            
            
        
	    $('#form-field-input-contrasenha').keyup(validarContrasenha);
	    $('#form-field-input-re-contrasenha').keyup(validarContrasenha);
	    $('.rut').Rut({
			on_error: function(){ alert('El rut ingresado no es válido, por favor revisar e intentar nuevamente.'); },
			on_success: function(){},
			format_on: 'keyup'
		});
		$('#form-link-cambiar-pass').on('click',function(){
			$('.link-pass').hide();
			$('.pass').show();
			$('#form-field-input-contrasenha').attr('name','data[Postulante][contrasenha]');
			$('#form-field-input-contrasenha').attr('required','required');
			$('#form-field-input-re-contrasenha').attr('required','required');
		});
		
	$("#form-field-input-telefono").keydown(function(event) {
		if(event.shiftKey)
		{
			 event.preventDefault();
		}
	  
		if (event.keyCode == 46 || event.keyCode == 8)    {
		}
		else {
			 if (event.keyCode < 95) {
			   if (event.keyCode < 48 || event.keyCode > 57) {
					 event.preventDefault();
			   }
			 } 
			 else {
				   if (event.keyCode < 96 || event.keyCode > 105) {
					   event.preventDefault();
				   }
			 }
		   }
		});
	});
        
        
        function validarContrasenha(){
            var primera_contrasenha = $('#form-field-input-contrasenha').val();
            var segunda_contrasenha = $('#form-field-input-re-contrasenha').val();
            if(primera_contrasenha!='' || segunda_contrasenha!=''){
                    $('.img').show();
            }else{
                    $('.img').hide();
            }
            if (primera_contrasenha==segunda_contrasenha && primera_contrasenha!='' && segunda_contrasenha!='') {
                $('#form-input-submit-enviar').removeAttr('disabled');
                $('#img-check').show();
                $('#img-error').hide();
            }else{
                $('#form-input-submit-enviar').attr('disabled','disabled');
                $('#img-check').hide();
                $('#img-error').show();
            }
        }
</script>
