<?php echo $this->Html->script('jquery.rut.js'); ?>
<style type="text/css">
	#msj-obligatorio{
		font-size: 12px;
		font-style:italic;
		margin-top:-10px;
	}
	.registro label{
		font-size:16px;
	}
	#form-field-input-nombre{
		text-transform:uppercase;
	}
</style>
<br/>
<div class="row-fluid registro">
    <div class="span10 offset1">
        <div class="row-fluid">
        	<div class="span8">
        		<h2>Formulario de Inscripción</h2>		
        	</div>
        	<div class="span4">
        		<a class="pull-right btn btn-danger" href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'postulante')); ?>" style="margin-top:12px;">Salir</a>
        	</div>
        	<!--<div class="span2" align="right" style="margin-top:20px;">
        		<a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'postulante')); ?>" class="" style="display:inline-block">ir al login</a>
        	</div>-->
        </div>
        <div class="row-fluid">
	    	<div class="span5">
				<label id="msj-obligatorio" class="pull-left label label-warning"> Todos los campos son obligatorios</label>  
			</div>
	    </div>
        <br>
        <form action="" method="POST" id="FormFormularioInscripcion">
            <div class="row-fluid">
                <div class="span3">
                    <label for="form-field-input-nombre"class="control-label" >Nombre Completo</label>
                </div>
                <div class="span9">
                    <input id="form-field-input-nombre" value="<?php echo (isset($this->data['Postulante']))? $this->data['Postulante']['nombre']: ''; ?>" class="span12" type="text" required name="data[Postulante][nombre]" placeholder="Nombre y Apellidos...">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <label for="form-field-input-rut"class="control-label">Rut</label>
                </div>
                <div class="span9" >
                    <input id="form-field-input-rut"  value="<?php echo (isset($this->data['Postulante']))? $this->data['Postulante']['rut']: ''; ?>"  type="text" required name="data[Postulante][rut]" placeholder="ejemplo: 11.111.111-1" class="span12 rut">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <label for="form-field-input-email"class="control-label">Email</label>
                </div>
                <div class="span9">
                    <input id="form-field-input-email" value="<?php echo (isset($this->data['Postulante']))? $this->data['Postulante']['email']: ''; ?>"  type="email" required  name="data[Postulante][email]" placeholder="correo@dominio.com" class="span12">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <label for="form-field-input-fecha-nacimiento"  class="control-label">Fecha Nacimiento</label>
                </div>
                <div class="span9">
                    <select id="form-field-input-dia" required  type="select" name="data[Postulante][dia]" class="span4">
                    	<option value="">Día</option>
                    	<?php for ($i=1; $i<32 ; $i++): ?>
                    		<option value="<?php echo $i; ?>"  <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['dia']==$i)? 'selected="selected"': ''; ?> ><?php echo $i; ?></option>
                    	<?php endfor; ?>
                    </select>
                    <select id="form-field-input-mes" required  type="select" name="data[Postulante][mes]" class="span4">
                    	<option value="">Mes</option>
                		<option value="01" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="01")? 'selected': ''; ?>><?php echo __('Enero'); ?></option>
                		<option value="02" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="02")? 'selected': ''; ?>><?php echo __('Febrero'); ?></option>
                		<option value="03" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="03")? 'selected': ''; ?>><?php echo __('Marzo'); ?></option>
                		<option value="04" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="04")? 'selected': ''; ?>><?php echo __('Abril'); ?></option>
                		<option value="05" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="05")? 'selected': ''; ?>><?php echo __('Mayo'); ?></option>
                		<option value="06" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="06")? 'selected': ''; ?>><?php echo __('Junio'); ?></option>
                		<option value="07" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="07")? 'selected': ''; ?>><?php echo __('Julio'); ?></option>
                		<option value="08" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="08")? 'selected': ''; ?>><?php echo __('Agosto'); ?></option>
                		<option value="09" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="09")? 'selected': ''; ?>><?php echo __('Septiembre'); ?></option>
                		<option value="10" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="10")? 'selected': ''; ?>><?php echo __('Octubre'); ?></option>
                		<option value="11" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="11")? 'selected': ''; ?>><?php echo __('Noviembre'); ?></option>
                		<option value="12" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['mes']=="12")? 'selected': ''; ?>><?php echo __('Diciembre'); ?></option>
                    </select>
					<select id="form-field-input-anho" required  type="select" name="data[Postulante][anho]" class="span4">
                    	<option value="">Año</option>
                    	<?php 
                    		$year = date('Y');
                    		$year = (int)($year-60);
							$anho_actual = date('Y') +1;
                    	?>
                    	<?php for ($i=$year; $i<$anho_actual ; $i++): ?>
                    		<option value="<?php echo $i; ?>" <?php echo (isset($this->data['Postulante']) && $this->data['Postulante']['anho']==$i)? 'selected': ''; ?>><?php echo $i; ?></option>
                    	<?php endfor; ?>
                    </select>                   
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <label for="form-field-input-contrasenha"class="control-label">Contraseña</label>
                </div>
                <div class="span9">
                    <input id="form-field-input-contrasenha" value="" required type="password" name="data[Postulante][contrasenha]" placeholder="*********"class="span12">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <label for="form-field-input-re-contrasenha" class="control-label">Confirmar Contraseña</label>
                </div>
                <div class="span9">
                    <input id="form-field-input-re-contrasenha" value="" required type="password" class="span12" placeholder="*********">
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <label class="control-label">Genero</label>
                </div>
                <div class="span9">
                    <input id="form-field-radio-genero-m" required="required" value="M" type="radio" name="data[Postulante][genero]">&nbsp;&nbsp;&nbsp;<label for="form-field-radio-genero-m" style="display:inline-block" >Masculino</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input id="form-field-radio-genero-f" required="required" value="F" type="radio" name="data[Postulante][genero]">&nbsp;&nbsp;&nbsp;<label for="form-field-radio-genero-f" style="display:inline-block" >Femenino</label>
                </div>
            </div>
            <div class="row-fluid">
            	<div class="span5 offset3">
		            <div id="img-check" align="left" class="hide"><?php echo $this->Html->image('test-pass-icon.png'); ?> Contraseñas correctas</div>
		            <div id="img-error" align="left" class="hide"><?php echo $this->Html->image('test-fail-icon.png'); ?> Verifique contraseñas</div>
            	</div>
            </div>
            <div class="row-fluid">
            	<div class="span9 offset3">
		            <p><input  type="checkbox" value="1" name="data[Postulante][acepto]" onclick="javascript:validarFormulario()" id="check-acepto-condiciones" style="display:inline-block" />&nbsp;Autorizo a DUOC UC a realizar tratamiento de la información que entrego, conforme a la ley 19.628.</p>
            	</div>
            </div>
            <div class="row-fluid">
            	<div class="span5 offset3">
            		<div class="control-submit">
						<button style="margin-top:10px;" id="form-input-submit-enviar" disabled type="submit"  class="btn btn-primary btn-block">Enviar</button>  
					</div>
				</div>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>
