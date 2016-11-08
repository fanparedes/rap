<?php

header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=ReporteOrientadores.xls');
header('Content-Transfer-Encoding: binary');
?>

<h2 class="offset1">Reporte: Orientadores </h2>
<h class="offset1"><b>Rango de fechas seleccionados <?php echo $desde ;?> al <?php echo $hasta ;?></b></h>
<table width="200" border="1" style="color:1 px solid black">
  <tr>
    <td><b><p>Dias seleccionados:<?php echo $dias_diferencia ;?></p></b></td>
	<td></td>
    <td><b><p>Totales: <?php echo $totales ;?></p></b></td>
  </tr>
  <tr>
    <td><b>Total de entrevistas agendadas por carrera</b></td>
	<td></td>
    <td><b>Total de entrevistas agendadas por orientador</b></td>
  </tr>
  <tr>
    <td><table width="200" border="1" style="color:1 px solid black">
      <tr>
        <td><b>Carrera</b></td>
        <td><b>Cantidad</b></td>
      </tr>
	  
       <?php foreach($carrera_excel as $clave => $carrera) :?>
	     <tr>
			<td style="color:1 px solid black"><?php echo $carrera['Carrera']['nombre'] ;?></td>
			<td style="color:1 px solid black"><?php echo $carrera['Carrera']['cantidad'] ;?></td>
		</tr>
		<?php endforeach ;?>
      
    </table>
	</td>
	<td></td>
    <td><table width="200" border="1" style="color:1 px solid black">
      <tr>
        <td><b>Orientador</b></td>
        <td><b>Cantidad</b></td>
      </tr>
	  <?php foreach($orientador_excel as $clave => $orientador) :?>
      <tr>
       <td style="color:1 px solid black"><?php echo $orientador['Orientador']['nombre'] ;?></td>
		<td style="color:1 px solid black"><?php echo $orientador['Orientador']['cantidad'] ;?></td>
      </tr>
	  <?php endforeach ;?>
    </table>
	</td>
	<!-- COMIENZAN LOS DATOS DE LOS NOMBRES DE LOS ENTREVISTADOS -->
	<tr>
	</tr>
	<tr>
		<table border="1">
			<thead>
				<tr border="1" style="background-color:#93b996;">
					<th>Nombre</th>
					<th>Email</th>
					<th>Carrera</th>
					<th>Orientador</th>
					<th>Fecha</th>
					<th>Hora</th>
					<th>Estado</th>
					<th>Estado Postulacion</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($entrevistas2 as $entrevista): ?>
					<tr border="1">
						<td><?php echo $entrevista['Postulantes']['nombre'];?></td>
						<td><?php echo $entrevista['Postulantes']['email'];?></td>
						<td><?php echo $entrevista['Carreras']['nombre'];?></td>		
						<td><?php echo $entrevista['Administrativos']['nombre'];?></td>		
						<td><?php echo substr($entrevista['Horarios']['fecha'],0,10);?></td>
						<td><?php echo substr($entrevista['Horarios']['hora_inicio'],11,6);?></td>
						<td><?php echo $entrevista['Entrevista']['estado'];?></td>
						<td><?php echo $entrevista['Postulaciones']['Estado'];?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
	
		</table>
	</tr>
	
	
    
  </tr>
</table>