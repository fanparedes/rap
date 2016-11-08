<?php

App::uses('AppModel', 'Model');

class Postulacion extends AppModel {
	
	var $useTable = 'RAP_POSTULACIONES';
	var $primaryKey = 'CODIGO';
	var $name = 'Postulacion';
	
	public $hasOne = array(
        'Carrera' => array(
            'className' => 'Carrera',
            'conditions' => array(
				'Carrera.codigo = Postulacion.carrera_codigo',
			),
			'foreignKey'  => false,
            'associatedKey'   => 'carrera_codigo'
        ),
		 'Postulante' => array(
            'className' => 'Postulante',
            'conditions' => array(
				'Postulante.codigo = Postulacion.postulante_codigo',
			),
			'foreignKey'  => false,
            'associatedKey'   => 'postulante_codigo'
        ),
		'Ciudad' => array(
            'className' => 'Ciudad',
            'conditions' => array(
				'Ciudad.codigo = Postulacion.ciudad_codigo',
			),
			'foreignKey'  => false,
            'associatedKey'   => 'ciudad_codigo'
        ) 
    );
	
	
	
	function obtenerCincoPostulaciones(){
		$sql = "SELECT
	A .ESTADO_CODIGO,
	b.POSTULACION_CODIGO,
	c.sede_codigo,
	c.carrera_codigo,
	c.postulante_codigo,
	c.jornada,
	d.nombre_sede,
	h.nombre nombre_carrera,
	i.nombre nombre_postulante,
	Estado.nombre nombre_estado

	
	
FROM
	RAP_ESTADOS_POSTULACIONES A
INNER JOIN (
	SELECT
		MAX (FECHA_CAMBIO) AS FECHA,
		POSTULACION_CODIGO
	FROM
		RAP_ESTADOS_POSTULACIONES
	GROUP BY
		POSTULACION_CODIGO
) b ON (
	A .POSTULACION_CODIGO = b.POSTULACION_CODIGO
	AND A .FECHA_CAMBIO = b.FECHA
)
INNER JOIN (
	RAP_POSTULACIONES c ) ON (
		c.CODIGO = b.POSTULACION_CODIGO
)

INNER JOIN (
	RAP_SEDES d ) ON (
		c.sede_codigo = d.codigo_sede
)

INNER JOIN (
	RAP_CARRERAS h ) ON (
		c.carrera_codigo = h.codigo
)

INNER JOIN (
	RAP_POSTULANTES i ) ON (
		c.postulante_codigo = i.codigo
)

INNER JOIN( RAP_ESTADOS Estado) ON (
	a.estado_codigo = Estado.codigo
)

WHERE
	A .ESTADO_CODIGO IN (2, 5)";
		return $this->query($sql);
		
	}
	
	function obtenerPostulaciones(){
		$sql = $this->find('all', array(
			'joins' => array(
				array(   
					'table' => 'RAP_POSTULANTES',
					'alias' => 'Postulante',
					'type' => 'inner',													 
					'conditions'=> 
						array( 'Postulante.codigo = Postulacion.postulante_codigo')
				)
				
				,array(   
					'table' => 'RAP_CARRERAS',
					'alias' => 'Carrera',
					'type' => 'inner',													 
					'conditions'=> 
						array( 'Carrera.codigo = Postulacion.carrera_codigo')
				),
				array(   
					'table' => 'RAP_SEDES',
					'alias' => 'Sede',
					'type' => 'inner',													 
					'conditions'=> 
						array( 'Sede.codigo_sede = Postulacion.sede_codigo')
				),
				
				
			),
			'oder' => 'created desc',
			'fields' => array(
				'Postulante.nombre',
				'Postulacion.codigo',
				'Postulacion.codigo',
				'Postulante.codigo',
				'Postulante.nombre',
				'Postulante.activo',
				'Sede.nombre_sede',
				'Postulacion.jornada',
				'Postulacion.activo',
				'Carrera.codigo',
				'Carrera.nombre'
			)
		));
		return $sql;
	}
		
