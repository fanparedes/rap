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
    .mleft10 {
        margin-left: 10px;
    }
	.nota{
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
		-moz-border-radius-topleft: 5px;
		-moz-border-radius-topright: 5px;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
		display:none;
		position:fixed;
		font-size:18px;
		width:200px;
		height:60px;
		right:5%;
		bottom:0px;
		background-color:#eea635;
		color:#fff;
		padding:15px;
		-webkit-box-shadow: 4px 4px 32px 1px rgba(0,0,0,0.75);
		-moz-box-shadow: 4px 4px 32px 1px rgba(0,0,0,0.75);
		box-shadow: 4px 4px 32px 1px rgba(0,0,0,0.75);
	}
</style>
<br/>
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
    </script>

<div class="row-fluid registro well">
    <div class="span10 offset1">
        <div class="row-fluid">
            <div class="span8">
                <h2>Formulario de Inscripción</h2>		
            </div>
            <div class="span4">
                <a class="pull-right btn btn-danger" href="<?php echo $this->Html->url(array('controller' => 'login', 'action' => 'postulante')); ?>" style="margin-top:12px;"><i class="icon-reply"></i> Volver</a>
            </div>
            <!--<div class="span2" align="right" style="margin-top:20px;">
                    <a href="<?php echo $this->Html->url(array('controller' => 'login', 'action' => 'postulante')); ?>" class="" style="display:inline-block">ir al login</a>
            </div>-->
        </div>
        <div class="row-fluid">
            <div class="span5">
                <label id="msj-obligatorio" class="pull-left label label-warning"> Todos los campos son obligatorios</label>  
            </div>
        </div>
        <br>
        <div>
            <?php
            echo $this->Form->create('Postulante', array(
				'accept-charset' => 'ISO-8859-1',
                'class' => 'form-horizontal',
                'inputDefaults' => array(
                    'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                    'div' => array('class' => 'control-group'),
                    'label' => array('class' => 'control-label'),
                    'between' => '<div class="controls">',
                    'after' => '</div>',
                    'error' => array('attributes' => array('wrap' => 'span', 'class' => 'label label-important mleft10')),
            )));?>
           <?php echo $this->Form->input('Postulante.nombre', array('type' => 'text', 'maxlength' => 50, 'class' => 'form-control ','onkeyup'=>"patron(this);",'required'=>true, 'placeholder' => 'Nombre(s)', 'style' => 'text-transform:uppercase',
                'label' => array('text' => 'Nombre', 'class' => 'control-label'))); ?> 
			<?php echo $this->Form->input('Postulante.apellidop', array('type' => 'text', 'maxlength' => 50, 'class' => 'form-control ','onkeyup'=>"patron(this);",'required'=>true, 'placeholder' => 'Apellido Paterno', 'style' => 'text-transform:uppercase',
                'label' => array('text' => 'Apellido Paterno', 'class' => 'control-label'))); ?>
				<?php echo $this->Form->input('Postulante.apellidom', array('type' => 'text', 'maxlength' => 50, 'class' => 'form-control ','onkeyup'=>"patron(this);",'required'=>true, 'placeholder' => 'Apellido Materno', 'style' => 'text-transform:uppercase',
                'label' => array('text' => 'Apellido Materno', 'class' => 'control-label'))); ?>	
           <?php echo $this->Form->input('Postulante.rut', array('type' => 'text', 'class' => 'form-control input_rut', 'placeholder' => '11.111.111-1' ,'required'=>true)); ?>         
            <div class="control-group" style="margin-bottom:0px!important;">
                <label class="control-label">Extranjero</label>
                <div class='controls'>
                    		 <?php echo $this->Form->input('extranjero', array(
                                  'type'=>'checkbox', 
								  'class' => '',
								  'label' => false,
                                  'format' => array('before', 'input', 'between', 'label', 'after', 'error' ) 
						)); ?>
                </div>
            </div>



         
			
            <div class="control-group">
                <!-- <label class="control-label">Género</label> -->
				<label class="control-label">Sexo</label>
                <div class='controls'>
                    <?php
                    foreach ($generos as $genero) {
                        ?>
                        <label class="radio">
                            <input type="radio" name="data[Postulante][genero]" value="<?php echo $genero[1] ?>" <?php
                            if(isset($this->request->data['Postulante']['genero']) && $this->request->data['Postulante']['genero'] === $genero[1]) {
                                echo 'checked';
                            }
                        ?>><?php echo $genero[0] ?>
                        </label>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class='control-group'>
                <label class='control-label'>Fecha de nacimiento</label>
                <div class='controls'>
                    <div class='row-fluid'>
                        <?php
                        echo $this->Form->input('Postulante.dia', array('type' => 'select', 'options' => $dias, 'empty' => 'Seleccione día',
                            'label' => false, 'div' => false, 'between' => false, 'after' => false, 'class' => 'span4', 'required'
                        ));

                        echo $this->Form->input('Postulante.mes', array('type' => 'select', 'options' => $meses, 'empty' => 'Seleccione mes',
                            'label' => false, 'div' => false, 'between' => false, 'after' => false, 'class' => 'span4', 'required'
                        ));

                        echo $this->Form->input('Postulante.anho', array('type' => 'select', 'options' => $anos, 'empty' => 'Seleccione año',
                            'label' => false, 'div' => false, 'between' => false, 'after' => false, 'class' => 'span4', 'required'
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <?php
            echo $this->Form->input('Postulante.email', array('type' => 'email', 'class' => 'form-control', 'maxlength' => 50, 'placeholder' => 'ejemplo@ejemplo.cl'));
            echo $this->Form->input('Postulante.email2', array('after'=> "<div class='nota'><i class='fa icon-warning'></i> Sea cuidadoso al escribir su email, es necesario para terminar su registro.</div>", 'label' => array('text' => 'Repita su dirección de email', 'class' => 'control-label'),'type' => 'email', 'class' => 'form-control', 'maxlength' => 50, 'placeholder' => 'ejemplo@ejemplo.cl'));
			?>
			<br>
			<?php
            echo $this->Form->input('Postulante.telefonomovil', array('type' => 'text','required'=>true,"pattern"=>"[0-9]+", 'class' => 'form-control', 'maxlength'=>11, 'placeholder' => '12345678',
                'label' => array('text' => 'Teléfono Móvil', 'class' => 'control-label')));

            echo $this->Form->input('Postulante.contrasenha', array('type' => 'password', 'class' => 'form-control', 'maxlength'=>25, 'placeholder' => 'Su contraseña',
                'label' => array('text' => 'Su contraseña', 'class' => 'control-label')));

            echo $this->Form->input('Postulante.contrasenha2', array('type' => 'password', 'class' => 'form-control', 'maxlength'=>25, 'placeholder' =>
                'Confirme la contraseña',
                'label' => array('text' => 'Confirme la contraseña', 'class' => 'control-label')));
            ?>
			<div class="row-fluid">
            	<div class="span5 offset3">
		            <div id="img-check" align="left" class="hide"><?php echo $this->Html->image('test-pass-icon.png'); ?> Contraseñas correctas</div>
		            <div id="img-error" align="left" class="hide"><?php echo $this->Html->image('test-fail-icon.png'); ?> Verifique contraseñas</div>
		            <div id="img-check2" align="left" class="hide"><?php echo $this->Html->image('test-pass-icon.png'); ?> Emails iguales</div>
		            <div id="img-error2" align="left" class="hide"><?php echo $this->Html->image('test-fail-icon.png'); ?> Los emails son diferentes</div><br>
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
                        <button style="margin-top:10px;" id="form-input-submit-enviar" type="submit" disabled class="btn btn-primary btn-block">Enviar</button>  
                    </div>
                </div>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
	$(function(){
		formatearrut();
                $('#PostulanteContrasenha').keyup(validarContrasenha);
                $('#PostulanteContrasenha2').keyup(validarContrasenha);
            
                /*
                $('#PostulanteRut').Rut({
			on_error: function(){ 
                            alert('El rut ingresado no es válido, por favor revisar e intentar nuevamente.');
                            
                            $("#PostulanteRut").val('');
                            $("#PostulanteRut").parent('div').parent('div').removeClass('error');
                            $("#PostulanteRut").removeClass('form-error');
                            $(".mleft10").hide();
                            //$('#PostulanteRut').focus(); 
                            
                        },
			on_success: function(){

                        },
			format_on: 'keyup'
		});
                */
                
                $('#PostulanteRut').keyup(function(){
                    var rut        = $('input#PostulanteRut').val();
                    
                    if(rut){
                        var tamano = rut.length;
                        var rutsd  = rut.substring(0,rut.length-1);
                        var digito = rut.substring(rut.length-1,rut.length);

                        console.log("Digito: "+digito);
                        console.log("Rut Formateado: "+$.Rut.formatear(rutsd+'-'+digito)+'-'+digito);
                        
                        $('#PostulanteRut').val($.Rut.formatear(rutsd)+'-'+digito);
                    }
                });
                $("#PostulanteRut").blur(function(){//asd
                    
                    if($(this).val() == ''){
                        $(this).parent('div').find('.mleft10').hide();
                        $(this).parent('div').parent('div').removeClass('error');
                    }else{
                        if($.Rut.validar($(this).val())){

                        }
                        else{
                            alert('El rut ingresado no es válido, por favor revisar e intentar nuevamente.');
                            $(this).val('');
                            $(this).parent('div').find('.mleft10').hide();
                            $(this).parent('div').parent('div').removeClass('error');
                        }
                    }
                    
                });
                
                
                
                
                $("#PostulanteEmail").blur(function(){//asd
                    
                    if($(this).val() == ''){
                        $(this).parent('div').find('.mleft10').hide();
                        $(this).parent('div').parent('div').removeClass('error');
                    }
                    
                });
                
                
                
               
		$('#PostulanteRut').attr('maxlength',12);
		$('#PostulanteNombre').keyup(validarFormulario);
		$('#PostulanteRut').keyup(validarFormulario);
		$('#PostulanteEmail').keyup(function(){
                    validarFormulario();
                    $(this).parent('div').find('.mleft10').hide();
                    $(this).parent('div').parent('div').removeClass('error');
                });
		$('#PostulanteEmail2').keyup(validarFormulario);
		$('#PostulanteDia').change(validarFormulario);
		$('#PostulanteMes').change(validarFormulario);
		$('#PostulanteAnho').change(validarFormulario);
		$("input[name='data[Postulante][genero]']:radio").change(validarFormulario);

		
		$('#PostulanteNombre').validCampo(' abcdefghijklmnopqrstuvwxyzáéiouéíóúÁÉÍÓÚñÑ´');
		
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
                
		
	});
	function validarFormulario(){
		var nombre = $('#PostulanteNombre').val();
		var rut = $('#PostulanteRut').val();
		var email = $('#PostulanteEmail').val();
		var email2 = $('#PostulanteEmail2').val();
		if (email !== email2){			
			$('#PostulanteEmail2').css('border', '1px solid #e33c39');
			$('#PostulanteEmail').css('border', '1px solid #e33c39');			
            $('#img-error2').show();
			$('#img-check2').hide();
		}
		else {
			$('#PostulanteEmail2').css('border', '1px solid #ccc');
			$('#PostulanteEmail').css('border', '1px solid #ccc');
			$('#img-error2').hide();
            $('#img-check2').show();
		}
		
		
		var dia = $('#PostulanteDia').val();		
		var mes = $('#PostulanteMes').val();	
		var anho = $('#PostulanteAnho').val();
		var contrasenha_1 = $('#PostulanteContrasenha').val();
        var contrasenha_2 = $('#PostulanteContrasenha2').val();	
        var genero = $("input[name='data[Postulante][genero]']:radio").is(':checked');
        var condiciones = $("#check-acepto-condiciones").is(':checked');
		

		
		if(nombre!='' && email!='' && dia!='' && mes!='' && anho!='' && contrasenha_1!='' && contrasenha_2!='' && genero==true && condiciones!='' && email==email2 && contrasenha_1==contrasenha_2){			
			$('#form-input-submit-enviar').removeAttr('disabled');			
		}else{			
			$('#form-input-submit-enviar').attr('disabled','disabled');			
		}
	}
    function validarContrasenha(){
        var primera_contrasenha = $('#PostulanteContrasenha').val();
        var segunda_contrasenha = $('#PostulanteContrasenha2').val();
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

    function validarEmail(){
        var email  = $('#PostulanteEmail').val();
        var email2 = $('#PostulanteEmail2').val();      
        if (email==email2 && email!='' && email!='') {
            validarFormulario();
            //alert('emails correctos');
        }else{
            $('#form-input-submit-enviar').attr('disabled','disabled');
			//alert('emails cincrrectos');
        }
    }
	
    function formatearrut(){
        var rut        = $('input#PostulanteRut').val();
        if(rut.length > 0){
            var tamano = rut.length;
            var rutsd  = rut.substring(0,rut.length-1);
            var digito = rut.substring(rut.length-1,rut.length);

            $('input#PostulanteRut').val(jQuery.Rut.formatear(rutsd)+'-'+digito);
        }
    }
</script>

<script>
$( "#PostulanteEmail" ).focus(function() {	
	$('.nota').delay(3000).slideUp().show();
});
</script>

<script>
//Requerimiento fase 2016 - Se debe quitar el required para el rut si el checkbox extranjero está marcado. 
$( "#PostulanteExtranjero" ).click(function() {
	
	if($("#PostulanteExtranjero").is(':checked')) {	
		$("#PostulanteRut").prop('required', false);
	}
	 else {
		$("#PostulanteRut").prop('required', true); 
	 } 
});
</script>