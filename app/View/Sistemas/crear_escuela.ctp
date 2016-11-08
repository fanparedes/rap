<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'sistemas', 'action' => 'listadoEscuelas'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Agregar Escuela:</h3>
		<div class="row-fluid">
			<div class="span7">
				<h5>Rellene los siguientes campos:</h5>
				<?php echo $this->Form->create('Escuela', array('inputDefaults' => array('div' => 'control-group','label' => array('class' => 'control-label'),'wrapInput' => 'controls'),'class' => 'well form-horizontal')); ?>
				<?php echo $this->Form->input('nombre', array('placeholder' => 'Nombre de la escuela','required' =>'required','class' => 'controls capitalizar','pattern' => '[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ]+', 'onkeyup' => 'patron(this)'));?>
				<br>
				<h5>Elegir Carreras que se imparten en esta Escuela</h5>
				<div class="full">
					<div class="cupos">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<td>#</td>
									<td>CARRERA</td>
									<td>MODALIDAD</td>
									<td>SE IMPARTE EN:</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach($carreras as $key => $carrera) :?>
								<tr>
									<td><?php echo $this->Form->input('EscuelaCarrera.'.$carrera['Carrera']['codigo'].'', array('type' => 'checkbox','label' => false, 'value' => 1))?>		</td>
									<td><?php echo $carrera['Carrera']['nombre']; ?></td>
									<td><?php echo $carrera['Modalidad']['nombre']; ?></td>									
									<td>
										<?php 
											foreach ($carrera['Sedes'] as $k => $sede){
												echo $sede['nombre_sede'].'<br>';											
											}
										?>									
									</td>									
								</tr>								
								<?php endforeach ;?>
							</tbody>
						</table>			
					</div>
				</div>				
				<?php echo $this->Form->submit('Guardar', array('div' => false,'class' => 'btn btn-success','style' => 'margin-left:35%')) ; echo ' '.$this->Form->button('Limpiar', array('type' => 'reset', 'class' => 'btn'))?>													
				<?php echo $this->Form->end(); ?>
					</div>
				</form>			
		</div>
	</div>	
</div>