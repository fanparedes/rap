<?php 
	$urlPreguntasBasicas = $this->Html->url(array('controller'=>'postulaciones','action'=>'completarPostulacion',$codigo_postulacion));
	$urlDocumentacion = $this->Html->url(array('controller'=>'postulaciones','action'=>'cargaDocumentos',$codigo_postulacion));
	$urlCompetencias = $this->Html->url(array('controller'=>'postulaciones','action'=>'competencias',$codigo_postulacion));
	#pr($resumen);
	 
?>
<style type="text/css" media="screen">
	.mini-title{
		font-size:15px;
		color:#ccc;
	}	
	#msj-obligatorio{
		font-size: 12px;
		font-style:italic;
	}
	.subtitle{
		color:#6E6E6E;
		font-size:40px;
		margin-top:12px;
	}
	.textarea{
		width:98.3%;
		max-width:98.3%;
		height:100px;
		max-height: 100px;
	}
	.link-pass:hover{
		cursor:pointer
	}
	.formulario{
		border-bottom: 1px solid #E2E2E2;
		border-left :1px solid #E2E2E2;
		border-right: 1px solid #E2E2E2;
		width: 98%;
		margin-left:1% !important;
	}
	.btn-nuevo{
		margin-top:5px;
	}
	.th-acciones{
		width:16%;
	}
	.th-numero{width:2%;}
	.th-institucion{width:35%;}
        
        #FormTextAreaObservacionesCvrap{
            text-transform: uppercase;
            
        }
        
	
</style>

