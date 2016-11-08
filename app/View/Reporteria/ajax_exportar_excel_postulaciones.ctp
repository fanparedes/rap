<?php

       header('Content-Type: application/force-download');
       header('Content-Disposition: attachment; filename=ReportePostulaciones.xls');
       header('Content-Transfer-Encoding: binary');
?>

<h2 class="offset1">Reporte: Postulaciones</h2>
<h class="offset1"><b>Rango de fechas seleccionados <?php echo $desde ;?> al <?php echo $hasta ;?></b></h>
<table width="200" border="1" style="color:1 px solid black">
  <tr>
    <td>Reporte: Postulaciones</td>
    <td rowspan="2">&nbsp;</td>
    <td>Total de postulaciones activas por Sede:</td>
    <td rowspan="2">&nbsp;</td>
    <td>Total de postulaciones activas por Carrera:</td>
  </tr>
  <tr>
    <td>
      <table width="200" border="1" style="color:1 px solid black">
        <tr>
          <td style="color:1 px solid black">Activos: &nbsp;<?php echo $totales[0]['totales'] ;?></td>
          <td style="color:1 px solid black">Deserciones: &nbsp; <?php echo $desertor ;?></td>
        </tr>
        <tr>
          <td style="color:1 px solid black">Finalizados: &nbsp; <?php echo $totales[1]['totales'] ;?></td>
          <td style="color:1 px solid black">Rechazados: &nbsp; <?php echo $totales[2]['totales'] ;?></td>
        </tr>
      </table>
    </td>
    <td>
		<?php if (! empty($total_sedes)) :?>
      <table width="200" border="1" style="color:1 px solid black">
        <tr>
          <td style="color:1 px solid black">Sede</td>
          <td style="color:1 px solid black">Cantidad</td>
        </tr>
        <?php foreach($total_sedes as $clave => $sede) :?>
		<tr>
		 <td style="color:1 px solid black"><?php echo htmlentities($sede['Sede']['sede']) ;?></td>
		  <td style="color:1 px solid black"><?php echo $sede['Sede']['cantidad'] ;?></td>
		</tr>
	  <?php endforeach ;?>
      </table>
	   <?php else :?>
		<p>Sin resultados</p>
		<?php endif ;?>	
    </td>
    <td>
	<?php if (! empty($total_carreras)) :?>
	<table width="200" border="1" style="color:1 px solid black">
      <tr>
        <td style="color:1 px solid black">Carrera</td>
        <td style="color:1 px solid black">Cantidad</td>
        </tr>
		<?php foreach($total_carreras as $key => $carreras) :?>
		<tr>
		 <td style="color:1 px solid black"><?php echo htmlentities($carreras['Carrera']['carrera']) ;?></td>
		  <td style="color:1 px solid black"><?php echo $carreras['Carrera']['cantidad'] ;?></td>
		</tr>
		<?php endforeach ;?>
    </table>
	 <?php else :?>
		<p>Sin resultados</p>
	<?php endif ;?>	
	</td>
  </tr>
</table>
<br />
<br />
<table width="200" border="1">
  <tr>
    <td>Desglose total de postulaciones activas de carreras por sedes</td>
    <td>&nbsp;</td>
    <td>Desglose total de postulaciones activas de sedes por carreras</td>
  </tr>
  <tr>
    <td>
	<?php if (! empty($estadistica_por_carrera)) :?>
    <table width="200" border="1">
      <tr>
        <td>Carrera</td>
        <td>Sede</td>
        <td>Cantidad</td>
      </tr>
	   <?php foreach($estadistica_por_carrera as $key1 => $carrera_datos) :?>
      <tr>
        <td><?php echo htmlentities($carrera_datos['Carrera']['carrera']) ;?><br /> Cantidad: &nbsp; <?php echo $carrera_datos['Carrera']['cantidad'] ;?></td>
        <td colspan="2">
		 <?php foreach($carrera_datos['Sede'] as $key2 => $sede) :?>	
			<table width="200" border="1">
			  <tr id ="<?php echo $key2 ;?>">
					<?php if ($key2% 2 ==0) :?>
						<td><?php echo htmlentities($sede['sede']) ;?></td>
						<td><?php echo $sede['cantidad'] ;?></td>
					<?php else :?>
						<td><?php echo htmlentities($sede['sede']) ;?></td>
						<td><?php echo $sede['cantidad'] ;?></td>
					<?php endif ;?>
				</tr>
			</table>
			 <?php endforeach ;?>
        </td>
      </tr>
	   <?php endforeach ;?>
		<?php else :?>
		  <p>Sin resultados</p>
	  <?php endif ;?>
     </table>
    </td>
    <td>&nbsp;</td>
    <td>
    <?php if (! empty($estadisticas_por_sede)) :?>
    <table width="200" border="1">
      <tr>
        <td>Sede</td>
        <td>Carrera</td>
        <td>Cantidad</td>
      </tr>
	<?php foreach($estadisticas_por_sede as $llave => $sede_datos) :?>
      <tr>
        <td><?php echo $sede_datos['Sede']['sede'] ;?><br /> Cantidad: &nbsp; <?php echo $sede_datos['Sede']['cantidad'] ;?></td>
        <td colspan="2">
		 <?php foreach($sede_datos['Carrera'] as $key2 => $carrera) :?>
			<table width="200" border="1">
			  <tr id ="<?php echo $key2 ;?>">
					 <?php if ($key2% 2 ==0) :?>
						<td><?php echo htmlentities($carrera['carrera']) ;?></td>
						<td><?php echo $carrera['cantidad'] ;?></td>
					<?php else :?>
						<td><?php echo htmlentities($carrera['carrera']) ;?></td>
						<td><?php echo $carrera['cantidad'] ;?></td>
					<?php endif ;?>
				</tr>
			</table>
			 <?php endforeach ;?>
        </td>
      </tr>
	   <?php endforeach ;?>
		<?php else :?>
		  <p>Sin resultados</p>
	  <?php endif ;?>
    </td>
  </tr> 
	</table>
</table>
<br><br>
<table border="1">
 <tr border="1" style="background-color:#93b996;">
	<td>Carrera</td>
	<td>Sede</td>
	<td>Nombre</td> 
	<td>Email</td>
	<td>Ciudad</td>  
	<td>Estado</td>  
  </tr>
  <?php foreach ($postulaciones2 as $postulacion): ?>
  <tr border="1">
	<td><?php echo htmlentities($postulacion['Carreras']['nombre']);?></td>
	<td><?php echo htmlentities($postulacion['Sedes']['nombre_sede']);?></td>
	<td><?php echo htmlentities($postulacion['Postulantes']['nombre']);?></td>
	<td><?php echo htmlentities($postulacion['Postulantes']['email']);?></td>
	<td><?php echo htmlentities($postulacion['Ciudades']['nombre']);?></td>
	<td><?php echo $postulacion['Postulaciones']['Estado'];?></td>
  </tr>
  <?php endforeach; ?>
</table>
