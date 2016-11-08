<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
		Tu Currículum RAP y la Autoevaluación se encuentran validados. Puedes continuar con el proceso de 
		postulación de admisión especial vía Reconocimiento de Aprendizajes Previos (RAP) adjuntando sus evidencias previas y
		agendando una entrevista con el orientador.<br /><br />
		<br/><br />
			Para continuar con el proceso de postulación haz <a href="<?php echo $this->Html->url('/', true); ?>">click aquí</a><br><br>
			Le informamos que dispone de <?php echo $plazo; ?> días para completar sus Evidencias Previas y Agendar la entrevista con el orientador.
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>