	function estadoActual($codigo_postulacion = null){
		$rechazada = $this->postulacionRechazada($codigo_postulacion);	
		if(!empty($rechazada)){
			return $rechazada;
		}
		$estado_actual = $this->find('all',array(
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS_POSTULACIONES',
					'alias'=>'EstadoPostulacion',
					'conditions'=>array(
						'Postulacion.codigo = EstadoPostulacion.postulacion_codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS',
					'alias'=>'Estado',
					'conditions'=>array(
						'Estado.codigo = EstadoPostulacion.estado_codigo'
					)
				)
			),
			'conditions'=>array(
				'Postulacion.codigo' => $codigo_postulacion
			),
			'fields'=>array(
				'EstadoPostulacion.codigo',
				'EstadoPostulacion.postulacion_codigo',
				'EstadoPostulacion.fecha_cambio',
				'EstadoPostulacion.postulante_codigo',
				'EstadoPostulacion.modified',
				'Estado.nombre',
				'Estado.codigo',
				'Estado.descripcion'
			),
			'order'=>'Estado.codigo ASC'
		));
		$ant=0;
		foreach ($estado_actual as $actual){
			if ($ant+1==$actual['Estado']['codigo']){$ant=$actual['Estado']['codigo'];$buff=$actual['Estado'];}
			if (($ant==6)&&($actual['Estado']['codigo']==8)){$ant=8;$buff=$actual['Estado'];}
		
		}
		if (!isset($buff)){$buff = 0;}
		$estado_actual[0]['Estado']=$buff;
		
