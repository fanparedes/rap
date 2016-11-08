<?php

App::uses('AppModel', 'Model');

class Horario extends AppModel {
	
	var $useTable = 'RAP_HORARIOS';
	var $name = 'Horario';
	
	function buscarHoras($codigo = null, $fecha = null){
		if($codigo == null || $fecha == null){
			return array();
		}else{
			$sql = $this->find('all', array(
				'conditions' => array('ADMINISTRATIVO_CODIGO' => $codigo, 'FECHA' => $fecha)
			));
			
			return $sql;
		}
	}
	
	function borrarHora($codigo){
		if($codigo == null ){
			return false;
		}else{
			$sql = "DELETE FROM RAP_HORARIOS WHERE CODIGO = '$codigo'";
			return $this->query($sql);
		}
	}
	
	function horariosDisponibles($carrera_codigo = null){
		if(empty($carrera_codigo)){
			return array();
		}
		$fecha_actual = date('Y-m-d H:i:s');
		
		$result = $this->find('all',array(
			'conditions'=>array(
				"Horario.hora_inicio >= TO_DATE('".$fecha_actual."', 'YYYY/MM/DD HH24:MI:SS')",
				"Horario.estado = 'DISPONIBLE'",
				"Administrativo.carrera_codigo"=>$carrera_codigo
			),
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_ADMINISTRATIVOS',
					'alias'=>'Administrativo',
					'conditions'=>array(
						'Administrativo.codigo = Horario.administrativo_codigo'
					)
				)			
			)
		));
		//prx($result);
		#debug($this->getLastQuery());
		#debug($result);
		return $result;
	}
	
	function horarioAdministrativo($codigo_horario=null){
		if(empty($codigo_horario)){
			return array();
		}
		$horario_administrativo = $this->find('first',array(
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_ADMINISTRATIVOS',
					'alias'=>'Administrativo',
					'conditions'=>array(
						'Administrativo.codigo = Horario.administrativo_codigo'
					)
				)
			),
			'conditions'=>array(
				'Horario.codigo'=>$codigo_horario,
				'Horario.estado'=>'DISPONIBLE',
				'Administrativo.orientador'=>1
			),
			'fields'=>array(
				'Horario.codigo',
				'Horario.hora_inicio',
				'Horario.hora_fin',
				'Administrativo.nombre'
			)
		));
		return $horario_administrativo;
	}
	
	function proximoHorarioDisponibleAdministrativo($administrativo_codigo=null){
		if(empty($administrativo_codigo)){
			return array();
		}
		$fecha_actual = date('Y-m-d H:i:s');
		$result = $this->find('first',array(
			'conditions'=>array(
				'Horario.administrativo_codigo'=>$administrativo_codigo,
				'Horario.estado'=>'DISPONIBLE',
				"Horario.hora_inicio >= TO_DATE('".$fecha_actual."','YYYY/MM/DD HH24:Mi:SS')"
			),
			'fields'=>'MIN (hora_inicio) hora_inicio'
		));
		#debug($this->getLastQuery());
		return $result;
	}
}
