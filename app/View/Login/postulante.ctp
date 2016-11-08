<style type="text/css">

</style>
<div >
    <div class="login-well">
        <div class="well login row-fluid">
        	<form class="form-signin" action="<?php echo $this->Html->url(array('controller'=>'login','action'=>'postulante')); ?>" method="POST">
        	<div class="row-fluid">
	        	<div class="span12">
	        		<h2>Si ya posee una cuenta, ingrese sus credenciales acá</h2>
	        	</div>
	        </div>
        	<div class="row-fluid">
        		<div class="span3">
        			<?php echo $this->Html->image('img-login.png',array('class'=>'profile-img')); ?>
        		</div>
        		<div class="span9 ">
        			<input type="text" name="data[Postulante][nombre_usuario]" class="form-control span12" placeholder="Email" required="" autofocus="">
	            	<input type="password" name="data[Postulante][pass]" class="form-control span12" placeholder="Password" required="">
        		</div>
        	</div>
        	<div class="row-fluid">
        		<div class="span12">
        			<button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar a Mi Cuenta</button>
        		</div>
        	</div>
            </form>
            <div class="row-fluid">
            	<div class="span12 olvido-pass">
            		<a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'identificar')); ?>" class="text-center new-account">¿ Ha olvidado su contraseña ?</a>
            	</div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
		<div class="span12" style="text-align: center;">
			<a href="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'nueva')); ?>" class="btn btn-registrarse" id="boton-registro">Para Postular Regístrese Aquí</a>
		</div>
	</div>
</div>