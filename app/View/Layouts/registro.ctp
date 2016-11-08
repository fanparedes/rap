<!DOCTYPE html>
<html>
    <head>
      <!--  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">-->
        <meta http-equiv="X-UA-Compatible" content="IE=9">
        <title>Postulantes :: PortalRap</title>
        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css('bootstrap.css');
        echo $this->Html->css('bootstrap-responsive.min.css');
        echo $this->Html->script('jquery.js');
        echo $this->Html->script('bootstrap.min.js');
        echo $this->Html->script('mensajes.js');
        echo $this->Html->script('modernizr.js');
        echo $this->Html->script('ingenia');
        echo $this->Html->script('jquery.validCampo.js');
        echo $this->Html->css('mensajes.css');
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
                background:url("https://duoc-cdn.s3.amazonaws.com/uploads/20dad7cce579e169dbf25d6f42f48d8e/original/bg-body.png") repeat-x left top #fff;
            }
            .top{
                background-color:#002d56;
                height:5px;
            }
            .menu-home{
                text-align:right;
                margin-top:-20px;
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


                margin-left:20px;
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
                background:url('<?php echo $this->Html->url('/img/rap-inscripcion-03.jpg'); ?>') no-repeat left top #002d56;
				background-size:100% 102%;
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

            .content-login{
                background-color:#fff;	
            }

            .li-menu-blue{
                color:  #fff;
                background-color: navy;
                border-color: navy;
            }
            .video{
                width:100%
                    position:absolute;
                height: 100%;
                top:0% !important;
                left:0% !important;
            }
            .btn-cerrar{
                position: fixed;
                top: 50px;
                color: #ccc;
                font-size: 4em;
                font-family: "Arial";
                left: 95.7%;
                z-index: 1040;
            }
            .btn-cerrar:hover{
                cursor:pointer;
                color:white;
                text-decoration:none;
            }
            .modal{
                left:35% ;
                top:1% ;
            }
            #iframe-video-registro{
                width:900px;
                height:500px;
            }
        </style>
    </head>
    <body>
        <aside class="top"></aside>
        <br/>
        <div class="container">
            <header class="row-fluid">
                <!-- logo y menu -->​
                <div class="span3">
                    <?php echo $this->Html->image('logo-duoc.png'); ?>
                </div>
                <div class="span9 menu-home">
                    <ul>
                        <li><a style="margin-left:0px" href="<?php echo $this->Html->url('/') ?>"><div class="li-menu li-menu-green"><i class="icon-home"></i></div><div class="li-menu-texto"> Home</div></a></li> 
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'web', 'action' => 'preguntasFrecuentes')); ?>"><div class="li-menu li-menu-orange"><i class="icon-question"></i></div> <div class="li-menu-texto">Preguntas Frecuentes</div></a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'web', 'action' => 'queEsRap')) ?>"><div class="li-menu li-menu-red"><i class="icon-file"></i></div><div class="li-menu-texto"> ¿ Que es RAP ? </div></a></li>
                        <li><a href="#modal-registrarse" data-toggle="modal" class="ver-video"><div class="li-menu li-menu-blue"><i class="icon-youtube-play"></i></div><div class="li-menu-texto"> Ver Video</div></a></li>
                    </ul>
                </div>
            </header><br/>
            <div class="row-fluid body-content">
                <div class="span12 content-home">
                    <div class="row-fluid">
                        <div class="span12 registrate">
                            <div class="row-fluid">
                                <div class="span12 welcome">
                                    <h2>Portal de Reconocimiento de Aprendizajes Previos</h2>
                                </div>
                            </div>
                            <br/>
                            <div class="row-fluid">
                                <div class="span8 offset2 content-login">
                                    <?php echo $this->Session->flash(); ?>
                                    <?php echo $this->fetch('content'); ?>
                                </div>
                            </div>
                            <br/><br/>&nbsp;
                        </div>
                        <!--<div class="offset1 span4 ">
                                &nbsp;
                        </div>-->
                    </div>

                </div>
            </div>

        </div>
        <?php
        $direccion_video = $this->webroot;
        $direccion_video .= "files/video_rap.mp4";
        $action = $this->params['action'];
        $controller = $this->params['controller'];
        ?>
        <div class="modal hide fade in" id="modal-registrarse" data-backdrop="static" style="height: 379px;">
            <a class="btn-cerrar" href="<?php echo $this->Html->url(array('controller' => $controller, 'action' => $action)); ?>">&times;</a>
            <!--<video width="1000" style="margin-top:-3.4%;" height="600" id="video"  frameborder="0" allowfullscreen controls>
                    <source src="<?php echo $direccion_video; ?>" type="video/mp4"></source>
            </video>-->
            <iframe  src="https://www.youtube.com/embed/IxBp1hPnMPg?rel=0" id="iframe-video-registro" frameborder="0" allowfullscreen></iframe>
        </div>
        <footer>
            <br/>
            <div class="container footer-inner">  
                <div class="footer-in row-fluid">
                    <div class="span5">
                        <?php echo $this->Html->image('logo-mineduc.jpg', array('div' => false, 'alt' => 'Ministerio de Educacion')); ?>
                    </div>
                    <div class="span7" align="right">
                        <?php echo $this->Html->image('logo-acreditado-footer.png', array('div' => false, 'alt' => 'Acreditado 7 años IP - 6 años CFT')); ?>
                    </div>
                </div>
            </div>
            <br/>&nbsp;
        </footer>
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>