		return $estado_actual[0];
	}
	
	function datosPostulacion($codigo_postulacion = null){
		if(empty($codigo_postulacion)){
			return array();
		}
		$result = $this->find('first',array(
			'joins'=>array(
				array(   
					'table' => 'RAP_POSTULANTES',
					'alias' => 'Postulante',
					'type' => 'inner',													 
					'conditions'=> 
						array( 'Postulante.codigo = Postulacion.postulante_codigo')
				),
 				 	array(
					'type'=>'INNER',
					'table'=>'RAP_SEDES',
					'alias'=>'Sede',
					'conditions'=>array(
						'Sede.codigo_sede = Postulacion.sede_codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_CARRERAS',
					'alias'=>'Carrera',
					'conditions'=>array(
						'Carrera.codigo = Postulacion.carrera_codigo'
					)
				),
			  array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS_POSTULACIONES',
					'alias'=>'EstadoPostulacion',
					'conditions'=>array(
						'Postulacion.codigo = EstadoPostulacion.postulacion_codigo'
					)
				),
			    array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS',
					'alias'=>'Estado',
					'conditions'=>array(
						'Estado.codigo = EstadoPostulacion.estado_codigo'
					)
				)
			), 
			'conditions'=>array(
				'Postulacion.codigo' => $codigo_postulacion,
			),
			'fields'=>array(
 				'Estado.codigo',
				'Estado.nombre',
				'EstadoPostulacion.estado_codigo',
				'Estado.descripcion',
				'Postulacion.jornada', 
 				'Postulacion.empresa',
				'Postulacion.codigo',
				'Postulacion.postulante_codigo',
				'Postulacion.carrera_codigo',
				'Postulacion.ciudad_codigo',
				'Postulacion.tipo_cargo_codigo',
				'Postulacion.medio_informacion_codigo',
				'Postulacion.actividad_laboral',
				'Postulacion.licencia_educacion_media',
				'Postulacion.cargo',
				'Postulacion.observaciones_cvrap',
				'Postulacion.created',
				'Postulacion.sede_codigo',
				'Sede.nombre_sede',
 				'Carrera.nombre',
				'Carrera.codigo', 		
			)
		));		
		return $result;
	}


	function datosPostulacion2($codigo_postulacion = null){
		if(empty($codigo_postulacion)){
			return array();
		}
		$result = $this->find('first',array(
			'joins'=>array(		
 				array(
					'type'=>'INNER',
					'table'=>'RAP_SEDES',
					'alias'=>'Sede',
					'conditions'=>array(
						'Sede.codigo_sede = Postulacion.sede_codigo'
					)
				),
			  array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS_POSTULACIONES',
					'alias'=>'EstadoPostulacion',
					'conditions'=>array(
						'Postulacion.codigo = EstadoPostulacion.postulacion_codigo'
					)
				),
			    array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS',
					'alias'=>'Estado',
					'conditions'=>array(
						'Estado.codigo = EstadoPostulacion.estado_codigo'
					)
				)
			), 
			'conditions'=>array(
				'Postulacion.codigo' => $codigo_postulacion,
			),
			'fields'=>array(
 				'Postulacion.jornada', 
 				'Postulacion.empresa',
				'Postulacion.codigo',
				'Postulacion.postulante_codigo',				
				'Postulacion.carrera_codigo',
				'Postulacion.ciudad_codigo',
				'Postulacion.tipo_cargo_codigo',
				'Postulacion.medio_informacion_codigo',
				'Postulacion.actividad_laboral',
				'Postulacion.licencia_educacion_media',
				'Postulacion.cargo',
				'Postulacion.observaciones_cvrap',
				'Postulacion.created',
				'Postulacion.sede_codigo',
 				'Postulante.codigo',
				'Sede.nombre_sede',
 				'Carrera.nombre',
				'Carrera.codigo',			
				'Estado.codigo',
				'Estado.nombre',
				'EstadoPostulacion.estado_codigo', 
				'Estado.descripcion',
			)
		));
		//pr($result);
		return $result;
	}

	function datosCompletosPostulacion($codigo_postulacion = null){
		if(empty($codigo_postulacion)){
			return array();
		}
		$result = $this->find('first',array(
			'joins'=>array(			
				array(
					'type'=>'INNER',
					'table'=>'RAP_SEDES',
					'alias'=>'Sede',
					'conditions'=>array(
						'Sede.codigo_sede = Postulacion.sede_codigo'
					)
				),	
				array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS_POSTULACIONES',
					'alias'=>'EstadoPostulacion',
					'conditions'=>array(
						'Postulacion.codigo = EstadoPostulacion.postulacion_codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS',
					'alias'=>'Estado',
					'conditions'=>array(
						'Estado.codigo = EstadoPostulacion.estado_codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_TIPOS_CARGOS',
					'alias'=>'Cargos',
					'conditions'=>array(
						'Cargos.codigo = Postulacion.tipo_cargo_codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_CIUDADES',
					'alias'=>'Ciudades',
					'conditions'=>array(
						'Ciudades.codigo = Postulacion.ciudad_codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_MEDIOS_INFORMACION',
					'alias'=>'Medios',
					'conditions'=>array(
						'Medios.codigo = Postulacion.medio_informacion_codigo'
					) 
				),
			),
			'conditions'=>array(
				'Postulacion.codigo' => $codigo_postulacion,
			),
			'fields'=>array(
				'Postulacion.codigo',
				'Postulacion.postulante_codigo',
				'Postulacion.jornada',
				'Postulacion.empresa',
				'Postulacion.codigo',				
				'Postulacion.carrera_codigo',
				'Postulacion.ciudad_codigo',
				'Postulacion.tipo_cargo_codigo',
				'Postulacion.medio_informacion_codigo',
				'Postulacion.actividad_laboral',
				'Postulacion.licencia_educacion_media',
				'Postulacion.cargo',
				'Postulacion.modified',
				'Postulacion.created',
				'Postulacion.sede_codigo',
 				'Medios.nombre',
				'Ciudades.nombre',
				'Cargos.nombre',
				'Postulante.codigo',
				'Postulante.nombre',
				'Postulante.apellidop',
				'Postulante.apellidom',
				'Postulante.email',
				'Postulante.telefonomovil',
				'Postulante.rut',
				'Sede.nombre_sede',
				'Carrera.nombre',
				'Estado.codigo',
				'Estado.nombre',
				'Estado.descripcion',	
			)
		));

		return $result;
	}

	function postulacionRechazada($postulacion_codigo=null){
		$postulacion_rechazada = $this->find('first',array(
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS_POSTULACIONES',
					'alias'=>'EstadoPostulacion',
					'conditions'=>array(
						'Postulacion.codigo = EstadoPostulacion.postulacion_codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_ESTADOS',
					'alias'=>'Estado',
					'conditions'=>array(
						'Estado.codigo = EstadoPostulacion.estado_codigo'
					)
				)
			),
			'conditions'=>array(
				'Postulacion.codigo' => $postulacion_codigo,
				'Estado.codigo = 7'
			),
			'fields'=>array(
				'EstadoPostulacion.codigo',
				'EstadoPostulacion.postulacion_codigo',
				'EstadoPostulacion.fecha_cambio',
				'EstadoPostulacion.postulante_codigo',
				'Estado.nombre',
				'Estado.codigo',
				'Estado.descripcion'
			),
			'order'=>'Estado.codigo DESC'
		));
		
		return $postulacion_rechazada;
	}

	function getPonderacion($carrera_codigo=null,$codigo_postulacion=null){		
		$sql = "
			SELECT
				Asignatura.CODIGO_ASIGNATURA,
				Asignatura.NOMBRE_ASIGNATURA,
				UnidadCompetenciaAsignatura.CODIGO_UNIDAD_COMP
			FROM
				RAP_ASIGNATURAS Asignatura
				JOIN RAP_UNIDAD_COMP_ASIGNATURA UnidadCompetenciaAsignatura ON (
					UnidadCompetenciaAsignatura.codigo_asignatura = Asignatura.codigo_asignatura
				)
				JOIN RAP_COMPETENCIA_UNIDAD_COMP c ON (
					c.codigo_unidad_comp = UnidadCompetenciaAsignatura.codigo_unidad_comp
					AND codigo_competencia IN (
						SELECT
							codigo_competencia
						FROM
							RAP_COMPETENCIA_CARRERAS
						WHERE
							codigo_carrera = '{$carrera_codigo}'
					)
				)
			ORDER BY
				NOMBRE_ASIGNATURA
		";
		//prx($sql);
		#debug($sql);
		$result = $this->query($sql);
		//prx($result);
		foreach($result as $asignaturaUnidad){
			$new_result[$asignaturaUnidad['Asignatura']['codigo_asignatura']]['Asignatura']['nombre'] = $asignaturaUnidad['Asignatura']['nombre_asignatura'];
			$new_result[$asignaturaUnidad['Asignatura']['codigo_asignatura']]['Asignatura']['codigo'] = $asignaturaUnidad['Asignatura']['codigo_asignatura'];
			$new_result[$asignaturaUnidad['Asignatura']['codigo_asignatura']]['Unidades'][] = $asignaturaUnidad['UnidadCompetenciaAsignatura']['codigo_unidad_comp'];
		}
		$asignaturas = $new_result;
		//prx($asignaturas);
		$sql_indicadores = "
			SELECT 
				IndicadorPonderacion.INDICADOR,
				IndicadorPonderacion.PORCENTAJE 
			FROM 
				RAP_INDICADORES_PONDERACION IndicadorPonderacion
		";
		$result_indicadores = $this->query($sql_indicadores);
		foreach($result_indicadores as $indicador){
			$new_indicadores[$indicador['IndicadorPonderacion']['indicador']] =$indicador['IndicadorPonderacion']['porcentaje'];  
		}
		$indicadores = $new_indicadores;		
		$sql_autoevaluacion = "
			SELECT
				AutoEvaluacion.INDICADOR,
				AutoEvaluacion.UNIDAD_COMPETENCIA_CODIGO
			FROM
				RAP_AUTOEVALUACION AutoEvaluacion
			WHERE
				POSTULACION_CODIGO = '{$codigo_postulacion}'
		";
		$new_autoevaluacion = array();
		$result_autoevaluacion = $this->query($sql_autoevaluacion);
		//prx($result_autoevaluacion);
		foreach($result_autoevaluacion as $autoevaluacion){
			$new_autoevaluacion[$autoevaluacion['AutoEvaluacion']['unidad_competencia_codigo']] = $autoevaluacion['AutoEvaluacion']['indicador'];
		}
		$auto_evaluacion = $new_autoevaluacion;
		$ponderacion = array();
		//prx($asignaturas);
		foreach($asignaturas as $codigo_asignatura => $asignatura){
			$total_unidades_asignatura = count($asignatura['Unidades']);
			$suma_asignatura = 0;
			foreach($asignatura['Unidades'] as $unidad_competencia){
				if(isset($auto_evaluacion[$unidad_competencia])){
					$indicador_unidad = (int)$auto_evaluacion[$unidad_competencia];
					$porcentaje_unidad = $indicadores[$indicador_unidad];
				}else{
					$porcentaje_unidad = 0;	
				}
				$suma_asignatura =$suma_asignatura+$porcentaje_unidad; 
			}
			$porcentaje_asignatura = round($suma_asignatura/$total_unidades_asignatura);
			$ponderacion[$codigo_asignatura]['porcentaje'] = $porcentaje_asignatura;
			$ponderacion[$codigo_asignatura]['asignatura'] = $asignatura['Asignatura']['nombre'];
			$ponderacion[$codigo_asignatura]['sigla'] = $asignatura['Asignatura']['codigo'];
		}
		/*Ordenar la ponderacion segun los porcentajes de mayor a menor*/
		/*foreach($ponderacion as $asignaturas as $asignatura){
			$new_ponderacion[] =  
		}*/
		
		
		#$a = $ponderacion;
		
		usort($ponderacion, "self::ordenarPonderacion");
		#pr($ponderacion);
		#exit(); 
		/*
		pr($auto_evaluacion);
		pr($indicadores);
		pr($asignaturas);
		
		exit();*/
		//prx($ponderacion);
		return $ponderacion;
	}

	function ordenarPonderacion($a, $b){
		$a = $a['porcentaje'];
		$b = $b['porcentaje'];
	    if ($a == $b) {
	        return 0;
	    }
	    return ($a > $b) ? -1 : 1;
	}
	
	
	/* OBTENER ESTADO ADMISIONES DE HORIZONTAL Y ADMISIÓN VERTICAL */
	function obtenerEstadoAdmisiones($codigo_postulacion){
		$postulacion = $this->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));		
		if (($postulacion['Postulacion']['revision'] == null) || ($postulacion['Postulacion']['revision'] == 0)){
			return 'EN REVISIÓN';
		}
		elseif ( ($postulacion['Postulacion']['revision'] == 1) && ($postulacion['Postulacion']['habilitado'] == 1) && ($postulacion['Postulacion']['firma'] == 0) ) {
			return '<span style="color:#66d41c">HABILITADO</span>';
		}
		elseif ( ($postulacion['Postulacion']['revision'] == 1) && ($postulacion['Postulacion']['habilitado'] == 1) && ($postulacion['Postulacion']['firma'] == 1) ) {
			return '<span style="color:#66d41c">HABILITADO CON FIRMA</span>';
		}
		else{
			return '<span style="color:#e12d2d">NO HABILITADO</span>';
		}	
	}
	
		
	//OBTIENE EL ESTADO DE POSTULACIÓN EN RAP Y LO DEVUELVE EN UN STRING	
	function estadoRAP($codigoPostulacion){		
		$postulacion = $this->find('first', array('conditions' => array('Postulacion.codigo' => $codigoPostulacion)));		
		App::import('model','EstadoPostulacion');		
		$EstadoPostulacion = new EstadoPostulacion;
		App::import('model','EvidenciasPrevias');
		$EvidenciasPrevias = new EstadoPostulacion;
		$estado = $EstadoPostulacion->find('first', array('conditions' => array('postulacion_codigo' => $codigoPostulacion), 'order' => array('estado_codigo DESC')));				
		$estado2 = '';
		if(!empty($estado)){	
			$estado = $estado['EstadoPostulacion']['estado_codigo'];		
			switch ($estado){
				case '8':
					$evidencias = $EvidenciasPrevias->find('first', array('conditions' => array('postulacion_codigo' => $codigoPostulacion, 'validar' => '1', 'preliminar' => '0')));
					if (!empty($evidencias)){
						$estado2 = 'EVIDENCIAS FINALES VALIDADAS';
					}
					else{
						$estado2 = "ENTREVISTA AGENDADA";
					}
					break;
				case '7':
					$estado2 =  "<span class='rojo'>NO HABILITADO</SPAN>";
					break;
				case '9':
					$estado2 =  "<span class='verde'>HABILITADO</span>";
					break;
				case '6':
					$evidencias = $EvidenciasPrevias->find('first', array('conditions' => array('postulacion_codigo' => $codigoPostulacion, 'validar' => '1', 'preliminar' => '1')));
					if (!empty($evidencias)){
						$estado2 = 'ENTREVISTA';
					}
					else {
						$estado = 'CV RAP Y AUTOEVALUACIÓN COMPLETA EN REVISIÓN';
					}					
					break;
				case '5':
					$estado2 =  "CV RAP Y AUTOEVALUACIÓN COMPLETA EN REVISIÓN";
					break;
				case '3':
					$estado2 =  "DOCUMENTACIÓN APROBADA";
					break;
				case '2':
					$estado2 =  "DOCUMENTACIÓN RECIBIDA EN REVISIÓN";
					break;
				case '1':
					$estado2 =  "FORMULARIO DE POSTULACIÓN COMPLETADO";
					break;			
			}	
		}
		else{
			$estado2 = 'FORMULARIO PENDIENTE DE COMPLETAR';				
		}
		return $estado2;
	}
	
	
		
}