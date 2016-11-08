<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
		Has cargado todos los documentos requeridos, los cuales se encuentran en revisión. Su validación se realizará de acuerdo a los plazos establecidos en la 
		<a href="<?php echo $this->Html->url(array('controller'=>'cargas','action'=>'descargarGuia'),true); ?>" target="_blank">
			guía del postulante
		</a>. 
			Para continuar con el proceso de postulación haz <a href="<?php echo $this->Html->url('/', true); ?>">click aquí</a><br><br>
			Le informamos que dispone de <?php echo $plazo; ?> días para completar sus Evidencias Previas y Agendar la entrevista con el orientador.
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>