<style>
	.acciones{width:110px;}
</style>
<br/>
<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Carga masiva de datos:</h3>
	</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1" >
	<?php if ((!empty($errores)) && (isset($errores))): ?>
		<div class="alert alert-danger">
				<h5>El proceso falló por las siguientes causas:</h5>
				<?php foreach ($errores as $key => $error):?>
					<i class="icon-times-circle"></i> <?php echo $error; ?><br>		
				<?php endforeach; ?>			
		</div>
	<?php endif; ?>				
		<?php if (isset($errores) && (empty($errores))):	?>
		<div class="alert alert-success">
				<i class="icon-check-square"></i> Proceso correctamente ejecutado<br>

		</div>
			<?php endif; ?>
		
	</div>
</div>
<div class="row-fluid">	
	<div class="span10 offset1">
		<div class="alert alert-info">
			<H5>Instrucciones para crear el archivo de carga en formato .CSV:</h5>
			<ul style="text-decoration:none;">
				<li>1)- El archivo debe contener una primera fila en blanco.</li>
				<li>2)- La cabecera debe tener los siguientes campos obligatorios CARRERA | COMPETENCIA | TIPO DE COMPETENCIA | UNIDAD DE COMPETENCIA | SIGLA ASIGNATURA | NOMBRE ASIGNATURA.</li>
				<li>3)- Todos los campos deben escribirse con mayúscula a excepción  del campo UNIDAD DE COMPETENCIA el cual debe poseer la primera letra en mayúscula.</li>
				<li>4)- No deben existir  campos vacíos o en blanco.</li>	
			</ul>
			<span>Archivo de muestra:</span><br><span><a href="<?php echo $this->webroot.'uploads/csv/csv_muestra.xls';?>"> <i class="icon-download"></i> Descargar</a></span>
		</div>
	</div>

</div>

<div class="row-fluid">
	<?php if ((empty($errores)) && (isset($errores))):	?>
	<div class="span10 offset1">
			<?php echo $this->Html->link(
				'Volver',
				array('controller' => 'sistemas', 'action' => 'cargar_csv'),
				array('class'=>'btn btn-success')); 
			?>
	</div>
<?php endif;?>	
<?php if ((!empty($errores)) && (isset($errores))):	?>
	<div class="span10 offset1">
			<?php echo $this->Html->link(
				'Volver',
				array('controller' => 'sistemas', 'action' => 'cargar_csv'),
				array('class'=>'btn btn-danger')); 
			?>
	</div>
<?php endif;?>	
</div>	
<div class="row-fluid">	
	<div class="span5 offset1">
	<?php $datos = $this->data; //echo var_dump($datos);
		if (empty($this->data)):	?>	
			<?php echo $this->Form->create('Archivo', array('type' => 'file')); ?>
		<h5>Elija el archivo a cargar:</h5>
		<fieldset>				
				<?php echo $this->Form->input('', array(
					'type' => 'file',
					'required' => true,
				)); ?>
				<?php echo $this->Form->submit('Subir CSV', array(
					'div' => false,
					'class' => 'btn btn-success excel'
				)); ?>
		</fieldset>
		<?php echo $this->Form->end(); ?>
	<?php endif; ?>
	</div><br>
</div>
<br/>