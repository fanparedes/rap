 <div class="row-fluid content-login-recuperar-clave">
	<div class="span12 login-well">
        <div class="well login row-fluid">
        	<form class="form-signin" action="<?php echo $this->Html->url(array('controller'=>'login','action'=>'recuperarClave',$valor)); ?>" method="POST">
        	<div class="span12">
        		<h2>Restablecer Contraseña</h2>
        	</div>
        	<div class="row-fluid">
        		<div class="span12">
        			<p>Para restablecer su contraseña debe ingresar una nueva en ambos campos</p>
        		</div>
        	</div>
        	<div class="row-fluid">
        		<div class="span12">
        			<div class="control-group horizontal">
        				<label for="pass1">Nueva Contraseña</label>
        				<div class="control">
        					<input type="password" id="pass1" name="data[Postulante][pass]" class="form-control span12" placeholder="********" required="" autofocus="">		
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="row-fluid">
        		<div class="span12">
        			<div class="control-group horizontal">
        				<label for="pass2">Repetir Nueva Contraseña</label>
        				<div class="control">
        					<input type="password" id="pass2" name="data[Postulante][pass_confirm]" class="form-control span12" placeholder="********" required="">		
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="row-fluid">
        		<div class="span12">
        			<button class="btn btn-lg btn-primary btn-block" type="submit">Restablecer Contraseña</button>
        		</div>
        	</div>
            </form>
            <div class="row-fluid">
            	<div class="span12 olvido-pass">
            		<a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'postulante')); ?>" class="text-center new-account">Volver al Ingreso</a>
            	</div>
            </div>
        </div>
    </div>
 </div>
 <input type="hidden" value="3" id="aux-foto" />
 <script type="text/javascript">
 	$(function(){
		timer = setInterval(cambiarFoto,2000);
		//$('#modal-video').modal('show');
	});
	function cambiarFoto(){
		var foto_actual = $('#aux-foto').val();
		switch(foto_actual){
			case '1':
				$('#imagen-rap-1').hide();
				$('#imagen-rap-2').show();
			break;
			case '2':
				$('#imagen-rap-2').hide();
				$('#imagen-rap-3').show();		
			break;
			case '3':
				$('#imagen-rap-3').hide();
				$('#imagen-rap-1').show();
				foto_actual = 0;
			break;
		}
		foto_actual++;
		$('#aux-foto').val(foto_actual);
	}
 </script>
 