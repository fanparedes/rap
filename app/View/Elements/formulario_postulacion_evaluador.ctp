<div id="modalRechazar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'cancelarPostulacion', $cod_postulacion))?>">	
	  	<div class="modal-body">
	  		<!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
	  		<div class="row-fluid">
	  			<label class="control-label" for="textarea-rechazo"> Motivo del Rechazo</label>
	  			<textarea id="textarea-rechazo" required class="span12" name="data[Postulacion][motivo_rechazo]"></textarea>
	  		</div>
	  		
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>
<div id="modalDesactivar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<h3 style="text-align: center;">¿Está seguro que desea DESACTIVAR esta Postulación?</h3>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'desactivarPostulacion', $cod_postulacion))?>">	
	  	<div class="modal-body">
	  		<!--<h3 style="text-align: center;">¿Está seguro que desea RECHAZAR esta Postulación?</h3>-->
	  		<div class="row-fluid">
	  			<label class="control-label" for="textarea-rechazo"> Motivo de la Desactivación</label>
	  			<textarea id="textarea-rechazo" required class="span12" name="data[Postulacion][motivo_desactivacion]"></textarea>
	  		</div>
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>

<div id="modalborrarPostulacion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header" id="modal_header">
		<div class="row-fluid">
			<h3 style="text-align: center;">¿Está seguro que desea ELIMINAR esta Postulación?</h3>
			<br>
			<p><strong><i class="icon-warning"></i> Si acepta se borrarán TODOS los datos asociados al postulante y a la postulación.</strong></p>
		</div>
	</div>	
	<form method="POST" action="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'borrarPostulante'))?>">	
	  	<?php echo $this->Form->input('postulante', array ('type' => 'hidden', 'value' => $postulacion['Postulacion']['postulante_codigo'])); ?>
		<div class="modal-body">	  		
		</div>
		<div class="modal-footer">
			<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
			<button type="submit" class="btn btn-primary" id="btn-rechazo" >Aceptar</button>
		</div>
	</form>
</div>



<div id="modalDocumentacion" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
  	<div class="modal-body">
  		<h3 style="text-align: center;">¿Está seguro que desea ACEPTAR la Documentación?</h3>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'aceptarDocumentacion', $cod_postulacion)); ?>">Aceptar</a>
	</div>
</div>
<div id="modalCvRap" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
  	<div class="modal-body">
  		<h3 style="text-align: center;">¿Está seguro que desea ACEPTAR el CvRap y la Autoevaluación?</h3>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'aceptarCvrapAE', $cod_postulacion))?>">Aceptar</a>
	</div>
</div>

<div id="modalDesactivarEtapa" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">		
  	<div class="modal-body">
  		<h3 style="text-align: center;">
			<?php 
				if (($postulacion['Estado']['codigo'] == 8) && (!empty($evidencia_datos2)))	{
					echo '¿Está seguro que desea INVALIDAR las Evidencias finales?';
				}
				if (($postulacion['Estado']['codigo'] == 8) && (empty($evidencia_datos2)))	{
					echo '¿Está seguro que desea ELIMINAR la entrevista agendada del postulante?';
				}
				if (($postulacion['Estado']['codigo'] == 6) && (!empty($evidencia_datos)))	{
					echo '¿Está seguro que desea INVALIDAR las Evidencias Previas?';
				}
				if (($postulacion['Estado']['codigo'] == 6) && (empty($evidencia_datos)))	{
					echo '¿Está seguro que desea INVALIDAR EL CV.RAP y la AUTOEVALUACIÓN?';				
				}
				if ($postulacion['Estado']['codigo'] == 5)	{
					echo '¿Está seguro que desea BORRAR los datos de Autoevaluación del postulante?';				
				}
				if ($postulacion['Estado']['codigo'] == 4)	{
					echo '¿Está seguro que desea CANCELAR el CV.RAP del Postulante?';	
				}
				if ($postulacion['Estado']['codigo'] == 3)	{
					echo '¿Está seguro que desea INVALIDAR la documentación del postulante?';	
				}				
				if ($postulacion['Estado']['codigo'] == 2)	{
					echo '¿Está seguro que desea BORRAR la documentación del postulante?';	
				}
				if ($postulacion['Estado']['codigo'] == 1)	{
					echo '¿Está seguro que desea BORRAR esta postulación?';	
				}

			?>
		</h3>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'desactivaretapa', $cod_postulacion))?>">Aceptar</a>
	</div>
</div>

<div id="modalFinalizar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">		
  	<div class="modal-body">
  		<h3 style="text-align: center;">¿Está seguro que desea FINALIZAR la Postulación?</h3>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" aria-hidden="true" class="btn" href="#">Cerrar</a>
		<a class="btn btn-primary" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'finalizarPostulacion', $cod_postulacion))?>">Aceptar</a>
	</div>
</div>
<style>
.resumen {
    border: 1px solid #E2E2E2;
    margin-left: 1%;
    padding-top: 10px;
    width: 97.8%;
}
.modal-footer{
	background-color:white;
}
.modal-header{
	border-bottom: 0px;
}

