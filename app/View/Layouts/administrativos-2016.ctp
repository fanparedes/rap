<!DOCTYPE html>
<html>
	<head>
		<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">-->
		<title>DUOC - Portal de Admisiones Especiales - BACKEND</title>
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=9">
		<?php
			$user = $this->Session->read('UserLogued');
			echo $this->Html->meta('icon');
			echo $this->Html->css('bootstrap.css');
			echo $this->Html->css('jquery-ui.css');
			echo $this->Html->css('jquery-ui.structure.css');
			echo $this->Html->css('jquery-ui.theme.css');
			
			echo $this->Html->css('bootstrap-responsive.min.css');
			echo $this->Html->css('style_paginate.css');
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('bootstrap.min.js');
			echo $this->Html->script('jquery-ui.js');
			echo $this->Html->script('typeahead.js');
			echo $this->Html->script('graficos/highcharts.js');
			echo $this->Html->script('graficos/modules/exporting.js');
			echo $this->Html->script('jquery.PrintArea.js');			
			echo $this->Html->script('jquery.paginate.js');			
			
			echo $this->Html->script('mensajes.js');
			echo $this->Html->css('mensajes.css');
			echo $this->Html->css('style-admin-2016.css');
			//echo $this->Html->css('stylesadmin.css');
			echo $this->Html->script('modernizr.js');
			//echo $this->Html->css('media-querys.css');
			echo $this->Html->scriptBlock("var webroot ='{$this->webroot}';");
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');	

		?>
				
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,700,300' rel='stylesheet' type='text/css'>
		<!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
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
		<div class="page-wrap">
		<a name="arriba"></a>
			<header>
				<div class="row-fluid">
						<div id="header" class="">
							<?php echo $this->Html->image('logo.png');?>
						</div>
						<div class="menu-home">
							<div class="container">
								<div class="span12">
									<?php echo $this->requestAction('/login/home/1',array('return')); ?>
								</div>
							</div>
						</div>
				</div>	
				</header>
				<div class="clearfix"></div>
				<div class="row">
					<div class="container">
						<div class="row-fluid body-content">
							<div class="span12">
								<div class="row-fluid">
									<div class="span12">
										<div class="span10 offset1">
											<br>
											<?php echo $this->Session->flash(); ?>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span12">
										<?php echo $this->fetch('content'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>		
		<footer class="site-footer">
			<div class="row">
				<?php echo $this->Html->image('acreditado.png');?>
			</div>
		</footer>
		<?php echo $this->element('sql_dump'); ?>
	</body>
</html>