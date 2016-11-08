<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'permisos', 'action' => 'index_funcion'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Editar funcionalidad:</h3>
		<div class="row-fluid">
			<div class="span7">
				<h5>Rellene los siguientes campos:</h5>
				<?php echo $this->Form->create('Permiso', array(
						'inputDefaults' => array(
							'div' => 'control-group',
							'label' => array(
								'class' => 'control-label'
							),
							'wrapInput' => 'controls'
						),
						'class' => 'well form-horizontal'
						)); 
						echo $this->Form->input('Funcion.id',array('value'=>$this->data['Funcion']['id']));
						echo $this->Form->input('Funcion.controlador', array('placeholder' => 'Nombre de perfil', 'class' => 'controls','required'=>true));
						echo $this->Form->input('Funcion.funcion', array('placeholder' => 'Método del controlador', 'class' => 'controls','required'=>true));
						echo $this->Form->input('Funcion.menu',array('id'=>'menu','class'=>'controls','type'=>'checkbox'));
						echo $this->Form->input('Funcion.friendly',array('id'=>'friendly','class'=>'controls','type'=>'text', array('label' => 'Nombre', 'class'=>'label-control') ));
						echo $this->ingenia->inputIcon('Funcion.clase');
						//echo $this->Form->input('Funcion.clase',array('class'=>'controls','type'=>'text'));
						echo $this->Form->submit('Guardar', array(
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
      if($("#menu:checked").length >0){
            $('#friendly').attr('required','true');
            $('#icono').attr('required','true');
            } else {  
            $('#icono').removeAttr('required'); 
            $('#friendly').removeAttr('required'); 
            } 
    
    
}    
$(document).ready(function(){
            checks();
        });
$('#menu').click(function(){checks();})

</script>