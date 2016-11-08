<!DOCTYPE html>
<html>
	<head>
		<!--<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">-->
		<title>DUOC - Portal de Admisiones Especiales - Administrativos</title>
		<meta http-equiv="X-UA-Compatible" content="IE=9">
		<?php
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
			    background:url("../img/bg-body.png") left top #fff;
			    
			}
			.top{
				background-color:#002d56;
				height:5px;
			}
			.menu-home{
				text-align:right;
				height:60px;
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
			.menu-home li{
				
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
				min-height:900px;
				background:url('<?php echo $this->Html->url('/img/duoc-valpo.jpg');?>') no-repeat left top #002d56;
				margin-bottom:50px;
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
					
			}
		</style>
	</head>
	<body>
		<aside class="top"></aside>
		<br/>
		<div class="container body-contaniner">
			<header class="row-fluid">
				<!-- logo y menu -->​
				<div class="span3">
					<?php echo $this->Html->image('logo-duoc.png');?>
				</div>
				<div class="span9 menu-home">
					<ul>
						<li><a href="<?php echo $this->Html->url('/')?>"><div class="li-menu li-menu-green"><i class="icon-home"></i></div><div class="li-menu-texto"> Home RAP</div></a></li>
					</ul>
				</div>
			</header><br/>
			<div class="row-fluid">
				<div class="span12 content-home">
					<div class="row-fluid">
						<div class="span7 registrate">
							<div class="row-fluid">
								<div class="span12 welcome">
									<h2>Portal de Reconocimiento de Aprendizajes Previos</h2>
								</div>
							</div>
							<br/><br/><br/><br/><br/><br/>
						</div>
						<div class="offset1 span4 content-login">
							<?php echo $this->fetch('content'); ?>
							<?php echo $this->Session->flash(); ?>
						</div>						
					</div>
				</div>
			</div>
		</div>
		<br/><br/><br/><br/><br/><br/>
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
