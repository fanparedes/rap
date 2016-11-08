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
</style>
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span5 offset2">
			  	<h1>Datos del Postulante </h1>
			</div>
			<div class="span1" style="margin-top: 20px;">
				<a class="btn" href="<?php echo $this->Html->url(array('action'=>'postulantes')); ?>">Volver</a>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="row-fluid">
			<div class="span10 offset2">
			  	<form action="<?php echo $this->Html->url(array('action'=>'updateData',$user['Postulante']['codigo']))?>" method="POST" id="FormFormularioInscripcion">
			  		<input value="<?php echo $user['Postulante']['codigo']; ?>" type="hidden" name="data[Postulante][codigo]" >
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-nombre"class="control-label" >Nombre</label>
		                </div>
		                <div class="span5">
		                	<?php $nombre = isset($user['Postulante']['nombre'])? $user['Postulante']['nombre'] : ''; ?>
		                    <input value="<?php echo $nombre; ?>" id="form-field-input-nombre" class="span12"type="text" required name="data[Postulante][nombre]" placeholder="Nombre...">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-nombre"class="control-label" >A.Paterno</label>
		                </div>
		                <div class="span5">
		                	<?php $nombre = isset($user['Postulante']['apellidop'])? $user['Postulante']['apellidop'] : ''; ?>
		                    <input value="<?php echo $nombre; ?>" id="form-field-input-nombre" class="span12"type="text" required name="data[Postulante][apellidop]" placeholder="Apellido Paterno">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-nombre"class="control-label" >A.Materno</label>
		                </div>
		                <div class="span5">
		                	<?php $nombre = isset($user['Postulante']['apellidom'])? $user['Postulante']['apellidom'] : ''; ?>
		                    <input value="<?php echo $nombre; ?>" id="form-field-input-nombre" class="span12"type="text" required name="data[Postulante][apellidom]" placeholder="Apellido Materno">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-rut"class="control-label">Rut</label>
		                </div>
		                <div class="span5" >
		                	<?php $rut = isset($user['Postulante']['rut'])? $user['Postulante']['rut'] : ''; ?>
		                    <input value="<?php echo $this->format->rut($rut); ?>" maxlength="12" id="form-field-input-rut" type="text" required name="data[Postulante][rut]" placeholder="ejemplo: 11.111.111-1" class="span12 rut">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-email"class="control-label">Email</label>
		                </div>
		                <div class="span5">
		                	<?php $email = isset($user['Postulante']['email'])? $user['Postulante']['email'] : ''; ?>
		                    <input value="<?php echo $email; ?>" id="form-field-input-email" type="email" required  name="data[Postulante][email]" placeholder="correo@dominio.com" class="span12">
		                </div>
		            </div>
					 <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-telefono"class="control-label">Teléfono</label>
		                </div>
		                <div class="span5">
		                	<?php $telefono = isset($user['Postulante']['telefonomovil'])? $user['Postulante']['telefonomovil'] : ''; ?>
		                    <input value="<?php echo $user['Postulante']['telefonomovil']; ?>" id="form-field-input-telefonomovil" required  name="data[Postulante][telefonomovil]" placeholder="Teléfono" class="span12" maxlength="11">
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label for="form-field-input-fecha-nacimiento"  class="control-label">Fecha Nacimiento</label>
		                </div>
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
		                    <label class="control-label">Genero</label>
		                </div>
		                <div class="span5">
		                    <input id="form-field-radio-genero-m" required <?php echo ($user['Postulante']['genero']=='M')? 'checked': ''; ?> value="M" type="radio" name="data[Postulante][genero]">&nbsp;&nbsp;&nbsp;<label for="form-field-radio-genero-m" style="display:inline-block" >Masculino</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                    <input id="form-field-radio-genero-f" required <?php echo ($user['Postulante']['genero']=='F')? 'checked': ''; ?> value="F" type="radio" name="data[Postulante][genero]">&nbsp;&nbsp;&nbsp;<label for="form-field-radio-genero-f" style="display:inline-block" >Femenino</label>
		                </div>
		            </div>
		            <div class="row-fluid">
		                <div class="span2">
		                    <label class="control-label">Extranjero</label>
		                </div>
		                <div class="span5">
							<?php
								if ($user['Postulante']['extranjero']== 1): $valor = '1'; else: $valor = '0'; endif; 
								$opciones = array('1' => 'SI', '0' => 'NO');
								echo $this->Form->input('extranjero', array('options' => $opciones, 'label' => false, 'selected' => $valor));
							?>
						</div>
					</div>
		            </div>
		            <div class="row-fluid">
		            	<div class="span5 offset2">
		            		<div class="control-submit">
								<button style="margin-top:10px;"id="form-input-submit-enviar" type="submit"  class="btn btn-primary btn-block">Actualizar</button>  
							</div>
						</div>
		            </div>
		            <div class="row-fluid">
		            	<div class="span5 offset2">
		            		<div class="control-submit">
								<label id="msj-obligatorio"> Todos los campos son obligatorios</label>  
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
	    $('.rut').Rut({
			on_error: function(){ alert('El rut ingresado no es válido, favor revizar e intentar nuevamente.'); },
			on_success: function(){},
			format_on: 'keyup'
		});
		
		$("#form-field-input-telefonomovil").keydown(function(event) {
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
</script>
