<?php

App::uses('AppModel', 'Model','Administrativo');
App::uses('BaseLog', 'Log/Engine');

class Contador extends AppModel {
	var $name = 'Contador';
	var $useTable="RAP_CONTADORES";	
	
	
	function obtener(){
		//Variables para generar ID Correlativo
		$codigo_admision        = 'AEE';			
		$ano_admision           = date('y');
		$registro = $this->find('first', array('conditions' => array('id' => $ano_admision)));
		if(empty($registro)){			
			$data = array('id' => (intval($ano_admision)), 'contador' => '0');
			$this->create($data);
			$this->save($data);			
		}
		$contador = $registro['Contador']['contador'];
		if ($contador == null){ $contador = 0; }
		$contador++;		
		$data = array('id' => (intval($ano_admision)), 'contador' => $contador);
		$this->id = $ano_admision;
		$this->save($data);
		$ceros = 5-strlen($contador);			
		$tamano = '';
		for ($i = 1; $i <= $ceros; $i++) {
			$tamano .= '0';
		}
		$tamano = $tamano.$contador;		
		return 'AEE'.$ano_admision.$tamano;
	}
}
?>