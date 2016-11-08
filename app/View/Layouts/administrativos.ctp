<!DOCTYPE html>
<html lang="es">
	<head>
	
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=9">
		<title>Administrativos :: PortalRap</title>
		<?php
			$user = $this->Session->read('UserLogued');
			echo $this->Html->meta('icon');
			echo $this->Html->css('bootstrap.css');
			echo $this->Html->css('jquery-ui.css');
			echo $this->Html->css('jquery-ui.structure.css');
			echo $this->Html->css('jquery-ui.theme.css');
			
			echo $this->Html->css('bootstrap-responsive.min.css');
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('bootstrap.min.js');
			echo $this->Html->script('jquery-ui.js');
			echo $this->Html->script('typeahead.js');
			echo $this->Html->script('graficos/highcharts.js');
			echo $this->Html->script('graficos/modules/exporting.js');
			echo $this->Html->script('jquery.PrintArea.js');
			
			echo $this->Html->script('mensajes.js');
			echo $this->Html->css('mensajes.css');
			echo $this->Html->css('stylesadmin.css');
			echo $this->Html->script('modernizr.js');
			//echo $this->Html->css('media-querys.css');
			echo $this->Html->scriptBlock("var webroot ='{$this->webroot}';");
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,700,300' rel='stylesheet' type='text/css'>
		
		<script>
		function patron(campo){			
			console.log(campo.value);
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
	</head>
	<body>
		<div class="container">					
			<div class="row-fluid">
				<div class="span12">					
					<div class="row-fluid">
					  <div class="span2 logo">
						<?php echo $this->Html->image('logo-rap-grande-v2-sin-fondo.png',array('class'=>'logo-rap'));?>
					  </div>
					  <div class="span10 menu-home">
						<?php 
							echo $this->requestAction('/login/home/1',array('return')); 							
						?>				
					  </div>
					</div>
				</div>
				
			</div>
			<div class="row-fluid">	

			</div>
			<div class="row-fluid body-content">
				<div class="span10 offset1">
					<?php echo $this->Session->flash(); ?>
				</div>			
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
		<footer>
		<div class="container footer-inner">  
				<div class="footer-in row-fluid">
					<div class="span5">
						<?php echo $this->Html->image('logo-mineduc.jpg',array('div'=>false,'alt'=>'Ministerio de Educacion')); ?>
					</div>
				  	<div class="span7" align="right">
				  		<?php echo $this->Html->image('logo-acreditado-footer.png',array('div'=>false,'alt'=>'Acreditado 7 años IP - 6 años CFT')); ?>
				  	</div>
				</div>
			</div>
			<br/>&nbsp;
		</footer>

<?php echo $this->element('sql_dump'); ?>


			
	</body>
</html>

				