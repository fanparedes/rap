<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<style type="text/css">
	.image-duoc{float:left;}
	.image-rap{float:right;height: 120px;margin-top :-30px;}
	#resumen{	
		border:1px solid #ccc;
		padding:10px;
	}
	.border{
		border: 1px solid navy;
	}
	.documentacion-completa{
		color:#5bb75b;
	}
	.no-certificados{
		color:#b94a48;
	}
	.sub-title{
		color:#666;
	}
	.table{
		width:100%;
	}
	.table thead tr th{
		padding:5px;
		border:1px solid #666;
	}
	.table tbody tr td{
		padding:10px;
		border:1px solid #666;
	}
	.resp{
		margin-left:30%;
	}
	ul{
		list-style:none;
	}
	.check-documentacion{
		margin-left: 40px;
	}
	.check{
		height: 16px;
		width: 16px;
	}
	.label, .badge {
		display: inline-block;
		padding: 2px 4px;
		font-size: 11.844px;
		font-weight: bold;
		line-height: 14px;
		color: #FFF;
		text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.25);
		white-space: nowrap;
		vertical-align: baseline;
		background-color: #999;
	}
	.label-info, .badge-info {
		background-color: #3A87AD;
	}
	.label-warning, .badge-warning {
		background-color: #F89406;
	}
</style>
<div class="row">
	<?php 
		echo $this->Html->image('logo-duoc.png',array('class'=>'image-duoc','div'=>false));
		echo $this->Html->image('logo-rap.jpg',array('class'=>'image-rap','div'=>false)); 
	?>
</div>
<div>
	<h2 align="center">DOCUMENTACIÓN DEL POSTULANTE </h2>
</div>
<div id="resumen">
	<table>
		<tbody>
			<tr>
				<td>&nbsp;</td>
				<td>Postulante:&nbsp;&nbsp;</td>
				<td><strong><?php echo $postulacion['Postulante']['nombre'].' '.$postulacion['Postulante']['apellidop'].' '.$postulacion['Postulante']['apellidom']; ?></strong></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>Carrera:</td>
				<td><strong><?php echo $postulacion['Carrera']['nombre']; ?></strong></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>Sede:</td>
				<td><strong><?php echo $postulacion['Sede']['nombre_sede']; ?></strong></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>Estado:</td>
				<td><strong><?php echo $postulacion['Estado']['nombre']; ?></strong></td>
				<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>Jornada:</td>
				<td><strong><?php echo $postulacion['Postulacion']['jornada']; ?></strong></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>Teléfono:</td>
				<td><strong><?php echo $postulacion['Postulante']['telefonomovil'].' / '.$postulacion['Postulante']['email']; ?></strong></td>
			</tr>
			<tr>
				<td >&nbsp;</td>
				<?php if(!empty($postulacion['Estado']['descripcion'])): ?>
					<td>Descripción:</td>
					<td><strong><?php echo $postulacion['Estado']['descripcion']; ?></strong></td>
				<?php endif; ?>
			</tr>
			
		</tbody>
	</table>
</div>
<br/>
<h4>1.- Formulario de Postulación</h4>
<table>
	<tr>
		<td><h6>¿Posee al menos un año de experiencia Laboral?</h6></td>
		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td> 
			<?php if($postulacion['Postulacion']['actividad_laboral'] == 1): ?>
				Si
			<?php else: ?>
				No 
			<?php endif;?>
		</td>
	</tr>
	<tr>
		<td><h6>¿Posee Licencia de Enseñanza Media?</h6></td>
		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td> 
			<?php if($postulacion['Postulacion']['licencia_educacion_media'] == 1): ?>
				Si
			<?php else: ?>
				No 
			<?php endif;?>
		</td>
	</tr>
	<tr>
		<td><h6>Ciudad de Residencia</h6></td>
		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td> 
			<?php echo $postulacion['Ciudades']['nombre']; ?>
		</td>
	</tr>
	<tr>
		<td><h6>Empresas</h6></td>
		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td> 
			<?php echo strtoupper(mb_strtoupper($postulacion['Postulacion']['empresa'])); ?>
		</td>
	</tr>
	<tr>
		<td><h6>Tipo Cargo</h6></td>
		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td> 
			<?php echo $postulacion['Cargos']['nombre']; ?>
		</td>
	</tr>
	<tr>
		<td><h6> Cargo</h6></td>
		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td> 
			<?php echo strtoupper(mb_strtoupper($postulacion['Postulacion']['cargo'])); ?>
		</td>
	</tr>
	<tr>
		<td><h6> Medio De Información</h6></td>
		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td> 
			<?php echo $postulacion['Medios']['nombre']; ?>
		</td>
	</tr>
