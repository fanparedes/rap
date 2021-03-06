<style type="text/css">
	.login h2{
		font-size:1.2em;
		margin:10px 0;
		padding:0;
		line-height:25px;
	}
	
	.profile-img{
	    width: 65px;
	    height: 65px;
	    margin: 0 auto 10px;
	    display: block;
	    -moz-border-radius: 50%;
	    -webkit-border-radius: 50%;
	    border-radius: 50%;
	}
	.login-well{
		margin-left:12px !important;
		margin-top:15px !important;
	}
	.olvido-pass{
		text-align:right;
	}
	
</style>
 <div class="row-fluid content-login">
	<div class="span10 login-well">
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
    </div>
 </div>
 