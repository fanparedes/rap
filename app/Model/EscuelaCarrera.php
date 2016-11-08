<?php

App::uses('AppModel', 'Model');
/* ESTE MODELO ES LA RELACIÓN MUCHOS A MUCHOS DE CARRERAS CON ESCUELAS */
class EscuelaCarrera extends AppModel {
	var $useTable = 'RAP_ESCUELAS_CARRERAS';
	var $primaryKey = 'ID';
	var $name = 'EscuelaCarrera';	


		var $belongsTo = array(
		'Escuela' => array(
			'className' => 'Escuela',
			'foreignKey' => 'escuela_codigo',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Carrera' => array(
			'className' => 'Carrera',
			'foreignKey' => 'carrera_codigo',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
