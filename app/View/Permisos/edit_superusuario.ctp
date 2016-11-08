<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'permisos', 'action' => 'index_superusuarios'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Editar Super Administrador:</h3>
		<div class="row-fluid">
			<div class="span7">
				<h5>Rellene los siguientes campos:</h5>
				<?php echo $this->Form->create('Permisos', array(
						'class' => 'form-horizontal',
						'inputDefaults' => array(
							'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
							'div' => array('class' => 'control-group'),
							'label' => array('class' => 'control-label'),
							'between' => '<div class="controls">',
							'after' => '</div>',
							'error' => array('attributes' => array('wrap' => 'span', 'class' => 'label label-important mleft10')),
						)));
					  echo $this->Form->input('Administrativo.codigo',array('type'=>'hidden'));
					  echo $this->Form->input('Administrativo.nombre', array('placeholder' => 'Nombre', 'class' => 'controls', 'required' => 'true', 'maxlength' => '50'));
					  echo $this->Form->input('Administrativo.username', array('placeholder' => 'Nombre', 'class' => 'controls', 'maxlength' => '25', 'disabled' =>true));					  
					  echo $this->Form->input('Administrativo.perfil', array('options'=>$perfiles,'class' => 'controls', 'required' => true));
					  ?>
					
						<?php echo $this->Form->submit('Guardar', array(
							'div' => false,
							'class' => 'btn btn-success',
							'style' => 'margin-left:35%'
						));
						echo ' '.$this->Form->button('Limpiar', array('type' => 'reset', 'class' => 'btn'));
						?>													
					<?php echo $this->Form->end(); ?>
					</div>
				</form>			
		</div>
	</div>	
</div>
<script>
function checks(){
      if($(".icon:checkbox:checked").length <= 0){
            $('.icon').attr('required','true');
            } else {  
            $('.icon').removeAttr('required'); 
            }   
    
}    
$(document).ready(function(){
            checks();
        });
$('.icon').click(function(){checks();})
</script>