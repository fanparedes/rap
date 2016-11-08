<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
			Te has registrado para iniciar el proceso de habilitación de tus competencias vía admisión especial.<br>
			Para activar tu cuenta haz <a href="<?php echo $this->Html->url(array('controller'=>'postulaciones','action'=>'activarCuenta',$postulante['Postulante']['codigo']), TRUE); ?>" target="_blank" >click aquí</a>.<br /><br>
			Le informamos de que una vez activada su cuenta dispone de <?php echo $plazo; ?> días para completar su formulario de postulación.<br><br>
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>