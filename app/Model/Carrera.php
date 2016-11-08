<?php

App::uses('AppModel', 'Model');

class Carrera extends AppModel {
	
	var $useTable = 'RAP_CARRERAS';
	var $primaryKey = 'CODIGO';
	var $name = 'Carrera';
	
	function competenciasByCarrera($codigo_carrera=null){
		if(empty($codigo_carrera)){
			return array();
		}
		$result = $this->find('all',array(
				'joins'=>array(
					array(
						'type'=>'INNER',
						'table'=>'RAP_COMPETENCIA_CARRERAS',
						'alias'=>'CompetenciaCarrera',
						'conditions'=>array(
							'Carrera.codigo = CompetenciaCarrera.codigo_carrera'
						)
					),
					array(
						'type'=>'INNER',
						'table'=>'RAP_COMPETENCIA',
						'alias'=>'Competencia',
						'conditions'=>array(
							'Competencia.codigo_competencia = CompetenciaCarrera.codigo_competencia',
							'Competencia.troncal = 0'
						)
					)
				),
				'conditions'=>array(
					'Carrera.codigo'=>$codigo_carrera
				),
				'fields'=>array(
					'Competencia.nombre_competencia',
					'Competencia.codigo_competencia',
					'Competencia.troncal'
				)
			)
		);
		#debug($this->getLastQuery());
		return $result;
	}
	
	//Devuelve las competencias de la carrera solamente las genéricas
	function competenciasByCarrera2($codigo_carrera=null){
		if(empty($codigo_carrera)){
			return array();
		}
		$result = $this->find('all',array(
				'joins'=>array(
					array(
						'type'=>'INNER',
						'table'=>'RAP_COMPETENCIA_CARRERAS',
						'alias'=>'CompetenciaCarrera',
						'conditions'=>array(
							'Carrera.codigo = CompetenciaCarrera.codigo_carrera'
						)
					),
					array(
						'type'=>'INNER',
						'table'=>'RAP_COMPETENCIA',
						'alias'=>'Competencia',
						'conditions'=>array(
							'Competencia.codigo_competencia = CompetenciaCarrera.codigo_competencia',
							'Competencia.troncal = 1'
						)
					)
				),
				'conditions'=>array(
					'Carrera.codigo'=>$codigo_carrera
				),
				'fields'=>array(
					'Competencia.nombre_competencia',
					'Competencia.codigo_competencia',
					'Competencia.troncal'
				)
			)
		);
		#debug($this->getLastQuery());
		return $result;
	}
	
	
	//COMO NO PODMEOS UNIR EL MODELO AUTOMÁTICAMENTE, VAMOS A CREAR UNA FUNCIÓN QUE TE LAS UNA.
	public function obtenerCarreras(){	
		$carreras = $this->find('all');	
		$EscuelaCarrera = ClassRegistry::init('EscuelaCarrera');		
		$Escuela = ClassRegistry::init('Escuela');	
		
		$escuelas = $Escuela->find('all');
		
		foreach ($escuelas as $k => $escuela){
			$codigo_escuela = $escuela['Escuela']['id'];
			$relacion = $EscuelaCarrera->find('all', array('conditions' => array('EscuelaCarrera.escuela_codigo' => $codigo_escuela), 'order' => 'EscuelaCarrera.carrera_codigo DESC'));			
			foreach ($relacion as $i => $escuela_carrera){
				$carrera = $this->find('first', array('conditions' => array('Carrera.codigo' => $escuela_carrera['EscuelaCarrera']['carrera_codigo'])));
				//$escuelas[$k]['Carrera'][$escuela_carrera['EscuelaCarrera']['carrera_codigo']] = $carrera['Carrera'];			
				$escuelas[$k]['Carrera'][$escuela_carrera['EscuelaCarrera']['carrera_codigo']] = $carrera['Carrera'];			
			}
		}
		return ($escuelas);	
	}	
	
	
	//DEVUELVE SOLAMENTE LA ESCUELA DE UN CÓDIGO DE UNA CARRERA PASADA POR PARÁMETRO
	public function obtieneEscuela($carrera = null){		
		$escuelas = $this->obtenerCarreras();
		foreach ($escuelas as $k => $escuela){
			if (array_key_exists($carrera, $escuela['Carrera'])){
					return $escuelas[$k]['Escuela'];
			}
		}
	}
	
	
	
	
	//DEVUELVE EL NOMBRE DE LA CARRERA PASANDOLE EL CODIGO POR PARÁMETRO
	public function nombreCarrera($codigo){
		$nombre = $this->find('first', array('conditions'=> array('Carrera.codigo' => $codigo)));
		return ($nombre['Carrera']);
	}
	
	
	
}
