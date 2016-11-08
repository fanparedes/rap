<style>
.acciones{width:110px;}
h1{
font-size: 20px;
color: #00aff0;
margin-left: 28px;
width: 200px;
}
.datepicker, .datepicker2{
font-size:12px!important;
}
</style>

<br/>
<!---inicio-->
<?php echo $this->Form->create('Reporteria', array('div' => false));?>

<div class="row-fluid">
			<div class="span6 offset1">
				<h3>Detalle de reportes de postulaciones:</h3>
				<br>

			</div>

</div>
<div class="row-fluid">
			<div class="span11 offset1">
				<div id="orientadores_elegidos">
					<b>Fecha de inicio:</b>	<?php echo $fecha_inicio; ?>			
				</div>
			</div>
</div>
<div class="row-fluid">
			<div class="span11 offset1">
				<div id="orientadores_elegidos">
					<b>Fecha de fin:</b>	<?php echo $fecha_final; ?>			
				</div>
			</div>
</div>
<div class="row-fluid">
			<div class="span10 offset1">
				<div id="orientadores_elegidos">
					<b>Sedes elegidas:</b>
						<?php foreach ($sedes as $sede){
							if (isset($sede['sede'])){
								echo mb_strtoupper($sede['sede'].', ');
							}						
						}
						?>	
				</div>
			</div>
</div>
<div class="row-fluid">
			<div class="span10 offset1">
				<div id="orientadores_elegidos">
					<b>Carreras elegidas:</b>
					<?php foreach ($carreras as $carrera){
							if (isset($carrera['carrera'])){
								echo mb_strtoupper($carrera['carrera'].', ');
							}
						}
					?>			
				</div>
			</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1">
		<hr>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Postulante</th>
					<th>Email</th>
					<th>Carrera</th>
					<th>Sede</th>
					<th>Ciudad</th>
					<th>Estado Postulación</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($postulaciones2)==0):?><tr><td colspan="8" align="center">No hay postulaciones</td></tr><?php endif;?>
				<?php foreach ($postulaciones2 as $k => $postulacion): ?>
					<tr>
						<td><?php echo $k+1; ?></td>
						<td><?php echo $postulacion['Postulantes']['nombre']; ?></td>
						<td><?php echo $postulacion['Postulantes']['email']; ?></td>
						<td><?php echo $postulacion['Carreras']['nombre']; ?></td>
						<td><?php echo $postulacion['Sedes']['nombre_sede']; ?></td>
						<td><?php echo $postulacion['Ciudades']['nombre']; ?></td>					
						<td><?php echo $postulacion['Postulaciones']['Estado'];?></td>
						<td>
							<?php if ($postulacion['Postulantes']['nombre']<>null) : ?> <a class="btn btn-sm" target="_blank" href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'updateData', $postulacion['Postulantes']['codigo'])); ?>"><i class="icon icon-search-plus"></i> Ver Postulante</a><?php endif;?>
							<?php if (($postulacion['Postulacion']['codigo']<> null) && ($postulacion['Postulantes']['codigo'] <> null) ) : ?> <a class="btn btn-sm" target="_blank"  href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones', $postulacion['Postulacion']['codigo'])); ?>"><i class="icon-file-text"></i> Ver Postulación</a><?php endif;?>						
						</td>
					<tr>
				<?php endforeach; ?>				
			</tbody>
		</table>
		<div class="pull-right"><p>* Los campos vacíos se deben a que se borró la postulación o el postulante</p></div>
		<div class="clearfix"></div>
		<div class="pull-right"><a class="btn btn-success excel"><i class="icon icon-download"></i> Exportar a Excel</a></div>
	</div>
</div>
<script>
	$('.excel').click(function(){
		var formulario = $('#ReporteriapostulaciondoresForm').serialize();
			window.location.href =  webroot +'reporteria/ajax_exportar_excel_postulaciones';
	})
</script>
