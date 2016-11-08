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
				<h3>Detalle de reportes de entrevistas:</h3>
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
			<div class="span11 offset1">
				<div id="orientadores_elegidos">
					<b>Orientadores elegidos:</b>
						<?php foreach ($orientadores as $orientador){
							if (isset($orientador['Nombre'])){
								echo mb_strtoupper($orientador['Nombre'].', ');
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
					<th>Orientador</th>
					<th>Fecha</th>
					<th>Hora</th>
					<th>Estado Entr.</th>
					<th>Estado Postulación</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($entrevistas2)==0):?><tr><td colspan="10" align="center">No hay entrevistas</td></tr><?php endif;?>
				<?php foreach ($entrevistas2 as $k => $entrevista): ?>
					<tr>
						<td><?php echo $k+1; ?></td>
						<td><?php echo $entrevista['Postulantes']['nombre']; ?></td>
						<td><?php echo $entrevista['Postulantes']['email']; ?></td>
						<td><?php echo $entrevista['Carreras']['nombre']; ?></td>
						<td><?php echo mb_strtoupper($entrevista['Administrativos']['nombre']); ?></td>
						<td><?php echo substr($this->Ingenia->formatearFecha($entrevista['Horarios']['fecha']),0,10); ?></td>
						<td><?php echo substr($this->Ingenia->formatearFecha($entrevista['Horarios']['hora_inicio']),11,5);?></td>
						<td><?php echo $entrevista['Entrevista']['estado']; ?></td>
						<td><?php echo $entrevista['Postulaciones']['Estado'];?></td>
						<td>
							<?php if ($entrevista['Postulantes']['nombre']<>null) : ?> <a class="btn btn-sm" target="_blank" href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'updateData', $entrevista['Postulantes']['codigo'])); ?>"><i class="icon icon-search-plus"></i> Ver Postulante</a><?php endif;?>
							<?php if (($entrevista['Postulaciones']['codigo']<> null) && ($entrevista['Postulantes']['codigo'] <> null) ) : ?> <a class="btn btn-sm" target="_blank"  href="<?php echo $this->Html->url(array('controller'=>'administrativos','action'=>'postulaciones', $entrevista['Postulaciones']['codigo'])); ?>"><i class="icon-file-text"></i> Ver Postulación</a><?php endif;?>						
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
		var formulario = $('#ReporteriaEntrevistadoresForm').serialize();
			window.location.href =  webroot +'reporteria/ajax_exporta_excel';
	})
</script>
