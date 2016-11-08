<style type="text/css">
	.btn-registrarse{
		background: #f39c12;
		font-size: 18px;
		padding: 17px 25px;
		color: #FFF;
		margin-left:58px;
		text-shadow: 1px 1px 0 rgba(0,0,0,.3);
		font-weight: bold;
	}
	
	.btn-registrarse:hover{
		background-color:#012c56;
		color:#fff;
	}
	.content-login{
		width: 23.3%;
		position: absolute;
		top: 107px;
		left: 65.6%;
	}
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
<a href="<?php echo $this->webroot.'web/queEsRap'; ?>"><div>
	<?php echo $this->Html->image('1.jpg',array('id'=>'imagen-rap-1','div'=>false)); ?>
	<?php echo $this->Html->image('2.jpg',array('id'=>'imagen-rap-2','div'=>false,'style'=>'display:none;')); ?>
	<?php echo $this->Html->image('3.jpg',array('id'=>'imagen-rap-3','div'=>false,'style'=>'display:none;')); ?>

</div></a>
<div class="content-login">
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
		<div class="span12">
			<a href="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'nueva')); ?>" class="btn btn-registrarse">Para Postular Regístrese Aquí</a>
		</div>
	</div>
</div>
<input type="hidden" value="3" id="aux-foto" />
<script type="text/javascript">
 	$(function(){
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
		timer = setInterval(cambiarFoto,5000);
	});
 </script>