.accordion-heading a{
	font-size: 20px;
	font-weight: 700;
	letter-spacing: 1;
}

.accordion-heading a:hover{
	text-decoration: none;
}

p#competencias{
	font-size: 15px;
	font-weight: 400;
}

#comp{
	font-size: 20px;
	margin: 10px;
}

b.resp{
	margin-left: 30px;
	font-weight: 200;
}

</style>
<br/>
<?php //echo var_dump($postulacion);?>
<div class="row-fluid resumen ">
	<div class="span12">
		<div class="row-fluid">
			<div class="span6">
				<div class="row-fluid">
					<div class="span3 offset1">
						<p style="margin-top:10px;">Postulante</p>
					</div>
					<div class="span7">
						<h4><?php echo $postulacion['Postulante']['nombre']; ?></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="row-fluid hr">
			<div class="span6">
				<div class="row-fluid">
					<div class="span3 offset1 ">
						<p style="margin-top:10px;">Carrera a la que postula</p>
					</div>
					<div class="span7 ">
						<h4><?php echo ($postulacion['Carrera']['nombre']); ?></h4>
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
								<h4><?php echo ($postulacion['Sede']['nombre_sede']); ?></h4><!--PHP-->
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="row-fluid">
							<div class="span3">
								<p style="margin-top:10px;">Jornada</p>
							</div>
							<div class="span9">
								<h4><?php echo ($postulacion['Postulacion']['jornada']); ?></h4><!--PHP-->
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
						<p style="margin-top:10px; ">Teléfono</p>
					</div>
					<div class="span7 ">
						<h4><?php echo ($postulacion['Postulante']['telefonomovil']); ?></h4>
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
						<h4><?php echo ($postulacion['Estado']['nombre']); ?></h4>
					</div>
				</div>
			</div>
			<?php if(!empty($postulacion['Estado']['descripcion'])): ?>
				<div class="span6 ">
					<div class="row-fluid">
						<div class="span10 offset1 alert alert-info" style="margin-top:10px;">
							<h5><?php echo ($postulacion['Estado']['descripcion']); ?></h5>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<br/>
<style>
	.accordion-inner h6{
		color:#002d56;
	}
	
	p.respuesta{
		margin-top: -10px;
		margin-left: 10px;
	}
	.accordion {
	    margin-left: 1%;
	    padding-top: 10px;
	    width: 97.8%;
	}
	.accordion-group{
		webkit-border-radius:0px !important;
		-moz-border-radius: 0px !important;
		border-radius:0px !important;
	}
	.botonera{
		margin-left: 1%;
	    padding-top: 10px;
	    width: 97.8%;
	}
	.ponderacion{
		padding-right:20px;
	}
	.ponderacion .center{text-align:center;}
</style>
<?php //echo var_dump($estados_postulacion); ?>
<div class="row-fluid">
	<div class="span7" style="padding-left:12px;"><h3>Postulación</h3></div>
	<?php if(!empty($ponderacion)):?>
		<div class="span5"><h3>Ponderación Asignaturas</h3></div>
	<?php endif;?>
</div>
<div class="row-fluid">
	<div class="<?php echo empty($ponderacion)? 'span12':'span7';?>">
		<?php if(!empty($postulacion)):?>
		<div class="accordion" id="accordion2">
		
		  <?php if((!empty($evidencia_datos2)) && ($postulacion['Estado']['codigo'] >= 8)):?>		
		  <div class="accordion-group">
		    <div class="accordion-heading">
		      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSeis">
		       Evidencias Finales <?php if (isset($evidencia_datos2[0]['fecha_validacion']) != null) { echo '<span><h6 class="pull-right fechaEtapa"><i class="fa icon-calendar"></i>  '.$this->Ingenia->formatearFecha($evidencia_datos2[0]['fecha_validacion']).'</h6></span>'; } ?>
		      </a>
		    </div>
		    <div id="collapseSeis" class="accordion-body collapse">
		      <div class="accordion-inner">
		      	<?php foreach ($evidencia_datos2 as $evidencia): ?>

			   <div class="row-fluid">
				<?php if(isset($evidencia) && ! empty($evidencia['uCom'])) :?>
					<h5>Unidad de competencia: <span class="capitalizar"><?php echo $evidencia['uCom']['UnidadCompetencia']['nombre_unidad_comp']?></span></h5>	
				<?php else :?>
					<h5>Unidad de competencia: <span class="capitalizar">Sin Unidad de competencia</span></h5>	
				<?php endif ;?>

					<div class="row-fluid">
						<div class="span2"> Nombre evidencia:</div>
						
						<div class="span10"><i class="fa icon-caret-right"></i> <?php echo($evidencia['nombre_evidencia']);?></div>
					</div>
					
					<div class="row-fluid">
						<div class="span2"> Justificación:</div>
						<div class="span10"><i class="fa icon-caret-right"></i> <?php echo strip_tags($evidencia['relacion']);?></div>
					</div>
					<div class="row-fluid">
						<div class="span2"> Archivo:</div>
						<div class="span10"><i class="fa icon-caret-right"></i>			
							<?php  
							$nombre = $evidencia['imagen']['ArchivoEvidencia']['nombre_fisico'];
							echo $this->Html->link($nombre,array('controller' => 'postulaciones', 'action' => 'descargarEvidencia', $evidencia['imagen']['ArchivoEvidencia']['codigo'])); 
							?>
						</div>
					</div>
					<hr>
				</div>
				<?php endforeach;?>
		      </div>
		    </div>
		  </div>
		  <?php endif; ?>
		  
		  
		  
		  
		</div>	
	<?php endif;?>		
	</div>
	<?php if(!empty($ponderacion)):?>
		<div class="span5 ponderacion">
			<table class="table table-bordered table-hover">
				<tr>
					<th>#</th>
					<th>Sigla</th>
					<th>Asignatura</th>
					<th>Ponderación</th>
				</tr>
				<?php $aux = 1; foreach($ponderacion as $sigla => $asignatura): ?>
					<tr>
						<td><?php echo $aux;?></td>
						<td><?php echo $asignatura['sigla'];?></td>
						<td><?php echo ($asignatura['asignatura'])?></td>
						<td class="center"><?php echo $asignatura['porcentaje'].'%'?></td>
					</tr>
				<?php $aux++; endforeach;?>
			</table>
		</div>
	<?php endif;?>
