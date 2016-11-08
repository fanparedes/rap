<?php
	if (isset($this->params['pass'][0])){
		$valor = $this->params['pass'][0];	
	}
	else {
		$valor = null;
	}
	
	 echo $this->Form->input('filtro', array(
		'label' => array(
			'text' => ''
		),
		'value' => $valor,
		'class' => 'redondos',
		'empty' => '--Seleccione Estado',
		'options' => array(
			'0' => 'Postulante Registrado',
			'1' => 'Formulario de Postulación',
			'2' => 'Doc. Recibida en Revisión',
			'3' => 'Doc. Aprobada',
			'4' => 'Currículum RAP completado',
			'5' => 'CV RAP y Autoevaluación',
			'6' => 'CV RAP y Autoevaluación Aprobados',			
			'10' => 'Evidencias Previas',
			'8' => 'Entrevista Agendada',
			'11' => 'Evidencias Finales',			
			'16' => 'Entrevista anulada'			
		),
)); ?>
	

