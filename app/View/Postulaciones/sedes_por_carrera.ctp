<select id="form-field-input-sede" required name="data[Postulacion][sede_codigo]" class="pull-right">
   	<option></option>
   	<?php foreach($sedes as  $sede): ?>
   		<option value="<?php echo $sede['Sede']['codigo_sede']; ?>" <?php echo (!empty($postulacion) && isset($postulacion['Postulacion']['sede_codigo']) && $postulacion['Postulacion']['sede_codigo']==$sede['Sede']['codigo_sede'])? 'selected':'';?>><?php echo $sede['Sede']['nombre_sede']; ?></option>
   	<?php endforeach; ?>
</select>
<script type="text/javascript">
	$(function(){
		$('#form-field-input-sede').change(function(){
			var sede = $(this).val();
			var carrera = $('#form-field-input-carrera').val();
			$('#div-jornada').html('<div class="pull-right"><?php echo $this->Html->image('loader.gif'); ?></div>');
			$('#div-jornada').load('<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'jornadasPorSede')); ?>'+'/'+carrera+'/'+sede);
		});
	});
</script>
