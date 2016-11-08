<div class="color:#333">
	<p>
		<font size="2">
			Estimado/a <?php echo $postulante['Postulante']['nombre']; ?>,
		</font>
	</p>
	<p></p>
	<p align="justify">
		<font><br /><br />
			Has agendado una entrevista con el orientador <strong><?php echo $orientador['Administrativo']['nombre']; ?></strong> el día <strong><?php echo date('d-m-Y', strtotime($horario['Horario']['hora_inicio'])); ?></strong> a las <strong><?php echo date('H:i', strtotime($horario['Horario']['hora_inicio'])); ?></strong>. Este profesional te informará como continuar con el 
			proceso de postulación de admisión especial.
			</a>.
		</font>
	</p>
	<p><br /><br />
		<font >Atte.</font>
	</p>
</div>