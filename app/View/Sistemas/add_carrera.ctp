<div class="row-fluid vista">
	<div class="span10 offset1">
		<div class="row-fluid">
				<div class="span12 menu-home">
					<div class="pull-right">
						<ul id="menu-full">
							<li id="prin">
								<?php echo $this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-caret-left"></i></div><div class="li-menu-texto"> Volver</div>', array('controller' => 'sistemas', 'action' => 'index_carrera'), array('escape' => false)); ?>
							</li>							
						</ul>
					</div>
				</div>
			</div>
		<h3>Agregar Carrera:</h3>
			<div class="row-fluid">
				
					<div class="span7" style="width: 699px;">
						<h5>Rellene los siguientes campos:</h5>
						<?php echo $this->Form->create('Sistema', array('inputDefaults' => array('div' => 'control-group','label' => array('class' => 'control-label'),'wrapInput' => 'controls'),'class' => 'well form-horizontal')); ?>
						<?php echo $this->Form->input('nombre', array('placeholder' => 'Nombre de carrera','required' =>'required', 'maxlength' => '50','class' => 'controls capitalizar','style'=>"margin-top: 14px;", 'pattern' => '[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ]+', 'onkeyup' => 'patron(this)'));?>
						<?php echo $this->Form->input('modalidad', array('options' => $modalidad,'empty' => '----Seleccione Modalidad','required' =>'required','class' => 'controls','style'=>"margin-top: 14px;"));?>
						<br>
							<!---cupos--->
							<div class="full">
								<div class="cupos">
									<div class="leyenda" style="text-align:right; padding-right:50px"><span class="span1" style="margin-left: 6px;">SEDE</span><span style="margin-right: 230px;">VACANTES FOL</span></div><br>
									<?php foreach($arreglo as $key => $sede) :?>
									<div class="cupos" style="float:right;width: 400px;display:inline-block;">
										<?php echo $this->Form->input('Sede.'.$key.'.full', array('class' => 'controls full','required' =>'required','label' => false,'div' => false,'disabled' => 'disabled'));?>
									</div>
									<div style="width:130px; margin-left:20px; float:left;"><?php echo $sede['Sede']['nombre_sede'] ?></div>
									<?php echo $this->Form->input('Sede.'.$key.'.codigo_sede', array('type' => 'checkbox','label' => false,'value' => $sede['Sede']['codigo_sede']))?>		
									<?php endforeach ;?>
									<br>
								</div>
							</div>
							<div class="normal">
								<div class="cupos">
									<div class="leyenda" style="text-align:right; padding-right:50px"><span class="span1" style="margin-left: 6px;">SEDE</span><span style="margin-right:40px;">VACANTES DIURNOS</span><span style="margin-right: 73px;">VACANTES VESPERTINOS</span></div><br>
									<?php foreach($arreglo as $key => $sede) :?>
									<div class="cupos" style="float:right;width: 400px;display:inline-block;">
										<?php echo $this->Form->input('Sede.'.$key.'.diurno', array('required' =>'required', 'class' => 'controls','label' => false,'div' => false,'disabled' => 'disabled'));?>
										<?php echo $this->Form->input('Sede.'.$key.'.vespertino', array('required' =>'required', 'class' => 'controls','label' => false,'div' => false,'disabled' => 'disabled'));?>
									</div>
									<div style="width:130px; margin-left:20px; float:left;"><?php echo $sede['Sede']['nombre_sede'] ?></div>
									<?php echo $this->Form->input('Sede.'.$key.'.codigo', array('type' => 'checkbox','label' => false,'value' => $sede['Sede']['codigo_sede']))?>		
									<?php endforeach ;?>
									<br>
								</div>
							</div>	
						<!---end cupos--->
					</div>
					<?php echo $this->Form->submit('Guardar', array('div' => false,'class' => 'btn btn-success','style' => 'margin-left:35%')); echo ' '.$this->Form->button('Limpiar', array('type' => 'borrar', 'class' => 'btn borrar', 'onclick' => 'return false'))?>													
					<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>	
</div>

<script type="text/javascript">
// <![CDATA[
//escondemos lso fomularios
$('div.normal').hide();
$('div.full').hide();
$('#SistemaModalidad').change(function(){
	var modalidad = $(this).val();
	if(modalidad == 12)
	{
		$(document).change(function()   {
			$('div.full').show();
			$('div.normal').hide();
			$('.full').each(function(index,elemento){	
					if ($('.full').find('input#Sede'+index+'CodigoSede').prop('checked') == true)
					{	
						$('.full').find('div.cupos').find('#Sede'+index+'Full').prop("disabled", false );
						$('.full').find('div.cupos').find('#Sede'+index+'Full').keydown(function(event){
						if ( event.keyCode != 8 && event.keyCode != 9  )
							if ( event.keyCode < 48 || event.keyCode > 57 && event.keyCode < 96 || event.keyCode > 105 || event.keyCode < 8 )
								return false;
						});
					}
					else {
						$('.full').find('div.cupos').find('#Sede'+index+'Full').prop( "disabled", true);
						$('.full').find('div.cupos').find('#Sede'+index+'Full').val('');
					}	
				})		
				
		});
	}else{
	
		$(document).change(function()   {
				$('div.full').hide();
				$('div.normal').show();
				$('.cupos').each(function(index,elemento){	
						if ($('.cupos').find('input#Sede'+index+'Codigo').is(':checked') == true)
						{	
							$('.cupos').find('div.cupos').find('#Sede'+index+'Diurno').prop("disabled", false );	
							$('.cupos').find('div.cupos').find('#Sede'+index+'Vespertino').prop("disabled", false );
							
							$('.cupos').find('div.cupos').find('#Sede'+index+'Diurno').keydown(function(event){
								if ( event.keyCode != 8 && event.keyCode != 9  )
									if ( event.keyCode < 48 || event.keyCode > 57 && event.keyCode < 96 || event.keyCode > 105 || event.keyCode < 8 )
										return false;
								});
							$('.cupos').find('div.cupos').find('#Sede'+index+'Vespertino').keydown(function(event){
							if ( event.keyCode != 8 && event.keyCode != 9  )
								if ( event.keyCode < 48 || event.keyCode > 57 && event.keyCode < 96 || event.keyCode > 105 || event.keyCode < 8 )
									return false;
							});
						}
						else {
							$('.cupos').find('div.cupos').find('#Sede'+index+'Diurno').prop( "disabled", true);
							$('.cupos').find('div.cupos').find('#Sede'+index+'Vespertino').prop( "disabled", true);
							$('.cupos').find('div.cupos').find('#Sede'+index+'Diurno').val('');
							$('.cupos').find('div.cupos').find('#Sede'+index+'Vespertino').val('');
						}	
				})		
		});
		
	}
})
// ]]>
</script>
<script type="text/javascript">
$('.borrar').click(function() {	
	$(SistemaAddCarreraForm).find(':input').each(function() {	
	switch(this.type){
		case 'password':
		case 'text':
		case 'textarea':
		case 'select-multiple':
		case 'select-one':
		$(this).val('');
		break;
		case 'checkbox':
		case 'radio':
		this.checked = false;
		}
	});
});    
</script>


