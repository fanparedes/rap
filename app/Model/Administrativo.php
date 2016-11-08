<?php
App::uses('AppModel', 'Model');
class Administrativo  extends AppModel {

	var $useTable = 'RAP_ADMINISTRATIVOS';
	var $name = 'Administrativo';
	var $primaryKey='codigo';
	

	function orientadoresHoras($codigo_carrera = null){
		$Carrera = ClassRegistry::init('Carrera');
		
		$orientadores = $this->find('all',array('conditions'=>array('Administrativo.orientador'=>1),
												'order'=>'ROWNUM'));
		if(!empty($orientadores)){
			foreach ($orientadores as $k => $orientador) {
				$carrera[$k] = $Carrera->find('first', array('conditions' => array('Carrera.codigo' => $orientador['Administrativo']['carrera_codigo'])));
				$result[$k]['Administrativo'] = $orientador['Administrativo'];
				$result[$k]['Carrera'] = $carrera[$k]['Carrera'];
				$result[$k]['Horarios'] = $this->horariosDelOrientador($orientador['Administrativo']['codigo']);				
			}
		}else{
			$result = array();
		}
		#debug($result);
		return $result;
	}
	
	//PAREA FUNCION DE FILTRO
	function orientadoresHorasAjax($codigo_carrera = null){
		$Carrera = ClassRegistry::init('Carrera');
		
		$orientadores = $this->find('all',array('conditions'=>array('Administrativo.orientador'=>1,
																	'Administrativo.carrera_codigo' => $codigo_carrera),
												'order'=>'ROWNUM'));
		if(!empty($orientadores)){
			foreach ($orientadores as $k => $orientador) {
				$carrera[$k] = $Carrera->find('first', array('conditions' => array('Carrera.codigo' => $orientador['Administrativo']['carrera_codigo'])));
				$result[$k]['Administrativo'] = $orientador['Administrativo'];
				$result[$k]['Carrera'] = $carrera[$k]['Carrera'];
				$result[$k]['Horarios'] = $this->horariosDelOrientador($orientador['Administrativo']['codigo']);
				
			}
		}else{
			$result = array();
		}
		#debug($result);
		
		return $result;
	}
	
	
	
	/* ESTA VALIDACIÓN VALE PARA LA INCORPORACIÓN DE USUARIOS */
	 function validaInsert($data, $edicion) {
		//Si el parámetro editar está vacío, quiere decir que la validación es para añadir, si no es para editar el registro. 
        if (empty($edicion)) {		
			$this->set($data);
			$this->validate = array(
				'nombre' => array('required' => true, 'rule' => '/^[1-9a-záéíóúÁÉÍÓÚñÑ\s]{1,}$/i', 'message' => 'Nombre incorrecto'),
				'username' => array(                
					array('required' => true, 'rule' => 'isUnique', 'message' => 'Ya existe un usuario con ese nombre')
				),            
			);
			return $this->validates();
			}
		else{
			$this->set($data);
			$this->validate = array(
				'nombre' => array('required' => true, 'rule' => '/^[1-9a-záéíóúÁÉÍÓÚñÑ\s]{1,}$/i', 'message' => 'Nombre incorrecto')          
			);
			return $this->validates();		
		}
    }
	
	
	
	

	function obtenerEncuestadores(){
		$sql ="
			SELECT
				A.nombre,
				A.codigo,
				A.CARRERA_CODIGO,
				c.nombre nombre_carrera,
				b.HORA_INICIO
			FROM
				RAP_ADMINISTRATIVOS A
			Left JOIN (
				SELECT
					MAX (hora_inicio) HORA_INICIO,
					ENCUESTADOR_CODIGO
				FROM
					RAP_HORARIOS
				GROUP BY
					ENCUESTADOR_CODIGO
			) b ON (
				b.ENCUESTADOR_CODIGO = A.codigo
			)
			INNER JOIN (RAP_CARRERAS) c ON (c.codigo = A.carrera_codigo)
			WHERE a.ORIENTADOR = 1
		";
		return $this->query($sql);	
	}
	
	function obtenerOrientador($codigo = null){
		if($codigo == null){
			return array();
		}else{
			$sql = $this->find('first', array(
				'conditions' => array('Administrativo.codigo' => $codigo),
				'joins' => array(
					array(
						'table' => 'RAP_CARRERAS',
						'alias' => 'Carrera',
						'type' => 'inner',
						'conditions' => array('Administrativo.carrera_codigo = Carrera.codigo')
					),
				),
				'fields' => array(
					'Carrera.nombre',
					'Administrativo.nombre',
					'Administrativo.codigo'
		
				)
			));
			return $sql;	
		}
	}
	
	function orientadores(){
		$sql = $this->find('all',array(
			'conditions'=>array(
				'orientador'=> 1
			),
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_CARRERAS',
					'alias'=>'Carrera',
					'conditions'=>array(
						'Administrativo.carrera_codigo = Carrera.codigo'
					)
				)
			),
			'fields'=>array(
				'Administrativo.codigo',
				'Administrativo.nombre',
				'Carrera.nombre'
			)
		));
		return $sql;
	}
	
	function horariosDelOrientador($orientador_codigo=null){
		if(empty($orientador_codigo)){
			return array();
		}
		$result = $this->find('all',array(
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_HORARIOS',
					'alias'=>'Horario',
					'conditions'=>array(
						'Horario.administrativo_codigo = Administrativo.codigo'
					)
				),
				array(
					'type'=>'LEFT',
					'table'=>'RAP_ENTREVISTAS',
					'alias'=>'Entrevista',
					'conditions'=>array(
						'Horario.codigo = Entrevista.horario_codigo',
						"Entrevista.estado = 'ACTIVO'"
					)
				),
				array(
					'type'=>'LEFT',
					'table'=>'RAP_POSTULANTES',
					'alias'=>'Postulante',
					'conditions'=>array(
						'Entrevista.postulante_codigo = Postulante.codigo',
					)
				)
			),
			'conditions'=>array(
				'Administrativo.codigo'=>$orientador_codigo
			),
			'fields'=>array(
				'Horario.codigo',				
				'Horario.hora_inicio',
				'Horario.hora_fin',
				'Horario.estado',
				'Entrevista.codigo',
				'Entrevista.postulacion_codigo',
				'Postulante.nombre',
				'Entrevista.postulante_codigo',
				'Administrativo.nombre'
			)	
		));
		
		return $result;
	}	
	
	
	public function compruebaEmail($email){		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false; 
		}		
		else{
			return true;
		}
	}
}
