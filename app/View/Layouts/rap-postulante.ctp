<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Postulantes :: PortalRap</title>
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
			echo $this->Html->script('modernizr.js');
			echo $this->Html->scriptBlock("var webroot ='{$this->webroot}'");
			//echo $this->Html->css('media-querys.css');
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,700,300' rel='stylesheet' type='text/css'>
		<style>
			body { 
				font-family: 'Open Sans Condensed', sans-serif;
				color: #666;
				background-color:#E2E2E2; 
			}
			input,select,textarea{
				font-family: 'Open Sans Condensed', sans-serif;
				color: #666;
			}
			.top{
				background-color:#002d56;
				height:5px;
			}
			.menu-home{
				text-align:right;
				height:60px;
				
			}
			.content-menu{
				width:98% !important;
				margin-left:1% !important;
			}
			.menu-home ul{margin-top:10px;}
			.menu-home ul,.menu-home li{
				list-style:none;
				display:inline-block;
				padding: 0;
				list-style-type: none;
			}
			
			.menu-home li *{
				display:inline-block;
			}
			.menu-home li a{
				margin-left:30px;
			}
			.li-menu{
				height:30px;
				width:40px;
				text-align:center;
				padding-top:10px;
				margin:0;
				font-size:24px;
				float:left;
			}
			.li-menu-texto:hover{
				background-color:#002d56;
				color:#fff;	
			}
			
			.li-menu-texto{
				
				height:30px;
				padding:10px 20px 0 20px ;
				font-weight:700;
				background-color:#cac9c9;
				color:#012c56;
			}
			.li-menu-red{
				color: #fff;
				background-color: #b94a48;
				border-color: #eed3d7;
			}
			.li-menu-green{
				color:  #fff;
				background-color: #468847;
				border-color: #d6e9c6;
			}
			.li-menu-orange{
				color:  #fff;
				background-color: #f39c12;
				border-color: #f39c12;
			}
			.body-content{
				min-height:600px;
				/*background:url('<?php echo $this->Html->url('/img/duoc-valpo.jpg');?>') no-repeat left top #002d56;*/
				margin-bottom:50px;
				background-color:#fff;
				-moz-border-radius: 10px;
			    -webkit-border-radius: 10px;
			    border-radius: 10px;
				border-bottom:4px solid #002d56;border-top:4px solid #002d56;
				
			}
			
			footer{
				background: #012c56;
				border-top: 5px solid #fbb32b;
				color:#fff;
			}
			
			footer a{
				color:#fff;
			}
			
			.welcome{
				background-color: #0094ab;
				color:#fff;
				margin-top:20px;
				padding:10px 10px 10px 15px;
			}
			
			.btn-registrarse{
				background: #f39c12;
				font-size: 18px;
				padding: 17px 25px;
				color: #FFF;
				text-shadow: 1px 1px 0 rgba(0,0,0,.3);
				font-weight: bold;
			}
			.btn-registrarse:hover{
				background-color:#012c56;
				color:#fff;
			}
			.content-login{
				background-color:#fff;
			    -moz-border-radius: 20px;
			    -webkit-border-radius: 20px;
			    border-radius: 20px;	
			}
			.legend-rap{
				text-align:right;
				margin-top:15px;
			}
			.legend-rap h3{
				line-height:0;
				font-size:27px;
				padding:0;
				position:relative;
			}
			.mensajes{
				margin-top:10px;
			}
			.header{
				margin:10px 0;
			}
			
		</style>
	</head>
	<body>
		<aside class="top"></aside>
		<div class="container">
			<header class="row-fluid header">
				<div class="span3">
					<?php echo $this->Html->image('logo-duoc.png');?>
				</div>
				<div class="span9 legend-rap">
					<h3>Portal de Reconocimiento de Aprendizajes Previos (RAP)</h3>
				</div>
			</header>
			<br/>
			<div class="row-fluid body-content">
				<div class="span12">
					<div class="row-fluid content-menu">
						<div class="span12 menu-home">
							<ul>
								<li><a href="<?php echo $this->Html->url(array('controller'=>'home','action'=>'postulantes'))?>"><div class="li-menu li-menu-orange"><i class="icon-copy"></i></div><div class="li-menu-texto"> MiS Postulaciones</div></a></li> 
								<li><a href="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'updateData')); ?>"><div class="li-menu li-menu-orange"><i class="icon-user"></i></div> <div class="li-menu-texto">Mis Datos</div></a></li>
								<li><a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'logout')); ?>"><div class="li-menu li-menu-orange"><i class="icon-power-off"></i></div><div class="li-menu-texto"> Salir de RAP</div></a></li>
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
		<footer>
			<br/>
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
