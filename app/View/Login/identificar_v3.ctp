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
	.content-login-restablecer{
		width: 26.9%;
		position: absolute;
		top: 107px;
		left: 65.7%;
	}
</style>
<div>
	<?php echo $this->Html->image('1.jpg',array('id'=>'imagen-rap-1','div'=>false,'style'=>'display:none;')); ?>
	<?php echo $this->Html->image('2.jpg',array('id'=>'imagen-rap-2','div'=>false,'style'=>'display:none;')); ?>
	<?php echo $this->Html->image('3.jpg',array('id'=>'imagen-rap-3','div'=>false)); ?>
</div>
<div class="content-login-restablecer">
    <div class="span10 login-well">
        <div class="well login restablecer row-fluid">
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
 