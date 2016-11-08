<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
		<p align="justify">
		<font><br /><br />
					Lamentamos comunicarle que su postulación no ha sido habilitada (<?php echo $postulacion;?>). <br />
		<p>
		<p>
		Puede ver más detalles de su postulación haciendo
			<a href="<?php echo $this->Html->url('/administracion', true); ?>">
				click aquí
			</a>.
		</p>
		</font>
	</p>
	<p><br/><br />
		<font >Atte.</font>
	</p>
</div>