<br/>
<div class="row-fluid">
	<div class="span10 offset1">
		<h3>Bienvenid@ <?php echo($perfil['nombre']); ?> a la administración del PORTAL DE ADMISIONES ESPECIALES</h3>		
		<?php //echo var_dump($perfil); ?>
		<?php //echo var_dump($rol); ?>
		<div class="row-fluid">
			<div class="span8">
				<br>
				<p>Haga clic en los diferentes elementos del menú de arriba para acceder a las acciones disponibles para su perfil.</p>
				<p>Tenga en cuenta que dependiendo del perfil podrá acceder a más o menos funcionalidades.</p>
				<p>Una vez que haya terminado de realizar sus tareas, puede salir del sistema desplegando el botón que lleva su nombre y posteriormente pulsando el botón Salir.</p>
				<br>			
				<div class="row-fluid">				
				<?php $usuario = $this->Session->read('UserLogued');
				if($usuario['Administrativo']['tipo'] == 'AH' ){
					echo '<h5>Carreras Asignadas:</h5><br>';
					if (isset($carreras)){
						foreach ($carreras as $key => $carrera){
							if ($key%2) : echo '<div class=span6>'; endif;
								echo '<i class="icon icon-check"> </i> '.$carrera['nombre'].'<br>';	
							if ($key%2) : echo '</div>'; endif;							
						}
					}
				}
				?>
				</div>
				</div>
			<div class="span4">
				<H5>Datos del perfil:</H5>
				Nombre: <?php echo $perfil['nombre']; ?><br>
				Username: <?php echo $perfil['username']; ?><br>
				Creado: <?php echo $this->Ingenia->formatearFecha($perfil['created']); ?><br>
				Modificado: <?php echo $this->Ingenia->formatearFecha($perfil['modified']); ?><br>
				Perfil: <?php if ($perfil['perfil'] == 0) {
					echo 'Superadministrador';				
				}
				else {
					echo $rol[0]['Perfil']['perfil'];
				}
				?>	
				<br>Tipo:  
					<?php 
						
						if (isset($usuario['Administrativo']['tipo']) && ($usuario['Administrativo']['tipo']!== 'null')){
							if($usuario['Administrativo']['tipo'] == 'AH' ){
								
								echo 'ESCUELAS: '.$escuela['Escuela']['nombre'].'<br>';
								
							}
							if($usuario['Administrativo']['tipo'] == 'AV'){
								echo 'ARTICULACION';
							}
							if($usuario['Administrativo']['tipo'] == 'RAP'){
								echo 'ARTICULACION';
							}												
						}; 
					?>		
			</div>		
		</div>
		
	</div>
</div>
<div class="row-fluid">
	<div class="span10 offset1">
	</div>	
</div>
<br/>