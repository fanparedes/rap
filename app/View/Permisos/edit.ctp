<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'permisos', 'action' => 'index'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Editar Perfil de Usuario:</h3>
		<div class="row-fluid">
			<div class="span7">
				<h5>Rellene los siguientes campos:</h5>
				<?php echo $this->Form->create('Permisos', array(
						'inputDefaults' => array(
							'div' => 'control-group',
							'label' => array(
								'class' => 'control-label'
							),
							'wrapInput' => 'controls'
						),
						'class' => 'well form-horizontal'
						)); 
					  echo $this->Form->input('Perfil.id');
					  echo $this->Form->input('Perfil.perfil', array('placeholder' => 'Nombre de perfil', 'class' => 'controls'));
					  ?>
					<p><strong>Establecer permisos:</strong></p>					
					<br>
					<?php 
					foreach ($funciones as $i=>$funcion){
						$key=$funcion['Funcion']['id'];
						$clase=$funcion['Funcion']['clase'];
						echo $this->Form->input('Permiso.'.$i.'.id',array('type'=>'hidden','label'=>false));
						echo $this->Form->input('Permiso.'.$i.'.id_perfil',array('type'=>'hidden','label'=>false,'value'=>$this->data['Perfil']['id']));
						echo $this->Form->input('Permiso.'.$i.'.id_funcion',array('type'=>'hidden','label'=>false,'value'=>$key)).
							$this->Form->input('Permiso.'.$i.'.autorizado',array('type'=>'checkbox', 
									 				     'class' => ' '.substr($clase,0,4), 
                                                         'label' => false,
														 'style' => 'float:right',
													     'before' => '<div clasS="'.$clase.'" style="width:25px; float:left;"></div><div style="margin-left:25px; float:left; width:110px;">'.ucwords($funcion['Funcion']['controlador'])."</div><div style='width:110px; margin-left:20px; float:left;'>".$funcion['Funcion']['funcion'].'</div><div style="width:150px; margin-left:20px; float:left;">'.$funcion['Funcion']['friendly'].'</div>'));
							
						
					} ?>
					<br>
						<?php echo $this->Form->submit('Guardar', array(
							'div' => false,
							'class' => 'btn btn-success',
							'style' => 'margin-left:35%;'
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