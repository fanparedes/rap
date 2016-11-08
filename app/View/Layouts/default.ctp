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
			echo $this->Html->css('bootstrap-responsive.min.css');
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('bootstrap.min.js');
			echo $this->Html->script('mensajes.js');
			echo $this->Html->css('mensajes.css');
			echo $this->Html->css('media-querys.css');
			echo $this->Html->script('modernizr.js');
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
			echo $this->Html->css('normalize.css');
			echo $this->Html->css('style2016.css');
			$action = $this->request->params['action'];
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
						<?php echo $this->Session->flash(); ?>
						<?php echo $this->fetch('content'); ?>	
					</div>
				</div>
		</div>		
		<footer class="site-footer">
			<div class="row">
				<?php echo $this->Html->image('acreditado.png');?>
			</div>
		</footer>
	</body>
</html>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5909572-1']);
  _gaq.push(['_setDomainName', 'duoc.cl']);
  _gaq.push(['_trackPageview']);
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
 
</script>