</table>
<br>
<?php if(!empty($anexos)): ?>
	<h4>2.- Documentación</h4>
	<div style="width: 100%; "align="center">
		<h5 class="documentacion-completa">Documentación Completa.</h5>
		<h5 class="check-documentacion"><?php echo $this->Html->image('check.png',array('class'=>'check','div'=>false))?>&nbsp;Licencia de enseñanza media.</h5>
        <h5 class="check-documentacion"><?php echo $this->Html->image('check.png',array('class'=>'check','div'=>false))?>&nbsp;Cédula de Identidad.</h5>
	</div>
<?php endif;?>
<pagebreak />
<?php if(!empty($historial_educacional) && !empty($historial_laboral)): ?>
	<h4>3.- Curriculum Vitae en Rap</h4>
	<h5 class="sub-title"> Historial Educacional</h5>
	<table class="table">
		<thead>
			<tr>
				<th>Nº</th>
				<th>Institución</th>
				<th>Enseñanza</th>
				<th>Observación</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($historial_educacional as $historial): $aux = 1; ?>
				<tr>
					<td><?php echo $aux; ?></td>
					<td><?php echo $historial['EducacionPostulacion']['institucion']; ?></td>
					<td><?php echo $historial['tiposEducacion']['nombre']; ?></td>
					<td><?php echo $historial['EducacionPostulacion']['observaciones']; ?></td>
				</tr>
			<?php $aux++; endforeach; ?>
		</tbody>
	</table>
    <br>
    <h5 class="sub-title">Capacitaciones</h5>
    <?php if(!empty($capacitaciones)): ?> 
	    <table class="table">
	    	<thead>
	    		<tr>
	    			<th class="th-numero">Nº</th>
	    			<th class="th-institucion">Institución</th>
	    			<th>Nombre Curso</th>
	    			<th>Observación</th>
	    		</tr>
	    	</thead>
	    	<tbody>
	 			<?php $k=0;foreach($capacitaciones as $k => $capacitacion):?>
	    			<tr>
	    				<td><?php echo $k+1; ?></td>
	    				<td><?php echo $capacitacion['CapacitacionPostulacion']['institucion']?></td>
	    				<td><?php echo $capacitacion['CapacitacionPostulacion']['nombre_curso']; ?></td>
	    				<td><?php echo $capacitacion['CapacitacionPostulacion']['observaciones']; ?></td>
	    			</tr>
	    		<?php endforeach; ?>
	    	</tbody>
	    </table>
    	<br>
	<?php else: ?>
		<div style="width: 100%; "align="center">
			<h5 class="no-certificados">No ha cargado registros de capacitaciones.</h5>
		</div>
    <?php endif; ?>
    <h5 class="sub-title">Historial Laboral</h5>
    <table class="table">
		<thead>
			<tr>
				<th class="th-numero">Nº</th>
				<th  class="th-lugar">Lugar</th>
				<th>Periodo</th>
				<th  class="th-cargo">Cargo</th>
				<th>Actividades</th>
			</tr>
		</thead>
		<tbody>
			<?php $k=0;$historial=null;foreach($historial_laboral as $k => $historial):?>
				<tr>
					<td><?php echo $k+1; ?></td>
					<td><?php echo $historial['LaboralPostulacion']['lugar_trabajo']?></td>
					<td><?php echo $historial['LaboralPostulacion']['periodo']; ?></td>
					<td><?php echo $historial['LaboralPostulacion']['cargo']; ?></td>
					<td><?php echo $historial['LaboralPostulacion']['actividades']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
   <?php if(!empty($observaciones)): ?> 
    <h5 class="sub-title">Otros Acontecimientos reseñables en el Curriculum RAP:</h5>
    <table class="table">
		<tbody>
                    <tr>
                        <td>
                           <?php print_r($observaciones); ?>
                        </td>    
                    </tr>			
		</tbody>
	</table>   
    
    <?php endif; ?>
