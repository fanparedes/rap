<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'sistemas', 'action' => 'index_medios'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Agregar Medios:</h3>
		<div class="row-fluid">
			<div class="span7">
				<h5>Rellene los siguientes campos:</h5>
				<br>
				<?php echo $this->Form->create('Sistema', array('inputDefaults' => array('div' => 'control-group','label' => array('class' => 'control-label'),'wrapInput' => 'controls'),'class' => 'well form-horizontal')); ?>
				<br><?php echo $this->Form->input('nombre', array('placeholder' => 'Nombre de Medios','required' =>'required','class' => 'controls capitalizar','pattern' => '[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ]+', 'onkeyup' => 'patron(this)', 'maxlength'=> '50', 'size' => '50'));?>
					<br>
						<?php echo $this->Form->submit('Guardar', array('div' => false,'class' => 'btn btn-success','style' => 'margin-left:35%')); echo ' '.$this->Form->button('Limpiar', array('type' => 'reset', 'class' => 'btn'))?>													
				<?php echo $this->Form->end(); ?>
					</div>
				</form>			
		</div>
	</div>	
</div>
