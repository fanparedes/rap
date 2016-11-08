<?php 
	#VARIABLES QUE RECIBE EL WIZARD:
	#	codigo de la postulacion
	#	resumen
	
	$codigo_postulacion = (empty($cod_postulacion))? '':$cod_postulacion;
	$urlPreguntasBasicas = $this->Html->url(array('controller'=>'postulaciones','action'=>'completarPostulacion',$codigo_postulacion));
	$urlCvRap = $this->Html->url(array('controller'=>'postulaciones','action'=>'CvRap',$codigo_postulacion));
	$urlAgenda= $this->Html->url(array('controller'=>'entrevistas','action'=>'agendaPostulante',$codigo_postulacion));
	$urlCargaDocumentos = $this->Html->url(array('controller'=>'postulaciones','action'=>'cargaDocumentos',$codigo_postulacion));
	$urlAutoEvaluacion = $this->Html->url(array('controller'=>'postulaciones','action'=>'autoEvaluacion',$codigo_postulacion));
	$urlEvidenciasprevias = $this->Html->url(array('controller'=>'postulaciones','action'=>'evidenciasprevias',$codigo_postulacion));
	$urlEvidenciasfinales = $this->Html->url(array('controller'=>'postulaciones','action'=>'evidenciasfinales',$codigo_postulacion));
	$urlRespuesta = $this->Html->url(array('controller'=>'postulaciones','action'=>'respuesta',$codigo_postulacion));
	$action = $this->params['action'];
	$estado = (empty($resumen['estado']['codigo']))? 0 : (int)$resumen['estado']['codigo'];	
?>
<style type="text/css" media="screen">
	.border{
		border:1px solid navy;
	}
	.titulo-formulario{
		border-left :1px solid #E2E2E2;
		border-right: 1px solid #E2E2E2;
	}
	.element-wizard{
		text-align:center;
		border:1px solid #666;
		/*border-top-left-radius: 30px;
		border-bottom-right-radius: 30px;*/
		padding:3px;
		color:#666;
	}
	.element-wizard:hover{
		background-color:#f39c12;
		color:white;
		cursor:pointer;
	}
	.disabled:hover{
		background-color:#E2E2E2;
		color:#012c56;	
		cursor:default;
	}
	.title-element-wizard{
		font-size:12px;
		margin-top:10%;		
	}
	.wizard{
		padding:10px;
		background-color: #E2E2E2;
	}
	.active{
		background-color:#012c56 !important;
		color:white !important;
	}
	.numero h1{
		font-size:46px;		
	}
	.numero2 h1{
		font-size:40px;
		margin-bottom: 0px;
		padding-top: 2px;
		
	}
	.span2-5{
		width:10.1% !important;
	}
	.content-wizard{
		width:98%;
		margin-left:1%;
	}
	.resumen{
		border:1px solid #E2E2E2;
		width:97.8%;
		padding-top:10px;
		margin-left:1%;
	}
	.hr{
		margin-bottom: 1px solid #E2E2E2;
	}