<pagebreak />
<?php if(!empty($competencias)): ?>
	<h3>4.- Auto Evaluación</h3>
	<?php foreach ($competencias as $key => $valor): ?>
		<table class="table">
			<thead>
				<tr>
					<th colspan="5" ><?php echo $valor['Compentencia']['nombre_competencia'].'';?><?php if ($valor['Compentencia']['troncal'] == 1): echo ' <span class="label label-warning">General</span><br>'; else: echo ' <span class="label label-info">Específica</span><br>'; endif; ?></th>
				</tr>
				<tr>
					<th rowspan="2" >Unidades de Competencias</th>
					<th colspan="4" >Indicadores de Autoevaluación</th>
				</tr>
				<tr>
					<th>1.- <br> No se hacerlo</th>
					<th>2.- <br> Lo realizó con ayuda</th>
					<th>3.- <br> Lo realizó de manera autónoma</th>
					<th>4.- <br> Lo realizó de manera autónoma, e incluso podría formar a otra persona.</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($autoevaluacion as $k => $val): ?>
					<?php if($valor['Compentencia']['codigo_competencia'] == $val['UnidadCompetencia']['codigo_competencia']): ?>
						<tr>
							<td><?php echo $val['UnidadCompetencia']['nombre_unidad_comp']; ?></td>
							<td align="center" ><?php echo ($val['AutoEvaluacion']['indicador'] == 1)? $this->Html->image('check.png',array('class'=>'check','div'=>false)) : "" ; ?></td>
							<td align="center" ><?php echo ($val['AutoEvaluacion']['indicador'] == 2)? $this->Html->image('check.png',array('class'=>'check','div'=>false)) : "" ; ?></td>
							<td align="center" ><?php echo ($val['AutoEvaluacion']['indicador'] == 3)? $this->Html->image('check.png',array('class'=>'check','div'=>false)) : "" ; ?></td>
							<td align="center" ><?php echo ($val['AutoEvaluacion']['indicador'] == 4)? $this->Html->image('check.png',array('class'=>'check','div'=>false)) : "" ; ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endforeach; ?>
<?php endif; ?>
<pagebreak />
<?php if(!empty($ponderacion)): ?>
	<h4>5.- Ponderación Asignaturas</h4>
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Sigla</th>
				<th>Asignatura</th>
				<th>Ponderación</th>
			</tr>
		</thead>
		<tbody>
			<?php $aux = 1; foreach($ponderacion as $sigla => $asignatura): ?>
				<tr>
					<td><?php echo $aux;?></td>
					<td><?php echo $asignatura['sigla'];?></td>
					<td><?php echo $asignatura['asignatura']?></td>
					<td class="center"><?php echo $asignatura['porcentaje'].'%'?></td>
				</tr>
			<?php $aux++; endforeach;?>
		</tbody>
	</table>
    <br>
