

        <div class="well login row-fluid">
        	<form class="form-signin" action="<?php echo $this->Html->url(array('controller'=>'login','action'=>'identificar')); ?>" method="POST">
        	<div class="row-fluid">
	        	<div class="span12">
	        		<h2>Restablecer Contraseña</h2>
	        	</div>
        	</div>
        	<div class="row-fluid">
        		<div class="span12">
        			<div class="control-group horizontal">
        				<label for="form-field-input-correo">Ingrese su email</label>
        				<div class="control">
        					<input type="text" id="form-field-input-correo" name="data[Postulante][correo]" class="form-control span12" placeholder="Email" required="" autofocus="">		
        				</div>
        			</div>
        		</div>
        	</div>
        	<div class="row-fluid">
        		<div class="span12">
        			<button class="btn btn-lg btn-primary btn-block" type="submit">Restablecer</button>
        		</div>
        	</div>
            </form>
            <div class="row-fluid">
            	<div class="span12 olvido-pass">
            		<a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'postulante')); ?>" class="text-center new-account">Volver al Ingreso</a>
            	</div>
            </div>
        </div>
