<style type="text/css">
	/*.form-signin{
	    max-width: 330px;
	    padding: 15px;
	    margin: 0 auto;
	}
	.form-signin .form-signin-heading, .form-signin .checkbox{
	    margin-bottom: 10px;
	}
	.form-signin .checkbox{
	    font-weight: normal;
	}
	.form-signin .form-control{
	    position: relative;
	    font-size: 16px;
	    height: auto;
	    padding: 10px;
	    -webkit-box-sizing: border-box;
	    -moz-box-sizing: border-box;
	    box-sizing: border-box;
	}
	.form-signin .form-control:focus{
	    z-index: 2;
	}
	.form-signin input[type="text"]{
	    margin-bottom: -1px;
	    border-bottom-left-radius: 0;
	    border-bottom-right-radius: 0;
	}
	.form-signin input[type="password"]{
	    margin-bottom: 10px;
	    border-top-left-radius: 0;
	    border-top-right-radius: 0;
	}
	.account-wall{
	    margin-top: 20px;
	    padding: 40px 0px 20px 0px;
	    background-color: #f7f7f7;
	    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	}
	.login-title{
	    color: #555;
	    font-size: 28px;
	    font-weight: 400;
	    display: block;
	}
	
	.need-help{
	    margin-top: 10px;
	}
	.new-account{
	    display: block;
	    margin-top: 10px;
	}    
	.offset3{
		margin-left:350px !important;
	}*/
	
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
        	<form class="form-signin" action="<?php echo $this->Html->url(array('controller'=>'login','action'=>'postulante')); ?>" method="POST">
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
 </div>
 