<?php endif; ?>
<!--Evidencias Previas DIFERENCIAMOS ENTRE CASOS ANTIGUOS Y NUEVOS-->
<?php if (empty($evidencias_previas2)): ?>
	<?php if((!empty($evidencias_previas)) && ($estadoActual['Estado']['codigo'] >= 6)):?>
		<h4>6.- Evidencias Previas</h4>
		<table class="table">
			<thead>
				<tr>
					<th>Unidad Competencia</th>
					<th>Nombre evidencia</th>
					<th>Justificación</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($evidencias_previas as $evidencia): ?>
				<tr>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia[0]);?></td>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia[1]['EvidenciasPrevias']['nombre_evidencia']);?></td>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia[1]['EvidenciasPrevias']['relacion_evidencia']);?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<br>
	<?php endif; ?>
<?php else: ?>
	<?php if(($estadoActual['Estado']['codigo'] >= 6)):?>
		<h4>6.- Evidencias Previas*</h4>
		<table class="table">
			<thead>
				<tr>
					<th>Código</th>
					<th>Competencia</th>
					<th>Nombre evidencia</th>
					<th>Justificación</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($evidencias_previas2 as $evidencia): ?>
				<tr>					
					<td style="text-align:justify;"><?php echo strip_tags($evidencia['codigo']);?></td>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia['nombre']);?></td>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia['EvidenciasPrevias']['nombre_evidencia']);?></td>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia['EvidenciasPrevias']['relacion_evidencia']);?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<br>
	<?php endif; ?>
<?php endif; ?>
<!--fin Evidencias Previas-->
<!--entrevista-->
<?php if((!empty($horario)) && ($estadoActual['Estado']['codigo'] >= 8) && (isset($entrevista)) ):?>
	<h4>7.- Entrevista</h4>
	<table class="table">
		<thead>
			<tr>
				<th>Orientador</th>
				<th>Hora Inicio</th>
				<th>Hora Fin</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $administrativo['Administrativo']['nombre'];?></td>
				<td><?php echo $this->Ingenia->formatearFecha($horario['Horario']['hora_inicio']); ?></td>
				<td><?php echo $this->Ingenia->formatearFecha($horario['Horario']['hora_fin']) ;?></td>
				<td class="center"><?php echo $horario['Horario']['estado'] ; ?></td>
			</tr>
		</tbody>
	</table>
    <br>
<?php endif; ?>
<!--fin entrevista-->

<!--Evidencias Finales-->
<?php if (empty($evidencias_finales2)):?>
 <?php if((!empty($evidencias_finales)) && ($estadoActual['Estado']['codigo'] >= 8)):?>	
	<h4>8.- Evidencias Finales</h4>
	<table class="table">
		<thead>
			<tr>
				<th>Unidad Competencia</th>
				<th>Nombre evidencia</th><br />
				<th>Justificación</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($evidencias_finales as $evidencia_final): ?>
			<tr>
				<td style="text-align:justify;"><?php echo strip_tags($evidencia_final[0]);?></td>
				<td style="text-align:justify;"><?php echo strip_tags($evidencia_final[1]['EvidenciasPrevias']['nombre_evidencia']);?></td>
				<td style="text-align:justify;"><?php echo strip_tags($evidencia_final[1]['EvidenciasPrevias']['relacion_evidencia']);?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
    <br>
	<?php endif;?>
<?php else: ?>
<h4>8.- Evidencias Finales*</h4>
		<table class="table">
			<thead>
				<tr>
					<th>Código</th>
					<th>Competencia</th>
					<th>Nombre evidencia</th>
					<th>Justificación</th>
				</tr>
			</thead>
			<tbody>				
				<?php foreach ($evidencias_finales2 as $evidencia): ?>
				<tr>					
					<td style="text-align:justify;"><?php echo strip_tags($evidencia['codigo']);?></td>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia['nombre']);?></td>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia['EvidenciasPrevias']['nombre_evidencia']);?></td>
					<td style="text-align:justify;"><?php echo strip_tags($evidencia['EvidenciasPrevias']['relacion_evidencia']);?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<br>
		* Evidencias por COMPETENCIA, no por UNIDAD DE COMPETENCIA.
<?php endif; ?>
<!--fin Evidencias Finales-->