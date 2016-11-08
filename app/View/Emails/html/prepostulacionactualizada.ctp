<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
			Acabas de modificar y enviar su postulación (<?php echo $postulacion; ?>). Un administrativo revisará su documentación en breve. 
			<br/><br />
			Puede ver más detalles de su postulación haciendo	<a href="<?php echo $this->Html->url('/', true); ?>">
				click aquí
				</a>
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>