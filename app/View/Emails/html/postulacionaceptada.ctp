<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
			La documentación de su postulación (<?php echo $postulacion; ?>) ha sido aceptada. Debe continuar el proceso completando el formulario de postulación. <br><br>
			Puede ver más detalles de su postulación haciendo
			<a href="<?php echo $this->Html->url('/', true); ?>" face="Verdana, Geneva, sans-serif">
				click aquí
			</a>.</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>