
<?php if(isset($modalidad) &&  ! empty($modalidad)) :?>
<select id="form-field-input-jornada" required name="data[Postulacion][jornada]" class="pull-right">
	<option></option>
	<?php if($cupos['SedeCarreraCupo']['cupos_diurno']!=0 || $cupos[0]['SedeCarreraCupo']['cupos_diurno']!= null ): ?>
		<option value="DIURNA">DIURNA</option>
	<?php endif; ?>
	<?php if($cupos['SedeCarreraCupo']['cupos_vespertino']!=0 || $cupos[0]['SedeCarreraCupo']['cupos_vespertino']!= null): ?>
		<option value="VESPERTINA">VESPERTINA</option>
	<?php endif; ?>
	<?php if($cupos['SedeCarreraCupo']['cupos_full']!=0 || $cupos[0]['SedeCarreraCupo']['cupos_full'] != null): ?>
		<option value="FOL">FOL</option>
	<?php endif; ?>
</select>
<?php else :?>
<select id="form-field-input-jornada" required name="data[Postulacion][jornada]" class="pull-right">
	
</select>
<?php endif; ?>