<?php echo $this->element('wizard-postulacion',array('resumen'=>$resumen,'cod_postulacion'=>$codigo_postulacion)); ?>
<div class="row-fluid formulario">
	<div class="span12 ">
		<div class="row-fluid">
			<div class="span10 offset1">
			  	<h3 class="pull-left"> Curriculum Rap</h3>
			</div>
			<!--<div class="span5 subtitle">
				<span class="pull-right">
                	<i class="icon icon-file"></i>&nbsp;<?php echo $carrera['Carrera']['nombre']; ?>
                </span>
         </div>-->
		</div>
		<!--<div class="row-fluid">
			<hr class="span10 offset1">
		</div>-->
		<div class="row-fluid">
			<div class="span10 offset1"><?php #debug($user); ?>
	            <div class="row-fluid">
	                <div class="span12">
	                	<div class="row-fluid">
	                		<div class="span6">
	                			<h4 class="pull-left">Historial Educacional</h3>
	                		</div>
	                		<?php if(!$disabled): ?>
		                		<div class="span6">
		                			<a class="btn btn-success pull-right btn-nuevo" id="nuevo-historial-educacional" href="#modal" data-toggle="modal"><i class="icon icon-plus-circle"></i> Añadir Acontecimiento</a>				
		                		</div>
	                		<?php endif; ?>
	                	</div>
	                    <table class="table table-bordered table-hover">
	                    	<thead>
	                    		<tr>
	                    			<th class="th-numero">Nº</th>
	                    			<th class="th-institucion">Institución</th>
	                    			<th>Enseñanza</th>
	                    			<th>Observación</th>
	                    			<?php if(!$disabled): ?>
                    					<th class="th-acciones">Acciones</th>
                    				<?php endif; ?>
	                    		</tr>
	                    	</thead>
	                    	<tbody>
	                    		<?php if(empty($historial_educacional)): ?>
	                    			<tr>
	                    				<td colspan="5"><div align="center"> No ha cargado información. </div></td>
	                    			</tr>
	                    		<?php else: ?>
	                    		<?php foreach($historial_educacional as $k => $historial):?>
	                    			<tr>
	                    				<td><?php echo $k+1; ?></td>
	                    				<td ><?php echo strtoupper($historial['EducacionPostulacion']['institucion']);?></td>
	                    				<td><?php echo $tipos_educacion[$historial['EducacionPostulacion']['tipo_educacion_codigo']]; ?></td>
	                    				<td><?php echo strtoupper($historial['EducacionPostulacion']['observaciones']); ?></td>
	                    				<?php if(!$disabled): ?>
		                    				<td>
		                    					<a href="#modal" data-toggle="modal" class="btn btn-info btn-small editar-historial-educacional" codigo="<?php echo $historial['EducacionPostulacion']['codigo']; ?>"><i class="icon-edit">&nbsp;</i>Editar</a>
		                    					<a href="javascript:if(confirm('¿ Está seguro que desea eliminar este Historial Eduacaional ? Esta acción es irreversible.')){window.location='<?php echo $this->Html->url(array('action'=>'eliminarHistorialEducacional',$historial['EducacionPostulacion']['codigo'])); ?>'};" class="btn btn-danger btn-small">
		                    						<i class="icon-trash-o">&nbsp;</i>
		                    						Eliminar
		                    					</a>
		                    				</td>
	                    				<?php endif; ?>
	                    			</tr>
	                    		<?php endforeach; ?>
	                    		<?php endif; ?>
	                    	</tbody>
	                    </table>
	                </div>
	            </div>
	            <div class="row-fluid"><div class="span12"><hr/></div></div>	                	
	            <div class="row-fluid">
	                <div class="span12">
	                	<div class="row-fluid">
	                		<div class="span6">
	                			<h4 class="pull-left"> Capacitaciones</h4>
	                		</div>
	                		<?php if(!$disabled): ?>
		                		<div class="span6">
		                			<a class="btn btn-success pull-right btn-nuevo" id="nueva-capacitacion" href="#modal" data-toggle="modal"><i class="icon icon-plus-circle"></i> Añadir Acontecimiento</a>				
		                		</div>
	                		<?php endif; ?>
	                	</div>
	                    <table class="table table-bordered  table-hover">
	                    	<thead>
	                    		<tr>
	                    			<th class="th-numero">Nº</th>
	                    			<th  class="th-institucion">Institución</th>
	                    			<th>Nombre Curso</th>
	                    			<th>Observación</th>
	                    			<?php if(!$disabled): ?>
	                    				<th class="th-acciones">Acciones</th>
	                    			<?php endif; ?>
	                    		</tr>
	                    	</thead>
	                    	<tbody>
	                    		<?php if(empty($capacitaciones)): ?>
	                    			<tr>
	                    				<td colspan="5"><div align="center"> No ha cargado información. </div></td>
	                    			</tr>
	                    		<?php else: ?>
	                    		<?php $k=0;foreach($capacitaciones as $k => $capacitacion):?>
	                    			<tr>
	                    				<td><?php echo $k+1; ?></td>
	                    				<td><?php echo strtoupper($capacitacion['CapacitacionPostulacion']['institucion']);?></td>
	                    				<td><?php echo strtoupper($capacitacion['CapacitacionPostulacion']['nombre_curso']); ?></td>
	                    				<td><?php echo strtoupper($capacitacion['CapacitacionPostulacion']['observaciones']); ?></td>
	                    				<?php if(!$disabled): ?>
		                    				<td>
		                    					<a href="#modal" data-toggle="modal" class="btn btn-info btn-small editar-capacitacion" codigo="<?php echo $capacitacion['CapacitacionPostulacion']['codigo']; ?>"><i class="icon-edit">&nbsp;</i>Editar</a>
		                    					<a href="javascript:if(confirm('¿ Está seguro que desea eliminar esta Capacitación ? Esta acción es irreversible.')){window.location='<?php echo $this->Html->url(array('action'=>'eliminarCapacitacion',$capacitacion['CapacitacionPostulacion']['codigo'])); ?>'};" class="btn btn-danger btn-small">
		                    						<i class="icon-trash-o">&nbsp;</i>
		                    						Eliminar
		                    					</a>
		                    				</td>
	                    				<?php endif; ?>
	                    			</tr>
	                    		<?php endforeach; ?>
	                    		<?php endif; ?>
	                    	</tbody>
	                    </table>
	                </div>
	            </div>
	            <div class="row-fluid"><div class="span12"><hr/></div></div>
	            <div class="row-fluid">
	                <div class="span12">
	                	<div class="row-fluid">
	                		<div class="span6">
	                			<h4 class="pull-left">Historial Laboral</h4>
	                		</div>
	                		<?php if(!$disabled): ?>
		                		<div class="span6">
		                			<a class="btn btn-success pull-right btn-nuevo" id="nuevo-historial-laboral" href="#modal" data-toggle="modal"><i class="icon icon-plus-circle"></i> Añadir Acontecimiento</a>				
		                		</div>
	                		<?php endif; ?>
	                	</div>
	                    <table class="table table-bordered  table-hover">
	                    	<thead>
	                    		<tr>
	                    			<th class="th-numero">Nº</th>
	                    			<th  class="th-lugar">Lugar</th>
	                    			<th>Periodo</th>
	                    			<th  class="th-cargo">Cargo</th>
	                    			<th>Actividades</th>
	                    			<?php if(!$disabled): ?>
	                    				<th class="th-acciones">Acciones</th>
	                    			<?php endif; ?>
	                    		</tr>
	                    	</thead>
	                    	<tbody>
	                    		<?php if(empty($historial_laboral)): ?>
	                    			<tr>
	                    				<td colspan="6"><div align="center"> No ha cargado información. </div></td>
	                    			</tr>
	                    		<?php else: ?>
	                    		<?php $k=0;$historial=null;foreach($historial_laboral as $k => $historial):?>
	                    			<tr>
	                    				<td><?php echo $k+1; ?></td>
	                    				<td><?php echo strtoupper($historial['LaboralPostulacion']['lugar_trabajo']);?></td>
	                    				<td>
	                    					<?php
	                    						$periodos = array(
	                    							'1'=>'1 año',
	                    							'2'=>'2 años',
	                    							'3'=>'3 años',
	                    							'4'=>'4 años',
	                    							'5'=>'5 o más años'
												); 
	                    						echo $periodos[$historial['LaboralPostulacion']['periodo']];
											?>
	                    				</td>
	                    				<td><?php echo strtoupper($historial['LaboralPostulacion']['cargo']); ?></td>
	                    				<td><?php echo strtoupper($historial['LaboralPostulacion']['actividades']); ?></td>
	                    				<?php if(!$disabled): ?>
		                    				<td>
		                    					<a href="#modal" data-toggle="modal" class="btn btn-info btn-small editar-historial-laboral" codigo="<?php echo $historial['LaboralPostulacion']['codigo']; ?>"><i class="icon-edit">&nbsp;</i>Editar</a>
		                    					<a href="javascript:if(confirm('¿ Está seguro que desea eliminar este Historial Laboral ? Esta acción es irreversible.')){window.location='<?php echo $this->Html->url(array('action'=>'eliminarHistorialLaboral',$historial['LaboralPostulacion']['codigo'])); ?>'};" class="btn btn-danger btn-small">
		                    						<i class="icon-trash-o">&nbsp;</i>
		                    						Eliminar
		                    					</a>
		                    				</td>
		                    			<?php endif; ?>
	                    			</tr>
	                    		<?php endforeach; ?>
	                    		<?php endif; ?>
	                    	</tbody>
	                    </table>
	                </div>
	            </div><?php #debug($competencias); ?>
	            <div class="row-fluid"><div class="span12"><hr/></div></div>
	            <form action="<?php echo $this->Html->url(array('action'=>'CvRap',$codigo_postulacion)); ?>" method="POST">
	            <div class="row-fluid">
	                <div class="span12">
	                	<div class="row-fluid">
	                		<div class="span6">
	                			<h3 class="pull-left">
	                				<?php if(!$disabled): ?>
	                					¿Cómo debo seleccionar mis competencias?
	                				<?php else: ?>
	                					Competencias 
            						<?php endif;  ?>
	                			</h3>
	                		</div>
	                		<div class="span6">
	                			<?php 
		            				if($disabled):
										echo "<br><span id='span-count' class='pull-right'>".count($competencias_seleccionadas)." competencias seleccionadas</span>";
									endif;  
		            			?>
	                		</div>
	                	</div>
	                	<?php if(!$disabled): ?>
                			<div class="row-fluid">
		                		<div class="span12 alert alert-info">
		                			<h4>Atención</h4>
					                <ul>
					                	<li>Lee el listado de competencias correspondientes a la carrera que deseas cursar.</li>		
					                	<li>Considerando tu experiencia laboral, reflexiona sobre las competencias en las que consideras tener algún grado de dominio.</li>
					                	<li>Selecciona aquellas competencias en las que presentes algún grado de dominio (no es necesario manejarlas completamente, pero sí en algunos aspectos).</li>
					                	<li><b>Es obligatorio elegir al menos una competencia específica de la carrera.</b></li>
					                </ul>
		                		</div>
		                	</div>
                		<?php endif; ?>
						<h4>Competencias específicas:</h4>
	                    <table class="table table-bordered  table-hover">
	                    	<thead>
	                    		<tr>
	                    			<th width="15">Nº</th>
	                    			<th>Nombre</th>
	                    			<?php if(!$disabled): ?>
	                    				<th width="50">Seleccionar</th>
	                    			<?php endif; ?>
	                    		</tr>
	                    	</thead>
	                    	<tbody>
	                    		<?php if(empty($competencias)): ?>
	                    			<tr>
	                    				<td colspan="6"><div align="center"> No se han cargado competencias a la carrera que esta postulando.</div></td>
	                    			</tr>
	                    		<?php else: ?>
	                    		<?php foreach($competencias as $k => $competencia):?>
									<?php 
										$cuenta = count ($competencias);
										//echo var_dump($cuenta);
									?>
	                    			<tr class="<?php echo isset($competencias_seleccionadas[$competencia['Competencia']['codigo_competencia']])? 'alert alert-info': ''; ?>">
	                    				<td><?php echo $k+1; ?></td>
	                    				<td><label for="input-check-<?php echo $k; ?>" ><?php echo $competencia['Competencia']['nombre_competencia']; ?></label></td>
	                    				<?php if(!$disabled): ?>
	                    					<td><div align="center"><input name="data[Competencia][<?php echo $k; ?>][codigo_competencia]" id="input-check-<?php echo $k; ?>" class="check-competencia especifica" value="<?php echo $competencia['Competencia']['codigo_competencia']?>"  type="checkbox" /></div></td>
	                    				<?php endif; ?>
	                    			</tr>
	                    		<?php endforeach; ?>
	                    		<?php endif; ?>
	                    	</tbody>
	                    </table>					
					<br>
					<div class="span3 pull-right" style="margin-top:-30px">
	            		<span id="count">
	            		</span>
	            	</div>
					<br>
	              
				  <h4>Competencias genéricas:</h4>
	                    <table class="table table-bordered  table-hover">
	                    	<thead>
	                    		<tr>
	                    			<th width="15">Nº</th>
	                    			<th>Nombre</th>
	                    			<?php if(!$disabled): ?>
	                    				<th width="50">Seleccionar</th>
	                    			<?php endif; ?>
	                    		</tr>
	                    	</thead>
	                    	<tbody>
	                    		<?php if(empty($competencias_troncales)): ?>
	                    			<tr>
	                    				<td colspan="6"><div align="center"> No se han cargado competencias genericas la carrera que esta postulando.</div></td>
	                    			</tr>
	                    		<?php else: ?>
	                    		<?php foreach($competencias_troncales as $k => $competencia):?>
	                    			<tr class="<?php echo isset($competencias_seleccionadas[$competencia['Competencia']['codigo_competencia']])? 'alert alert-info': ''; ?>">
	                    				<td><?php echo $k+1; ?></td>
	                    				<td><label for="input-check-<?php echo $k; ?>" ><?php echo $competencia['Competencia']['nombre_competencia']; ?></label></td>
	                    				<?php if(!$disabled): ?>
	                    					<td><div align="center"><input name="data[Competencia][<?php echo $cuenta+$k; ?>][codigo_competencia]" id="input-check-<?php echo $k; ?>" class="check-competencia generica" value="<?php echo $competencia['Competencia']['codigo_competencia']?>"  type="checkbox" /></div></td>
	                    				<?php endif; ?>
	                    			</tr>
	                    		<?php endforeach; ?>
	                    		<?php endif; ?>
	                    	</tbody>
	                    </table>
	                </div>
	            </div>
	            <div class="row-fluid hide no-competencia">
	            	<div class="span12 alert alert-warning">
	            		<h5>Debe seleccionar al menos una competencia.</h5>
	            	</div>
	            </div>
	            <div class="row-fluid hide no-laboral">
	            	<div class="span12 alert alert-info">
	            		<h5>Debe ingresar al menos un historial laboral.</h5>
	            	</div>
	            </div>
	            <div class="row-fluid hide no-educacional">
	            	<div class="span12 alert alert-info">
	            		<h5>Debe ingresar al menos un historial educacional.</h5>
	            	</div>
	            </div>
	            <div class="row-fluid">
					<div class="span9">
						<div class="alert alert-info">En este listado se evaluarán las competencias de especialidad de la carrera a la que postulas. En la entrevista con el orientador, se revisarán las competencias básicas y de empleabilidad.</div>
					</div>
	            	<div class="span3">
	            		<span id="count_generica">
	            		</span>
	            	</div>
	            </div>
	            <div class="row-fluid">
	            	<div class="span12">
	            		<div class="control-group">
	            			<label class="control-label" for="FormTextAreaObservacionesCvrap" style="width:100%!important; display:block!important;">Si desea agregar algún acontecimiento importante para su Curriculum en Rap, escriba aquí:</label>
	            			<div class="control">
	            				<textarea <?php echo ($disabled)? 'disabled="disabled"': ''; ?>id="FormTextAreaObservacionesCvrap" class="textarea"name="data[Postulacion][observaciones_cvrap]" type="textarea" ><?php echo (isset($postulacion['Postulacion']['observaciones_cvrap']))? strtoupper($postulacion['Postulacion']['observaciones_cvrap']) : ''; ?></textarea>
	            			</div>
	            		</div>
	            	</div>
	            </div>
	            <div class="row-fluid">
	            	<?php if(!$disabled): ?>
		            	<div class="span4 offset8">
		            		<div id="advertencia" class="pull-right" style="color:red;"><?php echo $this->Html->image('test-fail-icon.png'); ?> Se requiere seleccionar al menos una competencia específica<br><br></div>
		            		<div id="advertencia-correcta" class="pull-right" style="display:none;"><?php echo $this->Html->image('test-pass-icon.png'); ?> Al menos una competencia específica seleccionada<br><br></div>
							<button class="btn btn-primary pull-right" type="submit" id="btn-competencias-postulacion" disabled><i class="icon-check"></i> CvRap Completo</button>
		            	</div>
	            	<?php endif; ?>
	            </div>
	            </form>
	            <br>
			</div>
		</div>
	</div>