<script type="text/javascript">
	$(function(){
	    $('#form-field-input-contrasenha').keyup(validarContrasenha);
	    $('#form-field-input-re-contrasenha').keyup(validarContrasenha);
	    $('.rut').Rut({
			on_error: function(){ alert('El rut ingresado no es válido, favor revizar e intentar nuevamente.'); },
			on_success: function(){},
			format_on: 'keyup'
		});
		$('.rut').attr('maxlength',12);
		$('#form-field-input-nombre').keyup(validarFormulario);
		$('#form-field-input-rut').keyup(validarFormulario);
		$('#form-field-input-email').keyup(validarFormulario);
		$('#form-field-input-dia').change(validarFormulario);
		$('#form-field-input-mes').change(validarFormulario);
		$('#form-field-input-anho').change(validarFormulario);
		$("input[name='data[Postulante][genero]']:radio").change(validarFormulario);
	});
	function validarFormulario(){
		var nombre = $('#form-field-input-nombre').val();
		var rut = $('#form-field-input-rut').val();
		var email = $('#form-field-input-email').val();
		var dia = $('#form-field-input-dia').val();		
		var mes = $('#form-field-input-mes').val();	
		var anho = $('#form-field-input-anho').val();
		var contrasenha_1 = $('#form-field-input-contrasenha').val();
        var contrasenha_2 = $('#form-field-input-re-contrasenha').val();	
        var genero = $("input[name='data[Postulante][genero]']:radio").is(':checked');
        var condiciones = $("#check-acepto-condiciones").is(':checked');
		if(nombre!='' && rut!='' && email!='' && dia!='' && mes!='' && anho!='' && contrasenha_1!='' && contrasenha_2!='' && genero==true && condiciones!=''){
			$('#form-input-submit-enviar').removeAttr('disabled');
		}else{
			$('#form-input-submit-enviar').attr('disabled','disabled');
		}
	}
    function validarContrasenha(){
        var primera_contrasenha = $('#form-field-input-contrasenha').val();
        var segunda_contrasenha = $('#form-field-input-re-contrasenha').val();
        if (primera_contrasenha==segunda_contrasenha && primera_contrasenha!='' && segunda_contrasenha!='') {
            validarFormulario();
            $('#img-check').show();
            $('#img-error').hide();
        }else{
            $('#form-input-submit-enviar').attr('disabled','disabled');
            $('#img-check').hide();
            $('#img-error').show();
        }
    }
</script>