<?php

App::uses('AppModel', 'Model');

class Entrevista extends AppModel {
	
	var $useTable = 'RAP_ENTREVISTAS';
	var $name = 'Entrevista';
	
	
	function datosEntrevista($codigo_postulacion=null){
		if(empty($codigo_postulacion)){
			return array();
		}
		
		$result = $this->find('first',array(
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_HORARIOS',
					'alias'=>'Horario',
					'conditions'=>array(
						'Entrevista.horario_codigo = Horario.codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_ADMINISTRATIVOS',
					'alias'=>'Administrativo',
					'conditions'=>array(
						'Entrevista.administrativo_codigo = Administrativo.codigo'
					)
				),
				
			),
			'conditions'=>array(
				'Entrevista.postulacion_codigo'=>$codigo_postulacion,
				'Entrevista.estado'=>'ACTIVO'
			),
			'fields'=>array(
				'Administrativo.nombre',
				'Entrevista.codigo',
				'Entrevista.estado',
				'Horario.hora_inicio',
				'Horario.codigo',
				'Horario.hora_fin'
			)
		));
		
		return $result;
		
	}	
}