</style>
<?php if(!empty($resumen)): ?>
	<div class="row-fluid resumen ">
		<div class="span12">
			<div class="row-fluid hr">
				<div class="span6">
					<div class="row-fluid">
						<div class="span3 offset1 ">
							<p style="margin-top:10px;">Carrera a la que postula</p>
						</div>
						<div class="span7 ">
							<h4><?php echo ($resumen['carrera']); ?></h4>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="row-fluid">
						<div class="span5 offset1">
							<div class="row-fluid">
								<div class="span3">
									<p style="margin-top:10px;">Sede</p>
								</div>
								<div class="span9">
									<h4><?php echo ($resumen['sede']); ?></h4><!--PHP-->
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="row-fluid">
								<div class="span3">
									<p style="margin-top:10px;">Jornada</p>
								</div>
								<div class="span9">
									<h4><?php echo $resumen['jornada']; ?></h4><!--PHP-->
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="row-fluid">
						<div class="span3 offset1 ">
							<p style="margin-top:10px; ">Estado</p>
						</div>
						<div class="span7 ">
							<h4><?php 	
								$resumen2 = $resumen;
								//echo var_dump($resumen['habilitado']);
								if ((isset($resumen2['habilitado']))){									
									if ($resumen2['habilitado'] == null){
										echo 'PENDIENTE DE REVISAR EVIDENCIAS FINALES';									
									}
									if ($resumen2['habilitado'] == '1'){
										if (isset($archivo_firma)){
											echo 'HABILITADO CON FIRMA';		
										}
										else{
											echo 'HABILITADO';
										}
									}
									if ($resumen2['habilitado'] == '0'){
										echo 'NO HABILITADO';									
									}
								}						
								else {
									if (empty($resumen2['estado']['nombre'])){
										echo 'PENDIENTE DE RELLENAR FORMULARIO DE POSTULACIÓN';
									}
									else{
										echo $resumen2['estado']['nombre']; 		
									}										
								}
							?>							
							</h4>
						</div>
					</div>
				</div>
				<?php if(!empty($resumen['estado']['descripcion'])): ?>
					<?php 
						$class_alert = ((int)$resumen['estado']['codigo'] === 9 )? 'success':'info';
					?>
					<div class="span6 ">
						<div class="row-fluid">
							<div class="span10 offset1 alert alert-<?php echo $class_alert; ?>" style="margin-top:10px;">
								<h5><?php echo ($resumen['estado']['descripcion']); ?></h5>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<br>
