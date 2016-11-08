<?php

App::uses('AppModel', 'Model');

class AutoEvaluacion extends AppModel {
	
	var $useTable = 'RAP_AUTOEVALUACION';
	var $primaryKey = 'CODIGO';
	var $name = 'AutoEvaluacion';
	
	
	function obtenerAutoevaluacion($codigo = null){
		if($codigo == null){
			return array();
		}else{
			$result = $this->find('all', 
				array(
				'conditions' => array('postulacion_codigo' => $codigo),
				'joins' => array(
					array(   
						'table' => 'RAP_COMPETENCIA_UNIDAD_COMP',
						'alias' => 'UnidadCompetencia',
						'type' => 'inner',													 
						'conditions'=> 
							array( 'AutoEvaluacion.unidad_competencia_codigo = UnidadCompetencia.codigo_unidad_comp')
					),
				
				),
				'fields' => array(
					'AutoEvaluacion.indicador',
					'UnidadCompetencia.nombre_unidad_comp',
					'UnidadCompetencia.codigo_competencia'
		
				)
				
			));
			return $result;
		}
	}
	
}