</div><br>
<div class="modal hide fade" id="modal"></div>
<script type="text/javascript">
 
 //Función que cuenta las competencias elegidas
 var countChecked = function() {
	var n = $( "input.generica:checked" ).length;
	$("#count_generica").text( n + (n == 1 ? " competencia generica seleccionada" : " competencias genéricas elegidas"));
	return n;
 };
 

 
function countChecked2(){
	console.log('entro');
	//alert(1);
	
	var t = $( "input.especifica:checked" ).length;
	
	if (t > 0){
		console.log('1');
		$("#btn-competencias-postulacion" ).prop( "disabled", false );
		
		//$("#advertencia").hide();
		//$("#advertencia-correcta").show();
		
		$("#advertencia").css("display","none");
		$("#advertencia-correcta").css("display","block");
	}
	else{
		console.log('2');
		$( "#btn-competencias-postulacion" ).prop( "disabled", true );
		
		//$("#advertencia").show();
		//$("#advertencia-correcta").hide();
		
		$("#advertencia").css("display","block");
		$("#advertencia-correcta").css("display","none");
		
	}
	
	$("#count").text( t + (t == 1 ? " competencia específica seleccionada" : " competencias especificas seleccionadas"));
	return t;
	
 }
 
 //$("input.especifica[type=checkbox]").on("click", countChecked2); 
  $("input.especifica[type=checkbox]").click(function(){
		//alert('asd');
		countChecked2();
  });
  
 $("input.generica[type=checkbox]").on("click", countChecked);
 
 
 
 $('#btn-competencias-postulacion').on('click',function(){
 	var numero  = countChecked2();
 	if(numero<1){
 		$('.no-competencia').show();
 		return false;
 	}else{
 		$('.no-competencia').hide();
 		<?php if(count($historial_laboral) == 0): ?>
 			$('.no-laboral').show();
 			return false;
 		<?php else: ?>
 			$('.no-laboral').hide();
 			<?php if(count($historial_educacional) == 0): ?>
	 			$('.no-educacional').show();
	 			return false;
	 		<?php else: ?>
	 			$('.no-educacional').hide();
	 			
	 		<?php endif; ?>
 		<?php endif; ?>
 		return true;
 	}
 });
 $('#nuevo-historial-educacional').on('click',function(){
 	$('#modal').html('CARGANDO...');
 	$('#modal').load("<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'nuevoHistorialEducacional',$codigo_postulacion)); ?>");
 });
 $('#nuevo-historial-laboral').on('click',function(){
 	$('#modal').html('CARGANDO...');
 	$('#modal').load("<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'nuevoHistorialLaboral',$codigo_postulacion)); ?>");
 });
 $('#nueva-capacitacion').on('click',function(){
 	$('#modal').html('CARGANDO...');
 	$('#modal').load("<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'nuevaCapacitacion',$codigo_postulacion)); ?>");
 });
 $('.editar-historial-educacional').each(function(){
 	$(this).on('click',function(){
	 	var codigo = $(this).attr('codigo');
	 	$('#modal').html('CARGANDO...');
	 	$('#modal').load("<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'editarHistorialEducacional')); ?>"+"/"+codigo);
	 });	
 });
 $('.editar-capacitacion').each(function(){
 	$(this).on('click',function(){
	 	var codigo = $(this).attr('codigo');
	 	$('#modal').html('CARGANDO...');
	 	$('#modal').load("<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'editarCapacitacion')); ?>"+"/"+codigo);
	 });	
 });
 $('.editar-historial-laboral').each(function(){
 	$(this).on('click',function(){
	 	var codigo = $(this).attr('codigo');
	 	$('#modal').html('CARGANDO...');
	 	$('#modal').load("<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'editarHistorialLaboral')); ?>"+"/"+codigo);
	 }); 	
 });
</script>
