<style type="text/css">

</style>
<div class="row-fluid content-login">
	<div class="span12 login-well">
        <div class="well login row-fluid">
        	<form class="form-signin" action="<?php echo $this->Html->url(array('controller'=>'login','action'=>'administrativo')); ?>" method="POST">
        	<div class="row-fluid">
	        	<div class="span12">
	        		<h2>Si ya posee una cuenta, ingrese sus credenciales acá</h2>
	        	</div>
	        </div>
        	<div class="row-fluid">
        		<div class="span3">
        			<img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt="">
        		</div>
        		<div class="span9 ">
        			<input type="text" name="data[Administrativo][nombre_usuario]" class="form-control span12" placeholder="Nombre de usuario" required="" autofocus="">
	            	<input type="password" name="data[Administrativo][pass]" class="form-control span12" placeholder="Password" required="">
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
            		
            	</div>
            </div>
        </div>
    </div>
 </div>
 