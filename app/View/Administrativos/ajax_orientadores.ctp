<h5>Orientadores para la Carrera: <?php echo ($carrera['Carrera']['nombre']) ;?></h5>
<!--<?php $colores = array('#f4543c','#00c0ef','#0073b7','#00b29e','#ba79cb','#ec3b83','#ffa812','#00a65a','#6c541e','#701c1c','#f4543c','#00c0ef','#0073b7','#00b29e','#ba79cb','#ec3b83','#ffa812','#00a65a','#6c541e','#701c1c','#f4543c','#00c0ef','#0073b7','#00b29e','#ba79cb','#ec3b83','#ffa812','#00a65a','#6c541e','#701c1c')?>-->
<?php if($orientadores) :?>
<div class="span5" style="width: 250px; margin-left: -23px;">
	<ul class="listado_opciones">
		<li>
			<div class="span10">
				<p><input type="checkbox" checked="checked" onclick="seleccionarOpciones('DISPONIBLE','<?php echo $carrera['Carrera']['codigo']?>')" id="check-DISPONIBLE" style="display:inline-block"/> <label style="display:inline-block" for="check-DISPONIBLE">Disponibles</label></p>		
			</div>
		</li>
		<li>
			<div class="span10">
				<p><input type="checkbox" checked="checked" onclick="seleccionarOpciones('AGENDADO','<?php echo $carrera['Carrera']['codigo']?>')" class="" id="check-AGENDADO" style="display:inline-block" /> <label style="display:inline-block" for="check-AGENDADO">Agendados</label></p> 
			</div>
		</li>
		<li>
			<div class="span10">
				<p><input type="checkbox" checked="checked" onclick="seleccionarOpciones('REALIZADO','<?php echo $carrera['Carrera']['codigo']?>')" class="" id="check-REALIZADO" style="display:inline-block" /> <label style="display:inline-block" for="check-AGENDADO">Realizados</label></p> 
			</div>
		</li>
	</ul>
</div>
<?php foreach($orientadores as $k => $orientador): ?>	
	<p><input type="checkbox" class="orientador" checked="checked" onclick="javascript:seleccionarHorarios('<?php echo $orientador['Administrativo']['codigo']; ?>')"  value="<?php echo $orientador['Administrativo']['codigo']?>" id="input-check-<?php echo $orientador['Administrativo']['codigo']; ?>" />&nbsp;&nbsp;<?php echo $orientador['Administrativo']['nombre']?> <span style="width: 10px; height: 10px; /*background-color: <?php echo $colores[$orientador['Administrativo']['codigo']]; ?>*/;">&nbsp;&nbsp;|</span><span>&nbsp <?php echo $orientador['Carrera']['nombre']?> </span></p>	
<?php endforeach;?>
<?php else :?>
<p>Carrera sin Orientadores.</p>
<?php endif ;?>

<script type="text/javascript">
// <![CDATA[
function seleccionarHorarios(element)
{
	if($('#input-check-'+ element).is(':checked')){
		$('.horario-'+ element).show();
	}else{
		$('.horario-'+ element).hide();
	}
}

function seleccionarOpciones(estado,codigo)
{
	if($('#check-'+estado).is(':checked'))
	{
		$('.'+estado+'-'+codigo).show();
	}else{
		$('.'+estado+'-'+codigo).hide();
	}
	
}
// ]]>
</script>