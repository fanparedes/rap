<!DOCTYPE html>
<html>
	<head>
		<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">-->
		<title>DUOC - Portal de Admisiones Especiales</title>
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=9">
		<?php
			echo $this->Html->meta('icon');
			echo $this->Html->css('bootstrap.css');
			echo $this->Html->css('postulante.css');
			echo $this->Html->css('bootstrap-responsive.min.css');
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('bootstrap.min.js');
			echo $this->Html->script('typeahead.js');
			echo $this->Html->script('script.js');
			echo $this->Html->script('jquery.validCampo.js');
			echo $this->Html->script('mensajes.js');
			echo $this->Html->css('mensajes.css');
			echo $this->Html->css('style-postulante-2016.css');
			echo $this->Html->script('modernizr.js');
			echo $this->Html->scriptBlock("var webroot ='{$this->webroot}'");
			//echo $this->Html->css('media-querys.css');
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
				
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,700,300' rel='stylesheet' type='text/css'>
		<!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
	</head>
	<body>
		<div class="page-wrap">
			<header>
				<div class="row-fluid">
						<div id="header" class="">
							<?php echo $this->Html->image('logo.png');?>
						</div>
				</div>	
				</header>
				<div class="clearfix"></div>
				<div class="row">
					<div class="container">
						<div class="row-fluid body-content">
							<div class="span12">
								<div class="row-fluid content-menu">
									<div class="span12 menu-home">
										<ul>
											<li><a href="<?php echo $this->Html->url(array('controller'=>'home','action'=>'postulantes'))?>"><div class="li-menu li-menu-orange"><i class="icon-copy"></i></div><div class="li-menu-texto"> Mis Postulaciones</div></a></li> 
											<li><a href="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'updateData')); ?>"><div class="li-menu li-menu-orange"><i class="icon-user"></i></div> <div class="li-menu-texto">Mis Datos</div></a></li>
											<li><a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'logout')); ?>"><div class="li-menu li-menu-orange"><i class="icon-power-off"></i></div><div class="li-menu-texto"> Salir</div></a></li>
										</ul>
									</div>
								</div>
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