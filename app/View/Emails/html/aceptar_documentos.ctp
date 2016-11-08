<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
			Tus documentos se encuentran validados (<?php echo $postulacion;?>). Puedes continuar con el proceso de postulación de admisión 
			especial vía Reconocimiento de Aprendizajes Previos (RAP) completando el Currículum RAP y la 
			Autoevaluación.
			<br/><br />
			Para continuar con el proceso de postulación haz 
			<a href="<?php echo $this->Html->url('/', true); ?>">
				click aquí
			</a>.
			<br><br>
			Le informamos que dispone de <?php echo $plazo1; ?> días para completar el Currículum RAP y de <?php echo $plazo2;?> días para 
			completar la Autoevaluación.
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>