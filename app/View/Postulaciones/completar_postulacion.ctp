<?php 
	echo $this->Html->script('jquery.rut.js');
	$codigo_postulacion = (!empty($postulacion) && isset($postulacion['Postulacion']['codigo']))? $postulacion['Postulacion']['codigo']:'';
	$urlDocumentacion = $this->Html->url(array('controller'=>'postulaciones','action'=>'cargaDocumentos',$codigo_postulacion));
	$urlCvRap = $this->Html->url(array('controller'=>'postulaciones','action'=>'CvRap',$codigo_postulacion));
	$urlCompetencias = $this->Html->url(array('controller'=>'postulaciones','action'=>'competencias',$codigo_postulacion));
	#debug($postulacion);
	//MODIFICO ESTA PARTE DE LA VISTA PARA QUE SI NO HAY SEDE, ES QUE AÚN NO HA ELEGIDO CARRERA RAP, ENTONCES, SE BLOQUEAN TODOS LOS CAMPOS PORQUE YA HA PASADO ESTE PASO	
	if (($postulacion['Postulacion']['tipo'] == 'RAP') && ($resumen['estado']['codigo'] < 1))
		{
				$disabled = '';
		}
		else { 
			$disabled = 'disabled';
		}
        
?>
<style type="text/css" media="screen">
	.mini-title{
		font-size:15px;
		color:#ccc;
	}	
	#msj-obligatorio{
		font-size: 12px;
		font-style:italic;
	}
	.subtitle{
		color:#6E6E6E;
		border-bottom:1px solid #6E6E6E;
	}
	.link-pass:hover{
		cursor:pointer
	}
	.formulario{
		border-bottom: 1px solid #E2E2E2;
		border-left :1px solid #E2E2E2;
		border-right: 1px solid #E2E2E2;
		width: 98%;
		margin-left:1% !important;
	}
	#form-field-input-empresa{
		text-transform:uppercase;
	}
	#form-field-input-cargo{
		text-transform:uppercase;
	}
</style>
<script>
    function patron(campo){
    	if (campo.value.trim().length>0){
  			if (campo.value.substr(0,1).trim().length>0){
				campo.pattern="[a-zA-Z 0-9ñÑáéíóúÁÉÍÓÚüÜ]+";}
			else{
  			campo.pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ]+";}
  		}else{
  			campo.pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ]+";
  		}
  	}
	
$(document).ready(function() {
$('#form-field-input-empresa').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiou');
$('#form-field-input-cargo').validCampo(' abcdefghijklmnñopqrstuvwxyzáéiou');

});
    </script>
