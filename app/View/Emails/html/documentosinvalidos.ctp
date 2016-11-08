<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
			Junto con saludar, informamos que su solicitud de habilitación vía Admisión Especial (<?php echo $postulacion; ?>) presenta observaciones en la documentación adjunta, favor revisar y corregir para continuar con su evaluación.<br><br>
			Puede revisar su postulación haciendo <a href="<?php echo $this->Html->url('/', true); ?>">
				click aquí
			</a>.
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>