<?php

App::uses('AppModel', 'Model');

class Prepostulacion extends AppModel {
	
	var $useTable = 'RAP_PREPOSTULACIONES';
	var $primaryKey = 'ID';
	var $name = 'Prepostulacion';
	
	
	 public $hasOne = array(
        'Carrera' => array(
            'className' => 'Carrera',
            'conditions' => array(
				'Carrera.codigo = Prepostulacion.carrera_id',
			),
			'foreignKey'  => false,
            'associatedKey'   => 'carrera_codigo'
        ),
		'Postulante' => array(
            'className' => 'Postulante',
            'conditions' => array(
				'Postulante.codigo = Prepostulacion.postulante_codigo',
			),
			'foreignKey'  => false,
            'associatedKey'   => 'postulante_codigo'
        ),
		'Ciudad' => array(
            'className' => 'Ciudad',
            'conditions' => array(
				'Ciudad.codigo = Prepostulacion.ciudad_codigo',
			),
			'foreignKey'  => false,
            'associatedKey'   => 'ciudad_codigo'
        ),
		'Postulacion' => array(
            'className' => 'Postulacion',
            'conditions' => array(
				'Postulacion.codigo = Prepostulacion.codigo_postulacion',
			),
			'foreignKey'  => false,
            'associatedKey'   => 'codigo_postulacion'
        )
    );
	
/* 	public $hasMany = array(	
		'ArchivoPostulante' => array(
            'className' => 'ArchivoPostulante',
            'conditions' => array(
				'SUBSTR(ArchivoPostulante.codigo,4,20) = Prepostulacion.postulante_codigo',
			),
			'foreignKey'  => false,
            'associatedKey'   => 'postulante_codigo'
        ));
 */
}
