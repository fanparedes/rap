<?php

App::uses('AppModel', 'Model');

class Sede extends AppModel {
	
	var $useTable = 'RAP_SEDES';
	var $primaryKey = 'CODIGO_SEDE';
	var $name = 'Sede';
	
//	 public $belongsTo   = array( 
//        'SedeCarreraCupo' => array( 
//            'className' => 'SedeCarreraCupo', 
//            'foreignKey' => 'CODIGO_SEDE',
//			'counterScope'	=> array('Asociado.modelo' => 'SedeCarreraCupo')
//        ) 
//        ); 
//	
	
	function sedesPorCarrera($carrera=null){
		if(empty($carrera)){
			return array();
		}
		
		$result = $this->find('all',array(
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_SEDE_CARRERA_CUPO',
					'alias'=>'CupoCarrera',
					'conditions'=>array(
						'CupoCarrera.codigo_sede = Sede.CODIGO_SEDE'
					)
				),
			),
			'conditions'=>array(
				'CupoCarrera.codigo_carrera'=>$carrera,
				'Sede.activo' => 1
			),
			'fields'=>array(
				'Sede.nombre_sede',
				'Sede.codigo_sede'	
			),
			'order'=>'Sede.nombre_sede ASC'
		));
		// debug($result);
		// debug($this->getLastQuery());
		// exit();
		return $result;
	}	
	
	//function jornadasPorSede($carrera=null,$sede = null){
	//	if(empty($carrera) || empty($sede)){
	//		return array();
	//	}
	//	
	//	$result = $this
	//	$sql = "
	//		SELECT
	//		 	CupoCarrera.cupos_diurno,
	//			CupoCarrera.cupos_vespertino
	//		FROM
	//			rap_sede_carrera_cupo CupoCarrera
	//		WHERE
	//			CupoCarrera.codigo_carrera = '".$carrera."'
	//			and CupoCarrera.codigo_sede = '".$sede."'
	//	";
	//	$result = $this->query($sql);
	//	prx($result);
	//	return $result;
	//}
	
	function validaInsert($data) {
        $this->set($data);
		//prx($data);
        $this->validate = array(
            'codigo_sede' => array(array('required' => true, 'rule' => 'isUnique', 'message' => 'Ya existe ese codigo de sede')
            ),
        );
		echo var_dump($this->validates());
        return $this->validates();
    }
	
}
