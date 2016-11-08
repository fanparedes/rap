<?php

App::uses('AppModel', 'Model');

class EducacionPostulacion extends AppModel {
	
	var $useTable = 'RAP_EDUCACION_POSTULACIONES';
	var $primaryKey = 'CODIGO';
	var $name = 'EducacionPostulacion';
	
	function eliminar($codigo = null){
		$sql = "DELETE FROM RAP_EDUCACION_POSTULACIONES WHERE CODIGO = '".$codigo."'";
		$this->query($sql);
		return TRUE;
	}
	
	function obtenerEducacionPostulacion($codigo = null){
		if($codigo == null){
			return array();
		}else{
			
			$result = $this->find('all', array(
				'conditions' => array('postulacion_codigo' => $codigo),
				'joins'=>array(
					array(   
						'table' => 'RAP_TIPOS_EDUCACION',
						'alias' => 'tiposEducacion',
						'type' => 'inner',													 
						'conditions'=> 
							array( 'tiposEducacion.codigo = EducacionPostulacion.tipo_educacion_codigo')
					),
				),
				'fields' => array(
					'EducacionPostulacion.institucion',
					'EducacionPostulacion.observaciones',
					'EducacionPostulacion.created',
					'tiposEducacion.nombre'
				),
				
			));
			return $result;
		}
	}
}