<div class="row-fluid content-wizard">
	<div class="span12 wizard">
		<div class="row-fluid">
			<div class="span2-5 element-wizard <?php echo ($action=='completarPostulacion')? 'active':''; ?>" action="<?php echo ($action!='completarPostulacion')? $urlPreguntasBasicas:''; ?>">
				<div class="row-fluid">
					<div class="span1 offset1 numero" >
						<h1>1</h1>
					</div>
					<div class="span10">
						<div class="row-fluid">
							<div class="span12 title-element-wizard" >
								Formulario de Postulación
							</div>
						</div>
					</div>
				</div>
			</div>		
			<div  class="span2-5 element-wizard <?php if ($estado == null): echo 'disabled' ; endif;  ?><?php if ($estado < 1) : echo ' disabled '; endif; ?><?php echo ($action=='cargaDocumentos')? 'active':''; ?>"	
			action="<?php echo ($action != 'cargaDocumentos')? $urlCargaDocumentos:''; ?>" <?php if ($resumen['estado']['codigo'] == null): echo 'disabled'; endif; ?>>
				<div class="row-fluid">
					<div class="span1 offset1 numero" >
						<h1>2</h1>
					</div>
					<div class="span10">
						<div class="row-fluid">
							<div class="span12 title-element-wizard" >
								Documentación
							</div>
						</div>
					</div>
				</div>
			</div><?php $action_lower = strtolower($action); ?>
			<div class="span2-5 element-wizard  <?php echo ($action_lower == 'cvrap')? 'active':''; ?> <?php echo ($estado>=3)? '':'disabled'; ?>" action="<?php echo ($action_lower != 'cvrap')? $urlCvRap:''; ?>">
				<div class="row-fluid">
					<div class="span1 offset1 numero" >
						<h1>3</h1>
					</div>
					<div class="span10">
						<div class="row-fluid">
							<div class="span12 title-element-wizard" >
								CV Rap
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span2-5 element-wizard <?php echo ($action=='autoEvaluacion')? 'active':''; ?> <?php echo ($estado>=4)? '':'disabled'; ?>" action="<?php echo ($action!='autoEvaluacion')? $urlAutoEvaluacion:''; ?>">
				<div class="row-fluid">
					<div class="span1 offset1 numero" >
						<h1>4</h1>
					</div>
					<div class="span7 offset2">
						<div class="row-fluid">
							<div class="span11 title-element-wizard" style="font-size:13.2px!important;" >
								Autoevaluación
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span2-5 element-wizard <?php echo ($action=='evidenciasprevias')? 'active':''; ?> <?php echo ($estado >= 6)? '':'disabled'; ?>" action="<?php echo ($action!='evidenciasprevias')? $urlEvidenciasprevias:''; ?>">
				<div class="row-fluid">
					<div class="span2 offset1 numero">
						<h1>5</h1>
					</div>
					<div class="span9">
						<div class="row-fluid">
							<div class="span12 title-element-wizard" style="font-size:12.2px!important;" >
								Informe Evidencias Preliminar 
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span2-5 element-wizard <?php echo ($action=='agendaPostulante')? 'active':''; ?> <?php if (($estado<6) || (isset($validado)) && ($validado == false)) { echo ' '.'disabled';  } 
			?>" action="<?php echo ($action!='agendaPostulante')? $urlAgenda:''; ?>">
				<div class="row-fluid">
					<div class="span2 offset1 numero">
						<h1>6</h1>
					</div>
					<div class="span9">
						<div class="row-fluid">
							<div class="span12 title-element-wizard" >
								Agendar Entrevista
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if((isset($datos_entrevista)) && (! empty($datos_entrevista)) && (strcmp($datos_entrevista['Entrevista']['estado'],'ACTIVO')  == 0)) :?>
			<div class="span2-5 element-wizard <?php echo ($action=='evidenciasfinales')? 'active':''; ?> <?php echo ($resumen['entrevista'] =='REALIZADO' )? '':'disabled'; ?>" action="<?php echo ($action!='evidenciasfinales')? $urlEvidenciasfinales:''; ?>">
			<?php else :?>
			<div class="span2-5 element-wizard <?php echo ($action=='evidenciasfinales')? 'active':''; ?> <?php echo ($estado >7)? '':'disabled'; ?> " action="<?php echo ($action!='evidenciasfinales')? $urlEvidenciasfinales:''; ?>">
			<?php endif ;?>
			
				<div class="row-fluid">
					<div class="span2 offset1 numero">
						<h1>7</h1>
					</div>
					<div class="span9">
						<div class="row-fluid">
							<div class="span12 title-element-wizard" >
								Informe Evidencias Final
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- RESPUESTA -->
			<?php if((isset($validado_final)) && (($validado_final) == true)) :?>
				<div class="span2-5 element-wizard <?php echo ($action=='respuesta')? 'active':''; ?>" action="<?php echo $urlRespuesta.''; ?>">
			<?php else :?>
				<div class="span2-5 element-wizard <?php echo ($action=='respuesta')? 'active':''; ?> <?php echo ($estado >7)? '':'disabled'; ?>   <?php if (!isset($validado_final) || ($validado_final == false)): echo ' disabled '; endif; ?>" action="<?php echo ($action!='evidenciasfinales')? $urlRespuesta:''; ?>">
			<?php endif ;?>
				<div class="row-fluid">
					<div class="span2 offset1 numero">
						<h1>8</h1>
					</div>
					<div class="span9">
						<div class="row-fluid">
							<div class="span12 title-element-wizard" >
								Respuesta Final
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- FIN DE RESPUESTA -->
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		bloqueo();
		<?php if(!empty($codigo_postulacion)): ?>
			$('.element-wizard').each(function(){
				$(this).on('click',function(){
					if($(this).hasClass('disabled')){
						return;	
					}else{
						var action = $(this).attr('action');
						
						if(action!='' && action != 'false')
						{
							window.location = action;	
						}else{
							return false;
						}	
					}
				});
			});
		<?php endif; ?>
	});
	
	function bloqueo()
	{
		$(document).keydown(function(tecla){ 
				if (tecla.keyCode == 123) { 
					return false;
				} 
			});
		$('.element-wizard').each(function(){
				if($(this).hasClass('disabled')){
					$(this).attr('action', false);
				}
			});
    } 
	
</script>