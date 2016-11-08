<style>
.label_id{
	float:left!important;
	margin-left: 13px;

}
</style>
<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'sistemas', 'action' => 'index_plazos'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Agregar Plazo:</h3>		
		<div class="row-fluid">
			<div class="span7">
				<h5>Rellene los siguientes campos:</h5>
				<?php echo $this->Form->create('Plazo', array('inputDefaults' => array('div' => 'control-group','label' => array('class' => 'control-label'),'wrapInput' => 'controls'),'class' => 'well form-horizontal')); ?>
				<?php echo $this->Form->input('etapa', array('value' => $this->data['Plazo']['etapa'],'placeholder' => 'Etapa','required' =>'required','class' => 'controls capitalizar', 'maxlength'=> '50'));?>
				<?php echo $this->Form->hidden('etapa_id', array('value' => $this->data['Plazo']['etapa_id']));?>
				<?php echo $this->Form->input('plazo', array('placeholder' => 'Días','required' =>'required','class' => 'controls capitalizar', 'type' => 'number',  'pattern' => '[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ]+', 'onkeyup' => 'patron(this)', 'maxlength'=> '3'));?>
					<br>
				<?php echo $this->Form->submit('Guardar', array('div' => false,'class' => 'btn btn-success','style' => 'margin-left:35%'));?>													
				<?php echo $this->Form->end(); ?>
					</div>		
		</div>
	</div>	
</div>
