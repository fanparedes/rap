<?php
	$unidades_indicadores = null;
	$unidades_enviadas = null;
	if(!empty($this->data) && !empty($this->data['Postulacion']['Unidades'])){
		$unidades_indicadores = $this->data['Postulacion']['Unidades'];
		$unidades_enviadas = array_keys($unidades_indicadores);		
	}
	#pr($unidades_enviadas);
	#debug($auto_evaluacion);
	#debug($estado);
?>
<?php echo $this->element('wizard-postulacion',array('resumen'=>$resumen,'cod_postulacion'=>$codigo_postulacion)); ?>
<style type="text/css" media="screen">
.subtitulo-instrucciones{
	text-decoration:underline;
}
.autoevaluacion{
	font-size:1.2em;
}
.lista-respuestas li{
	text-decoration:blink;
}
.div-button{text-align:right}
.th-header{
	width:78%;
}
.th-indicadores{text-align:center;}
.tr-indicadores th{text-align:center !important;}
.td-radio-indicador{
	text-align:center !important;
	vertical-align:middle;
}
.tabla-autoevaluacion{
	font-size:1em !important;
}
.unidad-empty{
	background-color:#f2dede;
}
</style>
<div class="row-fluid autoevaluacion">
	<div class="span12">
		<div class="row-fluid">
			<div class="span11 offset1">
			  	<h3 class="pull-left"> Autoevaluación</h3>
			</div>
		</div>
		<form method="POST" id="formulario-auto-evaluacion">
			<input type="hidden" value="<?php echo $codigo_postulacion;?>" name="data[Postulacion][codigo]" />
		<div class="row-fluid">
			<div class="span10 offset1 alert alert-info">
				<h4 align="center">INSTRUCCIONES</h4>
				<p align="justify">
					El siguiente cuestionario ha sido elaborado para que manifiestes una valoración acerca de tus competencias y tiene relación directa con las que seleccionaste en el currículum RAP.<br>
					Esta valoración previa a la etapa de evaluación, es relevante para determinar las competencias y unidades de competencia a evaluar y visualizar qué asignaturas podrían ser convalidadas.<br>
					Contesta a este cuestionario de manera sincera, recuerda que tus antecedentes y resultados alcanzados son confidenciales.<br>a 
					Reflexiona sobre las actividades que has realizado en el puesto de trabajo y considera tu nivel de autonomía en el desempeño de cada una de las Unidades de Competencia, en una escala de 1 a 4:
				</p> 
				<ol class="lista-respuestas">
					<li>1 = No sé hacerlo.</li> 
					<li>2 = Lo realizo con ayuda.</li> 
					<li>3 = Lo realizo de manera autónoma.</li>
					<li>4 = Lo realizo de manera autónoma, e incluso podría formar a otra persona.</li>
				</ol>
				<p align="justify">Marca sólo una de las alternativas por cada unidad de competencia. Puedes recurrir a la guía del postulante para acceder a las definiciones de competencia y unidad de competencia.</p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span10 offset1">
				<table class="table table-bordered tabla-autoevaluacion table-hover">
					<tr>
						<th class="th-header" rowspan="2">Competencia</th>
						<th colspan="4" class="th-indicadores">Indicador de Autoevaluación</th>
					</tr>
					<tr class="tr-indicadores">
						<th>1</th>
						<th>2</th>
						<th>3</th>
						<th>4</th>
					</tr>
					<?php $codigo_competencia_temporal = null;$contador_competencias =0; $contador_unidades=1; ?>
					<?php foreach($unidades_competencias_postulacion as $unidad_competencia):?>
						<?php
							$nueva_competencia = ($unidad_competencia['Competencia']['codigo_competencia'] !== $codigo_competencia_temporal) ? true:false;
							$codigo_competencia_temporal = $unidad_competencia['Competencia']['codigo_competencia'];
							#var_dump($nueva_competencia);
							$uno_selected = null;
							$dos_selected = null;
							$tres_selected = null;
							$cuatro_selected = null;
							$unidad_empty = true;
							if(!empty($unidades_indicadores) && !empty($unidades_enviadas)){
								if(!in_array($unidad_competencia['UnidadCompetencia']['codigo_unidad_comp'], $unidades_enviadas)){
									if(isset($auto_evaluacion[$unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']])){
										switch ($auto_evaluacion[$unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']]) :
											case '1': $uno_selected='checked'; break;
											case '2': $dos_selected='checked'; break;
											case '3': $tres_selected='checked'; break;
											case '4': $cuatro_selected='checked'; break;
										endswitch;
										$unidad_empty = false;
									}else{
										$unidad_empty = true;	
									}
								}else{
									$unidad_empty = false;
									switch ($unidades_indicadores[$unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']]) :
										case '1': $uno_selected='checked'; break;
										case '2': $dos_selected='checked'; break;
										case '3': $tres_selected='checked'; break;
										case '4': $cuatro_selected='checked'; break;
									endswitch;
								}
							}else{
								if(isset($auto_evaluacion[$unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']])){
									switch ($auto_evaluacion[$unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']]) :
										case '1': $uno_selected='checked'; break;
										case '2': $dos_selected='checked'; break;
										case '3': $tres_selected='checked'; break;
										case '4': $cuatro_selected='checked'; break;
									endswitch;
									$unidad_empty = false;
								}else{
									$unidad_empty = true;	
								}
							}
							if(empty($this->data)) $unidad_empty=false;	
						?>
						<?php if($nueva_competencia): $contador_competencias++; $contador_unidades=1;?>

							<tr>
								<td colspan="5" class="td-competencia"
								<?php 
								//	echo var_dump($unidad_competencia['Competencia']['troncal']);
									if ($unidad_competencia['Competencia']['troncal'] == 1): echo ('style="background-color:#ffedd1;"'); else: echo('style="background-color:#c6d2eb;"');  endif; 
								?>
								>
								<?php echo '<b>'.$contador_competencias.".- </b>".$unidad_competencia['Competencia']['nombre_competencia']; ?></td>
							</tr>
						<?php endif;?>
						<tr class="<?php echo ($unidad_empty)? 'unidad-empty' : ''; ?>">
							<td class="td-unidad"><?php echo  $contador_competencias.".".$contador_unidades.".- ".$unidad_competencia['UnidadCompetencia']['nombre_unidad_comp']; ?></td>
							<td class="td-radio-indicador"><input type="radio" <?php echo $uno_selected; ?> <?php echo ($formulario_completado)? 'disabled="disabled"':''; ?> value="1" name="data[Postulacion][Unidades][<?php echo $unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']; ?>]"></td>
							<td class="td-radio-indicador"><input type="radio" <?php echo $dos_selected; ?> <?php echo ($formulario_completado)? 'disabled="disabled"':''; ?> value="2" name="data[Postulacion][Unidades][<?php echo $unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']; ?>]"></td>
							<td class="td-radio-indicador"><input type="radio" <?php echo $tres_selected; ?> <?php echo ($formulario_completado)? 'disabled="disabled"':''; ?> value="3" name="data[Postulacion][Unidades][<?php echo $unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']; ?>]"></td>
							<td class="td-radio-indicador"><input type="radio" <?php echo $cuatro_selected; ?> <?php echo ($formulario_completado)? 'disabled="disabled"':''; ?> value="4" name="data[Postulacion][Unidades][<?php echo $unidad_competencia['UnidadCompetencia']['codigo_unidad_comp']; ?>]"></td>
						</tr>
					<?php $contador_unidades++; endforeach;?>
				</table>
			</div>
		</div>
		<div class="clear-fix"></div>
		<br/>
		<?php if(!$formulario_completado): ?>
			<div class="row-fluid">
				<div class="span10 offset1 div-button">										
					<i class="icon-square" style="color:#c6d2eb"> </i> Con este color se muestran las competencias específicas de la carrera<br>
					<i class="icon-square" style="color:#ffedd1"> </i> Con este color se muestran las competencias generales<br><br>
				</div>
				<div class="span11 div-button">
					<button type="submit" class="btn btn-primary pull-right" style="margin-right:30px;margin-top:10px;"><i class="icon-save"></i>&nbsp;Finalizar</button>
				</div>
			</div>
		<?php endif; ?>
		<br/>
		</form>
		<br/>
	</div>
</div>