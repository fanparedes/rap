<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
			Has completado el CV RAP y la Autoevaluación. Se ha iniciado la revisión de la información ingresada de acuerdo 
			a los plazos establecidos en la <a href="<?php echo $this->Html->url(array('controller'=>'cargas','action'=>'descargarGuia'),true); ?>" target="_blank">guía del postulante</a>. Te contactaremos una vez finalizado este proceso.<br /><br />
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>