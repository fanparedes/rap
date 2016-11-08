<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'sistemas', 'action' => 'index_sede'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Editar Sede:</h3>
		<div class="row-fluid">
			<div class="span7">
				<h5>Rellene los siguientes campos:</h5>
				<?php echo $this->Form->create('Sistema', array('inputDefaults' => array('div' => 'control-group','label' => array('class' => 'control-label'),'wrapInput' => 'controls'),'class' => 'well form-horizontal')); ?>
				<?php echo $this->Form->hidden('codigo_sede', array('value' => $this->data['Sede']['codigo_sede']));?>
				<?php echo $this->Form->input('nombre_sede', array('placeholder' => 'Nombre de sede','required' =>'required','class' => 'controls capitalizar', 'onkeyup' => 'patron(this)', 'value' => $this->data['Sede']['nombre_sede']));?>				
					  
					<br>
						<?php echo $this->Form->submit('Guardar', array('div' => false,'class' => 'btn btn-success','style' => 'margin-left:35%')); echo ' '.$this->Form->button('Limpiar', array('type' => 'reset', 'class' => 'btn'))?>													
				<?php echo $this->Form->end(); ?>
					</div>
				</form>			
		</div>
	</div>	
</div>