<!DOCTYPE >
<html>
	<head>
		<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">-->
		<title>DUOC - Portal de Admisiones Especiales - Login</title>
		<meta http-equiv="X-UA-Compatible" content="IE=9">
		<?php
			echo $this->Html->meta('icon');
			echo $this->Html->css('bootstrap.min.css');
			echo $this->Html->css('bootstrap-responsive.min.css');
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('bootstrap.min.js');
			echo $this->Html->css('media-querys.css');
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
	</head>
	<body>
		<div class="container">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<?php echo $this->element('sql_dump'); ?>
	</body>
</html>	