<?php echo $this->element('wizard-postulacion',array('resumen'=>$resumen,'cod_postulacion'=>$codigo_postulacion)); ?>
<div class="row-fluid formulario">
	<div class="span12 ">
		<div class="row-fluid ">
			<div class="span10 offset1 ">
			  	<h3>Formulario de Postulación</h3>
			</div>
		</div>
		<?php if(!$disabled): ?>
            <div class="row-fluid">
            	<div class="span4 offset1" style="margin-top:-3px;">
					<label id="msj-obligatorio" class="pull-left label label-warning"> Todos los campos son obligatorios</label>  
				</div>
            </div>
        <?php endif; ?>
        <br>
		<div class="row-fluid">
			<div class="span10 offset1">
			  	 <form action="" method="POST" id="FormFormularioPreguntasBasicas">		           
		            <input type="hidden" name="data[Postulacion][actividad_laboral]" value="1" />
		            <input type="hidden" name="data[Postulacion][licencia_educacion_media]" value="1"/>
		            <input type="hidden" name="data[Postulacion][codigo]" value="<?php echo $this->params['pass'][0]; ?>"/>
		            <div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-experiencia"class="control-label" >¿Posee al menos un año de Experiencia Laboral?</label>
		                </div>
		                <div class="span6 ">
		                    <select id="form-field-input-experiencia" disabled required type="select" required name="data[Postulacion][actividad_laboral]"  class="pull-right">
		                    	<option></option>
		                    	<option value="1" <?php echo (!empty($postulacion) && $postulacion['Postulacion']['actividad_laboral']==1)? 'selected':'';?> >SI</option>
		                    	<option value="0" <?php echo (!empty($postulacion) && $postulacion['Postulacion']['actividad_laboral']==0)? 'selected':'';?> >NO</option>
		                    </select>
		                </div>
		            </div>
		            <div class="row-fluid no-experiencia  <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['actividad_laboral']) && $postulacion['Postulacion']['actividad_laboral']!=1)? '':'hide';?>">
		                <div class="span12 alert alert-info">
		                    <h6>Esta modalidad de admisión sólo está disponible si tienes un año o más de experiencia laboral</h6>
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-licencia" class="control-label" >¿Posee Licencia de Enseñanza Media?</label>
		                </div>
	                	<div class="span6 ">
		                    <select id="form-field-input-licencia" disabled required type="select" required name="data[Postulacion][licencia_educacion_media]"  class="pull-right">
		                    	<option></option>
		                    	<option value="1" <?php echo (!empty($postulacion) && $postulacion['Postulacion']['licencia_educacion_media']==1)? 'selected':'';?>>SI</option>
		                    	<option value="0" <?php echo (!empty($postulacion) && $postulacion['Postulacion']['licencia_educacion_media']==0)? 'selected':'';?>>NO</option>
		                    </select>
		                </div>
		            </div>
		            <div class="row-fluid no-media  <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['licencia_educacion_media']) && $postulacion['Postulacion']['licencia_educacion_media']!=1)? '':'hide';?>">
		                <div class="span12 alert alert-info">
		                    <h6>Esta modalidad de admisión sólo está disponible si tienes licencia de enseñanza media</h6>
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-ciudad" class="control-label" >Ciudad de Residencia</label>
		                </div>
		                <div class="span6">
		                   <select id="form-field-input-ciudad" required disabled name="data[Postulacion][ciudad_codigo]" class="pull-right">
		                   	
                                        <option></option>                                        
		                   	<?php foreach($ciudades as  $ciudad): ?>
		                   		<?php /*<option value="<?php echo $ciudad['Ciudad']['codigo']; ?>" <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['ciudad_codigo']))? 'selected':'';?>><?php echo utf8_encode($ciudad['Ciudad']['nombre']); ?></option> */?>
                                                <option value="<?php echo $ciudad['Ciudad']['codigo'];?>" <?php if ((!empty($postulacion)) && ($ciudad['Ciudad']['codigo'] == $postulacion['Postulacion']['ciudad_codigo'])) {echo 'selected';} ?>><?php echo ($ciudad['Ciudad']['nombre']); ?></option>
		                   	<?php endforeach; ?>
		                   </select>
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-carrera" class="control-label" >Carrera a postular</label>
		                </div>
		                <div class="span6">
		                   <select id="form-field-input-carrera" required  <?php echo $disabled; ?> name="data[Postulacion][carrera_codigo]" class="pull-right">
		                   	<option></option>
		                   	<?php foreach($carreras as  $carrera): ?>
		                   		<option value="<?php echo $carrera['Carrera']['codigo']; ?>" <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['carrera_codigo']) && $postulacion['Postulacion']['carrera_codigo']==$carrera['Carrera']['codigo'])? 'selected':'';?>><?php echo ($carrera['Carrera']['nombre']); ?></option>
		                   	<?php endforeach; ?>
		                   </select>
		                </div>
		            </div>
		            <div class="row-fluid" >
		                <div class="span6">
		                    <label for="form-field-input-sede" class="control-label" >Sede</label>
		                </div>
		                <div class="span6" id="div-sedes">
		                   <select id="form-field-input-sede"<?php echo $disabled; ?> required name="data[Postulacion][sede_codigo]" class="pull-right">
		                   	<option></option>
		                   	<?php if($disabled): ?>
			                   	<?php foreach($sedes as  $sede): ?>
			                   		<option value="<?php echo $sede['Sede']['codigo_sede']; ?>" <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['sede_codigo']) && $postulacion['Postulacion']['sede_codigo']==$sede['Sede']['codigo_sede'])? 'selected':'';?>><?php echo ($sede['Sede']['nombre_sede']); ?></option>
			                   	<?php endforeach; ?>
		                   	<?php endif; ?>
		                   </select>
		                </div>
		            </div>
	             	<div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-jornada" class="control-label" >Jornada</label>
		                </div>
		                <div class="span6" id="div-jornada">
		                   <select id="form-field-input-jornada" <?php echo $disabled; ?> required name="data[Postulacion][jornada]" class="pull-right">
		                   	<option></option>
		                   	<?php if($disabled): ?>
		                   		<option value="DIURNA" <?php echo (!empty($postulacion) && $postulacion['Postulacion']['jornada']=="DIURNA")? 'selected':'';?>>DIURNA</option>
		                   		<option value="VESPERTINA" <?php echo (!empty($postulacion) && $postulacion['Postulacion']['jornada']=="VESPERTINA")? 'selected':'';?>>VESPERTINA</option>
								<option value="FOL" <?php echo (!empty($postulacion) && $postulacion['Postulacion']['jornada']=="FOL")? 'selected':'';?>>FOL</option>
		                    <?php endif; ?>
		                   </select>
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-empresa" class="control-label" >Empresa</label>
		                </div>
		                <div class="span6">
		                   <input type="text" id="form-field-input-empresa" <?php echo $disabled; ?> value="<?php echo (!empty($postulacion) && $postulacion['Postulacion']['empresa']!="")? $postulacion['Postulacion']['empresa'] :'';?>" required name="data[Postulacion][empresa]" class="pull-right" onkeyup="patron(this);" />
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-tipo-cargo" class="control-label" >Tipo Cargo</label>
		                </div>
		                <div class="span6">
		                   <select id="form-field-input-tipo-cargo"<?php echo $disabled; ?> required name="data[Postulacion][tipo_cargo_codigo]" class="pull-right">
		                   	<option></option>
		                   	<?php foreach($tipos_cargo as  $tipo_cargo): ?>
		                   		<option value="<?php echo $tipo_cargo['TipoCargo']['codigo']; ?>" <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['tipo_cargo_codigo']) && $postulacion['Postulacion']['tipo_cargo_codigo']==$tipo_cargo['TipoCargo']['codigo'])? 'selected':'';?> ><?php echo $tipo_cargo['TipoCargo']['nombre']; ?></option>
		                   	<?php endforeach; ?>
		                   </select>
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-cargo" class="control-label" >Cargo</label>
		                </div>
		                <div class="span6">
		                   <input type="text" id="form-field-input-cargo" <?php echo $disabled; ?> value="<?php echo (!empty($postulacion) && $postulacion['Postulacion']['cargo']!="")? $postulacion['Postulacion']['cargo'] :'';?>" required name="data[Postulacion][cargo]" class="pull-right"  onkeyup="patron(this);"  />
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span6">
		                    <label for="form-field-input-medio-informacion" class="control-label" >¿Cómo se enteró de esta vía de admisión?</label>
		                </div>
		                <div class="span6">
		                   <select id="form-field-input-medio-informacion" <?php echo $disabled; ?> required name="data[Postulacion][medio_informacion_codigo]"  class="pull-right">
		                   	<option></option>
		                   	<?php foreach($medios as $medio ): ?>
		                   		<option value="<?php echo $medio['MedioInformacion']['codigo']; ?>" <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['medio_informacion_codigo']) && $postulacion['Postulacion']['medio_informacion_codigo']==$medio['MedioInformacion']['codigo'])? 'selected':'';?>><?php echo $medio['MedioInformacion']['nombre']; ?></option>
		                   	<?php endforeach; ?>
		                   </select>
		                </div>
		            </div>
		            <?php if(!$disabled): ?>
			            <div class="row-fluid">
			            	<div class="span6 offset6">
			            		<div class="control-submit">
									<button class="btn btn-primary btn-xs pull-right" type="submit">Enviar</button>									
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
		
		$("#PostulanteTelefonomovil").keydown(function(event) {
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
		
			
		$('#form-field-input-ciudad').change(function(){
			var ciudad = $('#form-field-input-ciudad').val();
			if(ciudad!=309){
				$('.no-stgo').show();
			}else{
				$('.no-stgo').hide();
			}
		});
		$('#form-field-input-licencia').change(function(){
			var ciudad = $('#form-field-input-licencia').val();
			if(ciudad!=1){
				$('.no-media').show();
			}else{
				$('.no-media').hide();
			}
		});
		$('#form-field-input-experiencia').change(function(){
			var ciudad = $('#form-field-input-experiencia').val();
			if(ciudad!=1){
				$('.no-experiencia').show();
			}else{
				$('.no-experiencia').hide();
			}
		});
		$('#form-field-input-carrera').change(function(){
			var carrera = $(this).val();
			$('#div-sedes').html('<div class="pull-right"><?php echo $this->Html->image('loader.gif'); ?></div>');
			$('#div-sedes').load('<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'sedesPorCarrera')); ?>'+'/'+carrera);
			$('select#form-field-input-jornada').val('');
		});
	});

	$(window).load(function(){			
			if ($('#form-field-input-cargo').val() == ''){
				console.log('entro');
				var carrera = $('#form-field-input-carrera').val();
				$('#div-sedes').html('<div class="pull-right"><?php echo $this->Html->image('loader.gif'); ?></div>');
				$('#div-sedes').load('<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'sedesPorCarrera')); ?>'+'/'+carrera);
				$('select#form-field-input-jornada').val('');
				$('#form-field-input-carrera').prop('disabled', true);
			}
			
			<?php if ($resumen['estado']['codigo']==9): //HABILITADA ?>
				$('#modalHabilitar').modal('show');			
			<?php endif; ?>
			<?php if ($resumen['estado']['codigo']==7): //HABILITADA ?>
				$('#modalInhabilitar').modal('show');			
			<?php endif; ?>
		});
		
		
</script>
