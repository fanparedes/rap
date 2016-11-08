<?php

App::uses('AppModel', 'Model');

class CompetenciaPostulacion extends AppModel {
	
	var $useTable = 'RAP_COMPETENCIAS_POSTULACIONES';
	var $primaryKey = 'CODIGO';
	var $name = 'CompetenciaPostulacion';
	


function competenciaselegidas ($codigo_postulacion){
			$opciones = array(
						'fields' => array(
							'CompetenciaPostulacion.competencia_codigo',
							'Competencia.nombre_competencia'
						),
						'joins' => array(
							array(
								'type' => 'inner',
								'alias' => 'Competencia',
								'table' => 'RAP_COMPETENCIA',
								'conditions' => array(
									'CompetenciaPostulacion.competencia_codigo = Competencia.codigo_competencia'
								)
							)
						),
						'conditions' => array(
							'CompetenciaPostulacion.postulacion_codigo' => $codigo_postulacion
						)
					);
			$competencias_elegidas = $this->find('all', $opciones);
			return $competencias_elegidas;
}
	
	
	
}



