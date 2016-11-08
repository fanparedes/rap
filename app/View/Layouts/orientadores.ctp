<!DOCTYPE html>
<html>
	<head>
		<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">-->
		<title>Orientadores :: PortalRap</title>
		<meta http-equiv="X-UA-Compatible" content="IE=9">
		<?php
			$user = $this->Session->read('UserLogued');
			echo $this->Html->meta('icon');
			echo $this->Html->css('bootstrap.css');
			echo $this->Html->css('bootstrap-responsive.min.css');
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('bootstrap.min.js');
			echo $this->Html->script('mensajes.js');
			echo $this->Html->css('mensajes.css');
			echo $this->Html->script('modernizr.js');
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
				background-color: #E2E2E2;
			}
			.top{
				background-color:#002d56;
				height:5px;
			}
			.menu-home{
				text-align:right;
				height:60px;
			}
			.menu-home #menu-full{}
			.menu-home #menu-full,.menu-home #menu-full #prin,.menu-home #menu-full #dro{
				list-style:none;
				display:inline-block;
				padding: 0;
				list-style-type: none;
			}
			
			.menu-home #menu-full #prin *{
				display:inline-block;
			}
			.menu-home #menu-full #prin a{
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
				width:auto;
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
			
			
			.content-login{
					
			}
			
			.dropdown-menu{
				left: inherit;
				top:inherit;
				background-color: #cac9c9;
				
			}
			
			.dropdown-menu li{
				display: block;
				
			}
			.dropdown-menu li a{ 
				color: #012c56;
				font-weight: 700;
			}
			
			#dro{
				text-align:center;
				min-width:170px;
				width:auto;
				vertical-align:top;
				margin-left:30px;
			}
			
			.caret{
				margin-top: -20px;
				margin-left: 150px;
				float:left;
			}
			
			.body-content{
				background: inherit;
				background-color: white;
				padding: 10px 5px;
				height: auto;
			}
			
			.dropdown-menu li:hover{
				background-color: red;
			}
			.dropdown-menu{
				 min-width: 169px;
			}
			#dro a{
				text-decoration:none;
			}
			#dro{
				margin-right:-10px;
			}
		</style>
	</head>
	<body>
		<aside class="top"></aside>
		<br/>
		<div class="container">
			<div class="row-fluid">
				<header class="row-fluid">
				<div class="span2">
					<?php echo $this->Html->image('logo-rap-grande-v2-sin-fondo.png',array('class'=>'logo-rap'));?>
				</div>
				<div class="span10 content-home">
					<div class="span12 menu-home" style="margin-top: 30px;">
						<ul id="menu-full">
							<li id="prin">
								<a href="<?php echo $this->Html->url(array('controller'=>'orientadores','action'=>'index')); ?>">
									<div class="li-menu li-menu-orange"><i class="icon-calendar"></i></div><div class="li-menu-texto">Agenda</div>
								</a>
							</li>
							<li id="dro">
								<a data-toggle="dropdown" href="#">
									<div class="li-menu li-menu-orange">
										<i class="icon-user"></i>
									</div>
									<div class="li-menu-texto"><?php echo substr($user['Administrativo']['nombre'],0,15); ?></div>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'logout_administrativos')); ?>">
											<div class="li-menu-drop li-menu-drop-orange">
												<i class="icon-power-off"></i>
											</div>
											<div class="li-menu-drop-texto">Salir</div>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
				</header>
			</div>
			<div class="container body-content">
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>			
		</div>
		<br/>
		<br />
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
			<script>
				
			</script>
	</body>
</html>
