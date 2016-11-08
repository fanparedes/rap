<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'permisos', 'action' => 'index_usuarios'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Agregar Usuario:</h3>
		<div class="row-fluid">
			<div class="span5">
				<h5>Rellene los siguientes campos:</h5>
					 <?php            echo $this->Form->create('Permisos', array(
					'class' => 'form-horizontal',
					'inputDefaults' => array(
						'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
						'div' => array('class' => 'control-group'),
						'label' => array('class' => 'control-label'),
						'between' => '<div class="controls">',
						'after' => '</div>',
						'error' => array('attributes' => array('wrap' => 'span', 'class' => 'label label-important mleft10')),
						)));
						$tipos = array('RAP' => 'RAP', 'AH' =>  'ESCUELAS', 'AV' => 'ARTICULACIÓN');
					    echo $this->Form->input('Administrativo.nombre', array('placeholder' => 'Nombre', 'class' => 'controls', 'required' => true, 'maxlength' => '50'));
					    echo $this->Form->input('Administrativo.username', array('placeholder' => 'Nombre', 'class' => 'controls','maxlength' => '25'));
					    echo $this->Form->input('Administrativo.email', array('placeholder' => 'ejemplo@ejemplo.com', 'type'=>'email', 'required' => true, 'class' => 'controls','maxlength' => '55'));
					    echo $this->Form->input('Administrativo.perfil', array('options'=>$perfiles,'class' => 'controls','empty' => array(-1 => '')));
					    echo $this->Form->input('Administrativo.tipo', array('options' => $tipos,'class' => 'controls', 'disabled' => true, 'empty' => array(-1 => '')));
					    echo $this->Form->input('Administrativo.escuela_id', array('options'=>$escuelas,'empty'=>'', 'class' => 'controls', 'disabled' => true, 'escape' => false));
					    ?>
						<div class="carreraRap">
						<?php echo $this->Form->input('Administrativo.carrera_codigo', array('options'=>$carreras,'empty'=>'', 'class' => 'controls', 'escape' => false));	?>
						</div>
					  
					  <?php echo $this->Form->submit('Guardar', array(
							'div' => false,
							'class' => 'btn btn-success',
							'style' => 'margin-left:35%'
						));
						echo ' '.$this->Form->button('Limpiar', array('type' => 'reset', 'class' => 'btn'));
						?>												
					
			</div>
			<div class="span4">
				<div id="carrerasEscuelas">
					<h5>Carreras:</h5>
					<?php foreach ($escuelas_carreras as $escuela): ?>
						<div id="escuela_<?php echo $escuela['Escuela']['id']; ?>" style="display:none;" class="escuelas">
							<?php foreach ($escuela['Carrera'] as $i => $carrera): ?>							
								<input name="data[Administrativo][carreras][<?php echo $i; ?>]" type="checkbox" value="<?php echo $carrera['codigo'];?>" >
									<?php echo '  ('.$carrera['codigo'].')  '.$carrera['nombre'];?>
								</input><br>						
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?><br>
				</div>
			</div>					
			<?php echo $this->Form->end(); ?>
			</form>			
		</div>
	</div>	
</div>
<script>
$("#AdministrativoEscuelaId").change(function() {
	carreras($(this).val());
});

function carreras(escuela){
	var escuela = (escuela);
	$('.escuelas').hide();	
	$("#escuela_"+escuela).show();
}


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

$( "#AdministrativoPerfil" ).change(function() {
	var tipo = $( "#AdministrativoPerfil" ).val();
	console.log(tipo);
	if (tipo == '0') {
		console.log('entro');
		$('#AdministrativoTipo').attr('disabled', true);
		$('#AdministrativoCarreraCodigo').attr('disabled', true);	
		$('#AdministrativoEscuelaId').attr('disabled', true);
		$('#AdministrativoCarreraCodigo').val('-1');
		$('#AdministrativoEscuelaId').val('-1');
		$('#AdministrativoTipo').val('-1');
	}
	if (tipo == '1') {
		$('#AdministrativoTipo').attr('disabled', false);		
	}
	if (tipo == '2') {		
		$('#AdministrativoTipo').val('RAP');
		$('#AdministrativoTipo').attr('disabled', false);
		$('#AdministrativoEscuelaId').attr('disabled', true);	
		$('#AdministrativoCarreraCodigo').attr('disabled', false);
		$('#AdministrativoCarreraCodigo').val('-1');		
	}
	if (tipo == '3') {		
		$('#AdministrativoTipo').val('3');
		$('#AdministrativoEscuelaId').attr('disabled', true);	
		$('#AdministrativoCarreraCodigo').attr('disabled', true);				
	}
	if (tipo == '4') {		
		$('#AdministrativoTipo').attr('disabled', true);
		$('#AdministrativoEscuelaId').attr('disabled', true);	
		$('#AdministrativoCarreraCodigo').attr('disabled', true);	
				
	}

});


$( "#AdministrativoTipo" ).change(function() {
	var tipo = $( "#AdministrativoTipo" ).val();
	var escuela_inicial = $('#AdministrativoEscuelaId').val();				
	carreras(escuela_inicial);	
	if ((tipo == 'AH')) {	
		console.log('entro en ah');
		$('#AdministrativoEscuelaId').removeAttr("disabled");		
		$('#AdministrativoEscuelaId').attr('disabled', false);		
		$('#AdministrativoCarreraCodigo').attr('disabled', true);
		$('#AdministrativoCarreraCodigo').val('-1');
		$('#carrerasEscuelas').show();	
		$('.carreraRap').hide();	
	}
	if ((tipo == 'AV')){		
		$('.carreraRap').hide();	
		$('#carrerasEscuelas').hide();	
		$('#AdministrativoEscuelaId').attr('disabled', true);		
		$('#AdministrativoCarreraCodigo').attr('disabled', true);
		$('#AdministrativoEscuelaId').val('-1');
	}
	if ((tipo == 'RAP')) {
		$('.carreraRap').show();
		$('#carrerasEscuelas').hide();
		$('#AdministrativoEscuelaId').val('-1');
		$('#AdministrativoEscuelaId').attr('disabled', true);
		$('#AdministrativoCarreraCodigo').removeAttr("disabled");		
	}
});
</script>