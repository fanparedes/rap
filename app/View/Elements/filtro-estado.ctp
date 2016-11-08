<?php
	if(isset($this->params) && ! empty($this->params['pass'][0]))
	{
		$valor = $this->params['pass'][0];
	}else{
		$valor = 0;
	}
	 echo $this->Form->input('filtro', array(
		'label' => array(
			'text' => ''
		),
		'value' => $valor,
		'class' => 'redondos',
		'empty' => '--Seleccione Estado',
		'options' => array(
			'-1' => 'Registro sin activación de cuenta',
			'1' => 'Formulario de Postulación',
			'2' => 'Doc. Recibida en Revisión',
			'3' => 'Doc. Aprobada',
			'4' => 'Currículum RAP completado',
			'5' => 'CV RAP y Autoevaluación en Revisión',
			'6' => 'CV RAP y Autoevaluación Aprobados',			
			'10' => 'Evidencias Previas Validadas',
			'8' => 'Entrevista',
			'11' => 'Evidencias Finales Validadas',
			'7' => 'Postulación Rechazada',
			'9' => 'Postulación Finalizada'
		),
)); ?>
	

