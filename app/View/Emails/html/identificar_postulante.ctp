<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
			Has generado una nueva clave para iniciar el proceso de postulación  vía admisión especial. 
			Para activar tu cuenta haz 
			<a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'recuperarClave',$postulante['Postulante']['codigo']), true); ?>", target="blank">
				click aquí
			</a>.
			<br />
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>