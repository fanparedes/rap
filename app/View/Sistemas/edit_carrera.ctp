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
		<h3>Editar Carrera:</h3>
		<div class="row-fluid">
			<div class="span9">
				<h5>Rellene los siguientes campos:</h5>
				<?php echo $this->Form->create('Sistema', array('inputDefaults' => array('div' => 'control-group formulario','label' => array('class' => 'control-label'),'wrapInput' => 'controls'),'class' => 'well form-horizontal')); ?>
				<?php echo $this->Form->hidden('codigo', array('value' => $this->data['Carrera']['codigo']));?>
				<?php echo $this->Form->hidden('modalidad', array('value' => $this->data['Carrera']['modalidad']));?>
				<?php echo $this->Form->input('nombre', array('placeholder' => 'Nombre de carrera','required' =>'required','class' => 'controls capitalizar', 'onkeyup' => 'patron(this)' ,'value' => $this->data['Carrera']['nombre']));?>
				<?php echo $this->Form->input('modalidad', array('options' => $modalidad,'disabled' => 'disabled','class' => 'controls modalidad','value' => $this->data['Carrera']['modalidad']));?>
				<br>
		<!---cupos--->
		<?php if($this->data['Carrera']['modalidad'] == 12) :?>
		<div class="full">
				<div class="cupos">
					<div class="leyenda" style="text-align:right; padding-right:50px"><span class="span1" style="margin-left: 6px;">SEDE</span><span style="margin-right: 230px;">VACANTES FOL</span></div><br>
					<?php foreach($arreglo as $key => $sede) :?>
				
					<div class="cupos" style="float:right;width: 400px;display:inline-block;">
							<?php if(isset($sede['Sede']['cupos_full']) && $sede['Sede']['cupos_full']) :?>
							<?php echo $this->Form->input('Sede.'.$key.'.full', array('value' => $sede['Sede']['cupos_full'],'required' =>'required','class' => 'controls full','label' => false,'div' => false)) ?>
							<?php else :?>
							<?php echo $this->Form->input('Sede.'.$key.'.full', array('required' =>'required','class' => 'controls full','label' => false,'div' => false,'disabled' => 'disabled')) ?>
							<?php endif ;?>
					</div>
					<div style="width:130px; margin-left:20px; float:left;"><?php echo $sede['Sede']['nombre_sede'] ?></div>
							
							<?php if(isset($sede['Sede']['cupos_full']) && $sede['Sede']['cupos_full']) :?>
							<?php echo $this->Form->input('Sede.'.$key.'.codigo_sede', array('type' => 'checkbox','checked' => 'checked','label' => false,'value' => $sede['Sede']['codigo_sede'])) ?>
							<?php else :?>
							<?php echo $this->Form->input('Sede.'.$key.'.codigo_sede', array('type' => 'checkbox','label' => false,'value' => $sede['Sede']['codigo_sede']))?>
							<?php endif ;?>
					
					<?php endforeach ;?>
					<br>
				</div>
			</div>
		<script type="text/javascript">
		// <![CDATA[
			$(document).change(function()   {
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
		// ]]>
		</script>
		<?php else :?>
		<div class="normal">
			<script type="text/javascript">
			// <![CDATA[
				
					$(document).change(function()   {
					
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
			// ]]>
			</script>	
				<div class="cupos">
					<div class="leyenda" style="text-align:right; padding-right:50px"><span class="span1" style="margin-left: 6px;">SEDE</span><span style="margin-right:40px;">VACANTES DIURNOS</span><span style="margin-right: 73px;">VACANTES VESPERTINOS</span></div><br>
					
					<?php foreach($arreglo as $key => $sede) :?>
					<div class="cupos" style="float:right;width: 400px;display:inline-block;">
							<?php if(! isset($sede['Sede']['cupos_full']) && ! empty($sede['Sede']['cupos_vespertino']) || ! empty($sede['Sede']['cupos_diurno'])) :?>
							<?php echo $this->Form->input('Sede.'.$key.'.diurno', array('value' => $sede['Sede']['cupos_diurno'],'required' =>'required', 'class' => 'controls','label' => false,'div' => false));?>
							<?php echo $this->Form->input('Sede.'.$key.'.vespertino', array('value' => $sede['Sede']['cupos_vespertino'],'required' =>'required', 'class' => 'controls','label' => false,'div' => false));?>
							<?php else :?>
							<?php echo $this->Form->input('Sede.'.$key.'.diurno', array('required' =>'required', 'class' => 'controls','label' => false,'div' => false,'disabled' => 'disabled'));?>
							<?php echo $this->Form->input('Sede.'.$key.'.vespertino', array('required' =>'required', 'class' => 'controls','label' => false,'div' => false,'disabled' => 'disabled'));?>
							<?php endif ;?>
					</div>
					<div style="width:130px; margin-left:20px; float:left;"><?php echo $sede['Sede']['nombre_sede'] ?></div>
							
							<?php if(! isset($sede['Sede']['cupos_full'])&& ! empty($sede['Sede']['cupos_vespertino']) || ! empty($sede['Sede']['cupos_diurno'])) :?>
							<?php echo $this->Form->input('Sede.'.$key.'.codigo', array('type' => 'checkbox','checked' => 'checked','label' => false,'value' => $sede['Sede']['codigo_sede'])) ?>
							<?php else :?>
							<?php echo $this->Form->input('Sede.'.$key.'.codigo', array('type' => 'checkbox','label' => false,'value' => $sede['Sede']['codigo_sede']))?>
							<?php endif ;?>
					
					<?php endforeach ;?>
					<br>
				</div>
			</div>
			
		<?php endif ;?>
		<!---end cupos--->
			<?php echo $this->Form->submit('Guardar', array('div' => false,'class' => 'btn btn-success','style' => 'margin-left:35%')); echo ' '.$this->Form->button('Limpiar', array('type' => 'borrar', 'class' => 'btn borrar', 'onclick' => 'borrarForm()'))?>													
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
</div>
<script type="text/javascript">
// <![CDATA[
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
});
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
	});		
// ]]>
</script>
<script type="text/javascript">
$('.borrar').click(function() {
	$(SistemaEditCarreraForm).find(':input').each(function() {
	switch(this.type){
		case 'password':
		case 'text':
		case 'textarea':
		$(this).val('');
		break;
		case 'checkbox':
		case 'radio':
		this.checked = false;
		}
	});
});    
</script>
					