</div>
<div class="row-fluid botonera">
	<div class="span12" align="right">
  		<a class="btn btn-info" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'postulacionArchivo', $cod_postulacion))?>"><i class="icon-file"></i> Generar PDF</a>
  		<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones')); ?>" class="btn btn-warning" ><i class="icon-chevron-circle-left"></i> Volver</a>
		
	</div>
</div>
<!--<div class="row-fluid botonera">
	<div class="span12" align="right">
		<a class="btn" id="desactivarPostulacion"><i class="fa icon-ban"></i> Desactivar Postulación</a>
		<?php if(($estadoActual['Estado']['codigo'] > 0) && (($estadoActual['Estado']['codigo'] < 7)) || (($estadoActual['Estado']['codigo'] == 8)))  : ?>
			<a class="btn" id="desactivarEtapa" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'desactivaretapa', $cod_postulacion))?>"><i class="fa icon-mail-reply"></i> Desactivar Última Etapa</a>
		<?php endif; ?>
		<?php if($estadoActual['Estado']['codigo'] == 2): ?>
			<a id="aceptarDocumentacion" class="btn btn-success" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'aceptarDocumentacion', $cod_postulacion))?>"><i class="fa icon-check  "></i>Aceptar Documentación</a>		
		<?php elseif($estadoActual['Estado']['codigo'] == 5): ?>
			<a id="aceptarCvRap" class="btn btn-success"><i class="fa icon-caret-right"></i> Aceptar CvRap y Autoevaluación</a>
		<?php elseif($estadoActual['Estado']['codigo'] == 8): ?>
			<a id="finalizarPostulacion" class="btn btn-success"><i class="fa icon-check"></i> Finalizar Postulación</a>
  		<?php endif; ?>
  		<?php if($estadoActual['Estado']['codigo'] != 7 && $estadoActual['Estado']['codigo'] != 9): ?>
			<a id="cancelarPostulacion" class="btn btn-danger"><i class="fa icon-ban"></i> Rechazar Postulación</a>
		<?php endif; ?>
  		<a class="btn btn-info" href="<?php echo $this->html->url(array('controller' => 'Administrativos', 'action' => 'postulacionArchivo', $cod_postulacion))?>"><i class="icon-file"></i> Generar PDF</a>
  		<a href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones')); ?>" class="btn btn-warning" ><i class="icon-chevron-circle-left"></i> Volver</a>
		<a class="btn btn-danger" id="borrarPostulacion"><i class="fa icon-trash-o"></i> Borrar Postulación</a>
	</div>
</div>-->



<script>
	$('#cancelarPostulacion').on('click',function(){
		$('#modalRechazar').modal('show');
		return false;
	});
	$('#aceptarDocumentacion').on('click',function(){
		$('#modalDocumentacion').modal('show');
		return false;
	});
	$('#aceptarCvRap').on('click',function(){
		$('#modalCvRap').modal('show');
		return false;
	});
	$('#finalizarPostulacion').on('click',function(){
		$('#modalFinalizar').modal('show');
		return false;
	});
	$('#desactivarPostulacion').on('click',function(){
		$('#modalDesactivar').modal('show');
		return false;
	});
	$('#borrarPostulacion').on('click',function(){
		$('#modalborrarPostulacion').modal('show');
		return false;
	});
	$('#desactivarEtapa').on('click',function(){
		$('#modalDesactivarEtapa').modal('show');
		return false;
	});
</script>

<br/>